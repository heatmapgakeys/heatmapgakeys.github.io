<?php

namespace App\Http\Controllers;

use App\Mail\SimpleHtmlEmail;
use App\Models\Usage_log;
use App\Services\AutoResponder\AutoResponderServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Services\TelegramServiceInterface;
use App\Services\WhatsappServiceInterface;
use App\Services\SmsManagerServiceInterface;

class Home extends BaseController
{
    public $user_id = '';
    public $user_type = '';
    public $manager_id = '';
    public $is_admin = false;
    public $is_agent = false;
    public $is_member = false;
    public $is_manager = false; // is manger can be true despite of is_admin/is_agent = true
    public $is_team = false;
    public $is_affiliate = false;
    public $agent_has_whitelabel = false;
    public $current_package = '1';
    public $module_ids = [];
    public $team_access = null;
    public $monthly_limit = [];
    public $user_limit = '-1';
    public $parent_user_id = '1';
    public $parent_package_id = null;
    public $parent_parent_user_id = null;
    public $expired_date = null;
    public $module_id_no_of_website = 1;
    public $module_id_recorded_sessions = 2;
    public $module_id_month_of_data_sotrage = 3;
    public $module_id_affiliate_system = 12;
    public $module_id_team_member = 13;
    public $availatble_autoresponder_names = ['mailchimp','sendinblue','activecampaign','mautic'];

    public $is_rtl = false;

  protected function set_global_userdata($check_validity=false,$allowed_role=[], $denied_role=[] ,$module_id=null)
  {
      set_time_limit(0);
      $this->middleware('auth');
      $this->middleware(function ($request, $next) use ($check_validity,$allowed_role,$denied_role,$module_id) {
          if(Auth::user())
          {
              if(Auth::user()->status=='0') {
                  header('Location:'.route('logout'));
                  die;
              }
              $this->set_auth_variables();

              if(!empty($denied_role)){
                  $deny_access = false;
                  if(in_array('Admin',$denied_role) && $this->is_admin) $deny_access  = true;
                  if(in_array('Agent',$denied_role) && $this->is_agent) $deny_access  = true;
                  if(in_array('Member',$denied_role) && $this->is_member) $deny_access  = true;
                  if(in_array('Manager',$denied_role) && $this->is_manager) $deny_access  = true;
                  if($deny_access) abort('403');
              }

              if(!empty($allowed_role)){
                  $allow_access = false;
                  if(in_array('Admin',$allowed_role) && $this->is_admin) $allow_access  = true;
                  if(in_array('Agent',$allowed_role) && $this->is_agent) $allow_access  = true;
                  if(in_array('Member',$allowed_role) && $this->is_member) $allow_access  = true;
                  if(in_array('Manager',$allowed_role) && $this->is_manager) $allow_access  = true;
                  if(!$allow_access) abort('403');
              }
              if($check_validity) $this->check_member_validity();
              if(!$this->is_admin && !empty($module_id)){
                  if(!is_array($module_id) && !in_array($module_id,$this->module_ids)) abort('403');
                  else if(is_array($module_id) && count(array_intersect($this->module_ids,$module_id))==0) abort('403');
              }
              return $next($request);
          }
      });
  }

  protected function set_auth_variables(){

      if(!Auth::user()) return set_agency_config(null,get_agent_id());


      $parent_user_id = Auth::user()->parent_user_id;
      $this->parent_user_id = $parent_user_id;
      $this->parent_parent_user_id = $parent_parent_user_id = $this->parent_package_id = $parent_package_id = null;

      $manager_parent_data = $parent_user_id>0 ? DB::table('users')->where('id',$parent_user_id)->first() : null;
      $parent_parent_user_id = $manager_parent_data->parent_user_id ?? null;
      $parent_package_id = $manager_parent_data->package_id ?? null;
      $parent_expired_date = $manager_parent_data->expired_date ?? null;
      $parent_module_ids = [];
      if(!empty($parent_package_id)){
          $parent_package_data = $this->get_package($parent_package_id);
          $parent_module_ids = isset($parent_package_data->module_ids) ? explode(',',$parent_package_data->module_ids) : [];
      }

      if(Auth::user()->user_type=="Manager"){
          Auth::user()->user_type = $manager_parent_data->user_type ?? '';
          Auth::user()->manager_id = Auth::user()->id;
          Auth::user()->id = $manager_parent_data->id ?? '';
          Auth::user()->expired_date = Auth::user()->user_type!='Admin' ? $parent_expired_date : null;
          Auth::user()->parent_parent_user_id = $this->parent_parent_user_id = $parent_parent_user_id;
          Auth::user()->parent_package_id = $this->parent_package_id = $parent_package_id;
          $this->is_manager = true;
          $this->manager_id = Auth::user()->manager_id;
      }

      $user_id = Auth::user()->id;
      $this->user_id = $user_id;
      session(['auth_user_id' => $this->user_id]);

      $user_type = Auth::user()->user_type;


      if($user_type=='Admin') $this->is_admin = true;
      else if($user_type=='Agent') {
          $this->is_agent = true;
          $this->agent_has_whitelabel = Auth::user()->agent_has_whitelabel=='1';
      }
      else $this->is_member = true;

      if(Auth::user()->is_affiliate=='1') {
          $this->is_affiliate = true;
      }

      set_agency_config($this->user_id);

      $this->user_type = $user_type;
      $this->current_package = Auth::user()->package_id;
      if($this->is_member || $this->is_agent || $this->is_manager){
          $package_data = $this->get_package($this->current_package);
          $module_ids = isset($package_data->module_ids) ? explode(',',$package_data->module_ids) : [];
          if($parent_user_id>1){
              // if parent user doesnt have a module we are revoking that module from child
              foreach ($module_ids as $kmod=>$mod){
                  if(!in_array($mod,$parent_module_ids)) unset($module_ids[$kmod]);
              }
          }
          $this->is_trial = isset($package_data->is_default) ? (bool) $package_data->is_default : false;
          $team_access = isset($package_data->team_access) && !empty($package_data->team_access) ? json_decode($package_data->team_access,true) : null;
          $monthly_limit = isset($package_data->monthly_limit) && !empty($package_data->monthly_limit) ? json_decode($package_data->monthly_limit,true) : [];
          Auth::user()->module_ids = $this->module_ids = $module_ids;
          if(!empty($team_access)) Auth::user()->team_access = $this->team_access = $team_access;
          if(!empty($monthly_limit)) Auth::user()->monthly_limit = $this->monthly_limit = $monthly_limit;
          Auth::user()->user_limit = $this->user_limit = $package_data->user_limit ?? '-1';
      }
      $this->expired_date = Auth::user()->expired_date;
      return true;
  }

    public function set_module_ids($data=[]){
        $data['module_id_no_of_website'] = $this->module_id_no_of_website;
        $data['module_id_recorded_sessions'] = $this->module_id_recorded_sessions;
        $data['module_id_month_of_data_sotrage'] = $this->module_id_month_of_data_sotrage;
        $data['module_id_affiliate_system'] = $this->module_id_affiliate_system;
        $data['module_id_team_member'] = $this->module_id_team_member;
        return $data;
    }

    protected function viewcontroller($data=array())
    {
        $data = $this->set_module_ids($data);
        if (!isset($data['body'])) return false;
        if (!isset($data['iframe'])) $data['iframe'] = false;
        if (!isset($data['load_datatable'])) $data['load_datatable'] = false;
        $data['user_id'] = $this->user_id;
        $data['parent_parent_user_id'] = $this->parent_parent_user_id;
        $data['parent_package_id'] = $this->parent_package_id;
        $data['parent_user_id'] = $this->parent_user_id;
        $data['expired_date'] = $this->expired_date;
        $data['is_admin'] = $this->is_admin;
        $data['is_agent'] = $this->is_agent;
        $data['agent_has_whitelabel'] = $this->agent_has_whitelabel;
        $data['is_member'] = $this->is_member;
        $data['is_manager'] = $this->is_manager;
        $data['is_team'] = false;
        $data['is_affiliate'] = $this->is_affiliate;
        $data['user_module_ids'] = $this->module_ids;
        $data['team_access'] = $this->team_access;
        $data['monthly_limit'] = $this->monthly_limit;
        $data['is_rtl'] = $this->is_rtl ? '1' : '0';

        $data['notifications'] = $this->get_notifications();
        $data['route_name'] = Route::currentRouteName();
        $data['get_selected_sidebar'] = get_selected_sidebar($data['route_name']);
        $data['full_width_page_routes'] = full_width_page_routes();

        $allowed_domain_ids = $this->is_manager && !empty(Auth::user()->allowed_domain_ids) ? json_decode(Auth::user()->allowed_domain_ids,true) : [];
        $query = DB::table('visitor_analysis_domain_list')->select('id','domain_name','domain_code')->where(['user_id'=>$this->user_id,'deleted'=>'0']);
        if(!empty($allowed_domain_ids)) $query->whereIntegerInRaw('id',$allowed_domain_ids);
        $domains = $query->orderBy("id","DESC")->get();

        $data['domains'] = $domains;

        return view($data['body'], $data);
    }

    protected function get_landing_language($user_id=1){
        $get_data = DB::table('settings')->where('user_id',$user_id)->select(['agency_landing_settings','analytics_code'])->first();
        if(!isset($get_data->agency_landing_settings) || empty($get_data->agency_landing_settings)){
            $get_data = $get_data = DB::table('settings')->where('user_id',1)->select(['agency_landing_settings','analytics_code'])->first();
        }
        return $get_data;
    }

    protected function set_meta_data($get_landing_language=null){
        $this->metadata = [
            'title' => '',
            'meta_title' => $get_landing_language->company_title ?? '',
            'meta_description' => $get_landing_language->company_short_description ?? '',
            'meta_image' =>  $get_landing_language->company_cover_image ?? '',
            'meta_keyword' => $get_landing_language->company_keywords ?? '',
            'meta_author' => $get_landing_language->company_name ?? ''
        ];
    }

    public function make_view_data(){
        $user_id = get_agent_id();
        $this->set_auth_variables();
        $get_landing_language = $this->get_landing_language($user_id);
        $get_agency_landing = isset($get_landing_language->agency_landing_settings) ? json_decode($get_landing_language->agency_landing_settings) : [];
        $get_analytics_code = isset($get_landing_language->analytics_code) ? json_decode($get_landing_language->analytics_code,true) : [];
        $this->set_meta_data($get_agency_landing);
        $data = $this->metadata;
        $data['get_landing_language'] = $get_agency_landing;
        $data['get_analytics_code'] = $get_analytics_code;
        $data['disable_landing_page'] = $get_agency_landing->disable_landing_page ?? '0';
        $data['disable_ecommerce_feature'] = $get_agency_landing->disable_ecommerce_feature ?? '0';
        $data['disable_review_section'] = $get_agency_landing->disable_review_section ?? '0';
        return $data;
    }

    protected function site_viewcontroller($data=array())
    {
        if (!isset($data['body'])) return false;
        $user_id = $parent_user_id = $user_type = "";
        $expired_date = null;
        $is_admin = $is_agent = $is_member = $is_manager = $is_team = $agent_has_whitelabel = false;
        $enable_blog_comment = '0';
        if( Auth::user()){
            $user_type =  Auth::user()->user_type;
            $user_id =  Auth::user()->id;
            $parent_user_id =  Auth::user()->parent_user_id;
            $expired_date =  Auth::user()->expired_date;
            $enable_blog_comment =  Auth::user()->enable_blog_comment;
            $is_admin =  $user_type=="Admin";
            $is_agent =  $user_type=="Agent";
            $is_member =  $user_type=="Member";
            $is_manager =  $user_type=="Manager";
            $is_team =  false;
            $agent_has_whitelabel = $is_agent && Auth::user()->agent_has_whitelabel=='1';
        }
        $data['user_id'] = $user_id;
        $data['user_type'] = $user_type;
        $data['parent_user_id'] = $parent_user_id;
        $data['expired_date'] = $expired_date;
        $data['is_admin'] = $is_admin;
        $data['is_agent'] = $is_agent;
        $data['agent_has_whitelabel'] = $agent_has_whitelabel;
        $data['is_member'] = $is_member;
        $data['is_manager'] = $is_manager;
        $data['is_team'] = $is_team;
        $data['is_rtl'] = $this->is_rtl ? '1' : '0';
        $data['enable_blog_comment'] = $enable_blog_comment;

        $is_agency_site = check_is_agency_site();
        $data['is_agency_site'] = $is_agency_site;

        $meta_image_width = $meta_image_height = '';
        if(!empty($data['meta_image']) && !$is_agency_site){
            if (!filter_var($data['meta_image'], FILTER_VALIDATE_URL))
                $data['meta_image'] = asset($data['meta_image']);
            $meta_image_data = @getimagesize($data['meta_image']);
            $meta_image_width = $meta_image_data[0] ?? '';
            $meta_image_height = $meta_image_data[1] ?? '';
        }
        $data['meta_image_width'] = $meta_image_width;
        $data['meta_image_height'] = $meta_image_height;
        return view($data['body'], $data);
    }

    protected function docs_viewcontroller($data=array())
    {
        if (!isset($data['body'])) return false;

        $agent_user_id = get_agent_id();
        if(empty($agent_user_id)) $agent_user_id = 1;
        if(Auth::user()) set_agency_config(Auth::user()->id);
        else set_agency_config(null,$agent_user_id);

        $user_id = $parent_user_id = $user_type = "";
        $expired_date = null;
        $is_admin = $is_agent = $is_member = $is_manager = $is_team = $agent_has_whitelabel = false;
        $enable_blog_comment = '0';
        if( Auth::user()){
            $user_type =  Auth::user()->user_type;
            $user_id =  Auth::user()->id;
            $parent_user_id =  Auth::user()->parent_user_id;
            $expired_date =  Auth::user()->expired_date;
            $enable_blog_comment =  Auth::user()->enable_blog_comment;
            $is_admin =  $user_type=="Admin";
            $is_agent =  $user_type=="Agent";
            $is_member =  $user_type=="Member";
            $is_manager =  $user_type=="Manager";
            $is_team =  false;
            $agent_has_whitelabel = $is_agent && Auth::user()->agent_has_whitelabel=='1';
        }
        $data['user_id'] = $user_id;
        $data['user_type'] = $user_type;
        $data['parent_user_id'] = $parent_user_id;
        $data['expired_date'] = $expired_date;
        $data['is_admin'] = $is_admin;
        $data['is_agent'] = $is_agent;
        $data['agent_has_whitelabel'] = $agent_has_whitelabel;
        $data['is_member'] = $is_member;
        $data['is_manager'] = $is_manager;
        $data['is_team'] = $is_team;
        $data['enable_blog_comment'] = $enable_blog_comment;
        $data['agent_user_id'] = $agent_user_id;
        $data['is_rtl'] = $this->is_rtl ? '1' : '0';
        return view($data['body'], $data);
    }

    protected function bare_viewcontroller($data=array())
    {
        $data = $this->set_module_ids($data);
        if (!isset($data['body'])) return false;
        if (!isset($data['load_datatable'])) $data['load_datatable'] = false;

        $auth_check = Auth::check() ? true :false;
        $data['user_id'] = $auth_check ? $this->user_id : 0;
        $data['parent_user_id'] = $auth_check ? $this->parent_user_id : null;
        $data['expired_date'] = $auth_check ? $this->expired_date : null;
        $data['is_admin'] = $auth_check ? $this->is_admin : false;
        $data['is_agent'] = $auth_check ? $this->is_agent : false;
        $data['is_member'] = $auth_check ? $this->is_member : false;
        $data['user_module_ids'] =  $auth_check ? $this->module_ids : [];

        $data['route_name'] = Route::currentRouteName();
        $data['get_selected_sidebar'] = '';
        return view($data['body'], $data);
    }

    public function ecommerce_viewcontroller($data=[])
    {
        $data = $this->set_module_ids($data);
        if(!isset($data['load_datatable'])) $data['load_datatable'] = false;
        return view($data['body'], $data);
    }

    protected function ecommerce_send_messenger_reminder($message='',$bot_token='',$store_id=0,$subscriber_id='')
    {
        if(empty($subscriber_id) || strpos($subscriber_id, "sys") !== false){
            return $sent_response = array("response"=> __("Not a telegram subscriber, message sending was skipped."),"status"=>'1');
        }

        $sent_response = array();

        $telegram_service = app(TelegramServiceInterface::class);

        $telegram_service->bot_token = $bot_token;
        try
        {
            $method = 'sendMessage';
            $response = $telegram_service->send($method,$message);

            if(isset($response['ok']) && $response['ok']==false) {
                $sent_response = array("response"=> $response["description"],"status"=>'0');
            }
            else {
                $bot_data = $this->get_bot($bot_token,['user_id','id']);
                $user_id = $bot_data->user_id ?? null;
                $telegram_bot_id = $bot_data->id ?? null;
                $insert_livechat_data = [
                    'telegram_bot_subscriber_subscriber_id' => $subscriber_id,
                    'telegram_bot_id' => $telegram_bot_id,
                    'sender' => 'bot',
                    'message_content' => $message
                ];
                $this->insert_livechat_data($insert_livechat_data,$user_id);
                $sent_response = array("response"=>$response,"status"=>'1');
            }

        }
        catch(Exception $e) {
            $sent_response = array("response"=> $e->getMessage(),"status"=>'0');
        }
        return $sent_response;
    }

    protected function ecommerce_confirmation_message_sender($cart_id=0,$subscriber_id="")
    {
        if($cart_id==0 || $subscriber_id=="") return false;
        $cart_select = array("ecommerce_carts.*","store_unique_id","telegram_bot_id","messenger_content","sms_content","sms_api_id","email_content","email_api_id","email_subject","configure_email_table","telegram_bot_label_ids","store_name");

        $cart_data_2d = DB::table("ecommerce_carts")->select($cart_select)->leftJoin("ecommerce_stores","ecommerce_carts.ecommerce_store_id","=","ecommerce_stores.id")->where(["ecommerce_carts.telegram_bot_subscriber_subscriber_id"=>$subscriber_id,"ecommerce_carts.id"=>$cart_id,"ecommerce_stores.status"=>"1"])->get();

        if(!isset($cart_data_2d[0])) return false;

        $cart_data = $cart_data_2d[0];
        $store_unique_id = isset($cart_data->store_unique_id)?$cart_data->store_unique_id:'';
        $store_id = isset($cart_data->ecommerce_store_id)?$cart_data->ecommerce_store_id:'0';
        $user_id = isset($cart_data->user_id)?$cart_data->user_id:'0';
        $bot_id = isset($cart_data->telegram_bot_id)?$cart_data->telegram_bot_id:'0';
        $sms_api_id = isset($cart_data->sms_api_id)?$cart_data->sms_api_id:'0';
        $sms_content = (isset($cart_data->sms_content) && !empty($cart_data->sms_content)) ? json_decode($cart_data->sms_content,true) : array();
        $email_api_id = isset($cart_data->email_api_id)?$cart_data->email_api_id:'0';
        $email_content = (isset($cart_data->email_content) && !empty($cart_data->email_content)) ? json_decode($cart_data->email_content,true) : array();
        $configure_email_table = isset($cart_data->configure_email_table)?$cart_data->configure_email_table:'';
        $email_subject = isset($cart_data->email_subject)?$cart_data->email_subject:'{{store_name}} | Order Update';
        $messenger_content = (isset($cart_data->messenger_content) && !empty($cart_data->messenger_content)) ? json_decode($cart_data->messenger_content,true) : array();
        $action_type = isset($cart_data->action_type)?$cart_data->action_type:'checkout';
        $buyer_first_name = isset($cart_data->buyer_first_name)?$cart_data->buyer_first_name:'';
        $buyer_last_name = isset($cart_data->buyer_last_name)?$cart_data->buyer_last_name:'';
        $buyer_email = isset($cart_data->buyer_email)?$cart_data->buyer_email:'';
        $buyer_mobile = isset($cart_data->buyer_mobile)?$cart_data->buyer_mobile:'';
        $buyer_country = isset($cart_data->buyer_country)?$cart_data->buyer_country:'-';
        $buyer_state = isset($cart_data->buyer_state)?$cart_data->buyer_state:'-';
        $buyer_city = isset($cart_data->buyer_city)?$cart_data->buyer_city:'-';
        $buyer_address = isset($cart_data->buyer_address)?$cart_data->buyer_address:'-';
        $buyer_zip = isset($cart_data->buyer_zip)?$cart_data->buyer_zip:'-';
        $subtotal = isset($cart_data->subtotal)?$cart_data->subtotal:0;
        $payment_amount = isset($cart_data->payment_amount)?$cart_data->payment_amount:0;
        $currency = isset($cart_data->currency)?$cart_data->currency:'USD';
        $shipping = isset($cart_data->shipping)?$cart_data->shipping:0;
        $tax = isset($cart_data->tax)?$cart_data->tax:0;
        $coupon_code = isset($cart_data->coupon_code)?$cart_data->coupon_code:"";
        $discount = isset($cart_data->discount)?$cart_data->discount:0;
        $payment_method = isset($cart_data->payment_method)?$cart_data->payment_method:"Cash on Delivery";
        $ecom_store_name = isset($cart_data->store_name)?$cart_data->store_name:'';

        $checkout_url = route("ecommerce-store-carts",['cart_id'=>$cart_id,"subscriber_id"=>$subscriber_id]);
        $order_url = route("ecommerce-store-cart-order",['order_no'=>$cart_id,"subscriber_id"=>$subscriber_id]);
        $store_url = route("ecommerce-load-store",['store_unique_id'=>$store_unique_id,"subscriber_id"=>$subscriber_id]);
        $my_orders_url = route("ecommerce-store-my-orders",['store_id'=>$store_id,"subscriber_id"=>$subscriber_id]);

        if(empty($buyer_email)) $buyer_email = isset($cart_data->bill_email)?$cart_data->bill_email:'';
        if(empty($buyer_mobile)) $buyer_mobile = isset($cart_data->bill_mobile)?$cart_data->bill_mobile:'';


        $cart_info = DB::table("ecommerce_cart_items")->select(["quantity","product_name","unit_price","coupon_info","attribute_info","thumbnail","ecommerce_product_id","woocommerce_product_id"])->leftJoin("ecommerce_products","ecommerce_cart_items.ecommerce_product_id","=","ecommerce_products.id")->where("ecommerce_cart_id",$cart_id)->get();

        $curdate = date("Y-m-d H:i:s");
        $chat_id = '';
        if(strpos($subscriber_id,"sys-") === false) {
            $exploding_id = explode("-",$subscriber_id);
            $chat_id = $exploding_id[0];
        }

        $buyer_mobile = preg_replace("/[^0-9]+/", "", $buyer_mobile);

        $replace_variables = array(
            "store_name"=>$ecom_store_name,
            "store_url"=>$store_url,
            "order_no"=>$cart_id,
            "order_url"=>$order_url,
            "checkout_url"=>$checkout_url,
            "my_orders_url"=>$my_orders_url,
            "first_name"=>$buyer_first_name,
            "last_name"=>$buyer_last_name,
            "email"=>$buyer_email,
            "mobile"=>$buyer_mobile,
        );

        $checkout_info = array();
        $confirmation_response = array();
        if($action_type =='checkout') {
            $i=1;
            $cart_product_details = '<b><u>'.__('Products Details').'</u>: </b>';
            $cart_product_details .= "\r\n";
            $cart_product_details .= "\r\n";

            foreach ($cart_info as $key => $value)
            {
                if(empty($value->thumbnail)) $image_url = asset('assets/images/products/product-1.jpg');
                else $image_url = $value->thumbnail;

                if(isset($value->woocommerce_product_id) && !is_null($value->woocommerce_product_id) && $value->thumbnail!='')
                    $image_url = $value->thumbnail;

                $attribute_print = $attr_html ="";
                $attribute_info = json_decode($value->attribute_info,true);
                if(!empty($attribute_info))
                {
                    $attribute_print_tmp = array();
                    foreach ($attribute_info as $key2 => $value2)
                    {
                        $attribute_print_tmp[] = is_array($value2) ? implode('+', array_values($value2)) : $value2;
                    }
                    $attribute_print = implode(', ', $attribute_print_tmp);

                    $attr_html = "\n<b>".__('Attributes').": </b>".$attribute_print;
                }

                $cart_product_details .= '#'.$i.'. '.'<a href="'.url($image_url).'"><b>'.(isset($value->product_name) ? $value->product_name : "").'</b></a>';
                $cart_product_details .= $attr_html;
                $cart_product_details .= "\n";
                $cart_product_details .= '<b>Quantity: </b>'.(isset($value->quantity) ? $value->quantity : 1);
                $cart_product_details .= "\n";
                $cart_product_details .= "<b>Price: </b>".$currency.(isset($value->unit_price) ? $value->unit_price : 0);
                $cart_product_details .= "\n";
                $cart_product_details .= "\n";

                $i++;
                $update_sales_count_sql = "UPDATE ecommerce_products SET sales_count=sales_count+".$value->quantity." WHERE id=".$value->ecommerce_product_id;
                DB::update($update_sales_count_sql);
                $update_stock_count_sql = "UPDATE ecommerce_products SET stock_item=stock_item-".$value->quantity." WHERE stock_item>0 AND id=".$value->ecommerce_product_id;
                DB::update($update_stock_count_sql);
            }

            $replace_variables['cart_product_details'] = $cart_product_details;

            if(empty($buyer_address)) $buyer_address = '-';
            if(empty($buyer_city)) $buyer_city = '-';
            if(empty($buyer_zip)) $buyer_zip = '-';
            if(empty($buyer_state)) $buyer_state = '-';
            if(empty($buyer_country)) $buyer_country = '-';

            if($cart_data->store_pickup=='0')
                $address = array(
                    "street_1" => $buyer_address,
                    "street_2" => "",
                    "city" => $buyer_city,
                    "postal_code" => $buyer_zip,
                    "state" => $buyer_state,
                    "country" => $buyer_country
                );
            else
                $address = array(
                    "street_1" => "-",
                    "street_2" => "",
                    "city" => "-",
                    "postal_code" => "-",
                    "state" => "-",
                    "country" => "-"
                );

            $delivery_address_details = '<b><u>'.__("Delivery Address").'</u>: </b> '.$address['street_1'].', '.$address['city'].', '.$address['postal_code'].', '.$address['state'].', '.$address['country']."\n";
            $replace_variables['delivery_address_details'] = $delivery_address_details;

            $recipient_name = $buyer_first_name." ".$buyer_last_name;
            if(trim($recipient_name=="")) $recipient_name="-";

            $checkout_summary = '<b><u>'.__("Checkout Summary").'</u>: </b>';
            $checkout_summary .= "\n";
            if($coupon_code != "") {
                $checkout_summary .= '<b>'.__("Coupon Code").': </b>'.$coupon_code;
                $checkout_summary .= "\n";
                $checkout_summary .= '<b>'.__("Discount").': </b>'.$discount;
                $checkout_summary .= "\n";
            }
            $checkout_summary .= '<b>'.__("Subtotal").': </b>'.$currency.$subtotal;
            $checkout_summary .= "\n";
            $checkout_summary .= '<b>'.__("Shipping Cost").': </b>'.$shipping;
            $checkout_summary .= "\n";
            $checkout_summary .= '<b>'.__("Total Tax").': </b>'.$tax;
            $checkout_summary .= "\n";
            $checkout_summary .= '<b>'.__("Total Cost").': </b>'.$currency.$payment_amount;
            $checkout_summary .= "\n";
            $checkout_summary .= '<b>'.__("Paid with").': </b>'.$payment_method;
            $checkout_summary .= "\n";

            $replace_variables['checkout_summary'] = $checkout_summary;

            // Messenger send block
            $sent_response = array();
            $bot_info = DB::table("telegram_bots")->where("id",$bot_id)->get();
            $bot_token = isset($bot_info[0]->bot_token) ? $bot_info[0]->bot_token : "";
            // template 1
            $intro_text = isset($messenger_content["checkout"]["checkout_text"]) ? $messenger_content["checkout"]["checkout_text"] : "";
            if($intro_text!="")
            {
                $intro_text = $this->spin_and_replace($intro_text,$replace_variables);

                $telegram_confirmation_temp1 = json_encode(['chat_id'=>$chat_id,'text'=>$intro_text,'parse_mode'=>"HTML"]);
                $this->ecommerce_send_messenger_reminder($telegram_confirmation_temp1,$bot_token,$store_id,$subscriber_id);
            }

            // template 2
            $telegram_confirmation_temp2 = isset($messenger_content["checkout"]["checkout_text_confirm"]) ? $messenger_content["checkout"]["checkout_text_confirm"] : "";

            if($telegram_confirmation_temp2 != "") {

                $telegram_confirmation_temp2 = $this->spin_and_replace($telegram_confirmation_temp2,$replace_variables);

                $telegram_confirmation_temp2 = json_encode(['chat_id'=>$chat_id,"text"=>$telegram_confirmation_temp2,"parse_mode"=>"HTML"]);
                $sent_response = $this->ecommerce_send_messenger_reminder($telegram_confirmation_temp2,$bot_token,$store_id,$subscriber_id);
            }

            // template 3
            $after_checkout_text = isset($messenger_content["checkout"]["checkout_text_next"]) ? $messenger_content["checkout"]["checkout_text_next"] : "";
            $after_checkout_btn = isset($messenger_content["checkout"]["checkout_btn_next"]) ? $messenger_content["checkout"]["checkout_btn_next"] : "MY ORDERS";
            if($after_checkout_text!="")
            {
                $after_checkout_text = $this->spin_and_replace($after_checkout_text,$replace_variables);
                $messenger_confirmation_template3 = $after_checkout_text."\r\n"."<a href='".$my_orders_url."'>".$after_checkout_btn."</a>";
                $messenger_confirmation_template3 = json_encode(['chat_id'=>$chat_id,"text"=>$messenger_confirmation_template3,"parse_mode"=>'HTML']);

                $this->ecommerce_send_messenger_reminder($messenger_confirmation_template3,$bot_token,$store_id,$subscriber_id);
            }
            $confirmation_response['messenger'] = $sent_response;
            // Messenger send block


            //  SMS Sending block
            if($buyer_mobile!="" && $sms_api_id!='0')
            {
                $checkout_text_sms = isset($sms_content['checkout']['checkout_text']) ? $this->spin_and_replace($sms_content['checkout']['checkout_text'],$replace_variables,false) : "";
                $checkout_text_sms = str_replace(array("'",'"'),array('`','`'),$checkout_text_sms);

                $sms_response = array("response"=> 'missing param',"status"=>'0');

                if(trim($checkout_text_sms)!="")
                {
                    try
                    {
                        $response = $this->send_sms_using_api_id($sms_api_id, $buyer_mobile,$checkout_text_sms, $user_id);

                        if(isset($response['ok']) && $response['ok']==true) {
                            $message_sent_id = $response['description'];
                            $sms_response = array("response"=> $message_sent_id,"status"=>'1');
                        }
                        else {
                            $message_sent_id = $response["description"];
                            $sms_response = array("response"=> $message_sent_id,"status"=>'0');
                        }

                    }
                    catch(Exception $e)
                    {
                        $message_sent_id = $e->getMessage();
                        $sms_response = array("response"=> $message_sent_id,"status"=>'0');
                    }
                }

                $confirmation_response['sms']=$sms_response;
            }
            //  SMS Sending block

            //  Email Sending block
            if($buyer_email!="" && $email_api_id!='0')
            {
                $checkout_text_email = isset($email_content['checkout']['checkout_text']) ? $this->spin_and_replace($email_content['checkout']['checkout_text'],$replace_variables,false) : "";
                $email_subject = $this->spin_and_replace($email_subject,$replace_variables,false);
                $from_email = "";

                $email_response = array("response"=> 'missing param',"status"=>'0');
                if(trim($checkout_text_email)!='')
                {
                    try
                    {

                        $response = $this->send_email_using_api_id($email_api_id, $buyer_email, $checkout_text_email, $email_subject, $user_id);
                        if(isset($response['status']) && !empty($response['status'])) {
                            $message_sent_id = $response['status'];
                            $email_response = array("response"=> $message_sent_id,'status'=>'1');
                        }
                        else {
                            $message_sent_id = $response;
                            $email_response = array("response"=> $message_sent_id,'status'=>'0');
                        }
                    }
                    catch(Exception $e) {
                        $email_response = ['response'=>$e->getMessage(),'status'=>'0'];
                    }
                }
                $confirmation_response['email']=$email_response;
            }
            //  Email Sending block
            $confirmation_response = json_encode($confirmation_response);
            DB::table("ecommerce_carts")->where(['id'=>$cart_id,"telegram_bot_subscriber_subscriber_id"=>$subscriber_id])->update(['confirmation_response'=>$confirmation_response]);
            if($coupon_code!="") {
                $coupon_used_sql = "UPDATE ecommerce_coupons SET used=used+1 WHERE coupon_code='".$coupon_code."' AND ecommerce_store_id=".$store_id;
                DB::update($coupon_used_sql);
            }

        }
        // end checkout action


    }

    protected function spin_and_replace($str="",$replace = array(),$is_spin=true)
    {
        if(!isset($replace['store_name'])) $replace['store_name'] = '';
        if(!isset($replace['store_url'])) $replace['store_url'] = '';
        if(!isset($replace['order_no'])) $replace['order_no'] = '';
        if(!isset($replace['order_url'])) $replace['order_url'] = '';
        if(!isset($replace['checkout_url'])) $replace['checkout_url'] = '';
        if(!isset($replace['my_orders_url'])) $replace['my_orders_url'] = '';
        if(!isset($replace['first_name'])) $replace['first_name'] = '';
        if(!isset($replace['last_name'])) $replace['last_name'] = '';
        if(!isset($replace['email'])) $replace['email'] = '';
        if(!isset($replace['mobile'])) $replace['mobile'] = '';
        if(!isset($replace['cart_product_details'])) $replace['cart_product_details'] = '';
        if(!isset($replace['delivery_address_details'])) $replace['delivery_address_details'] = '';
        if(!isset($replace['checkout_summary'])) $replace['checkout_summary'] = '';
        $replace_values = array_values($replace);
        $str = str_replace(array("{{store_name}}","{{store_url}}","{{order_no}}","{{order_url}}","{{checkout_url}}","{{my_orders_url}}","{{first_name}}","{{last_name}}","{{email}}","{{mobile}}","{{cart_product_details}}","{{delivery_address}}","{{checkout_summary}}"), $replace_values, $str);

        if($is_spin) return spintax_process($str);
        else return $str;
    }

    protected function spin_and_replace_notification($str="",$replace = array(),$is_spin=true)
    {
        if(!isset($replace['store_name'])) $replace['store_name'] = '';
        if(!isset($replace['store_url'])) $replace['store_url'] = '';
        if(!isset($replace['order_no'])) $replace['order_no'] = '';
        if(!isset($replace['order_status'])) $replace['order_status'] = '';
        if(!isset($replace['invoice_url'])) $replace['invoice_url'] = '';
        if(!isset($replace['update_note'])) $replace['update_note'] = '';
        if(!isset($replace['first_name'])) $replace['first_name'] = '';
        if(!isset($replace['last_name'])) $replace['last_name'] = '';
        if(!isset($replace['my_orders_url'])) $replace['my_orders_url'] = '';

        $replace_values = array_values($replace);
        $str = str_replace(array("{{store_name}}","{{store_url}}","{{order_no}}","{{order_status}}","{{invoice_url}}","{{update_note}}","{{first_name}}","{{last_name}}","{{my_orders_url}}"), $replace_values, $str);
        if($is_spin) return spintax_process($str);
        else return $str;
    }

    protected function insert_usage_log($module_id=0,$usage_count=0,$user_id=0)
    {
        if($module_id==0 || $usage_count==0) return false;
        if($user_id==0) $user_id=$this->user_id;
        if($user_id==0 || $user_id=="") return false;

        $usage_month=date("n");
        $usage_year=date("Y");

        $where=array("module_id"=>$module_id,"user_id"=>$user_id,"usage_month"=>$usage_month,"usage_year"=>$usage_year);

        // insert new entry if not exit, increment usage_count otherwise
        $usage_log = Usage_log::firstOrNew($where);
        $usage_log->usage_count = ($usage_log->usage_count + $usage_count);
        $usage_log->save();

        return true;
    }

    protected function delete_usage_log($module_id=0,$usage_count=0,$user_id=0)
    {
        if($module_id==0 || $usage_count==0) return false;
        if($user_id==0) $user_id=$this->user_id;
        if($user_id==0 || $user_id=="") return false;

        $usage_month=date("n");
        $usage_year=date("Y");

        $where=array("module_id"=>$module_id,"user_id"=>$user_id,"usage_month"=>$usage_month,"usage_year"=>$usage_year);

        // insert new entry if not exit, decrement usage_count otherwise
        $usage_log = Usage_log::firstOrNew($where);
        if($usage_log) $usage_log->usage_count = ($usage_log->usage_count - $usage_count);
        else $usage_log->usage_count = 0;
        $usage_log->save();

        return true;
    }

    protected function check_usage($module_id=0,$request=0,$user_id=0)
    {
        if($this->is_admin) return '1';

        if($module_id==0 || $request==0) return "0";
        if($user_id==0) $user_id=$this->user_id;
        if($user_id==0 || $user_id=="") return false;

        $usage_month=date("n");
        $usage_year=date("Y");

        $module = DB::table('modules')->select('extra_text')->where('id',$module_id)->first();
        $extra_text = $module->extra_text;

        if($extra_text=="") $where = [
            ['module_id', '=', $module_id],
            ['user_id', '=', $user_id]
        ];
        else $where = [
            ['module_id', '=', $module_id],
            ['user_id', '=', $user_id],
            ['usage_month', '=', $usage_month],
            ['usage_year', '=', $usage_year]
        ];

        $usage_count = Usage_log::where($where)->sum('usage_count');

        $monthly_limit=array();
        $bulk_limit=array();
        $module_ids=array();

        $package_id = $this->is_manager ? $this->parent_package_id : $this->current_package;
        $package_info = DB::table('packages')->where('id',$package_id)->first();

        if(isset($package_info->bulk_limit))    $bulk_limit=json_decode($package_info->bulk_limit,true);
        if(isset($package_info->monthly_limit)) $monthly_limit=json_decode($package_info->monthly_limit,true);
        if(isset($package_info->module_ids))    $module_ids=explode(',', $package_info->module_ids);

        $return = "0";
        if(in_array($module_id, $module_ids) && $bulk_limit[$module_id] > 0 && $bulk_limit[$module_id]<$request)
            $return = "2"; // bulk limit crossed | 0 means unlimited
        else if(in_array($module_id, $module_ids) && $monthly_limit[$module_id] > 0 && $monthly_limit[$module_id]<($request+$usage_count))
            $return = "3"; // montly limit crossed | 0 means unlimited
        else  $return = "1"; //success

        return $return;
    }

    protected function print_limit_message($module_id=0,$request=0)
    {
        $status=$this->check_usage($module_id,$request);
        if($status=="2") {
            Session::flash('module_limit_exceed_message', __("Sorry, bulk action limit has been exceeded for this module."));
            return false;
        }
        else if($status=="3") {
            Session::flash('module_limit_exceed_message', __("Sorry, usage limit has been exceeded for this module."));
            return false;
        }
        return true;
    }

    protected function check_member_validity()
    {
        $pricing_link = $this->parent_user_id==1 ? env('APP_URL').'/pricing' : route('pricing-plan');
        if(!$this->is_admin)
        {
            $expire_date = $this->expired_date;
            if($expire_date=='0000-00-00 00:00:00' || $expire_date==null) return true;
            $expire_date = strtotime($expire_date);
            $current_date = strtotime(date("Y-m-d"));
            $package_id = $this->is_manager ? $this->parent_package_id : $this->current_package;
            $package_info = DB::table('packages')->where('id',$package_id)->first();
            $price = isset($package_info->price) ? $package_info->price : 0;
            if($price=="Trial") $price=1;
            if ($expire_date < $current_date && ($price>0 && $price!="")) {
                header('Location:'.$pricing_link);
                die;
            }
        }
        if($this->parent_user_id > 1)
        {
            $parent_user_data = DB::table('users')->select('expired_date','parent_user_id')->where(['id'=>$this->parent_user_id,'status'=>'1','deleted'=>'0'])->first();
            if(!isset($parent_user_data)) abort('403');
            $expire_date = isset($parent_user_data->expired_date) ? $parent_user_data->expired_date : null;
            if($expire_date=='0000-00-00 00:00:00' || $expire_date==null) return true;
            $expire_date = strtotime($expire_date);
            $current_date = strtotime(date("Y-m-d"));
            if ($expire_date < $current_date) abort('403');
        }
        return true;

    }

    protected function check_subscriber_limit($offset=0,$user_id=null,$return_count=false){
        if(empty($user_id)) $user_id = $this->user_id;
        $select_user = ['user_type','parent_user_id','package_id','monthly_limit'];
        $package_info = DB::table('users')
            ->select($select_user)
            ->where(['users.id'=>$user_id])
            ->leftJoin('packages','users.package_id','=','packages.id')->first();
        $user_type = $package_info->user_type ?? 'Member';
        $parent_user_id = $package_info->parent_user_id ?? 1;
        $limit_exceed = false;
        if($user_type!='Admin'){
            if($parent_user_id>1 || $user_type=='Agent'){ // agent or agent's end user
                $where_parent_user_info = $user_type=='Agent' ? ['users.id'=>$user_id] : ['users.id'=>$parent_user_id];
                $parent_user_info = DB::table('users')->select(['subscriber_limit'])->where($where_parent_user_info)
                    ->leftJoin('packages','users.package_id','=','packages.id')->first();
                $bot_subscriber_limit_agent = $parent_user_info->subscriber_limit ?? 0;

                // count all subscribe of agent and his child users
                $all_child_user_where = $user_type=='Agent' ? ['parent_user_id'=>$user_id] : ['parent_user_id'=>$parent_user_id] ;
                $all_child_user = DB::table('users')->select('id')->where($all_child_user_where)->get();
                $child_user_ids = $user_type=='Agent' ? [$user_id] : [$parent_user_id];
                foreach ($all_child_user as $keyuser => $valueuser){
                    $child_user_ids[] = $valueuser->id;
                }
                $query = DB::table('telegram_bot_subscribers')
                    ->select('id')->where(['subscriber_type'=>'telegram']);
                if(!empty($child_user_ids)) $query->whereIntegerInRaw('user_id',$child_user_ids);
                $count_subscriber_agent_telegram = $query->count();
                $query = DB::table('whatsapp_bot_subscribers')
                    ->select('id')->where(['subscriber_type'=>'whatsapp']);
                if(!empty($child_user_ids)) $query->whereIntegerInRaw('user_id',$child_user_ids);
                $count_subscriber_agent_whatsapp = $query->count();
                $count_subscriber_agent = $count_subscriber_agent_telegram + $count_subscriber_agent_whatsapp + $offset;

                if($return_count) return $count_subscriber_agent; //return count not error
                if($bot_subscriber_limit_agent>0 && $count_subscriber_agent>=$bot_subscriber_limit_agent) $limit_exceed = true;
            }
            else{
                $monthly_limit = $package_info->monthly_limit ?? null;
                $monthly_limit = !empty($monthly_limit) ? json_decode($package_info->monthly_limit,true) : [];
                $module_id_bot_subscriber = $this->module_id_bot_subscriber;
                $bot_subscriber_limit = $monthly_limit[$module_id_bot_subscriber] ?? 0;

                $count_subscriber_telegram = DB::table('telegram_bot_subscribers')
                    ->select('id')
                    ->where(['user_id'=>$user_id,'subscriber_type'=>'telegram'])->count();
                $count_subscriber_whatsapp = DB::table('whatsapp_bot_subscribers')
                    ->select('id')
                    ->where(['user_id'=>$user_id,'subscriber_type'=>'whatsapp'])->count();
                $count_subscriber = $count_subscriber_telegram + $count_subscriber_whatsapp + $offset;

                if($return_count) return $count_subscriber; //return count not error
                if($bot_subscriber_limit>0 && $count_subscriber>=$bot_subscriber_limit) $limit_exceed = true;
            }
        }
        return $limit_exceed;
    }

    public function get_notifications($user_id=0)
    {
        $last_days = date('Y-m-d', strtotime('-15 days', strtotime(date("Y-m-d"))));
        $last_days = $last_days." 00:00:00";
        if($user_id==0) $user_id = $this->user_id;
        $where = "((user_id={$user_id} AND is_seen='0') OR (user_id=0 AND NOT FIND_IN_SET('".$user_id."', seen_by))) AND created_at>='".$last_days."'";
        $notifications =  DB::table('notifications')->whereRaw($where)->orderByRaw('created_at DESC')->get();
        return $notifications;
    }

    protected function get_domain_list($user_id=0,$only_allowed_bots=true)
    {
        if($only_allowed_bots && $this->is_manager && empty(Auth::user()->allowed_domain_ids)) return [];

        if($user_id == 0) $user_id = $this->user_id;
        $allowed_domain_ids = $only_allowed_bots && $this->is_manager && !empty(Auth::user()->allowed_domain_ids) ? json_decode(Auth::user()->allowed_domain_ids,true) : [];

        $query = DB::table('visitor_analysis_domain_list')->select(['id','domain_name'])->where(['user_id'=>$this->user_id]);
        if(!empty($allowed_domain_ids)) $query->whereIntegerInRaw('id',$allowed_domain_ids);
        $get = $query->orderBy('domain_name','asc')->get();
        $result = [];

        foreach ($get as $key=>$val){
            $result[$val->id] = $val->domain_name;
        }
        return $result;
    }



    protected function get_payment_config($user_id=0,$select='*')
    {
        if($user_id == 0) $user_id = $this->user_id;
        return DB::table('settings_payments')->select($select)->where(['user_id'=>$user_id])->whereNull('ecommerce_store_id')->first();
    }

    protected function get_payment_config_parent($parent_user_id=0,$select='*')
    {
        if($parent_user_id == 0) $parent_user_id = Auth::user()->parent_user_id;
        return DB::table('settings_payments')->select($select)->where(['user_id'=>$parent_user_id,'users.status'=>'1','users.deleted'=>'0'])->whereNull('ecommerce_store_id')->leftJoin('users', 'users.id', '=', 'settings_payments.user_id')->first();
    }

    protected function get_payment_status()
    {
        return array('pending'=>__('Pending'),'approved'=>__('Approved'),'rejected'=>__('Rejected'),'shipped'=>__('Shipped'),'delivered'=>__('Delivered'),'completed'=>__('Completed'));
    }

    protected function get_user($id=0,$select='*')
    {
        if($id==0) return null;
        $user_data = DB::table("users")->select($select)->where(['id' => $id])->first();
        return $user_data;
    }

    protected function get_modules($team_package=false)
    {
        $query = DB::table('modules')->where('status','1');
        if($team_package) $query->where('team_module','1');
        else $query->where('subscription_module','1');
        return $query->orderBy('sl','asc')->get();
    }

    protected function get_package($id=0,$select='*',$where='')
    {
        if($id==0) $id = $this->current_package;
        if(empty($where)) $where = ['id'=>$id];
        return DB::table('packages')->select($select)->where($where)->first();
    }

    protected function get_packages($select='*',$team_package=false)
    {
        $query =  DB::table('packages')->select($select)->where(['user_id'=>$this->user_id,'deleted'=>'0']);
        if($team_package) $query->where('package_type','team');
        else $query->where('package_type','subscription');
        return $query->orderBy('package_name','asc')->get();
    }

    protected function get_packages_all($select='*')
    {
        $query =  DB::table('packages')->select($select)->where(['user_id'=>$this->user_id,'deleted'=>'0']);
        return $query->orderByRaw('package_type asc,package_name asc')->get();
    }

    protected function get_packages_parent($parent_user_id=0,$select='*',$team_package=false)
    {
        if($parent_user_id == 0) $parent_user_id = Auth::user()->parent_user_id;
        $query = DB::table('packages')->select($select)
            ->where(["user_id"=>$parent_user_id,"is_default"=>"0","visible"=>"1","deleted"=>"0"]);
        if($team_package) {
            $query->where('package_type','team');
        }
        else {
            $query->where('price','>',0)->where('validity','>',0)->where('package_type','subscription');
        }
        return $query->orderByRaw('CAST(price AS SIGNED)')->get();
    }

    protected function get_validity_types()
    {
        return array('D' => __('Days'), 'W' => __('Weeks'), 'M' => __('Months'), 'Y' => __('Years'));
    }

    protected function get_payment_formatting_data(){
        $user_id = get_agent_id();
        if(empty($user_id)) $user_id = 1;
        $payment_config = DB::table('settings_payments')
            ->whereNull('ecommerce_store_id')->where('user_id',$user_id)
            ->select(['currency','decimal_point','thousand_comma','currency_position'])->first();
        return $format_settings = ['currency'=>$payment_config->currency ?? 'USD','decimal_point'=>$payment_config->decimal_point ?? null,'thousand_comma'=>$payment_config->thousand_comma ?? '0','currency_position'=>$payment_config->currency_position ?? 'left'];

    }

    protected function get_email_template($template_type='',$user_id=0)
    {
        if($user_id==0) $user_id = $this->user_id;
        if(empty($user_id) || empty($template_type)) return null;
        $user_ids = [0,$user_id];
        return  DB::table('settings_email_templates')->where(['template_type'=>$template_type])->whereIntegerInRaw('user_id',$user_ids)->orderBy('id','desc')->first();
    }

    public function get_sms_email_profiles($api_type='email',$assoc=true,$user_id=0,$select='*')
    {
        if($user_id==0) $user_id = $this->user_id;
        $info_type = DB::table('settings_sms_emails')->where('user_id','=',$user_id)->where('api_type','=',$api_type)->where('status','=','1')->orderByRaw('api_name ASC')->get();
        if(!$assoc) return $info_type;

        $return = [];
        foreach ($info_type as  $value)
        {
            $return[$value->id] = $value->api_name.' : '.$value->profile_name;
        }
        return $return;
    }

    public function get_enum_values($table,$column){
        $enum = DB::select(DB::raw('SHOW COLUMNS FROM '.$table.' WHERE Field = "'.$column.'"'));
        $return = [];
        if(!empty($enum)){
            $values_str = $enum[0]->Type ?? '';
            $values_str = ltrim($values_str,'enum(');
            $values_str = rtrim($values_str,')');
            $values = explode(',',$values_str);
            $return = array_map(function ($item) {
                return trim($item,"'");
            }, $values);
        }
        return $return;

    }

    protected function get_payment_validity_data($buyer_user_id=0,$package_id=0)
    {
        $package_data = $this->get_package($package_id,['package_name','is_agency','price','validity','discount_data','product_data']);
        $package_name = $package_data->package_name ?? '';
        $is_agency = $package_data->is_agency ?? '0';
        $discount_data = $package_data->discount_data ?? null;
        $product_data = $package_data->product_data ?? null;
        $price = $package_data->price ?? 0;
        $validity = $package_data->validity ?? 0;
        $validity_str='+'.$validity.' day';

        $prev_payment_info = DB::table('transaction_logs')->select('cycle_start_date','cycle_expired_date')
            ->where(['buyer_user_id'=>$buyer_user_id])->whereNotNull('package_id')
            ->orderByRaw('id DESC')->first();
        $prev_cycle_expired_date = $prev_payment_info->cycle_expired_date ?? '';
        $cycle_start_date = $cycle_expired_date = date('Y-m-d');
        if(empty($prev_cycle_expired_date)) $cycle_expired_date = date("Y-m-d",strtotime($validity_str,strtotime($cycle_start_date)));
        else if (strtotime($prev_cycle_expired_date) <= strtotime(date('Y-m-d'))) $cycle_expired_date = date("Y-m-d",strtotime($validity_str,strtotime($cycle_start_date)));
        else if (strtotime($prev_cycle_expired_date) > strtotime(date('Y-m-d')))
        {
            $cycle_start_date = date("Y-m-d",strtotime('+1 day',strtotime($prev_cycle_expired_date)));
            $cycle_expired_date = date("Y-m-d",strtotime($validity_str,strtotime($cycle_start_date)));
        }

        $user_data = DB::table("users")->where(['id'=>$buyer_user_id])->select('parent_user_id','email','name')->first();
        $parent_user_id = $user_data->parent_user_id ?? 0;
        $email = $user_data->email ?? '';
        $name = $user_data->name ?? '';

        return ['parent_user_id'=>$parent_user_id,'email'=>$email,'name'=>$name,'package_name'=>$package_name,'price'=>$price,'is_agency'=>$is_agency,'cycle_start_date'=>$cycle_start_date,'cycle_expired_date'=>$cycle_expired_date,'validity'=>$validity,'discount_data'=>$discount_data,'product_data'=>$product_data];
    }


    protected function complete_payment($insert_data=[],$is_agency=null,$is_whitelabel=null,$payment_type='')
    {
        $curtime = date("Y-m-d H:i:s");
        $last_payment_method = $payment_type;
        $user_email = $insert_data['user_email'] ?? '';
        $user_name = $insert_data['user_name'] ?? '';
        $package_name = $insert_data['package_name'] ?? '';
        $package_id = $insert_data['package_id'] ?? null;
        $paid_currency = $insert_data['paid_currency'] ?? "USD";
        $paid_amount = $insert_data['paid_amount'] ?? 0;
        $buyer_user_id = $insert_data['buyer_user_id'] ?? 0;
        $parent_user_id = $insert_data['user_id'] ?? 0;
        $cycle_expired_date = $insert_data['cycle_expired_date'] ?? null;
        $update_data = array
        (
            "bot_status"=>"1",
            "updated_at"=>$curtime,
            "purchase_date"=>$curtime,
            "last_payment_method"=>$last_payment_method
        );
        if(!empty($cycle_expired_date)) $update_data['expired_date'] = $cycle_expired_date;
        if(!empty($package_id)) $update_data['package_id'] = $package_id;
        if(!empty($is_agency)) $update_data['user_type'] = $is_agency=='1' ? 'Agent' : 'Member';
        if(!empty($is_whitelabel)) $update_data['agent_has_whitelabel'] = $is_whitelabel;

        $error = false;
        try {
            DB::beginTransaction();
            unset($insert_data['user_email']);
            unset($insert_data['user_name']);
            DB::table('transaction_logs')->insert($insert_data);
            DB::table('users')->where(['id'=>$buyer_user_id])->update($update_data);

            if($is_agency=='1')
            {
                $default_package_data =
                    [
                        'user_id' => $buyer_user_id,
                        'package_name' => 'Trial',
                        'module_ids' => '1,9,10,11,2,4,7,8',
                        'monthly_limit' => '{"1":"10","9":"5000","10":"0","11":"0","2":"0","4":"0","7":"10","8":"10"}',
                        'bulk_limit' => '{"1":"1","9":"0","10":"0","11":"0","2":"0","4":"0","7":"0","8":"0"}',
                        'price' => 'Trial',
                        'validity' => '30',
                        'validity_extra_info' => '1,M',
                        'is_default' => '1'
                    ];
                $check_default_package = DB::table('packages')->where(['user_id'=>$buyer_user_id,'is_default'=>'1'])->select('id')->first();
                if(is_null($check_default_package)) DB::table('packages')->insert($default_package_data);
            }

            $insert_data = [
                'title'=> __('Payment Confirmation'),
                'description'=> __('We have received your payment of')." {$paid_currency} {$paid_amount}",
                'created_at' => date("Y-m-d H:i:s"),
                'user_id' => $buyer_user_id,
                'color_class' => 'bg-success',
                'icon' => 'fas fa-shopping-bag',
                'published' => '1',
                'linkable' => '1',
                'custom_link' => route('transaction-log')
            ];
            DB::table("notifications")->insert($insert_data);
            $insert_data['title'] = __('New Payment Received');
            $insert_data['description'] =  __('You have received a payment of')." {$paid_currency} {$paid_amount}";
            $insert_data['user_id'] =  $parent_user_id;
            $insert_data['icon'] =  'fas fa-dollar-sign';
            DB::table("notifications")->insert($insert_data);

            DB::commit();
        }
        catch (\Throwable $e){
            DB::rollBack();
            $error = true;
            $error_message = $e->getMessage();
        }

        if($error) dd($error_message);
        else
        {
            $param_subject = __('Payment Confirmation');
            $param_name = 'Hello'.' '.$user_name;
            $param_message = __("Congratulation, We have received your payment of")." {$paid_currency} {$paid_amount} ({$package_name}) ".__("New billing cycle will continue until")." {$cycle_expired_date}.";
            if(!empty($user_email)) Mail::to($user_email)->send(new SimpleHtmlEmail($param_name,$param_message,$param_subject));

            $parent_userdata = DB::table('users')->select('email','name')->where(['id'=>$parent_user_id])->first();
            $param_subject = __('New Payment Received');
            $admin_email = $parent_userdata->email ?? '';
            $admin_name = $parent_userdata->name ?? '';
            $param_name = 'Hello'.' '.$admin_name;
            $param_message = __("Congratulation, You have received a new payment of")." {$paid_currency} {$paid_amount} ({$package_name}).".__("The payment was sent by")." : {$user_name}.";
            if(!empty($admin_email)) Mail::to($admin_email)->send(new SimpleHtmlEmail($param_name,$param_message,$param_subject));
        }
        return true;
    }

    public function set_api_error($code='',$message='',$return_json=false)
    {
        if($code=='' && $message=='') return false;
        $error_message = 'Error '.$code.' : '.$message;

        if($return_json) return json_encode(['error' => true,'message' => $error_message]);
        session()->flash('api_error_message',$error_message);
        return true;
    }

    public function get_email_profile_dropdown(Request $request) // common function both
    {
        $user_id = $request->user_id;
        $icon = $request->icon;
        $field_name = $request->field_name ?? 'default_email';
        $field_id = $request->field_id ?? '';
        if(empty($user_id)) $user_id = Auth::user()->id ?? 0;
        if(empty($icon)) $icon = false;

        $settings = DB::table('settings')->where('user_id',$user_id)->first();
        $email_settings = $settings->email_settings ?? '';
        $email_settings = json_decode($email_settings);
        $default = $email_settings->default ?? '';

        $info_type = DB::table('settings_sms_emails')->where('user_id','=',$user_id)->where('api_type','=','email')->orderByRaw('api_name ASC')->get();

        $response = $icon ? '<span class="input-group-text"><i class="far fa-envelope-open"></i></span>' : '';
        $response .= "<select name='".$field_name."'  id='".$field_id."' class='form-control'>";
        $response .= "<option value=''>".__('System')."</option>";
        foreach ($info_type as  $value)
        {
            $selected = $default==$value->id ? 'selected' : '';
            $response .= "<option value='{$value->id}' ".$selected.">".$value->api_name." : ".$value->profile_name."</option>";
        }
        $response .= "</select>";
        echo $response;
    }

    public function get_sms_profile_dropdown(Request $request) // common function both
    {
        $user_id = $request->user_id;
        $icon = $request->icon;
        $field_name = $request->field_name ?? 'default_sms';
        $field_id = $request->field_id ?? '';
        if(empty($user_id)) $user_id = Auth::user()->id ?? 0;
        if(empty($icon)) $icon = false;

        $settings = DB::table('settings')->where('user_id',$user_id)->first();
        $sms_settings = $settings->sms_settings ?? '';
        $sms_settings = json_decode($sms_settings);
        $default = $sms_settings->default ?? '';

        $info_type = DB::table('settings_sms_emails')->where('user_id','=',$user_id)->where('api_type','=','sms')->orderByRaw('api_name ASC')->get();

        $response = $icon ? '<span class="input-group-text"><i class="fas fa-phone"></i></span>' : '';
        $response .= "<select name='".$field_name."' id='".$field_id."' class='form-control'>";
        $response .= "<option value=''>".__('Select')."</option>";
        foreach ($info_type as  $value)
        {
            $selected = $default==$value->id ? 'selected' : '';
            $response .= "<option value='{$value->id}' ".$selected.">".$value->api_name." : ".$value->profile_name."</option>";
        }
        $response .= "</select>";
        echo $response;
    }

    public function create_label_and_assign(Request $request) // common function telegram
    {
        $telegram_bot_id = $request->telegram_bot_id;
        $label_name = $request->label_name;

        $bot_data = DB::table('telegram_bots')->where('id',$telegram_bot_id)->select('user_id')->first();
        $user_id = $bot_data->user_id ?? 0;

        $is_exists = DB::table("telegram_bot_labels")->where(["telegram_bot_id"=>$telegram_bot_id,"label_name"=>$label_name])->select('id')->first();

        if(!empty($is_exists)) $insert_id = $is_exists[0]['id'];
        else
        {
            DB::table("telegram_bot_labels")->insert(["telegram_bot_id"=>$telegram_bot_id,"label_name"=>$label_name,"user_id"=>$user_id,"updated_at"=>date("Y-m-d H:i:s")]);
            $insert_id =  DB::getPdo()->lastInsertId();
        }
        echo json_encode(array('id'=>$insert_id,"text"=>$label_name));
    }

    public function get_postbacks($return_array=false) // common function telegram
    {
        $is_from_add_button = request()->is_from_add_button ?? '0';
        $telegram_bot_id = request()->telegram_bot_id ?? session('bot_manager_get_bot_details_telegram_bot_id');
        $order_by = request()->order_by ?? "";
        if($order_by=="") $order_by="id DESC";
        else $order_by=$order_by." ASC";
        $postback_data = DB::table("telegram_bot_postbacks")->where(["telegram_bot_id"=>$telegram_bot_id,"is_template"=>"1",'template_for'=>'reply_message'])->orderByRaw($order_by)->get();
        if($return_array) return $postback_data;
        $push_postback="";

        if($is_from_add_button=='0')
        {
            $push_postback.="<option value=''>".__("Select")."</option>";
        }

        foreach ($postback_data as $key => $value)
        {
            $push_postback.="<option value='".$value->id."'>".$value->template_name.' ['.$value->postback_id.']'."</option>";
        }

        if($is_from_add_button=='1' || $is_from_add_button=='')
        {
            $push_postback.="<option value=''>".__("Select")."</option>";
        }
        echo $push_postback;
    }

    public function get_label_dropdown(Request $request) // common function telegram
    {
        $telegram_bot_id = $request->telegram_bot_id;
        $set_bot_session = $request->set_bot_session;
        if(empty($set_bot_session)) $set_bot_session = false;

        if($set_bot_session){
            session(['bot_manager_get_bot_details_telegram_bot_id'=>$telegram_bot_id]);
            session(['bot_manager_get_bot_details_tab_menu_id'=>'v-pills-bot-settings-tab']);
        }

        $icon = $request->icon;
        $multiple = $request->multiple;
        $name = $request->name;
        if(empty($icon)) $icon = false;
        if(empty($multiple)) $multiple = '0';
        if(empty($name)) $name = 'label_id';
        $mutiple_str = $multiple=='1' ? 'multiple' : '';

        $info_type = DB::table('telegram_bot_labels')->where('telegram_bot_id','=',$telegram_bot_id)->orderByRaw('label_name ASC')->get();

        $response = $icon ? '<span class="input-group-text"><i class="far fa-tag"></i></span>' : '';
        $response .= "<select name='".$name."' class='form-control' id='".$name."' style='width:100% !important;' ".$mutiple_str.">";
        if($multiple=='0') $response .= "<option value=''>".__('Select Label')."</option>";
        foreach ($info_type as  $value)
        {
            $response .= "<option value='{$value->id}'>".$value->label_name."</option>";
        }
        $response .= "</select><script>$('#".$name."').select2();</script>";
        echo $response;
    }

    public function get_sequence_dropdown(Request $request) // common function telegram
    {
        $telegram_bot_id = $request->telegram_bot_id;
        $icon = $request->icon;
        $multiple = $request->multiple;
        $name = $request->name;
        if(empty($icon)) $icon = false;
        if(empty($multiple)) $multiple = '0';
        if(empty($name)) $name = 'sequence_id';
        $mutiple_str = $multiple=='1' ? 'multiple' : '';

        $info_type = DB::table('broadcast_sequence_campaigns')->where('telegram_bot_id','=',$telegram_bot_id)->orderByRaw('campaign_name ASC')->get();

        $response = $icon ? '<span class="input-group-text"><i class="far fa-random"></i></span>' : '';
        $response .= "<select name='".$name."' class='form-control' id='".$name."' style='width:100% !important;' ".$mutiple_str.">";
        if($multiple=='0') $response .= "<option value=''>".__('Select Sequence')."</option>";
        foreach ($info_type as  $value)
        {
            $response .= "<option value='{$value->id}'>".$value->campaign_name."</option>";
        }
        $response .= "</select><script>$('#".$name."').select2();</script>";
        echo $response;
    }

    public  function  get_broadcast_tags() // common function telegram
    {
        $new_tags = array
        (
            ""=>__("Select Tag"),
            "ACCOUNT_UPDATE"=>"ACCOUNT_UPDATE",
            "CONFIRMED_EVENT_UPDATE"=>"CONFIRMED_EVENT_UPDATE",
            "HUMAN_AGENT"=>"HUMAN_AGENT",
            "POST_PURCHASE_UPDATE"=>"POST_PURCHASE_UPDATE",
            "NON_PROMOTIONAL_SUBSCRIPTION" => "NON_PROMOTIONAL_SUBSCRIPTION (NPI REGISTERED ONLY)"
        );
        return $new_tags;
    }

    public function create_label_and_assign_whatsapp(Request $request) // common function whatsapp
    {
        $whatsapp_bot_id = $request->whatsapp_bot_id;
        $label_name = $request->label_name;

        $bot_data = DB::table('whatsapp_bots')->where('id',$whatsapp_bot_id)->select('user_id')->first();
        $user_id = $bot_data->user_id ?? 0;

        $is_exists = DB::table("whatsapp_bot_labels")->where(["whatsapp_bot_id"=>$whatsapp_bot_id,"label_name"=>$label_name])->select('id')->first();

        if(!empty($is_exists)) $insert_id = $is_exists[0]['id'];
        else
        {
            DB::table("whatsapp_bot_labels")->insert(["whatsapp_bot_id"=>$whatsapp_bot_id,"label_name"=>$label_name,"user_id"=>$user_id,"updated_at"=>date("Y-m-d H:i:s")]);
            $insert_id =  DB::getPdo()->lastInsertId();
        }
        echo json_encode(array('id'=>$insert_id,"text"=>$label_name));
    }

    public function get_postbacks_whatsapp($return_array=false) // common function whatsapp
    {
        $is_from_add_button = request()->is_from_add_button ?? '0';
        $whatsapp_bot_id = request()->whatsapp_bot_id ?? session('bot_manager_get_bot_details_whatsapp_bot_id');
        $order_by = request()->order_by ?? "";
        if($order_by=="") $order_by="id DESC";
        else $order_by=$order_by." ASC";
        $postback_data = DB::table("whatsapp_bot_postbacks")->where(["whatsapp_bot_id"=>$whatsapp_bot_id,"is_template"=>"1",'template_for'=>'reply_message'])->orderByRaw($order_by)->get();
        if($return_array) return $postback_data;
        $push_postback="";

        if($is_from_add_button=='0')
        {
            $push_postback.="<option value=''>".__("Select")."</option>";
        }

        foreach ($postback_data as $key => $value)
        {
            $push_postback.="<option value='".$value->id."'>".$value->template_name.' ['.$value->postback_id.']'."</option>";
        }

        if($is_from_add_button=='1' || $is_from_add_button=='')
        {
            $push_postback.="<option value=''>".__("Select")."</option>";
        }
        echo $push_postback;
    }

    public function get_label_dropdown_whatsapp(Request $request) // common function whatsapp
    {
        $whatsapp_bot_id = $request->whatsapp_bot_id;
        $set_bot_session = $request->set_bot_session;
        if(empty($set_bot_session)) $set_bot_session = false;

        if($set_bot_session){
            session(['bot_manager_get_bot_details_whatsapp_bot_id'=>$whatsapp_bot_id]);
            session(['bot_manager_get_bot_details_tab_menu_id_whatsapp'=>'v-pills-bot-settings-tab']);
        }

        $icon = $request->icon;
        $multiple = $request->multiple;
        $name = $request->name;
        if(empty($icon)) $icon = false;
        if(empty($multiple)) $multiple = '0';
        if(empty($name)) $name = 'label_id';
        $mutiple_str = $multiple=='1' ? 'multiple' : '';

        $info_type = DB::table('whatsapp_bot_labels')->where('whatsapp_bot_id','=',$whatsapp_bot_id)->orderByRaw('label_name ASC')->get();

        $response = $icon ? '<span class="input-group-text"><i class="far fa-tag"></i></span>' : '';
        $response .= "<select name='".$name."' class='form-control' id='".$name."' style='width:100% !important;' ".$mutiple_str.">";
        if($multiple=='0') $response .= "<option value=''>".__('Select Label')."</option>";
        foreach ($info_type as  $value)
        {
            $response .= "<option value='{$value->id}'>".$value->label_name."</option>";
        }
        $response .= "</select><script>$('#".$name."').select2();</script>";
        echo $response;
    }

    public function get_sequence_dropdown_whatsapp(Request $request) // common function whatsapp
    {
        $whatsapp_bot_id = $request->whatsapp_bot_id;
        $icon = $request->icon;
        $multiple = $request->multiple;
        $name = $request->name;
        if(empty($icon)) $icon = false;
        if(empty($multiple)) $multiple = '0';
        if(empty($name)) $name = 'sequence_id';
        $mutiple_str = $multiple=='1' ? 'multiple' : '';

        $info_type = DB::table('whatsapp_broadcast_sequence_campaigns')->where('whatsapp_bot_id','=',$whatsapp_bot_id)->orderByRaw('campaign_name ASC')->get();

        $response = $icon ? '<span class="input-group-text"><i class="far fa-random"></i></span>' : '';
        $response .= "<select name='".$name."' class='form-control' id='".$name."' style='width:100% !important;' ".$mutiple_str.">";
        if($multiple=='0') $response .= "<option value=''>".__('Select Sequence')."</option>";
        foreach ($info_type as  $value)
        {
            $response .= "<option value='{$value->id}'>".$value->campaign_name."</option>";
        }
        $response .= "</select><script>$('#".$name."').select2();</script>";
        echo $response;
    }


    public function get_sequence_campaigns($telegram_bot_id=0,$campaign_type='telegram')
    {
        $response=[];
        $info_type = DB::table('broadcast_sequence_campaigns')->where(['telegram_bot_id'=>$telegram_bot_id,'campaign_type'=>$campaign_type])->orderByRaw('campaign_name ASC')->get();
        foreach ($info_type as  $value)
        {
            $response[$value->id]= $value->campaign_name;
        }
        return $response;
    }

    public function get_sequence_campaigns_whatsapp($whatsapp_bot_id=0,$campaign_type='whatsapp')
    {
        $response=[];
        $info_type = DB::table('whatsapp_broadcast_sequence_campaigns')->where(['whatsapp_bot_id'=>$whatsapp_bot_id,'campaign_type'=>$campaign_type])->orderByRaw('campaign_name ASC')->get();
        foreach ($info_type as  $value)
        {
            $response[$value->id]= $value->campaign_name;
        }
        return $response;
    }


    public function _random_number_generator($length=6)
    {
        $rand = substr(uniqid(mt_rand(), true), 0, $length);
        return $rand;
    }

    protected function delete_bot_action($table_id=0,$user_id=0)
    {
        $table = 'telegram_bots';
        $where = ['user_id'=>$this->user_id,'id'=>$table_id];
        if(!valid_to_delete($table,$where)) {
            return response()->json(['error'=>true,'message'=>__('Bad request.')]);
        }
        unset($where['user_id']);

        $table_names = $this->table_names_array_bot();
        try {
            DB::beginTransaction();
            foreach($table_names as $value)
            {
                // deleting usage log having lifetime limit, we do not delete usage log having monthly limit
                $count_module = DB::table($value['table_name'])->where([$value['column_name']=>$table_id])->select('id')->count();
                if($count_module>0) $this->delete_usage_log($value['module_id'],$count_module);
            }
            DB::table($table)->where($where)->delete();
            $this->delete_usage_log(1,1); // delete bot count
            DB::commit();
            $response['error'] = false;
            $response['message'] = __("Bot has been deleted successfully.");

        }
        catch (\Throwable $e){
            DB::rollBack();
            $error = $e->getMessage();
            $response['error'] = true;
            $response['message'] =__('Database error occurred').' : '.$error;
        }
        return json_encode($response);
    }

    protected function whatsapp_delete_bot_action($table_id=0,$user_id=0)
    {
        $table = 'whatsapp_bots';
        $where = ['user_id'=>$this->user_id,'id'=>$table_id];
        if(!valid_to_delete($table,$where)) {
            return response()->json(['error'=>true,'message'=>__('Bad request.')]);
        }
        unset($where['user_id']);

        $get_bot = $this->get_bot_whatsapp_joined($table_id);
        $whatsapp_service = app(WhatsappServiceInterface::class);
        $whatsapp_service->access_token = $get_bot->access_token ?? '';
        $whatsapp_business_account_id = $get_bot->whatsapp_business_account_id ?? '';
        $whatsapp_business_id = $get_bot->whatsapp_business_id ?? '';
        $whatsapp_service->whatsapp_business_account_id = $whatsapp_business_account_id;

        $table_names = $this->whatsapp_table_names_array_bot();
        try {
            DB::beginTransaction();
            foreach($table_names as $value)
            {
                // deleting usage log having lifetime limit, we do not delete usage log having monthly limit
                if(Schema::hasTable($value['table_name']) && Schema::hasColumn($value['table_name'], $value['column_name'])){
                    $count_module = DB::table($value['table_name'])->where([$value['column_name']=>$table_id])->select('id')->count();
                    if($count_module>0) $this->delete_usage_log($value['module_id'],$count_module);
                }
            }
            DB::table($table)->where($where)->delete();
            DB::commit();

            if(DB::table($table)->where('whatsapp_business_id',$whatsapp_business_id)->select('id')->count()==0){
                DB::table('whatsapp_businesses')->where('id',$whatsapp_business_id)->delete();
                $this->delete_usage_log(1,1); // delete bot count
                $whatsapp_service->delete_webhook();
            }

            $response['error'] = false;
            $response['message'] = __("WhatsApp integration has been deleted successfully.");

        }
        catch (\Throwable $e){
            DB::rollBack();
            $error = $e->getMessage();
            $response['error'] = true;
            $response['message'] =__('Database error occurred').' : '.$error;
        }
        return json_encode($response);
    }

    protected function table_names_array_bot()
    {
        $tables = array (
            1 => array (
                'table_name' => 'broadcast_sequence_campaigns',
                'column_name' => 'telegram_bot_id',
                'module_id' => $this->module_id_broadcast_sequence
            ),
            2 => array (
                'table_name' => 'ecommerce_stores',
                'column_name' => 'telegram_bot_id',
                'module_id' => $this->module_id_ecommerce
            )
        );
        return $tables;
    }

    protected function whatsapp_table_names_array_bot()
    {
        $tables = array (
            1 => array (
                'table_name' => 'whatsapp_broadcast_sequence_campaigns',
                'column_name' => 'whatsapp_bot_id',
                'module_id' => $this->module_id_broadcast_sequence
            ),
            2 => array (
                'table_name' => 'ecommerce_stores',
                'column_name' => 'whatsapp_bot_id',
                'module_id' => $this->module_id_ecommerce
            )
        );
        return $tables;
    }

    protected function table_names_array_subscriber()
    {
        $tables = array (

            1 => array (
                'table_name' => 'broadcast_sequence_campaign_maps',
                'column_name' => 'telegram_bot_subscriber_subscriber_id'
            ),
            2 => array (
                'table_name' => 'broadcast_sequence_campaign_reports',
                'column_name' => 'telegram_bot_subscriber_id'
            ),
            3 => array (
                'table_name' => 'broadcast_telegram_campaign_sends',
                'column_name' => 'telegram_bot_subscriber_chat_id'
            ),
            4 => array (
                'table_name' => 'ecommerce_carts',
                'column_name' => 'telegram_bot_subscriber_subscriber_id'
            ),
            5 => array (
                'table_name' => 'ecommerce_cart_addresses',
                'column_name' => 'telegram_bot_subscriber_subscriber_id'
            ),
            6 => array (
                'table_name' => 'ecommerce_cart_addresses',
                'column_name' => 'telegram_bot_subscriber_subscriber_id'
            ),
            7 => array (
                'table_name' => 'ecommerce_product_comments',
                'column_name' => 'telegram_bot_subscriber_subscriber_id'
            ),
            8 => array (
                'table_name' => 'ecommerce_product_reviews',
                'column_name' => 'telegram_bot_subscriber_subscriber_id'
            ),
            9 => array (
                'table_name' => 'ecommerce_reminder_reports',
                'column_name' => 'telegram_bot_subscriber_subscriber_id'
            ),
            10 => array (
                'table_name' => 'telegram_bot_input_flow_campaign_question_answers',
                'column_name' => 'telegram_bot_subscriber_subscriber_id'
            ),
            11 => array (
                'table_name' => 'telegram_bot_input_flow_custom_field_maps',
                'column_name' => 'telegram_bot_subscriber_subscriber_id'
            ),
            12 => array (
                'table_name' => 'telegram_bot_webview_builder_campaign_formdatas',
                'column_name' => 'telegram_bot_subscriber_subscriber_id'
            )
        );
        return $tables;
    }

    protected function whatsapp_table_names_array_subscriber()
    {
        $tables = array (

            1 => array (
                'table_name' => 'whatsapp_broadcast_sequence_campaign_maps',
                'column_name' => 'whatsapp_bot_subscriber_subscriber_id'
            ),
            2 => array (
                'table_name' => 'whatsapp_broadcast_sequence_campaign_reports',
                'column_name' => 'whatsapp_bot_subscriber_id'
            ),
            3 => array (
                'table_name' => 'broadcast_whatsapp_campaign_sends',
                'column_name' => 'whatsapp_bot_subscriber_chat_id'
            ),
            4 => array (
                'table_name' => 'ecommerce_carts',
                'column_name' => 'whatsapp_bot_subscriber_subscriber_id'
            ),
            5 => array (
                'table_name' => 'ecommerce_cart_addresses',
                'column_name' => 'whatsapp_bot_subscriber_subscriber_id'
            ),
            6 => array (
                'table_name' => 'ecommerce_cart_addresses',
                'column_name' => 'whatsapp_bot_subscriber_subscriber_id'
            ),
            7 => array (
                'table_name' => 'ecommerce_product_comments',
                'column_name' => 'whatsapp_bot_subscriber_subscriber_id'
            ),
            8 => array (
                'table_name' => 'ecommerce_product_reviews',
                'column_name' => 'whatsapp_bot_subscriber_subscriber_id'
            ),
            9 => array (
                'table_name' => 'ecommerce_reminder_reports',
                'column_name' => 'whatsapp_bot_subscriber_subscriber_id'
            ),
            10 => array (
                'table_name' => 'whatsapp_bot_input_flow_campaign_question_answers',
                'column_name' => 'whatsapp_bot_subscriber_subscriber_id'
            ),
            11 => array (
                'table_name' => 'whatsapp_bot_input_flow_custom_field_maps',
                'column_name' => 'whatsapp_bot_subscriber_subscriber_id'
            ),
            12 => array (
                'table_name' => 'whatsapp_bot_webview_builder_campaign_formdatas',
                'column_name' => 'whatsapp_bot_subscriber_subscriber_id'
            )
        );
        return $tables;
    }

    public function thirdparty_webhook_trigger($telegram_bot_id=0,$subscriber_id="",$trigger='trigger_email',$user_id=0,$postback_id="",$form_canonical_id="",$form_data=[])
    {
        if($user_id=='' || $user_id==0) $user_id = $this->user_id;

        if($trigger=='trigger_postback')
            $trigger="trigger_postback_".$postback_id;
        else if($trigger=='trigger_webview')
            $trigger="trigger_webview_".$form_canonical_id;
        else if ($trigger=='trigger_userinput')
            $trigger="trigger_userinput_".$form_canonical_id;

        $table = 'telegram_bot_thirdparty_webhooks';
        $where = [$table.'.telegram_bot_id'=>$telegram_bot_id,'telegram_bot_thirdparty_webhook_triggers.trigger_option'=>$trigger];
        if(isset($user_id) && $user_id!="") $where[$table.'.user_id']=$user_id;
        $webhook_connector_info = DB::table($table)->select($table.'.*','webhook_id','trigger_option')->where($where)->leftJoin('telegram_bot_thirdparty_webhook_triggers', 'telegram_bot_thirdparty_webhook_triggers.webhook_id', '=', $table.'.id')->get();
        if(empty($webhook_connector_info)) return false;

        /** Get subscriber information  **/
        $subscriber_info = $this->get_subscriber($subscriber_id,'*',true);

        /**Get subscriber Labels name from labels id***/
        $label_ids = $subscriber_info->label_ids ?? "";
        $label_ids_array = explode(',',$label_ids);
        $label_ids_array=array_filter($label_ids_array);
        $labels_name="";
        $labels_name_array="";
        if(!empty($label_ids_array))
        {
            $label_info = DB::table('telegram_bot_labels')->whereIntegerInRaw('id',$label_ids_array)->get();
            foreach($label_info as $value)
            {
                $labels_name_array[]=$value['label_name'];
            }
        }
        if(!empty($labels_name_array)) $labels_name = implode(',',$labels_name_array);

        foreach ($webhook_connector_info as $webhook_value)
        {
            $webhook_url = $webhook_value->webhook_url ?? '';
            $webhook_id = $webhook_value->webhook_id ?? 0;
            $post_variable = $webhook_value->variable_post ?? '';
            $post_variable = explode(',',$post_variable);
            $post_variable = array_filter($post_variable);

            /**Making the variable for post/send ***/
            $post_info=array();
            foreach ($post_variable as $variable_info)
            {
                if($variable_info=='psid') $post_info[$variable_info]= $subscriber_info->subscriber_id ?? '';
                else if ($variable_info=='labels') $post_info[$variable_info]= $labels_name;
                else if($variable_info=='telegram_bot_username') $post_info[$variable_info]= $webhook_connector_info->telegram_bot_username ?? "";
                else if($variable_info=='postbackid') $post_info[$variable_info]= $postback_id;
                else if($variable_info =='formdata')
                {
                    foreach ($form_data as $key => $value) $post_info[$key]=$value;
                }
                else if($variable_info=='user_input_flow_campaign') $post_info["user_input_data"]=$form_data;
                else $post_info[$variable_info] = $subscriber_info->$variable_info ?? "";
            }

            /***    Send/Post Information to webhook url ***/
            $post_info = json_encode($post_info);
            $curl_response = run_curl($webhook_url,$post_info);
            $curl_http_code= $curl_response['http_code'];
            $curl_error= $curl_response['curl_error'];

            /***Insert into Activity table**/
            $insert_data=array();
            $insert_data['http_code'] = $curl_http_code;
            $insert_data['curl_error'] = $curl_error;
            $insert_data['webhook_id'] = $webhook_id;
            $insert_data['post_time'] = date('Y-m-d H:i:s');
            $insert_data['post_data'] = $post_info;
            DB::table('telegram_bot_thirdparty_webhook_activities')->insert($insert_data);
            $update_data_last_trigger['last_trigger_time'] = $insert_data['post_time'];
            DB::table('telegram_bot_thirdparty_webhooks')->where(['id'=>$webhook_id])->update($update_data_last_trigger);

            return true;
        }
    }

    public function thirdparty_webhook_trigger_whatsapp($whatsapp_bot_id=0,$subscriber_id="",$trigger='trigger_email',$user_id=0,$postback_id="",$form_canonical_id="",$form_data=[])
    {
        if($user_id=='' || $user_id==0) $user_id = $this->user_id;

        if($trigger=='trigger_postback')
            $trigger="trigger_postback_".$postback_id;
        else if($trigger=='trigger_webview')
            $trigger="trigger_webview_".$form_canonical_id;
        else if ($trigger=='trigger_userinput')
            $trigger="trigger_userinput_".$form_canonical_id;

        $table = 'whatsapp_bot_thirdparty_webhooks';
        $where = [$table.'.whatsapp_bot_id'=>$whatsapp_bot_id,'whatsapp_bot_thirdparty_webhook_triggers.trigger_option'=>$trigger];
        if(isset($user_id) && $user_id!="") $where[$table.'.user_id']=$user_id;
        $webhook_connector_info = DB::table($table)->select($table.'.*','webhook_id','trigger_option')->where($where)->leftJoin('whatsapp_bot_thirdparty_webhook_triggers', 'whatsapp_bot_thirdparty_webhook_triggers.webhook_id', '=', $table.'.id')->get();
        if(empty($webhook_connector_info)) return false;

        /** Get subscriber information  **/
        $subscriber_info = $this->get_subscriber_whatsapp($subscriber_id,'*',true);

        /**Get subscriber Labels name from labels id***/
        $label_ids = $subscriber_info->label_ids ?? "";
        $label_ids_array = explode(',',$label_ids);
        $label_ids_array=array_filter($label_ids_array);
        $labels_name="";
        $labels_name_array="";
        if(!empty($label_ids_array))
        {
            $label_info = DB::table('whatsapp_bot_labels')->whereIntegerInRaw('id',$label_ids_array)->get();
            foreach($label_info as $value)
            {
                $labels_name_array[]=$value['label_name'];
            }
        }
        if(!empty($labels_name_array)) $labels_name = implode(',',$labels_name_array);

        foreach ($webhook_connector_info as $webhook_value)
        {
            $webhook_url = $webhook_value->webhook_url ?? '';
            $webhook_id = $webhook_value->webhook_id ?? 0;
            $post_variable = $webhook_value->variable_post ?? '';
            $post_variable = explode(',',$post_variable);
            $post_variable = array_filter($post_variable);

            /**Making the variable for post/send ***/
            $post_info=array();
            foreach ($post_variable as $variable_info)
            {
                if($variable_info=='psid') $post_info[$variable_info]= $subscriber_info->subscriber_id ?? '';
                else if ($variable_info=='labels') $post_info[$variable_info]= $labels_name;
                else if($variable_info=='whatsapp_bot_username') $post_info[$variable_info]= $webhook_connector_info->whatsapp_bot_username ?? "";
                else if($variable_info=='postbackid') $post_info[$variable_info]= $postback_id;
                else if($variable_info =='formdata')
                {
                    foreach ($form_data as $key => $value) $post_info[$key]=$value;
                }
                else if($variable_info=='user_input_flow_campaign') $post_info["user_input_data"]=$form_data;
                else $post_info[$variable_info] = $subscriber_info->$variable_info ?? "";
            }

            /***    Send/Post Information to webhook url ***/
            $post_info = json_encode($post_info);
            $curl_response = run_curl($webhook_url,$post_info);
            $curl_http_code= $curl_response['http_code'];
            $curl_error= $curl_response['curl_error'];

            /***Insert into Activity table**/
            $insert_data=array();
            $insert_data['http_code'] = $curl_http_code;
            $insert_data['curl_error'] = $curl_error;
            $insert_data['webhook_id'] = $webhook_id;
            $insert_data['post_time'] = date('Y-m-d H:i:s');
            $insert_data['post_data'] = $post_info;
            DB::table('whatsapp_bot_thirdparty_webhook_activities')->insert($insert_data);
            $update_data_last_trigger['last_trigger_time'] = $insert_data['post_time'];
            DB::table('whatsapp_bot_thirdparty_webhooks')->where(['id'=>$webhook_id])->update($update_data_last_trigger);

            return true;
        }
    }


    protected function assign_sequence_campaign($sequence_type="default",$engagement_table_id=0,$telegram_bot_id=0,$subscriber_id='',$broadcast_sequence_campaign_id=0)
    {
        $where = [];
        if(!empty($broadcast_sequence_campaign_id) && $broadcast_sequence_campaign_id>0){ // Means Campaign id is passed directly, no need to get it from engagement table.
            $where['id'] = $broadcast_sequence_campaign_id;
            if(!empty($telegram_bot_id) && $telegram_bot_id>0) $where['telegram_bot_id'] = $telegram_bot_id;
        }
        else $where= ["engagement_table_id"=>$engagement_table_id,"sequence_type"=>$sequence_type,"telegram_bot_id"=>$telegram_bot_id];

        $broadcast_sequence_campaigns_info = DB::table('broadcast_sequence_campaigns')->where($where)->first();
        $id = $broadcast_sequence_campaigns_info->id ?? 0;
        $user_id = $broadcast_sequence_campaigns_info->user_id ?? 0;

        if($id>0)
        {
            DB::table('broadcast_sequence_campaign_maps')->insertOrIgnore([
                [
                    'user_id' => $user_id,
                    'telegram_bot_id' => $telegram_bot_id,
                    'telegram_bot_subscriber_subscriber_id' => $subscriber_id,
                    'broadcast_sequence_campaign_id' => $id,
                    'sequence_type'=>$sequence_type,
                    'initial_date'=> date("Y-m-d H:i:s")
                ]
            ]);
            return true;
        }
        return false;
    }

    protected function assign_sequence_campaign_whatsapp($sequence_type="default",$engagement_table_id=0,$whatsapp_bot_id=0,$subscriber_id='',$broadcast_sequence_campaign_id=0)
    {
        $where = [];
        if(!empty($broadcast_sequence_campaign_id) && $broadcast_sequence_campaign_id>0){ // Means Campaign id is passed directly, no need to get it from engagement table.
            $where['id'] = $broadcast_sequence_campaign_id;
            if(!empty($whatsapp_bot_id) && $whatsapp_bot_id>0) $where['whatsapp_bot_id'] = $whatsapp_bot_id;
        }
        else $where= ["engagement_table_id"=>$engagement_table_id,"sequence_type"=>$sequence_type,"whatsapp_bot_id"=>$whatsapp_bot_id];

        $broadcast_sequence_campaigns_info = DB::table('whatsapp_broadcast_sequence_campaigns')->where($where)->first();
        $id = $broadcast_sequence_campaigns_info->id ?? 0;
        $user_id = $broadcast_sequence_campaigns_info->user_id ?? 0;

        if($id>0)
        {
            DB::table('whatsapp_broadcast_sequence_campaign_maps')->insertOrIgnore([
                [
                    'user_id' => $user_id,
                    'whatsapp_bot_id' => $whatsapp_bot_id,
                    'whatsapp_bot_subscriber_subscriber_id' => $subscriber_id,
                    'broadcast_sequence_campaign_id' => $id,
                    'sequence_type'=>$sequence_type,
                    'initial_date'=> date("Y-m-d H:i:s")
                ]
            ]);
            return true;
        }
        return false;
    }

    protected  function send_email_using_api_id($email_api_id='', $email='', $email_reply_message='', $email_reply_subject='', $user_id='', $email_reply_message_header='')
    {
        if(empty($user_id)) $user_id = $this->user_id;
        if(empty($email) || empty($email_reply_message) || empty($email_reply_subject) ) return ['error'=>true,'message'=>__('Missing params.')];
        if(set_email_config($email_api_id))
        {
            $response = $this->send_email($email,$email_reply_message,$email_reply_subject,$email_reply_message_header);
            $status = $response['status'] ?? 'Unknown';
            $now_time=date('Y-m-d H:i:s');
            $insert_data=array('user_id'=>$user_id,'settings_type'=>'quick-reply','status'=>$status,'email'=>$email,'api_type'=>"Email Sender",'api_name'=>config('mail.default'),'response'=>json_encode($response),'updated_at'=>$now_time,'email_api_id'=>$email_api_id);
            DB::table("sms_email_send_logs")->insert($insert_data);
            return $response;
        }
        else return ['error'=>true,'message'=>__('Email settings not found.')];
    }

    protected  function send_email($email='', $email_reply_message='', $email_reply_subject='', $email_reply_message_header='')
    {
        if(empty($email) || empty($email_reply_message) || empty($email_reply_subject) ) return ['error'=>true,'message'=>__('Missing params.')];
        try
        {
            Mail::to($email)->send(new SimpleHtmlEmail($email_reply_message_header,$email_reply_message,$email_reply_subject));
            return ['error'=>false,'message'=>__('Email sent successfully.')];
        }
        catch(\Swift_TransportException $e){
            return ['error'=>true,'message'=>$e->getMessage()];
        }
        catch(\GuzzleHttp\Exception\RequestException $e){
            return ['error'=>true,'message'=>$e->getMessage()];
        }
        catch(Exception $e) {
            return ['error'=>true,'message'=>$e->getMessage()];
        }

    }

    protected  function send_sms_using_api_id($sms_api_id='', $phone_number='', $sms_reply_message='', $user_id='')
    {
        $error_response = ['ok'=>false,'description'=>__('API settings not found.'),'error_code'=>''];
        if($user_id=='') $user_id = $this->user_id;
        $where = ['id'=>$sms_api_id,'user_id'=>$user_id];
        $api_data = DB::table('settings_sms_emails')->where($where)->first();
        if(empty($api_data)) return $error_response;

        $settings_data = isset($api_data->settings_data) ? json_decode($api_data->settings_data,true) : [];
        $api_name = $api_data->api_name ?? '';
        if(empty($settings_data) || empty($api_name)) return $error_response;

        $sms_manager = app(SmsManagerServiceInterface::class);
        $sms_manager->api_name = $api_name;
        foreach ($settings_data as $key=>$value){
            if(empty($key) || empty($value)) continue;
            $index = $api_name.'_'.$key;
            $sms_manager->$index = $value;
        }
        return $sms_manager->send_sms($sms_reply_message, $phone_number);
    }

    public function get_autoresponder_list(){
        $autoresponder_info = DB::table('settings_email_autoresponders')
            ->select('settings_email_autoresponder_lists.*', 'profile_name', 'api_name')
            ->leftJoin('settings_email_autoresponder_lists', 'settings_email_autoresponder_lists.settings_email_autoresponder_id', '=', 'settings_email_autoresponders.id')
            ->where(['user_id' => $this->user_id])->orderByRaw('api_name ASC')->get();
        return $autoresponder_info;
    }

    public function sync_email_to_autoresponder($email_auto_responder_settings='', $email='',$first_name='',$last_name='',$type='signup',$user_id="0",$tags='')
    {
        if(empty($email)) return false;
        $email_auto_responder_settings = json_decode($email_auto_responder_settings);
        if(empty($email_auto_responder_settings)) return false;

        $now_time = date('Y-m-d H:i:s');
        $data_to_send = ['firstname' => $first_name,'lastname' => $last_name,'email' => $email];

        $autoresponder = app(AutoResponderServiceInterface::class);

        foreach ($email_auto_responder_settings as $key=>$value)
        {
            if(empty($value)) continue;
            $settings_email_autoresponders = DB::table('settings_email_autoresponders')
                ->whereIntegerInRaw('settings_email_autoresponder_lists.id',$value)->select('settings_email_autoresponder_id','list_id','api_name','settings_data')
                ->leftJoin('settings_email_autoresponder_lists','settings_email_autoresponder_lists.settings_email_autoresponder_id','=','settings_email_autoresponders.id')
                ->get();

            foreach($settings_email_autoresponders as $key2=>$value2){
                $settings_email_autoresponder_id = $value2->settings_email_autoresponder_id ?? 0;
                $list_id = $value2->list_id ?? '';
                $api_name = $value2->api_name ?? 'mailchimp';
                $settings_data = json_decode($value2->settings_data) ?? [];

                if($api_name=='mailchimp') {
                    $api_key = $settings_data->api_key ?? '';
                    $response = $autoresponder->mailchimp_add_contact($api_key, $list_id, $data_to_send, $tags);
                }
                else if($api_name=='sendinblue') {
                    $api_key = $settings_data->api_key ?? '';
                    $response = $autoresponder->sendinblue_add_contact($api_key, $list_id, $data_to_send);
                }
                else if($api_name=='activecampaign') {
                    $api_key = $settings_data->api_key ?? '';
                    $api_url = $settings_data->api_url ?? '';
                    $response = $autoresponder->activecampaign_add_contact($api_key, $api_url, $list_id, $data_to_send);
                }
                else if($api_name=='mautic') {
                    $username = $settings_data->username ?? '';
                    $password = $settings_data->password ?? '';
                    $base_url = $settings_data->base_url ?? '';
                    $base64 = base64_encode($username . ":" . $password);
                    $response = $autoresponder->mautic_add_contact($base64, $base_url, $list_id, $data_to_send,$tags);
                }
                else if($api_name=='acelle') {
                    $api_key = $settings_data->api_key ?? '';
                    $api_url = $settings_data->api_url ?? '';
                    $response = $autoresponder->acelle_add_contact($api_key, $api_url, $list_id, $data_to_send);
                }
                $ok = $response['ok'] ?? false;
                $status = $ok===true ? '1' : '0';
                $insert_data = array('user_id' => $user_id, 'settings_type' => $type, 'status' => $status, 'email' => $email, 'api_type' => "Autoresponder", 'api_name' => $api_name, 'response' => json_encode($response), 'updated_at' => $now_time, 'email_api_id' => $settings_email_autoresponder_id);
                DB::table("sms_email_send_logs")->insert($insert_data);
            }
        }

        return true;

    }

    protected function multiple_assign_label($subscriber_id='',$telegram_bot_id=0,$label_auto_ids='',$subscriber_info='') // $subscriber_info is optional
    {
        $label_auto_ids=explode(",",$label_auto_ids);
        $label_auto_ids=array_filter($label_auto_ids);
        $telegram_bot_subscriber_id = $subscriber_info->id ?? 0;
        $updated_at = date('Y-m-d H:i:s');
        foreach($label_auto_ids as $value)
        {
            $value = trim($value);
            $sql = "INSERT IGNORE INTO telegram_bot_subscriber_assigned_labels(telegram_bot_label_id,telegram_bot_subscriber_id,updated_at)
                VALUES('$value','$telegram_bot_subscriber_id','$updated_at');";
            DB::statement($sql);
        }

    }

    protected function multiple_assign_label_whatsapp($subscriber_id='',$whatsapp_bot_id=0,$label_auto_ids='',$subscriber_info='') // $subscriber_info is optional
    {
        $label_auto_ids=explode(",",$label_auto_ids);
        $label_auto_ids=array_filter($label_auto_ids);
        $whatsapp_bot_subscriber_id = $subscriber_info->id ?? 0;
        $updated_at = date('Y-m-d H:i:s');
        foreach($label_auto_ids as $value)
        {
            $value = trim($value);
            $sql = "INSERT IGNORE INTO whatsapp_bot_subscriber_assigned_labels(whatsapp_bot_label_id,whatsapp_bot_subscriber_id,updated_at)
                VALUES('$value','$whatsapp_bot_subscriber_id','$updated_at');";
            DB::statement($sql);
        }

    }

    //if user id passed then function will automatically check module and insert
    protected function insert_livechat_data($insert_data=[],$user_id=null,$update_interaction=true){

        if(empty($insert_data)) return false;
        $subscriber_id = $insert_data['telegram_bot_subscriber_subscriber_id'] ?? null;
        if($update_interaction) $this->update_subscriber_last_interaction($subscriber_id);

        $has_livechat_access = true;
        if(!empty($user_id) && $user_id!=0){
            $user_module_access_data =  DB::table('users')->where('users.id','=',$user_id)->select('users.id as user_id','users.user_type','packages.module_ids')->leftJoin('packages','users.package_id','=','packages.id')->first();
            $is_admin = isset($user_module_access_data->user_type) && $user_module_access_data->user_type=='Admin' ? true : false;
            $module_ids = isset($user_module_access_data->module_ids) && !empty($user_module_access_data->module_ids) ? explode(',',$user_module_access_data->module_ids) : [];
            $has_livechat_access = has_module_access($this->module_id_live_chat,$module_ids,$is_admin);
        }
        $message_id = false;
        if($has_livechat_access){
            if(!isset($insert_data['conversation_time'])) $insert_data['conversation_time'] = date('Y-m-d H:i:s');
            DB::table('telegram_bot_livechat_messages')->insert($insert_data);
            $message_id = DB::getPdo()->lastInsertId();
        }
        return $message_id;
    }

    protected function insert_livechat_data_whatsapp($insert_data=[],$user_id=null,$update_interaction=true){

        if(empty($insert_data)) return false;
        $subscriber_id = $insert_data['whatsapp_bot_subscriber_subscriber_id'] ?? null;
        if($update_interaction) $this->update_subscriber_last_interaction_whatsapp($subscriber_id);

        $has_livechat_access = true;
        if(!empty($user_id) && $user_id!=0){
            $user_module_access_data =  DB::table('users')->where('users.id','=',$user_id)->select('users.id as user_id','users.user_type','packages.module_ids')->leftJoin('packages','users.package_id','=','packages.id')->first();
            $is_admin = isset($user_module_access_data->user_type) && $user_module_access_data->user_type=='Admin' ? true : false;
            $module_ids = isset($user_module_access_data->module_ids) && !empty($user_module_access_data->module_ids) ? explode(',',$user_module_access_data->module_ids) : [];
            $has_livechat_access = has_module_access($this->module_id_live_chat,$module_ids,$is_admin);
        }
        $message_id = false;
        if($has_livechat_access){
            if(!isset($insert_data['conversation_time'])) $insert_data['conversation_time'] = date('Y-m-d H:i:s');
            DB::table('whatsapp_bot_livechat_messages')->insert($insert_data);
            $message_id = DB::getPdo()->lastInsertId();
        }
        return $message_id;
    }

    protected function create_insert_livechat_template_data_whatsapp($subscriber_id=null,$whatsapp_bot_id=null,$whatsapp_bot_template_id=null,$custom_values=[],$header_param=[],$first_name=''){



        if(empty($subscriber_id) || empty($whatsapp_bot_id) || empty($whatsapp_bot_template_id)) return false;
        $template_data = DB::table('whatsapp_bot_templates')->where('id',$whatsapp_bot_template_id)->first();
        $template_json = json_decode($template_data->template_json,true);
        if(!empty($header_param)) $header_param = json_decode(json_encode($header_param),true);

        $header_exist = $body_exist = false;
        $header_type = $header_pos = $body_pos = '';
        foreach ($template_json['components'] as $key_com=>$val_com) {
            $temp_type = $val_com['type'] ?? '';
            if ($temp_type == 'header') {
                $header_exist = true;
                $header_pos = $key_com;
                $header_type = $val_com['format'] ?? 'text';
            } else if ($temp_type == 'body') {
                $body_exist = true;
                $body_pos = $key_com;
            }
        }

        if($header_exist){
            if($header_type=='text') $template_json['components'][$header_pos]['text'] = $template_data->header_content;
            else {
                // the index differ in cron and live chat
                $find_val = $header_param[0][$header_type]['link'] ?? ''; // cron
                if(empty($find_val)) $find_val = $header_param[$header_type]['link'] ?? ''; // livechat
                $template_json['components'][$header_pos]['link'] = $find_val;
            }
        }
        if($body_exist){
            $template_json['components'][$body_pos]['text'] = $template_data->body_content;
        }
        if(isset($template_json['access_token'])) unset($template_json['access_token']);

        $template_json = json_encode($template_json);
        $template_json = str_replace(['#LEAD_USER_FIRST_NAME#','%23LEAD_USER_FIRST_NAME%23'], $first_name, $template_json);
        foreach ($custom_values as $kc=>$vc){
            $template_json = str_replace('#'.$vc->name.'#', $vc->custom_field_value, $template_json);
        }

        $insert_livechat_data = [
            'whatsapp_bot_subscriber_subscriber_id' => $subscriber_id,
            'whatsapp_bot_id' => $whatsapp_bot_id,
            'sender' => 'bot',
            'message_content' => $template_json
        ];
        $this->insert_livechat_data_whatsapp($insert_livechat_data);
        return true;

    }

    protected function update_subscriber_last_interaction($subscriber_id=null){
        if(!empty($subscriber_id) && $subscriber_id!=0)
            DB::table('telegram_bot_subscribers')->where('subscriber_id','=',$subscriber_id)->update(['last_interacted_at'=>date('Y-m-d H:i:s')]);
    }

    protected function update_subscriber_last_interaction_whatsapp($subscriber_id=null){
        if(!empty($subscriber_id) && $subscriber_id!=0)
            DB::table('whatsapp_bot_subscribers')->where('subscriber_id','=',$subscriber_id)->update(['last_interacted_at'=>date('Y-m-d H:i:s')]);
    }

    public function error_no_bot_connected(){
        set_agency_config(Auth::user()->id);
        return view('errors/connect-bot');
    }

    public function error_no_account_connected(){
        set_agency_config(Auth::user()->id);
        return view('errors/connect-account');
    }

    protected function get_available_language_list(){
        if($this->is_admin) $user_id = 1;
        else if($this->is_agent) $user_id = $this->user_id;
        else $user_id = Auth::user()->parent_user_id;

        $all_language_list = get_language_list();

        $languages = ['en'=>'English'.' ('.__('System').')'];
        $files = File::allFiles(resource_path().DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'translation');
        foreach ($files as $key=>$value){
            $getRelativePath = $value->getRelativePath();
            if(!isset($languages[$getRelativePath])){
                $langName = rtrim($getRelativePath,'-'.$this->user_id);
                if(($this->is_admin || $this->parent_user_id==1) && !$this->is_agent && !str_contains($getRelativePath,'-')){
                    $languages[$getRelativePath] = $all_language_list[$langName] ?? $langName;
                }
                else if(($this->is_agent || $this->parent_user_id>1) && str_ends_with($getRelativePath,'-'.$user_id)){
                    $languages[$getRelativePath] = $all_language_list[$langName] ?? $langName;
                }
            }
        }
        return $languages;
    }

    public function custom_error_page($error_title='',$error_code='',$error_message=''){
        if(empty($error_title)) $error_title = __('Bad Request');
        if(empty($error_code)) $error_code = __('Bad Request')." : 400";
        if(empty($error_message)) $error_message = __('An Unexpected Error Occurred.');
        $data = ['error_title'=>$error_title,'error_code'=>$error_code,'error_message'=>$error_message,'body'=>'errors.custom'];
        return view($data['body'], $data);
    }

    public  function  test(){
        echo "Test route";
    }

}

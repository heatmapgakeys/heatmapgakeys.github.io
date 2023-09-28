<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class Agency extends Home
{

    public function __construct()
    {
        $this->set_global_userdata(true,['Admin','Agent'],['Manager']);
    }

    private function detailed_feature_elements()
    {
        return array(
            'header_image' => array(
                array(
                    'label'=>__( 'Header Image'),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'header_image',
                    'value'=> asset('assets/front/images/hero/hero-image-2.png'),
                    'placeholder' => '',
                    'upload'=>true,
                )
            ),
            'details_feature_1' => array(
                array(
                    'label'=>__("Feature Image"),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'details_feature_1_img',
                    'value'=> asset('assets/front/images/about/about-image-1.png'),
                    'placeholder' => '',
                    'upload'=>true,
                ),
            ),
            'details_feature_2' => array(
                array(
                    'label'=>__("Feature Image"),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'details_feature_2_img',
                    'value'=> asset('assets/front/images/about/about-image-2.png'),
                    'placeholder' => '',
                    'upload'=>true,
                ),
            ),
            'details_feature_3' => array(
                array(
                    'label'=>__("Open a Free Account Image"),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'details_feature_3_img',
                    'value'=> asset('assets/front/images/cta/cta-image-1.png'),
                    'placeholder' => '',
                    'upload'=>true,
                ),
            ),
            'details_feature_4' => array(
                array(
                    'label'=>__("Open a Free Account Image"),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'details_feature_4_img',
                    'value'=> asset('assets/front/images/cta/cta-image-2.png'),
                    'placeholder' => '',
                    'upload'=>true,
                ),
            )

        );
    }

    private function customer_reviews()
    {
        return array(
            'review_1' => array(
                array(
                    'label'=>__('Name'),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'review_1_name',
                    'value'=> '',
                    'placeholder' => '',
                ),
                array(
                    'label'=>__('Designation'),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'review_1_designation',
                    'value'=> '',
                    'placeholder' => '',
                ),
                array(
                    'label'=>__('Avatar'),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'review_1_avatar',
                    'value'=> '',
                    'placeholder' => '',
                    'upload'=>true,
                ),
                array(
                    'label'=>__('Review'),
                    'field'=>'textarea',
                    'type'=>'text',
                    'name' => 'review_1_description',
                    'value'=> '',
                    'placeholder' => '',
                ),
            ),
            'review_2' => array(
                array(
                    'label'=>__('Name'),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'review_2_name',
                    'value'=> '',
                    'placeholder' => '',
                ),
                array(
                    'label'=>__('Designation'),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'review_2_designation',
                    'value'=> '',
                    'placeholder' => '',
                ),
                array(
                    'label'=>__('Avatar'),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'review_2_avatar',
                    'value'=> '',
                    'placeholder' => '',
                    'upload'=>true,
                ),
                array(
                    'label'=>__('Review'),
                    'field'=>'textarea',
                    'type'=>'text',
                    'name' => 'review_2_description',
                    'value'=> '',
                    'placeholder' => '',
                ),
            ),
            'review_3' => array(
                array(
                    'label'=>__('Name'),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'review_3_name',
                    'value'=> '',
                    'placeholder' => '',
                ),
                array(
                    'label'=>__('Designation'),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'review_3_designation',
                    'value'=> '',
                    'placeholder' => '',
                ),
                array(
                    'label'=>__('Avatar'),
                    'field'=>'input',
                    'type'=>'text',
                    'name' => 'review_3_avatar',
                    'value'=> '',
                    'placeholder' => '',
                    'upload'=>true,
                ),
                array(
                    'label'=>__('Review'),
                    'field'=>'textarea',
                    'type'=>'text',
                    'name' => 'review_3_description',
                    'value'=> '',
                    'placeholder' => '',
                )
            )
        );
    }

    private function company_elements()
    {
        return array(
            array(
                //Telegram and WhatsApp Chatbot Marketing Service
                'label'=>__('Company Slogan'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_title',
                'value'=> 'Heatmap & Sessions Recording Tool',
                'placeholder' => '',
            ),
            array(
                //Create a FREE account that is valid for life to get started. Create chatbots for Telegram and WhatsApp, with no coding required. Try bot broadcasting to increase your open rate to 80%+ rather than solely depending on email marketing.
                'label'=>__('Company Short Description'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_short_description',
                'value'=> 'Heatmap & Sessions Recording Tool',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Company Address'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_address',
                'value'=> 'Holding #127, 1st Floor, Gonokpara, Boalia, Rajshahi-6100, Bangladesh',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Company Email'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_email',
                'value'=> '',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Company Cover Image'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_cover_image',
                'value'=> '',
                'placeholder' => '',
                'upload'=>true,
            ),
            array(
                'label'=>__('Company Keywords'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_keywords',
                'value'=> 'heatmap,session recording,live user,analytics,seo',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Facebook Messenger URL'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_fb_messenger',
                'value'=> 'https://m.me/heatsketch',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Facebook Page URL'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_fb_page',
                'value'=> 'https://facebook.me/heatsketch',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Telegram Bot URL'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_telegram_bot',
                'value'=> 'https://t.me/heatsketch_bot',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Telegram Channel URL'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_telegram_channel',
                'value'=> 'https://t.me/heatsketch',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Youtube Channel URL'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_youtube_channel',
                'value'=> 'https://www.youtube.com/channel/UCGXLEjzQwxNz1bO-ujKgn3Q',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Twitter Profile URL'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_twitter_account',
                'value'=> 'https://twitter.com/heatsketch',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Instagram Profile URL'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_instagram_account',
                'value'=> 'https://www.instagram.com/heatsketch',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Linkedin Profile URL'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_linkedin_channel',
                'value'=> 'https://linkedin.com/company/heatsketch',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Support Desk URL'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'company_support_url',
                'value'=> '',
                'placeholder' => '',
            ),
            array(
                'label'=>__('Documentation URL'),
                'field'=>'input',
                'type'=>'text',
                'name' => 'links_docs_url',
                'value'=> url('docs'),
                'placeholder' => '',
            )
        );
    }


    public function get_agency_landing_page_data()
    {
        $data['settings_data'] = array();
        $data['settings_data']['details_features'] = $this->detailed_feature_elements();
        $data['settings_data']['customer_reviews'] = $this->customer_reviews();
        $data['settings_data']['company_elements'] = $this->company_elements();

        $xdata = DB::table('settings')->select('agency_landing_settings')->where('user_id',$this->user_id)->first();
        $data['xdata'] = isset($xdata->agency_landing_settings) ? json_decode($xdata->agency_landing_settings) : null;
        $data['body'] = 'member.settings.agency-landing-settings';
        return $this->viewcontroller($data);
    }

    public function submit_agency_landing_form_data(Request $request)
    {
        if(config('settings.is_demo') == '1') abort('403');
        $submitted_data =(object) $request->all();
        if(isset($submitted_data->_token)) unset($submitted_data->_token);
        if(!isset($submitted_data->disable_landing_page)) $submitted_data->disable_landing_page='0';
        if(!isset($submitted_data->disable_ecommerce_feature)) $submitted_data->disable_ecommerce_feature='0';

        $insert_data['agency_landing_settings'] = json_encode($submitted_data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $update_data = $insert_data;
        $insert_data['user_id'] = $this->user_id;
        $insert_data['email_settings'] = '{"default":null,"sender_email":null,"sender_name":null}';
        $insert_data['auto_responder_signup_settings'] = '{"mailchimp":[],"sendinblue":[],"activecampaign":[],"mautic":[],"acelle":[]}';
        $insert_data['upload_settings'] = '{"bot":{"image":"1","video":"20","audio":"5","file":"20"}}';
        $insert_data['timezone'] = 'Europe/Dublin';
        $insert_data['updated_at'] = date('Y-m-d H:i:s');
        DB::table('settings')->upsert($insert_data,['user_id'],$update_data);
        return redirect()->route('agency-landing-editor')->with('status',__("Data has been updated successfully."));

    }

    public function reset_editor()
    {
        DB::table("settings")->where('user_id',$this->user_id)->update(["agency_landing_settings"=>'']);
        return redirect()->route('agency-landing-editor')->with('status',__("Reset successfully"));
    }

    public function upload_media(Request $request) {
        $rules = (['file' => 'mimes:png,jpg,jpeg,webp|max:2048']);
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return Response::json([
                'error' => true,
                'message' => $validator->errors()->first(),
            ]);
        }

        $upload_dir_subpath = 'agency/'.$this->user_id;

        $file = $request->file('file');
        $extension = $request->file('file')->extension();
        $filename = time().'.'.$extension;

        if(env('AWS_UPLOAD_ENABLED')){
            try {
                $upload2S3 = Storage::disk('s3')->putFileAs($upload_dir_subpath, $file,$filename);
                return response()->json([
                    'error' => false,
                    'filename' =>  Storage::disk('s3')->url($upload2S3)
                ]);
            }
            catch (\Exception $e){
                $error_message = $e->getMessage();
                if(empty($error_message)) $error_message =  __('Something went wrong.');
                return response()->json([
                    'error' => true,
                    'message' => $error_message
                ]);
            }
        }
        else{

            if ($request->file('file')->storeAs('public/'.$upload_dir_subpath, $filename)) {
                return Response::json([
                    'error' => false,
                    'filename' =>  asset('storage').'/'.$upload_dir_subpath.'/'.$filename
                ]);
            } else {
                return Response::json([
                    'error' => true,
                    'message' => __('Something went wrong.'),
                ]);
            }
        }
    }
}

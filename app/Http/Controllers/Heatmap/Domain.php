<?php

namespace App\Http\Controllers\Heatmap;

use AWS\CRT\HTTP\Response;
use App\Http\Controllers\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use mysql_xdevapi\Exception;

class Domain extends Home
{
    public $table_names = [];

    public function __construct()
    {
        $this->set_global_userdata(true, ['Admin', 'Member']);
    }

    protected function set_table_names(){       
        $domain_code_session = session('active_domain_code_session');
        $user_and_domain = explode('-',$domain_code_session);
        $domain_user = $user_and_domain[1] ?? 1;
        $domain_code = $user_and_domain[0] ?? 1;
        $this->table_names = get_table_names($domain_user,$domain_code);
    }

    public function index()
    {
        $allowed_domain_ids = $this->is_manager && !empty(Auth::user()->allowed_domain_ids) ? json_decode(Auth::user()->allowed_domain_ids,true) : [];
        $query = DB::table('visitor_analysis_domain_list')->where(['user_id'=>$this->user_id,'deleted'=>'0']);
        if(!empty($allowed_domain_ids)) $query->whereIntegerInRaw('id',$allowed_domain_ids);
        $domain_list = $query->paginate(10);

        $data = array('body' => 'heatmap/domain_list', 'load_datatable' => true, 'domain_list'=>$domain_list);
        return $this->viewcontroller($data);
    }

    public function generate_embed_code(Request $request)
    {
        if(config('settings.is_demo') == '1' && Auth::user()->user_type == 'Admin') abort('403');

        $aws_storage_data = DB::table('settings')->select('aws_settings')->first();
        if($aws_storage_data){
            $aws_storage_data = json_decode($aws_storage_data->aws_settings);
            if(empty($aws_storage_data->access_key_id) && Auth::user()->user_type == 'Admin')
                return response()->json(['status' => '3', 'message' => __('S3 storage configuration is empty. Please configure the settings first.')]);
            else if(empty($aws_storage_data->access_key_id) && Auth::user()->user_type != 'Admin')
                return response()->json(['status' => '3', 'message' => __('Please contact admin for S3 storage configuration.')]);
        }
        

        if(!has_module_action_access(1,1,$this->team_access,$this->is_manager)) abort(403);

        $given_domain_name = strip_tags(strtolower($request->domain_name));
        $domain_name = get_domain_only($given_domain_name);
        $user_id = $this->user_id;
        $domain_table_id = $request->domain_table_id ?? '';
        $recording_option = [];
        $excluded_ips = $request->excluded_ips;
        $domain_prefix = $request->domain_prefix;
        $recording_option['block_class'] = $request->block_class ?? '';
        $recording_option['ignore_class'] = $request->ignore_class ?? '';
        $recording_option['maskText_class'] = $request->maskText_class ?? '';
        $recording_option['maskInput_option'] = $request->maskInput_option ?? 'password';
        $recording_option['maskAllInputs'] = $request->maskAllInputs ?? '';
        
        $random_num = $this->_random_number_generator() . time() . "-" . $user_id;
        $js_url = route('script-loader-function',$random_num);

        $js_code_raw = '<div id="hmsas-script-loader"></div><script id="hmsas-22-domain-name" hmsas-22-data-name="' . $random_num . '" type="text/javascript" src="' . $js_url . '"></script>';
        $screenshot = NULL;
        $js_code = htmlspecialchars($js_code_raw);

        $insert_data = [
            'user_id' => $user_id,
            'domain_name' => $domain_name,
            'domain_code' => $random_num,
            'excluded_ip' => $excluded_ips,
            'domain_prefix'=>$domain_prefix,
            'recording_option'=>json_encode($recording_option),
            'js_code' => $js_code,
            'screenshot' => $screenshot,
            'add_date' => date("Y-m-d")
        ];

        if(empty($domain_table_id)){
            $where = ['domain_name' => $domain_name, 'user_id' => $user_id, 'deleted' => '0'];
            $domain_exist = DB::table('visitor_analysis_domain_list')->where($where)->get();
            
            if ($domain_exist->isNotEmpty()) {
                return response()->json(['status' => '0', 'message' => __("Domain Is already Exist")]);
                exit;
            }
    
            $status = $this->check_usage($this->module_id_no_of_website = 1, $request = 1, $this->user_id);
            if ($status == "2") {
                return response()->json(['status' => '2', 'message' => __("Sorry, usage limit has been exceeded for this module.")]);
                exit;
            } else if ($status == "3") {
                return response()->json(['status' => '3', 'message' => __("Sorry, usage limit has been exceeded for this module.")]);
                exit;
            }
            $new_js_code = $js_code_raw;
            DB::table('visitor_analysis_domain_list')->insert($insert_data);
            $id = DB::getPdo()->lastInsertId();
            session(['active_domain_id_session' => $id]);
            session(['active_domain_name_session' => $domain_name]);
            session(['active_domain_code_session' => $random_num]);
            $this->insert_usage_log($this->module_id_no_of_website, 1, $this->user_id);
            return response()->json(['status' => '1', 'message' => $new_js_code]);
        }
        else{
            unset($insert_data['js_code']);
            unset($insert_data['domain_code']);
            DB::table('visitor_analysis_domain_list')->where(['id'=>$domain_table_id,'user_id'=>$user_id])->update($insert_data);
            return response()->json(['status' => '2', 'message' => __('Data has been updated successfully')]);
        }

       
    }


    public function play_pause_domain(Request $request)
    {
        if(config('settings.is_demo') == '1' && Auth::user()->user_type == 'Admin') abort('403');

        $table_id = $request->domain_id;
        $eventType = $request->eventType;

        $updateData = [];
        if($eventType=="play") {
            $updateData['pause_play'] = "pause";
        } else {
            $updateData['pause_play'] = "play";
        }

        DB::table("visitor_analysis_domain_list")->where(['id'=>$table_id,'user_id'=>$this->user_id])->update($updateData);
        echo '1';
    }

    public function get_rest_visit_lists(Request $request)
    {
        $this->set_table_names();
        $domain_list_id = session('active_domain_id_session');
        $session_value = $request->session_value;
        $session_id = $request->id;

        $getData = DB::table($this->table_names['sessions_table'])->select('visit_url')->where(['id'=>$session_id,'domain_list_id'=>$domain_list_id,'session_value'=>$session_value])->first();
        $urlList = json_decode($getData->visit_url,true);
        $str = '<ul class="list-group">';

        if(!empty($urlList)) {
            foreach($urlList as $key => $value) {
                $str .= '<a class="list-group-item list-group-item-action text-primary" href="'.$value['url'].'" target="_blank"><i class="fas fa-paperclip"></i> &nbsp;'.$value["page_title"].'</a>';
            }
        } else {
            $str .= '<li class="list-group-item text-muted"><i class="fas fa-exclamation-triangle"></i> '.__('No Data Found').'</li>';
        }
        $str .= '</ul>';

        echo $str;
    }

    public function domain_session_data_delete(Request $request)
    {
        if(config('settings.is_demo') == '1' && Auth::user()->user_type == 'Admin') abort('403');

        $this->set_table_names();
        if(!has_module_action_access(2,3,$this->team_access,$this->is_manager)) {
            echo '0';
            return false;
        }

        $table_id = $request->table_id;
        if($table_id==0 || $table_id=="") echo '0';

        try {
            DB::beginTransaction();

            $session_storage = DB::table($this->table_names['sessions_table'])->where(['id'=>$table_id,'user_id'=>$this->user_id])->select(['id','session_data'])->first();

            
            if(isset($session_storage->id))
            {
                $session_data = $session_storage->session_data;
                
                if($session_data !== NULL)
                {
                    $full_path_array = explode('session-recordings/',$session_data);
                    Storage::disk('s3')->delete('session-recordings/'.$full_path_array[1]);
                }
                else
                {
                    $temp_session_storage = DB::table($this->table_names['temp_sessions_table'])->where('session_data_table_id',$table_id)->get();


                    foreach($temp_session_storage as $single_temp_session)
                    {
                        if(file_exists(storage_path($this->table_names['temp_sessions_table'].'/'.$single_temp_session->id.'.json')))
                        {
                            @unlink(storage_path($this->table_names['temp_sessions_table'].'/'.$single_temp_session->id.'.json'));
                        }

                    }
                    // no need to delete from temp table, because of relation
                    // DB::table($this->table_names['temp_sessions_table'])->where(['session_data_table_id'=>$table_id])->delete();
                }

                DB::table($this->table_names['sessions_table'])->where(['id'=>$table_id])->delete();

                echo '1';
            }

            DB::commit();
        }
        catch (\Throwable $e){
            DB::rollBack();
            $error = $e->getMessage();
            echo '0';
        }

    }

    public function get_corresponding_url_session_data(Request $request)
    {
        ini_set('memory_limit', '-1');
        $this->set_table_names();
        
        $browser_list = [
            'chrome' => 'assets/images/browser/chrome.png',
            'firefox' => 'assets/images/browser/firefox.png',
            'safari' => 'assets/images/browser/safari.png',
            'opera' => 'assets/images/browser/opera.png',
            'ie' => 'assets/images/browser/ie.png',
            'edge' => 'assets/images/browser/edge.png',
        ];
        $os_list = [
            'android' => 'assets/images/os/android.png',
            'ipad' => 'assets/images/os/ipad.png',
            'iphone' => 'assets/images/os/iphone.png',
            'linux' => 'assets/images/os/linux.png',
            'mac os x' => 'assets/images/os/mac.png',
            'search bot' => 'assets/images/os/search-bot.png',
            'windows' => 'assets/images/os/windows.png',
            'desktop' => 'assets/images/os/desktop.png',
            'mobile' => 'assets/images/os/mobile.png',
            'tablet' => 'assets/images/os/tablet.png',
        ];

        $sessionId = $request->id;

        $session_data = DB::table($this->table_names['sessions_table'])->select(['session_data','country','city','os','device','browser_name','browser_version','ip','entry_time'])->where(['id'=>$sessionId])->first();

        $device = '';
        $ip = '';
        $location = '';
        $entry_time = '';

        $country = $session_data->country ?? '';
        $s_country =get_country_iso_phone_currency_list();

        if(!empty($country)){
             $country_name = isset($s_country[$country]) ? $s_country[$country]:$country;
             $image_link = "assets/images/flags/".$country.".png";
        }
        else{
            $country_name = __('Not Found');
            $image_link = "assets/images/flags/other.png";
        }

        $country_image = '<img style="height: 20px; width: 20px; margin-top: -3px;" src="'.asset($image_link).'" alt=" "> &nbsp;';
        $location=$country_image.$country_name.', '.$session_data->city;

        $os = $session_data->os ?? '';
        $os = strtolower($os);
        $os_img_path = isset($os_list[$os]) ? $os_list[$os] : "assets/images/os/other.png";
        $os_image = '<img data-bs-toggle="tooltip" title="'.$session_data->os.'" style="height: 20px; width: 20px; margin-top: -3px;" src="'.asset($os_img_path).'" alt=" ">';
        $device_img_path = $os_list[strtolower($session_data->device)];
        $device_img = '<img data-bs-toggle="tooltip" title="'.$session_data->device.'" style="height: 20px; width: 20px; margin-top: -3px;" src="'.asset($device_img_path).'" alt="'.__('Device').'">';
        $browser_name = strtolower($session_data->browser_name);
        $browser_img_path = isset($browser_list[$browser_name]) ? $browser_list[$browser_name] : "assets/images/browser/other.png";
        $browser_image = '<img data-bs-toggle="tooltip" title="'.$session_data->browser_name.'-'.$session_data->browser_version.'" style="height: 20px; width: 20px; margin-top: -3px;" src="'.asset($browser_img_path).'" alt=" ">';
        $device = $browser_image.'&nbsp;'.$os_image.'&nbsp;'.$device_img;

        $ip =$session_data->ip;
        $entry_time = 'Entry Time: '.convert_datetime_to_timezone($session_data->entry_time,'','','j M, h:i A');

        $info_html = $location." &nbsp;|&nbsp; ".$device." &nbsp;|&nbsp; ".$ip." &nbsp;|&nbsp; ".$entry_time;


        $response_session = [];
        if($session_data->session_data === NULL)
        {
            $all_sessions = DB::table($this->table_names['temp_sessions_table'])->select('session_data','id')->where(['session_data_table_id'=>$sessionId])->get();
            
            foreach($all_sessions as $session)
            {
                $temp_sessions = [];
                if(file_exists(storage_path($this->table_names['temp_sessions_table'].'/'.$session->id.'.json')))
                {
                    $json_file_data = file_get_contents(storage_path($this->table_names['temp_sessions_table'].'/'.$session->id.'.json'));
                    $temp_sessions = json_decode($json_file_data,true);
                }

                foreach($temp_sessions as $value)
                    array_push($response_session, $value);
            }
            return response()->json(['session_data'=>json_encode($response_session),'info_html'=>$info_html]);
        }
        else
        {
            $full_path_array = explode('session-recordings/',$session_data->session_data);
            $response_session = Storage::disk('s3')->get('session-recordings/'.$full_path_array[1]);
            return response()->json(['session_data'=>$response_session,'info_html'=>$info_html]);
        }


    }

    public function user_session_video(Request $request)
    {
        $data['browser_list'] = ['chrome'=>'Chrome','firefox'=>'Firefox','safari'=>'Safari','opera'=>'Opera','ie'=>'IE','edge'=>'Edge'];
        $data['os_list'] = ['android'=>'Android','ipad'=>'Ipad','iphone'=>'Iphone','linux'=>'Linux','mac'=>'Mac','windows'=>'Windows'];
        $data['device_list'] = ['desktop'=>'Desktop','mobile'=>'Mobile','tablet'=>'Tablet'];

        $data['body'] = "heatmap/user_session_video";
        $data['load_datatable'] = true;
        $data['country_names'] = get_country_iso_phone_currency_list();
        return $this->viewcontroller($data);
    }

    public function get_visit_url_information(Request $request)
    {
        $this->set_table_names();
        $domain_list_id = session('active_domain_id_session');
        $search_country = $request->search_country;
        $search_browser = $request->search_browser;
        $search_os = $request->search_os;
        $search_device = $request->search_device;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $is_dashboard = isset($request->is_dashboard) ? true:false;
        $search_value = !is_null($request->input('search.value')) ? $request->input('search.value') : '';
        $display_columns = array("#",'id', 'country', 'total_stay_time', 'devices', 'entry_time','last_engagement_time','ip','actions','referrer');
        $search_columns = array('country','referrer');

        $time = date("Y-m-d H:i:s");
        $live_user_time = date("Y-m-d H:i:s", strtotime($time. " - 30 sec"));

        $page = isset($request->page) ? intval($request->page) : 1;
        $start = isset($request->start) ? intval($request->start) : 0;
        $limit = isset($request->length) ? intval($request->length) : 10;
        $sort_index = !is_null($request->input('order.column')) ? strval($request->input('order.column')) : 1;
        $sort = !is_null($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = !is_null($request->input('order.0.dir')) ? strval($request->input('order.0.dir')) : 'desc';
        $order_by = $sort . " " . $order;
        $browser_list = [
            'chrome' => 'assets/images/browser/chrome.png',
            'firefox' => 'assets/images/browser/firefox.png',
            'safari' => 'assets/images/browser/safari.png',
            'opera' => 'assets/images/browser/opera.png',
            'ie' => 'assets/images/browser/ie.png',
            'edge' => 'assets/images/browser/edge.png',
        ];
        $os_list = [
            'android' => 'assets/images/os/android.png',
            'ipad' => 'assets/images/os/ipad.png',
            'iphone' => 'assets/images/os/iphone.png',
            'linux' => 'assets/images/os/linux.png',
            'mac os x' => 'assets/images/os/mac.png',
            'search bot' => 'assets/images/os/search-bot.png',
            'windows' => 'assets/images/os/windows.png',
            'desktop' => 'assets/images/os/desktop.png',
            'mobile' => 'assets/images/os/mobile.png',
            'tablet' => 'assets/images/os/tablet.png',
        ];
        $table = $this->table_names['sessions_table'];
        $query = DB::table($table)->where(['user_id'=>$this->user_id,'domain_list_id'=>$domain_list_id]);
        $info = $query->orderByRaw($order_by)->offset($start)->limit($limit)->groupBy(['session_value','cookie_value'])->get();

        if($is_dashboard) {
            $total_result = DB::table($table)
                            ->where(['user_id'=>$this->user_id,'domain_list_id'=>$domain_list_id])
                            ->where("last_engagement_time",">",$live_user_time)
                            ->groupBy(['session_value','cookie_value'])
                            ->get();
        } else {
            $total_result = DB::table($table)
                            ->where(['user_id'=>$this->user_id,'domain_list_id'=>$domain_list_id])
                            ->groupBy(['session_value','cookie_value'])
                            ->get();
        }

        $total_result = count($total_result);

        foreach ($info as $key => $value) {

            if($is_dashboard) {
                if(strtotime($value->last_engagement_time) < strtotime($live_user_time)) {
                    unset($info[$key]);
                    continue;
                }
            }

            if($search_value != NULL) {
                if(strpos($value->country, $search_value) === false && strpos($value->referrer, $search_value) === false)
                {
                    unset($info[$key]);
                    continue;
                }
            }

            if($search_country != NULL && strpos($value->country, $search_country) === false) {
                    unset($info[$key]);
                    continue;
            }

            if($search_browser != NULL && strpos($value->browser_name, ucfirst($search_browser)) === false) {
                    unset($info[$key]);
                    continue;
            }

            if($search_os != NULL && strpos($value->os, ucfirst($search_os)) === false) {
                    unset($info[$key]);
                    continue;
            }

            if($search_device != NULL && strpos($value->device, ucfirst($search_device)) === false) {
                    unset($info[$key]);
                    continue;
            }

            $onlyDate = date("Y-m-d",strtotime($value->last_engagement_time));
            
            if($from_date !=NULL) {

                if(strtotime($onlyDate) < strtotime($from_date) ) {
                    unset($info[$key]);
                    continue;
                }
            }

            if($to_date !=NULL) {

                if(strtotime($onlyDate) > strtotime($to_date) ) {
                    unset($info[$key]);
                    continue;
                }
            }

            $value->country = $value->country ?? '';
            $s_country =get_country_iso_phone_currency_list();

            if(!empty($value->country)){
                 $country_name = isset($s_country[$value->country]) ? $s_country[$value->country]:$value->country;
                 $image_link = file_exists(base_path("assets/images/flags/".$value->country.".png")) 
                                ? "assets/images/flags/".$value->country.".png"
                                : "assets/images/flags/other.png";
            }
            else {

                $country_name = __('Not Found');
                $image_link = "assets/images/flags/other.png";
            }

            $total_stay_time = $value->total_stay_time;

            if($total_stay_time != 0) {
                $average_stay_time = $total_stay_time;
                $hours = floor($average_stay_time / 3600);
                $minutes = floor(($average_stay_time / 60) % 60);
                $seconds = $average_stay_time % 60;  
            }
            
            $value->total_stay_time = $hours.'h '.$minutes.'m '.$seconds.'s';
           
            $country_image = '<img data-bs-toggle="tooltip" title="'.$country_name.'" style="height: 20px; width: 20px; margin-top: -3px;" src="'.asset($image_link).'" alt=" "> &nbsp;';

            $value->country=$country_image.$country_name;
            if(!empty($value->city))
                $value->country=$country_image.$country_name.', '.$value->city;

            $os = $value->os ?? '';
            $os = strtolower($os);
            $os_img_path = isset($os_list[$os]) ? $os_list[$os] : "assets/images/os/other.png";
            $os_image = '<img data-bs-toggle="tooltip" title="'.$value->os.'" style="height: 20px; width: 20px; margin-top: -3px;" src="'.asset($os_img_path).'" alt=" ">';
            $device_img = isset($os_list[strtolower($value->device)]) ? $os_list[strtolower($value->device)]: $value->device;
            $device = '<img data-bs-toggle="tooltip" title="'.$value->device.'" style="height: 20px; width: 20px; margin-top: -3px;" src="'.asset($device_img).'" alt="'.__('Device').'">';
            $value->device=$value->device;
            $value->os=$os_image;
            $browser_name = strtolower($value->browser_name);
            $browser_img_path = isset($browser_list[$browser_name]) ? $browser_list[$browser_name] : "assets/images/browser/other.png";
            $browser_image = '<img data-bs-toggle="tooltip" title="'.$value->browser_name.'" style="height: 20px; width: 20px; margin-top: -3px;" src="'.asset($browser_img_path).'" alt=" ">';
            $value->browser_name=$browser_image;
            $value->entry_time = convert_datetime_to_timezone($value->entry_time,'','','j M, h:i A');
            $value->last_engagement_time = convert_datetime_to_timezone($value->last_engagement_time,'','','j M, h:i A');
            $value->referrer = $value->referrer;
            $value->ip = $value->ip;
            $value->devices = '<div style="min-width:80px;">'.$value->browser_name.'&nbsp;'.$value->os.'&nbsp;'.$device.'</div>';
            $visit_url_id = $value->id;


            $str = $action_width = '';
            if(!$is_dashboard) $action_width = "style='min-width:150px'";
            $str.= '<div '.$action_width.'>';
        
            $str .= '<a href="#" data-id= "'.$visit_url_id.'" session_value="'.$value->session_value.'" title="'.__("Play").'" class="btn btn-circle btn-outline-primary play_record"><i class="fas fa-play-circle"></i></a>&nbsp;';

            if(!$is_dashboard) {
                $str .= '<a href="#" data-id= "'.$visit_url_id.'" session_value="'.$value->session_value.'" title="'.__("Visited URLs").'" class="btn btn-circle btn-outline-info domain_related_url_list"><i class="fas fa-bars"></i></a>&nbsp;';
                $str .= '<a href="#" data-id= "'.$visit_url_id.'" session_value="'.$value->session_value.'" title="'.__("Delete").'" class="btn btn-circle btn-outline-danger delete_record"><i class="fas fa-trash-alt"></i></a>';
            }
            $str .= '</div>';
            $value->actions = $str;

        }

        $data['draw'] = (int) $_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = array_format_datatable_data($info, $display_columns, $start);

        echo json_encode($data);
    }

    public function get_video_download_link(Request $request)
    {
        ini_set('memory_limit', '-1');
        $this->set_table_names();
        $sessionId = $request->id;
        $user_id = $this->user_id;
        $session_data = DB::table($this->table_names['sessions_table'])->select(['session_data','domain_code'])->where(['id'=>$sessionId,'user_id'=>$user_id])->first();
        $domain_code = $session_data->domain_code;
        $response_session = [];

        $path = storage_path('app/public/video/'.$domain_code);
        if(!File::isDirectory($path)){
           File::makeDirectory($path, 0777, true, true);
        }

        if($session_data->session_data === NULL)
        {
            $all_sessions = DB::table($this->table_names['temp_sessions_table'])->select('session_data')->where(['session_data_table_id'=>$sessionId])->get();

            foreach($all_sessions as $session)
            {
                $temp_sessions = json_decode($session->session_data,true);
                foreach($temp_sessions as $value)
                    array_push($response_session, $value);
            }
            
            Storage::disk('public')->put('video/'.$domain_code.'/'.$sessionId.'.json', json_encode($response_session));
            $input_file = storage_path('app/public/video/'.$domain_code.'/'.$sessionId.'.json');
        }
        else
        {
            Storage::disk('public')->put('video/'.$domain_code.'/'.$sessionId.'.json', json_encode($session_data->session_data));
            $input_file = storage_path('app/public/video/'.$domain_code.'/'.$sessionId.'.json');
        }

        $config_file = storage_path('app/public/video/video_config.json');
        $output_file = storage_path('app/public/video/'.$domain_code.'/'.$sessionId.'.mp4');

        $url='rrvideo --input '.$input_file.' --config '.$config_file.' --output '.$output_file.' -loglevel error 2>&1';
        exec($url,$log);
        echo "<pre>";
        print_r($log);
        exit;


        $str = '<a href="'.$output_file.'" ><i class="fas fa-paperclip"></i> &nbsp;'.__('Click to Download').'</a>';
        echo $str;

    }


    public function delete_domain(Request  $request)
    {
        if(config('settings.is_demo') == '1' && Auth::user()->user_type == 'Admin') abort('403');

        $this->set_table_names();
        if(!has_module_action_access(1,3,$this->team_access,$this->is_manager)) {
            return response()->json(['error' => true,'message' => __('You are not allowed to perform this action.')]);
        }

        $id = $request->id;
        $check_delete = false;
        $error = '';
        try {
            DB::beginTransaction();
            $domain_code = '';
            $domain_info = DB::table('visitor_analysis_domain_list')->where(['id'=>$id,'user_id'=>$this->user_id])->update(['deleted'=>'1']);
            $this->delete_usage_log($this->module_id_no_of_website,1);
            $check_delete = true;

            // if the deleted domain is in active session, then set another domain as active session 
            if($id == session('active_domain_id_session'))
            {
                $domains =  DB::table('visitor_analysis_domain_list')->select('id','domain_name','domain_code')->where(['user_id'=>$this->user_id])->orderBy("id","DESC")->first();
                if(isset($domains->id))
                {
                    session(['active_domain_id_session' => $domains->id]);
                    session(['active_domain_name_session' => $domains->domain_name]);
                    session(['active_domain_code_session' => $domains->domain_code]);

                    $domain_code_session = session('active_domain_code_session');
                    $user_and_domain = explode('-',$domain_code_session);
                    $this->table_names = get_table_names($user_and_domain[1],$user_and_domain[0]);
                    
                    $domains_pages = DB::table($this->table_names['heatmap_table'])->select(['visit_url'])->groupBy('visit_url')->where(array("user_id" => $this->user_id, "domain_list_id" => $domains->id))->first();
                    if(isset($domains_pages->visit_url))
                        session(['active_heatmap_page_name_session' => $domains_pages->visit_url]);
                }
            }
            
            DB::commit();
        }
        catch (\Throwable $e){
            DB::rollBack();
            $error = $e->getMessage();
        }
        

        if ($check_delete) return response()->json(['error' => false, 'message' => __('Domain and corresponding data has been deleted successfully')]);
        else return response()->json(['error' => true, 'message' => $error]);
    }

    public function get_js_embed_code(Request $request)
    {
        $id = $request->campaign_id;
        $domain_data = DB::table('visitor_analysis_domain_list')->where(array("id" => $id, "user_id" => $this->user_id))->first();
        $embed_code = $domain_data->js_code;
        $embed_code = htmlspecialchars_decode($embed_code);
        return response()->json(array("str1" => $embed_code));
    }

    public function edit_domain(Request $request)
    {
        if(config('settings.is_demo') == '1' && Auth::user()->user_type == 'Admin') abort('403');
        $id = $request->campaign_id;
        $domain_data = DB::table('visitor_analysis_domain_list')->where(array("id" => $id, "user_id" => $this->user_id))->first();
        $domain = $domain_data->domain_name;
        $domain_prefix = $domain_data->domain_prefix;
        $excluded_ip = $domain_data->excluded_ip;
        $recording_option = !empty($domain_data->recording_option) ? json_decode($domain_data->recording_option,true) : [''];
        $block_class = $recording_option['block_class'] ?? '';
        $ignore_class= $recording_option['ignore_class'] ?? '';
        $maskText_class= $recording_option['maskText_class']?? '';
        $maskInput_option= $recording_option['maskInput_option']?? '';
        $maskAllInputs= $recording_option['maskAllInputs'] ?? '';
        return response()->json(array('id'=>$id,'domain_prefix'=>$domain_prefix,'domain_name'=>$domain,'excluded_ip'=>$excluded_ip,'block_class'=>$block_class,'ignore_class'=>$ignore_class,'maskText_class'=>$maskText_class,'maskInput_option'=>$maskInput_option,'maskAllInputs'=>$maskAllInputs));
    }

    public function domain_analytics(Request $request)
    {
        $this->set_table_names();
        $domain_data = DB::table($this->table_names['heatmap_table'])->select(['id','visit_url','page_title'])->groupBy('visit_url')->where(array("user_id" => $this->user_id, "domain_list_id" => session('active_domain_id_session')))->get();
        $url_str = '';
        $active_page_session = session('active_heatmap_page_name_session');
        $i = 0;
        $url_list = [];
        $first_url = '';
        foreach ($domain_data as $single_url){
            $active_class = "";
            if($active_page_session == '' && $i == 0)
            {
                session(['active_heatmap_page_name_session' => $single_url->visit_url]);
                $active_page_session = $single_url->visit_url;
            }

            if($i == 0) $first_url = $single_url->visit_url;
            array_push($url_list,$single_url->visit_url);

            if($single_url->visit_url == $active_page_session) $active_class = "selected";
            $url_str.= '<option value="'.$single_url->visit_url.'" class="dropdown-item visit_url_link" '.$active_class.'>'.$single_url->page_title.'</option>';
        }

        if(!in_array($active_page_session,$url_list)) $active_page_session = $first_url;

        $retake_screenshot_url = $active_page_session."?retake-screenshot=yes";
        $data['retake_screenshot_url'] = $retake_screenshot_url;

        $data['urls_for_domain'] = $url_str;
        $data['country_names'] = get_country_iso_phone_currency_list();
        $data['body'] = "heatmap/domain";
        
        $active_domain_id = '';
        $active_domain_code = '';

        $active_domain_id = session('active_domain_id_session');
        if($active_domain_id != '')
            $active_domain_code = DB::table('visitor_analysis_domain_list')->select('domain_code')->where('id',$active_domain_id)->first();
        if($active_domain_code != '')
            $active_domain_code = $active_domain_code->domain_code;

        $device_type =$request->device_type ? $request->device_type : 'desktop';
        $event_type = $request->event_type ? $request->event_type : 'click';
        $from_date = $request->from_date ? $request->from_date : '';
        $to_date = $request->to_date ? $request->to_date : '';
        $search_country = $request->search_country ? $request->search_country : '';

        $data['search_country'] = $search_country;
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;
        $data['event_type'] = $event_type;
        $data['device_type'] = $device_type;
        $request->merge([
              'active_heatmap_page_name_session' => $active_page_session,
              'active_domain_id' => $active_domain_id ? $active_domain_id : '',
              'active_domain_code' => $active_domain_code,
              'device_type' => $device_type,
              'search_event_type' => $event_type,
              'from_date' => $from_date,
              'to_date' => $to_date,
              'search_country' => $search_country,
          ]);
        $value = $this->get_screenshot_with_data($request);
        $check_error = json_decode($value,true);
        if($check_error == '') $data['screenshot_data'] = 'error';
        else $data['screenshot_data'] = $value;
        return $this->viewcontroller($data);
    }


    public function domain_switch_action (Request $request)
    {
        $old_session_domain_id = Session::get('active_domain_id_session');
        $domain_id = $request->domain_id;
        $domain_name = DB::table('visitor_analysis_domain_list')->select(['id','domain_name','domain_code'])->where(array("user_id" => $this->user_id, "id" => $domain_id))->first();

        session(['active_domain_id_session' => $domain_id]);
        session(['active_domain_name_session' => $domain_name->domain_name]);
        session(['active_domain_code_session' => $domain_name->domain_code]);
        echo json_encode(['status'=>1]);
    }


    public function set_active_url_session(Request $request)
    {
        $other_page_url = $request->other_page_url;
        session(['active_heatmap_page_name_session' => $other_page_url]);
        echo json_encode(['status'=>1]);
    }


    public function get_screenshot_with_data(Request $request)
    {
        $this->set_table_names();
        $url = $request->active_heatmap_page_name_session;
        $domain_list_id = $request->active_domain_id;
        $domain_code = $request->active_domain_code;

        $user_and_domain = explode('-',$domain_code);
        $domain_user = $user_and_domain[1] ?? 1;
        $domain_code = $user_and_domain[0] ?? 1;
        $this->table_names = get_table_names($domain_user,$domain_code);
        $device_type = $request->device_type;
        $from_date = $request->from_date;
        if ($from_date != NULL) $from_date = strtotime($from_date);
        $to_date = $request->to_date;
        if ($to_date != NULL) $to_date = strtotime($to_date);
        $search_country = $request->search_country;
        $search_event_type = $request->search_event_type;
        if ($search_event_type == NULL) $search_event_type = 'click';
        $user_id = $this->user_id;
        $domain_data = DB::table($this->table_names['heatmap_table'])
            ->select(['id','domain_code','device', 'visit_url', 'height', 'width','json_data', 'country', 'click_move_scroll', 'entry_time', 'last_engagement_time', 'total_stay_time', 'session_value', 'total_clicks'])
            ->where(array("user_id" => $this->user_id, "domain_list_id" => $domain_list_id, 'visit_url' => $url, 'click_move_scroll' => $search_event_type))
            ->get();
        // If the parent domain is unavailable, throw error
        if($domain_data->isEmpty()) {
            $response['error'] = true;
            $response['message'] = __("Sorry, we couldn't find domain data.");
            return json_encode($response);
        }
        $response = [];
        $coordinate_value = [];
        $x_values = [];
        $y_values = [];
        $height_values = [];
        $width_values = [];
        $click_counts = [];
        $positionData = [];
        $total_click_count = 0;
        $total_unique_sessions = 0;
        $all_sessions_values = [];
        $total_stay_time = 0;
        $average_stay_time = 0;
        $hours = 00;
        $minutes = 00;
        $seconds = 00;
        $wasabi_files_tableid_array = [];
        $temp_scroll_data = [];
        $final_scroll_data = [];
        foreach ($domain_data as $value) {
            $database_device = strtolower($value->device);
            if ($device_type != NULL)
                if ($device_type != $database_device)
                    continue;
            $database_date = strtotime($value->entry_time);
            if ($from_date != NULL)
                if ($database_date < $from_date)
                    continue;
            if ($to_date != NULL)
                if ($database_date > $to_date)
                    continue;

            if ($search_country != NULL)
                if ($search_country != $value->country)
                    continue;
            if ($url != $value->visit_url) continue;

            if ($value->click_move_scroll != $search_event_type)
                continue;
            array_push($all_sessions_values, $value->session_value);
            $total_stay_time += $value->total_stay_time;
            $json_to_array = [];
            $final_data = [];
            if($value->json_data == NULL)
            {
                $temp_heatmaps = DB::table($this->table_names['temp_heatmap_table'])->where(['list_data_table_id'=>$value->id])->select('json_data')->orderBy("id","ASC")->get();
                if(!$temp_heatmaps->isEmpty())
                {
                    foreach($temp_heatmaps as $single_heatmap)
                    {
                        $json_to_array = json_decode($single_heatmap->json_data,true);
                        if(is_array($json_to_array))
                        {
                            foreach($json_to_array as $single_array)
                            {
                                if($search_event_type == 'scroll')
                                {
                                    if(!isset($final_data[$single_array['x']])) $final_data[$single_array['x']]=[];
                                    if(!in_array($single_array['y'],$final_data[$single_array['x']])) array_push($final_data[$single_array['x']],(int)$single_array['y']);

                                    if(isset($single_array['x']))
                                    {
                                        $temp_scroll_data['height'] = $single_array['x'];
                                        $temp_scroll_data['positions'] = $final_data[$single_array['x']];
                                        array_push($final_scroll_data,$temp_scroll_data);
                                    }
                                }
                                else
                                {
                                    $single_click_count = $single_array['value'] ?? 1;
                                    if(isset($single_array['x'])) array_push($x_values, $single_array['x']);
                                    if(isset($single_array['y'])) array_push($y_values, $single_array['y']);
                                    array_push($height_values, $value->height);
                                    array_push($width_values, $value->width);
                                    if(isset($single_array['value'])) array_push($click_counts, $single_array['value']);
                                    $total_click_count = $total_click_count + $single_click_count;
                                }
                            }
                        }
                    }
                }
            }
            else
            {
                if(in_array($value->json_data,$wasabi_files_tableid_array)) continue;
                $wasabi_json_data = DB::table('wasabi_files_for_heatmap')->where(['id'=>$value->json_data])->select(['id','file_name'])->first();
                array_push($wasabi_files_tableid_array,$value->json_data);
                $full_path_array = explode('domain-heatmaps/',$wasabi_json_data->file_name);
                $server_file_content = Storage::disk('s3')->get('domain-heatmaps/'.$full_path_array[1]);
                $json_to_array = json_decode($server_file_content,true);

                if(is_array($json_to_array))
                {
                    foreach($json_to_array as $single_array)
                    {
                        if($search_event_type == 'scroll')
                        {
                            if(!isset($final_data[$single_array['x']])) $final_data[$single_array['x']]=[];
                            if(!in_array($single_array['y'],$final_data[$single_array['x']])) array_push($final_data[$single_array['x']],(int)$single_array['y']);

                            if(isset($single_array['x']))
                            {
                                $temp_scroll_data['height'] = $single_array['x'];
                                $temp_scroll_data['positions'] = $final_data[$single_array['x']];
                                array_push($final_scroll_data,$temp_scroll_data);
                            }
                        }
                        else
                        {
                            $single_click_count = $single_array['value'] ?? 1;

                            if(isset($single_array['x'])) array_push($x_values, $single_array['x']);
                            if(isset($single_array['y'])) array_push($y_values, $single_array['y']);
                            array_push($height_values, $value->height);
                            array_push($width_values, $value->width);
                            if(isset($single_array['value'])) array_push($click_counts, $single_array['value']);
                            $total_click_count = $total_click_count + $single_click_count;

                        }
                    }
                }
            }

        }


        $positionData = json_encode($final_scroll_data);

        // url exists but no coordinate value return 404 error.
        if(!empty($url) && empty($x_values) && $search_event_type != 'scroll') {
            $response['error'] = true;
            $response['average_stay_time'] = $hours.'h '.$minutes.'m '.$seconds.'s';
            $response['total_click_count'] = $total_click_count;
            $response['total_unique_sessions'] = $total_unique_sessions;
            $response['message'] = __("Sorry, we couldn't find any heatmap data regarding this url.");
            return json_encode($response); exit;
        }

        

        $height_values_filter = array_filter($height_values);
        $iframe_height_values = count($height_values_filter) > 0 ? round(array_sum($height_values_filter) / count($height_values_filter)) : "";
        
        $total_unique_sessions = count(array_unique($all_sessions_values));
        $response['x_values'] = $x_values;
        $response['y_values'] = $y_values;
        $response['height_values'] = $height_values;
        $response['iframe_height_values'] = $iframe_height_values;
        $response['width_values'] = $width_values;
        $response['click_counts'] = $click_counts;
        $response['num_of_rows'] = count($x_values);
        $response['total_click_count'] = $total_click_count;
        $response['total_unique_sessions'] = $total_unique_sessions;

        if($total_stay_time != 0)
        {
            $average_stay_time = $total_stay_time/$total_unique_sessions;
            $hours = floor($average_stay_time / 3600);
            $minutes = floor(($average_stay_time / 60) % 60);
            $seconds = $average_stay_time % 60;  
        }
        $response['average_stay_time'] = $hours.'h '.$minutes.'m '.$seconds.'s';
        
        $getScreenshot = DB::table("domain_screenshot")->select('image')->where(['website_code'=>$domain_data[0]->domain_code,'visit_url'=>$url,'device'=>ucfirst($device_type)])->get();
        $image_src = $getScreenshot[0]->image ?? '';
        
        if($search_event_type=='scroll'){
            $response['positionData'] = $positionData;
            $response['image_path'] = $image_src;
        }
        
        if(!empty($image_src)) {
            $response['image_src'] = $image_src;
            $response['error'] = false;
        } else {

            $response['image_src'] = '';
            $response['error'] = true;
            $response['message'] = __("Sorry, we couldn't find any screenshot regarding this url.");
        }

        $response['event_type'] = $search_event_type;
        if($search_event_type=='click')
            $response['total_clicks'] = $total_click_count;
        else
            $response['total_clicks'] = $total_click_count;
        return json_encode($response);
    }


}
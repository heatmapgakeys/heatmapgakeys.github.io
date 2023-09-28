<?php

namespace App\Http\Controllers\Heatmap;

use App\Http\Controllers\Home;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Jscontroller extends Home
{

    public function __construct()
    {
    }

    function get_ip()
    {
        $ip[0]=$this->real_ip();
        if(isset($_GET['callback'])){
            echo $_GET['callback']."(".json_encode($ip).")";
        }
    }

    public function user_session_data(Request $request)
    {
        $time=date("Y-m-d H:i:s");
        $last_engagement_time=$time;
        $ip=$this->real_ip();
        $url_array = [];
        $url_info = [];
        $page_title=$request->page_title;
        $website_code=$request->website_code;

        $user_id_code = explode('-',$website_code);
        $user_id = $user_id_code[1];
        $domain_code = $user_id_code[0];
        $table_names = get_table_names($user_id,$domain_code);
        $session_data_table_name = $table_names['sessions_table'];
        $temp_session_data_table_name = $table_names['temp_sessions_table'];
        $heatmap_data_table_name = $table_names['heatmap_table'];
        $temp_heatmap_data_table_name = $table_names['temp_heatmap_table'];
        
        $browser_name=$request->browser_name;
        $browser_version=$request->browser_version;
        $device=$request->device;
        $mobile_desktop=$request->mobile_desktop;
        $referrer=$request->referrer;

        $current_url_array = [];
        if(strpos($request->current_url,'?'))
            $current_url_array=explode('?',$request->current_url);
        else if(strpos($request->current_url,'#'))
            $current_url_array=explode('#',$request->current_url);

        $current_url = $current_url_array[0] ?? $request->current_url;
        $current_url = str_replace('https://www.','https://',$current_url);
        $current_url = str_replace('http://www.','http://',$current_url);
        $current_url = rtrim($current_url,'/#');

        $only_domain = get_domain_only($current_url);
        $cookie_value=$request->cookie_value;
        $is_new=$request->is_new;
        $total_clicks=$request->total_clicks;
        $session_value=$request->session_value;
        $browser_rawdata=$request->browser_rawdata;
        $session_data = $request->session_data;
        $session_data_array = json_decode($session_data,true);
        $session_data_json = json_encode($session_data_array);
        $url_array['url'] = $current_url;
        $url_array['page_title'] = $page_title;
        $user_country=$user_city=$user_org=$user_latitude=$user_longitude=$user_postal='';

        // heatmap data receive
        $click_move_scroll = [];
        $mouse_click_data=$request->mouse_click_data;
        $mouse_click_data = json_decode($mouse_click_data);
        $mouse_data[0] = !empty($mouse_click_data->data) ? json_encode($mouse_click_data->data) : json_encode([]);
        $click_move_scroll[0] ='click';

        $mouse_move_data=$request->mouse_move_data;
        $mouse_move_data = json_decode($mouse_move_data);
        $mouse_data[1] = !empty($mouse_move_data->data) ? json_encode($mouse_move_data->data) : json_encode([]);
        $click_move_scroll[1] ='move';

        $mouse_scroll_data=$request->mouse_scroll_data;
        $mouse_scroll_data = json_decode($mouse_scroll_data);
        $mouse_data[2] = !empty($mouse_scroll_data->data) ? json_encode($mouse_scroll_data->data) : json_encode([]);
        $click_move_scroll[2] ='scroll';
        $height=$request->height;
        $width=$request->width;
        // end of heatmap data receive

        if(strripos($referrer,$only_domain))
            $referrer = '';
        
        $where = ['session_value'=>$session_value,'cookie_value'=>$cookie_value,'domain_code'=>$website_code];
        $existing_data = DB::table($session_data_table_name)
        ->select('id','session_data','last_engagement_time','total_stay_time','visit_url','country','city','org','latitude','longitude','postal')
        ->where($where)
        ->first();

        if(isset($existing_data->id) && $existing_data->id!='')
        {
            if($existing_data->session_data != NULL)
            {
                echo json_encode(['session_distroy'=>'yes']);
                return false;
            }
            $url_info = json_decode($existing_data->visit_url,true);
            $temp_url_info = array_pop($url_info);
            if($temp_url_info['url'] == $current_url)
                array_push($url_info,$temp_url_info);
            else
            {
                array_push($url_info,$temp_url_info);
                array_push($url_info,$url_array);
            }

            $total_stay_time = $existing_data->total_stay_time + 5;
            // ip information from existing data
            $user_country=isset($existing_data->country) ? $existing_data->country: "";
            $user_city=isset($existing_data->city)? $existing_data->city: "";
            $user_org=isset($existing_data->org) ? $existing_data->org:"";
            $user_latitude=isset($existing_data->latitude) ? $existing_data->latitude :"";
            $user_longitude=isset($existing_data->longitude) ? $existing_data->longitude : "";
            $user_postal=isset($existing_data->postal) ? $existing_data->postal : "";

            $session_data_table_id = $existing_data->id;
        }
        else
        {
            /**Get Country code and country name***/
            if($ip)
            {
                $ip_info= ip_information($ip);
                $user_country=isset($ip_info['country']) ? $ip_info['country']: "";
                $user_city=isset($ip_info['city'])? $ip_info['city']: "";
                $user_org=isset($ip_info['org'])?$ip_info['org']:"";
                $user_latitude=isset($ip_info['latitude'])?$ip_info['latitude']:"";
                $user_longitude=isset($ip_info['longitude'])?$ip_info['longitude']:"";
                $user_postal=isset($ip_info['postal'])?$ip_info['postal']:"";
            }

            array_push($url_info,$url_array);
            $session_data_table_id = 0;
            $total_stay_time = 5;
        }

        $where = ['domain_code'=>"$website_code"];
        $domain_info = DB::table('visitor_analysis_domain_list')
        ->select('id','domain_name','user_id')
        ->where($where)
        ->first();

        $domain_list_id = $domain_info->id;
        $domain_name = $domain_info->domain_name;
        $user_id = $domain_info->user_id;

        // session video data insertion
        if(strtolower($only_domain) == strtolower($domain_name)) {
            if($session_data_table_id != 0)
            {
                DB::table($session_data_table_name)->where(['cookie_value'=>trim($cookie_value),'session_value'=>trim($session_value),'domain_code'=>$website_code,'id'=>$session_data_table_id])->update(['last_engagement_time'=>$last_engagement_time,'visit_url'=>json_encode($url_info),'total_stay_time'=>$total_stay_time]);
                
                if(!empty(json_decode($session_data_json)))
                {
                    DB::table($temp_session_data_table_name)->insert([
                        'session_data_table_id' => $session_data_table_id,
                        'visit_url' => $current_url,
                        'session_data' => 'has data'
                    ]);

                    // session data write into a file
                    $temp_table_id = DB::getPdo()->lastInsertId();
                    if (!file_exists(storage_path($temp_session_data_table_name))) {
                        mkdir(storage_path($temp_session_data_table_name), 0755, true);
                    }
                    $json_file_name = storage_path($temp_session_data_table_name."/".$temp_table_id.'.json');
                    file_put_contents($json_file_name, $session_data_json);

                }
            }
            else
            {
                if(!empty(json_decode($session_data_json)))
                {
                    DB::table($session_data_table_name)->insert([
                        'domain_list_id' => $domain_list_id,
                        'user_id' => $user_id,
                        'domain_code' => $website_code,
                        'ip' => $ip,
                        'country' => trim($user_country),
                        'city' => trim($user_city),
                        'org' => $user_org,
                        'latitude' => $user_latitude,
                        'longitude' => $user_longitude,
                        'postal' => $user_postal,
                        'os' => $device,
                        'device' => trim($mobile_desktop),
                        'browser_name' => trim($browser_name),
                        'browser_version' => $browser_version,
                        'referrer' => $referrer,
                        'visit_url' => json_encode($url_info),
                        'cookie_value' => trim($cookie_value),
                        'is_new' => $is_new,
                        'entry_time' => date('Y-m-d H:i:s', strtotime($time. " - 5 sec")),
                        'last_engagement_time' => $last_engagement_time,
                        'total_stay_time' => $total_stay_time,
                        'session_value' => trim($session_value),
                        'browser_rawdata' => $browser_rawdata

                    ]);

                    $session_data_table_id = DB::getPdo()->lastInsertId();
                    DB::table($temp_session_data_table_name)->insert([
                        'session_data_table_id' => $session_data_table_id,
                        'visit_url' => $current_url,
                        'session_data' => 'has data'
                    ]);

                    // session data write into a file
                    $temp_table_id = DB::getPdo()->lastInsertId();
                    if (!file_exists(storage_path($temp_session_data_table_name))) {
                        mkdir(storage_path($temp_session_data_table_name), 0755, true);
                    }
                    $json_file_name = storage_path($temp_session_data_table_name."/".$temp_table_id.'.json');
                    file_put_contents($json_file_name, $session_data_json);

                }
            }
        }
        // end of session video data insertion

        $existing_heatmaps = [];
        $existing_heatmap_info = DB::table($heatmap_data_table_name)->where(['visit_url'=>$current_url,'cookie_value'=>trim($cookie_value),'session_value'=>trim($session_value)])->select(['click_move_scroll','last_engagement_time','total_stay_time','total_clicks','id'])->get();
        if(!$existing_heatmap_info->isEmpty())
        {
            foreach($existing_heatmap_info as $single_heatmap)
            {
                $existing_heatmaps[$single_heatmap->click_move_scroll]['last_engagement_time'] = $single_heatmap->last_engagement_time;
                $existing_heatmaps[$single_heatmap->click_move_scroll]['total_stay_time'] = $single_heatmap->total_stay_time;
                $existing_heatmaps[$single_heatmap->click_move_scroll]['total_clicks'] = $single_heatmap->total_clicks;
                $existing_heatmaps[$single_heatmap->click_move_scroll]['id'] = $single_heatmap->id;
            }
        }

        // heatmap data insertion 0=click,1=move,2=scroll
        if(strtolower($only_domain) == strtolower($domain_name)) {
            for ($i = 0; $i <3 ; $i++) 
            {
                if(isset($existing_heatmaps[$click_move_scroll[$i]]))
                {
                    DB::table($heatmap_data_table_name)
                    ->where(['visit_url'=>$current_url,'cookie_value'=>trim($cookie_value),'session_value'=>trim($session_value),'click_move_scroll'=>$click_move_scroll[$i]])
                    ->update(['last_engagement_time'=>$time,'total_stay_time'=>$existing_heatmaps[$click_move_scroll[$i]]['total_stay_time']+5,'total_clicks'=>$existing_heatmaps[$click_move_scroll[$i]]['total_clicks']+$total_clicks]);
                    
                    if(!empty(json_decode($mouse_data[$i])))
                    {
                        DB::table($temp_heatmap_data_table_name)->insert([
                            'list_data_table_id' => $existing_heatmaps[$click_move_scroll[$i]]['id'],
                            'click_move_scroll' => $click_move_scroll[$i],
                            'json_data' => $mouse_data[$i]
                        ]);
                    }
                }
                else
                {
                    DB::table($heatmap_data_table_name)->insert([
                        'domain_list_id' => $domain_list_id,
                        'user_id' => $user_id,
                        'domain_code' => $website_code,
                        'ip' => $ip,
                        'country' => trim($user_country),
                        'city' => trim($user_city),
                        'org' => $user_org,
                        'latitude' => $user_latitude,
                        'longitude' => $user_longitude,
                        'postal' => $user_postal,
                        'os' => $device,
                        'device' => trim($mobile_desktop),
                        'browser_name' => trim($browser_name),
                        'browser_version' => $browser_version,
                        'referrer' => $referrer,
                        'visit_url' => $current_url,
                        'page_title' => $page_title,
                        'cookie_value' => trim($cookie_value),
                        'is_new' => $is_new,
                        'entry_time' => date('Y-m-d H:i:s', strtotime($time. " - 5 sec")),
                        'last_engagement_time' => $time,
                        'total_stay_time' => 5,
                        'session_value' => trim($session_value),
                        'browser_rawdata' => $browser_rawdata,
                        'click_move_scroll'=>$click_move_scroll[$i],
                        'height'=>$height,
                        'width'=>$width,
                        'total_clicks' => $total_clicks
                    ]);
                    if(!empty(json_decode($mouse_data[$i])))
                    {
                        $list_data_table_id = DB::getPdo()->lastInsertId();
                        DB::table($temp_heatmap_data_table_name)->insert([
                            'list_data_table_id' => $list_data_table_id,
                            'click_move_scroll' => $click_move_scroll[$i],
                            'json_data' => $mouse_data[$i]
                        ]);
                    }
                }

            } 
        }
        // end of heatmap data insertion

        echo json_encode(['session_distroy'=>'no']);

    }
   

    public function get_screenshot(Request $request)
    {
        $time=date("Y-m-d H:i:s");
        $website_code=$request->website_code;

        $current_url_array = [];
        if(strpos($request->current_url,'?'))
            $current_url_array=explode('?',$request->current_url);
        else if(strpos($request->current_url,'#'))
            $current_url_array=explode('#',$request->current_url);

        $current_url = $current_url_array[0] ?? $request->current_url;
        $current_url = str_replace('https://www.','https://',$current_url);
        $current_url = str_replace('http://www.','http://',$current_url);
        $current_url = rtrim($current_url,'/#');

        $device =$request->mobile_desktop;
        $existency_check = $request->existency_check;

        $where = ['website_code'=>$website_code,'visit_url'=>$current_url,'device'=>$device];
        $check_exist_data = DB::table('domain_screenshot')->where($where)->select(['id','image'])->first();
        
        if($existency_check == 'yes')
        {
            if(empty($check_exist_data))
                echo 'no';
            else
                echo 'yes';
            return false;

        }

        $userinfo = explode('-',$website_code);
        $user_id = $userinfo[1];
        $cookie_value=$request->cookie_value;

        $session_value=$request->session_value;
        $image_data = $request->image_data;
        $image_data=str_replace(" ","+",$image_data);
        $aws_img_path = '';
        $error_message = '';

        $folder_name = 'url-screenshot/'.$user_id.'/'.$website_code;
        $file_name = time().uniqid().$website_code.'.png';

        if (!file_exists(storage_path($folder_name))) {
            mkdir(storage_path($folder_name), 0755, true);
        }
        $image_path = storage_path($folder_name."/".$file_name);

        $base64_data = substr($image_data, strpos($image_data, ",") + 1);
        $binary_data = base64_decode($base64_data, false);
        file_put_contents($image_path, $binary_data);

        if(empty($check_exist_data))
        {
            if(file_exists($image_path))
            {
                DB::table('domain_screenshot')->insert([
                    'website_code' => $website_code,
                    'user_id' => $user_id,
                    'visit_url' => $current_url,
                    'cookie_value' => trim($cookie_value),
                    'session_value' => trim($session_value),
                    'device' => $device,
                    'image'=>url('/').'/storage/'.$folder_name.'/'.$file_name
                ]);
            }
        }
        else
        {
            if($check_exist_data->image != '')
            {
                $delete_file_url = str_replace(url('/').'/storage/',"",$check_exist_data->image);
                if(file_exists(storage_path($delete_file_url)))
                {
                    unlink(storage_path($delete_file_url));
                }
            }

            if(file_exists($image_path))
            {
                DB::table('domain_screenshot')->where(['website_code'=>$website_code,'visit_url'=>$current_url,'device'=>$device])->update([
                    'website_code' => $website_code,
                    'user_id' => $user_id,
                    'visit_url' => $current_url,
                    'cookie_value' => trim($cookie_value),
                    'session_value' => trim($session_value),
                    'device' => $device,
                    'image'=>url('/').'/storage/'.$folder_name.'/'.$file_name
                ]);
            }
        }

        echo 'done';
    }

    public function client($website_code)
    {
        $ip=$this->real_ip();

        $domain_info = DB::table('visitor_analysis_domain_list')->select(['excluded_ip','status','pause_play','deleted','recording_option'])
            ->where(['domain_code'=>$website_code])->first();
        if($domain_info->status == 'off' || $domain_info->pause_play == 'pause' || $domain_info->deleted == '1') return false;

        $excluded_ips = explode(',',$domain_info->excluded_ip);
        if(is_array($excluded_ips))
        {
            foreach ($excluded_ips as $excluded_ip) {
                if (fnmatch($excluded_ip, $ip)) {
                    // IP address is not allowed, display an error message or redirect to a different page
                    return false;
                }
            }
        }

        $recording_option = json_decode($domain_info->recording_option,true);
        $block_class =  !empty($recording_option['block_class']) ? $recording_option['block_class']  : 'rr-block';
        $ignore_class = !empty($recording_option['ignore_class']) ? $recording_option['ignore_class'] : 'rr-ignore';
        $maskText_class = !empty($recording_option['maskText_class']) ? $recording_option['maskText_class'] : 'rr-mask';
        $maskAllInputs = !empty($recording_option['maskAllInputs']) ? $recording_option['maskAllInputs'] : 'false';
        $maskInput_option = !empty($recording_option['maskInput_option']) ? explode(',',$recording_option['maskInput_option']) : [];
        $maskInput_option_string = "";
        $maskInput_option_string .= '{';
        for($i=0;$i<count($maskInput_option);$i++){
            $maskInput_option_string .=$maskInput_option[$i].':true,';
        }
        $maskInput_option_string = rtrim($maskInput_option_string,',');
        $maskInput_option_string.="}";
        $csrf_token = csrf_token();
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/javascript');
        $content = "
            var user_session_data = '".route('user-session-data')."';
            var get_screen_shot = '".route('get-screenshot')."';
            var rrweb_record_js_link='".asset('assets/node_modules/rrweb/dist/record/rrweb-record.min.js')."';
            var heatmap_js_link='".asset('assets/heatmap/js/heat_sketch.js')."';
            
            var block_class = '".$block_class."';
            var ignore_class = '".$ignore_class."';
            var maskText_class = '".$maskText_class."';
            var maskInput_option_string = ".$maskInput_option_string.";
            var maskAllInputs = '".$maskAllInputs."';

            var hmsas_22_csrf_token = '".$csrf_token."';
            var hmsas_22_fullWidth = 0;
            var hmsas_22_fullHeight = 0;
            var hmsas_22_events = [];
            var hmsas_22_total_clicks = 0;
            var hmsas_22_has_screenshot;

            function hmsas_22_ajax_dolphin(link,data,async_type){

                xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == XMLHttpRequest.DONE) {
                        hmsas_22_has_screenshot = xhr.responseText;
                    }
                }
                xhr.open('POST',link,async_type);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('X-CSRF-TOKEN', hmsas_22_csrf_token);
                xhr.send(data);
            }


            function get_browser_info(){
                var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || [];
                if(/trident/i.test(M[1])){
                    tem=/\brv[ :]+(\d+)/g.exec(ua) || [];
                    return {name:'IE',version:(tem[1]||'')};
                    }
                if(M[1]==='Chrome'){
                    tem=ua.match(/\bOPR\/(\d+)/)
                    if(tem!=null)   {return {name:'Opera', version:tem[1]};}
                    }
                M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
                if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
                return {
                  name: M[0],
                  version: M[1]
                };
            }

            /*** Creating Cookie function ***/
            function hmsas_22_createCookie(name,value,days) {
                if (days) {
                    var date = new Date();
                    date.setTime(date.getTime()+(days*24*60*60*1000));
                    var expires = '; expires='+date.toGMTString();
                }
                else var expires = '';
                document.cookie = name+'='+value+expires+'; path=/';
            }

            /***Read Cookie function**/
            function hmsas_22_readCookie(name) {
                var nameEQ = name + '=';
                var ca = document.cookie.split(';');
                for(var i=0;i < ca.length;i++) {
                    var c = ca[i];
                    while (c.charAt(0)==' ') c = c.substring(1,c.length);
                    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
                }
                return null;
            }

            /*** Delete Cookie Function ***/
            function eraseCookie(name) {
                hmsas_22_createCookie(name,'',-1);
            }

            function hmsas_22_ajax_call(){
                var w = document.createElement('link');
                var z = document.createElement('script');
                var k = document.createElement('script');
                z.setAttribute('src',rrweb_record_js_link);
                k.setAttribute('src',heatmap_js_link);

                setTimeout(function(){
                    document.head.appendChild(z);
                    document.head.appendChild(k);
                },10);   


                /**after browser plugin loaded**/

                k.onload=function(){

                    var hmsas_22_device;
                    var hmsas_22_mobile_desktop;

                    hmsas_22_device=jscd.os;
                    if(jscd.mobile == 'mobile'){
                        hmsas_22_mobile_desktop='Mobile';
                    }
                    else if(jscd.mobile == 'tablet'){
                        hmsas_22_mobile_desktop='Tablet';
                    }
                    else{
                        hmsas_22_mobile_desktop='Desktop';
                    }

                    var browser_info=get_browser_info();
                    var browser_name=browser_info.name;
                    var browser_version=browser_info.version;

                    var browser_rawdata = JSON.stringify(navigator.userAgent);
                    var website_code = document.querySelector('script#hmsas-22-domain-name').getAttribute('hmsas-22-data-name');

                    /**Get referer Address**/
                    var referrer = document.referrer;

                    /** Get Current url **/
                    var current_url = window.location.href;
                    var screenshot_url = new URL(current_url);
                    var retake_screenshot = screenshot_url.searchParams.get('retake-screenshot');

                    /*** Get cookie value , if it is already set or not **/
                    var hmsas_22_cookie_value=hmsas_22_readCookie('hmsas_22_script_cookie');
                    var extra_value= new Date().getTime();


                    /**if new visitor set the cookie value a random number***/
                    if(hmsas_22_cookie_value=='' || hmsas_22_cookie_value==null || hmsas_22_cookie_value === undefined){
                        var is_new=1;
                        var random_cookie_value=Math.floor(Math.random()*999999);
                        random_cookie_value=random_cookie_value+extra_value.toString();
                        hmsas_22_createCookie('hmsas_22_script_cookie',random_cookie_value,1);
                        hmsas_22_cookie_value=random_cookie_value;
                    }
                    else{
                        hmsas_22_createCookie('hmsas_22_script_cookie',hmsas_22_cookie_value,1);
                        var is_new=0;
                    }

                    var hmsas_22_session_value=sessionStorage.getItem('hmsas_22_script_session');
                    if(hmsas_22_session_value=='' || hmsas_22_session_value==null || hmsas_22_session_value === undefined){
                        var random_session_value=Math.floor(Math.random()*999999);
                        random_session_value=random_session_value+extra_value.toString();
                        sessionStorage.setItem('hmsas_22_script_session', random_session_value);
                        hmsas_22_session_value=random_session_value;
                    }

                    var heatmapInstance_mouse_click=[];
                    var heatmapInstance_mouse_move=[];
                    var heatmapInstance_mouse_scroll=[];
                    setTimeout(function(){
                        hmsas_22_fullHeight = Math.max(
                            document.body.offsetHeight,
                            );

                        hmsas_22_fullWidth = Math.max(
                            document.body.offsetWidth,
                            );
                        heatmapInstance_mouse_click = h337.create({
                            container: document.querySelector('#hmsas-script-loader'),
                            radius: 30
                        });
                        heatmapInstance_mouse_scroll = h337.create({
                            container: document.querySelector('#hmsas-script-loader'),
                            radius: 30
                        });
                        heatmapInstance_mouse_move = h337.create({
                            container: document.querySelector('#hmsas-script-loader'),
                            radius: 30
                        });
                    }, 200);



                    /*** User session video ****/
                    setTimeout(function(){
                        rrwebRecord({
                            emit(event){
                                hmsas_22_events.push(event);
                            },
                            maskTextClass:maskText_class,
                            blockClass:block_class,
                            ignoreClass:ignore_class,
                            maskAllInputs:maskAllInputs,
                            maskInputOptions:maskInput_option_string
                        });
                    }, 1000);

                    var hmsas_22_page_title = document.getElementsByTagName('title')[0].innerHTML;

                    document.querySelector('body').onclick = function(ev) {
                        heatmapInstance_mouse_click.addData({
                            x: ev.pageX,
                            y: ev.pageY,
                            value: 1,
                            });
                        hmsas_22_total_clicks++;
                    };

                    document.querySelector('body').onmousemove = function(ev) {
                        heatmapInstance_mouse_move.addData({
                            x: ev.pageX,
                            y: ev.pageY,
                            value: 1
                            });
                    };

                    document.querySelector('body').onwheel = function(ev) {
                        heatmapInstance_mouse_scroll.addData({
                            x: ev.pageX,
                            y: ev.pageY,
                            value: 1
                            });
                    };

                    //Screenshot of webpage
                    var image_data;
                    window.onload=function(){
                        var existency_check = 'yes';
                        var code_and_url ='website_code='+website_code+'&current_url='+current_url+'&mobile_desktop='+hmsas_22_mobile_desktop+'&existency_check='+existency_check;
                        hmsas_22_ajax_dolphin(get_screen_shot,code_and_url,false);
                        if(hmsas_22_has_screenshot == 'no' || retake_screenshot == 'yes')
                        {
                            var timeout_time = 10000;
                            if(retake_screenshot == 'yes')
                            {
                                alert('Taking Screenshot may take some times, in this time page will scroll down to bottom and then scroll up to top automatically. Please wait untill the final confirmation message appear. Now please click the ok button to start the process.');
                                timeout_time = 30000+10000+((document.body.offsetHeight/1000)*2000)+2000;
                                var scrolltotop_timeout_time = 10000+10000+((document.body.offsetHeight/1000)*2000)+2000;
                            }
                            
                            existency_check = 'no';

                            setTimeout(function() {

                              var loop_counter = document.body.offsetHeight/1000;
                              var loop_timeout_time = 2000;
                              var fullheight_timeout_time = 0;
                              var bottom_pos_final = 0;


                              for(var height_loop=1; height_loop<=loop_counter; height_loop++ )
                              {


                                (function(){ 

                                var _height_loop=height_loop;
                                var bottom_pos = _height_loop*1000;
                                var top_pos = bottom_pos-1000;

                                var individual_loop_timeout_time = loop_timeout_time*_height_loop;

                                setTimeout(function() {
                                  window.scrollTo(top_pos, bottom_pos);
                                }, individual_loop_timeout_time);

                                fullheight_timeout_time = loop_timeout_time*_height_loop;
                                bottom_pos_final=bottom_pos;

                                })();
                              }

                              setTimeout(function() {
                                window.scrollTo(bottom_pos_final,document.body.offsetHeight);
                              }, fullheight_timeout_time);

                              setTimeout(function() {
                                window.scrollTo(0, 0);
                              }, scrolltotop_timeout_time);
                            }, 10000);
            

                            setTimeout(function() {
                                html2canvas(document.body,{scrollX: -window.scrollX,scrollY: -window.scrollY,windowWidth: document.body.offsetWidth,windowHeight: document.body.offsetHeight}).then(function(canvas){
                                        var image_data = canvas.toDataURL('image/png');
                                        var data ='website_code='+website_code+'&current_url='+current_url+'&session_value='+hmsas_22_session_value+'&cookie_value='+hmsas_22_cookie_value+'&mobile_desktop='+hmsas_22_mobile_desktop+'&existency_check='+existency_check+'&image_data='+image_data;
                                        hmsas_22_ajax_dolphin(get_screen_shot,data,false);
                                        if(hmsas_22_has_screenshot == 'done' && retake_screenshot == 'yes')
                                        {
                                            alert('Screenshot has been taken successfully.');
                                            window.close();
                                        }
                                    });
                            }, timeout_time);
                        }
                    }


                    setInterval(function() {
                        var mouse_click_data = heatmapInstance_mouse_click.getData();
                        var mouse_move_data = heatmapInstance_mouse_move.getData();
                        var mouse_scroll_data = heatmapInstance_mouse_scroll.getData();
                        var ajax_call_condition = 'yes';

                        heatmapInstance_mouse_click.setData({data:[]});
                        heatmapInstance_mouse_move.setData({data:[]});
                        heatmapInstance_mouse_scroll.setData({data:[]});

                        if(JSON.stringify(hmsas_22_events) == '[]')
                            ajax_call_condition = 'no';

                        mouse_click_data = JSON.stringify(mouse_click_data);
                        mouse_move_data = JSON.stringify(mouse_move_data);
                        mouse_scroll_data = JSON.stringify(mouse_scroll_data);

                        var height = hmsas_22_fullHeight;
                        var width = hmsas_22_fullWidth;

                        const strintgify_events = JSON.stringify(hmsas_22_events);
                        hmsas_22_events = [];

                        const body = JSON.stringify({'session_data':strintgify_events,'website_code':website_code,'current_url':current_url,'cookie_value':hmsas_22_cookie_value,'session_value':hmsas_22_session_value,'browser_name':browser_name,'browser_version':browser_version,'device':hmsas_22_device,'referrer':referrer,'is_new':is_new,'browser_rawdata':browser_rawdata,'mobile_desktop':hmsas_22_mobile_desktop,'page_title':hmsas_22_page_title,'height':height,'width':width,'mouse_click_data':mouse_click_data,'mouse_move_data':mouse_move_data,'mouse_scroll_data':mouse_scroll_data,'total_clicks':hmsas_22_total_clicks});
                        
                        hmsas_22_total_clicks = 0;

                        if(ajax_call_condition == 'yes')
                        {
                            fetch(user_session_data, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': hmsas_22_csrf_token
                                },
                                body,
                            }).then(response=>response.json())
                                .then((data) => {
                                    if(data.session_distroy == 'yes')
                                    {
                                        sessionStorage.removeItem('hmsas_22_script_session');
                                        location.reload();
                                    }
                                });
                        }

                    }, 5000);

                } // end of y.onload

            } //end of ajax call

            function init(){
                hmsas_22_ajax_call();
            }

            init();
        "; 
        echo $content;
    }


    protected function real_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}
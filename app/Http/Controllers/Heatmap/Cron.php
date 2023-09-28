<?php

namespace App\Http\Controllers\Heatmap;

use App\Http\Controllers\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class Cron extends Home
{
    public $telegram;

    public function __construct()
    {
    }

    // Every 5 minutes
    public function s3_export_sessionrecodings()
    {
        try {

            $table_names = [];
            $table_names[0] = '_odd_odd';
            $table_names[1] = '_odd_even';
            $table_names[2] = '_even_odd';
            $table_names[3] = '_even_even';


            foreach($table_names as $table_name)
            {
                $time = date("Y-m-d H:i:s");
                $twohours_before = date("Y-m-d H:i:s", strtotime($time. " - 2 hours"));
                $video_sessions = DB::table('visitor_analysis_session_data'.$table_name)->where('entry_time','<=',$twohours_before)->where(['cron_processing'=>'no'])->select(['id','user_id','domain_code','session_data'])->limit(50)->orderBy("last_cron_time","ASC")->get();
                $session_table_ids = [];

                if(!$video_sessions->isEmpty())
                {
                    foreach($video_sessions as $session_ids)
                        array_push($session_table_ids, $session_ids->id);
                }
                if(!empty($session_table_ids))
                    DB::table('visitor_analysis_session_data'.$table_name)->whereIntegerInRaw('id',$session_table_ids)->update(['cron_processing'=>'yes']);

                if(!$video_sessions->isEmpty())
                {
                    foreach($video_sessions as $value)
                    {
                        if($value->session_data === NULL)
                        {
                            $child_video_sessions_array = [];
                            $i = 0;
                            $temp_sessions = DB::table('visitor_analysis_temp_sessions'.$table_name)->where(['session_data_table_id'=>$value->id])->select('session_data','id')->orderBy("id","ASC")->get();
                            if(!$temp_sessions->isEmpty())
                            {
                                foreach($temp_sessions as $session)
                                {
                                    if($i==0)
                                    {
                                        if(file_exists(storage_path('visitor_analysis_temp_sessions'.$table_name.'/'.$session->id.'.json')))
                                        {
                                            $json_file_data = file_get_contents(storage_path('visitor_analysis_temp_sessions'.$table_name.'/'.$session->id.'.json'));
                                            $child_video_sessions_array = json_decode($json_file_data,true);
                                        }
                                    }
                                    else
                                    {
                                        if(file_exists(storage_path('visitor_analysis_temp_sessions'.$table_name.'/'.$session->id.'.json')))
                                        {
                                            $json_file_data = file_get_contents(storage_path('visitor_analysis_temp_sessions'.$table_name.'/'.$session->id.'.json'));
                                            $child_temp_sessions_array = json_decode($json_file_data,true);
                                        }

                                        foreach($child_temp_sessions_array as $child_session)
                                            array_push($child_video_sessions_array,$child_session);
                                    }
                                    $i++;

                                    // delete the temp session file
                                    @unlink(storage_path('visitor_analysis_temp_sessions'.$table_name.'/'.$session->id.'.json'));
                                }
                            }

                            // need to delete temp session table data
                            DB::table('visitor_analysis_temp_sessions'.$table_name)->where(['session_data_table_id'=>$value->id])->delete();

                            $folder_name = 'session-recordings/'.$value->user_id.'/'.$value->domain_code.'/'.date('Y').'/'.date('m').'/'.date('d');
                            $file_name = uniqid().$value->id.'.json';


                            if(env('AWS_UPLOAD_ENABLED'))
                            {

                                file_put_contents(storage_path($file_name),json_encode($child_video_sessions_array));

                                try {
                                    config(['filesystems.disks.s3.visibility'=>'private']);
                                    $upload2S3 = Storage::disk('s3')->putFileAs($folder_name, storage_path($file_name), $file_name);
                                    $aws_img_path = Storage::disk('s3')->url($upload2S3);
                                    DB::table('visitor_analysis_session_data'.$table_name)->where(['id'=>$value->id])->update(['session_data'=>$aws_img_path,'last_cron_time'=>date("Y-m-d H:i:s")]);
                                    unlink(storage_path($file_name));
                                }
                                catch (\Exception $e){
                                    $error_message = $e->getMessage();
                                    echo $error_message;
                                }
                            }
                        }
                        else
                        {
                            // need to delete temp session table data
                            DB::table('visitor_analysis_temp_sessions'.$table_name)->where(['session_data_table_id'=>$value->id])->delete();
                        }

                    }
                }
            }
            
        }
        catch (\Throwable $e){
            echo $error = $e->getMessage();
        }
    }

    // Every 6 minutes
    public function s3_export_heatmap_data()
    {
        try {

            $table_names = [];
            $table_names[0] = '_odd_odd';
            $table_names[1] = '_odd_even';
            $table_names[2] = '_even_odd';
            $table_names[3] = '_even_even';

            foreach($table_names as $table_name)
            {
                $time = date("Y-m-d H:i:s");
                $twohours_before = date("Y-m-d H:i:s", strtotime($time. " - 2 hours"));

                $heatmap_data = DB::table('visitor_analysis_domain_list_data'.$table_name)->where('entry_time','<=',$twohours_before)->whereNull('json_data')->where(['cron_processing'=>'no'])->select(['id','user_id','domain_code','click_move_scroll','json_data','entry_time','visit_url'])->limit(100)->orderBy("last_cron_time","ASC")->get();

                $heatmap_table_ids = [];

                if(!$heatmap_data->isEmpty())
                {
                    foreach($heatmap_data as $heatmap_id)
                        array_push($heatmap_table_ids, $heatmap_id->id);
                }
                if(!empty($heatmap_table_ids))
                    DB::table('visitor_analysis_domain_list_data'.$table_name)->whereIntegerInRaw('id',$heatmap_table_ids)->update(['cron_processing'=>'yes']);

                if(!$heatmap_data->isEmpty())
                {
                    foreach($heatmap_data as $value)
                    {
                        $existing_wasabi_file = DB::table('wasabi_files_for_heatmap')->where(['domain_code'=>$value->domain_code,'storage_date'=>date('Y-m-d',strtotime($value->entry_time)),'click_move_scroll'=>$value->click_move_scroll,'visit_url'=>$value->visit_url])->select(['id','file_name'])->first();

                        $child_heatmaps_array = [];
                        $i = 0;
                        $temp_sessions = DB::table('visitor_analysis_temp_heatmap_data'.$table_name)->where(['list_data_table_id'=>$value->id])->select('json_data')->orderBy("id","ASC")->get();
                        if(!$temp_sessions->isEmpty())
                        {
                            foreach($temp_sessions as $session)
                            {
                                if($i==0)
                                    $child_heatmaps_array = json_decode($session->json_data,true);
                                else
                                {
                                    $child_temp_sessions_array = json_decode($session->json_data,true);
                                    foreach($child_temp_sessions_array as $child_session)
                                        array_push($child_heatmaps_array,$child_session);
                                }
                                $i++;
                            }
                        }

                        // need to delete temp session table data
                        DB::table('visitor_analysis_temp_heatmap_data'.$table_name)->where(['list_data_table_id'=>$value->id])->delete();

                        $folder_name = 'domain-heatmaps/'.$value->user_id.'/'.$value->domain_code.'/'.date('Y').'/'.date('m').'/'.date('d');
                        $file_name = uniqid().$value->id.'.json';

                        if(!isset($existing_wasabi_file->id))
                        {
                            if(env('AWS_UPLOAD_ENABLED'))
                            {
                                file_put_contents(storage_path($file_name),json_encode($child_heatmaps_array));

                                try {
                                    config(['filesystems.disks.s3.visibility'=>'private']);
                                    $upload2S3 = Storage::disk('s3')->putFileAs($folder_name, storage_path($file_name), $file_name);
                                    $aws_img_path = Storage::disk('s3')->url($upload2S3);

                                    DB::table('wasabi_files_for_heatmap')->insert([
                                        'user_id'=>$value->user_id,
                                        'domain_code'=>$value->domain_code,
                                        'storage_date'=>date('Y-m-d',strtotime($value->entry_time)),
                                        'click_move_scroll'=>$value->click_move_scroll,
                                        'visit_url'=>$value->visit_url,
                                        'file_name'=>$aws_img_path
                                    ]);
                                    $lastInsertId = DB::getPdo()->lastInsertId();

                                    DB::table('visitor_analysis_domain_list_data'.$table_name)->where(['id'=>$value->id])->update(['json_data'=>$lastInsertId,'last_cron_time'=>date("Y-m-d H:i:s")]);
                                    unlink(storage_path($file_name));
                                }
                                catch (\Exception $e){
                                    $error_message = $e->getMessage();
                                    echo $error_message;
                                }
                            }
                        }
                        else
                        {
                            $full_path_array = explode('domain-heatmaps/',$existing_wasabi_file->file_name);
                            $only_file_path = 'domain-heatmaps/'.$full_path_array[1];

                            try{
                                $server_file_content = Storage::disk('s3')->get($only_file_path);
                            }
                            catch (\Exception $e){
                                $server_file_content=json_encode(array());
                                $error_message = $e->getMessage();
                                echo $value->json_data.": ".$error_message;
                            }
                            
                            $server_file_content = json_decode($server_file_content,true);
                            foreach($child_heatmaps_array as $child_heatmap)
                                array_push($server_file_content,$child_heatmap);
                            
                            file_put_contents(storage_path($file_name),json_encode($server_file_content));

                            if(env('AWS_UPLOAD_ENABLED')){
                                try {
                                    config(['filesystems.disks.s3.visibility'=>'private']);
                                    $upload2S3 = Storage::disk('s3')->putFileAs($folder_name, storage_path($file_name), $file_name);
                                    $aws_img_path = Storage::disk('s3')->url($upload2S3);

                                    DB::table('wasabi_files_for_heatmap')->where(['id'=>$existing_wasabi_file->id])->update(['file_name'=>$aws_img_path]);

                                    DB::table('visitor_analysis_domain_list_data'.$table_name)->where(['id'=>$value->id])->update(['json_data'=>$existing_wasabi_file->id,'last_cron_time'=>date("Y-m-d H:i:s")]);
                                    unlink(storage_path($file_name));
                                    Storage::disk('s3')->delete($only_file_path);
                                }
                                catch (\Exception $e){
                                    $error_message = $e->getMessage();
                                    echo $error_message;
                                }
                            }
                        }

                    }
                }
            }

        }
        catch (\Throwable $e){
            echo $error = $e->getMessage();
        }
    }

    public function s3_export_heatmap_data_conversion()
    {
        try {

            $table_names = [];
            $table_names[0] = '_odd_odd';
            $table_names[1] = '_odd_even';
            $table_names[2] = '_even_odd';
            $table_names[3] = '_even_even';

            foreach($table_names as $table_name)
            {
                $time = date("Y-m-d H:i:s");
                $twohours_before = date("Y-m-d H:i:s");

                $heatmap_data = DB::table('visitor_analysis_domain_list_data'.$table_name)->whereNotNull('json_data')->where(['cron_processing'=>'no'])->select(['id','user_id','domain_code','click_move_scroll','json_data','entry_time','visit_url'])->limit(300)->orderBy("last_cron_time","ASC")->get();

                $heatmap_table_ids = [];

                if(!$heatmap_data->isEmpty())
                {
                    foreach($heatmap_data as $heatmap_id)
                        array_push($heatmap_table_ids, $heatmap_id->id);
                }
                if(!empty($heatmap_table_ids))
                    DB::table('visitor_analysis_domain_list_data'.$table_name)->whereIntegerInRaw('id',$heatmap_table_ids)->update(['cron_processing'=>'yes']);


                if(!$heatmap_data->isEmpty())
                {
                    foreach($heatmap_data as $value)
                    {
                        $existing_wasabi_file = DB::table('wasabi_files_for_heatmap')->where(['domain_code'=>$value->domain_code,'storage_date'=>date('Y-m-d',strtotime($value->entry_time)),'click_move_scroll'=>$value->click_move_scroll,'visit_url'=>$value->visit_url])->select(['id','file_name'])->first();

                        $child_heatmaps_array = [];
                        $full_path_array1 = explode('domain-heatmaps/',$value->json_data);
                        $only_file_path1 = 'domain-heatmaps/'.$full_path_array1[1];
                        try{
                            $server_file_content = Storage::disk('s3')->get($only_file_path1);
                        }
                        catch (\Exception $e){
                            $server_file_content=json_encode(array());
                            $error_message = $e->getMessage();
                            echo $value->json_data.": ".$error_message;
                        }
                        
                        $server_file_content = json_decode($server_file_content,true);
                        $child_heatmaps_array = $server_file_content;
                        Storage::disk('s3')->delete($only_file_path1);



                        $folder_name = 'domain-heatmaps/'.$value->user_id.'/'.$value->domain_code.'/'.date('Y',strtotime($value->entry_time)).'/'.date('m',strtotime($value->entry_time)).'/'.date('d',strtotime($value->entry_time));
                        $file_name = uniqid().$value->id.'.json';


                        if(!isset($existing_wasabi_file->id))
                        {
                            file_put_contents(storage_path($file_name),json_encode($child_heatmaps_array));

                            if(env('AWS_UPLOAD_ENABLED')){
                                try {
                                    config(['filesystems.disks.s3.visibility'=>'private']);
                                    $upload2S3 = Storage::disk('s3')->putFileAs($folder_name, storage_path($file_name), $file_name);
                                    $aws_img_path = Storage::disk('s3')->url($upload2S3);

                                    DB::table('wasabi_files_for_heatmap')->insert([
                                        'user_id'=>$value->user_id,
                                        'domain_code'=>$value->domain_code,
                                        'storage_date'=>date('Y-m-d',strtotime($value->entry_time)),
                                        'click_move_scroll'=>$value->click_move_scroll,
                                        'visit_url'=>$value->visit_url,
                                        'file_name'=>$aws_img_path
                                    ]);
                                    $lastInsertId = DB::getPdo()->lastInsertId();

                                    DB::table('visitor_analysis_domain_list_data'.$table_name)->where(['id'=>$value->id])->update(['json_data'=>$lastInsertId,'last_cron_time'=>date("Y-m-d H:i:s")]);
                                    unlink(storage_path($file_name));
                                }
                                catch (\Exception $e){
                                    $error_message = $e->getMessage();
                                    echo $error_message;
                                }
                            }
                        }
                        else
                        {
                            $full_path_array = explode('domain-heatmaps/',$existing_wasabi_file->file_name);
                            $only_file_path2 = 'domain-heatmaps/'.$full_path_array[1];

                            try{
                                 $server_file_content = Storage::disk('s3')->get($only_file_path2);

                            }
                            catch (\Exception $e){
                                $server_file_content=json_encode(array());
                                $error_message = $e->getMessage();
                                echo $value->json_data.": ".$error_message;
                            }
                            
                            $server_file_content = json_decode($server_file_content,true);
                            foreach($child_heatmaps_array as $child_heatmap)
                                array_push($server_file_content,$child_heatmap);
                            
                            file_put_contents(storage_path($file_name),json_encode($server_file_content));

                            if(env('AWS_UPLOAD_ENABLED')){
                                try {
                                    config(['filesystems.disks.s3.visibility'=>'private']);
                                    $upload2S3 = Storage::disk('s3')->putFileAs($folder_name, storage_path($file_name), $file_name);
                                    $aws_img_path = Storage::disk('s3')->url($upload2S3);

                                    DB::table('wasabi_files_for_heatmap')->where(['id'=>$existing_wasabi_file->id])->update(['file_name'=>$aws_img_path]);

                                    DB::table('visitor_analysis_domain_list_data'.$table_name)->where(['id'=>$value->id])->update(['json_data'=>$existing_wasabi_file->id,'last_cron_time'=>date("Y-m-d H:i:s")]);
                                    unlink(storage_path($file_name));
                                    Storage::disk('s3')->delete($only_file_path2);
                                }
                                catch (\Exception $e){
                                    $error_message = $e->getMessage();
                                    echo $error_message;
                                }
                            }
                        }

                    }
                }
            }

        }
        catch (\Throwable $e){
            echo $error = $e->getMessage();
        }
    }

    // Every Hour
    public function domain_validity_check()
    {

        $domains = DB::table('visitor_analysis_domain_list')->select(['id','user_id','domain_code'])->orderBy("last_validity_check_time","DESC")->limit(50)->get();
        $packages = DB::table('packages')->select(['monthly_limit','id','price'])->get();
        $package_info = [];
        foreach($packages as $package)
        {
            $limit_info = json_decode($package->monthly_limit,true);
            $package_info[$package->id]['session_limit'] = $limit_info[2] ?? 0;
            $package_info[$package->id]['storage_month'] = $limit_info[3] ?? 0;
            $package_info[$package->id]['price'] = $package->price;
        }

        $user_status = [];

        if(!$domains->isEmpty())
        {
            foreach($domains as $domain_info)
            {
                $user_id_code = explode('-',$domain_info->domain_code);
                $user_id = $user_id_code[1];
                $domain_code = $user_id_code[0];
                $table_names = get_table_names($user_id,$domain_code);

                $domain_validity = 'on';
                $today = date('Y-m-d H:i:s');
                $user_id = $domain_info->user_id;
                $user_info = DB::table('users')->where(['users.id'=>$user_id])->select(['expired_date','package_id','created_at','user_type'])->first();

                // data delete based on package
                if($user_info->user_type == 'Admin')
                    $storage_month = ENV("ADMIN_DATA_STORAGE_MONTH") ?? 2;
                else
                    $storage_month = $package_info[$user_info->package_id]['storage_month'];
                
                $delete_from_date = date("Y-m-d H:i:s", strtotime($today. " - $storage_month months"));
                DB::table($table_names['sessions_table'])->where(['user_id'=>$user_id])->where('entry_time','<',$delete_from_date)->delete();
                for ($i=0; $i < 6; $i++) { 
                    $calculated_date = date("Y-m-d H:i:s", strtotime($delete_from_date. " - $i days"));
                    $folder_name = 'session-recordings/'.$user_id.'/'.$domain_info->domain_code.'/'.date('Y',strtotime($calculated_date)).'/'.date('m',strtotime($calculated_date)).'/'.date('d',strtotime($calculated_date));
                    Storage::disk('s3')->deleteDirectory($folder_name);

                    $folder_name = 'domain-heatmaps/'.$user_id.'/'.$domain_info->domain_code.'/'.date('Y',strtotime($calculated_date)).'/'.date('m',strtotime($calculated_date)).'/'.date('d',strtotime($calculated_date));
                    Storage::disk('s3')->deleteDirectory($folder_name);
                }
                DB::table($table_names['heatmap_table'])->where(['user_id'=>$user_id])->where('entry_time','<',$delete_from_date)->delete();
                DB::table('wasabi_files_for_heatmap')->where(['user_id'=>$user_id])->where('storage_date','<',date('Y-m-d',strtotime($delete_from_date)))->delete();
                // end fo data delete

                if(isset($user_status[$user_id]))
                {
                    DB::table('visitor_analysis_domain_list')->where(['id'=>$domain_info->id,'user_id'=>$user_id])->update(['status'=>$user_status[$user_id],'last_validity_check_time'=>date('Y-m-d H:i:s')]);
                    continue;
                }


                if($user_info->user_type == 'Admin') continue;

                if(isset($user_info->expired_date) && $user_info->expired_date != '')
                {
                    if((strtotime($user_info->expired_date) < strtotime($today)) && $package_info[$user_info->package_id]['price'] != '0')
                        $domain_validity = 'off';
                    else if($package_info[$user_info->package_id]['price'] == '0')
                        $cycle_start_date = $user_info->created_at;
                    else
                    {
                        $cycle_ino = DB::table('transaction_logs')->where(['user_id'=>$user_id])->orderBy('id','desc')->select('cycle_start_date')->first();
                        $cycle_start_date = $cycle_ino->cycle_start_date ?? '';
                    }

                    if($domain_validity == 'on')
                    {
                        $month_difference = $this->month_difference_two_date($cycle_start_date,$today);
                        if(date('d') < date('d',strtotime($cycle_start_date)))
                            $month_difference = $month_difference-1;
                        $from_date = date("Y-m-d H:i:s", strtotime($cycle_start_date. " + $month_difference months"));
                        $session_data = DB::table($table_names['sessions_table'])->where(['user_id'=>$user_id])->where('entry_time','>=',$from_date)->select(DB::raw('count(id) as total_sessions'))->first();
                        if($session_data->total_sessions != '' && ($session_data->total_sessions > $package_info[$user_info->package_id]['session_limit']))
                            $domain_validity = 'off';
                    }
                    $user_status[$user_id] = $domain_validity;
                    DB::table('visitor_analysis_domain_list')->where(['id'=>$domain_info->id,'user_id'=>$user_id])->update(['status'=>$domain_validity,'last_validity_check_time'=>date('Y-m-d H:i:s')]);
                }

            }
        }
    }

    // 2 per day
    public function domain_delete_action()
    {
        try {

            $domain_info = DB::table('visitor_analysis_domain_list')->where(['deleted'=>'1'])->select(['id','user_id','domain_code'])->limit(2)->get();

            if(!$domain_info->isEmpty())
            {
                foreach ($domain_info as $value)
                {
                    $domain_code = $value->domain_code;
                    $user_id = $value->user_id;
                    if($domain_code != '')
                    {
                        $query = DB::table('visitor_analysis_domain_list')->where(['id'=>$value->id])->delete();

                        DB::table('domain_screenshot')->where(['user_id'=>$user_id,'website_code'=>$domain_code])->delete();
                        DB::table('wasabi_files_for_heatmap')->where(['user_id'=>$user_id,'domain_code'=>$domain_code])->delete();
                        
                        $screenshot_directory = 'url-screenshot/'.$user_id.'/'.$domain_code;
                        Storage::disk('s3')->deleteDirectory($screenshot_directory);

                        if (File::exists(storage_path($screenshot_directory)))
                        {
                            File::deleteDirectory(storage_path($screenshot_directory));
                        }

                        $directory = 'session-recordings/'.$user_id.'/'.$domain_code;
                        Storage::disk('s3')->deleteDirectory($directory);
                        $heatmap_directory = 'domain-heatmaps/'.$user_id.'/'.$domain_code;
                        Storage::disk('s3')->deleteDirectory($heatmap_directory);
                        
                    }
                }
            }

        }
        catch (\Throwable $e){
            $error = $e->getMessage();
            echo $error;
        }
        
    }

    // 1 per day. 
    public function user_delete_action()
    {
        try {

            $user_info = DB::table('users')->where(['deleted'=>'1'])->select(['id'])->limit(2)->get();

            if(!$user_info->isEmpty())
            {
                foreach ($user_info as $value)
                {
                    $query = DB::table('users')->where(['id'=>$value->id])->delete();   
                }
            }

        }
        catch (\Throwable $e){
            $error = $e->getMessage();
            echo $error;
        }
    }

    //  every 3 hours 
    public function get_screenshot_for_domain()
    {
        $domain_info = DB::table('visitor_analysis_domain_list')->whereNull('screenshot')->select(['id','domain_prefix','domain_name'])->limit(10)->orderBy("id","DESC")->get();

        $xdata = DB::table('settings')->select('social_apps_setting')->first();
        $social_apps_setting = isset($xdata->social_apps_setting) ? json_decode($xdata->social_apps_setting,true):[];
        $google_api_key_data = json_decode($social_apps_setting['google_app_setting']);
        $google_api_key = isset($google_api_key_data->google_api_key) ? $google_api_key_data->google_api_key:"";
        $api_key = $google_api_key;
        foreach($domain_info as $value)
        {
            $get_screenshot_with_domain = $value->domain_prefix.$value->domain_name;
            
            $api_url = "https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url={$get_screenshot_with_domain}&key=" . $api_key;
            $hit = file_get_contents($api_url);

            if($hit === false) {
                $screenshot = NULL;
            } else {
                $screenshot = json_decode($hit);
                // $screenshot = $screenshot->lighthouseResult->audits->{'full-page-screenshot'}->details->screenshot->data;
                $screenshot = $screenshot->lighthouseResult->fullPageScreenshot->screenshot->data;
            }

            DB::table('visitor_analysis_domain_list')->where(['id'=>$value->id])->update(['screenshot' => $screenshot]);

        }

    }

    public function month_difference_two_date($date1,$date2)
    {
        $ts1 = strtotime($date1);
        $ts2 = strtotime($date2);

        $year1 = date('Y', $ts1);
        $year2 = date('Y', $ts2);

        $month1 = date('m', $ts1);
        $month2 = date('m', $ts2);

        $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
        return $diff;
    }

    public function sync_language(){
        $s3_url = request()->s3_url;
        $language = request()->lang;
        if(empty($s3_url)){
           echo json_encode(['ok'=>false,'description'=>'Invalid AWS S3 URL provided.','request'=>request()->all()]);
        }
        else{
            // download from aws and store temp
            $path = storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR.'lang-download-'.time());
            $downloadPath = $path.'.zip';
            @file_put_contents($downloadPath,file_get_contents($s3_url));
            $extractPath = resource_path('lang');

            //deleting previous lang folder as well as json file
            @file_delete_directory($extractPath.DIRECTORY_SEPARATOR.$language);
            @File::delete($extractPath.DIRECTORY_SEPARATOR.$language.'.json');

            //unzipping to resources/lang folder
            @file_unzip($downloadPath,$extractPath);
            //deleting the temp aws downloaded file
            @File::delete($downloadPath);

            $response = json_encode(['ok'=>true,'description'=>$s3_url,'request'=>request()->all()]);
            echo $response;
        }
    }



    public function delete_language(){
        $language = request()->lang;
        if(empty($language)){
           echo json_encode(['ok'=>false,'description'=>'Invalid locale provided.','request'=>request()->all()]);
        }
        else{

            // store the language name
            $locale_name = $language;
            // set json file path
            $json_file = resource_path('lang').DIRECTORY_SEPARATOR.$locale_name.'.json';

            $vendor_directories = [
                resource_path('lang').DIRECTORY_SEPARATOR,
                resource_path('lang').DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'forum'.DIRECTORY_SEPARATOR,
                resource_path('lang').DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'translation'.DIRECTORY_SEPARATOR,
            ];

            foreach($vendor_directories as $directory){
                $directory = $directory.$locale_name;
                @file_delete_directory($directory);
            }
            @File::delete($json_file);

            $response = json_encode(['ok'=>true,'description'=>'success','request'=>request()->all()]);
            echo $response;
        }
    }

    public function clean_system_logs(){
        @unlink(storage_path('logs/laravel.log'));
    }

    public function get_paypal_subscriber_transaction(){
        $where = [
            ['paypal_subscriber_id', '!=',''],
            ['subscription_enabled','=','1'],
            ['paypal_next_check_time','<=',Carbon::now()->toDateTimeString()],
        ];
        $data = DB::table('users')->select('users.*','settings_payments.paypal','settings_payments.currency')->leftJoin("settings_payments","users.parent_user_id","=","settings_payments.user_id")->where($where)->orderByRaw('paypal_next_check_time asc')->limit(10)->get();

        $paypal_processing_data = [];
        foreach ($data as $user) {
            array_push($paypal_processing_data,$user->id);
        }
        DB::table('users')->whereIn('id',$paypal_processing_data)->update(['paypal_processing'=>'1']);
        foreach ($data as $user) {
            $id = $user->id;
            $paypal_credintial = $user->paypal;
            $paypal_credintial = json_decode($paypal_credintial,true);
            $paypal_client_id = $paypal_credintial['paypal_client_id'];
            $paypal_client_secret = $paypal_credintial['paypal_client_secret'];
            $currency = $user->currency;

            $paypal_app_id = $paypal_credintial['paypal_app_id'];
            $paypal_mode = $paypal_credintial['paypal_mode'];
            $paypal_subscriber_id = $user->paypal_subscriber_id;
            $expired_date = strtotime($user->expired_date);
            $provider = new PayPalClient;
            $subscription_data = json_decode($user->subscription_data,true);
            $package_id = $subscription_data['package_id'];
            if($paypal_mode == 'sandbox'){
               $config = [
                   'mode'    => 'sandbox',
                   'sandbox' => [
                       'client_id'         => $paypal_client_id,
                       'client_secret'     => $paypal_client_secret,
                       'app_id'            => $paypal_app_id,
                   ],
                   'payment_action' => 'Sale',
                   'currency'       => $currency,
                   'notify_url'     => '',
                   'locale'         => 'en_US',
                   'validate_ssl'   => true,
               ];
               $provider->setApiCredentials($config);
            }
            else{
                $config = [
                    'mode'    => 'live',
                    'live' => [
                        'client_id'         => $paypal_client_id,
                        'client_secret'     => $paypal_client_secret,
                        'app_id'            => $paypal_app_id,
                    ],
                    'payment_action' => 'Sale',
                    'currency'       => $currency,
                    'notify_url'     => '',
                    'locale'         => 'en_US',
                    'validate_ssl'   => true,
                ];
                $provider->setApiCredentials($config);
            }
            $provider->getAccessToken();
            $timestamp = time()-(365*24*60*60);
            $one_year_ago_date = gmdate("Y-m-d\TH:i:s\Z",$timestamp);

            $response = $provider->listSubscriptionTransactions($paypal_subscriber_id,$one_year_ago_date,gmdate("Y-m-d\TH:i:s\Z",time()));
            $transaction_id = $response['transactions'][0]['id'] ?? '';
            $buyer_user_id = $user->id ?? null;
            $payment_type = "PayPal";

            $check_duplicate = DB::table("transaction_logs")->select('transaction_id')->where(['buyer_user_id'=>$buyer_user_id,'transaction_id'=>$transaction_id,'payment_method'=>$payment_type])->first();
            $previous_transaction_id = $check_duplicate->transaction_id ?? '';
            if($previous_transaction_id == $transaction_id && get_domain_only(env('APP_URL'))!='aipen.test') dd("Transaction ID duplicated.");

            $subscription_time = strtotime($response['transactions'][0]['time']);
            $get_payment_validity_data = $this->get_payment_validity_data($user->id,$package_id);
            // dd($get_payment_validity_data);
            $cycle_start_date = $get_payment_validity_data['cycle_start_date'] ?? date("Y-m-d");
            $cycle_expired_date = $get_payment_validity_data['cycle_expired_date'] ?? date("Y-m-d");
            $insert_data=array(
                "verify_status"     => $response['transactions'][0]['status'] ?? '',
                "user_id"           => 1,
                "buyer_user_id"     => $buyer_user_id,
                "first_name"        => $response['transactions'][0]['payer_name']['given_name'] ?? '',
                "last_name"         => $response['transactions'][0]['payer_name']['surname'] ?? '',
                "buyer_email"       => $response['transactions'][0]['payer_email'] ?? '',
                "paid_currency"     => $response['transactions'][0]['amount_with_breakdown']['gross_amount']['currency_code'] ?? '',
                "paid_at"           => $response['transactions'][0]['time'] ?? '',
                "payment_method"    => $payment_type ?? '',
                "transaction_id"    => $transaction_id,
                "paid_amount"       => $response['transactions'][0]['amount_with_breakdown']['gross_amount']['value'] ?? '',
                "cycle_start_date"  => $cycle_start_date,
                "cycle_expired_date"=> $cycle_expired_date,
                "paypal_next_check_time"=> $cycle_expired_date,
                "package_id"        => $package_id,
                "response_source"   => json_encode($response),
                "package_name"      => $get_payment_validity_data['package_name'] ?? '',
                "user_email"        => $get_payment_validity_data['email'] ?? '', // not for insert, for sending email
                "user_name"         => $get_payment_validity_data['name'] ?? '' // not for insert, for sending email
            );
            $this->complete_payment($insert_data,null,null,$payment_type);
        }
        DB::table('users')->whereIn('id',$paypal_processing_data)->update(['paypal_processing'=>'0']);
    }

}
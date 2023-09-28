<?php

namespace App\Http\Controllers\Heatmap;
use App\Http\Controllers\Home;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Dashboard extends Home
{
    public $table_names = [];
    
    public function __construct()
    {
        $this->set_global_userdata(true);
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
        $is_passed = $this->important_feature($redirect=false);
        if(!$is_passed) return redirect()->route('credential-check');
        
        $this->set_table_names();
        $domain_list_id = session('active_domain_id_session');
        $time = date("Y-m-d H:i:s");
        $live_user_time = date("Y-m-d H:i:s", strtotime($time. " - 30 sec"));
        $last_30_days = date("Y-m-d H:i:s", strtotime($time. " - 30 days"));
        $total_duration = 0;
        $last_30_days_sessions = [];
        $top_pages = [];
        $referrer_lists = $country_lists = [];
        $all_sessions=array();

        $get_current_domain_info = DB::table("visitor_analysis_domain_list")
                                    ->where(['id'=>$domain_list_id,'user_id'=>$this->user_id,'deleted'=>'0'])
                                    ->first();

        $domain_heatmap_data = DB::table($this->table_names['heatmap_table'])
            ->select(['device', 'visit_url', 'page_title', 'country', 'click_move_scroll', 'entry_time', 'last_engagement_time', 'total_stay_time', 'session_value', 'cookie_value', 'referrer', 'is_new'])
            ->where(array("user_id" => $this->user_id, "domain_list_id" => $domain_list_id))
            ->where('last_engagement_time','>=',$last_30_days)
            ->get();

        foreach($domain_heatmap_data as $heatmap)
        {
            if($heatmap->click_move_scroll == 'click')
            {

                if(!isset($all_sessions[$heatmap->session_value]))
                {
                    $all_sessions[$heatmap->session_value]=1;
                    $only_date = date("Y-m-d", strtotime($heatmap->last_engagement_time));
                    $last_30_days_sessions[$only_date]['no_of_session'] = isset($last_30_days_sessions[$only_date]['no_of_session']) ? $last_30_days_sessions[$only_date]['no_of_session']+1 : 1;
                    $last_30_days_sessions[$only_date]['date'] = $only_date;

                    if(isset($heatmap->country) && !empty($heatmap->country)) 
                    {
                        $country_lists[$heatmap->country] = isset($country_lists[$heatmap->country]) ? $country_lists[$heatmap->country]+1 : 1;
                    }
                }

                $total_duration = $total_duration+$heatmap->total_stay_time;
                $top_pages[$heatmap->visit_url]['no_of_session'] = isset($top_pages[$heatmap->visit_url]['no_of_session']) ? $top_pages[$heatmap->visit_url]['no_of_session']+1 : 1;
                $top_pages[$heatmap->visit_url]['page_title'] = $heatmap->page_title;
                $top_pages[$heatmap->visit_url]['url'] = $heatmap->visit_url;

                if(isset($heatmap->referrer) && !empty($heatmap->referrer)) 
                {
                    $referrer_lists[$heatmap->referrer] = isset($referrer_lists[$heatmap->referrer]) ? $referrer_lists[$heatmap->referrer]+1 : 1;
                }

            }
        }


        $traffic_data_result = $this->get_traffic_chart_data($last_30_days_sessions,$domain_heatmap_data);

        $data['traffic_data'] = $traffic_data_result['session'] ?? [];
        $data['stepSize'] = $traffic_data_result['stepSize'] ?? 1;

        usort($top_pages, fn($a, $b) => $b['no_of_session'] <=> $a['no_of_session']);

        arsort($referrer_lists);
        arsort($country_lists);
        $referrer_lists = array_slice($referrer_lists, 0, 5, true);
        $country_lists = array_slice($country_lists, 0, 12, true);
        $top_pages = array_slice($top_pages, 0, 5, true);

        $data['load_datatable'] = true;
        $data['body'] = 'heatmap/dashboard';
        $data['top_pages'] = $top_pages;
        $data['referrer_lists'] = $referrer_lists;
        $data['country_lists'] = $country_lists;
        $data['last_30_days_sessions'] = $last_30_days_sessions;
        $data['domain_info'] = $get_current_domain_info;
        return $this->viewcontroller($data);
    }


    protected function get_traffic_chart_data($last_30_days_sessions=[],$domain_heatmap_data=[])
    {
        $the_day = '';
        for($i = 29; $i >= 0; $i--) {

            $the_day = date("Y-m-d", strtotime('today - ' .$i .' days'));

            if(!array_key_exists($the_day, $last_30_days_sessions)) {
                $last_30_days_sessions[$the_day] = [
                    'no_of_session' => 0,
                    'date' => $the_day
                ];
            }

            $sessions[date("j M", strtotime($the_day))] = $last_30_days_sessions[$the_day]['no_of_session'];
        }

        $large_val = array();
        $max_values = 1;
        if(!empty($last_30_days_sessions)) array_push($large_val, max($sessions));
        if(!empty($large_val)) $max_values = max($large_val);
        if($max_values > 100) $stepSize = floor($max_values/100);
        else $stepSize = 1;

        return [
            'session' => $sessions,
            'stepSize' => $stepSize,
        ];
    }

    protected function demo_data()
    {
        $rand = 1000;

        return  array( 
            date('j M',strtotime('2021-11-15')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-16')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-17')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-18')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-19')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-20')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-21')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-22')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-23')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-24')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-25')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-26')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-27')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-28')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-29')) => rand(0,$rand), 
            date('j M',strtotime('2021-11-30')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-01')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-02')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-03')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-04')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-05')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-06')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-07')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-08')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-09')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-10')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-11')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-12')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-13')) => rand(0,$rand), 
            date('j M',strtotime('2021-12-14')) => rand(0,$rand),
        );
    }

    protected function get_dashboard_data(Request $request)
    {
        $this->set_table_names();
        $domain_list_id = session('active_domain_id_session');
        $time = date("Y-m-d H:i:s");
        $live_user_time = date("Y-m-d H:i:s", strtotime($time. " - 30 sec"));
        $last_30_days = date("Y-m-d H:i:s", strtotime($time. " - 30 days"));
        $total_live_user = 0;
        $total_mobile_user = 0;
        $total_pc_user = 0;
        $url_wise_live = [];
        $all_sessions = [];
        $total_duration = 0;
        $last_30_days_sessions = [];
        $total_new_user = 0;
        $total_returning_user = 0;
        $total_clicks = 0;
        $total_page_view = 0;
        $average_stay_time = 0;
        $hours = 00;
        $minutes = 00;
        $seconds = 00;

        $domain_heatmap_data = DB::table($this->table_names['heatmap_table'])
            ->select(['device', 'visit_url', 'page_title', 'country', 'click_move_scroll', 'entry_time', 'last_engagement_time', 'total_stay_time', 'session_value', 'cookie_value', 'referrer', 'is_new', 'device', 'total_clicks'])
            ->where(array("user_id" => $this->user_id, "domain_list_id" => $domain_list_id))
            ->where('last_engagement_time','>=',$last_30_days)
            ->get();

        foreach($domain_heatmap_data as $heatmap)
        {
            if($heatmap->click_move_scroll == 'click' && (strtotime($heatmap->last_engagement_time) >= strtotime($live_user_time)))
            {
                $url_wise_live[$heatmap->visit_url]['session'] = isset($url_wise_live[$heatmap->visit_url]['session']) ? $url_wise_live[$heatmap->visit_url]['session']+1 : 1;
                $url_wise_live[$heatmap->visit_url]['page_title'] = isset($heatmap->page_title) ? $heatmap->page_title : "";
                $url_wise_live[$heatmap->visit_url]['url'] = isset($heatmap->visit_url) ? $heatmap->visit_url : "";
            }

            if($heatmap->click_move_scroll == 'click')
            {
                $all_sessions[$heatmap->session_value] = $all_sessions[$heatmap->session_value] ?? 1;
                $total_duration = $total_duration+$heatmap->total_stay_time;
                if($heatmap->is_new == '1') $total_new_user++;
                else $total_returning_user++;

                $total_clicks = $total_clicks+$heatmap->total_clicks;
                $total_page_view++;
            }

        }


        $total_session = count($all_sessions);
        $average_stay_time = $total_session > 0  ? round($total_duration / $total_session, 2): 0;

        if($average_stay_time != 0) {

            $average_stay_time = $average_stay_time;
            $hours = floor($average_stay_time / 3600);
            $minutes = floor(($average_stay_time / 60) % 60);
            $seconds = $average_stay_time % 60;  

        }
        $average_stay_time = $hours.'h '.$minutes.'m '.$seconds.'s';

        usort($url_wise_live, fn($a, $b) => $b['session'] <=> $a['session']);

        $response['url_wise_live'] = $url_wise_live;
        $response['total_session'] = $total_session;
        $response['average_stay_time'] = $average_stay_time;

        $response['total_new_user'] = $total_new_user;
        $response['total_returning_user'] = $total_returning_user;
        $response['total_clicks'] = $total_clicks;
        $response['total_page_view'] = $total_page_view;


        $select_session_col = ['id','country','total_stay_time','device','ip','os','browser_name','referrer','entry_time','last_engagement_time','session_value'];
        $info = DB::table($this->table_names['sessions_table'])
                    ->select($select_session_col)
                    ->where(['user_id'=>$this->user_id,'domain_list_id'=>session('active_domain_id_session')])
                    ->where("last_engagement_time",">=",$live_user_time)
                    ->orderByDesc("id")
                    ->groupBy(['session_value','cookie_value'])
                    ->get();

        $session_str = $this->get_session_info($info);

        $response['session_info'] = $session_str['session_str'];
        $response['total_live_user'] = $session_str['total_live_user'];
        $response['total_mobile_user'] = $session_str['total_mobile_user'];
        $response['total_pc_user'] = $session_str['total_pc_user'];


        echo json_encode($response);
    }

    public function get_session_info($info=[])
    {
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

        $session_str = '<ul class="list-unstyled user-details list-unstyled-border list-unstyled-noborder">';
        $total_live_user = 0;
        $total_mobile_user = 0;
        $total_pc_user = 0;

        if($info->isNotEmpty()) {
            foreach($info as $key => $value) {

                $country = $value->country ?? '';
                $s_country =get_country_iso_phone_currency_list();

                if(!empty($country)){
                     $country_name = isset($s_country[$country]) ? $s_country[$country]:$country;
                     $image_link = file_exists(base_path("assets/images/flags/".$country.".png")) 
                                    ? "assets/images/flags/".$country.".png"
                                    : "assets/images/flags/other.png";
                }
                else {

                    $country_name = __('Not Found');
                    $image_link = "assets/images/flags/other.png";
                }

                $total_stay_time = $value->total_stay_time;

                if($total_stay_time != 0) {
                    $hours = floor($total_stay_time / 3600);
                    $minutes = floor(($total_stay_time / 60) % 60);
                    $seconds = $total_stay_time % 60;  
                }

                $total_live_user++;
                if($value->device == 'Desktop') $total_pc_user++;
                else if($value->device == 'Mobile') $total_mobile_user++;
                
                $total_stay_time = $hours.':'.$minutes.':'.$seconds;

                $os = $value->os ?? '';
                $os = strtolower($os);
                $os_img_path = isset($os_list[$os]) ? $os_list[$os] : "assets/images/os/other.png";
                $os_image = '<img data-bs-toggle="tooltip" title="'.$value->os.'" style="height: 15px; width: 15px; margin-top: -3px;" src="'.asset($os_img_path).'" alt=" ">';

                $device_img = isset($os_list[strtolower($value->device)]) ? $os_list[strtolower($value->device)]: $value->device;
                $device = '<img data-bs-toggle="tooltip" title="'.$value->device.'" style="height: 15px; width: 15px; margin-top: -3px;" src="'.asset($device_img).'" alt="'.__('Device').'">';
                $browser_name = strtolower($value->browser_name);
                $browser_img_path = isset($browser_list[$browser_name]) ? $browser_list[$browser_name] : "assets/images/browser/other.png";
                $browser_image = '<img data-bs-toggle="tooltip" title="'.$value->browser_name.'" style="height: 15px; width: 15px; margin-top: -3px;" src="'.asset($browser_img_path).'" alt=" ">';
                $entry_time = convert_datetime_to_timezone($value->entry_time,'','','j M, h:i A');
                $last_engagement_time = convert_datetime_to_timezone($value->last_engagement_time,'','','j M, h:i A');
                $referrer = !empty($value->referrer) ? $value->referrer: __('Not available');
                $ip = $value->ip;
                $devices = '<div>'.$browser_image.'&nbsp;'.$os_image.'&nbsp;'.$device.'</div>';
                $visit_url_id = $value->id;

                $actions = '<div class="mt-1"><a href="#" data-id= "'.$visit_url_id.'" session_value="'.$value->session_value.'" title="'.__("Play").'" class="btn btn-circle btn-outline-primary play_record"><i class="fas fa-play-circle"></i></a></div>';


                $session_str .= '
                        <li class="media">
                            <img alt="image" class="me-2" width="40" src='.asset($image_link).'>
                            <div class="media-body" style="width:100px">
                                <div class="media-title">'.$country_name.'</div>
                                <div class="text-job text-muted">'.$devices.'</div>
                            </div>
                            <div class="media-items">
                                <div class="media-item px-3 order-1 order-sm-1" style="width:100px">
                                    <div class="media-value mb-1">'.$total_stay_time.'</div>
                                    <div class="media-label">'.__('Duration').'</div>
                                </div>
                                <div class="media-item px-3 order-3 order-sm-2" style="width:120px">
                                    <div class="media-value">'.$ip.'</div>
                                    <div class="media-label">'.__("IP").'</div>
                                </div>
                                <div class="media-item px-3 order-2 order-sm-3" style="width:65px">
                                    <div class="media-value">'.$actions.'</div>
                                </div>
                                <div class="media-item px-3 order-4 order-sm-4">
                                    <div class="media-value">'.$referrer.'</div>
                                    <div class="media-label">'.__("Referrer").'</div>
                                </div>
                            </div>
                        </li>';
            }
        } else {
            $session_str .= '<li class="border-bottom-0 justify-content-center fw-bold">'.__('No data found').'</li>';
        }

        $session_str .= '</ul>';

        $response['session_str'] = $session_str;
        $response['total_live_user'] = $total_live_user;
        $response['total_mobile_user'] = $total_mobile_user;
        $response['total_pc_user'] = $total_pc_user;

        return $response;
    }
}

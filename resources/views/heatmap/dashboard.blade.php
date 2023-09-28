@extends('layouts.auth')
@section('title',__('Domain'))

@push('styles-header')
    <link rel="stylesheet" href="{{ asset('assets/vendors/chartjs/Chart.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/rrweb/dist/rrweb.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/rrweb-player/dist/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/heatmap/css/dashboard.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

@endpush

@section('content')
<?php 
    $referrer_lists_colors = ['#9BBFE0','#E8A09A','#FBE29F','#C6D68F','#47B39C'];
    $progress_color_lists = ['#0d6efd','#E8A09A','#FBE29F','#C6D68F','#47B39C'];
    $pause_active = $play_active = $domain_prefix = '';
    if(isset($domain_info)) $domain_prefix = $domain_info->domain_prefix;
    if(isset($domain_info) && $domain_info->pause_play=="play") $play_active = 'active';
    else if(isset($domain_info) && $domain_info->pause_play=="pause") $pause_active = 'active';

?>
<div class="main-content container-fluid">
     @auth
        @if (!auth()->user()->email_verified_at && !$is_manager)
            <div class="alert alert-light-warning alert-dismissible fade show p-4 border-warning border-dashed"  role="alert">
                <h5 class="alert-heading text-dark">
                    <i class="far fa-envelope-open fs-1 float-start mt-1 me-3"></i>
                    {{__('Verify Email')}} : <small>{{__('Email is not verified yet. Please verify your email.')}}</small>
                </h5>
                <p class="">{{ __('Click the link to get started') }} : <a href="{{ route('verification.notice') }}" class="text-success fw-bold">{{ __('Start Email Verification') }}</a></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    @endauth
    <div class="row">
        <div class="col-12 col-lg-5 order-sm-first order-last">
            <div class="card card-icon-bg-md box-shadow">
                <div class="card-header bg-light-info p-0 domain-screenshot">
                    <img src="{{  $domain_info->screenshot ?? asset('assets/images/example-image.jpg') }}" class="border-bottom" alt="" width="100%">
                </div>
                <div class="card-header bg-light-purple p-0 domain-screenshot text-center">
                    <h4 class="card-title text-primary px-4 py-3 m-0" id="domain_name">{!! isset($domain_info->domain_name) ?  __('Summary').': <a class="text-dark fw-normal" href="https://'.$domain_info->domain_name.'" target="_BLANK">'.$domain_info->domain_name.'</a>'   :__("No Domain") !!}
                    </h4>
                </div>

                <div class="card-body py-4">
                    <div class="row mb-3">
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-2">
                                    <div class="symbol-label bg-light-success">
                                        <i class="fas fa-clock text-success"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-6 text-dark fw-bold"><span id="avg_stay_time">0</span></div>
                                    <div class="fs-6 text-muted">{{__('Stay Time')}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-2">
                                    <div class="symbol-label bg-light-danger">
                                        <i class="fas fa-user-clock text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-6 text-dark fw-bold"><span id="total_sessions">0</span></div>
                                    <div class="fs-6 text-muted">{{__('Session')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-2">
                                    <div class="symbol-label bg-light-primary">
                                        <i class="fas fa-user-circle text-info"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-6 text-dark fw-bold"><span id="unique_user">0</span></div>
                                    <div class="fs-6 text-muted">{{__('Unique Visitor')}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-50px me-2">
                                    <div class="symbol-label bg-light-warning">
                                        <i class="fas fa-user-check text-warning"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-6 text-dark fw-bold"><span id="returinig_user">0</span></div>
                                    <div class="fs-6 text-muted">{{__('Returning Visitor')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="d-flex align-items-center me-2">
                                <div class="symbol symbol-50px me-2">
                                    <div class="symbol-label bg-light-info">
                                        <i class="fas fa-mouse text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-6 text-dark fw-bold"><span id="total_clicks">0</span></div>
                                    <div class="fs-6 text-muted">{{__('Click')}}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center me-2">
                                <div class="symbol symbol-50px me-2">
                                    <div class="symbol-label bg-light">
                                        <i class="fas fa-eye text-success"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="fs-6 text-dark fw-bold"><span id="total_page_view">0</span></div>
                                    <div class="fs-6 text-muted">{{__('Page View')}}</div>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
                <div class="card-footer px-0 pt-0 pb-0">
                    <canvas id="domain_summary" height="50px"></canvas>
                </div>
                <div class="card-footer bg-light-purple py-0 pt-0 pb-2">
                    @if($domain_info)
                    <div class="event_buttons text-center pt-3 pb-2">
                        
                        <span class="text-sm pt-1" id="domain_status">
                            @if(isset($domain_info->pause_play) && $domain_info->pause_play=="play")
                                <i class="fas fa-record-vinyl text-success"></i> {{ __('Recording') }}
                            @elseif((isset($domain_info->pause_play) && $domain_info->pause_play=="pause"))
                                <i class="fas fa-stop text-danger"></i> {{ __('Stopped') }}
                            @else
                                {{ __("Status") }}
                            @endif
                        </span>                     

                        <span 
                            data-bs-toggle="tooltip" 
                            title="{{__('Play Recording')}}" 
                            class="play_recording event_buttons_item mx-2 btn-sm rounded pointer {{$play_active}}"
                             domain_id="{{ session('active_domain_id_session') }}">
                            {{ __("Start") }}
                        </span>
                        <span 
                            data-bs-toggle="tooltip" 
                            title="{{__('Stop Recording')}}" 
                            class="pause_recording event_buttons_item mx-2 btn-sm rounded pointer {{$pause_active}}" 
                            domain_id="{{ session('active_domain_id_session') }}">
                            {{ __("Stop") }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-md-7 order-2 order-sm-last order-first">
            <div class="card mb-4">
                <div class="card no-shadow card-icon-bg mb-0">
                    <div class="card-body bg-light-primary" id="card-body_id">
                        <div class="card-icon-container">
                            <i class="fas fa-users text-success"></i>
                        </div>                          
                        <h4>
                            <a href="#" class="card-title fw-bold fs-5 text-success"><i class="fas fa-trophy"></i> {{ __("Live Sessions") }}</a>
                        </h4>                           
                        <div class="row mt-2">
                            <div class="col-12 col-md-4 mt-2">
                                <div class="card card-icon-bg-md box-shadow pb-0 m-0" id="card-icon-bg_id">
                                    <div class="card-body bg-white ps-4 pe-2" id="card-bod_id">
                                        <div class="row">
                                            <div class="col">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px me-3">
                                                        <div class="symbol-label bg-success">
                                                            <i class="fas fa-circle text-white fs-3" id="fas_fa-circle_text-white"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fs-4 text-dark fw-bold"><span class="total_live_users">0</span></div>
                                                        <div class="fw-bold text-muted">{{ __("Live User") }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mt-2 d-none d-sm-block">
                                <div class="card card-icon-bg-md box-shadow pb-0 m-0" id="card-card-icon-bg-md_box-shado">
                                    <div class="card-body bg-white ps-4 pe-2" id="card-body-bg-white-ps-4-p">
                                        <div class="row">
                                            <div class="col">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px me-3">
                                                        <div class="symbol-label bg-primary">
                                                            <i class="fas fa-mobile text-white fs-3" id="fas-fa-mobile-text-white-f" ></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fs-4 text-dark fw-bold"><span class="total_mobile_user">0</span></div>
                                                        <div class="fw-bold text-muted">{{ __("Mobile User") }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-4 mt-2  d-none d-sm-block">
                                <div class="card card-icon-bg-md box-shadow pb-0 m-0" id="card-card-icon-bg-md-b">
                                    <div class="card-body bg-white ps-4 pe-2" id="card-body_bg-white_ps-4_pe">
                                        <div class="row">
                                            <div class="col">
                                                <div class="d-flex align-items-center">
                                                    <div class="symbol symbol-50px me-3">
                                                        <div class="symbol-label bg-warning">
                                                            <i class="fas fa-laptop text-white fs-3" id="fas-fa-laptop-te"></i>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <div class="fs-4 text-dark fw-bold"><span class="total_pc_user">0</span></div>
                                                        <div class="fw-bold text-muted">{{ __("PC User") }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>  

                    </div>
                    <div class="card-body pt-4 nicescroll" id="session_info">
                        <ul class="list-unstyled user-details list-unstyled-border list-unstyled-noborder">
                            <li class="border-bottom-0 justify-content-center fw-bold">{{ __('No data found') }}</li>
                        </ul>
                    </div>

                </div>
                
            </div>

            
        </div>
    </div>


    <div class="row second-row">
        <div class="col-12 col-md-7">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-link"></i> {{ __("Active Pages") }}</h4>
                </div>
                <div class="card-body nicescroll w-100" id="liveUsersLists" >
                    <ul class="list-group"></ul>
                    <div id="error" class="custom_error" >
                        <div class="container text-center pt-5">
                            <h1 class='error-title text-secondary fw-normal mb-0'><i class="far fa-frown"></i></h1>
                            <p class="text-muted error-text">{{ __("Currently there is no user on this domain") }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-header"><h4><i class="fas fa-globe-asia"></i> {{ __("Top Countries") }}</h4></div>
                <div class="card-body" id="card-body-idd_id">
                    <div class="row">
                        @foreach($country_lists as $country_key => $country)
                            <?php
                                if(file_exists(base_path('assets/images/flags/'.$country_key.'.png'))) {
                                    $imageSrc = asset('assets/images/flags/'.$country_key.'.png');
                                } else {
                                    $imageSrc = asset('assets/images/flags/other.png');
                                }
                            ?>
                            <div class="col-6 col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <img class="me-2" data-bs-toggle="tooltip" src="{{ $imageSrc }}" alt="" width="55" height="50" data-bs-original-title="" title="">
                                    <div>                                                       
                                        <div class="fs-6 text-dark fw-bold">
                                            <b data-bs-toggle="tooltip" title="{{__('No of sessions')}}" class="">{{$country}}</b>
                                        </div>
                                        <div class="fs-6 text-muted">
                                            {{ 
                                                isset(get_country_iso_phone_currency_list()[$country_key]) 
                                                ? get_country_iso_phone_currency_list()[$country_key]
                                                :$country_key 
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </div>



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5><i class="fas fa-users"></i> {{ __("Last 30 days Traffic Overview") }}</h5></div>              
                <div class="card-body">
                    <canvas id="domain_traffic_overview" height="90"></canvas>                          
                </div>
            </div>
        </div>
    </div>

    <div class="row fourth-row">
        <div class="col-12 col-md-8">
            <div class="card">
                <div class="card-header"><h4 class="mb-0"><i class="fas fa-hand-pointer"></i> {{ __("Top 5 Referrer") }}</h4></div>
                <div class="card-content">
                    <div class="card-body mt-2">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <canvas id="referrer_lists_pie_chart" height="120"></canvas>
                            </div>
                            <div class="col-12 col-md-6">
                                    <ul class="list-group">
                                        @php($i=0)
                                        @foreach($referrer_lists as $key => $value)
                                        <li class="list-group-item border-0 text-sm">
                                            <i class="fas fa-circle" style="color: {{ $referrer_lists_colors[$i] }}"></i> {{ $key }}
                                            <label class="badge rounded float-end" style="background: {{ $referrer_lists_colors[$i] }}">{{ $value }}</label>
                                        </li>
                                        @php($i++)
                                        @endforeach
                                    </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-md-4">
            <div class="card mb-0">
                <div class="card-header py-3"><h4 class="mb-0 text-dark"><i class="fas fa-newspaper"></i> {{ __("Top 5 Pages") }}</h4></div>
                <div class="card-content">
                    <div class="card-body" id="card-body_id-id">
                        @if(count($top_pages) > 0)

                            <?php $maxNo = max($top_pages)['no_of_session']; ?>

                            @php($i=0)
                            @foreach($top_pages as $value)
                                <?php 
                                    $width = round(($value['no_of_session'] / $maxNo)  * 90) ; 
                                    $title = strlen($value['page_title']) > 40 ? mb_substr($value['page_title'], 0 , 37).'...':$value['page_title'];
                                ?>
                            <div class="d-inline">
                                <a data-bs-toggle="tooltip" title="{{$value['page_title']}}" target="_blank" href="{{ $value['url'] }}" class="text-sm text-dark"><i class="far fa-circle" style="color: {{ $referrer_lists_colors[$i] }}"></i> {{ $title }}</a>
                            </div>
                            <div class="progress mb-3" id="progress_mb-">   
                                <div class="progress-bar text-dark fw-bolder" role="progressbar" style="font-size: 10px;background-color: {{ $referrer_lists_colors[$i] }};width: {{$width}}%" aria-valuenow="{{$width}}" aria-valuemin="0" aria-valuemax="100">{{$value['no_of_session']}}</div>
                            </div>
                            @php($i++)
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="play_session_video_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">{{ __("Session Video") }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center my-4 loaderDiv"></div>
                
                <div id='video_session_information'>
                    <div class="row mx-5 ">
                        <div class="col-md-8 offset-md-2 text-center" id="info_html">
                            
                        </div>
                    </div>
                </div>

                <div id="videoSection" class="mt-4 d-flex justify-content-center"></div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __("Close") }}</button>
            </div>
        </div>
    </div>
</div>

@endsection

<?php
$domain_traffic_chart_labels = array_keys($traffic_data);
$domain_traffic_chart_values = array_values($traffic_data);

$domain_traffic_chart_labels_chunk = array_chunk(array_reverse($domain_traffic_chart_labels),7)[0];
$domain_summary_chart_labels =  array_reverse($domain_traffic_chart_labels_chunk);
$domain_traffic_chart_values_chunk = array_chunk(array_reverse($domain_traffic_chart_values),7)[0];
$domain_summary_chart_values =  array_reverse($domain_traffic_chart_values_chunk);
?>

@push('scripts-footer')

    <script>
        "user strict";

        var domain_traffic_chart_labels = <?php echo json_encode($domain_traffic_chart_labels) ?>;
        var domain_traffic_chart_values = <?php echo json_encode($domain_traffic_chart_values) ?>;
        var domain_summary_chart_labels = <?php echo json_encode($domain_summary_chart_labels) ?>;
        var domain_summary_chart_values = <?php echo json_encode($domain_summary_chart_values) ?>;
        var domain_refferer_lists_labels = <?php echo json_encode(array_keys($referrer_lists)); ?>;
        var domain_refferer_lists_values = <?php echo json_encode(array_values($referrer_lists)); ?>;
        var domain_traffic_chart_step_size = <?php echo $stepSize ?>;
        var domain_prefix = '<?php echo $domain_prefix ?>';
        var numberof_session = '{{ __("No of Session") }}';
    </script>

    <script src="{{asset ('assets/node_modules/rrweb-player/dist/index.js') }}"></script>
    <script src="{{ asset('assets/vendors/chartjs/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/heatmap/js/dashboard.js') }}"></script>
@endpush


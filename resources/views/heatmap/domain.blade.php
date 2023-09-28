@extends('layouts.auth')
@section('title', __('Domain Heatmap'))
@section('top_header', __('Domain Heatmaps'))

@push('styles-header')
    <link rel="stylesheet" href="{{ asset('assets/vendors/emoji/dist/emojionearea.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/heatmap/css/domain_analytics.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

@endpush

@section('content')
<div id="my-element" data-my-attribute="{{ $screenshot_data }}"></div>
<div class="main-content container-fluid pt-3">
    <section id="basic-horizontal-layouts">
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12 colrig" id="right_column">
                <div class="text-center waiting" id="ext-cent">
                    <i class="fas fa-spinner fa-spin blue text-center"></i>
                </div>

                <div class="row">
                    <div class="col-12 col-xl-3 col-sm-6 ps-xl-0">
                        <div class="card mb-3">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between">
                                    <div class="align-self-center">
                                        <i class="fas fa-users text-primary fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="font-weight-bold" id="total_unique_sessions">0</h5>
                                        <p class="mb-0 small">{{ __("Total Unique Sessions") }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-3 col-sm-6">
                        <div class="card mb-3">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between">
                                    <div class="align-self-center">
                                        <i class="far fa-clock text-warning fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="font-weight-bold" id="average_stay_time">0:0:0</h5>
                                        <p class="mb-0 small">{{ __("Average Stay Time") }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-3 col-sm-6">
                        <div class="card mb-3">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between">
                                    <div class="align-self-center">
                                        <i class="fas fa-chart-line text-danger fa-3x"></i>
                                    </div>
                                    <div class="text-end">
                                        <h5 class="font-weight-bold" id="total_clicks">0</h5>
                                        <p class="mb-0 small">{{ __("Total Clicks") }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-xl-3 col-sm-6 pe-xl-0 mb-3">
                            <div class="headerSelect2">
                                <h4 class="text-center urlList_header">{{ __("Other Pages") }}</h4>
                                <select class="form-select select2 text-center w-100" id="domain_pages_list" name="domain_pages_list">
                                    {!!$urls_for_domain!!}
                                </select>
                            </div>
                            
                        </div>
                    </div>
                </div>  
                @php
                    if(isset($device_type)) $device_type = $device_type;
                    else $device_type = 'desktop';
                @endphp
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-3 mb-3 mb-md-0">
                                <nav class="">
                                    <div class="nav nav-tabs device_group" id="nav-tab" role="tablist">
                                        <button class="nav-link device_type @if(isset($device_type) && $device_type == 'desktop') active @endif"  device_type="desktop"
                                            data-bs-toggle="tab"
                                            data-bs-target="#nav-desktop" type="button" role="tab"
                                            aria-controls="nav-desktop" aria-selected="true"><i
                                                data-feather="monitor" width="20"></i>
                                            <span>{{ __('PC') }}</span></button>
                                        <button class="nav-link device_type @if(isset($device_type) && $device_type == 'tablet') active @endif" device_type="tablet" id="nav-tabs-tab" data-bs-toggle="tab" data-bs-target="#nav-tabs" type="button" role="tab" aria-controls="nav-tabs" aria-selected="false"><i data-feather="tablet" width="20"></i> <span>{{ __('Tab') }}</span></button>
                                        <button class="nav-link device_type @if(isset($device_type) && $device_type == 'mobile') active @endif" device_type="mobile"
                                            id="nav-mobile-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-mobile" type="button" role="tab"
                                            aria-controls="nav-mobile" aria-selected="false"><i
                                                data-feather="phone" width="20"></i>
                                            <span>{{ __('Mobile') }}</span></button>
                                    </div>
                                </nav>
                            </div>
                            <div class="col-12 col-md-4 mb-3 mb-md-0">
                                <nav class="d-inline-block">
                                    <div class="nav nav-tabs event_group" id="nav-tab" role="tablist">
                                        <input type="hidden" name="from_date" value="{{ isset($from_date)?$from_date : '' }}">
                                        <input type="hidden" name="to_date" value="{{ isset($to_date)?$to_date : '' }}">
                                        <input type="hidden" name="search_country" value="{{ isset($search_country)?$search_country : '' }}">
                                        <input type="hidden" name="device_type" class="device-type-field">
                                        <button class="nav-link which_event @if($event_type == 'click') active @endif" name="event_type" value="click" 
                                            id="nav-click-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-click" type="submit" role="tab"
                                            aria-controls="nav-click" aria-selected="true"><i
                                                data-feather="mouse-pointer" width="20"></i>
                                            <span>{{ __('Click') }}</span></button>
                                        <button class="nav-link which_event @if($event_type == 'move') active @endif" name="event_type" value= "move" id="nav-move-tab" data-bs-toggle="tab" data-bs-target="#nav-move" type="submit" role="tab" aria-controls="nav-move" aria-selected="false"><i data-feather="move" width="20"></i> <span>{{ __('Move') }}</span></button>
                                        <button class="nav-link which_event @if($event_type == 'scroll') active @endif" name="event_type" value="scroll" 
                                            id="nav-scroll-tab" data-bs-toggle="tab"
                                            data-bs-target="#nav-scroll" type="submit" role="tab"
                                            aria-controls="nav-scroll" aria-selected="false"><i
                                                data-feather="activity" width="20"></i>
                                            <span>{{ __('Scroll') }}</span></button>
                                        
                                    </div>
                                </nav>
                                <div class="d-inline-block ms-4" id="retake_screenshot_parent" data-bs-toggle="tooltip" title="{{ __('Retake Screenshot') }}"><a class="btn btn-danger" href="{{ $retake_screenshot_url }}" target="_BLANK" id="retake_screenshot"><i class="fas fa-camera"></i></a></div>
                            </div>

                            <div class="col-12 col-md-5">
                                <form action="{{ route('domain-analytics') }}">
                                    @csrf
                                    <div class="input-group input-group-sm filter_group" id="searchbox">
                                        <input type="hidden" id="event_type" name ="event_type" value="{{ isset($event_type)? $event_type : ''}}">
                                        <input type="hidden" name="device_type" class="device-type-field">
                                        <input type="text" class="form-control datepicker_x" name="from_date"
                                            name="from_date"  value="{{ isset($from_date)?$from_date : '' }}" placeholder="{{ __('From Date') }}">
                                        <input type="text" class="form-control datepicker_x" name="to_date"
                                            name="to_date" value="{{ isset($to_date)?$to_date : '' }}" placeholder="{{ __('To Date') }}">
                                        @php
                                            $country_names[''] = __('Country');
                                            $search_country = isset($search_country) ? $search_country : '';
                                        @endphp
                                        {{ Form::select('search_country', $country_names,$search_country , ['class' => 'form-control select2', 'name' => 'search_country']) }}
                                        <button type="submit" class="btn btn-primary" name="submit_filter"><i
                                                data-feather="filter" width="20"></i>
                                            <span>{{ __('Filter') }}</span></button>
                                    </div>
                               </form>
                            </div>

                            <div class="col-12 col-md-4 offset-md-3 mt-4 custom_tooltip" id="custom_tooltip_id" >
                                <input type="range" class="form-range" id="image_opacity" min="0" max="1" step="0.05" value="0.4">
                                <span class="tooltiptext">{{ __('Opacity') }} : 0.5</span>
                            </div>
                        </div>
                        <div class="tab-content tab-bordered" id="nav-tabContent">
                            <div id="waiting_spin" class="text-center pt-5"><img
                                    src="{{ asset('assets/images/loading-animations.gif') }}" alt=""
                                    width="300"></div>
                            <div id="error" class="cus_error mt-3">
                                <div class="container text-center pt-5">
                                    <h1 class='error-title text-secondary fw-normal pt-5 my-2'>{{ __("404") }}</h1>
                                    <p class="text-muted red" id="error_message">{{ __("we couldn't find any data you are looking for.") }}</p>
                                </div>
                            </div>
                            <div id="only_for_mobile">
                                <div class="mt-3" id="image_holder" >
                                    <img src="" alt="" id="heatmap_image" width="100%">
                                </div>
                               <div class="mt-3" id="scroll_image" >
                                    <canvas id="target"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section>
</div>

@endsection

@push('scripts-header')
    <script src="{{asset ('assets/heatmap/js/heat_sketch.js') }}"></script>
    <script src="{{ asset('assets/vendors/emoji/dist/emojionearea.min.js') }}"></script>
@endpush

@push('scripts-footer')
    <script src="{{ asset('assets/heatmap/js/domain.js') }}"></script>
@endpush
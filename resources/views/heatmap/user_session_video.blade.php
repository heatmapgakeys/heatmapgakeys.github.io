@extends('layouts.auth')
@section('title', __('Domain Heatmap'))
@section('top_header', __('Domain Session Recordings'))


@push('styles-header')
    <link rel="stylesheet" href="{{ asset('assets/vendors/emoji/dist/emojionearea.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/heatmap/css/domain_analytics.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/heatmap/css/card-style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/node_modules/rrweb/dist/rrweb.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/node_modules/rrweb-player/dist/style.css') }}">
@endpush

@section('content')
<div class="main-content container-fluid pt-3">
    <section class="section">

        <div class="card mb-3">
            <div class="card-body">
                <div class="input-group recording_filter_group" id="searchbox">
                    @php
                        $country_names[''] = __('Select country');
                        $browser_list[''] = __('Select Browser');
                        $os_list[''] = __('Select OS');
                        $device_list[''] = __('Select Device');
                    @endphp
                    {{ Form::select('search_country', $country_names, '', ['class' => 'form-control select2', 'id' => 'search_country']) }}
                    {{ Form::select('search_browser', $browser_list, '', ['class' => 'form-control select2', 'id' => 'search_browser']) }}
                    {{ Form::select('search_os', $os_list, '', ['class' => 'form-control select2', 'id' => 'search_os']) }}
                    {{ Form::select('search_device', $device_list, '', ['class' => 'form-control select2', 'id' => 'search_device']) }}

                    <input type="text" class="form-control datepicker_x" id="from_date"
                        name="from_date" placeholder="{{ __('From Date') }}">
                    <input type="text" class="form-control datepicker_x" id="to_date"
                        name="to_date" placeholder="{{ __('To Date') }}">
                    <button type="submit" class="btn btn-primary" id="filter_data">
                        <i data-feather="filter" width="20"></i> <span>{{ __('Filter') }}</span>
                    </button>

                </div>
            </div>
        </div>


        <div class="card">
            <div class="card-body data-card">
                <div class="table-responsive">
                    <table class='table table-hover table-bordered table-sm w-100' id="mytable">
                        <thead>
                        <tr class="table-light">
                            <th>#</th>
                            <th>{{__("Id") }}</th>
                            <th>{{__("Country") }}</th>
                            <th>{{__("Duration") }}</th>
                            <th>{{__("Device") }}</th>
                            <th>{{__("Entry Time") }}</th>
                            <th>{{__("Last Engaged") }}</th>
                            <th>{{__("IP") }}</th>
                            <th>{{__("Actions") }}</th>
                            <th>{{__("Referrer") }}</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>


<div class="modal fade" id="session_video_lists" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="staticBackdropLabel">{{ __("Visited URLs") }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center text-primary my-4 loaderDiv1"></div>
                <div class="row" id="listDiv">
                    <div class="col-12">
                        <div id="urlLists"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __("Close") }}</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="video_download" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="staticBackdropLabel">{{ __("Video Download") }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center my-4 loaderDiv2"></div>

                <div class="row" id="downloadDiv">
                    <div class="col-12">
                        <div id="videoDownloadLink" class="text-center"></div>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __("Close") }}</button>
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
                
                <div id='video_session_information' style="display: none;">
                    <div class="row mx-5">
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

@push('scripts-footer')
    <script src="{{asset ('assets/node_modules/rrweb-player/dist/index.js') }}"></script>
    <script src="{{ asset('assets/heatmap/js/user-session-video.js') }}"></script>
@endpush

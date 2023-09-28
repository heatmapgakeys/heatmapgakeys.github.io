<!DOCTYPE html>
<html lang="{{ get_current_lang() }}">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }} - @yield('title')</title>
        <link rel="shortcut icon" href="{{ config('app.favicon') }}" type="image/x-icon">

        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">

        @if($load_datatable)
            <link rel="stylesheet" href="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.css') }}">
            <link rel="stylesheet" href="{{ asset('assets/vendors/datatables/datatables.min.css') }}">
            <link rel="stylesheet" href="{{ asset('assets/vendors/datatables/DataTables-1.10.25/css/dataTables.bootstrap5.min.css') }}">
            <link rel="stylesheet" href="{{ asset('assets/vendors/datatables/ColReorder-1.5.4/css/colReorder.bootstrap5.min.css') }}">
            <link rel="stylesheet" href="{{ asset('assets/vendors/datatables/Buttons-1.7.1/css/buttons.bootstrap5.min.css') }}">
            <link rel="stylesheet" type="text/css" href="{{asset('assets/cdn/css/daterangepicker.css')}}" />
        @endif

        <link rel="stylesheet" href="{{ asset('assets/vendors/datetimepicker/jquery.datetimepicker.css') }}">
        <link rel="stylesheet" href="{{asset('assets/cdn/css/select2.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/cdn/css/sweetalert2.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/cdn/css/toastr.min.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/cdn/css/all.min.css')}}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/summernote/summernote-bs4.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/OwlCarousel/dist/owl.carousel.min.css') }}" />
        <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/OwlCarousel/dist/owl.theme.default.min.css') }}" />

        <link rel="stylesheet" href="{{ asset('assets/vendors/chocolat/css/chocolat.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendors/prism/prism.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

        <link rel="stylesheet" href="{{ asset('assets/css/pages/layouts/iframe.css') }}">
        <link rel="stylesheet" href="{{asset('assets/cdn/css/select2.css')}}">


        <script src="{{asset('assets/cdn/js/jquery-3.6.0.min.js')}}"></script>
        <script src="{{asset('assets/cdn/js/jquery-ui.js')}}"></script>

        <script src="{{ asset('assets/js/common/include_head.js') }}"></script>
        <script src="{{ asset('assets/js/pages/layouts/iframe.js') }}"></script>

        @stack('styles-header')
        @stack('scripts-header')

    </head>
    <body>

        <div class="text-center preloading_body">
          <i class="fas fa-spinner fa-spin text-primary text-center"></i>
        </div>
        <div id="theme_iframe_container">
            <div class="main-content container-fluid p-0">
                <div class="section">
                    @yield('content')
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/vendors/popper/popper.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/nicescroll/jquery.nicescroll.min.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>

        @if($load_datatable)
            <script src="{{ asset('assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
            <script src="{{ asset('assets/vendors/datatables/datatables.min.js') }}"></script>
            <script src="{{ asset('assets/vendors/datatables/DataTables-1.10.25/js/dataTables.bootstrap5.min.js') }}"></script>
            <script src="{{ asset('assets/vendors/datatables/ColReorder-1.5.4/js/colReorder.bootstrap5.min.js') }}"></script>
            <script src="{{ asset('assets/vendors/datatables/Buttons-1.7.1/js/dataTables.buttons.min.js') }}"></script>
            <script src="{{ asset('assets/vendors/datatables/Buttons-1.7.1/js/buttons.bootstrap5.min.js') }}"></script>
            <script src="{{ asset('assets/vendors/datatables/Buttons-1.7.1/js/buttons.html5.min.js') }}"></script>
            <script type="text/javascript" src="{{asset('assets/cdn/js/moment.js')}}"></script>
            <script type="text/javascript" src="{{asset('assets/cdn/js/daterangepicker.min.js')}}"></script>

        @endif

        <script src="{{ asset('assets/vendors/datetimepicker/build/jquery.datetimepicker.full.min.js') }}"></script>
        <script src="{{asset('assets/cdn/js/select2.min.js')}}"></script>
        <script src="{{asset('assets/cdn/js/sweetalert2.min.js')}}"></script>
        <script src="{{asset('assets/cdn/js/toastr.min.js')}}"></script>
        <script src="{{ asset('assets/vendors/chocolat/js/jquery.chocolat.min.js') }}"></script>
        <script src="{{ asset('assets/vendors/prism/prism.js') }}"></script>
        <script src="{{ asset('assets/vendors/summernote/summernote-bs4.js') }}"></script>
        <script src="{{ asset('assets/vendors/OwlCarousel/dist/owl.carousel.min.js') }}"></script>

        @include('shared.variables')

        @stack('scripts-footer')
        @stack('styles-footer')

        <script src="{{ asset('assets/js/common/common.js') }}"></script>
        <script src="{{ asset('assets/js/common/include.js') }}"></script>
        <script src="{{ asset('assets/js/heatmap/js/layouts/iframe.js') }}"></script>
    </body>
</html>

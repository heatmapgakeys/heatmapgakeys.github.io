<!DOCTYPE html>
<html lang="{{ get_current_lang() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ config('app.favicon') }}" type="image/x-icon">
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}"> -->
    <link href="{{asset('assets/cdn/css/bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/language.css') }}">
    <link rel="stylesheet" href="{{ asset('public/vendor/translation/css/main.css') }}">
    <link rel="stylesheet" href="{{asset('assets/cdn/css/all.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/cdn/css/sweetalert2.css')}}" />
    <script src="{{asset('assets/cdn/js/jquery-3.6.0.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('assets/cdn/css/select2.css')}}" />
</head>
<body>
    
    <div id="app">
        
        @include('translation::nav')
        @include('translation::notifications')
        
        @yield('body')
        
    </div>

    <script type="text/javascript">       
        "user strict";     
        var user_first_name = '';
        var user_last_name = '';
    </script>
    
    <script src="{{asset('assets/cdn/js/bootstrap.bundle.min.js')}}" ></script>
    <script src="{{ asset('public/vendor/translation/js/app.js') }}"></script>
    <script src="{{asset('assets/cdn/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/cdn/js/sweetalert2.min.js')}}"></script>
    <script>
        "user strict";
        var confirm_delete = "{{ __('Delete Language') }}";
        var after_deletion_confirm_text = "{{ __('Do you really want to delete this language? it will delete all files of this language.') }}";
        $(document).ready(function($){
            $('[data-bs-toggle=\"tooltip\"]').tooltip();
            $('.select2').select2();
            
        });
    </script>
    <script src="{{ asset('assets/js/pages/language.js') }}"></script>
</body>
</html>

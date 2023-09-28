<!DOCTYPE html>
<html>
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

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/OwlCarousel/dist/owl.carousel.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/OwlCarousel/dist/owl.theme.default.min.css') }}" />

    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/summernote/summernote-bs4.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendors/chocolat/css/chocolat.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/prism/prism.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/component.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <script src="{{asset('assets/cdn/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{ asset('assets/js/common/include_head.js') }}"></script>

    @stack('styles-header')
    @stack('scripts-header')

</head>
<body>
    @php
        $profile_pic  = !empty(Auth::user()->profile_pic) ? Auth::user()->profile_pic : asset('assets/images/avatar/avatar-1.png');
    @endphp

    <div id="app">
        <div id="sidebar" <?php if(!in_array($route_name,$full_width_page_routes)) echo "class='active'";?>>
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <a href="{{url('/')}}"><img src="{{ config('app.logo') }}" alt=""></a>
                </div>

                <div class="sidebar-menu" id="sidebar-menu">
                    <ul class="menu">
                        <li class='sidebar-title'>{{ __('Affiliate Menu') }}</li>
                        <li class="sidebar-item {{ $get_selected_sidebar == 'affiliate-dashboard' ? 'active' : '' }}">
                            <a href="{{ route('affiliate-dashboard') }}" class='sidebar-link'>
                                <i data-feather="home" width="20"></i>
                                <span>{{ __('Dashboard') }}</span>
                            </a>
                        </li>
                        <li class="sidebar-item {{ $get_selected_sidebar == 'affiliate-visitor-analytics' ? 'active' : '' }}">
                            <a href="" class='sidebar-link'>
                                <i data-feather="users" width="20"></i>
                                <span>{{ __('Visitor Analytics') }}</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ $get_selected_sidebar == 'affiliate-withdrawal-requests' ? 'active' : '' }}">
                            <a href="{{ route('affiliate-withdrawal-requests') }}" class='sidebar-link'>
                                <i data-feather="send" width="20"></i>
                                <span>{{ __('Withdrawal Requests') }}</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ $get_selected_sidebar == 'affiliate-withdrawal-methods' ? 'active' : '' }}">
                            <a href="{{ route('affiliate-withdrawal-methods') }}" class='sidebar-link'>
                                <i data-feather="grid" width="20"></i>
                                <span>{{ __('Withdrawal Methods') }}</span>
                            </a>
                        </li>
                        <li class='sidebar-title'>{{ __('Administration') }}</li>
                        <li class="sidebar-item {{ $get_selected_sidebar == 'affiliate-user-settings' ? 'active' : '' }}">
                            <a href="{{ route('affiliate-user-self-settings') }}" class='sidebar-link'>
                                <i data-feather="settings" width="20"></i>
                                <span>{{ __('Settings') }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <nav class="navbar navbar-header navbar-expand navbar-light">
                <a class="sidebar-toggler pointer"><span class="navbar-toggler-icon"></span></a>
                <button class="btn navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav d-flex align-items-center navbar-light ms-auto">

                        <li class="dropdown nav-icon me-4">
                            <a href="#" id="notification-dropdown" data-bs-toggle="dropdown"
                               class="nav-link  dropdown-toggle nav-link-lg nav-link-user">
                                <div class="d-lg-inline-block">
                                    <i data-feather="bell"></i><span class="badge bg-danger" id="notification-count">{{count($notifications)}}</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-large overflow-y h-max-500px"  id="notification-list">
                                <h6 class='py-2 px-4'>{{ __('Notifications') }}</h6>
                                <div>
                                    <ul class="list-group rounded-none">
                                        @foreach($notifications as $row)
                                            <?php
                                               $not_link = $row->linkable=='1' && $row->custom_link!='' ? $row->custom_link : '';
                                            ?>
                                            <div class="dropdown-divider"></div>
                                            <a href="{{ $not_link  }}" class="notification-mark-seen" data-id="{{$row->id}}">
                                            <li class="list-group-item border-0 align-items-start py-0">
                                                <div class="avatar {{$row->color_class}} me-3 align-items-center">
                                                    <span class="avatar-content"><i class="{{ $row->icon }}"></i></span>
                                                </div>
                                                <div>
                                                    <h6 class='text-bold mb-0'>{{ $row->title }}</h6>
                                                    <p class='text-xs mb-0'>
                                                       <?php echo $row->description;?>
                                                    </p>
                                                </div>
                                            </li>
                                            </a>
                                        @endforeach
                                    </ul>
                                </div>

                            </div>
                        </li>
                        <li class="dropdown">
                            <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user pe-0">
                                <div class="avatar me-1">
                                    <img src="{{$profile_pic}}" alt="" srcset="">
                                </div>
                                <div class="d-none d-md-block d-lg-inline-block">{{Auth::user()->name}}</div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('affiliate-account') }}"><i data-feather="user"></i> {{ __('Account') }}</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}"><i data-feather="log-out"></i> {{ __('Logout') }}</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            @yield('content')

            <footer class="">
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <span><?php echo date("Y")?> &copy; {{ config('app.name') }}</span>
                    </div>
                </div>
            </footer>
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
    <script src="{{ asset('assets/vendors/OwlCarousel/dist/owl.carousel.min.js') }}"></script>

    <script src="{{ asset('assets/vendors/chocolat/js/jquery.chocolat.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/prism/prism.js') }}"></script>
    <script src="{{ asset('assets/vendors/summernote/summernote-bs4.js') }}"></script>

    @include('shared.variables')

    @stack('scripts-footer')
    @stack('styles-footer')

    <script src="{{ asset('assets/js/common/common.js') }}"></script>
    <script src="{{ asset('assets/js/common/include.js') }}"></script>

</body>
</html>
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

        <?php
            $profilePicPath = base_path('storage/app/public/assets/profile/'.$user_id.'/profile_pic.png');
            $profile_pic = '';

            if (file_exists($profilePicPath)) {
                $profile_pic = 'data:image/png;base64,' . base64_encode(file_get_contents($profilePicPath));
            } else {
                $profile_pic = base_path('storage/app/public/assets/profile/profile_pic.png');
            }
        ?>
        {{-- <?php $profile_pic  = "data:image/png;base64,".base64_encode(file_get_contents(base_path('storage/app/public/assets/profile/'.$user_id.'/profile_pic.png'))) ?? base_path('storage/app/public/assets/profile/profile_pic.png') ; ?> --}}
        <?php
            $pricing_link = $parent_user_id==1 ? url('/').'/pricing' : route('pricing-plan');
        ?>
        <div id="app">
            <div id="sidebar" <?php if(!in_array($route_name,$full_width_page_routes)) echo "class='active'";?>>
                <div class="sidebar-wrapper active">
                    <div class="sidebar-header">
                        <a href="{{url('/')}}">
                            <img src="{{ config('app.logo') }}" alt="" class="large-logo">
                            <img src="{{ config('app.favicon') }}" alt="" class="small-logo">
                        </a>
                    </div>

                    <?php
                    $has_team_access = has_module_access($module_id_team_member,$user_module_ids,$is_admin);
                    $is_not_ticket_view = !$is_manager && request()->segment(1)!='tickets';
                    $stat_menu = $stat_menu1 =  $stat_menu2 = $stat_menu3 = [];
                    $admin_menus = [];
                    $payment_menu = [];

                    if(has_module_access($module_id_no_of_website,$user_module_ids,$is_admin)) {
                        $stat_menu1 = ['selected' => ['domain-list'], 'href' => route('domain-list'),'icon' => 'domains.png', 'title' => __('Domains')];
                    }
                    if(has_module_access($module_id_no_of_website,$user_module_ids,$is_admin)) {
                        $stat_menu2 = ['selected' => ['domain-analytics'], 'href' => route('domain-analytics'),'icon' => 'heatmap.png', 'title' => __('Heatmaps')];
                    }
                    if(has_module_access($module_id_recorded_sessions,$user_module_ids,$is_admin)) {
                        $stat_menu3 = ['selected' => ['user-session-video'], 'href' => route('user-session-video'),'icon' => 'recordings.png', 'title' => __('Recordings')];
                    }


                    if(!$is_manager){
                        if($is_admin || $has_team_access ){
                            $package_language_display = $has_team_access ? __('Package & Role') : __('Package');
                            $user_language_display = $has_team_access ? __('User & Team') : __('User');
                            $admin_menus =  [
                                0 => ['selected' => ['general-settings'], 'href' => route('general-settings'),'icon' => 'settings-5.png', 'title' => __('Settings')]
                            ];
                            $admin_menus[1] = ['selected' => ['list-user'], 'href' => route('list-user'),'icon' => 'settings-4.png', 'title' => $is_admin || $is_agent ? $user_language_display : __('Team')];
                                $admin_menus[2] = ['selected' => ['list-package'], 'href' => route('list-package'),'icon' => 'id-card-1.png', 'title' => $is_admin || $is_agent ? $package_language_display : __('Team Role')];
                            
                            $update_menus =  [
                                0 => ['selected' => ['update system'], 'href' => route('update-heatsketch-v2'),'icon' =>  'update-3.png', 'title' =>  __('Update')]
                            ];
                        }
                    }

                    if($is_member || $is_agent){
                        $payment_menu = [
                            'selected' => ['select-package'],
                            'href' => $pricing_link,
                            'icon' => Auth::user()->package_id==1 ? 'premium.png' : 'credit-card.png',
                            'title' => Auth::user()->package_id==1 ?__('Upgrade to Pro') : __('Renew / Upgrade')
                        ];
                    }

                    $sidebar_menu_items['main'] = [
                        'sidebar-title' =>  __('Main Menu'),
                        'sidebar-items' =>  [
                            0 => [
                                'selected' => ['dashboard'],
                                'href' => route('dashboard'),
                                'icon' => 'live_user.png',
                                'title' => __('Live Users')
                            ]
                        ],
                    ];
                    $sidebar_menu_items['stat_menu'] = [
                        'sidebar-title' =>  __('Stats'),
                        'sidebar-items' =>  [
                            $stat_menu1,
                            $stat_menu2,
                            $stat_menu3,
                        ],
                    ];

                    if(!empty($admin_menus)){
                        $sidebar_menu_items['admin'] = [
                            'sidebar-title' =>  $is_admin || $is_agent ? __('Administration') : __('Management'),
                            'sidebar-items' => $admin_menus
                        ];
                    }

                    if(!$is_manager){
                        $sidebar_menu_items['payment'] = [
                            'sidebar-title' => $is_admin ?  __('Payment') : __('Billing'),
                            'sidebar-items' =>  [
                                0 => $payment_menu,
                                1 => [
                                    'selected' => ['transaction-log'],
                                    'href' => route('transaction-log'),
                                    'icon' => $is_member ? 'refresh.png' : 'financial-profit.png',
                                    'title' =>  __('Transactions')
                                ]
                            ]
                        ];
                    }

                    if(!empty($update_menus)){
                        $sidebar_menu_items['update'] = [
                            'sidebar-title' => __('Update System'),
                            'sidebar-items' => $update_menus
                        ];
                    }
                    ?>

                    <div class="sidebar-menu" id="sidebar-menu">
                        <ul class="menu">
                            <div class="dropdown-divider m-0 pb-2"></div>

                            @foreach($sidebar_menu_items as $sec_key=>$section)
                                <?php if(empty($section)) continue; ?>
                                <li class='sidebar-title'><span>{!! $section['sidebar-title'] ?? '' !!}</span></li>
                                @foreach($section['sidebar-items'] as $menu_key=>$menu)
                                    <?php if(empty($menu)) continue; ?>
                                    <li class="sidebar-item {{ in_array($get_selected_sidebar,$menu['selected']) ? 'active' : '' }}">
                                        <a href="{{ $menu['href'] ?? '' }}" class='sidebar-link'>
                                            <?php $icon = isset($menu['icon']) ? asset('assets/images/flaticon/'.$menu['icon']) : '';?>
                                            <img src="{{$icon}}" data-bs-toggle="tooltip" data-bs-original-title="{{strip_tags($section['sidebar-title'].' : '.$menu['title'])}}" data-bs-placement="right"/>
                                            <span>{!! $menu['title'] ?? '' !!}</span>
                                        </a>
                                    </li>
                                @endforeach
                            @endforeach

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

                    <!-- heatmap header section -->
                    <div class="dropdown custom-css-dropdown">
                        <button class="btn dropdown-toggle" type="button" id="domain-dropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            @php
                            if(session('active_domain_name_session') != '') echo session('active_domain_name_session');
                            else echo __('Domains');
                            @endphp
                        </button>
                        <ul id="domain_list" class="dropdown-menu" aria-labelledby="domain-dropdown">

                            @foreach ($domains as $domain)
                            <li>
                                <a class="dropdown-item @if(session('active_domain_id_session')==$domain->id) {{'active'}} @endif " id="{{ $domain->id }}" domain_code="{{ $domain->domain_code }}" href="#">
                                    {{ $domain->domain_name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    @if(url()->current() == route('domain-analytics') || url()->current() == route('user-session-video'))
                    <div class="w-100 text-center lh-base mb-0 h5 text-secondary d-none d-sm-block">
                        @if(Request::segment(2)=='recordings')
                            <i class="far fa-play-circle text-info"></i>
                        @elseif(Request::segment(2)=='heatmaps')
                            <i class="fas fa-fire text-danger"></i>
                        @endif
                            @yield('top_header')
                        </h5>
                    </div>
                    @endif
                    <!-- heatmap header section end -->

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
                                    <a class="dropdown-item" href="{{ route('account') }}"><i data-feather="user"></i> {{ __('Account') }}</a>
                                    <div class="dropdown-divider"></div>
                                    @if(($is_member && in_array($parent_user_id,[1])) || $is_agent)
                                      <a class="dropdown-item" href="{{route('affiliate-program')}}"><i data-feather="user-plus"></i> {{ __('Affiliate Program') }}</a>
                                        <div class="dropdown-divider"></div> 
                                    @endif
                                    
                                     @if(has_module_access($module_id_affiliate_system,$user_module_ids,$is_admin,$is_manager) )
                                        <a class="dropdown-item" href="{{route('affiliate-settings')}}"><i data-feather="user-plus"></i> {{ __('Affiliate System') }}</a>
                                        <div class="dropdown-divider"></div>
                                    
                                     @endif 
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

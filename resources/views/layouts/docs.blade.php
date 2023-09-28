<!DOCTYPE html>
<html lang="{{ get_current_lang() }}">
  <head>
    <meta charset="UTF-8" />
    <meta
      content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no"
      name="viewport"
    />
    {!!url_make_canonical()!!}
    <title>{{ config('app.name') }} | {{__('Documentation')}} - @yield('title')</title>
    <link rel="shortcut icon" href="{{ config('app.favicon') }}" />

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{asset('assets/cdn/css/all.min.css')}}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendors/prism/prism.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendors/chocolat/css/chocolat.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/docs/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/docs/css/components.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/docs/css/custom.css') }}"/>
  </head>

  <body>
    <div id="app">
      <div class="main-wrapper">
        <nav class="navbar navbar-expand-lg main-navbar">
          <a href="#" data-toggle="sidebar" class="ms-3 d-md-none"><i class="fas fa-bars"></i></a>
          <a href="{{url('/')}}" class="navbar-brand text-center">
            <img src="{{ config('app.logo') }}" style="max-width: 150px" />
          </a>
          <div class="nav-collapse mr-lg-auto mr-0 ml-auto m-lg-0">
          </div>

        </nav>
        <div class="main-sidebar sidebar-style-2">
          <aside id="sidebar-wrapper">
            <div class="sidebar-brand sidebar-brand-sm">
              <a href="{{url('/')}}"><img src="{{ config('app.favicon') }}" /></a>
            </div>
            <ul>
              
              <li>
                <ul>
                  {{__('Telegram')}}
                  <li>
                    <a href="{{route('docs-connect-bot')}}">{{__('Connect Bot')}}</a>
                  </li>
                  <li><a href="{{route('docs-manage-bot')}}">{{__('Bot Manager')}}</a></li>
                  <li>
                    <a href="{{route('docs-manage-subscriber')}}">{{__('Subscriber Manager')}}</a>
                  </li>
                  <li>
                    <a href="{{route('docs-broadcasting')}}">{{__('Message Broadcast')}}</a>
                  </li>
                </ul>
              </li>

              @if(false)
              <li>
                <ul>
                    {{__('WhatsApp')}}
                    <li>
                        <a href="{{route('whatsapp-docs-connect-bot')}}">{{__('Connect Bot')}}</a>
                    </li>
                    <li><a href="{{route('whatsapp-docs-manage-bot')}}">{{__('Bot Manager')}}</a></li>
                    <li>
                        <a href="{{route('whatsapp-docs-manage-subscriber')}}">{{__('Subscriber Manager')}}</a>
                    </li>
                    <li>
                        <a href="{{route('whatsapp-docs-broadcasting')}}">{{__('Message Broadcast')}}</a>
                    </li>
                </ul>
              </li>

              @if($disable_ecommerce_feature=='0')
                <li>
                  <ul>
                    {{__('eCommerce')}}
                    <li>
                      <a href="{{route('docs-ecommerce')}}">{{__('eCommerce Store')}}</a>
                    </li>
                  </ul>
                </li>
              @endif

              <li>
                <ul>
                    {{__('Settings')}}
                    <li><a href="{{route('docs-integration')}}">{{__('Management')}}</a></li>
                </ul>
              </li>
              @endif

            </ul>


            <!-- Menu -->
          </aside>
        </div>

        <!-- Main Content -->
        @yield('content')
      </div>
    </div>

    <!-- General JS Scripts -->
    <script src="{{asset('assets/cdn/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/popper/popper.min.js') }}"></script>
    <script src="{{ asset('assets/docs/js/tooltip.js')}}"></script>
    <script src="{{ asset('assets/vendors/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/nicescroll/jquery.nicescroll.min.js')}}"></script>
    <script src="{{ asset('assets/docs/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/cdn/js/marked.min.js')}}"></script>

    <!-- JS Libraies -->
    <script src="{{ asset('assets/vendors/prism/prism.js')}}"></script>
    <script src="{{ asset('assets/docs/js/stisla.js')}}"></script>
    <script src="{{ asset('assets/docs/js/sticky-kit.js')}}"></script>
    <script src="{{ asset('assets/vendors/chocolat/js/jquery.chocolat.min.js')}}"></script>
    <script src="{{ asset('assets/docs/js/scripts.js')}}"></script>

    <!-- Template JS File -->
    <script src="{{asset('assets/cdn/js/navigo.min.js')}}"></script>
  </body>
</html>

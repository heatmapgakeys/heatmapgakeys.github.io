<!DOCTYPE html>
<html lang="{{ get_current_lang() }}">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    {!!url_make_canonical()!!}
    <title>{{ config('app.name') }} - @yield('title')</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="@yield('meta_title')">
    <meta name="description" content="@yield('meta_description')">
    <meta name="keywords" content="@yield('meta_keyword')">
    <meta name="author" content="@yield('meta_author')">

    <!-- Google -->
    <meta name="copyright" content="@yield('meta_author')"/>
    <meta name="application-name" content="{{config('app.name')}}" />

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{url('/')}}">
    <meta property="og:title" content="@yield('meta_title')">
    <meta property="og:description" content="@yield('meta_description')">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}"/>
    <meta property="og:image" content="@yield('meta_image')">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{url('/')}}">
    <meta property="twitter:title" content="@yield('meta_title')">
    <meta property="twitter:description" content="@yield('meta_description')">
    <meta property="twitter:image" content="@yield('meta_image')">

    <!--====== Favicon Icon ======-->
    <link rel="shortcut icon" href="{{config('app.favicon')}}" type="image/svg"/>

    <!-- ===== All CSS files ===== -->
    <link rel="stylesheet" href="{{asset('assets/front/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/front/css/ud-styles.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/front/css/glightbox.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/front/css/animate.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/front/css/tailwind.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/cdn/css/front.css')}}"/>
    <!-- ===== Alpine JS ===== -->
    <script
      defer
      src="{{asset('assets/cdn/js/cdn.min.js')}}"
    ></script>


    <script src="{{asset('assets/cdn/js/jquery-3.6.0.min.js')}}"></script>
    <script src="{{asset('assets/front/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/cdn/js/masonry.pkgd.min.js')}}" ></script>
    <script src="{{asset('assets/cdn/js/tailwind.js')}}"></script>
    @include('shared.variables_landing')

    @stack('styles-header')
    @stack('scripts-header')
  </head>
  <body x-data="{ scrolledFromTop: false }" x-init="window.pageYOffset >= 50 ? scrolledFromTop = true : scrolledFromTop = false" @scroll.window="window.pageYOffset >= 50 ? scrolledFromTop = true : scrolledFromTop = false" class="ud-bg-white dark:ud-bg-dark">
    <!-- ===== Header start ===== -->    
    <?php $is_dark_theme = str_contains(url()->current(), '/blog') ? '0'  : '1'; ?>
    <?php $is_homepage = request()->route()->getName() =='home' ? '1'  : '0'; ?>
    <header x-data="{navbarOpen: false,dropdownOpen: false}" :class="scrolledFromTop ? 'ud-bg-white dark:ud-bg-dark ud-bg-opacity-80 dark:ud-bg-opacity-80 ud-shadow-sticky ud-backdrop-blur-sm' : 'ud-bg-white dark:ud-bg-dark' " class="ud-w-full ud-flex ud-items-center ud-sticky ud-top-0 ud-z-50 wow fadeInUp" data-wow-delay=".2s">
      <div class="ud-container">
        <div class="ud-flex ud--mx-4 ud-items-center ud-justify-between ud-relative">
          <div class="ud-px-4 ud-w-60 ud-max-w-full">
            <a href="{{route('home')}}" :class="scrolledFromTop ? 'ud-py-5 lg:ud-py-0' : 'ud-py-7 lg:ud-py-0'" class="ud-w-full ud-block">
              <img
                src="{{ config('app.logo') }}"
                alt="logo"
                class="ud-w-full dark:ud-hidden"
              />
              <img
                src="{{ config('app.logo_alt') }}"
                alt="logo"
                class="ud-w-full ud-hidden dark:ud-block"
              />
            </a>
          </div>
          <div class="ud-flex ud-px-4 ud-justify-end ud-items-center ud-w-full">
            <div>
              <button
                @click="navbarOpen = !navbarOpen"
                :class="navbarOpen && 'navbarTogglerActive' "
                id="navbarToggler"
                class="
                  ud-block ud-absolute ud-right-4 ud-top-1/2 ud--translate-y-1/2
                  lg:ud-hidden
                  focus:ud-ring-2
                  ud-ring-primary ud-px-3 ud-py-[6px] ud-rounded-lg
                "
              >
                <span
                  :class="navbarOpen && 'ud-rotate-45 ud-top-[7px]' "
                  class="ud-relative ud-w-[30px] ud-h-[2px] ud-my-[6px] ud-block ud-bg-black dark:ud-bg-white"></span>
                <span
                  :class="navbarOpen && 'ud-opacity-0' "
                  class="ud-relative ud-w-[30px] ud-h-[2px] ud-my-[6px] ud-block ud-bg-black dark:ud-bg-white"></span>
                <span
                  :class="navbarOpen && 'ud-top-[-8px] ud-rotate-[135deg]' "
                  class="ud-relative ud-w-[30px] ud-h-[2px] ud-my-[6px] ud-block ud-bg-black dark:ud-bg-white"></span>
              </button>
              <nav
                :class="!navbarOpen && 'ud-hidden' "
                id="navbarCollapse"
                class=" ud-absolute ud-py-4 ud-px-6 ud-bg-white dark:ud-bg-black ud-shadow ud-rounded-lg ud-max-w-[250px] ud-w-full lg:ud-bg-transparent dark:lg:ud-bg-transparent lg:ud-max-w-full lg:ud-w-full ud-right-4 ud-top-full lg:ud-block lg:ud-static lg:ud-shadow-none">
                <ul class="ud-blcok lg:ud-flex">
                @if($disable_landing_page=='0')
                  <li>
                    <a
                      href="{{route('home')}}"
                      :class="scrolledFromTop ? 'ud-py-2 lg:ud-py-2' : 'ud-py-2 lg:ud-py-5' "
                      class="scroll-menu ud-text-base ud-font-medium ud-text-body-color hover:ud-text-primary lg:ud-inline-flex ud-flex lg:ud-ml-8 xl:ud-ml-12">
                      {{ __("Home") }}
                    </a>
                  </li>
                  <li>
                     <?php $current_route = Route::getCurrentRoute()->uri();?>
                    <a
                      href="{{route('pricing-plan')}}"
                      :class="scrolledFromTop ? 'ud-py-2 lg:ud-py-2' : 'ud-py-2 lg:ud-py-5' "
                      class=" scroll-menu ud-text-base ud-font-medium ud-text-body-color hover:ud-text-primary lg:ud-inline-flex ud-flex lg:ud-ml-8 xl:ud-ml-12">
                      {{ __("Pricing") }}
                    </a>
                  </li>
                @endif


                  <!-- <li>
                    <a href="{{route('list-blog')}}" :class="scrolledFromTop ? 'ud-py-2 lg:ud-py-2' : 'ud-py-2 lg:ud-py-5' " class="scroll-menu ud-text-base ud-font-medium ud-text-body-color hover:ud-text-primary lg:ud-inline-flex ud-flex lg:ud-ml-8 xl:ud-ml-12">
                      {{ __("Blog") }}
                    </a>
                  </li> -->

                  <!-- <li>
                    <a
                      href="{{route('dashboard-blog')}}"
                      :class="scrolledFromTop ? 'ud-py-2 lg:ud-py-2' : 'ud-py-2 lg:ud-py-5' "
                      class="scroll-menu ud-text-base ud-font-medium ud-text-body-color hover:ud-text-primary lg:ud-inline-flex ud-flex lg:ud-ml-8 l:ud-ml-12">
                      {{ __("Comments") }}
                    </a>
                  </li> -->

                  @if($disable_landing_page=='0')
                  <li>
                    <a
                      href="{{$get_landing_language->links_docs_url ?? url('docs')}}"
                      :class="scrolledFromTop ? 'ud-py-2 lg:ud-py-2' : 'ud-py-2 lg:ud-py-5' "
                      class="scroll-menu ud-text-base ud-font-medium ud-text-body-color hover:ud-text-primary lg:ud-inline-flex ud-flex lg:ud-ml-8 xl:ud-ml-12">
                      {{__('Documentation')}}
                    </a>
                  </li>
                  @endif

                  <li class="d-sm-none">
                    <a
                      href="{{route('login')}}"
                      :class="scrolledFromTop ? 'ud-py-2 lg:ud-py-2' : 'ud-py-2 lg:ud-py-5' "
                      class="scroll-menu ud-text-base ud-font-medium ud-text-body-color hover:ud-text-primary lg:ud-inline-flex ud-flex lg:ud-ml-8 xl:ud-ml-12">
                      <?php
                      if(Auth::user()) echo __('Dashboard');
                      else echo __('Sign In');
                      ?>
                    </a>
                  </li>


                </ul>
              </nav>
            </div>
            <div
              class="
                xl:ud-pl-20
                sm:ud-flex
                ud-justify-end ud-hidden ud-pr-16
                lg:ud-pr-0
              "
            >
              <a
                href="{{route('login')}}"
                class=" ud-flex ud-items-center ud-justify-center ud-text-base ud-font-medium ud-text-white ud-bg-primary ud-rounded-lg ud-py-3 ud-px-7 ud-transition-all hover:ud-shadow-primary-hover">
                <?php
                if(Auth::user()) echo __('Dashboard');
                else echo __('Sign In');
                ?>
              </a>
              <div>
                <label
                  for="darkToggler"
                  class="  
                    hidden                  
                    ud-cursor-pointer ud-w-9 ud-h-9
                    md:ud-w-14 md:ud-h-14
                    ud-rounded-full
                    ud-flex
                    ud-items-center
                    ud-justify-center
                    ud-bg-gray-2
                    dark:ud-bg-dark-bg
                    ud-text-black
                    dark:ud-text-white
                  "
                >
                  <input
                    type="checkbox"
                    name="darkToggler"
                    id="darkToggler"
                    class="ud-sr-only"
                    aria-label="darkToggler"
                  />
                  <svg
                    viewBox="0 0 23 23"
                    class="
                      ud-stroke-current
                      dark:ud-hidden
                      ud-w-5 ud-h-5
                      md:ud-w-6 md:ud-h-6
                    "
                    fill="none"
                  >
                    <path
                      d="M9.55078 1.5C5.80078 1.5 1.30078 5.25 1.30078 11.25C1.30078 17.25 5.80078 21.75 11.8008 21.75C17.8008 21.75 21.5508 17.25 21.5508 13.5C13.3008 18.75 4.30078 9.75 9.55078 1.5Z"
                      stroke-width="2"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg>
                  <svg
                    viewBox="0 0 25 24"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                    class="
                      ud-hidden
                      dark:ud-block
                      ud-w-5 ud-h-5
                      md:ud-w-6 md:ud-h-6
                    "
                  >
                    <mask id="path-1-inside-1_977:1934" fill="white">
                      <path
                        d="M12.0508 16.5C10.8573 16.5 9.71271 16.0259 8.8688 15.182C8.02489 14.3381 7.55078 13.1935 7.55078 12C7.55078 10.8065 8.02489 9.66193 8.8688 8.81802C9.71271 7.97411 10.8573 7.5 12.0508 7.5C13.2443 7.5 14.3888 7.97411 15.2328 8.81802C16.0767 9.66193 16.5508 10.8065 16.5508 12C16.5508 13.1935 16.0767 14.3381 15.2328 15.182C14.3888 16.0259 13.2443 16.5 12.0508 16.5ZM12.0508 18C13.6421 18 15.1682 17.3679 16.2934 16.2426C17.4186 15.1174 18.0508 13.5913 18.0508 12C18.0508 10.4087 17.4186 8.88258 16.2934 7.75736C15.1682 6.63214 13.6421 6 12.0508 6C10.4595 6 8.93336 6.63214 7.80814 7.75736C6.68292 8.88258 6.05078 10.4087 6.05078 12C6.05078 13.5913 6.68292 15.1174 7.80814 16.2426C8.93336 17.3679 10.4595 18 12.0508 18ZM12.0508 0C12.2497 0 12.4405 0.0790176 12.5811 0.21967C12.7218 0.360322 12.8008 0.551088 12.8008 0.75V3.75C12.8008 3.94891 12.7218 4.13968 12.5811 4.28033C12.4405 4.42098 12.2497 4.5 12.0508 4.5C11.8519 4.5 11.6611 4.42098 11.5205 4.28033C11.3798 4.13968 11.3008 3.94891 11.3008 3.75V0.75C11.3008 0.551088 11.3798 0.360322 11.5205 0.21967C11.6611 0.0790176 11.8519 0 12.0508 0V0ZM12.0508 19.5C12.2497 19.5 12.4405 19.579 12.5811 19.7197C12.7218 19.8603 12.8008 20.0511 12.8008 20.25V23.25C12.8008 23.4489 12.7218 23.6397 12.5811 23.7803C12.4405 23.921 12.2497 24 12.0508 24C11.8519 24 11.6611 23.921 11.5205 23.7803C11.3798 23.6397 11.3008 23.4489 11.3008 23.25V20.25C11.3008 20.0511 11.3798 19.8603 11.5205 19.7197C11.6611 19.579 11.8519 19.5 12.0508 19.5ZM24.0508 12C24.0508 12.1989 23.9718 12.3897 23.8311 12.5303C23.6905 12.671 23.4997 12.75 23.3008 12.75H20.3008C20.1019 12.75 19.9111 12.671 19.7705 12.5303C19.6298 12.3897 19.5508 12.1989 19.5508 12C19.5508 11.8011 19.6298 11.6103 19.7705 11.4697C19.9111 11.329 20.1019 11.25 20.3008 11.25H23.3008C23.4997 11.25 23.6905 11.329 23.8311 11.4697C23.9718 11.6103 24.0508 11.8011 24.0508 12ZM4.55078 12C4.55078 12.1989 4.47176 12.3897 4.33111 12.5303C4.19046 12.671 3.99969 12.75 3.80078 12.75H0.800781C0.601869 12.75 0.411103 12.671 0.270451 12.5303C0.129799 12.3897 0.0507813 12.1989 0.0507812 12C0.0507813 11.8011 0.129799 11.6103 0.270451 11.4697C0.411103 11.329 0.601869 11.25 0.800781 11.25H3.80078C3.99969 11.25 4.19046 11.329 4.33111 11.4697C4.47176 11.6103 4.55078 11.8011 4.55078 12ZM20.5363 3.5145C20.6769 3.65515 20.7559 3.84588 20.7559 4.04475C20.7559 4.24362 20.6769 4.43435 20.5363 4.575L18.4153 6.6975C18.3455 6.76713 18.2628 6.82235 18.1717 6.86C18.0806 6.89765 17.983 6.91699 17.8845 6.91692C17.6855 6.91678 17.4947 6.83758 17.354 6.69675C17.2844 6.62702 17.2292 6.54425 17.1915 6.45318C17.1539 6.36211 17.1345 6.26452 17.1346 6.16597C17.1348 5.96695 17.214 5.77613 17.3548 5.6355L19.4758 3.5145C19.6164 3.3739 19.8072 3.29491 20.006 3.29491C20.2049 3.29491 20.3956 3.3739 20.5363 3.5145ZM6.74678 17.304C6.88738 17.4446 6.96637 17.6354 6.96637 17.8342C6.96637 18.0331 6.88738 18.2239 6.74678 18.3645L4.62578 20.4855C4.48433 20.6221 4.29488 20.6977 4.09823 20.696C3.90158 20.6943 3.71347 20.6154 3.57442 20.4764C3.43536 20.3373 3.35648 20.1492 3.35478 19.9526C3.35307 19.7559 3.42866 19.5665 3.56528 19.425L5.68628 17.304C5.82693 17.1634 6.01766 17.0844 6.21653 17.0844C6.4154 17.0844 6.60614 17.1634 6.74678 17.304ZM20.5363 20.4855C20.3956 20.6261 20.2049 20.7051 20.006 20.7051C19.8072 20.7051 19.6164 20.6261 19.4758 20.4855L17.3548 18.3645C17.2182 18.223 17.1426 18.0336 17.1443 17.8369C17.146 17.6403 17.2249 17.4522 17.3639 17.3131C17.503 17.1741 17.6911 17.0952 17.8877 17.0935C18.0844 17.0918 18.2738 17.1674 18.4153 17.304L20.5363 19.425C20.6769 19.5656 20.7559 19.7564 20.7559 19.9552C20.7559 20.1541 20.6769 20.3449 20.5363 20.4855ZM6.74678 6.6975C6.60614 6.8381 6.4154 6.91709 6.21653 6.91709C6.01766 6.91709 5.82693 6.8381 5.68628 6.6975L3.56528 4.575C3.49365 4.50582 3.43651 4.42306 3.39721 4.33155C3.3579 4.24005 3.33721 4.14164 3.33634 4.04205C3.33548 3.94247 3.35445 3.84371 3.39216 3.75153C3.42988 3.65936 3.48557 3.57562 3.55598 3.5052C3.6264 3.43478 3.71014 3.37909 3.80232 3.34138C3.89449 3.30367 3.99325 3.2847 4.09283 3.28556C4.19242 3.28643 4.29083 3.30712 4.38233 3.34642C4.47384 3.38573 4.5566 3.44287 4.62578 3.5145L6.74678 5.6355C6.81663 5.70517 6.87204 5.78793 6.90985 5.87905C6.94766 5.97017 6.96712 6.06785 6.96712 6.1665C6.96712 6.26515 6.94766 6.36283 6.90985 6.45395C6.87204 6.54507 6.81663 6.62783 6.74678 6.6975Z"
                      />
                    </mask>
                    <path
                      d="M12.0508 16.5C10.8573 16.5 9.71271 16.0259 8.8688 15.182C8.02489 14.3381 7.55078 13.1935 7.55078 12C7.55078 10.8065 8.02489 9.66193 8.8688 8.81802C9.71271 7.97411 10.8573 7.5 12.0508 7.5C13.2443 7.5 14.3888 7.97411 15.2328 8.81802C16.0767 9.66193 16.5508 10.8065 16.5508 12C16.5508 13.1935 16.0767 14.3381 15.2328 15.182C14.3888 16.0259 13.2443 16.5 12.0508 16.5ZM12.0508 18C13.6421 18 15.1682 17.3679 16.2934 16.2426C17.4186 15.1174 18.0508 13.5913 18.0508 12C18.0508 10.4087 17.4186 8.88258 16.2934 7.75736C15.1682 6.63214 13.6421 6 12.0508 6C10.4595 6 8.93336 6.63214 7.80814 7.75736C6.68292 8.88258 6.05078 10.4087 6.05078 12C6.05078 13.5913 6.68292 15.1174 7.80814 16.2426C8.93336 17.3679 10.4595 18 12.0508 18ZM12.0508 0C12.2497 0 12.4405 0.0790176 12.5811 0.21967C12.7218 0.360322 12.8008 0.551088 12.8008 0.75V3.75C12.8008 3.94891 12.7218 4.13968 12.5811 4.28033C12.4405 4.42098 12.2497 4.5 12.0508 4.5C11.8519 4.5 11.6611 4.42098 11.5205 4.28033C11.3798 4.13968 11.3008 3.94891 11.3008 3.75V0.75C11.3008 0.551088 11.3798 0.360322 11.5205 0.21967C11.6611 0.0790176 11.8519 0 12.0508 0V0ZM12.0508 19.5C12.2497 19.5 12.4405 19.579 12.5811 19.7197C12.7218 19.8603 12.8008 20.0511 12.8008 20.25V23.25C12.8008 23.4489 12.7218 23.6397 12.5811 23.7803C12.4405 23.921 12.2497 24 12.0508 24C11.8519 24 11.6611 23.921 11.5205 23.7803C11.3798 23.6397 11.3008 23.4489 11.3008 23.25V20.25C11.3008 20.0511 11.3798 19.8603 11.5205 19.7197C11.6611 19.579 11.8519 19.5 12.0508 19.5ZM24.0508 12C24.0508 12.1989 23.9718 12.3897 23.8311 12.5303C23.6905 12.671 23.4997 12.75 23.3008 12.75H20.3008C20.1019 12.75 19.9111 12.671 19.7705 12.5303C19.6298 12.3897 19.5508 12.1989 19.5508 12C19.5508 11.8011 19.6298 11.6103 19.7705 11.4697C19.9111 11.329 20.1019 11.25 20.3008 11.25H23.3008C23.4997 11.25 23.6905 11.329 23.8311 11.4697C23.9718 11.6103 24.0508 11.8011 24.0508 12ZM4.55078 12C4.55078 12.1989 4.47176 12.3897 4.33111 12.5303C4.19046 12.671 3.99969 12.75 3.80078 12.75H0.800781C0.601869 12.75 0.411103 12.671 0.270451 12.5303C0.129799 12.3897 0.0507813 12.1989 0.0507812 12C0.0507813 11.8011 0.129799 11.6103 0.270451 11.4697C0.411103 11.329 0.601869 11.25 0.800781 11.25H3.80078C3.99969 11.25 4.19046 11.329 4.33111 11.4697C4.47176 11.6103 4.55078 11.8011 4.55078 12ZM20.5363 3.5145C20.6769 3.65515 20.7559 3.84588 20.7559 4.04475C20.7559 4.24362 20.6769 4.43435 20.5363 4.575L18.4153 6.6975C18.3455 6.76713 18.2628 6.82235 18.1717 6.86C18.0806 6.89765 17.983 6.91699 17.8845 6.91692C17.6855 6.91678 17.4947 6.83758 17.354 6.69675C17.2844 6.62702 17.2292 6.54425 17.1915 6.45318C17.1539 6.36211 17.1345 6.26452 17.1346 6.16597C17.1348 5.96695 17.214 5.77613 17.3548 5.6355L19.4758 3.5145C19.6164 3.3739 19.8072 3.29491 20.006 3.29491C20.2049 3.29491 20.3956 3.3739 20.5363 3.5145ZM6.74678 17.304C6.88738 17.4446 6.96637 17.6354 6.96637 17.8342C6.96637 18.0331 6.88738 18.2239 6.74678 18.3645L4.62578 20.4855C4.48433 20.6221 4.29488 20.6977 4.09823 20.696C3.90158 20.6943 3.71347 20.6154 3.57442 20.4764C3.43536 20.3373 3.35648 20.1492 3.35478 19.9526C3.35307 19.7559 3.42866 19.5665 3.56528 19.425L5.68628 17.304C5.82693 17.1634 6.01766 17.0844 6.21653 17.0844C6.4154 17.0844 6.60614 17.1634 6.74678 17.304ZM20.5363 20.4855C20.3956 20.6261 20.2049 20.7051 20.006 20.7051C19.8072 20.7051 19.6164 20.6261 19.4758 20.4855L17.3548 18.3645C17.2182 18.223 17.1426 18.0336 17.1443 17.8369C17.146 17.6403 17.2249 17.4522 17.3639 17.3131C17.503 17.1741 17.6911 17.0952 17.8877 17.0935C18.0844 17.0918 18.2738 17.1674 18.4153 17.304L20.5363 19.425C20.6769 19.5656 20.7559 19.7564 20.7559 19.9552C20.7559 20.1541 20.6769 20.3449 20.5363 20.4855ZM6.74678 6.6975C6.60614 6.8381 6.4154 6.91709 6.21653 6.91709C6.01766 6.91709 5.82693 6.8381 5.68628 6.6975L3.56528 4.575C3.49365 4.50582 3.43651 4.42306 3.39721 4.33155C3.3579 4.24005 3.33721 4.14164 3.33634 4.04205C3.33548 3.94247 3.35445 3.84371 3.39216 3.75153C3.42988 3.65936 3.48557 3.57562 3.55598 3.5052C3.6264 3.43478 3.71014 3.37909 3.80232 3.34138C3.89449 3.30367 3.99325 3.2847 4.09283 3.28556C4.19242 3.28643 4.29083 3.30712 4.38233 3.34642C4.47384 3.38573 4.5566 3.44287 4.62578 3.5145L6.74678 5.6355C6.81663 5.70517 6.87204 5.78793 6.90985 5.87905C6.94766 5.97017 6.96712 6.06785 6.96712 6.1665C6.96712 6.26515 6.94766 6.36283 6.90985 6.45395C6.87204 6.54507 6.81663 6.62783 6.74678 6.6975Z"
                      fill="black"
                      stroke="white"
                      stroke-width="2"
                      mask="url(#path-1-inside-1_977:1934)"
                    />
                  </svg>
                </label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>
    <!-- ===== Header end ===== -->

    @yield('content')

    <!-- ===== Footer start ===== -->
    @if($disable_landing_page=='0')
    <footer id="footer">
      <div
        class="ud-bg-gradient-1 ud-relative dark:ud-bg-black ud-z-10 ud-pt-[100px] ud-pb-[52px] wow fadeInUp
        " data-wow-delay=".2s"
      >
        <div class="ud-container">
          <div class="ud-flex ud-flex-wrap ud--mx-4">
            <div
              class="ud-w-full md:ud-w-1/2 lg:ud-w-4/12 xl:ud-w-4/12 ud-px-4"
            >
              <div class="ud-mb-12 sm:ud-max-w-[300px]">
                <a href="{{route('home')}}" class="ud-inline-flex ud-mb-8">
                  <img
                    src="{{ config('app.logo') }}"
                    alt="logo"
                    class="ud-max-w-[160px] dark:ud-hidden"
                  />
                  <img
                    src="{{ config('app.logo_alt') }}"
                    alt="logo"
                    class="ud-max-w-[160px] dark:ud-block ud-hidden"
                  />
                </a>
                <p class="ud-font-semibold ud-text-base ud-text-body-color ud-mb-8">{{ __("Heatmap and Sessions Recording tool") }}</p>
                <div class="ud-flex ud-items-center">
                  @if(isset($get_landing_language->company_telegram_channel) && !empty($get_landing_language->company_telegram_channel))
                    <a
                      href="{{$get_landing_language->company_telegram_channel}}"
                      class="
                        ud-text-body-color
                        ud-flex
                        ud-items-center
                        ud-justify-center
                        hover:ud-text-primary
                        ud-mr-4
                      "
                      name="social-link"
                      aria-label="social-link"
                    >
                      <i class="fas fa-paper-plane"></i>
                    </a>
                  @endif

                  @if(isset($get_landing_language->company_fb_page) && !empty($get_landing_language->company_fb_page))
                    <a
                      href="{{$get_landing_language->company_fb_page}}"
                      class="
                        ud-text-body-color
                        ud-flex
                        ud-items-center
                        ud-justify-center
                        hover:ud-text-primary
                        ud-mr-4
                      "
                      name="social-link"
                      aria-label="social-link"
                    >
                      <i class="lni lni-facebook-filled"></i>
                    </a>
                  @endif


                  @if(isset($get_landing_language->company_youtube_channel) && !empty($get_landing_language->company_youtube_channel))
                    <a
                      href="{{$get_landing_language->company_youtube_channel}}"
                      class="
                        ud-text-body-color
                        ud-flex
                        ud-items-center
                        ud-justify-center
                        hover:ud-text-primary
                        ud-mr-4
                      "
                      name="social-link"
                      aria-label="social-link"
                    >
                      <i class="fab fa-youtube small"></i>
                    </a>
                  @endif

                  @if(isset($get_landing_language->company_twitter_account) && !empty($get_landing_language->company_twitter_account))
                    <a
                      href="{{$get_landing_language->company_twitter_account}}"
                      class="
                        ud-text-body-color
                        ud-flex
                        ud-items-center
                        ud-justify-center
                        hover:ud-text-primary
                        ud-mr-4
                      "
                      name="social-link"
                      aria-label="social-link"
                    >
                      <i class="lni lni-twitter-filled"></i>
                    </a>
                  @endif

                  @if(isset($get_landing_language->company_instagram_account) && !empty($get_landing_language->company_instagram_account))
                    <a
                      href="{{$get_landing_language->company_instagram_account}}"
                      class="
                        ud-text-body-color
                        ud-flex
                        ud-items-center
                        ud-justify-center
                        hover:ud-text-primary
                        ud-mr-4
                      "
                      name="social-link"
                      aria-label="social-link"
                    >
                      <i class="lni lni-linkedin-original"></i>
                    </a>
                  @endif

                  @if(isset($get_landing_language->company_linkedin_channel) && !empty($get_landing_language->company_linkedin_channel))
                    <a
                      href="{{$get_landing_language->company_linkedin_channel}}"
                      class="
                        ud-text-body-color
                        ud-flex
                        ud-items-center
                        ud-justify-center
                        hover:ud-text-primary
                        ud-mr-4
                      "
                      name="social-link"
                      aria-label="social-link"
                    >
                      <i class="lni lni-instagram-filled"></i>
                    </a>
                  @endif

                </div>

                <div class="ud-flex ud-items-center">
                  <a
                    href="javascript:void(0)"
                    class="
                      ud-text-body-color
                      ud-flex
                      ud-items-center
                      ud-justify-center
                      hover:ud-text-primary
                      ud-mr-4
                    "
                    name="social-link"
                    aria-label="social-link"
                  >
                    <img src="{{ asset('assets/front/images/svg/footer1.svg') }}" alt="">
                  </a>
                  <a
                    href="javascript:void(0)"
                    class="
                      ud-text-body-color
                      ud-flex
                      ud-items-center
                      ud-justify-center
                      hover:ud-text-primary
                      ud-mr-4
                    "
                    name="social-link"
                    aria-label="social-link"
                  >
                  <img src="{{ asset('assets/front/images/svg/footer2.svg') }}" alt="">

                  </a>
                  <a
                    href="javascript:void(0)"
                    class="
                      ud-text-body-color
                      ud-flex
                      ud-items-center
                      ud-justify-center
                      hover:ud-text-primary
                      ud-mr-4
                    "
                    name="social-link"
                    aria-label="social-link"
                  >
                  <img src="{{ asset('assets/front/images/svg/footer3.svg') }}" alt="">

                  </a>
                  <a
                    href="javascript:void(0)"
                    class="
                      ud-text-body-color
                      ud-flex
                      ud-items-center
                      ud-justify-center
                      hover:ud-text-primary
                      ud-mr-4
                    "
                    name="social-link"
                    aria-label="social-link"
                  >
                  <img src="{{ asset('assets/front/images/svg/footer4.svg') }}" alt="">

                  </a>
                </div>
              </div>
            </div>
            <div
              class="
                ud-w-full
                sm:ud-w-1/2
                md:ud-w-1/2
                lg:ud-w-3/12
                xl:ud-w-3/12
                ud-px-4
              "
            >
              <div class="ud-mb-12">
                <h3
                  class="
                    ud-font-bold ud-text-xl ud-text-black
                    dark:ud-text-white
                    ud-mb-9
                  "
                >
                  {{ __("Links") }}
                </h3>
                <ul class="ud-space-y-[18px]">
                  <li>
                    <a
                      href="{{route('home')}}"
                      class="
                        ud-font-semibold ud-text-base ud-text-body-color
                        hover:ud-text-primary
                        ud-inline-block
                      "
                    >
                      {{ __("Home") }}
                    </a>
                  </li>

                  @if($is_agency_site)
                  <li>
                    <a class="
                        ud-font-semibold ud-text-base ud-text-body-color
                        hover:ud-text-primary
                        ud-inline-block
                      " href="{{$get_landing_language->links_docs_url ?? url('docs')}}">{{__('Knowledge-base')}}</a>
                  </li>
                  @endif
                    <li>
                      <a
                        href="{{route('pricing-plan')}}"
                        class="
                          ud-font-semibold ud-text-base ud-text-body-color
                          hover:ud-text-primary
                          ud-inline-block
                        "
                      >
                        {{ __("Pricing") }}
                      </a>
                    </li>
                  <?php
                   
                   $company_support_url = $get_landing_language->company_support_url ?? '';
                  ?>

                  @if(!empty($company_support_url))
                  <li>
                      <a class="ud-font-semibold ud-text-base ud-text-body-color hover:ud-text-primary ud-inline-block" href="{{$company_support_url}}">{{__('Support Desk')}}</a>
                  </li>
                  @endif
                </ul>
              </div>
            </div>
            <div
              class="
                ud-w-full
                sm:ud-w-1/2
                md:ud-w-1/2
                lg:ud-w-3/12
                xl:ud-w-3/12
                ud-px-4
              "
            >
              <div class="ud-mb-12">
                <h3
                  class="
                    ud-font-bold ud-text-xl ud-text-black
                    dark:ud-text-white
                    ud-mb-9
                  "
                >
                  {{__('Legal')}}
                </h3>
                <ul class="ud-space-y-[18px]">
                  <li>
                    <a
                      href="{{route('policy-privacy')}}"
                      class="
                        ud-font-semibold ud-text-base ud-text-body-color
                        hover:ud-text-primary
                        ud-inline-block
                      "
                    >
                      {{__('Privacy Policy')}}
                    </a>
                  </li>
                  <li>
                    <a
                      href="{{route('policy-terms')}}"
                      class="
                        ud-font-semibold ud-text-base ud-text-body-color
                        hover:ud-text-primary
                        ud-inline-block
                      "
                    >
                      {{__('Terms of Service')}}
                    </a>
                  </li>
                  <li>
                    <a
                      href="{{route('policy-gdpr')}}"
                      class="
                        ud-font-semibold ud-text-base ud-text-body-color
                        hover:ud-text-primary
                        ud-inline-block
                      "
                    >
                      {{__('GDPR Policy')}}
                    </a>
                  </li>
                  <li>
                    <a
                      href="{{route('policy-refund')}}"
                      class="
                        ud-font-semibold ud-text-base ud-text-body-color
                        hover:ud-text-primary
                        ud-inline-block
                      "
                    >
                      {{__('Refund Policy')}}
                    </a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>

        <div>
          <span class="ud-absolute ud-left-0 ud-bottom-0 ud--z-1">
            <img src="{{ asset('assets/front/images/svg/footer5.svg') }}" alt="">
          </span>
          <span class="ud-absolute ud-right-0 ud-top-0 ud--z-1">
            <img src="{{ asset('assets/front/images/svg/footer6.svg') }}" alt="">

          </span>
        </div>
      </div>
    </footer>
    @endif
    <!-- ===== Footer end ===== -->

    <!-- ====== Back To Top Start ====== -->
    <!-- ====== Back To Top Start -->
    <a
      x-show="scrolledFromTop"
      href="javascript:void(0)"
      class="
        ud-flex
        ud-items-center
        ud-justify-center
        ud-bg-primary
        ud-text-white
        ud-w-10
        ud-h-10
        ud-rounded-lg
        ud-fixed
        ud-bottom-8
        ud-right-8
        ud-left-auto
        ud-z-[999]
        hover:ud-shadow-signUp
        ud-transition
        back-to-top
        ud-shadow-md
      "
    >
      <span
        class="
          ud-w-3
          ud-h-3
          ud-border-t
          ud-border-l
          ud-border-white
          ud-rotate-45
          ud-mt-[6px]
        "
      ></span>
    </a>
    <!-- ====== Back To Top End ====== -->

    <script type="text/javascript">
      var is_dark_theme = '{{$is_dark_theme}}';
      var is_homepage = '{{$is_homepage}}';
    </script>

    <script src="{{ asset('assets/front/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('assets/front/js/main.js') }}"></script>

    <script>


      //========= glightbox
      if(is_homepage=='1')
      GLightbox({
        href: "https://youtu.be/5XQPr4sQDYQ",
        type: "video",
        source: "youtube", //vimeo, youtube or local
        width: 900,
        autoplayVideos: true,
      });
    </script>

    <!-- ====== All Javascript Files ====== -->
    <script src="{{asset('assets/front/js/wow.min.js')}}"></script>
    <script src="{{asset('assets/cdn/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/cdn/js/sweetalert2.min.js')}}"></script>
    <script src="{{asset('assets/cdn/js/toastr.min.js')}}"></script>
    <script src="{{ asset('assets/js/common/common.js') }}"></script>
    <script>
      // ===== wow js
      new WOW().init();
    </script>
    @if(!$is_agency_site)
        @include('shared.analytics')
    @endif
    @stack('scripts-footer')


    <!-- ===== All CSS files ===== -->
    <link rel="stylesheet" href="{{asset('assets/cdn/css/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/cdn/css/sweetalert2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/cdn/css/toastr.min.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/cdn/css/all.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/front/css/lineicons.css')}}" />

    @stack('styles-footer')
    </body>
</html>
@yield('modal')

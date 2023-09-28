@extends('layouts.front')
@section('title',$title)
@section('meta_title',$meta_title)
@section('meta_description',$meta_description)
@section('meta_keyword',$meta_keyword)
@section('meta_author',$meta_author)
@section('meta_image',$meta_image)
@section('meta_image_width',$meta_image_width)
@section('meta_image_height',$meta_image_height)
@section('content')

<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

<!-- ===== Hero Start ===== -->
@php
   if(isset($get_landing_language->header_image) && empty($get_landing_language->header_image)) $get_landing_language->header_image = asset('assets/front/images/hero/hero-image-2.png');
   if(isset($get_landing_language->details_feature_1_img) &&empty($get_landing_language->details_feature_1_img)) $get_landing_language->details_feature_1_img = asset('assets/front/images/about/about-image-1.png');
   if(isset($get_landing_language->details_feature_2_img) &&empty($get_landing_language->details_feature_2_img)) $get_landing_language->details_feature_2_img = asset('assets/front/images/about/about-image-2.png');
   if(isset($get_landing_language->details_feature_3_img) &&empty($get_landing_language->details_feature_3_img)) $get_landing_language->details_feature_3_img = asset('assets/front/images/cta/cta-image-1.png');
   if(isset($get_landing_language->details_feature_4_img) &&empty($get_landing_language->details_feature_4_img)) $get_landing_language->details_feature_4_img = asset('assets/front/images/cta/cta-image-2.png');

@endphp
<section id="home">
  <div
    class="
      ud-bg-gradient-to-l ud-from-gradient-1 ud-to-gradient-2
      dark:ud-from-[#3c3e56] dark:ud-to-black
      ud-rounded-[20px] ud-py-16 ud-mx-4
      2xl:ud-mx-[60px]
    "
  >
    <div class="ud-container">
      <div class="ud-flex ud-flex-wrap ud-items-center ud--mx-4">
        <div class="ud-w-full lg:ud-w-1/2 ud-px-4">
          <div
            class="ud-mb-14 lg:ud-mb-0 ud-max-w-[470px] wow fadeInUp"
            data-wow-delay=".2s"
          >
            <h1
              class="
                ud-font-semibold ud-text-black
                dark:ud-text-white
                ud-text-4xl
                md:ud-text-[45px]
                ud-leading-tight
                md:ud-leading-tight
                ud-mb-8
              "
            >
            <span class="ud-font-bold">{{ __("Heatmap & Sessions Recording tool") }}</span>
            </h1>
            <p
              class="
                ud-font-semibold ud-text-base ud-text-body-color ud-mb-16
              "
            >
              {{ __("With :appname- :title, you can see what users do on your website pages—where they click, how far they scroll, where their cursors move, what they look at, what they ignore and Where they are attracted to. In one word, you can monitor the whole interaction of users with your website.",['appname'=>config('app.name'),'title'=>$title]) }}
            </p>

            <div class="ud-flex ud-items-center">
              <a
                href="{{route('register')}}"
                class="
                  ud-text-base
                  ud-font-bold
                  ud-text-white
                  ud-bg-primary
                  ud-rounded-xl
                  ud-py-4
                  ud-px-10
                  ud-transition-all
                  hover:ud-shadow-primary-hover
                  ud-mr-9
                "
              >
                {{ __("Get Started for FREE") }}
              </a>
              <a
                href="{{$get_landing_language->links_docs_url ?? url('docs')}}"
                class="
                  ud-flex
                  ud-items-center
                  ud-font-bold
                  ud-text-base
                  ud-text-black
                  dark:ud-text-white
                  hover:ud-text-primary
                  dark:hover:ud-text-primary
                  ud-group ud-transition-all
                "
              >
                <span
                  class="
                    ud-w-[60px]
                    ud-h-[60px]
                    ud-rounded-full
                    ud-inline-flex
                    ud-items-center
                    ud-justify-center
                    ud-bg-primary
                    ud-bg-opacity-10
                    ud-text-primary
                    ud-mr-5
                    ud-transition-all
                    group-hover:ud-bg-opacity-100 group-hover:ud-text-white
                  "
                >
                <i class="fas fa-book-reader" id="fa-book-reader_id"></i>
                </span>
                {{ __("Learn More") }}
              </a>

            </div>
          </div>
        </div>
        <div class="ud-w-full lg:ud-w-1/2 ud-px-4">
          <div
            class="
              ud-text-center ud-relative ud-z-10 ud-h-[532px]
              wow
              fadeInUp
            "
            data-wow-delay=".25s"
          >
            <img
              src="{{$get_landing_language->header_image ?? ''}}"
              alt="hero-image"
              class="ud-max-w-full ud-mx-auto"
              width="254"
            />
            <span
              class="
                ud-absolute
                ud--z-1
                ud-top-1/2
                ud-left-1/2
                ud--translate-x-1/2
                ud--translate-y-1/2
                ud-max-w-[350px]
                ud-w-full
                ud-h-[350px]
                ud-bg-white
                dark:ud-bg-dark
                ud-bg-opacity-25
                dark:ud-bg-opacity-25
                ud-shadow-shape-1 ud-rounded-full
              "
            >
            </span>
            <span
              class="
                ud-absolute
                ud--z-1
                ud-top-1/2
                ud-left-1/2
                ud--translate-x-1/2
                ud--translate-y-1/2
                ud-max-w-[450px]
                ud-w-full
                ud-h-[450px]
                ud-bg-white
                dark:ud-bg-dark
                ud-bg-opacity-25
                dark:ud-bg-opacity-25
                ud-shadow-shape-1 ud-rounded-full
              "
            >
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ===== Hero end ===== -->

<!-- ===== Awards start ===== -->
<section id="features" class="ud-pt-[110px]">
  <div class="ud-container">
    <div class="ud-flex ud-justify-center ud--mx-4">
      <div class="ud-w-full ud-px-4">
        <div
          class="
            ud-max-w-[510px] ud-mx-auto ud-text-center ud-mb-[70px]
            wow
            fadeInUp
          "
          data-wow-delay=".2s"
        >
          <h2
            class="
              ud-font-extrabold ud-text-3xl
              sm:ud-text-4xl
              ud-text-black
              dark:ud-text-white
              ud-mb-5
            "
          >
            {{ __(":appname's Features",['appname'=>config('app.name')]) }}
          </h2>
          <p class="ud-font-semibold ud-text-base ud-text-body-color">{{ __(":appname provides real time analytics of your website. To do that, :appname has three features.",['appname'=>config('app.name')]) }}</p>
        </div>
      </div>
    </div>

    <div class="ud-flex ud-flex-wrap ud--mx-4">
      <div class="ud-w-full md:ud-w-1/2 xl:ud-w-1/3 ud-px-4">
        <div
          class="
            ud-bg-white
            dark:ud-bg-black
            ud-p-10
            sm:ud-p-12
            md:ud-p-10
            lg:ud-p-12
            xl:ud-p-10
            2xl:ud-p-12
            ud-rounded-[20px] ud-shadow-award ud-mb-8
            wow
            fadeInUp
          "
          data-wow-delay=".2s"
        >
          <div class="ud-flex ud-items-center ud-mb-6">
            <span class="ud-pr-[10px]">
              <svg
                width="32"
                height="32"
                viewBox="0 0 32 32"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <circle cx="16" cy="16" r="16" fill="#8B5CF6" />
                <path
                  d="M24 14.0968L18.248 13.5747L16 8L13.752 13.5747L8 14.0968L12.36 18.08L11.056 24L16 20.8589L20.944 24L19.632 18.08L24 14.0968Z"
                  fill="white"
                />
              </svg>
            </span>
            <span
              class="
                ud-font-bold ud-text-lg ud-text-black
                dark:ud-text-white
              "
            >
              {{ __("Live Users") }}
            </span>
          </div>

          <p class="ud-text-base ud-text-body-color ud-mb-9">{{ __("In the Live User feature, you can see the number of Users currently browsing on your website. And, you can watch the session recordings of the users. You can see the average stay time and number of total sessions. Moreover, you can see the List of top countries based on the number of users. Besides, you can see the 5 top viewed pages of your website.") }}</p>


        </div>
      </div>
      <div class="ud-w-full md:ud-w-1/2 xl:ud-w-1/3 ud-px-4">
        <div
          class="
            ud-bg-white
            dark:ud-bg-black
            ud-p-10
            sm:ud-p-12
            md:ud-p-10
            lg:ud-p-12
            xl:ud-p-10
            2xl:ud-p-12
            ud-rounded-[20px] ud-shadow-award ud-mb-8
            wow
            fadeInUp
          "
          data-wow-delay=".25s"
        >
          <div class="ud-flex ud-items-center ud-mb-6">
              <span class="ud-pr-[10px]">
                <i 
                  class="fas fa-fire text-white" 
                  id="fa-fire_id"
                >
                </i>
              </span>
            <span
              class="
                ud-font-bold ud-text-lg ud-text-black
                dark:ud-text-white
              "
            >
              {{ __("Heatmap") }}
            </span>
          </div>

          <div class="ud-flex ud-items-center ud-mb-5">
            <h3
              class="
                ud-font-bold ud-text-xl ud-text-black
                dark:ud-text-white
              "
            >
              {!! __("Heatmap, by infographic displaying, can easily visualize :attr and help you understand it in a minute.",["attr"=>'<span class="ud-font-bold ud-text-primary">complex data</span>']) !!}
            </h3>
          </div>

          <p class="ud-text-base ud-text-body-color ud-mb-9">{{ __("Heatmap provide graphical presentation of where users click on desktop or tap on a mobile, where the user’s cursor moves while navigating the website, and how far down a page a user scrolls on your website.") }}</p>
        </div>
      </div>
      <div class="ud-w-full md:ud-w-1/2 xl:ud-w-1/3 ud-px-4">
        <div
          class="
            ud-bg-white
            dark:ud-bg-black
            ud-p-10
            sm:ud-p-12
            md:ud-p-10
            lg:ud-p-12
            xl:ud-p-10
            2xl:ud-p-12
            ud-rounded-[20px] ud-shadow-award ud-mb-8
            wow
            fadeInUp
          "
          data-wow-delay=".3s"
        >
          <div class="ud-flex ud-items-center ud-mb-6">
            <span class="ud-pr-[10px]">
              <i 
                class="fas fa-video text-white"
                id="fa-video_text-white_id"
              >
              </i>
            </span>
            <span
              class="
                ud-font-bold ud-text-lg ud-text-black
                dark:ud-text-white
              "
            >
              {{ __('Sessions Recordings') }}
            </span>
          </div>

          <div class="ud-flex ud-items-center ud-mb-5">
            <h3
              class="
                ud-font-bold ud-text-xl ud-text-black
                dark:ud-text-white
              "
            >
              {{ __(":appname can also produce Sessions Recordings of what users do on your website.",['appname'=>config('app.name')]) }}
            </h3>
          </div>

          <p class="ud-text-base ud-text-body-color ud-mb-9">{{ __("On the Sessions Recording, you can see what a user is doing on your website — you can get a overall insight of the user's interaction with your website.") }}</p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ===== Awards end ===== -->

<!-- ===== About start ===== -->
<section id="about" class="ud-pt-[100px]">
  <div class="ud-container">
    <div class="ud-flex ud-flex-wrap ud-items-center ud--mx-4">
      <div class="ud-w-full lg:ud-w-1/2 ud-px-4">
        <div
          class="ud-text-center ud-mb-14 lg:ud-mb-0 wow fadeInUp"
          data-wow-delay=".2s"
        >
          <img
            src="{{$get_landing_language->details_feature_1_img ?? ''}}"
            alt="image"
            class="ud-max-full"
          />
        </div>
      </div>
      <div class="ud-w-full lg:ud-w-1/2 ud-px-4">
        <div
          class="ud-max-w-[485px] lg:ud-ml-auto wow fadeInUp"
          data-wow-delay=".3s"
        >
          <span
            class="
              ud-font-bold ud-text-base ud-text-primary ud-block ud-mb-2
            "
          >
            {{ __("Real-Time Tracking") }}
          </span>
          <h2
            class="
              ud-font-extrabold ud-text-3xl
              sm:ud-text-4xl
              ud-leading-tight ud-text-black
              dark:ud-text-white
              ud-mb-6
            "
          >
            {{ __("Instant and real-time analytics help you reorganize your website.") }}
          </h2>
          <p
            class="
              ud-text-lg ud-leading-relaxed ud-text-body-color ud-mb-12
              lg:ud-mb-16
            "
          >
            {{ __("With :appname, you can get Instant and real-time analytics of your website. That is, you can  see the behaviors and the interaction of your customers with your website. They are essential in detecting what does or doesn't work on a website or product page. Therefore, you can reorganize your website or product page. Ultimately, it will help you to assess your product’s performance and promote user engagement.",['appname'=>config('app.name')]) }}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ===== About end ===== -->

<!-- ===== About start ===== -->
<section class="ud-pt-14">
  <div class="ud-container">
    <div class="ud-flex ud-flex-wrap ud-items-center ud--mx-4">
      <div class="ud-w-full lg:ud-w-1/2 ud-px-4">
        <div
          class="ud-text-center ud-mb-14 lg:ud-mb-0 wow fadeInUp"
          data-wow-delay=".2s"
        >
          <img
            src="{{$get_landing_language->details_feature_2_img ?? ''}}"
            alt="image"
            class="ud-max-full"
          />
        </div>
      </div>
      <div class="ud-w-full lg:ud-w-1/2 lg:ud-order-first ud-px-4">
        <div class="ud-max-w-[485px] wow fadeInUp" data-wow-delay=".3s">
          <span
            class="
              ud-font-bold ud-text-base ud-text-primary ud-block ud-mb-2
            "
          >
            {{ __("Easy to Manage :appname",['appname'=>config('app.name')]) }}
          </span>
          <h2
            class="
              ud-font-extrabold ud-text-3xl
              sm:ud-text-4xl
              ud-leading-tight ud-text-black
              dark:ud-text-white
              ud-mb-6
            "
          >
            {{ __("Flourish your business with :appname",['appname'=>config('app.name')]) }}
          </h2>
          <p
            class="
              ud-text-lg ud-leading-relaxed ud-text-body-color ud-mb-12
              lg:ud-mb-16
            "
          >
            {{ __(":appname, a HeatMap and Session Recording Tool, provides product teams, digital marketers and data analysts detailed and profound insights into people's behavior and interaction with their website. As a result, they can discern why customers aren’t choosing their product. Then they can optimize their product and site to promote user engagement and boost sales. And ultimately, their business will flourish.",['appname'=>config('app.name')]) }}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ===== About end ===== -->

<!-- ===== How Works start ===== -->
<section id="how-works" class="ud-pt-[100px]">
  <div class="ud-container">
    <div class="ud-flex ud-justify-center ud--mx-4">
      <div class="ud-w-full ud-px-4">
        <div
          class="
            ud-max-w-[510px] ud-mx-auto ud-text-center ud-mb-[70px]
            wow
            fadeInUp
          "
          data-wow-delay=".2s"
        >
          <h2
            class="
              ud-font-extrabold ud-text-3xl
              sm:ud-text-4xl
              ud-text-black
              dark:ud-text-white
              ud-mb-5
            "
          >
            {{ __("How :appname Works?",['appname'=>config('app.name')]) }}
          </h2>
          <p class="ud-font-semibold ud-text-base ud-text-body-color">
            {{ __("Follow few easy steps to get :appname integrated and working on your website.",["appname"=>config('app.name')]) }}
          </p>
        </div>
      </div>
    </div>

    <div class="ud-flex ud-flex-wrap ud-justify-center ud--mx-4">
      <div class="ud-w-full md:ud-w-1/2 lg:ud-w-1/3 ud-px-4">
        <div
          class="
            ud-mb-12 ud-mx-auto ud-max-w-[310px] ud-text-center ud-group
            wow
            fadeInUp
          "
          data-wow-delay=".2s"
        >
          <div
            class="
              ud-w-20
              ud-h-20
              ud-mx-auto
              ud-rounded-3xl
              ud-bg-primary
              ud-bg-opacity-5
              ud-flex
              ud-items-center
              ud-justify-center
              ud-text-primary
              group-hover:ud-bg-opacity-100 group-hover:ud-text-white
              ud-transition-all ud-mb-5
            "
          >
            <i class="fas fa-user ud-text-3xl"></i>
          </div>
          <h3
            class="
              ud-font-bold ud-text-xl
              sm:ud-text-2xl
              md:ud-text-xl
              lg:ud-text-2xl
              ud-text-black
              dark:ud-text-white
              ud-mb-4
            "
          >
            {{__("Open an Account for FREE")}}
          </h3>
          <p class="ud-font-semibold ud-text-base ud-text-body-color">
            {{__("The basic version is FREE and always will be. Sign up and get access to the :appname now.",["appname"=>config("app.name")])}}
          </p>
        </div>
      </div>
      <div class="ud-w-full md:ud-w-1/2 lg:ud-w-1/3 ud-px-4">
        <div
          class="
            ud-mb-12 ud-mx-auto ud-max-w-[310px] ud-text-center ud-group
            wow
            fadeInUp
          "
          data-wow-delay=".25s"
        >
          <div
            class="
              ud-w-20
              ud-h-20
              ud-mx-auto
              ud-rounded-3xl
              ud-bg-primary
              ud-bg-opacity-5
              ud-flex
              ud-items-center
              ud-justify-center
              ud-text-primary
              group-hover:ud-bg-opacity-100 group-hover:ud-text-white
              ud-transition-all ud-mb-5
            "
          >
            <i class="fas fa-code ud-text-3xl"></i>
          </div>
          <h3
            class="
              ud-font-bold ud-text-xl
              sm:ud-text-2xl
              md:ud-text-xl
              lg:ud-text-2xl
              ud-text-black
              dark:ud-text-white
              ud-mb-4
            "
          >
            {{__("Get JS Embed Code")}}
          </h3>
          <p class="ud-font-semibold ud-text-base ud-text-body-color">
            {{__('Just the domain name for your website and get your JavaScript embed code instantly.')}}
          </p>
        </div>
      </div>
      <div class="ud-w-full md:ud-w-1/2 lg:ud-w-1/3 ud-px-4">
        <div
          class="
            ud-mb-12 ud-mx-auto ud-max-w-[310px] ud-text-center ud-group
            wow
            fadeInUp
          "
          data-wow-delay=".3s"
        >
          <div
            class="
              ud-w-20
              ud-h-20
              ud-mx-auto
              ud-rounded-3xl
              ud-bg-primary
              ud-bg-opacity-5
              ud-flex
              ud-items-center
              ud-justify-center
              ud-text-primary
              group-hover:ud-bg-opacity-100 group-hover:ud-text-white
              ud-transition-all ud-mb-5
            "
          >
            <i class="fas fa-plug ud-text-3xl"></i>
          </div>
          <h3
            class="
              ud-font-bold ud-text-xl
              sm:ud-text-2xl
              md:ud-text-xl
              lg:ud-text-2xl
              ud-text-black
              dark:ud-text-white
              ud-mb-4
            "
          >
            {{__('Integrate the JS code')}}
          </h3>
          <p class="ud-font-semibold ud-text-base ud-text-body-color">
            {{__("Add the JavaScript code inside your website's main html file and you will see it's working!")}}
          </p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ===== How Works end ===== -->

<!-- ===== Call to Action start ===== -->
<section id="cta" class="ud-pt-14">
  <div class="ud-container">
    <div
      class="
        ud-bg-gradient-to-l ud-from-gradient-1 ud-to-gradient-2
        dark:ud-from-[#3c3e56] dark:ud-to-black
        ud-rounded-[20px] ud-px-7
        sm:ud-px-10
        md:ud-px-16
        lg:ud-px-14
        xl:ud-px-16
        wow
        fadeInUp
      "
      data-wow-delay=".2s"
    >
      <div class="ud-flex ud-flex-wrap ud-items-end ud--mx-4">
        <div class="ud-w-full lg:ud-w-1/2 ud-px-4">
          <div class="ud-max-w-[400px] ud-py-16">
            <span
              class="
                ud-font-bold ud-text-base ud-text-primary ud-block ud-mb-2
              "
            >
              {{__('Get Access')}}
            </span>
            <h2
              class="
                ud-font-extrabold ud-text-3xl
                sm:ud-text-4xl
                ud-leading-tight ud-text-black
                dark:ud-text-white
                ud-mb-7
              "
            >
              {{__('Open a Free Account')}}
            </h2>
            <p
              class="
                ud-font-bold
                ud-text-base
                ud-leading-relaxed
                ud-text-body-color
                ud-mb-10
              "
            >
              {{__("The basic version is FREE and always will be. Sign up and get access to the :appname now.",["appname"=>config("app.name")])}}
            </p>
            <div class="ud-flex ud-items-center">
              <a
                href="{{route('register')}}"
                class="
                  ud-flex
                  ud-items-center
                  ud-bg-primary
                  ud-rounded-xl
                  ud-py-3
                  ud-px-3
                  sm:ud-px-4
                  ud-transition-all
                  hover:ud-shadow-primary-hover
                  ud-mr-2
                  sm:ud-mr-5
                "
              >
                <span class="ud-pr-3">
                  <i class="fas fa-user-circle text-white ud-text-4xl"></i>
                </span>
                <span class="ud-font-bold ud-text-white ud-text-lg">
                  <span
                    class="ud-block ud-text-xs ud-text-white ud-opacity-70"
                  >
                    {{__('Open a')}}
                  </span>
                 {{__('FREE Acoount')}}
                </span>
              </a>
            </div>
          </div>
        </div>
        <div class="ud-w-full lg:ud-w-1/2 ud-px-4">
          <div
            class="
              ud-relative ud-w-full ud-flex ud-justify-end ud-items-end
            "
          >
            <div class="ud-w-full">
              <img
                src="{{$get_landing_language->details_feature_3_img ?? ''}}"
                alt="image"
                class="ud-relative ud-z-10 ud-drop-shadow-image"
              />
            </div>
            <div class="ud-w-full ud--ml-8">
              <img
                src="{{$get_landing_language->details_feature_4_img ?? ''}}"
                alt="image"
                class="ud-relative ud-z-0 ud-drop-shadow-image"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ===== Call to Action end ===== -->

 <?php
  $review_found=0;
  $review_str = '';
  for($i=1;$i<=3;$i++):
      $var1 = "review_".$i."_description";
      $var2 = "review_".$i."_avatar";
      $var3 = "review_".$i."_name";
      $var4 = "review_".$i."_designation";
      if(!isset($get_landing_language->$var1) || !isset($get_landing_language->$var2) || !isset($get_landing_language->$var3) || !isset($get_landing_language->$var4)) continue;
      if(empty($get_landing_language->$var1) && empty($get_landing_language->$var2) && empty($get_landing_language->$var3) && empty($get_landing_language->$var4)) continue;
      $review_str .= '

      <div
          class="
            ud-w-full
            md:ud-w-1/2
            lg:ud-w-1/3
            ud-px-4
            lg:ud-px-3
            xl:ud-px-4
          "
        >
          <div
            class="
              ud-bg-white
              dark:ud-bg-dark
              ud-p-10
              lg:ud-py-8 lg:ud-px-5
              xl:ud-p-10
              ud-rounded-[20px]
              ud-rounded-tl-none
              ud-relative
              ud-z-10
              ud-overflow-hidden
              ud-mb-10
              ud-group
              hover:ud-bg-primary
              ud-transition-all ud-shadow-testimonial
              wow
              fadeInUp
            "
            data-wow-delay=".25s"
          >
            <p
              class="
                ud-font-bold ud-text-base ud-text-black
                dark:ud-text-white
                ud-mb-9
                group-hover:ud-text-white
              "
            >
              '.display_landing_content($get_landing_language->$var3).'
            </p>

            <div class="ud-flex ud-items-center">
              </div>
              <div>
                <h3
                  class="
                    ud-font-bold ud-text-sm ud-text-black
                    dark:ud-text-white
                    group-hover:ud-text-white
                  "
                >
                  '.display_landing_content($get_landing_language->$var1).'
                </h3>
                <p class="mt-3">
                <img
                  src="'.display_landing_content($get_landing_language->$var2).'"
                  alt="image"
                  class="rounded"
                  width="40" height="40"
                />
                </p>

                <p
                  class="
                    ud-font-semibold ud-text-xs ud-text-body-color
                    group-hover:ud-text-white group-hover:ud-text-opacity-70 mt-2
                  "
                >
                  '.display_landing_content($get_landing_language->$var4).'
                </p>
              </div>
            </div>

            <span
              class="
                ud-absolute ud-top-0 ud-right-0 ud--z-1 ud-text-primary
                group-hover:ud-text-white
              "
            >
              <img src="'.asset('assets/front/images/svg/client.svg').'">
            </span>
          </div>
        </div>';

      $review_found++;
  endfor;
  ?>

<!-- ===== Testimonial start ===== -->
@if($review_found>0 && $disable_review_section=='0')
<section id="testimonial" class="ud-pt-14">
  <div class="ud-bg-gradient-1 dark:ud-bg-black ud-pt-[100px] ud-pb-[70px]">
    <div class="ud-container">
      <div class="ud-flex ud-justify-center ud--mx-4">
        <div class="ud-w-full ud-px-4">
          <div
            class="
              ud-max-w-[510px] ud-mx-auto ud-text-center ud-mb-[70px]
              wow
              fadeInUp
            "
            data-wow-delay=".2s"
          >
            <h2
              class="
                ud-font-extrabold ud-text-3xl
                sm:ud-text-4xl
                ud-text-black
                dark:ud-text-white
                ud-mb-5
              "
            >
              {{__('What Clients Say?')}}
            </h2>
          </div>
        </div>
      </div>

      <div class="ud-flex ud-flex-wrap ud--mx-4 lg:ud--mx-3 xl:ud--mx-4">
         <?php echo $review_str;?>
      </div>
    </div>
  </div>
</section>
@endif
<!-- ===== Testimonial end ===== -->

<!-- ===== FAQ start ===== -->
<section id="faq" class="ud-py-[100px] ud-pb-[100px] ud-overflow-hidden">
  <div class="ud-container">
    <div class="ud-flex ud-justify-center ud--mx-4">
      <div class="ud-w-full ud-px-4">
        <div
          class="
            ud-max-w-[510px] ud-mx-auto ud-text-center ud-mb-[70px]
            wow
            fadeInUp
          "
          data-wow-delay=".2s"
        >
          <h2
            class="
              ud-font-extrabold ud-text-3xl
              sm:ud-text-4xl
              ud-text-black
              dark:ud-text-white
              ud-mb-5
            "
          >
            {{__('Frequently Asked Questions')}}
          </h2>
          <p class="ud-font-semibold ud-text-base ud-text-body-color">
            {{__('Any Questions! Answered')}}
          </p>
        </div>
      </div>
    </div>

    <div class="ud-flex ud-flex-wrap ud--mx-4">
      <div class="ud-w-full lg:ud-w-1 ud-px-4">
        <div class="ud-mb-6 lg:ud-mb-0 ud-relative ud-z-10">
          <div class="ud-bg-white dark:ud-bg-dark ud-border ud-border-[#e4f2fe] ud-rounded-2xl ud-py-12 ud-px-8 sm:ud-p-12 md:ud-py-14 lg:ud-py-10 lg:ud-px-8 xl:ud-p-12 2xl:ud-p-14 wow fadeInUp" data-wow-delay=".3s">
            <div class="">
              <h3 class="ud-font-bold ud-text-xl sm:ud-text-2xl lg:ud-text-xl xl:ud-text-2xl ud-text-black dark:ud-text-white ud-mb-4">
                {{__('How do heatmap analytics work?')}}
              </h3>
              <p class="ud-font-semibold ud-text-base ud-text-body-color">
                {{__('Heatmap analytics capture information on how people interact with a website, such as what they click, how far visitors scroll, and even where the mouse pointer is placed. A heatmap captures this information and converts it into a multi-colored map of the website that is more easily edible and informative than the data points shown separately.')}}
              </p>
            </div>
          </div>
          <div class="ud-absolute ud--z-1 ud--left-16 ud-bottom-4">
            <img src="{{ asset('assets/front/images/svg/faq.svg') }}" alt="">
          </div>
        </div>
        <div class="ud-mb-6 lg:ud-mb-0 ud-relative ud-z-10">
          <div class="ud-bg-white dark:ud-bg-dark ud-border ud-border-[#e4f2fe] ud-rounded-2xl ud-py-12 ud-px-8 sm:ud-p-12 md:ud-py-14 lg:ud-py-10 lg:ud-px-8 xl:ud-p-12 2xl:ud-p-14 wow fadeInUp" data-wow-delay=".3s">
            <div class="">
              <h3 class="ud-font-bold ud-text-xl sm:ud-text-2xl lg:ud-text-xl xl:ud-text-2xl ud-text-black dark:ud-text-white ud-mb-4">
                {{__('What are the advantages of using heatmap analytics?')}}
              </h3>
              <p class="ud-font-semibold ud-text-base ud-text-body-color">
                {{__('Analytics systems like as Google Analytics or Site Catalyst are excellent at delivering analytics to indicate which pages visitors view, but they might be lacking in detail when it comes to knowing how those sites are interacted with. Heatmaps may provide a more detailed picture of how people are actually behaving.')}}
                {{__('Heatmaps are also far more visually appealing than traditional analytics reports, making them simpler to understand at a glance. This makes them simpler, especially to those who are unfamiliar with processing big volumes of data.')}}
              </p>
            </div>
          </div>
          <div class="ud-absolute ud--z-1 ud--left-16 ud-bottom-4">
            <img src="{{ asset('assets/front/images/svg/faq.svg') }}" alt="">
          </div>
        </div>
        <div class="ud-mb-6 lg:ud-mb-0 ud-relative ud-z-10">
          <div class="ud-bg-white dark:ud-bg-dark ud-border ud-border-[#e4f2fe] ud-rounded-2xl ud-py-12 ud-px-8 sm:ud-p-12 md:ud-py-14 lg:ud-py-10 lg:ud-px-8 xl:ud-p-12 2xl:ud-p-14 wow fadeInUp" data-wow-delay=".3s">
            <div class="">
              <h3 class="ud-font-bold ud-text-xl sm:ud-text-2xl lg:ud-text-xl xl:ud-text-2xl ud-text-black dark:ud-text-white ud-mb-4">
                {{__('What is session recording?')}}
              </h3>
              <p class="ud-font-semibold ud-text-base ud-text-body-color">
                {{__("Another useful feature is session recording. This program merely records a user's browsing activity as video footage. This helps to clarify some of the information obtained from a heatmap.")}}
              </p>
            </div>
          </div>
          <div class="ud-absolute ud--z-1 ud--left-16 ud-bottom-4">
            <img src="{{ asset('assets/front/images/svg/faq.svg') }}" alt="">
          </div>
        </div>
        <div class="ud-mb-6 lg:ud-mb-0 ud-relative ud-z-10">
          <div class="ud-bg-white dark:ud-bg-dark ud-border ud-border-[#e4f2fe] ud-rounded-2xl ud-py-12 ud-px-8 sm:ud-p-12 md:ud-py-14 lg:ud-py-10 lg:ud-px-8 xl:ud-p-12 2xl:ud-p-14 wow fadeInUp" data-wow-delay=".3s">
            <div class="">
              <h3 class="ud-font-bold ud-text-xl sm:ud-text-2xl lg:ud-text-xl xl:ud-text-2xl ud-text-black dark:ud-text-white ud-mb-4">
                {{__('Why should you use heatmap and session recording?')}}
              </h3>
              <p class="ud-font-semibold ud-text-base ud-text-body-color">
                {{__('Heatmap and session recordings answer the questions of your business, it helps you to understand the behavior of the user on your websites. This tool is for you whether you are a UI designer, UX analyst, product manager, marketer, or business owner. A heatmap report is visual and could be reviewed together by your team members.')}}
              </p>
            </div>
          </div>
          <div class="ud-absolute ud--z-1 ud--left-16 ud-bottom-4">
            <img src="{{ asset('assets/front/images/svg/faq.svg') }}" alt="">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- ===== FAQ end ===== -->


<!-- ===== Contact start ===== -->
<section id="contact" class="ud-py-[100px] ud-overflow-hidden ud-bg-gradient-1 dark:ud-bg-black">
  <div class="ud-container">
    <div class="ud-flex ud-justify-center ud--mx-4">
      <div class="ud-w-full ud-px-4">
        <div
          class="
            ud-max-w-[510px] ud-mx-auto ud-text-center ud-mb-[70px]
            wow
            fadeInUp
          "
          data-wow-delay=".2s"
        >
          <h2
            class="
              ud-font-extrabold ud-text-3xl
              sm:ud-text-4xl
              ud-text-black
              dark:ud-text-white
              ud-mb-5
            "
          >
            {{__('Contact Us')}}
          </h2>
          <p class="ud-font-semibold ud-text-base ud-text-body-color">
            {{ __('Let’s talk about :appname',['appname'=>config('app.name')]) }} <br />
            {{ __('Love to hear from you!') }}
          </p>
        </div>
      </div>
    </div>

    <div
      class="
        ud-max-w-[770px]
        ud-mx-auto
        ud-shadow-faq
        ud-rounded-2xl
        ud-relative
        ud-bg-white
        dark:ud-bg-dark
        ud-z-10
        wow
        fadeInUp
      "
      data-wow-delay=".3s"
    >
      <div>
        <span class="ud-absolute ud-bottom-8 ud--left-14 ud--z-1">
          <img src="{{ asset('assets/front/images/svg/contact.svg') }}" alt="">
        </span>
        <span class="ud-absolute ud--top-8 ud--right-14 ud--z-1">
          <img src="{{ asset('assets/front/images/svg/contact_2.svg') }}" alt="">
        </span>
      </div>
    </div>
  </div>
</section>
<!-- ===== Contact end ===== -->

<!-- ===== Contact start ===== -->
<section id="contact2" class="ud-py-[100px] ud-overflow-hidden">
  <div class="ud-container px-5 mx-5">
    <div class="ud-contact-info-wrapper">
        <div class="ud-single-info">
            <div class="ud-info-icon">
                <i class="lni lni-map-marker"></i>
            </div>
            <div class="ud-info-meta">
                <h5 class="dark:ud-text-white">{{__('Our Location')}}</h5>
                <p class="dark:ud-text-white">{!! display_landing_content($get_landing_language->company_address ?? '') !!}</p>
            </div>
        </div>
        <div class="ud-single-info">
            <div class="ud-info-icon">
                <i class="far fa-comment-alt"></i>
            </div>
            <div class="ud-info-meta">
                <h5 class="dark:ud-text-white">{{__('Live Chat')}}</h5>
                <p class="dark:ud-text-white"><i class="fab fa-facebook"></i> <a class="dark:ud-text-white" href="{{$get_landing_language->company_fb_messenger??''}}" target="_BLANK">{{__('Facebook Messenger')}}</a></p>
                <p class="dark:ud-text-white"><i class="fab fa-telegram"></i> <a class="dark:ud-text-white" href="{{$get_landing_language->company_telegram_bot??''}}" target="_BLANK">{{__('Telegram Bot')}}</a></p>
            </div>
        </div>
        
    </div>
  </div>
</section>
<!-- ===== Contact end ===== -->
@endsection

@section('modal')
    <?php if(session('allow_cookie')!='yes') : ?>
    <div class="text-center cookiealert">
        <div class="cookiealert-container">
            <a class="cookie_content_css" href="{{display_landing_content($get_landing_language->links_privacy_policy_url ?? route('policy-privacy'))}}">
                {{__('This site requires cookies in order for us to provide proper service to you')}}
            </a>
            <a type="button" href="#" class="btn bg-light btn-sm acceptcookies text-dark ms-2 hover:ud-text-dark" aria-label="Close">
                <i class="fas fa-check-circle"></i> {{__('Accept')}}
            </a>
        </div>
    </div>
    <?php endif; ?> <!--====== FOOTER PART ENDS ======-->
@endsection

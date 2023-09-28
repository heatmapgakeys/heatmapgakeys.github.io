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

<!-- ====== Banner Start ====== -->
<section
  class="
    ud-relative
    ud-z-10
    ud-py-16
    ud-bg-gradient-to-l
    ud-from-gradient-1
    ud-to-gradient-2
    dark:ud-from-[#3c3e56] dark:ud-to-black
    ud-overflow-hidden
    wow
    fadeInUp
  "
  data-wow-delay=".2s"
>
  <div class="ud-container">
    <div class="ud-max-w-[570px] ud-mx-auto ud-text-center">
      <h1
        class="
          ud-font-extrabold ud-text-black
          dark:ud-text-white
          ud-text-4xl
          md:ud-text-[45px]
          ud-leading-tight
          md:ud-leading-tight
          ud-mb-5
        "
      >
        {{$title}}
      </h1>
      <ul class="ud-flex ud-items-center ud-justify-center">
        <li class="ud-flex ud-items-center">
          <a
            href="{{ url('') }}"
            class="
              ud-font-semibold ud-text-base ud-text-body-color
              hover:ud-text-primary
            "
          >
           {{ __("Home") }}
          </a>
          <span
            class="ud-font-semibold ud-text-base ud-text-body-color ud-px-2"
          >
            /
          </span>
        </li>

        <li class="ud-font-semibold ud-text-base ud-text-primary">
          {{$title}}
        </li>
      </ul>
    </div>
  </div>
 </section>
<!-- ====== Banner End ====== -->

<!-- ====== About Start ====== -->
<section id="about" class="ud-about">
  <div class="container">
    <div class="ud-about-wrapper wow fadeInUp" data-wow-delay=".2s">
      <div class="ud-about-content-wrapper">
          <h4 class="my-4">{{__('Application is not as described')}}</h4>
          <p>
            {{__('An application is "not as described" if it is materially different from the application description or preview so be sure to "tell it like it is" when it comes to the features and functionality of items. If it turns out the application is "not as described" we are obligated to refund buyers of that item.')}}
          </p>

          <h4 class="my-4">{{__('Application doesn`t work the way it should')}}</h4>
          <p>{{__('If an application doesn`t work the way it should and can`t easily be fixed we are obligated to refund buyers of the application. This includes situations where application has a problem that would have stopped a buyer from buying it if they`d known about the problem in the first place. If the application can be fixed, then we do so promptly by updating our application otherwise we are obligated to refund buyers of that application.')}}
          </p>

          <h4 class="my-4">{{__('Application has a security vulnerability')}}</h4>
          <p>{{__('If an application contains a security vulnerability and can`t easily be fixed we are obligated to refund buyers of the application. If the application can be fixed, then we do so promptly by updating our application. If our application contains a security vulnerability that is not patched in an appropriate timeframe then we are obligated to refund buyers of that application.')}}</p>

          <h4 class="my-4">{{__('Application support is promised but not provided')}}</h4>
          <p>{{__('If we promise our buyers application support and we do not provide that support in accordance with the application support policy we are obligated to refund buyers who have purchased support.')}}</p>

          <h4 class="my-4">{{__('No refund scenario')}}</h4>
          <p>{{__('If our application is materially similar to the description and preview and works the way it should, there is generally no obligation to provide a refund in situations like the following:')}}

          <ul id="ul_id" >
            <li>{{__('Buyer doesn`t want it after they`ve purchase it.')}}</li>
            <li>{{__('The application did not meet the their expectations.')}}</li>
            <li>{{__('Buyer is not satisfied with the current feature availability of the service.')}}</li>
            <li>{{__('Buyer simply change their mind.')}}</li>
            <li>{{__('Buyer bought a service by mistake.')}}</li>
            <li>{{__('Buyer do not have sufficient expertise to use the application.')}}</li>
            <li>{{__('Buyer ask for goodwill.')}}</li>
            <li>{{__('Problems originated from the API providing organization.')}}</li>
            <li>{{__('No refund will be provided after 30 days from the purchase of a service.')}}</li>
          </ul>

        </p>

        <h4 class="my-4">{{__('Force Refund')}}</h4>
        <p>{{__('We hold the authority to refund buyer purchase by force without any request from buyer end. Force refund will stop app access as well as support access by denying purchase code with immediate action.')}}</p>

        <h4 class="my-4">{{__('Refund Request')}}</h4>
        <p>{{__('If a buyer eligible to get a refund then he/she must open a support ticket.')}}</p>

      </div>
    </div>
  </div>
</section>
<!-- ====== About End ====== -->

@endsection


@push('styles-footer')
<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

@endpush

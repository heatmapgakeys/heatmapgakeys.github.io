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
<!-- ====== Banner Start ====== -->
<section id="pricing" class="ud-pt-[100px]">
  <div class="ud-container">
    <div class="ud-flex ud-justify-center ud--mx-4">
      <div class="ud-w-full ud-px-4">
        <div
          class="
            ud-max-w-[510px] ud-mx-auto ud-text-center mb-3
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
            {{__('Our Pricing Plans')}}
          </h2>
          <p class="ud-font-semibold ud-text-base ud-text-body-color">
            {{ __('Create a FREE account today to get access to everything you need to succeed in ') }} {{config('app.name')}}
          </p>
        </div>
      </div>
    </div>

    <!-- ====== Pricing Start ====== -->
    @include('front.partials.show-pricing')
    <!-- ====== Pricing End ====== -->

  </div>
</section>
<!-- ====== Banner End ====== -->

@endsection


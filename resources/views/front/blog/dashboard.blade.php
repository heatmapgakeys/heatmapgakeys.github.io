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

 <section class=" ud-relative ud-z-10 ud-py-16 ud-bg-gradient-to-l ud-from-gradient-1 ud-to-gradient-2 dark:ud-from-[#3c3e56] dark:ud-to-black ud-overflow-hidden wow fadeInUp mb-5" data-wow-delay=".2s">
   <div class="ud-container">
     <div class="ud-max-w-[570px] ud-mx-auto ud-text-center">
       <h1 class=" ud-font-extrabold ud-text-black dark:ud-text-white ud-text-4xl md:ud-text-[45px] ud-leading-tight md:ud-leading-tight ud-mb-5">
         {{$title}}
       </h1>
       <ul class="ud-flex ud-items-center ud-justify-center">
         <li class="ud-flex ud-items-center">
           <a href="{{ url('') }}" class="ud-font-semibold ud-text-base ud-text-body-color hover:ud-text-primary">
            {{ __("Home") }}
           </a>
           <span class="ud-font-semibold ud-text-base ud-text-body-color ud-px-2">/</span>
         </li>

         <li class="ud-font-semibold ud-text-base ud-text-primary">{{ $title }}</li>
       </ul>
     </div>
   </div>
 </section>
 <!-- ====== Banner End ====== -->

   <!-- ====== Blog Details Start ====== -->
   <section class="ud-blog-details pt-5">
       <div class="container">
           <div class="row">

               <div class="col-lg-9">
                   <div class="ud-blog-details-content pt-0">

                       <div class="ud-articles-box">
                           <h3 class="ud-articles-box-title">{{__('Unread Comments')}} ({{$total_comment}})</h3>
                           @include('front.blog.partials.show-comment')
                       </div>

                   </div>
               </div>
               <div class="col-lg-3">
                   <div class="ud-blog-sidebar pt-0">
                       @include('front.blog.partials.popular-blog')
                   </div>
               </div>
           </div>

       </div>
   </section>
   <!-- ====== Blog Details End ====== -->
@endsection
@section('modal')
    @include('front.blog.partials.modal-comment')
@endsection
@push('scripts-footer')
    @if(Auth::user())
        <script src="{{asset('assets/js/pages/blog/comment-reply.js')}}"></script>
    @endif
    @if(!$is_user)
        <script src="{{asset('assets/js/pages/blog/comment.hide-seen.js')}}"></script>
    @endif
    @if($is_admin)
        <script src="{{asset('assets/js/pages/blog/blog.delete.js')}}"></script>
    @endif
@endpush

@push('styles-footer')
<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">
@endpush

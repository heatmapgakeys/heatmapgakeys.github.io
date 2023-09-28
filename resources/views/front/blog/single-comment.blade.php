@extends('layouts.front')
@section('title',$title.' : '.__('Blog Comment'))
@section('meta_title',$meta_title)
@section('meta_description',$meta_description)
@section('meta_keyword',$meta_keyword)
@section('meta_author',$meta_author)
@section('meta_image',$meta_image)
@section('meta_image_width',$meta_image_width)
@section('meta_image_height',$meta_image_height)
@section('content')

   <!-- ====== Banner Start ====== -->
    <section class="ud-page-banner">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="ud-banner-content">
              <h1>{{__('Comment')}} {{$title}} @ <a class="text-white" href="{{route('single-blog',$parent_comment_info->first()->blog_id)}}">{{$parent_comment_info->first()->blog_title}}</a></h1>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ====== Banner End ====== -->

   <!-- ====== Blog Details Start ====== -->
   <section class="ud-blog-details">
       <div class="container">
           <div class="row">

               <div class="col-lg-8">
                   <div class="ud-blog-details-content pt-0">

                       <div class="ud-articles-box">
                           <h3 class="ud-articles-box-title">{{__('Comments')}} ({{$total_comment}})</h3>
                           @include('front.blog.partials.show-comment')
                       </div>

                   </div>
               </div>
               <div class="col-lg-4">
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

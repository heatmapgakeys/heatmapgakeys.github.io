@extends('layouts.front')
@section('title',$title.' : '.__('Blog'))
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

   <!-- ===== Blog start ===== -->
   <?php 
        $author_img = !empty($blog_info->author_img) ? $blog_info->author_img :  asset('assets/images/favicon.png');
        if(empty($blog_info->author_name)) $blog_info->author_name = config('app.name');
        $blog_img = !empty($blog_info->blog_img) ? $blog_info->blog_img :  asset('assets/front/images/blog/sample-blog.jpg');
        if(empty($blog_info->category_name)) $blog_info->category_name = __('Uncategorized');
        $share_current_url = url()->current();

    ?>
   <section id="blog" class="ud-py-20 2xl:ud-py-[100px]">
     <div class="ud-container">
       <div class="ud-flex ud-flex-wrap ud-justify-center ud--mx-4">
         <div class="ud-w-full lg:ud-w-10/12 ud-px-4">
           <div
             class="
               ud-h-[350px]
               md:ud-h-[430px]
               ud-relative ud-overflow-hidden ud-rounded-xl ud-mb-8
               wow
               fadeInUp
             "
             data-wow-delay=".2s"
           >
             <img
               src="{{ $blog_img }}"
               alt="image"
               class="ud-w-full ud-h-full ud-object-cover ud-object-center"
             />
             <div
               class=" ud-absolute ud-w-full ud-h-full ud-top-0 ud-left-0 ud-flex ud-items-end ud-bg-gradient-to-t ud-from-black ud-to-transparent">
               <div class="ud-p-8 ud-w-full">
                 <h3 class=" ud-font-extrabold ud-text-xl ud-text-white ud-mb-2 ud-block">
                   {{$title}}
                 </h3>

                 <div class="ud-flex ud-flex-wrap ud-items-center">
                   <div class="ud-flex ud-items-center ud-mr-5">
                     <div
                       class="
                         ud-max-w-[40px]
                         ud-w-full
                         ud-h-[40px]
                         ud-rounded-full
                         ud-overflow-hidden
                         ud-mr-4
                       "
                     >
                       <img
                         src="{{ $author_img }}"
                         alt="author"
                         class="ud-w-full"
                       />
                     </div>
                     <div class="ud-w-full">
                       <h4
                         class="
                           ud-text-base ud-font-medium ud-text-white ud-mb-1
                         "
                       >
                         By
                         <a
                           href="javascript:void(0)"
                           class="hover:ud-text-primary"
                         >
                           {{ $blog_info->author_name }}
                         </a>
                       </h4>
                     </div>
                   </div>
                   <div class="ud-flex ud-items-center">
                     <p
                       class="
                         ud-flex
                         ud-items-center
                         ud-text-base
                         ud-text-white
                         ud-font-medium
                         ud-mr-5
                       "
                     >
                       <span class="ud-mr-3">
                         <svg
                           width="15"
                           height="15"
                           viewBox="0 0 15 15"
                           class="ud-fill-current"
                         >
                           <path
                             d="M3.89531 8.67529H3.10666C2.96327 8.67529 2.86768 8.77089 2.86768 8.91428V9.67904C2.86768 9.82243 2.96327 9.91802 3.10666 9.91802H3.89531C4.03871 9.91802 4.1343 9.82243 4.1343 9.67904V8.91428C4.1343 8.77089 4.03871 8.67529 3.89531 8.67529Z"
                           />
                           <path
                             d="M6.429 8.67529H5.64035C5.49696 8.67529 5.40137 8.77089 5.40137 8.91428V9.67904C5.40137 9.82243 5.49696 9.91802 5.64035 9.91802H6.429C6.57239 9.91802 6.66799 9.82243 6.66799 9.67904V8.91428C6.66799 8.77089 6.5485 8.67529 6.429 8.67529Z"
                           />
                           <path
                             d="M8.93828 8.67529H8.14963C8.00624 8.67529 7.91064 8.77089 7.91064 8.91428V9.67904C7.91064 9.82243 8.00624 9.91802 8.14963 9.91802H8.93828C9.08167 9.91802 9.17727 9.82243 9.17727 9.67904V8.91428C9.17727 8.77089 9.08167 8.67529 8.93828 8.67529Z"
                           />
                           <path
                             d="M11.4715 8.67529H10.6828C10.5394 8.67529 10.4438 8.77089 10.4438 8.91428V9.67904C10.4438 9.82243 10.5394 9.91802 10.6828 9.91802H11.4715C11.6149 9.91802 11.7105 9.82243 11.7105 9.67904V8.91428C11.7105 8.77089 11.591 8.67529 11.4715 8.67529Z"
                           />
                           <path
                             d="M3.89531 11.1606H3.10666C2.96327 11.1606 2.86768 11.2562 2.86768 11.3996V12.1644C2.86768 12.3078 2.96327 12.4034 3.10666 12.4034H3.89531C4.03871 12.4034 4.1343 12.3078 4.1343 12.1644V11.3996C4.1343 11.2562 4.03871 11.1606 3.89531 11.1606Z"
                           />
                           <path
                             d="M6.429 11.1606H5.64035C5.49696 11.1606 5.40137 11.2562 5.40137 11.3996V12.1644C5.40137 12.3078 5.49696 12.4034 5.64035 12.4034H6.429C6.57239 12.4034 6.66799 12.3078 6.66799 12.1644V11.3996C6.66799 11.2562 6.5485 11.1606 6.429 11.1606Z"
                           />
                           <path
                             d="M8.93828 11.1606H8.14963C8.00624 11.1606 7.91064 11.2562 7.91064 11.3996V12.1644C7.91064 12.3078 8.00624 12.4034 8.14963 12.4034H8.93828C9.08167 12.4034 9.17727 12.3078 9.17727 12.1644V11.3996C9.17727 11.2562 9.08167 11.1606 8.93828 11.1606Z"
                           />
                           <path
                             d="M11.4715 11.1606H10.6828C10.5394 11.1606 10.4438 11.2562 10.4438 11.3996V12.1644C10.4438 12.3078 10.5394 12.4034 10.6828 12.4034H11.4715C11.6149 12.4034 11.7105 12.3078 11.7105 12.1644V11.3996C11.7105 11.2562 11.591 11.1606 11.4715 11.1606Z"
                           />
                           <path
                             d="M13.2637 3.3697H7.64754V2.58105C8.19721 2.43765 8.62738 1.91189 8.62738 1.31442C8.62738 0.597464 8.02992 0 7.28906 0C6.54821 0 5.95074 0.597464 5.95074 1.31442C5.95074 1.91189 6.35702 2.41376 6.93058 2.58105V3.3697H1.31442C0.597464 3.3697 0 3.96716 0 4.68412V13.2637C0 13.9807 0.597464 14.5781 1.31442 14.5781H13.2637C13.9807 14.5781 14.5781 13.9807 14.5781 13.2637V4.68412C14.5781 3.96716 13.9807 3.3697 13.2637 3.3697ZM6.6677 1.31442C6.6677 0.979841 6.93058 0.716957 7.28906 0.716957C7.62364 0.716957 7.91042 0.979841 7.91042 1.31442C7.91042 1.649 7.64754 1.91189 7.28906 1.91189C6.95448 1.91189 6.6677 1.6251 6.6677 1.31442ZM1.31442 4.08665H13.2637C13.5983 4.08665 13.8612 4.34954 13.8612 4.68412V6.45261H0.716957V4.68412C0.716957 4.34954 0.979841 4.08665 1.31442 4.08665ZM13.2637 13.8612H1.31442C0.979841 13.8612 0.716957 13.5983 0.716957 13.2637V7.16957H13.8612V13.2637C13.8612 13.5983 13.5983 13.8612 13.2637 13.8612Z"
                           />
                         </svg>
                       </span>
                       {{date('j M Y',strtotime($blog_info->updated_at))}}
                     </p>
                     <p
                       class="
                         ud-flex
                         ud-items-center
                         ud-text-base
                         ud-text-white
                         ud-font-medium
                       "
                     >
                       <span class="ud-mr-3">
                         <svg
                           width="20"
                           height="12"
                           viewBox="0 0 20 12"
                           class="ud-fill-current"
                         >
                           <path
                             d="M10.2559 3.8125C9.03711 3.8125 8.06836 4.8125 8.06836 6C8.06836 7.1875 9.06836 8.1875 10.2559 8.1875C11.4434 8.1875 12.4434 7.1875 12.4434 6C12.4434 4.8125 11.4746 3.8125 10.2559 3.8125ZM10.2559 7.09375C9.66211 7.09375 9.16211 6.59375 9.16211 6C9.16211 5.40625 9.66211 4.90625 10.2559 4.90625C10.8496 4.90625 11.3496 5.40625 11.3496 6C11.3496 6.59375 10.8496 7.09375 10.2559 7.09375Z"
                           />
                           <path
                             d="M19.7559 5.625C17.6934 2.375 14.1309 0.4375 10.2559 0.4375C6.38086 0.4375 2.81836 2.375 0.755859 5.625C0.630859 5.84375 0.630859 6.125 0.755859 6.34375C2.81836 9.59375 6.38086 11.5312 10.2559 11.5312C14.1309 11.5312 17.6934 9.59375 19.7559 6.34375C19.9121 6.125 19.9121 5.84375 19.7559 5.625ZM10.2559 10.4375C6.84961 10.4375 3.69336 8.78125 1.81836 5.96875C3.69336 3.1875 6.84961 1.53125 10.2559 1.53125C13.6621 1.53125 16.8184 3.1875 18.6934 5.96875C16.8184 8.78125 13.6621 10.4375 10.2559 10.4375Z"
                           />
                         </svg>
                       </span>
                       {{$blog_info->view_count}}
                     </p>
                     <p class="view ms-3">
                         @if($is_admin || ($is_manager && $parent_user_id==1) || ($blog_info->user_id==$user_id))
                             <a class="btn btn-sm btn-light" href="{{route('update-blog',$blog_info->id)}}">
                                 <i class="fas fa-edit"></i> {{__('Edit')}}
                             </a>
                         @endif
                         @if($is_admin)
                             <a class="btn btn-sm btn-danger ms-3" data-id="{{$blog_info->id}}" id="delete_blog" href="{{route('delete-blog')}}">
                                 <i class="fas fa-trash"></i> {{__('Delete')}}
                             </a>
                         @endif
                     </p>
                   </div>
                 </div>
               </div>
             </div>
           </div>
         </div>

         <div class="ud-w-full lg:ud-w-10/12 ud-px-4 flex flex-row">
            <div class="ud-w-full lg:ud-w-8/12 ud-px-4 pt-5 blog-details-container" id="ud-w-full_id">
                <div class="mb-4">
                    <?php echo nl2br($blog_info->blog_content);?>
                </div>

                <div class="sm:ud-flex ud-items-center ud-justify-between wow fadeInUp" data-wow-delay=".2s">
                  <div class="ud-mb-5">
                    <h5 class="ud-font-semibold ud-text-body-color ud-text-base ud-mb-3">
                      {{ __("Tags") }} :
                    </h5>
                    <div class="ud-flex ud-items-center">
                      <a href="javascript:void(0)" class=" ud-inline-flex ud-items-center ud-justify-center ud-py-[6px] ud-px-[18px] ud-mr-4 ud-rounded-md ud-bg-primary ud-bg-opacity-10 ud-text-body-color hover:ud-bg-opacity-100 hover:ud-text-white ud-transition ud-font-semibold ud-text-sm">
                        {{$blog_info->category_name}}
                      </a>
                    </div>
                  </div>
                  <div class="ud-mb-5">
                    <h5 class="ud-font-semibold ud-text-body-color ud-text-base sm:ud-text-right ud-mb-3">
                      {{ __("Share this post") }} :
                    </h5>
                    <div class="ud-flex ud-items-center sm:ud-justify-end">
                        <a target="_BLANK" href="https://t.me/share/url?url={{$share_current_url}}&text={{$blog_info->blog_title}}" class="ud-inline-flex ud-items-center ud-justify-center ud-w-9 ud-h-9 ud-ml-3 ud-rounded-md ud-bg-primary ud-bg-opacity-10 ud-text-body-color hover:ud-bg-opacity-100 hover:ud-text-white ud-transition" name="share" aria-label="share">
                          <i class="fas fa-paper-plane small"></i>
                        </a>
                        <a target="_BLANK" href="https://www.facebook.com/sharer.php?u=<?php echo $share_current_url;  ?>" class="ud-inline-flex ud-items-center ud-justify-center ud-w-9 ud-h-9 ud-ml-3 ud-rounded-md ud-bg-primary ud-bg-opacity-10 ud-text-body-color hover:ud-bg-opacity-100 hover:ud-text-white ud-transition" name="share" aria-label="share">
                            <i class="lni lni-facebook-filled"></i>
                        </a>
                        <a target="_BLANK" href="https://twitter.com/share?url=<?php echo $share_current_url;  ?>" class="ud-inline-flex ud-items-center ud-justify-center ud-w-9 ud-h-9 ud-ml-3 ud-rounded-md ud-bg-primary ud-bg-opacity-10 ud-text-body-color hover:ud-bg-opacity-100 hover:ud-text-white ud-transition" name="share" aria-label="share">
                            <i class="lni lni-twitter-filled"></i>
                        </a>
                        <a target="_BLANK" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo $share_current_url;  ?>" class="ud-inline-flex ud-items-center ud-justify-center ud-w-9 ud-h-9 ud-ml-3 ud-rounded-md ud-bg-primary ud-bg-opacity-10 ud-text-body-color hover:ud-bg-opacity-100 hover:ud-text-white ud-transition" name="share" aria-label="share">
                            <i class="lni lni-linkedin-original"></i>
                        </a>
                    </div>
                  </div>
                </div>

                <div class="clearfix"></div>

                @if(Auth::user() && $enable_blog_comment == '1')
                    <div class="form-group my-4" id="leave_comment">
                        <input type="hidden" name="blog_id" class="blog_id" value="{{$blog_info->id}}">
                        <input type="hidden" name="parent_commenter_id" class="parent_commenter_id" value="0">
                        <input name="parent_blog_comment_id" class="parent_blog_comment_id" type="hidden" value="0">
                        <textarea class="form-control p-3 comment" placeholder="{{__('Leave a comment...')}}"></textarea>
                        <a href="#" class="ud-text-base ud-font-medium ud-text-white ud-bg-primary ud-rounded-lg ud-py-3 ud-px-7 ud-transition-all hover:ud-shadow-primary-hover mt-3">{{__('Leave a Comment')}}</a>
                    </div>
                @endif

                <div class="ud-articles-box mt-4 pt-4">
                    <h3 class="ud-articles-box-title">{{__('Comments')}} ({{$total_comment}})</h3>
                    @include('front.blog.partials.show-comment')
                </div>
            </div>
            <div class="ud-w-full lg:ud-w-4/12 ms-5 pt-5">
                <div>
                    @include('front.blog.partials.popular-blog')
                </div>
            </div>
         </div>
       </div>
     </div>
   </section>
   <!-- ===== Blog end ===== -->



   <!-- ====== Blog Start ====== -->
   @if($related_blog_info->first())
   <section class="ud-blog-grids ud-related-articles">
       <div class="container">
           <div class="row col-lg-12">
               <div class="ud-related-title">
                   <h2 class="ud-related-articles-title">{{__('Related Articles')}}</h2>
               </div>
           </div>
           <div class="row">
               @foreach($related_blog_info as $related_key=>$related_value)
               <div class="col-lg-4 col-md-6">
                   <div class="ud-single-blog border rounded">
                       <div class="ud-blog-image mb-2">
                           <?php $blog_thumb = !empty($related_value->blog_img) ? $related_value->blog_img : '';?>
                           @if(!empty($blog_thumb))
                           <a href="{{route('single-blog',$related_value->blog_slug)}}">
                               <img src="{{$blog_thumb}}" alt="blog"/>
                           </a>
                           @endif
                       </div>
                       <div class="ud-blog-content p-3">
                           <h5>
                               <a class="text-dark" href="{{route('single-blog',$related_value->blog_slug)}}">
                                   {{$related_value->blog_title}}
                               </a>
                           </h5>
                           <p class="mt-2 small text-justify">
                               <?php
                               $blog_content = substr(trim(strip_tags($related_value->blog_content)),0,120).'...';
                               echo $blog_content?>
                           </p>
                           <span class="ud-blog-date bg-white border border-primary text-primary mt-3 mb-0">{{date('j M Y',strtotime($related_value->updated_at))}}</span>
                       </div>
                   </div>
               </div>
               @endforeach
           </div>
       </div>
   </section>
   @endif
@endsection
@section('modal')
    @include('front.blog.partials.modal-comment')
@endsection
@push('scripts-footer')
    <script src="{{ asset('assets/heatmap/js/blog/single-blog.js') }}"></script>
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

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
   <section class="ud-relative ud-z-10 ud-py-16 ud-bg-gradient-to-l ud-from-gradient-1 ud-to-gradient-2 dark:ud-from-[#3c3e56] dark:ud-to-black ud-overflow-hidden wow fadeInUp mb-5" data-wow-delay=".2s">
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
           Blog
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
             {{ __("Blog") }}
           </li>
         </ul>
       </div>
     </div>
    </section>
    <!-- ====== Banner End ====== -->

    <!-- ====== Blog Start ====== -->
    <section id="blog" class="pt-5">
      <div class="ud-container">
        <div class="row">
          <div class="col-12 col-md-6 col-lg-7">
              @if($is_admin || $is_manager || $is_team)
              <?php echo'<a href="'.route('create-blog').'" class="ud-text-base ud-font-medium ud-text-white ud-bg-primary ud-rounded-lg ud-py-3 ud-px-7 ud-transition-all hover:ud-shadow-primary-hover">'.__('New Blog').'</a>';?>
              @endif
          </div>
          <div class="col-12 col-md-6 col-lg-5">
              <form action="{{route('list-blog')}}" method="post">
                  @csrf
                  <div class="input-group mb-3">
                      <input type="text" class="form-control" placeholder="{{__('Search')}}" name="search" value="{{session('blog_seacrh_param')}}">
                      <input type="submit" class="btn btn-md btn-outline-primary" value="{{__('Search')}}...">
                  </div>
              </form>
          </div>
          <div class="col-12">
              @if (session('save_blog_status')=='1')
                  <div class="alert alert-success">
                      <h4 class="alert-heading">{{__('Successful')}}</h4>
                      <p> {{ __('Blog has been saved successfully.') }}</p>
                  </div>
              @elseif (session('save_blog_status')=='0')
                  <div class="alert alert-danger">
                      <h4 class="alert-heading">{{__('Failed')}}</h4>
                      <p> {{ __('Something went wrong. Failed to save blog.') }}</p>
                  </div>
              @endif
          </div>
        </div>
      </div>
    </section>
    <!-- ====== Blog End ====== -->


    <section id="blog" class="pt-5">
      <div class="ud-container">
        <div class="ud-flex ud-flex-wrap ud--mx-4">
          @php($i=0)
          @foreach($blog_list as $key=>$value)
          <?php $blog_img = !empty($value->blog_img) ? $value->blog_img :  asset('assets/front/images/blog/sample-blog.jpg'); ?>
            <div class="ud-w-full lg:ud-w-1/3 ud-px-4">
              <div class="ud-h-[350px] ud-relative ud-overflow-hidden ud-rounded-xl ud-mb-8 wow fadeInUp" data-wow-delay=".2s">
                <img src="{{$blog_img}}" alt="image" class="ud-w-full ud-h-full ud-object-cover ud-object-center"/>
                <div class="ud-absolute ud-w-full ud-h-full ud-top-0 ud-left-0 ud-flex ud-items-end ud-bg-gradient-to-t ud-from-black ud-to-transparent blog-short-body" block-number="{{ $i; }}">
                  <div class=" ud-p-8 ud-w-full ud-space-y-4 ud-flex ud-items-end ud-justify-between">
                    <div class="ud-max-w-[410px] w-full">
                      <h3>
                        <a href="{{route('single-blog',$value->blog_slug)}}"  class="blog-url{{ $i; }} ud-font-extrabold ud-text-xl ud-text-white hover:ud-text-primary ud-mb-2 ud-block">
                          {{$value->blog_title}}
                        </a>
                      </h3>
                      <a
                        href="{{route('single-blog',$value->blog_slug)}}"
                        class="ud-font-semibold ud-text-base ud-text-white"
                      >
                        {{ __("Continue reading...") }}
                      </a>
                    </div>
                    <div class="ud-flex ud-items-center ud-justify-between">
                      <a
                        href="{{route('single-blog',$value->blog_slug)}}"
                        class="ud-text-white hover:ud-text-primary mx-2"
                        name="read-more"
                        aria-label="read-more"
                      >
                        <svg
                          width="32"
                          height="32"
                          viewBox="0 0 32 32"
                          class="ud-fill-current"
                        >
                          <path
                            d="M18.6667 22.5868V17.2534H6.77334L6.73334 14.5734H18.6667V9.25342L25.3333 15.9201L18.6667 22.5868Z"
                          />
                        </svg>
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php $i++; ?>
          @endforeach

        </div>

        <div class="clearfix"></div>

        <br>
        {{ $blog_list->links() }}
        <br>
        <br>

      </div>
    </section>
@endsection

@push('scripts-footer')
<script>
  <script src="{{ asset('assets/heatmap/js/blog/list-blog.js') }}"></script>
</script>
@endpush

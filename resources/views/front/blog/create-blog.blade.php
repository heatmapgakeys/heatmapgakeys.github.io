@extends('layouts.front')
@section('title',$title)
@section('meta_title',$meta_title)
@section('meta_description',$meta_description)
@section('meta_keyword',$meta_keyword)
@section('meta_author',$meta_author)
@section('meta_image',$meta_image)
@section('meta_image_width',$meta_image_width)
@section('meta_image_height',$meta_image_height)
@push('styles-header')
<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

@endpush

@section('content')

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

    <!-- ====== Contact Start ====== -->
    <section id="contact" class="ud-contact">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-12">
            <div class="ud-contact-form-wrapper wow fadeInUp" data-wow-delay=".2s">
              <h3 class="ud-contact-form-title">{{$title}}</h3>
              <form class="ud-contact-form" method="post" action="{{route('save-blog')}}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="hidden_blog_id" value="{{isset($xdata->id) ? $xdata->id : 0}}">
                <div class="ud-form-group">
                    <label for="">{{__('Blog Title')}} *</label>
                    <input type="text" autofocus name="blog_title" id="blog_title" class="py-2 small" value="{{old('blog_title',isset($xdata->blog_title) ? $xdata->blog_title : '')}}"/>
                    @if ($errors->has('blog_title'))
                        <span class="text-danger small"> {{ $errors->first('blog_title') }} </span>
                    @endif
                </div>

                <div class="ud-form-group <?php if(isset($xdata)) echo 'd-none';?>">
                    <label for="">{{__('Blog Slug')}} *</label>
                    <input type="text" name="blog_slug" id="blog_slug" class="py-2 small" value="{{old('blog_slug',isset($xdata->blog_slug) ? $xdata->blog_slug : '')}}"/>
                    @if ($errors->has('blog_slug'))
                        <span class="text-danger small"> {{ $errors->first('blog_slug') }} </span>
                    @endif
                </div>

                <div class="ud-form-group">
                    <label for="" class="mb-4">{{__('Blog Content')}} *</label>
                    <textarea name="blog_content" id="blog_content" class="visual_editor border p-3" rows="6"  placeholder=""><?php echo old('blog_content',isset($xdata->blog_content) ? $xdata->blog_content : '')?></textarea>
                    @if ($errors->has('blog_content'))
                        <span class="text-danger small"> {{ $errors->first('blog_content') }} </span>
                    @endif
                </div>



                <div class="ud-form-group">
                    <label for="">{{__('Blog Category')}}</label>
                    <?php echo Form::select('blog_category_id',  $category_list, old('blog_category_id',isset($xdata->blog_category_id) ? $xdata->blog_category_id : ''), ['class' => 'form-control mt-3 py-3 small']);?>
                    @if ($errors->has('blog_category_id'))
                        <span class="text-danger small"> {{ $errors->first('blog_category_id') }} </span>
                    @endif
                 </div>

                <div class="ud-form-group">
                    <label for="">{{__('Blog Cover')}} </label>
                    <input type="file" name="blog_img" class="border-bottom-0" value="{{old('blog_img')}}"/>
                    @if ($errors->has('blog_img'))
                        <span class="text-danger small"> {{ $errors->first('blog_img') }} </span>
                    @endif
                </div>

                <div class="ud-form-group">
                    <label for="">{{__('Blog Keywords')}}</label>
                    <input type="text" name="blog_keyword" class="py-2 small" value="{{old('blog_keyword',isset($xdata->blog_keyword) ? $xdata->blog_keyword : '')}}"/>
                    @if ($errors->has('blog_keyword'))
                        <span class="text-danger small"> {{ $errors->first('blog_keyword') }} </span>
                    @endif
                </div>
                  <div class="ud-form-group">
                      <label class="" for="status">{{__("Status")}}</label>
                      <input  class="form-check-input border" id="status" name="status" type="checkbox" value="1" <?php echo (old('status',isset($xdata->status) ? $xdata->status : '')=='0') ? '' : 'checked'; ?>>
                      @if ($errors->has('status'))
                          <span class="text-danger"> {{ $errors->first('status') }} </span>
                      @endif
                </div>

                <div class="ud-form-group mt-4 mb-0">
                  <button type="submit" class="ud-text-white ud-bg-primary mt-4 py-3 px-4 rounded" id="ud-text-white ud-bg-primary mt-4 py-3" >
                  <i class="fas fa-save"></i>  {{__('Save Blog')}}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- ====== Contact End ====== -->
@endsection

@push('scripts-footer')
    <script src="{{asset('assets/cdn/js/tinymce.min.js')}}" referrerpolicy="origin"></script>
    <script src="{{ asset('assets/heatmap/js/blog/create-blog.js') }}"></script>
@endpush

@if($popular_blog_info->first())
<div class="ud-articles-box">
   <h3 class="ud-articles-box-title">{{__('Popular Articles')}}</h3>
   <ul class="ud-articles-list">
       @foreach($popular_blog_info as $popular_key=>$popular_value)
       <li>
           <div class="ud-article-image">
               <?php $blog_thumb = !empty($popular_value->blog_img) ? $popular_value->blog_img : asset('assets/images/favicon.png');?>
               <img src={{$blog_thumb}} alt="author" width="80" height="80"/>
           </div>
           <div class="ud-article-content">
               <h6 class="mb-2">
                   <a class="text-dark" href="{{route('single-blog',$popular_value->blog_slug)}}">
                       {{$popular_value->blog_title}}
                   </a>
               </h6>
               <p class="ud-article-author">
                   {{$popular_value->author_name}}
                   <span class="text-muted small">
                       <i class="far fa-clock"></i>
                       <?php echo convert_datetime_to_phrase($popular_value->updated_at,true);?>
                   </span>
               </p>
           </div>
       </li>
       @endforeach
   </ul>
</div>
@endif
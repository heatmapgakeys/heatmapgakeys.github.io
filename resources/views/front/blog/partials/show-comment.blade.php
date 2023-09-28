<link rel="stylesheet" href="{{ asset('assets/css/inlinecss.css') }}">

<ul class="ud-articles-list">
   <div id="append_comment" class="mb-4"></div>
   @foreach($parent_comment_info as $comment_key=>$comment_value)
       <li class="border-bottom-0 align-items-start" id="comment-{{$comment_value->id}}">
           <div class="ud-article-image">
               <?php $avatar = !empty($comment_value->profile_pic) ? $comment_value->profile_pic : asset('assets/images/avatar/avatar-'.rand(1,5).'.png');?>
               <img src={{$avatar}} alt="author" class="h-100"/>
           </div>
           <div class="ud-article-content w-100">
               <h6 class="d-inline">
                   <a class="text-dark" href="{{route('single-comment',$comment_value->id)}}">
                       {{$comment_value->name}}
                   </a>
                   <a href=""></a>
               </h6>
               <a class="text-muted small" href="{{route('single-comment',$comment_value->id)}}">
                   <i class="far fa-clock"></i> <?php echo convert_datetime_to_phrase($comment_value->updated_at,true);?>
               </a>
               @if(isset($comment_value->blog_slug))
                   @<a class="text-dark small" href="{{route('single-blog',$comment_value->blog_slug)}}">
                      <?php echo isset($comment_value->blog_title) ? substr(trim(strip_tags($comment_value->blog_title)),0,70).'...' : __('View Blog');?>
                   </a>
               @endif
              <div class="d-inline float-end <?php if(!Auth::user() || $enable_blog_comment == '0') echo 'd-none';?>">
                  @if($is_admin)
                    <a href="#" data-id="{{$comment_value->id}}" class="hide_comment btn btn-sm btn-outline-danger me-2" title="{{__('Remove')}}"><i class="fas fa-trash-alt"></i></a>
                  @endif
                  @if(!$is_user && $comment_value->display_admin_dashboard=='1')
                     <a href="#" data-id="{{$comment_value->id}}" class="seen_comment btn btn-sm btn-outline-secondary me-2" title="{{__('Mark as Seen')}}"><i class="fas fa-eye"></i></a>
                  @endif
                  <a href="#" data-id="{{$comment_value->id}}" data-blog-id="{{$comment_value->blog_id}}" data-parent-commenter-id="{{$comment_value->user_id}}" class="reply_comment btn btn-sm btn-primary text-white" title="{{__('Reply')}}"><i class="far fa-comment"></i></a>
              </div>
               <p class="ud-article-author mt-2"><?php echo format_comment($comment_value->comment);?></p>

               <ul class="ud-articles-list">
                   @if(isset($child_comment_info[$comment_value->id]))
                       @foreach($child_comment_info[$comment_value->id] as $child_key=>$child_value)
                       <li class="border-bottom-0 align-items-start pb-0">
                           <div class="me-3">
                               <?php $avatar = !empty($child_value->profile_pic) ? $child_value->profile_pic : asset('assets/images/avatar/avatar-'.rand(1,5).'.png');?>
                               <img src="{{$avatar}}" alt="author" class="rounded-circle mt-1" id="rounded-circle_id" />
                           </div>
                           <div class="ud-article-content w-100">
                               <span class="text-dark">{{$child_value->name}}</span>
                               <span class="text-muted small"><i class="far fa-clock"></i> <?php echo convert_datetime_to_phrase($child_value->updated_at,true);?></span>
                               <div class="d-inline float-end">
                                   @if($is_admin)
                                       <a href="#" data-id="{{$child_value->id}}" class="hide_comment btn btn-sm btn-outline-danger me-2" title="{{__('Remove')}}"><i class="fas fa-trash-alt"></i></a>
                                   @endif
                               </div>
                               <p class="ud-article-author mt-0"><?php echo format_comment($child_value->comment);?></p>
                           </div>
                       </li>
                       @endforeach
                   @endif
                   <div class="append_comment" id="append_comment-{{$comment_value->id}}">
                   </div>
               </ul>
           </div>
       </li>
   @endforeach
</ul>
<div class="mt-4 pt-4">
   {!! $parent_comment_info->links() !!}
</div>
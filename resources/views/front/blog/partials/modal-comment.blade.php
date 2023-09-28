<div class="modal fade" id="leave_comment_child" data-bs-backdrop="static" data-bs-keyboard="false">
   <div class="modal-dialog">
       <div class="modal-content">
           <div class="modal-header">
               <h5 class="modal-title">{{__('Reply a comment')}}</h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
           </div>
           <div class="modal-body">
               <div class="form-group mb-4" id="">
                   <input type="hidden" name="blog_id" class="blog_id" value="0">
                   <input type="hidden" name="parent_commenter_id" class="parent_commenter_id" value="0">
                   <input name="parent_blog_comment_id" class="parent_blog_comment_id" type="hidden" value="0">
                   <textarea autofocus class="form-control p-3 comment" placeholder="{{__('Leave a comment...')}}"></textarea>
               </div>
           </div>
           <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{__('Close')}}</button>
               <button type="button" class="btn btn-primary comment_submit child">{{__('Reply')}}</button>
           </div>
       </div>
   </div>
</div>
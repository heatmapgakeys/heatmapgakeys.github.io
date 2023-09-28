"use strict";
$(document).ready(function() {

    $(document).on('click', '.comment_submit', function(event) {
      event.preventDefault();
      var div_id = $(this).hasClass('main') ? '#leave_comment' : '#leave_comment_child';
      var blog_id=$(div_id+" .blog_id").val();
      var parent_blog_comment_id=$(div_id+" .parent_blog_comment_id").val();
      var comment=$(div_id+" .comment").val();
      var parent_commenter_id=$(div_id+" .parent_commenter_id").val();
      if(comment=='') return false;
      $.ajax({
	       type:'POST' ,
	       url: blog_url_reply_comment,
	       data: {blog_id,parent_blog_comment_id,comment,parent_commenter_id},
	       beforeSend: function (xhr) {
               xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
	       success:function(response)
	       {
		       if(parent_commenter_id==0) $("#append_comment").prepend(response);		       	
		       else $("#append_comment-"+parent_blog_comment_id).prepend(response);
		       $('#leave_comment_child').modal('hide');
		       Swal.fire({icon: 'success',title: global_lang_success,text: blog_lang_comment_posted_successfully});
		   	 },
		   	 error: function (xhr, statusText) {    
            const msg = handleAjaxError(xhr, statusText);
            Swal.fire({icon: 'error',title: global_lang_error,html: msg});
            return false;
         }
			});      
    });

    $(document).on('click','.reply_comment',function(event) {
    	event.preventDefault();
    	$("#leave_comment_child .parent_blog_comment_id").val($(this).attr('data-id'));
    	$("#leave_comment_child .parent_commenter_id").val($(this).attr('data-parent-commenter-id'));
      $("#leave_comment_child .blog_id").val($(this).attr('data-blog-id'));
    	$("#leave_comment_child .comment").val('');
    	$('#leave_comment_child').modal('show');
    });
});
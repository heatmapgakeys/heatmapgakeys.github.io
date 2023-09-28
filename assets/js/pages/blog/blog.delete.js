"use strict";
$(document).ready(function() {

    $(document).on('click', '#delete_blog', function(event) {
      event.preventDefault();
      var blog_id=$(this).attr('data-id');

      Swal.fire({
            title: global_lang_confirm,
            text: global_lang_delete_blog_confirmation,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '',
            confirmButtonText: global_lang_delete,
            cancelButtonText: global_lang_cancel
        }).then((result) => {
            if (result.isConfirmed) {
          	      $.ajax({
          		       type:'POST' ,
          		       url: blog_url_delete_blog,
          		       data: {blog_id},
          		       dataType: 'JSON',
          		       context: this,
          		       beforeSend: function (xhr) {
          	            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
          	         },
          		     success:function(response)
          		     {
          			    if(response.success='1'){
                            Swal.fire({icon: 'success',title: global_lang_success,text: response.message})
                            .then(function() {
                                window.location = blog_url_list_blog;
                            });
                        }
                        else Swal.fire({icon: 'error',title: global_lang_error,text: response.message});
          			 },
          			 error: function (xhr, statusText) 
                     {    
          	            const msg = handleAjaxError(xhr, statusText);
          	            Swal.fire({icon: 'error',title: global_lang_error,html: msg});
          	            return false;
          	         }
          		});
            }
        });

            
    });

    $(document).on('click', '.seen_comment', function(event) {
      event.preventDefault();
      var comment_id=$(this).attr('data-id');

      Swal.fire({
            title: global_lang_confirm,
            text: global_lang_seen_confirmation,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '',
            confirmButtonText: global_lang_confirm,
            cancelButtonText: global_lang_cancel
        }).then((result) => {
            if (result.isConfirmed) {
        	      $.ajax({
        		       type:'POST' ,
        		       url: blog_url_seen_comment,
        		       data: {comment_id},
        		       dataType: 'JSON',
        		       context: this,
        		       beforeSend: function (xhr) {
        	            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
        	         },
        		       success:function(response)
        		       {
        			       if(response.success=='1') {
        			       	$(this).hide();
        			       	Swal.fire({icon: 'success',title: global_lang_success,text: response.message});
        			       	if(current_route!='single-blog') $(this).parent().parent().parent().hide();
        			       }
        			       else Swal.fire({icon: 'error',title: global_lang_success,text: response.message});
        			   	 },		   	 
        			   	 error: function (xhr, statusText) {    
        	            const msg = handleAjaxError(xhr, statusText);
        	            Swal.fire({icon: 'error',title: global_lang_error,html: msg});
        	            return false;
        	         }
        				});
            }
        });
          
    });


});
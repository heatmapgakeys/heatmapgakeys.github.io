<script type="text/javascript">
	var current_path = '{{Request::path()}}';
	var current_route = '{{Route::currentRouteName()}}';
	var base_url = "{{url('/')}}";
	var csrf_token = '{{csrf_token()}}';
	var logo_url = "{{config('app.logo')}}";
    var logo_white_url = "{{config('app.logo_alt')}}";

    var landing_url_accept_cookie = "{{route('accept-cookie')}}";

    var blog_url_list_blog = "{{route('list-blog')}}";
    var blog_url_reply_comment = "{{route('reply-comment')}}";
    var blog_url_hide_comment = "{{route('hide-comment')}}";
    var blog_url_seen_comment = "{{route('seen-comment')}}";
    var blog_url_delete_blog = "{{route('delete-blog')}}";

    var global_lang_success = "{{__('Success')}}";
    var global_lang_error = "{{__('Error')}}";
    var global_lang_confirm = "{{__('Confirm')}}";
    var global_lang_delete = "{{__('Delete')}}";
    var global_lang_cancel = "{{__('Cancel')}}";
    var global_lang_delete_confirmation = "{{__('Do you really want to delete this recoed?')}}";
    var global_lang_delete_blog_confirmation = "{{__('Do you really want to delete this blog?')}}";
    var global_lang_seen_confirmation = "{{__('Do you really want to mark this recoed as seen?')}}";
    var blog_lang_comment_posted_successfully = "{{__('Comment has been posted successfully.')}}";
</script>
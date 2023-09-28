<?php
    if(!isset($is_admin)) $is_admin = '0';
    if(!isset($is_agent)) $is_agent = '0';
    if(!isset($is_member)) $is_member = '0';
    if(!isset($is_manager)) $is_manager = '0';
    if(!isset($user_module_ids)) $user_module_ids = [];
    if(!isset($team_access)) $team_access = [];
    if(!isset($agent_has_whitelabel)) $agent_has_whitelabel = '0';
    $language = config('app.locale');
    $language_exp = explode('-', $language);
    $language_code = $language_exp[0] ?? 'en';
    $datatable_lang_file_path = get_public_path('assets').DIRECTORY_SEPARATOR.'vendors'.DIRECTORY_SEPARATOR.'datatables'.DIRECTORY_SEPARATOR.'language'.DIRECTORY_SEPARATOR.$language_code.'.json';
    if(file_exists($datatable_lang_file_path))
    $datatable_lang_file = asset('assets/vendors/datatables/language/'.$language_code.'.json');
    else $datatable_lang_file = asset('assets/vendors/datatables/language/en.json');
?>
<script type="text/javascript">
    var base_url = '{{url('/')}}';
    var site_url = base_url;
    var temp_route_variable = 1;
    var csrf_token = '{{ csrf_token() }}';
    var today = '{{ date("Y-m-d") }}';
    var is_admin = '{{$is_admin}}';
    var is_agent = '{{$is_agent}}';
    var is_member = '{{$is_member}}';
    var is_manager = '{{$is_manager}}';
    var is_team = '0';
    var agent_has_whitelabel = '{{$agent_has_whitelabel}}';
    var route_name = '{{isset($route_name) && !empty($route_name) ? $route_name : ""}}';
    var language = '{{$language}}';
    var is_rtl = '{{$is_rtl??"0"}}';
    var auth_user_id = '{{Auth::user()->id ?? ''}}';
    var auth_parent_user_id = '{{Auth::user()->parent_user_id ?? ''}}';
    var auth_user_name = '{{Auth::user()->name ?? ''}}';
    var auth_user_email = '{{Auth::user()->email ?? ''}}';
    var auth_user_type = '{{Auth::user()->user_type ?? ''}}';


    var user_module_ids = '{{json_encode($user_module_ids)}}';
    var module_id_affiliate_system = '{{$module_id_affiliate_system}}';
    var module_id_team_member = '{{$module_id_team_member}}';


    var global_url_login = '{{ route('login') }}';
    var global_url_register = '{{ route('register') }}';
    var global_url_dashboard = '{{ route('dashboard') }}';
    var global_url_dashboard_clear_cache = '{{ route('dashboard-clear-cache') }}';
    var global_url_datatable_language = '{{$datatable_lang_file}}';
    var global_url_payment_success = '{{ route('transaction-log') }}'+'?action=success';
    var global_url_payment_cancel = '{{ route('transaction-log') }}'+'?action=cancel';
    var global_url_notification_mark_seen = '{{ route('notification-mark-seen') }}';


    var global_lang_loading = '{{ __('Loading') }}';
    var global_lang_sent = '{{ __('Sent') }}';
    var global_lang_required = '{{ __('Required') }}';
    var global_lang_ok = '{{ __('OK') }}';
    var global_lang_procced = '{{ __('Proceed') }}';
    var global_lang_success = '{{ __('Success') }}';
    var global_lang_warning = '{{ __('Warning') }}';
    var global_lang_error = '{{ __('Error') }}';
    var global_lang_confirm = '{{ __('Confirm') }}';
    var global_lang_create = '{{ __('Create') }}';
    var global_lang_edit = '{{ __('Edit') }}';
    var global_lang_domain_setting = '{{ __('Save') }}';
    var global_lang_delete = '{{ __('Delete') }}';
    var global_lang_clear_log = '{{ __('Clear Log') }}';
    var global_lang_cancel = '{{ __('Cancel') }}';
    var global_lang_apply = '{{ __('Apply') }}';
    var global_lang_understand = '{{ __('I Understand') }}';
    var global_lang_download = '{{ __('Download') }}';
    var global_lang_from = '{{ __('From') }}';
    var global_lang_to = '{{ __('To') }}';
    var global_lang_custom = '{{ __('Custom') }}';
    var global_lang_choose_data = '{{ __('Date') }}';
    var global_lang_last_30_days = '{{ __('Last 30 Days') }}';
    var global_lang_this_month = '{{ __('This Month') }}';
    var global_lang_last_month = '{{ __('Last Month') }}';
    var global_lang_something_wrong = '{{ __('Something went wrong.') }}';
    var global_lang_confirmation = '{{ __('Are you sure?') }}';
    var global_lang_delete_confirmation = '{{ __('Do you really want to delete this record? This action cannot be undone and will delete any other related data if needed.') }}';
    var global_lang_submitted_successfully = '{{ __('Data has been submitted successfully.') }}';
    var global_lang_saved_successfully = '{{ __('Data has been saved successfully.') }}';
    var global_lang_deleted_successfully = '{{ __('Data has been deleted successfully.') }}';
    var global_lang_action_successfully = '{{ __('Command action has been performed successfully.') }}';
    var global_lang_fill_required_fields = '{{ __('Please fill the required fields.') }}';
    var global_lang_check_status = '{{ __('Check Status') }}';
    var global_all_fields_are_required = '{{ __('All fields are required.') }}';
    var global_lang_affiliate_user_response = '{{ __('Do you really want to change the  status.') }}';
    var global_lang_affiliate_withdrawal_response = '{{ __('Do you really want to change the affliate withdrawal status.') }}';
    var common_function_url_get_email_profile_dropdown = '{{route('common-get-email-profile-dropdown')}}';

    var subscription_list_package_url_data = '{{route('list-package-data')}}';
    var subscription_list_package_url_update = '{{route('update-package',':id')}}';
    var subscription_list_package_url_delete = '{{route('delete-package')}}';
    var subscription_list_user_url_data = '{{route('list-user-data')}}';
    var subscription_list_user_url_update = '{{route('update-user',':id')}}';
    var subscription_list_user_url_delete = '{{route('delete-user')}}';
    var subscription_list_user_url_send_email = '{{route('user-send-email')}}';
    var subscription_list_user_lang_send_email = '{{__('Send Email')}}';
    var subscription_list_user_lang_email = '{{__('Email')}}';
    var subscription_list_user_lang_warning_select_user = '{{__('You have to select users to send email.')}}';


    var member_transaction_log_url_data= '{{route('transaction-log-data')}}';
    var member_transaction_log_manual_url_data= '{{route('transaction-log-manual-data')}}';
    var member_payment_buy_package_url = '{{route('buy-package',':id')}}';
    var member_payment_select_package_lang_already_subscribed = '{{__('Already Subscribed')}}';
    var member_payment_select_package_lang_already_subscribed_lang = '{{__('You already have a subscription set up. If you want to switch to a new payment method or subscription, please sure to cancel your current one first.')}}';
    var member_payment_buy_package_package_id = '{{$buy_package_package_id ?? '0'}}';
    var member_payment_buy_package_has_recurring_flag = '{{$has_reccuring ?? '0'}}';
    var member_payment_buy_package_fastspring_coupon = '{{$fastspring_discount_coupon ?? ''}}';
    var member_payment_buy_package_paypro_coupon = '{{$paypro_discount_coupon ?? ''}}';
    var member_settings_list_api_settings_url_data = '{{route('api-settings-data')}}';
    var member_settings_list_api_settings_url_update_data = '{{route('update-api-settings')}}';
    var member_settings_list_api_settings_url_save = '{{route('save-api-settings')}}';
    var member_settings_list_api_log_url_data = '{{route('list-payment-api-log-data')}}';


    var manual_payment_upload_file_route = '{{ route("Manual-payment-upload-file") }}';
    var manual_payment_submission_route = '{{ route("Manual-payment-submission") }}';
    var manual_payment_upload_file_delete_route = '{{ route("Manual-payment-uploaded-file-delete") }}';
    var manual_payment_handle_action_route = '{{ route("Manual-payment-handle-action") }}';

    var cancelcsvsubmission = '{{ __("Do you want to cancel this submission?") }}';
    var uploadCsvFile = '{{ __("Please upload your Subscribers CSV file") }}';
    var botIdNotFound = '{{ __("Please select a telegram bot") }}';

    //ronok

    var domain_text = '{{__('Domain Settings')}}';
    var provide_domain = '{{__('You have to provide a domain name.')}}';
    var generate_domain_embed_code = '{{route('generate-embed-code')}}';
    var delete_domain = '{{route('delete-domain')}}';
    var global_lang_embeded_code = '{{ __('Get Embed Code')}}';
    var get_visit_url_information = '{{ route('get-visit-url-information')}}';
    var get_js_code = '{{route('get-js-embed-code')}}';
    var edit_domain = '{{route('edit-domain')}}';

    var get_screenshot_with_data = '{{ route("heatmap-generator") }}';
    var set_active_url_session = '{{route('set-active-url-session')}}';
    var active_page_name_session = "{{session('active_page_name_session')}}";
    var delete_session_visit_url = '{{ route('delete-session-visit-url') }}';
    var active_domain_id_session = '{{ session('active_domain_id_session') }}';
    var get_domain_pages_list= '{{ route('get-domain-pages-list') }}';
    var get_rest_visit_lists= '{{ route('get-rest-visit-lists') }}';
    var get_video_download_link= '{{ route('get-video-download-link') }}';
    var get_corresponding_url_session_data= '{{ route('get-corresponding-url-session-data') }}';
    var domain_session_data_delete = '{{ route('domain-session-data-delete') }}';
    var play_pause_domain = '{{ route('play-pause-domain') }}';
    var get_dashboard_data = '{{ route('get-dashboard-data') }}';

    var play_domain = '{{ __("Play Recording") }}';
    var pause_domain = '{{ __("Pause Recording") }}';

    var dataNotAvailable = '{{ __("Data Not Available") }}';
    var startedRecording = '{{ __("Domain has started Recording") }}';
    var stoppedRecording = '{{ __("Domain has stopped Recording") }}';
    var videoisrendering = '{{ __("Video is Rendering, Please wait") }}';
    var download_file_generating = '{{ __("Please wait, generating file for download") }}';

    var module_id_no_of_website = '{{has_module_access($module_id_no_of_website,$user_module_ids,$is_admin)}}';
    var module_id_recorded_sessions = '{{has_module_access($module_id_recorded_sessions,$user_module_ids,$is_admin)}}';
    var module_id_month_of_data_sotrage = '{{has_module_access($module_id_month_of_data_sotrage,$user_module_ids,$is_admin)}}';
    var opacityText = '{{ __("Opacity") }}';

    var domain_delete_confirmation_alert = '{{ __("Do you really want to delete this domain? This action will delete all corresponding session recordings and heatmap data.") }}';

    // Affiliate global lang

    var affliate_edit_request = '{{ __('Edit Request') }}'
    var requested_amount_error = '{{ __('Please provide a valid amount. You are allowed to withdraw minimum $50') }}'
    var affiliate_subscription_list_user_url_data = '{{route('affiliate-list-user-data')}}';
    var affiliate_common_commision_set = '{{route('affiliate-commission-settings-set')}}';
    var affiliate_user_form_submission = '{{route('affiliate-user-form-submission')}}';
    var affiliate_user_get_info = '{{route('affiliate-user-get-info')}}';
    var affiliate_withdrawal_methods_data = '{{route('affiliate-withdrawal-methods-data')}}';
    var affiliate_create_withdrawal_method = '{{route('affiliate-create-withdrawal-method')}}';
    var affiliate_get_withdrawal_method_info = '{{route('affiliate-get-withdrawal-method-info')}}';
    var affiliate_update_withdrawal_method_info = '{{route('affiliate-update-withdrawal-method-info')}}';
    var affiliate_withdrawal_method_delete = '{{route('affiliate-withdrawal-method-delete')}}';
    var affiliate_user_request_list = '{{ route('affiliate-user-request-list') }}';
    var affiliate_request_status_change = '{{ route('affiliate-request-status-change') }}';
    var affiliate_send_whatsapp_otp = '{{ route('affiliate-send-whatsapp-otp') }}';
    var affiliate_withdrawal_requests_admin = '{{ route('affiliate-withdrawal-request-list-admin') }}';
    var affiliate_withdrawal_requests_delete_admin = '{{ route('affiliate-withdrawal-request-delete-admin') }}';
    var affiliate_withdrawal_requests_status_change = '{{ route('affiliate-withdrawal-request-status-change') }}';
    var affiliate_system_get_requests_info = '{{ route('affiliate_system-get-requests-info') }}';
    var affiliate_system_issue_new_request = '{{ route('affiliate-system-issue-new-request') }}';
    var affiliate_withdrawal_requests = '{{ route('affiliate-withdrawal-requests') }}';
    var affiliate_delete_withdrawal_requests = '{{ route('delete-withdrawal-request') }}';
    var affiliate_list_user_url_send_email = '{{route('affiliate-send-email')}}';
    var purchase_code_active = '{{ route("credential-check-action") }}'
    <?php if(check_is_mobile_view()) echo 'var areWeUsingScroll = false;';
    else echo 'var areWeUsingScroll = true;';;?>
</script>


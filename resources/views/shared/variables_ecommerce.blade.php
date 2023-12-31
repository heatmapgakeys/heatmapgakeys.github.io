<script type="text/javascript">
	var current_path = '{{ Request::path() }}';
	var ecommerce_base_url = '{{ url('') }}';
	var ecommerce_csrf = '{{ csrf_token() }}';
	var is_ecommerce_datatable = '{{ ($load_datatable) ?? false }}';
	var ecommerce_store_delete_title = '{{ __("Delete Store") }}';
	var ecommerce_store_delete_message = '{{ __("Do you really want to delete this store? Deleting store will also delete all related data like cart,purchase,settings etc.") }}';
	var ecommerce_store_delete_url = '{{ route("ecommerce-store-delete") }}';
	var ecommerce_store_list_api_response_title = '{{ __("API Response") }}';
	var ecommerce_reminder_send_status_data_url = '{{ route("ecommerce-reminder-send-status-data") }}';
	var ecommerce_store_dashboard = '{{ route("ecommerce-stores") }}';
	var ecommerce_store_create_success_msg = '{{ __("Store Created") }}';
	var ecommerce_store_update_success_msg = '{{ __("Store Updated") }}';
	var varibles_title = '{{ __("Variables") }}';
	var ecommerce_get_template_label_dropdown_url = '{{ route("ecommerce-get-template-label-dropdown") }}';
	var ecommerce_cart_update_title = '{{ __("Cart Update") }}';
	var ecommerce_data_not_saved_msg = '{{ __("Your data may not be saved.") }}';
	var ecommerce_goback_btn_warn = '{{ __("Do you want to go back?") }}';
	var ecommerce_create_store_action_url = '{{ route("create-ecommerce-store-action") }}';
	var ecommerce_update_store_action_url = '{{ route("edit-ecommerce-store-action") }}';
	var store_logo_upload_url = '{{ route("ecommerce-store-logo-upload") }}';
	var store_logo_delete_url = '{{ route("ecommerce-store-logo-delete") }}';
	var store_favicon_upload_url = '{{ route("ecommerce-store-favicon-upload") }}';
	var store_favicon_delete_url = '{{ route("ecommerce-store-favicon-delete") }}';
	var ecommerce_update_store_telegram_bot_id = '{!! $xdata->telegram_bot_id ?? "0" !!}';
	var ecommerce_update_store_id = '{!! $xdata->id ?? "0" !!}';
	var ecommerce_update_store_get_label_dropdown_edit_url = '{{ route("ecommerce-get-template-label-dropdown-edit") }}';
	var ecommerce_store_name_field_empty = '{{ __("Please put your store name") }}';
	var ecommerce_store_email_field_empty = '{{ __("Please put your store email") }}';
	var ecommerce_store_country_field_empty = '{{ __("Please put your store country") }}';
	var ecommerce_store_state_field_empty = '{{ __("Please put your store state") }}';
	var ecommerce_store_city_field_empty = '{{ __("Please put your store city") }}';
	var ecommerce_store_address_field_empty = '{{ __("Please put your store address") }}';
	var ecommerce_store_postal_field_empty = '{{ __("Please put your store postal code") }}';
	var ecommerce_store_category_field_empty = '{{ __("Category name is required") }}';
	var ecommerce_selected_store = '{!! session("ecommerce_selected_store") ?? "" !!}';
	var ecommerce_logo_favicon_storage = "{!! url('') !!}";
	var is_category_wise_product_view = "{{ $is_category_wise_product_view ?? '' }}";
	var url_cat = "{{ $url_cat ?? '' }}";

	// chart data variables //
	var ecommerce_earning_chart_title = '{{ __("Earning") }}';
	var ecommerce_store_list_view_chart_steps = '{{ $steps ?? "0"; }}';
	var ecommerce_earning_chart_labels = '{!! isset($earning_chart_labels) ? json_encode($earning_chart_labels): "" !!}';
	var ecommerce_earning_chart_values = '{!! isset($earning_chart_values) ? json_encode(array_values($earning_chart_values)): "" !!}';
	var ecommerce_business_always_open = '{!! $always_open ?? ""; !!}';
	var Doyouwanttodeletethisrecordfromdatabase = "{{ __('Do you want to detete this record?') }}";
	var ecommerce_category_list_data_url = '{{ route("ecommerce-category-list-data") }}';
	var ecommerce_category_list_add_text = '{{ __("Add") }}';
	var ecommerce_category_list_sort_text = '{{ __("Sort") }}';
	var ecommerce_category_thumb_upload_route = '{{ route("upload-category-thumb") }}';
	var ecommerce_category_thumb_delete_route = '{{ route("upload-category-thumb-delete") }}';
	var ecommerce_category_create_route = '{{ route("ecommerce-store-new-category") }}';
	var ecommerce_update_category_info_route = '{{ route("ecommerce-store-update-category-info") }}';
	var ecommerce_category_update_route = '{{ route("ecommerce-store-update-category") }}';
	var ecommerce_category_delete_route = '{{ route("ecommerce-store-delete-category") }}';
	var ecommerce_category_sort_route = '{{ route("ecommerce-store-sort-category") }}';
	var ecommerce_attributes_list_data_route = '{{ route("ecommerce-attribute-list-data") }}';
	var ecommerce_attribute_name_field_empty = '{{ __("Attribute name is required") }}';
	var ecommerce_attribute_value_field_empty = '{{ __("Attribute value is required") }}';
	var ecommerce_store_create_attribute_route = '{{ route("ecommerce-store-create-attribute") }}';
	var ecommerce_store_update_attribute_status_route = '{{ route("ecommerce-store-attribute-status") }}';
	var ecommerce_store_attribute_update_info_route = '{{ route("ecommerce-store-attribute-update-info") }}';
	var ecommerce_store_attribute_update_route = '{{ route("ecommerce-store-attribute-update") }}';
	var ecommerce_store_attribute_delete_route = '{{ route("ecommerce-store-attribute-delete") }}';
	var ecommerce_store_products_list_data_route = '{{ route("ecommerce-product-list-data") }}';
	var ecommerce_store_create_product_route = '{{ route("ecommerce-store-create-product") }}';
	var ecommerce_store_create_product_action_route = '{{ route("ecommerce-store-create-product-action") }}';
	var ecommerce_store_product_thumb_upload_route = '{{ route("ecommerce-store-product-thumb-upload") }}';
	var ecommerce_store_product_thumb_delete_route = '{{ route("ecommerce-store-product-thumb-delete") }}';
	var ecommerce_store_product_featured_upload_route = '{{ route("ecommerce-store-product-featured-upload") }}';
	var ecommerce_store_product_featured_delete_route = '{{ route("ecommerce-store-product-featured-delete") }}';
	var ecommerce_store_product_file_upload_route = '{{ route("ecommerce-store-product-file-upload") }}';
	var ecommerce_store_product_file_delete_route = '{{ route("ecommerce-store-product-file-delete") }}';
	var ecommerce_store_product_delete_route = '{{ route("ecommerce-store-delete-product-action") }}';
	var ecommerce_store_attribute_values_route = '{{ route("ecommerce-store-attribute-lists") }}';
	var ecommerce_store_deletion_failed = '{{ __("Store Deletion failed.") }}';
	var calender_last_30_days = '{{ __("Last 30 Days") }}';
	var calender_last_this_month = '{{ __("This Month") }}';
	var calender_last_last_month = '{{ __("Last Month") }}';
	var ecommerce_store_delivery_point_list_route = '{{ route("ecommerce-store-delivery-point-list-data") }}';
	var ecommerce_store_delivery_point_create_route = '{{ route("ecommerce-store-delivery-point-create") }}';
	var ecommerce_store_delivery_point_status_update = '{{ route("ecommerce-store-delivery-point-status-update") }}';
	var ecommerce_store_delivery_point_get_info_route = '{{ route("ecommerce-store-delivery-point-update-info") }}';
	var ecommerce_store_delivery_point_info_update = '{{ route("ecommerce-store-delivery-point-update-action") }}';
	var ecommerce_store_delivery_point_delete = '{{ route("ecommerce-store-delivery-point-delete-action") }}';
	var ecommerce_store_delivery_point_name_field = '{{ __("Delivery Point Name is required.") }}';
	var ecommerce_store_delivery_point_details_field = '{{ __("Delivery Point Details is required.") }}';
	var ecommerce_store_coupon_list_data_route = '{{ route("ecommerce-store-coupon-list-data") }}';
	var ecommerce_store_coupon_delete_route = '{{ route("ecommerce-store-coupon-delete-action") }}';
	var ecommerce_store_customer_list_route = '{{ route("ecommerce-customer-list-data") }}';
	var ecommerce_store_order_list_route = '{{ route("ecommerce-store-order-list-data") }}';
	var ecommerce_store_customer_password_route = '{{ route("ecommerce-store-customer-password") }}';
	var ecommerce_store_variables_text = '{{ __("Variables") }}';
	var ecommerce_go_back_text = '{{ __("Your data may not be saved.") }}';
	var ecommerce_go_back_button_text = '{{ __("Do you want to go back?") }}';
	var ecommerce_restore_message_text = '{{ __("Do you really want restore default settings?") }}';
	var ecommerce_restore_message_button_text = '{{ __("Restore Default Settings") }}';
	var ecommerce_store_notification_settings_action_route = '{{ route("ecommerce-store-order-notification-settings-action") }}';
	var order_status_notification_route = '{{ route("ecommerce-store-order-notification-settings",$store_id ?? "0") }}';
	var reset_notification_route = '{{ route("ecommerce-store-order-notification-reset",$store_id ?? "0") }}';
	var reminder_settings_route = '{{ route("ecommerce-store-reminder-settings",$store_id ?? "0") }}';
	var reset_reminder_route = '{{ route("ecommerce-store-reminder-settings-reset",$store_id ?? "0") }}';
	var ecommerce_store_reminder_settings_action_route = '{{ route("ecommerce-store-reminder-settings-action") }}';
	var ecommerce_store_reminder_settings_update_success_text = '{{ __("Updated") }}';

	var ecommerce_qr_code_page = '{{ route("ecommerce-store-qr-code",$store_id ?? "0") }}';
	var ecommerce_qr_code_pickup_page = '{{ route("ecommerce-store-qr-code",$store_id ?? "0",$pickup_point_id ?? "") }}';
	var ecommerce_store_update_qr_code = '{{ route("ecommerce-store-qr-code-action") }}';

	// store front variables
	var ecommerce_field_required = '{{ __("Email and password are required") }}';
	var ecommerce_field_required2 = '{{ __("Please fill the required fields") }}';
	var ecommerce_store_buyer_delete_address = '{{ __("Delete address") }}';
	var confirm_address_delete = '{{ __("are you sure you want to delete this?") }}';
	var address_deleted = '{{ __("Address has been deleted successfully") }}';

	var ecommerce_store_login_route = '{{ route("ecommerce-store-customers-login") }}';
	var ecommerce_store_reg_route = '{{ route("ecommerce-store-customers-registration") }}';
	var ecommerce_store_guest_reg_route = '{{ route("ecommerce-store-guest-login-action") }}';
	var ecommerce_store_logout_route = '{{ route("ecommerce-store-customers-logout-action") }}';
	var ecommerce_store_buyer_profile_route = '{{ route("ecommerce-store-buye-profile") }}';
	var ecommerce_store_save_buyer_profile_route = '{{ route("ecommerce-store-save-buye-profile") }}';
	var ecommerce_store_buyer_addresses_route = '{{ route("ecommerce-store-buyer-address-list") }}';
	var ecommerce_store_get_buyer_addresses_route = '{{ route("ecommerce-store-get-buyer-addresses") }}';
	var ecommerce_store_save_buyer_address_route = '{{ route("ecommerce-store-save-buyer-address") }}';
	var ecommerce_store_delete_buyer_address_route = '{{ route("ecommerce-store-delete-buyer-address") }}';

	var ecommerce_store_add_to_cart_modal_route = '{{ route("ecommerce-store-add-to-cart-modal") }}';

	// for comment.js
	var ecommerce_store_pickup = '{{ $pickup ?? "" }}';
	var ecommerce_store_subscriberId = '{{ $subscriberId ?? "" }}';
	var ecommerce_store_subscriber_id = '{!! $subscriber_id ?? "" !!}';
	var ecommerce_store_get_comment_list_data = '{{ route("ecommerce-store-comments-list") }}';
	var ecommerce_store_no_comment_found = '{{ __("No more comment found.") }}';
	var ecommerce_store_write_a_comment = '{{ __("Please write a comment.") }}';
	var ecommerce_store_write_a_reply = '{{ __("Please write a reply") }}';
	var ecommerce_store_hide_review_title = '{{ __("Hide review?") }}';
	var ecommerce_store_hide_review_confirm = '{{ __("Do you really really want to hide this review?") }}';
	var ecommerce_store_select_delivery_address = '{{ __("Please select delivery address or pickup point before you proceed.") }}';
	var ecommerce_store_hide_comment_text = '{{ __("Hide comment?") }}';
	var ecommerce_store_hide_comment_confirm = '{{ __("Do you really really want to hide this comment?") }}';
	var ecommerce_store_add_comment = '{{ route("ecommerce-store-new-comment") }}';
	var ecommerce_store_hide_comment = '{{ route("ecommerce-store-hide-comment") }}';
	var ecommerce_store_add_review = '{{ route("ecommerce-store-add-review") }}';
	var ecommerce_store_hide_review = '{{ route("ecommerce-store-hide-review") }}';
	var ecommerce_store_add_review_comment = '{{ route("ecommerce-store-add-review-comment") }}';

	// for attribute-value.js
	var ecommerce_store_price_basedon_attributes = '{{ route("ecommerce-store-price-basedon-attributes") }}';
	
	// for cart.js
	var ecommerce_store_choose_option = '{{ __("Please choose the required options.") }}';
	var ecommerce_store_item_not_found = '{{ __("Item can not be removed. It is not in cart anymore.") }}';
	var ecommerce_store_update_cart_item = '{{ route("ecommerce-store-update-cart-item") }}';
	var ecommerce_store_update_checkout_cart_item = '{{ route("ecommerce-store-update-checkout-cart-item") }}';
	var ecommerce_store_delete_cart_item = '{{ route("ecommerce-store-delete-cart-item") }}';
	var ecommerce_store_apply_coupon = '{{ route("ecommerce-store-coupon-apply") }}';
	var ecommerce_store_apply_pickup = '{{ route("ecommerce-store-pickup-apply") }}';
	var ecommerce_store_proceed_checkout = '{{ route("ecommerce-store-proceed-checkout") }}';
	var ecommerce_store_proceed_checkout_cod = '{{ route("ecommerce-store-proceed-checkout-cod") }}';
	var ecommerce_store_my_orders_data = '{{ route("ecommerce-store-my-orders-data") }}';
	var ecommerce_store_order_status = '{{ route("ecommerce-store-order-status-change") }}';
	var ecommerce_store_reminder_response = '{{ route("ecommerce-store-reminder-response") }}';

</script>
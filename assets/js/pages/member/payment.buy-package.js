"use strict";

function dataPopupClosed(data)
{
    var success=true;
    if (typeof(data.id)==='undefined') success = false;
    if(success) window.location.replace(global_url_payment_success);
    else window.location.replace(global_url_payment_cancel);
}

function dataPopupWebhookReceived(data)
{
}

$(document).ready(function() {

    $(document).on('click', '#fastspring_clone', function(e) {
        e.preventDefault();
        var fs_product_id = $(this).attr('data-fs-id');
        var fs_coupon = $(this).attr('data-fs-coupon');
        var custom_data = auth_user_id+"-"+auth_parent_user_id+"-"+member_payment_buy_package_package_id;

        fastspring.builder.add(fs_product_id);
        fastspring.builder.recognize(
        {
            "email" : auth_user_email,
            "firstName" : auth_user_name,
            "lastName" : '-'
        });
        if(fs_coupon!='') fastspring.builder.promo(fs_coupon);
        fastspring.builder.tag({'custom_data': custom_data});
        fastspring.builder.checkout();
    });
});
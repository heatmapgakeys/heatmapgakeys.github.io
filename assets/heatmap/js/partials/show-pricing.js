"use strict";
        $(document).ready(function(){

            $('#package_website_range').on('change', function (e) {
                var selected = $(this).val();
                
                var purchase_url = buy_url;
                var package_id = 1;
                var price = '';
                var website = 0;
                var discount_message = '';
                var validity_text = '';
                var name = '';

                $('.premium-li').addClass('hidden');
                $('.premium-'+selected).removeClass('hidden');

                if(typeof(package_map[selected]['id'])!=="undefined") package_id = parseInt(package_map[selected]['id']);
                if(typeof(package_map[selected]['price'])!=="undefined") price = package_map[selected]['price'];
                if(typeof(package_map[selected]['website'])!=="undefined") website = package_map[selected]['website'];
                if(typeof(package_map[selected]['discount_message'])!=="undefined") discount_message = package_map[selected]['discount_message'];
                if(typeof(package_map[selected]['validity_text'])!=="undefined") validity_text = package_map[selected]['validity_text'];
                if(typeof(package_map[selected]['name'])!=="undefined") name = package_map[selected]['name'];

                if(website==0) website = lang_Unlimited;
                if(website<1) website = '';
                purchase_url = purchase_url.replace(":id", package_id);
                $("#package_price").html(price);
                $("#package_no_website").html(website);
                $("#package_link").attr('href',purchase_url);
                $("#validity_text").html(validity_text);
                $("#package_name").html(name);

                if(discount_message!=''){
                    $("#package_price_save").html(discount_message).addClass('d-inline').removeClass('hidden');
                }
                else $("#package_price_save").addClass('hidden').removeClass('d-inline');

            });
        });
'user strict';
var perscroll;
var table;

$(document).ready(function () {

    $(document).on('click', '.pause_play_domain', function(event) {
        event.preventDefault();
        var domain_id = $(this).attr('data-id');
        var eventType = $(this).attr('eventType');
        var blockId = $(this).attr('blockId');
        $.ajax({
            context: this,
            url: play_pause_domain,
            type: 'POST',
            data: {eventType,domain_id},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            success: function(response) {

                var selectorElement = $(".block-"+blockId+" .pause_play_domain");
                if(eventType=="play"){

                    $(".block-"+blockId+" .card-status").html('<i class="fas fa-stop text-danger"></i> Stopped')
                    $(selectorElement).attr('eventType','pause');
                    $(selectorElement).attr('data-bs-original-title','Start recording');
                    $(selectorElement).html('<i class="fas fa-play"></i>');
                    $(selectorElement).css('background','linear-gradient(to bottom right, #C90A6D, #FF48A0)');

                } else if(eventType=="pause") {

                    $(".block-"+blockId+" .card-status").html('<i class="fas fa-record-vinyl text-success"></i> Recording')
                    $(selectorElement).attr('eventType','play');
                    $(selectorElement).attr('data-bs-original-title','Stop recording');
                    $(selectorElement).html('<i class="fas fa-pause"></i>');
                    $(selectorElement).css('background','linear-gradient(to right, rgba(250, 112, 154, 1), rgba(254, 225, 64, 1))');

                }
                toastr.success(global_lang_saved_successfully,'',{'positionClass':'toast-bottom-right'});
            }
        });
    });

    $(document).on('click', '.get_js_embed', function(event) {
        event.preventDefault();
        var campaign_id = $(this).attr('campaign_id');
        $.ajax({
            url: get_js_code,
            type: 'POST',
            dataType:'JSON',
            data: {campaign_id: campaign_id},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            success: function(response) {
                if(response)
                {
                    $('#put_embed_js_code_wp_post,#put_embed_js_code_html_post').text(response.str1);
                    $("#get_embed_modal").modal('show');
                    Prism.highlightAll();
                }
                else
                {

                }
            }
        });
    });
    $(document).on('click', '.edit_domain', function(event) {
        event.preventDefault();
        var campaign_id = $(this).attr('campaign_id');
        $.ajax({
            url: edit_domain,
            type: 'POST',
            dataType:'JSON',
            data: {campaign_id: campaign_id},
            beforeSend: function (xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            success: function(response) {
                if(response)
                {
                    if(response.domain_prefix == 'https://'){
                        $('#domain_prefix1').prop('checked',true);
                    }
                    else{
                        $('#domain_prefix2').prop('checked',true);
                    }
                    $('.only_create').hide();
                    $('#domain_table_id').val(response.id);
                    $('#domain_name_add').val(response.domain_name);
                    $('#excluded_ips').val(response.excluded_ip);
                    $('#block_class').val(response.block_class);
                    $('#ignore_class').val(response.ignore_class);
                    $('#maskText_class').val(response.maskText_class);
                    $('#maskInput_option').val(response.maskInput_option);
                    $('#add_domain').text(global_lang_domain_setting);
                    $('#add_domain_modal .modal-title').text(domain_text);
                    if(response.maskAllInputs == 'true'){
                        $('#maskAllInputs1').prop('checked',false);
                        $('#maskAllInputs2').prop('checked',true);
                    }
                    $("#add_domain_modal").modal('show');
                    Prism.highlightAll();
                }
                else
                {

                }
            }
        });
    });

    $(document).on('click', '.add_domain_modal', function(event) {
        event.preventDefault();
        $("#analytic_code").html('');
        $("#domain_name_add").val('');
        $("#add_domain_modal").modal('show');
    });

    $('#add_domain_modal').on('hidden.bs.modal', function () {
        location.reload();
    });

    $('#get_embed_modal').on('hidden.bs.modal', function () {
        $("#get_embed_modal .modal-dialog").removeClass("modal-lg");
        $("#installation-method-post").show();
        $("#tech-body-post").hide();
        $("#wordpress-post").hide();
        $("#html-post").hide();
    });

    $('#embed_js_code').hide();

    $(document).on('click', '#add_domain', function(event) {
        event.preventDefault();
        var domain_name = $("#domain_name_add").val();
        var domain_table_id = $("#domain_table_id").val();
        var excluded_ips = $("#excluded_ips").val();
        var domain_prefix = $('input[name=domain_prefix]:checked').val();
        var block_class = $("#block_class").val();
        var ignore_class = $("#ignore_class").val();
        var maskText_class = $("#maskText_class").val();
        var maskInput_option = $("#maskInput_option").val();
        var maskAllInputs = $('input[name=maskAllInputs]:checked').val();
        if(domain_name.trim() == '')
        {
            Swal.fire(global_lang_warning ,provide_domain, 'warning');
            return;
        }


        var waiting_content = '<div class="text-center text-primary waiting"><i class="fas fa-spinner fa-spin blue text-center" style="font-size: 40px;"></i></div>';
        $("#analytic_code").html(waiting_content);
        $(this).addClass('btn-progress');
        $.ajax({
            context: this,
            type:'POST' ,
            url: generate_domain_embed_code,
            data: {domain_table_id,domain_name,excluded_ips,domain_prefix,block_class,ignore_class,maskText_class,maskInput_option,maskAllInputs},
            headers:{'X-CSRF-TOKEN':csrf_token},
            dataType : 'JSON',
            success:function(response){
                $(this).removeClass('btn-progress');
                $('#analytic_code').html('');
                if(response.status =='1'){
                    $('#embed_js_code').show();
                    $('#tech-body').hide();
                    $('#installation-method').show();
                    $('#put_embed_js_code,#put_embed_js_code_wp,#put_embed_js_code_html').text(response.message);
                    Prism.highlightAll();
                }
                else if(response.status =='2'){
                    Swal.fire(global_lang_success,response.message,'success').then((value) => {             
                       location.reload();
                    });

                }
                else{
                    Swal.fire('error',response.message,'error');
                }
            }
        });
    });

    $(document).on('click', '.tech-body-content', function(event) {
        event.preventDefault();
        $("#add_domain_modal .modal-dialog").addClass("modal-lg");
        $("#form-body").hide();
        $("#tech-body").show();

        let techType = $(this).attr('tech-type');
        if(techType=='wp'){
            $("#wordpress").show();
            $("#html").hide();
        } else if(techType=='html') {
            $("#wordpress").hide();
            $("#html").show();
        }
    });


    $(document).on('click','.delete-domain',function(e){
        e.preventDefault();
        var link = $(this).attr("href");
        var id = $(this).attr('data-id');
        var blockId = $(this).attr('blockId');
        Swal.fire({
            title: global_lang_confirm,
            text: domain_delete_confirmation_alert,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '',
            confirmButtonText: global_lang_delete,
            cancelButtonText: global_lang_cancel
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    context:this,
                    method: 'post',
                    dataType: 'JSON',
                    data: {id},
                    url: link,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
                    },
                    success: function (response) {
                        if (false === response.error) {
                            location.reload();
                        }
                        if (true === response.error) toastr.error(response.message, '',{'positionClass':'toast-bottom-right'});
                        return false;
                    },
                    error: function (xhr, statusText) {
                        const msg = handleAjaxError(xhr, statusText);
                        Swal.fire({icon: 'error',title: global_lang_error,html: msg});
                        return false;
                    },
                });
            }
        });

    });

    $(document).on('click', '.tech-body-content-post', function(event) {
        event.preventDefault();
        $("#get_embed_modal .modal-dialog").addClass("modal-lg");
        $("#installation-method-post").hide();
        $("#tech-body-post").show();
        let techType = $(this).attr('tech-type');

        if(techType=='wp'){
            $("#wordpress-post").show();
            $("#html-post").hide();
        } else if(techType=='html') {
            $("#wordpress-post").hide();
            $("#html-post").show();
        }
    });

    $(document).on('click', '#go_back_form', function(event) {
        event.preventDefault();
        $("#add_domain_modal .modal-dialog").removeClass("modal-lg");
        $("#form-body").show();
        $("#tech-body").hide();
        $("#wordpress").hide();
        $("#html").hide();
    });

    $(document).on('click', '#go_back_form_post', function(event) {
        event.preventDefault();
        $("#get_embed_modal .modal-dialog").removeClass("modal-lg");
        $("#installation-method-post").show();
        $("#tech-body-post").hide();
        $("#wordpress-post").hide();
        $("#html-post").hide();
    });


    $('#domain_list a').on('click', function() {

        var domain_id = $(this).attr('id');
        var domain_name = $(this).text();
        $("#domain-dropdown").text(domain_name);
        $.ajax({
            url: get_domain_pages_list,
            method: "POST",
            dataType:'json',
            beforeSend: function(xhr) {
               xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
            },
            data: {
               domain_id: domain_id
            },
            success: function(response) {
              $('#domain_list a').removeClass('active');
              $(this).addClass('active');
              location.reload();
            }
        });
    });
});

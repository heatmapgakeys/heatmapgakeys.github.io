"user strict";


function check_device() {
    if (/ipad|tablet/i.test(navigator.userAgent)) {
        return "tablet";
    }
    else if (/mobile/i.test(navigator.userAgent)) {
        return "mobile";
    } else {
        return "desktop"
    }
}

var table = '';
var perscroll;
$(document).ready(function() {
    var table = $("#mytable").DataTable({
       colReorder: true,
       serverSide: true,
       processing:true,
       bFilter: false,
       order: [[ 1, "desc" ]],
       pageLength: 10,
       ajax:
           {
               "url": get_visit_url_information,
               "type": 'POST',
               data:function(d){
                   d.visit_url = active_page_name_session
                   d.search_country = $("#search_country").val()
                   d.search_browser = $("#search_browser").val()
                   d.search_os = $("#search_os").val()
                   d.search_device = $("#search_device").val()
                   d.from_date = $("#from_date").val()
                   d.to_date = $("#to_date").val()
               },
               beforeSend: function (xhr) {
                   xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
               },
           },
       language:
           {
               url: global_url_datatable_language
           },
       dom: '<"top"f>rt<"bottom"lip><"clear">',
       columnDefs: [
           {
               targets: [1],
               visible: false
           },
           {
               targets: [0,1,3,4,5,6,7,8,9],
               className: 'text-center'
           },
           {
               targets: [2],
               className: 'text-start'
           },
           {
               targets: '',
               sortable: false
           },
       ],
       fnInitComplete:function(){  // when initialization is completed then apply scroll plugin
           if(areWeUsingScroll)
           {
               if (perscroll) perscroll.destroy();
               perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
           }
           var $searchInput = $('div.dataTables_filter input');
           $searchInput.unbind();
           $searchInput.bind('keyup', function(e) {
               if(this.value.length > 2 || this.value.length==0) {
                   table.search( this.value ).draw();
               }
           });
       },
       scrollX: 'auto',
       fnDrawCallback: function( oSettings ) { //on paginition page 2,3.. often scroll shown, so reset it and assign it again
           if(areWeUsingScroll)
           {
               if (perscroll) perscroll.destroy();
               perscroll = new PerfectScrollbar('#mytable_wrapper .dataTables_scrollBody');
           }
       }
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

    $(document).on('click','#delete_visit_url', function() {
       var id = $(this).data('id'); 
       $.ajax({
           url: delete_session_visit_url,
           method: "POST",
           dataType:'json',
           beforeSend: function(xhr) {
               xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
           },
           data: {
               id: id,
           },
           success: function(response) {
            if(response.error) {
                Swal.fire(global_lang_error,response.message,'error');
                return false;
            } else {
                Swal.fire(global_lang_success,response.message,'success').then((result)=>{
                    if(result.isConfirmed) {
                        table.draw();
                    }
                });

            }
           }
       });
    });

    $(document).on('click', '#filter_data', function(event) {
        event.preventDefault();
        table.draw();
    });

    $(document).on('click', '.domain_related_url_list', function(event) {
        event.preventDefault();

        let load = '<i class="fas fa-spinner fa-spin blue text-center" style="font-size:60px"></i>';
        $(".loaderDiv1").html(load).show();
        $("#listDiv").hide();
        $('#urlLists').hide();
        $("#session_video_lists").modal('show');
        var session_value = $(this).attr('session_value');
        var id = $(this).data('id');

        setTimeout(function(){
            $.ajax({
                url: get_rest_visit_lists,
                type: 'POST',
                data: {session_value,id},
                headers: { 'X-CSRF-TOKEN': csrf_token },
                success: function(response) {
                    $(".loaderDiv1").html('').hide();
                    $("#listDiv").show();
                    $("#urlLists").show();
                    $("#urlLists").html(response);
                }
            })
        },1000);

    });
    $(document).on('click', '.download_record', function(event) {
        event.preventDefault();
        let load1 = '<i class="fas fa-spinner fa-spin blue text-center text-primary" style="font-size:50px"></i> <p>'+ download_file_generating+'</p>';
        $(".loaderDiv2").html(load1).show();
        $("#downloadDiv").hide();
        $("#videoDownloadLink").hide();
        $("#video_download").modal('show');
        var session_value = $(this).attr('session_value');
        var id = $(this).data('id');

        setTimeout(function(){
            $.ajax({
                url: get_video_download_link,
                type: 'POST',
                data: {session_value,id},
                headers: { 'X-CSRF-TOKEN': csrf_token },
                success: function(response) {
                    $(".loaderDiv2").html('').hide();
                    $("#downloadDiv").show();
                    $("#videoDownloadLink").show();
                    $("#videoDownloadLink").html(response);
                }
            })
        },1000);

    });


    $("#session_video_lists").on('hidden.bs.modal',function(e) {
        e.preventDefault();
        $(".loaderDiv1").show();
    });

    $("#play_session_video_modal").on('hidden.bs.modal',function(e) {
        e.preventDefault();
        $("#videoSection").hide().html('');
    });


    $(document).on('click', '.play_record', function(event) {
        event.preventDefault();

        let load = '<i class="fas fa-spinner fa-spin blue text-center text-primary" style="font-size:50px"></i> <p>'+ videoisrendering+'</p>';
        $(".loaderDiv").html(load).show();
        $("#videoSection").hide().html('');
        $("#video_session_information").hide();
        let id = $(this).data('id');
        play_session_video(id);
        
    });

    function play_session_video(id){
        var temp_id = id+1;
        let width = 1024;
        let height = 576;
        let currentDevice = check_device();
        if(currentDevice==="tablet") {
            width = 550;
            height = 500;
        } else if(currentDevice==="mobile") {
            width = 360;
            height = 500;
        }
        $.ajax({
            url: get_corresponding_url_session_data,
            type: 'POST',
            dataType: 'json',
            data: {id: id},
            headers: { 'X-CSRF-TOKEN': csrf_token },
            success:function(response) {
                $("#videoSection").hide().html('');
                $("#info_html").html(response.info_html);
                $("#video_session_information").show();
                $("#videoSection").show();
                $(".loaderDiv").html('').hide();

                var events = JSON.parse(response.session_data);
                const component = new rrwebPlayer({
                    target: document.getElementById("videoSection"),
                    data: {
                        events,
                        width: width,
                        height: height,
                        autoPlay: true,
                        speedOption:[1,2,4]
                    }
                });
            }
        });

       $("#play_session_video_modal").modal('show');
    }

    $(document).on('click', '.download_record', function(event) {
        event.preventDefault();
        /* Act on the event */
    });

    $(document).on('click', '.delete_record', function(event) {
        event.preventDefault();
        
        Swal.fire({
            title: global_lang_confirmation,
            text: global_lang_delete_confirmation,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
            showCancelButton: true,
        })
        .then((result) => {
            if (result.isConfirmed) {
                var table_id = $(this).data('id');

                $.ajax({
                    type:'POST',
                    url:domain_session_data_delete,
                    data:{table_id:table_id},
                    headers:{'X-CSRF-TOKEN':csrf_token},
                    success:function(response)
                    { 
                        if(response=='1') {
                            toastr.success(global_lang_deleted_successfully,global_lang_success,{'positionClass':'toast-bottom-right'});
                            table.draw();
                        } else {
                            toastr.error(global_lang_something_wrong,global_lang_error,{'positionClass':'toast-bottom-right'});
                        }
                    }
                });
            } 
        });
    });
});
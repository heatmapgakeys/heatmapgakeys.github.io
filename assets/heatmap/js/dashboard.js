"user strict";

var idleTime = 0;

setInterval(timerIncrement, 1000); // 1 sec
function timerIncrement() {
    idleTime = idleTime + 1;
    document.addEventListener('scroll', reset_idletime, true);
    document.addEventListener('mousemove', reset_idletime, true);
    document.addEventListener('keypress', reset_idletime, true);
    document.addEventListener('ontouchstart', reset_idletime, true);
    document.addEventListener('onclick', reset_idletime, true);
    document.addEventListener('onkeydown', reset_idletime, true);
    document.addEventListener('onkeyup', reset_idletime, true);
}
function reset_idletime() { idleTime = 0; }

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

function getDashboardData() 
{
    $.ajax({
        url: get_dashboard_data,
        type: 'POST',
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function(xhr){
            xhr.setRequestHeader('X-CSRF-TOKEN',csrf_token)
        },
        success:function(response){
            $(".waiters").hide().html('');
            $("#error").hide();

            $("#avg_stay_time").html(response.average_stay_time);
            $("#session_info").html(response.session_info);
            if(areWeUsingScroll){                    
                setTimeout(function(){
                    $("#session_info").getNiceScroll().remove();
                    $("#session_info").niceScroll({
                        cursorcolor:"#eee"
                }); }, 500);
            }
            $("#total_sessions").html(response.total_session);
            $("#unique_user").html(response.total_new_user);
            $("#returinig_user").html(response.total_returning_user);
            $("#total_clicks").html(response.total_clicks);
            $("#total_page_view").html(response.total_page_view);
            $(".total_live_users").html('0');
            $(".total_mobile_user").html('0');
            $(".total_pc_user").html('0');

            if(response.url_wise_live.length > 0) {
                render_live_user_table(response.url_wise_live);
                $(".total_live_users").html(response.total_live_user);
                $(".total_mobile_user").html(response.total_mobile_user);
                $(".total_pc_user").html(response.total_pc_user);
            } else {
                $("#liveUsersLists ul").html('');
                if(areWeUsingScroll){                    
                    setTimeout(function(){
                        $("#liveUsersLists").getNiceScroll().remove();
                        $("#liveUsersLists").niceScroll({
                            cursorcolor:"#eee"
                    }); }, 500);
                }
                $("#error").show();
            }
        }
    })
}


function render_live_user_table(data) {
    let i = 1;
    let str = '';
    let d_url = '';
    $.each(data, function(index, val) {
        d_url = val.url.replace(/^.*\/\/[^\/]+/, '');
        str += ' <li class="list-group-item d-flex justify-content-between align-items-start">';
        str += '<div class="ms-2 me-auto">';
        str += '<div class="mb-2"><i class="far fa-circle text-success"></i> '+val.page_title+'</div>'
        str += '<a target="_blank" class="ps-3" href="'+val.url+'">'+d_url+'</a>';
        str += '</div><span class="badge bg-success rounded-pill py-2 mt-2">'+val.session+'</span>';
        str += '</li>';

        i++;
    });
    $("#liveUsersLists ul").html(str);
    if(areWeUsingScroll){                    
        setTimeout(function(){
            $("#liveUsersLists").getNiceScroll().remove();
            $("#liveUsersLists").niceScroll({
                cursorcolor:"#eee"
        }); }, 500);
    }
}

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


var table = '';
var perscroll;
$(document).ready(function() {

    var domain_traffic_overview_chart = document.getElementById("domain_traffic_overview").getContext("2d");
    var domain_traffic_chart_color = domain_traffic_overview_chart.createLinearGradient(0, 0, 0, 120);
    domain_traffic_chart_color.addColorStop(0, 'rgba(255, 78, 0,.8)');
    domain_traffic_chart_color.addColorStop(1, 'rgba(255, 78, 0,.3)');
    var domain_traffic_overview_bar = new Chart(domain_traffic_overview_chart, {
        data: {
            labels: domain_traffic_chart_labels,
            datasets: [{
                type: 'line',
                label: numberof_session,
                data: domain_traffic_chart_values,
                borderColor: 'rgba(255, 78, 0,1)',
                backgroundColor: domain_traffic_chart_color,
                pointBackgroundColor: 'rgba(255, 78, 0,1)',
                borderWidth:2,
                pointRadius: 2,
                pointHoverRadius: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                yAxes: [{
                    gridLines: {
                        drawBorder: false,
                        display: false
                    },
                    ticks: {
                        beginAtZero: true,   
                        fontColor: "#686868"
                    },
                }],
                xAxes: [{
                    offset: true,
                    ticks: {
                        beginAtZero: true,
                        fontColor: "#686868",
                        stepSize: domain_traffic_chart_step_size
                    },
                    gridLines: {
                        display: false
                    },
                    barPercentage: 0.5
                }]
            },
            legend: {
                display: false,
                position: 'bottom'
            },
            elements: {
                point: {
                    radius: 2
                }
            }
        }
    });
    var domain_summary = document.getElementById("domain_summary").getContext('2d');
    var domain_summary_color = domain_summary.createLinearGradient(0, 0, 0, 150);
    domain_summary_color.addColorStop(0, 'rgb(106, 0, 91,.5)');
    domain_summary_color.addColorStop(1,'rgb(13, 139, 241,.5)');

    var myChart = new Chart(domain_summary, {
      type: 'line',
      data: {
        labels: domain_summary_chart_labels,
        datasets: [{
          label: numberof_session,
          data: domain_summary_chart_values,
          backgroundColor: domain_summary_color,
          borderWidth: 1,
          borderColor: 'rgb(106, 0, 91,.2)',
          pointBorderWidth: 0,
          pointBorderColor: 'transparent',
          pointRadius: 3,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(63,82,227,1)',
        }]
      },
      options: {
        layout: {
          padding: {
            bottom: -10,
            left: -10
          }
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              display: false,
              drawBorder: false,
            },
            ticks: {
              beginAtZero: true,
              display: false
            }
          }],
          xAxes: [{
            gridLines: {
              drawBorder: false,
              display: false,
            },
            ticks: {
              display: false
            }
          }]
        },
      }
    });


    var referrer_lists_pie = $("#referrer_lists_pie_chart").get(0).getContext("2d");
    var referrer_lists_pie_chart = new Chart(referrer_lists_pie, {
      type: 'pie',
      data: {
        datasets: [{
          data: domain_refferer_lists_values,
          backgroundColor: ['#9BBFE0','#E8A09A','#FBE29F','#C6D68F','#47B39C'],
          borderColor: ['#9BBFE0','#E8A09A','#FBE29F','#C6D68F','#47B39C'],
        }],

        // These labels appear in the legend and in the tooltips when hovering different arcs
        labels: domain_refferer_lists_labels
      },
      options: {
        cutoutPercentage: 75,
        responsive: true,
        animation: {
          animateScale: true,
          animateRotate: true
        },
        legend: {
          display: false
        },
      }
    });

    $("#error").show();
    getDashboardData();

    setInterval(function(){
        if (idleTime < 300)
        {
            $(".waiters").show().html('<i class="fas fa-circle-notch fa-spin"></i>');
            getDashboardData();
        }
    },6000);

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

    $(document).on('click', '.event_buttons_item', function(event) {
        event.preventDefault();
        let domain_id = $(this).attr("domain_id");
        let eventType;
        if($(this).hasClass("play_recording")){
            eventType = "pause";
            $("#domain_status").html('<i class="fas fa-record-vinyl text-success"></i> Recording')
            $(".event_buttons .play_recording").addClass('active');
            $(".event_buttons .pause_recording").removeClass('active');
        } else if($(this).hasClass("pause_recording")) {
            eventType = "play";
            $("#domain_status").html('<i class="fas fa-stop text-danger"></i> Stopped')
            $(".event_buttons .pause_recording").addClass('active');
            $(".event_buttons .play_recording").removeClass('active');
        }

        $.ajax({
            url: play_pause_domain,
            type: 'POST',
            data: {eventType,domain_id},
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token)
            },
            success: function(response) {
                if(eventType=='pause') {
                    toastr.success(startedRecording,'',{'positionClass':'toast-bottom-right'});
                }
                else {
                    toastr.error(stoppedRecording,'',{'positionClass':'toast-bottom-right'});
                }
                getDashboardData();
            }
        })
        
    });


    $(document).on('click', '#domain_list a', function(event) {
        event.preventDefault();
        var domain_id = $(this).attr('id'); 
        var domain_name = $(this).text();             
        $("#domain-dropdown").text(domain_name)  
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
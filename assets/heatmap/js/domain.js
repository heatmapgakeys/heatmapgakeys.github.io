"user strict";

var active_domain_id_session = active_domain_id_session;
$('.nav-pills,#bot_list_ul').niceScroll();

function generateRandomData(len, x_values, y_values, click_counts, height_values, width_values) {
	var heatmap_image = document.querySelector('#heatmap_image');
	var img_width = heatmap_image.offsetWidth;
	var img_height = heatmap_image.offsetHeight;

	var points = [];
	var i;
	for (i = 0; i < len; i++) {
		x_value = (x_values[i] * img_width) / width_values[i];
		x_value = parseInt(x_value);
		y_value = (y_values[i] * img_height) / height_values[i];
		y_value = parseInt(y_value);

		points[i] =
		{
			x: x_value,
			y: y_value,
			value: parseInt(click_counts[i]),
		}
	}

	var data = { max: 100, data: points };
	return data;
}

function check_all_search_value(run_both) {
	var myAttributeValue
	var myElement = document.getElementById('my-element');
	myAttributeValue = myElement.getAttribute('data-my-attribute');
	if(myAttributeValue == 'error'){
		$("#total_unique_sessions").html('0');
		$("#average_stay_time").html('0');
		$("#total_clicks").html('0');
		 
		$("#error").show();
		$(".custom_tooltip").hide();
		$("#error #error_message").html();
		$("#waiting_spin").hide();
		$("#image_holder").hide();
		$("#scroll_image").hide();
        return false;
	}
	else{
		var heatmap_value = JSON.parse(myAttributeValue);
		$("#total_unique_sessions").html(heatmap_value.total_unique_sessions);
		$("#average_stay_time").html(heatmap_value.average_stay_time);
		$("#total_clicks").html(heatmap_value.total_clicks);
		if(heatmap_value.error){
			$("#error").show();
			$(".custom_tooltip").hide();
			$("#error #error_message").html(heatmap_value.message);
			$("#waiting_spin").hide();
			$("#image_holder").hide();
			$("#scroll_image").hide();
	        return false;
		}

		if(!heatmap_value.error){
			$("#error").hide()
			$(".custom_tooltip").show();
			$("#waiting_spin").hide();
			if(heatmap_value.event_type =='scroll'){
				$("#heatmap_image").attr("src",'')
				$("#image_holder").hide();
				$("#scroll_image").show();
				var positionData = heatmap_value.positionData;

				positionData = JSON.parse(positionData);

				var image_path = heatmap_value.image_path;

				new Heatmap(
				  'target',
				  image_path,
				  positionData,
				  {
					screenshotAlpha: 0.9,
				    heatmapAlpha: 0.6,
				    colorScheme: 'simple'
				  }
				);
			}
		
			else{
				$("#heatmap_image").attr("src",heatmap_value.image_src)
				$("#image_holder").show();
				$("#scroll_image").hide();

				var x_values = heatmap_value.x_values;
				var y_values = heatmap_value.y_values;
				var click_counts = heatmap_value.click_counts;
				var height_values = heatmap_value.height_values;
				var width_values = heatmap_value.width_values;
				var num_of_rows = heatmap_value.num_of_rows;
				setTimeout(function () {
					var heatmapInstance = h337.create({
						container: document.querySelector('#image_holder'),
						gradient: {
							// enter n keys between 0 and 1 here
							// for gradient color customization
							'.5': 'blue',
							'.8': 'red',
							'.95': 'white'
						}
					});

					if($("#heatmap_image").next().next().length > 0) {
						$("#heatmap_image").next().remove();
					}
					
					var data = generateRandomData(num_of_rows, x_values, y_values, click_counts, height_values, width_values);
					heatmapInstance.setData(data);
				}, 2000);
			}
		}
		}
	

}


$(document).ready(function () {

	var initDeviceType = $('.device_type.active').attr('device_type');

	$('.device-type-field').val(initDeviceType);
	$("#waiting_spin").show();
	$("#image_holder").hide();
	$("#error").hide();
	$(".custom_tooltip").hide();

	$("#image_opacity").on('input', function(e) {
		e.preventDefault();
		$("#heatmap_image").css('opacity', $(this).val());
		$(".tooltiptext").text(opacityText+" : "+$(this).val());
	});

	setTimeout(function(){
		if(initDeviceType=="mobile") {
			$("#image_holder img").attr({'width':'480'});
			$("#image_holder").css({'width':'480px'});
			$("#image_holder").addClass('text-center');

			$("#only_for_mobile").css({
				'display':'flex',
				'justify-content':'center'
			});
		}
		else if(initDeviceType=="tablet") {
			$("#image_holder").addClass('text-center');
			$("#only_for_mobile").css({
				'display':'flex',
				'justify-content':'center'
			});
		}
		else { 
			$("#image_holder img").attr('width','100%');
			$("#image_holder").css({'width':'100%'});
			$("#image_holder").removeClass('text-center');
			$("#only_for_mobile").removeAttr('style');
		}
	},500);


	$(document).on("click", ".device_type", function (e) {
		e.preventDefault();
		var device_type = $('.device_type.active').attr('device_type');
		$('.device-type-field').val(device_type);
	});


	$(document).on("click", ".which_event", function (e) {
		e.preventDefault();
		var event_type = $('.which_event.active').attr('value');
		$('#event_type').val(event_type);
	});



	$(document).on("click", "#submit_filter", function (e) {
		e.preventDefault();

		$("#waiting_spin").show();
		$("#error").hide();
		$("#image_holder").hide();
		var x = "not run";
		check_all_search_value(x);

	});

	$(document).on("change", "#domain_pages_list", function (e) {
		e.preventDefault();
	    var other_page_url = $(this).val();
	    var retake_screenshot_url = other_page_url+'?retake-screenshot=yes';
	    $("#retake_screenshot").attr('href',retake_screenshot_url);
	    $.ajax({
	        url: set_active_url_session,
	        method: "POST",
	        dataType:'json',
	        beforeSend: function(xhr) {
	            xhr.setRequestHeader('X-CSRF-TOKEN', csrf_token);
	        },
	        data: {
	            other_page_url: other_page_url
	        },
	        success: function(response) {
	        	$("#waiting_spin").hide();
	        	// var x = "not run";
	           // check_all_search_value(x);
	        }
	    });
	});

	$('#domain_list a').on('click', function() {
	    var domain_id = $(this).attr('id'); 
	    var domain_name = $(this).text();             
	    $("#domain-dropdown").text(domain_name)  
	    $.ajax({
	    	context:this,
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

	check_all_search_value();

});

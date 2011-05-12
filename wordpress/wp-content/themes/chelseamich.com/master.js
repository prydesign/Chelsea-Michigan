jQuery(document).ready(function($) {

// Custom Calendar JS

	date_heading = $('td.current-day').find('h3.date-heading');
	date_text = date_heading.text();

	date_content = $('td.current-day').find('.event-popup').html();

	$('#calendar-bar').text(date_text);
	$('#calendar-day').html(date_content);


	$('td.has-events').live('mouseenter', function() {
		$(this).children('.popup-wrapper').children('.event-popup').fadeIn('fast');

	    var offset = $(this).children('.popup-wrapper').children('.event-popup').offset().left

	    if(offset < 0) {
   		  // alert(offset);
	 	  $(this).children('.popup-wrapper').children('.event-popup').addClass("flip");
		}		
		// if(offset > 0) {
		//    		  // alert(offset);
		// 	 	  $(this).children('.popup-wrapper').children('.event-popup').removeClass("flip");
		// }
	});
	
	$('td.has-events').live('mouseleave', function() {
		$(this).children('.popup-wrapper').children('.event-popup').fadeOut('fast');
	});
	
	$('td.has-events').live('click', function() {
		// This makes it so when a user clicks on a day it pops up down below in the larger content area
		date_heading = $(this).find('h3.date-heading');
		date_text = date_heading.text();
		
		date_content = $(this).find('.event-popup').html();

		$('#calendar-bar').text(date_text);
		$('#calendar-day').html(date_content);
	});
	


	
	$('.tab-nav li a').click(function() {
		$('#calendar-bar').text('');
		$('#calendar-day').html('');
	});
	
	function resize() {
	  var viewportWidth = window.innerWidth ? window.innerWidth : $(window).width();

	  if (jQuery.browser.msie) {
	    if(parseInt(jQuery.browser.version) == 7) {
	      viewportWidth -= 3;
	    }
	  }

	    $(this).css({'display': 'block', 'left': ''});
	    var offset = $(this).offset().left + $(this).outerWidth(true) + 10;

	    if(offset > viewportWidth) {
	      ie6offset = $.browser.msie && $.browser.version.substr(0,1)<7 ? 30 : 0;
	      $(this).css({
	        'left': -(offset + 10 - viewportWidth) - ie6offset
	      });
		}
	}
	
// VisiTic Master Blasters

	$('#length-slider .slider').slider({change: function(){
			visitic_length = $(this).slider('value');
			visitic_group = $('#group-slider .slider').slider('value');
			visitic_activity = $('#activity-slider .slider').slider('value');
			build_visitics(visitic_length, visitic_group, visitic_activity);
		}
	});
	
	$('#group-slider .slider').slider({change: function(){
			visitic_group = $(this).slider('value');
			visitic_activity = $('#activity-slider .slider').slider('value');
			visitic_length = $('#length-slider .slider').slider('value');
			build_visitics(visitic_length, visitic_group, visitic_activity);
		}
	});
	
	$('#activity-slider .slider').slider({change: function(){
			visitic_activity = $(this).slider('value');
			visitic_group = $('#group-slider .slider').slider('value');
			visitic_length = $('#length-slider .slider').slider('value');
			build_visitics(visitic_length, visitic_group, visitic_activity);
		}
	});
	
	$('#view-all-link').click(function() {
		$('#visitic-results').slideUp('slow');
		$.ajax({
			type: "POST",
			url: '/wp-content/themes/chelseamich.com/visitic-engine.php',
			data: "action=view_all_visitics",
			success: function(data) {
				$('#visitic-results').html(data);
				$('#visitic-results').slideDown('fast');
			}
		});
	});
	
	
	function build_visitics(length, group, activity) {
		$('#visitic-results').slideUp('slow');
		$.ajax({
			type: "POST",
			url: '/wp-content/themes/chelseamich.com/visitic-engine.php',
			data: "length="+length+"&group="+group+"&activity="+activity,
			success: function(data) {
				$('#visitic-results').html(data);
				$('#visitic-results').slideDown('fast');
			}
		});
	}
	
});
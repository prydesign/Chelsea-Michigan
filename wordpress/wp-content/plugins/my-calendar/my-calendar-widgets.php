<?php
class my_calendar_today_widget extends WP_Widget {

function my_calendar_today_widget() {
	parent::WP_Widget( false,$name=__('My Calendar: Today\'s Events','my-calendar') );
}

function widget($args, $instance) {
	extract($args);
	$the_title = apply_filters('widget_title',$instance['my_calendar_today_title']);
	$the_template = $instance['my_calendar_today_template'];
	$the_substitute = $instance['my_calendar_no_events_text'];
	$the_category = ($instance['my_calendar_today_category']=='')?'default':esc_attr($instance['my_calendar_today_category']);
	$widget_title = empty($the_title) ? __('Today\'s Events','my-calendar') : $the_title;
	$the_events = my_calendar_todays_events($the_category,$the_template,$the_substitute);
		if ($the_events != '') {
		  echo $before_widget;
		  echo $before_title . $widget_title . $after_title;
		  echo $the_events;
		  echo $after_widget;
		}
}

function form($instance) {
	$widget_title = esc_attr($instance['my_calendar_today_title']);
	$widget_template = esc_attr($instance['my_calendar_today_template']);
	if (!$widget_template) { $widget_template = get_option('my_calendar_today_template'); }
	$widget_text = esc_attr($instance['my_calendar_no_events_text']);
	$widget_category = esc_attr($instance['my_calendar_today_category']);
?>
	<p>
	<label for="<?php echo $this->get_field_id('my_calendar_today_title'); ?>"><?php _e('Title','my-calendar'); ?>:</label><br />
	<input class="widefat" type="text" id="<?php echo $this->get_field_id('my_calendar_today_title'); ?>" name="<?php echo $this->get_field_name('my_calendar_today_title'); ?>" value="<?php echo $widget_title; ?>"/>
	</p>
	<p>
	<label for="<?php echo $this->get_field_id('my_calendar_today_template'); ?>"><?php _e('Template','my-calendar'); ?></label><br />
	<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('my_calendar_today_template'); ?>" name="<?php echo $this->get_field_name('my_calendar_today_template'); ?>"><?php echo $widget_template; ?></textarea>
	</p>
	<p>
	<label for="<?php echo $this->get_field_id('my_calendar_no_events_text'); ?>"><?php _e('Show this text if there are no events today:','my-calendar'); ?></label><br />
	<input class="widefat" type="text" id="<?php echo $this->get_field_id('my_calendar_no_events_text'); ?>" name="<?php echo $this->get_field_name('my_calendar_no_events_text'); ?>" value="<?php echo $widget_text; ?>" /></textarea>
	</p>
	<p>
	<label for="<?php echo $this->get_field_id('my_calendar_today_category'); ?>"><?php _e('Category or categories to display:','my-calendar'); ?></label><br />
	<input class="widefat" type="text" id="<?php echo $this->get_field_id('my_calendar_today_category'); ?>" name="<?php echo $this->get_field_name('my_calendar_today_category'); ?>" value="<?php echo $widget_category; ?>" /></textarea>
	</p>	
	<?php
}  

	function update($new_instance,$old_instance) {
		$instance = $old_instance;
		$instance['my_calendar_today_title'] = strip_tags($new_instance['my_calendar_today_title']);
		$instance['my_calendar_today_template'] = $new_instance['my_calendar_today_template'];
		$instance['my_calendar_no_events_text'] = strip_tags($new_instance['my_calendar_no_events_text']);
		$instance['my_calendar_today_category'] = strip_tags($new_instance['my_calendar_today_category']);		
		return $instance;		
	}

}

class my_calendar_upcoming_widget extends WP_Widget {

function my_calendar_upcoming_widget() {
	parent::WP_Widget( false,$name=__('My Calendar: Upcoming Events','my-calendar') );
}

function widget($args, $instance) {
	extract($args);
	$the_title = apply_filters('widget_title',$instance['my_calendar_upcoming_title']);
	$the_template = $instance['my_calendar_upcoming_template'];
	$the_substitute = $instance['my_calendar_no_events_text'];
	$before = esc_attr($instance['my_calendar_upcoming_before']);
	$after = esc_attr($instance['my_calendar_upcoming_after']);
	$type = esc_attr($instance['my_calendar_upcoming_type']);
	$the_category = ($instance['my_calendar_upcoming_category']=='')?'default':esc_attr($instance['my_calendar_upcoming_category']);
	$widget_title = empty($the_title) ? __('Upcoming Events','my-calendar') : $the_title;
	$the_events = my_calendar_upcoming_events($before,$after,$type,$the_category,$the_template,$the_substitute);
		if ($the_events != '') {
		  echo $before_widget;
		  echo $before_title . $widget_title . $after_title;
		  echo $the_events;
		  echo $after_widget;
		}
}


function form($instance) {
	$widget_title = esc_attr($instance['my_calendar_upcoming_title']);
	$widget_template = esc_attr($instance['my_calendar_upcoming_template']);
	if (!$widget_template) { $widget_template = get_option('my_calendar_upcoming_template'); }
	$widget_text = esc_attr($instance['my_calendar_no_events_text']);
	$widget_category = esc_attr($instance['my_calendar_upcoming_category']);
	$widget_before = esc_attr($instance['my_calendar_upcoming_before']);
	$widget_after = esc_attr($instance['my_calendar_upcoming_after']);
	$widget_type = esc_attr($instance['my_calendar_upcoming_type']);
?>
	<p>
	<label for="<?php echo $this->get_field_id('my_calendar_upcoming_title'); ?>"><?php _e('Title','my-calendar'); ?>:</label><br />
	<input class="widefat" type="text" id="<?php echo $this->get_field_id('my_calendar_upcoming_title'); ?>" name="<?php echo $this->get_field_name('my_calendar_upcoming_title'); ?>" value="<?php echo $widget_title; ?>"/>
	</p>
	<p>
	<label for="<?php echo $this->get_field_id('my_calendar_upcoming_template'); ?>"><?php _e('Template','my-calendar'); ?></label><br />
	<textarea class="widefat" rows="8" cols="20" id="<?php echo $this->get_field_id('my_calendar_upcoming_template'); ?>" name="<?php echo $this->get_field_name('my_calendar_upcoming_template'); ?>"><?php echo $widget_template; ?></textarea>
	</p>
	<fieldset>
	<legend><?php _e('Widget Options','my-calendar'); ?></legend>

	<p>
	<label for="<?php echo $this->get_field_id('my_calendar_upcoming_type'); ?>"><?php _e('Display upcoming events by:','my-calendar'); ?></label> <select id="<?php echo $this->get_field_id('my_calendar_upcoming_type'); ?>" name="<?php echo $this->get_field_name('my_calendar_upcoming_type'); ?>">
	<option value="events" <?php echo ($widget_type == 'events')?'selected="selected"':''; ?>><?php _e('Events (e.g. 2 past, 3 future)','my-calendar') ?></option>
	<option value="days" <?php echo ($widget_type == 'days')?'selected="selected"':''; ?>><?php _e('Dates (e.g. 4 days past, 5 forward)','my-calendar') ?></option>
	</select>
	</p>
	<p>
	<input type="text" id="<?php echo $this->get_field_id('my_calendar_upcoming_after'); ?>" name="<?php echo $this->get_field_name('my_calendar_upcoming_after'); ?>" value="<?php echo $widget_after; ?>" size="1" maxlength="3" /> <label for="<?php echo $this->get_field_id('my_calendar_upcoming_after'); ?>"><?php _e('events into the future;','my-calendar'); ?></label><br />
	<input type="text" id="<?php echo $this->get_field_id('my_calendar_upcoming_before'); ?>" name="<?php echo $this->get_field_name('my_calendar_upcoming_before'); ?>" value="<?php echo $widget_before; ?>" size="1" maxlength="3" /> <label for="<?php echo $this->get_field_id('my_calendar_upcoming_after'); ?>"><?php _e('events from the past','my-calendar'); ?></label>
	</p>

	<p>
	<label for="<?php echo $this->get_field_id('my_calendar_no_events_text'); ?>"><?php _e('Show this text if there are no events meeting your criteria:','my-calendar'); ?></label><br />
	<input class="widefat" type="text" id="<?php echo $this->get_field_id('my_calendar_no_events_text'); ?>" name="<?php echo $this->get_field_name('my_calendar_no_events_text'); ?>" value="<?php echo $widget_text; ?>" /></textarea>
	</p>
	<p>
	<label for="<?php echo $this->get_field_id('my_calendar_upcoming_category'); ?>"><?php _e('Category or categories to display:','my-calendar'); ?></label><br />
	<input class="widefat" type="text" id="<?php echo $this->get_field_id('my_calendar_upcoming_category'); ?>" name="<?php echo $this->get_field_name('my_calendar_upcoming_category'); ?>" value="<?php echo $widget_category; ?>" /></textarea>
	</p>	
	<?php
}  

	function update($new_instance,$old_instance) {
		$instance = $old_instance;
		$instance['my_calendar_upcoming_title'] = strip_tags($new_instance['my_calendar_upcoming_title']);
		$instance['my_calendar_upcoming_template'] = $new_instance['my_calendar_upcoming_template'];
		$instance['my_calendar_no_events_text'] = strip_tags($new_instance['my_calendar_no_events_text']);
		$instance['my_calendar_upcoming_category'] = strip_tags($new_instance['my_calendar_upcoming_category']);		
		$instance['my_calendar_upcoming_before'] = strip_tags($new_instance['my_calendar_upcoming_before']);
		$instance['my_calendar_upcoming_after'] = strip_tags($new_instance['my_calendar_upcoming_after']);
		$instance['my_calendar_upcoming_type'] = strip_tags($new_instance['my_calendar_upcoming_type']);
		return $instance;		
	}

}

// Widget upcoming events
function my_calendar_upcoming_events($before='default',$after='default',$type='default',$category='default',$template='default',$substitute='') {
  global $wpdb,$default_template;
  // This function cannot be called unless calendar is up to date
	check_my_calendar();
	$offset = (60*60*get_option('gmt_offset'));	
    $defaults = get_option('my_calendar_widget_defaults');
	$display_upcoming_type = ($type == 'default')?$defaults['upcoming']['type']:$type;
	if ($display_upcoming_type == '') { $display_upcoming_type = 'event'; }
    // Get number of units we should go into the future
	$after = ($after == 'default')?$defaults['upcoming']['after']:$after;
	if ($after == '') { $after = 10; }
	// Get number of units we should go into the past
	$before = ($before == 'default')?$defaults['upcoming']['before']:$before;
	if ($before == '') { $before = 0; }
	$category = ($category == 'default')?'':$category;
	$template = ($template == 'default')?$defaults['upcoming']['template']:$template;
	if ($template == '' ) { $template = "$default_template"; };
	$no_event_text = ($substitute == '')?$defaults['upcoming']['text']:$substitute;
  
    $day_count = -($before);
	$output = "<ul>";
	if ($display_upcoming_type == "days") {
		$temp_array = array();
		while ($day_count < $after+1) {
			list($y,$m,$d) = split("-",date("Y-m-d",mktime($day_count*24,0,0,date("m",time()+$offset),date("d",time()+$offset),date("Y",time()+$offset))));
			$events = my_calendar_grab_events( $y,$m,$d,$category );
			$current_date = "$y-$m-$d";
			@usort($events, "my_calendar_time_cmp");
			if (count($events) != 0) {
				foreach($events as $event) {
					$event_details = event_as_array($event);
					$date_diff = jd_date_diff( strtotime($event_details['date']),strtotime($event_details['enddate']));
					
					if (get_option('my_calendar_date_format') != '') {
						$date = date_i18n(get_option('my_calendar_date_format'),strtotime($current_date));
						$date_end = date_i18n(get_option('my_calendar_date_format'),strtotime(my_calendar_add_date($current_date,$date_diff)));
					} else { 
						$date = date_i18n(get_option('date_format'),strtotime($current_date));
						$date_end = date_i18n(get_option('date_format'),strtotime(my_calendar_add_date($current_date,$date_diff)));
					}
					
					$event_details['date'] = $date;
					$event_details['enddate'] = $date_end;
					
					if ( get_option( 'mc_event_approve' ) == 'true' ) {
						if ( $event->event_approved != 0 ) {$temp_array[] = $event_details;}
					} else {
						$temp_array[] = $event_details;
					}
					// by Roland end
				}			  
			} 
            $day_count = $day_count+1;
     
		}

		if ( get_option('mc_skip_holidays') == 'false') {
			foreach ($temp_array as $details) {
				$output .= "<li>".jd_draw_template($details,$template)."</li>";		  				
			}
		} else {
			// By default, skip no events.
			$skipping = false;
			foreach($temp_array as $details) {
				// if any event this date is in the holiday category, we are skipping
				if ( $details['cat_id'] == get_option('mc_skip_holidays_category') ) {
					$skipping = true;
					break;
				}
			}
			// check each event, if we're skipping, only include the holiday events.
			foreach($temp_array as $details) {
				if ($skipping == true) {
					if ($details['cat_id'] == get_option('mc_skip_holidays_category') ) {
						$output .= "<li>".jd_draw_template($details,$template)."</li>";		  
					}
				} else {
					$output .= "<li>".jd_draw_template($details,$template)."</li>";		  
				}
			}		  
		}		
	} else {
        $events = mc_get_all_events($category);		 // grab all events within reasonable proximity		 	 
		$output .= mc_produce_upcoming_events( $events,$template,$before,$after );
	}
	if ($output != '<ul>') {
		$output .= "</ul>";
		return $output;
	} else {
		return stripcslashes( $no_event_text );
	}	
}
// make this function time-sensitive, not date-sensitive.
function mc_produce_upcoming_events($events,$template,$before=0,$after=10,$type='list') {
		$past = 1;
		$future = 1;
		$offset = (60*60*get_option('gmt_offset'));
		$today = date('Y',time()+($offset)).'-'.date('m',time()+($offset)).'-'.date('d',time()+($offset));		
         @usort( $events, "my_calendar_timediff_cmp" );// sort all events by proximity to current date
	     $count = count($events);
			for ( $i=0;$i<=$count;$i++ ) {
					$beginning = $events[$i]->event_begin . ' ' . $events[$i]->event_time;
					$current = date('Y-m-d H:i',time()+$offset);			
				if ($events[$i]) {
					if ( ( $past<=$before && $future<=$after ) ) {
						$near_events[] = $events[$i]; // if neither limit is reached, split off freely
					} else if ( $past <= $before && ( my_calendar_date_comp( $beginning,$current ) ) ) {
						$near_events[] = $events[$i]; // split off another past event
					} else if ( $future <= $after && ( !my_calendar_date_comp( $beginning,$current ) ) ) {
						$near_events[] = $events[$i]; // split off another future event
					}				
					if ( my_calendar_date_comp( $beginning,$current ) ) {
						$past++;
					} elseif  ( my_calendar_date_equal( $beginning,$current ) ) {
						$present = 1;
					} else {
						$future++;
					}
					if ($past > $before && $future > $after) {
						break;
					}
				}
			}
		  $events = $near_events;
		  @usort( $events, "my_calendar_datetime_cmp" ); // sort split events by date
		if ( is_array( $events ) ) {
          foreach( $events as $event ) {
		    $event_details = event_as_array( $event );
				if ( get_option( 'mc_event_approve' ) == 'true' ) {
					if ( $event->event_approved != 0 ) {$temp_array[] = $event_details; }
				} else {
					$temp_array[] = $event_details;
				}		
        }
		
			if ( get_option('mc_skip_holidays') == 'false') {
				foreach ($temp_array as $details) {
				$date = date('Y-m-d',strtotime($details['date']));
				if (my_calendar_date_comp( $date,$today )===true) {
					$class = "past-event";
				} else {
					$class = "future-event";
				}
				if ( my_calendar_date_equal( $date,$today ) ) {
					$class = "today";
				}
				if ($type == 'list') {
					$prepend = "<li class=\"$class\">";
					$append = "</li>\n";
				} else {
					$prepend = $append = '';
				}				
					$output .= "$prepend".jd_draw_template($details,$template,$type)."$append";		  				
				}
			} else {
				// By default, skip no events.
				$skipping = false;
				foreach($temp_array as $details) {
				$date = date('Y-m-d',strtotime($details['date']));
				if (my_calendar_date_comp( $date,$today )===true) {
					$class = "past-event";
				} else {
					$class = "future-event";
				}
				if ( my_calendar_date_equal( $date,$today ) ) {
					$class = "today";
				}
				if ($type == 'list') {
					$prepend = "<li class=\"$class\">";
					$append = "</li>\n";
				} else {
					$prepend = $append = '';
				}				
					// if any event this date is in the holiday category, we are skipping
					if ( $details['cat_id'] == get_option('mc_skip_holidays_category') ) {
						$skipping = true;
						break;
					}
				}
				// check each event, if we're skipping, only include the holiday events.
				foreach($temp_array as $details) {
					if ($skipping == true) {
						if ($details['cat_id'] == get_option('mc_skip_holidays_category') ) {
							$output .= "$prepend".jd_draw_template($details,$template,$type)."$append";		  
						}
					} else {
						$output .= "$prepend".jd_draw_template($details,$template,$type)."$append";		  
					}
				}		  
			}		
		
        $day_count = $day_count+1;
		} else {
			$output .= '';
		}
	return $output;
}

// Widget todays events
function my_calendar_todays_events($category='default',$template='default',$substitute='') {
	global $wpdb, $default_template;
	$offset = (60*60*get_option('gmt_offset'));  
	// This function cannot be called unless calendar is up to date
	check_my_calendar();
    $defaults = get_option('my_calendar_widget_defaults');
	$template = ($template == 'default')?$defaults['today']['template']:$template;
	if ($template == '' ) { $template = "$default_template"; };	
	$category = ($category == 'default')?$defaults['today']['category']:$category;
	$no_event_text = ($substitute == '')?$defaults['today']['text']:$substitute;

    $events = my_calendar_grab_events(date("Y",time()+$offset),date("m",time()+$offset),date("d",time()+$offset),$category);
	if (count($events) != 0) { $output = "<ul>"; }
	
    @usort($events, "my_calendar_time_cmp");
        foreach($events as $event) {
		    $event_details = event_as_array($event);

				if (get_option('my_calendar_date_format') != '') {
				$date = date_i18n(get_option('my_calendar_date_format'),time()+$offset);
				} else {
				$date = date_i18n(get_option('date_format'),time()+$offset);
				}	
			// correct displayed time to today
			$event_details['date'] = $date;
			if ( get_option('mc_skip_holidays') == 'false' ) {
				if ( get_option( 'mc_event_approve' ) == 'true' ) {
					if ( $event->event_approved != 0 ) {$output .= "<li>".jd_draw_template($event_details,$template)."</li>";}
				} else {
					$output .= "<li>".jd_draw_template($event_details,$template)."</li>";
				}
			} else if ( $event->event_category == get_option('mc_skip_holidays_category') ) {
				if ( get_option( 'mc_event_approve' ) == 'true' ) {
					if ( $event->event_approved != 0 ) {$output .= "<li>".jd_draw_template($event_details,$template)."</li>";}
				} else {
					$output .= "<li>".jd_draw_template($event_details,$template)."</li>";
				}
			}
        }
    if (count($events) != 0) {
		$output .= "</ul>";
        return $output;
    } else {
		return stripcslashes( $no_event_text );
	}
}

?>
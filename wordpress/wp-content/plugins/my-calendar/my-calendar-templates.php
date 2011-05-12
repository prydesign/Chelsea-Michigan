<?php
function jd_draw_template($array,$template,$type='list') {
	//1st argument: array of details
	//2nd argument: template to print details into
	foreach ($array as $key=>$value) {
	    $search = "{".$key."}";
		if ($type != 'list') {
			if ($key == 'link' && $value == '') { $value = get_bloginfo('url'); }
			$value = htmlentities($value);
		}
		$template = stripcslashes(str_replace($search,$value,$template));
		$rss_search = "{rss_$key}";
		$value = utf8_encode(htmlentities( $value,ENT_COMPAT,get_bloginfo('charset') ) );
		$template = stripcslashes(str_replace($rss_search,$value,$template));
	}
	return $template;
}

// Draw an event but customise the HTML for use in the widget
function event_as_array($event) {
	global $wpdb,$wp_plugin_dir,$wp_plugin_url;
	// My Calendar must be updated to run this function
	check_my_calendar();
	$details = array();

	$offset = (60*60*get_option('gmt_offset'));  
	$category_name = $event->category_name;
	$category_color = $event->category_color;
	$category_icon = $event->category_icon;

	if ( file_exists( $wp_plugin_dir . '/my-calendar-custom/' ) ) {
		$path = '/my-calendar-custom';
	} else {
		$path = '/'.dirname(plugin_basename(__FILE__)).'/icons';
	}
	$category_icon = $wp_plugin_url . $path . '/' . $category_icon;

	$e = get_userdata($event->event_author);
	$host = get_userdata($event->event_host);

	$hcard = "<div class=\"address vcard\">";
	$hcard .= "<div class=\"adr\">";
	if ($event->event_label != "") {
		$hcard .= "<strong class=\"org\">".$event->event_label."</strong><br />";
	}					
	if ($event->event_street != "") {
		$hcard .= "<div class=\"street-address\">".$event->event_street."</div>";
	}
	if ($event->event_street2 != "") {
		$hcard .= "<div class=\"street-address\">".$event->event_street2."</div>";
	}
	if ($event->event_city != "") {
		$hcard .= "<span class=\"locality\">".$event->event_city.",</span>";
	}						
	if ($event->event_state != "") {
		$hcard .= "<span class=\"region\">".$event->event_state."</span> ";
	}
	if ($event->event_postcode != "") {
		$hcard .= " <span class=\"postal-code\">".$event->event_postcode."</span>";
	}	
	if ($event->event_country != "") {
		$hcard .= "<div class=\"country-name\">".$event->event_country."</div>";
	}	
	$hcard .= "</div>\n</div>";	
	$map_string = $event->event_street.' '.$event->event_street2.' '.$event->event_city.' '.$event->event_state.' '.$event->event_postcode.' '.$event->event_country;	
	if ( strlen( trim( $map_string ) ) > 0 ) {
		$map_string = str_replace(" ","+",$map_string);
		if ($event->event_label != "") {
			$map_label = $event->event_label;
		} else {
			$map_label = $event->event_title;
		}
		$zoom = ($event->event_zoom != 0)?$event->event_zoom:'15';
		
		if ($event->event_longitude != '0.000000' && $event->event_latitude != '0.000000') {
			$map_string = "$event->event_latitude,$event->event_longitude";
		}
		$map = "<a href=\"http://maps.google.com/maps?f=q&z=$zoom&q=$map_string\">Map<span> to $map_label</span></a>";
	} else {
		$map = "";
	}
	
	$real_end_date = $event->event_end;

	if ( get_option('my_calendar_date_format') != '' ) {
		$date = date_i18n( get_option('my_calendar_date_format'),strtotime( $event->event_begin ) );
		$date_end = date_i18n( get_option('my_calendar_date_format'),strtotime($real_end_date) );
	} else {
		$date = date_i18n( get_option('date_format'),strtotime( $event->event_begin ) );
		$date_end = date_i18n( get_option('date_format'),strtotime($real_end_date) );
	}
	
	if ( $event->event_time == '00:00:00' ) {
		$details['time'] = get_option( 'my_calendar_notime_text' );
	} else {
		$details['time'] = date(get_option('time_format'),strtotime($event->event_time));
	}
	
	$tz = mc_user_timezone();
	if ($tz != '') {
		$local_begin = date_i18n( get_option('time_format'), strtotime($event->event_time ."+$tz hours") );
		$details['usertime'] = "$local_begin";
	} else {
		$details['usertime'] = '';
	}
	if ($event->event_endtime == '00:00:00' ) {
		$details['endtime'] = '';
	} else {
		$details['endtime'] = date( get_option('time_format'),strtotime($event->event_endtime));
	}
	if ($event->event_endtime == '00:00:00') {
		$endtime = '23:59:00';
	} else {
		$endtime = $event->event_endtime;
	}
	$offset = get_option('gmt_offset'); // reset offset in hours
	$os = strtotime($event->event_begin .' '. $event->event_time);
	$oe = strtotime($real_end_date  .' '. $endtime );

	$dtstart = date("Ymd\THi00", mktime(date('h',$os)+$offset,date('i',$os), date('s',$os), date('m',$os),date('d',$os), date('Y',$os) ) ).'Z'; 
	$dtend = date("Ymd\THi00", mktime(date('h',$oe)+$offset,date('i',$oe), date('s',$oe), date('m',$oe),date('d',$oe), date('Y',$oe) ) ).'Z';
	$details['ical_start'] = $dtstart;
	$details['ical_end'] = $dtend;
	$details['rssdate'] = date( 'D, d M Y H:i:s +0000', strtotime( $date .' '. $details['time'] ) );	
	$details['date'] = $date;
	$details['enddate'] = $date_end;	
	// end dates
	
	$details['cat_id'] = $event->event_category;
	$details['category'] = stripslashes($event->category_name);
	$details['title'] = stripslashes($event->event_title);
	$details['author'] = $e->display_name;
	$details['host'] = $host->display_name;
	$details['host_email'] = $host->user_email;
	if ($host->display_name == '') { $details['host'] = $e->display_name; }
	if ($host->user_email == '') { $details['host_email'] = $e->user_email; }	
	if ( $event->event_link_expires == 0 ) {
		$details['link'] = $event->event_link;
	} else {
		if ( my_calendar_date_comp( date('Y-m-d',$real_end_date), date('Y-m-d',time()+$offset ) ) ) {
			$details['link'] = '';
		} else {
			$details['link'] = $event->event_link;
		}
	}
	if ( $event->event_open == '1' ) {
		$event_open = get_option( 'mc_event_open' );
	} else if ( $event->event_open == '0' ) { $event_open = get_option( 'mc_event_closed' ); } else { $event_open = '';	}
	$details['description'] = stripslashes($event->event_desc);
	$details['link_title'] = ($details['link'] != '')?"<a href='".$event->event_link."'>".stripslashes($event->event_title)."</a>":stripslashes($event->event_title);
	$details['location'] = stripslashes($event->event_label);
	$details['street'] = stripslashes($event->event_street);
	$details['street2'] = stripslashes($event->event_street2);
	$details['city'] = stripslashes($event->event_city);
	$details['state'] = stripslashes($event->event_state);
	$details['postcode'] = stripslashes($event->event_postcode);
	$details['country'] = stripslashes($event->event_country);
	$details['hcard'] = stripslashes($hcard);
	$details['link_map'] = $map;
	$details['shortdesc'] = stripslashes($event->event_short);
	$details['event_open'] = $event_open;
	$details['icon'] = $category_icon;
	$details['color'] = $category_color;
	$details['event_status'] = ($event->event_approve == 1 )?__('Published','my-calendar'):__('Reserved','my-calendar');
	
	// RSS guid
	$details['guid'] = sanitize_title($event->event_title).'-'.rand(100000000,999999999);
	/* ical format */
	$details['ical_location'] = $event->event_label .' '. $event->event_street .' '. $event->event_street2 .' '. $event->event_city .' '. $event->event_state .' '. $event->event_postcode;
	$ical_description = mc_newline_replace(strip_tags($event->event_desc));
	$details['ical_description'] = str_replace( "\r", "=0D=0A=", $event->event_desc );	
	$details['ical_desc'] = $ical_description;
	return $details;
}
function mc_newline_replace($string) {
  return (string)str_replace(array("\r", "\r\n", "\n"), '', $string);
}

if ( !function_exists('shortcode_parse_atts') ) {
	function shortcode_parse_atts($text) {
			$atts = array();
			$pattern = '/(\w+)\s*=\s*"([^"]*)"(?:\s|$)|(\w+)\s*=\s*\'([^\']*)\'(?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
			$text = preg_replace("/[\x{00a0}\x{200b}]+/u", " ", $text);
			if ( preg_match_all($pattern, $text, $match, PREG_SET_ORDER) ) {
					foreach ($match as $m) {
							if (!empty($m[1]))
									$atts[strtolower($m[1])] = stripcslashes($m[2]);
							elseif (!empty($m[3]))
									$atts[strtolower($m[3])] = stripcslashes($m[4]);
							elseif (!empty($m[5]))
									$atts[strtolower($m[5])] = stripcslashes($m[6]);
							elseif (isset($m[7]) and strlen($m[7]))
									$atts[] = stripcslashes($m[7]);
							elseif (isset($m[8]))
									$atts[] = stripcslashes($m[8]);
					}
			} else {
					$atts = ltrim($text);
			}
			return $atts;
	}
}

?>
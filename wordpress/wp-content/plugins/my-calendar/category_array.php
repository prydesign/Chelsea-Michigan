$events = $wpdb->get_results("
SELECT * FROM " . MY_CALENDAR_TABLE . " WHERE event_begin <= '$date' AND event_end >= '$date' AND event_recur = 'S' ORDER BY event_id"); 

if (!empty($events)) {
	foreach($events as $event) {
		$this_event_start = strtotime("$event->event_begin $event->event_time");
		$this_event_end = strtotime("$event->event_end $event->event_endtime");
		$event->event_start_ts = $this_event_start;
		$event->event_end_ts = $this_event_end;
		
		// Lets get our cats out!
		
		// echo '<pre>';
		// print_r($event);
		// echo '</pre>';
		
		if (strlen($event->event_category) > 2) {
			$event_cats = explode(',', $event->event_category);	
			$i = 1;
			foreach ($event_cats as $event_cat) {
			
				if ($event_cat == $select_category) {
					if ($i == 1) {
						$arr_events[]=$event;
					} // if
				$i++;
				} //if
			} //foreach
			
		} else {
			if ($event->event_category == $select_category) {
				$arr_events[]=$event;
			} // if
		}// if
	} // foreach			
} // if
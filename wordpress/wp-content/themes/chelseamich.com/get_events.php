<?php

$date = $_REQUEST['date'];
$category = $_REQUEST['category'];

if($category == 'all') {} else {$cat_name = 'wp_my_calendar_events_categories.category_id = \''.$category.'\' AND';}

$sql = "select * from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id WHERE $cat_name event_begin <= '$date' AND event_end >= '$date'"; 

$events = $wpdb->get_results($sql);
$output = '';
?>
	
	
	
<?php return $output; ?>
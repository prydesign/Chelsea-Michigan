<?php
require("../../../wp-includes/wp-db.php");
require("../../../wp-blog-header.php");
global $wpdb;

if ($_POST['action'] == 'view_all_visitics') {view_all_visitics();}

function view_all_visitics() {
	global $wpdb;
	$sql = "select * from visitics";
	$visitics = $wpdb->get_results($sql);
	
	// echo '<pre>';
	// print_r($visitics);
	// echo '</pre>';
	$i=0;
	
	foreach ($visitics as $visitic) {		
		if ($i >= count($visitics)) {		
			$output .= '<div class="visitic">';
				$output .= '<a href="'.$visitic->file_path.'" title="'.$visitic->visitic_name.'">';
					$output .= '<img src="'.$visitic->preview_image.'" alt="'.$visitic->visitic_name.'" />';
				$output .= '</a>';
			$output .= '</div>';
			$i++;
		}
	}
	echo $output;
}


$length = $_POST['length'];
$group = $_POST['group'];
$activity = $_POST['activity'];

// Assign the proper names for our trip attributes/tags
switch($length) {
	case 0:
		$length = 6;
		break;
	case 1:
		$length = 7;
		break;
	case 2:
		$length = 8;
		break;
}

switch($group) {
	case 0:
		$group = 1;
		break;
	case 1:
		$group = 2;
		break;
	case 2:
		$group = 4;
		break;
	case 3:
		$group = 3;
		break;
	case 4:
		$group = 5;
		break;
}

switch($activity) {
	case 0:
		$activity = 9;
		break;
	case 1:
		$activity = 10;
		break;
	case 2:
		$activity = 11;
		break;
}

// Here are our queries, lets build our visitic array

$sql = "select * from visitics join visitics_tags on visitics.id = visitics_tags.visitic_id join tags on visitics_tags.tag_id = tags.tag_id WHERE tags.tag_id = $length";
$filtered_visitics = $wpdb->get_results($sql);

// echo '<pre>';
// print_r($filtered_visitics);
// echo '</pre>';

// The supplied values
$params = array($length, $group, $activity);
$results = array();
if(!empty($filtered_visitics) && isset($filtered_visitics)) {
	foreach ($filtered_visitics as $visitic) {
	
		// Deal with those weird duplicate results
		$visitic_ids[] = $visitic->visitic_id;
		
		// echo 'visitic_ids';
		// echo '<pre>';
		// print_r($visitic_ids);
		// echo '</pre>';

		$visitic_cats = get_visitic_cats($visitic->visitic_id);
		
		// The categories associated with the current visitic
			foreach($visitic_cats as $cat) {
				$cat_array[] = $cat->tag_id;
			}

		// echo 'Cats';
		// echo '<pre>';
		// print_r($cat_array);
		// echo '</pre>';
		// echo 'Params';
		// echo '<pre>';
		// print_r($params);
		// echo '</pre>';
		
		if(!empty($visitic_cats) && isset($visitic_cats)) {
			$diff = array_diff($params, $cat_array);			
		}
		// echo '<pre>';
		// print_r($diff);
		// echo '</pre>';
	
		echo '<br/>';
		if (isset($diff) && empty($diff)) {
			if (!in_array($visitic->visitic_id, $results)) { 
				$output .= '<div class="visitic">';
					$output .= '<a href="'.$visitic->file_path.'" title="'.$visitic->visitic_name.'">';
						$output .= '<img src="'.$visitic->preview_image.'" alt="'.$visitic->visitic_name.'" />';
					$output .= '</a>';
				$output .= '</div>';
				$results[] = $visitic->visitic_id;
				// print_r($results);
			}
		}
	}
				echo $output;	
} else {
	echo 'No VisiTics found for that combination, try another!';
}

function get_visitic_cats($tic_id) {
	global $wpdb;
	$sql = "select tags.tag_id from visitics join visitics_tags on visitics.id = visitics_tags.visitic_id join tags on visitics_tags.tag_id = tags.tag_id  WHERE visitics.id = $tic_id";
	$cats = $wpdb->get_results($sql);
	
	return $cats;
	
}

?>
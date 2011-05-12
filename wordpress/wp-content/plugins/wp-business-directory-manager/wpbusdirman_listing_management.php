<?php 

  //   What follows are my attempts to better abstract and compartmentalize the data being passed around.  God help us all.   //
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


//   Function for getting the package information for a given post

function get_package_array($listing_id) {
	
	// Lets build an array of the listing package information so's we can access it.
	// Tha playas be playin'
	
	$listing_package_id = get_post_meta($listing_id, "listing_package", $single=true);
	
	$option_names = array(
		'fee_label'=>'wpbusdirman_settings_fees_label_'.$listing_package_id,
		'fee_amount'=> 'wpbusdirman_settings_fees_amount_'.$listing_package_id,
		'images'=> 'wpbusdirman_settings_fees_images_'.$listing_package_id
		);
	
	foreach ($option_names as $option) {
		$listing_package_data[] = get_option($option);
	}
	return $listing_package_data;
	
}

// Lets make a new global for fee options, shall we?
global $wpbusdir_fee_options;

// Grab all of our fee options with this handy little function
$fee_options_ids = wpbusdirman_retrieveoptions($whichoptionvalue='wpbusdirman_settings_fees_label_');

// Build our lovely little options array
foreach($fee_options_ids as $option_set) {
	$option_names = array(
		'fee_label'=>'wpbusdirman_settings_fees_label_'.$option_set,
		'fee_amount'=> 'wpbusdirman_settings_fees_amount_'.$option_set,
		'images'=> 'wpbusdirman_settings_fees_images_'.$option_set
		);
		unset($options_data);
		foreach ($option_names as $option) {
			$options_data[] = get_option($option);
			$options_data[] = $option_set;
		}
		
		$options_data_array[] = $options_data;
}

$wpbusdir_fee_options = $options_data_array;

// End this super helpful function that would have made total sense to have in the original build

if($_REQUEST['action'] == 'change_listing_plan') {change_listing_plan();};
if($_REQUEST['action'] == 'ajax_edit_meta') {ajax_edit_meta();};

function change_listing_plan() {
	$listing_id = $_REQUEST['id'];
	$fee_id = $_REQUEST['fee_id'];
	
	update_post_meta($listing_id, 'listing_package', $fee_id);
}

function ajax_edit_meta() {
	$meta = $_REQUEST['meta'];
	$listing_id = $_REQUEST['id'];
	$new_meta = $_REQUEST['new_meta'];
	add_post_meta($listing_id, $meta, $new_meta, true) or update_post_meta($listing_id, $meta, $new_meta);
}

?>
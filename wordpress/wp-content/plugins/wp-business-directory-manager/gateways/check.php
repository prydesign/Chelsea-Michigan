<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// check.php
//
// Author: A Lewis
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_REQUEST['action'] == 'wpbusdirman_do_checkout') {wpbusdirman_do_checkout();};

global $wpdb;

function pay_with_check_btn($wpbusdirmanlistingid,$wpbusdirmanfeeoption)
{

	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
		
	$wpbusdirman_payment_ad_description.=get_the_title($wpbusdirmanlistingid);
	$wpbusdirman_payment_ad_description.=__(" with listing ID: ","WPBDM");
	$wpbusdirman_payment_ad_description.=$wpbusdirmanlistingid;
	$wpbusdirmangooglecheckoutbutton.="<form action=\"/directory\" id=\"BB_BuyButtonForm\" method=\"post\" name=\"BB_BuyButtonForm\">";

	if($wpbusdirmanfeeoption == 32)
	{
		$wpbusdirmansavedfeelabel=__("Upgrade Listing to Featured","WPBDM");	
		$wpbusdirmansavedfeeamount=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_32'];
		$wpbusdirmansavedfeeamount=number_format($wpbusdirmansavedfeeamount, 2, '.', '');
	}
	else
	{
		$wpbusdirmansavedfeelabel=get_option('wpbusdirman_settings_fees_label_'.$wpbusdirmanfeeoption);		
		$wpbusdirmansavedfeeamount=get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirmanfeeoption);
		$wpbusdirmansavedfeeamount=number_format($wpbusdirmansavedfeeamount, 2, '.', '');	
	}
		$wpbusdirmancurrencycode=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_20'];
		$wpbusdirmansavedfeecategories=get_option('wpbusdirman_settings_fees_categories_'.$wpbusdirmanfeeoption);


	$wpbusdirmangooglecheckoutbutton.="<input type=\"hidden\" name=\"action\" value=\"wpbusdirman_do_checkout\"/>";
	$wpbusdirmangooglecheckoutbutton.="<input type=\"hidden\" name=\"listing_id\" value=\"$wpbusdirmanlistingid\"/>";
	$wpbusdirmangooglecheckoutbutton.="<input type=\"hidden\" name=\"item_name_1\" value=\"$wpbusdirmansavedfeelabel\"/>";
	$wpbusdirmangooglecheckoutbutton.="<input type=\"hidden\" name=\"item_description_1\" value=\"$wpbusdirman_payment_ad_description\"/>";
	$wpbusdirmangooglecheckoutbutton.="<input type=\"hidden\" name=\"item_price_1\" value=\"$wpbusdirmansavedfeeamount\"/>";
	$wpbusdirmangooglecheckoutbutton.="<input type=\"hidden\" name=\"item_currency_1\" value=\"$wpbusdirmancurrencycode\"/>";
	$wpbusdirmangooglecheckoutbutton.="<input type=\"hidden\" name=\"item_quantity_1\" value=\"1\"/>";
	$wpbusdirmangooglecheckoutbutton.="<input type=\"submit\" value=\"Submit Your Listing\"";
	$wpbusdirmangooglecheckoutbutton.="/></form>";

	return $wpbusdirmangooglecheckoutbutton;
}





function wpbusdirman_do_checkout() {
	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	
		$wpbusdirmanlistingID=$_REQUEST['wpbdmlistingid'];

		$wpbdmpaymentstatus=get_post_meta($wpbusdirmanlistingID, "paymentstatus", true);
		$wpbdmpaymentgateway=get_post_meta($wpbusdirmanlistingID, "paymentgateway", true);

		add_post_meta($wpbusdirmanlistingID, "sticky", "pending", true) or update_post_meta($wpbusdirmanlistingID, "sticky", "pending");
		add_post_meta($wpbusdirmanlistingID, "paymentstatus", "pending", true) or update_post_meta($wpbusdirmanlistingID, "paymentstatus", "pending");
		add_post_meta($wpbusdirmanlistingID, "buyerfirstname", "Unknown", true) or update_post_meta($wpbusdirmanlistingID, "buyerfirstname", $first_name);
		add_post_meta($wpbusdirmanlistingID, "buyerlastname", "Unknown", true) or update_post_meta($wpbusdirmanlistingID, "buyerlastname", $last_name);
		add_post_meta($wpbusdirmanlistingID, "paymentgateway", "Check", true) or update_post_meta($wpbusdirmanlistingID, "paymentgateway", "Check");
		add_post_meta($wpbusdirmanlistingID, "payeremail", "Unknown", true) or update_post_meta($wpbusdirmanlistingID, "payeremail", "Unknown");
	}
?>
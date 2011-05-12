<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
Plugin Name: Chelsea First Business Directory Manager
Description: Provides the ability to maintain a free or paid business directory on your wordpress powered site.
Version: 1.9.1
Author: A Lewis
Author URI: http://www.themestown.com
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
// WP Business Directory Manager provides the ability for you to add a business directory to your wordpress blog and charge a fee for users
// to submit their listing
//
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*  Copyright 2009,2010  A. Lewis  (email : themestown@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if ( !defined('WP_CONTENT_DIR') )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' ); // no trailing slash, full paths only - WP_CONTENT_URL is defined further down

if ( !defined('WP_CONTENT_URL') )
	define( 'WP_CONTENT_URL', get_option('siteurl') . '/wp-content'); // no trailing slash, full paths only - WP_CONTENT_URL is defined further down

$wpcontenturl=WP_CONTENT_URL;
$wpcontentdir=WP_CONTENT_DIR;
$wpinc=WPINC;


$wpbusdirman_plugin_path = WP_CONTENT_DIR.'/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
$wpbusdirman_plugin_url = WP_CONTENT_URL.'/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
$wpbusdirman_plugin_dir = basename(dirname(__FILE__));
$wpbusdirman_haspaypalmodule=0;
$wpbusdirman_hastwocheckoutmodule=0;
$wpbusdirman_hasgooglecheckoutmodule=0;

$wpbusdirman_imagespath = WP_CONTENT_DIR.'/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'images';
$wpbusdirman_imagesurl = WP_CONTENT_URL.'/plugins/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__)).'images';

$uploaddir=get_option('upload_path');
if(!isset($uploaddir) || empty($uploaddir))
{
	$uploaddir=ABSPATH;
	$uploaddir.="wp-content/uploads";
}


$wpbusdirmanimagesdirectory=$uploaddir;
$wpbusdirmanimagesdirectory.="/wpbdm";
$wpbusdirmanthumbsdirectory=$wpbusdirmanimagesdirectory;
$wpbusdirmanthumbsdirectory.="/thumbnails";

$wpbdmimagesurl="$wpcontenturl/uploads/wpbdm";

$nameofsite=get_option('blogname');
$siteurl=get_option('siteurl');
$thisadminemail=get_option('admin_email');

$wpbdmposttype="wpbdm-directory";
$wpbdmposttypecategory="wpbdm-category";
$wpbdmposttypetags="wpbdm-tags";

$wpbusdirman_db_version = "1.9.1";

$wpbusdirmaname=__("WP Business Directory Manager","WPBDM");
$wpbusdirman_labeltext=__("Label","WPBDM");
$wpbusdirman_typetext=__("Type","WPBDM");
$wpbusdirman_associationtext=__("Association","WPBDM");
$wpbusdirman_optionstext=__("Options","WPBDM");
$wpbusdirman_ordertext=__("Order","WPBDM");
$wpbusdirman_actiontext=__("Action","WPBDM");
$wpbusdirman_valuetext=__("Value","WPBDM");
$wpbusdirman_amounttext=__("Amount","WPBDM");
$wpbusdirman_appliedtotext=__("Applied To","WPBDM");
$wpbusdirman_allcatstext=__("All categories","WPBDM");
$wpbusdirman_daytext=__("Day","WPBDM");
$wpbusdirman_daystext=__("Days","WPBDM");
$wpbusdirman_imagestext=__("Images","WPBDM");
$wpbusdirman_durationtext=__("Duration","WPBDM");
$wpbusdirman_validationtext=__("Validation","WPBDM");
$wpbusdirman_requiredtext=__("Required","WPBDM");
$wpbusdirman_showinexcerpttext=__("Excerpt","WPBDM");


define('WPBUSDIRMANURL', $wpbusdirman_plugin_url );
define('WPBUSDIRMANMENUICO', $wpbusdirman_imagesurl .'/menuico.png');
define('WPBUSDIRMAN', $wpbusdirmaname);
define('WPBUSDIRMAN_TEMPLATES_PATH', $wpbusdirman_plugin_path . '/posttemplate');


$wpbusdirman_gpid=wpbusdirman_gpid();
$permalinkstructure=get_option('permalink_structure');
$wpbusdirmanconfigoptionsprefix="wpbusdirman";

$wpbusdirman_field_vals_pfl=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');


// Options array
$poststatusoptions=array("pending","publish");
$yesnooptions=array("yes","no");
$myloginurl=get_option('siteurl').'/wp-login.php?action=login';
$myregistrationurl=get_option('siteurl').'/wp-login.php?action=register';
$categoryorderoptions=array('name','ID','slug','count','term_group');
$categorysortoptions=array('ASC','DESC');
$drafttrashoptions=array("draft","trash");

$def_wpbusdirman_config_options = array (

array("name" => "Miscellaneous settings",
"type" => "titles"),

array("name" => "Listing Duration for no-fee sites (measured in days)?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_18",
"std" => "365",
"type" => "text"),

array("name" => "Hide all buy plugin module buttons?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_25",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Hide tips for use and other information?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_26",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Include listing contact form on listing pages?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_27",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Include comment form on listing pages?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_36",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Give credit to plugin author?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_34",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Turn on listing renewal option?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_38",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Use default picture for listings with no picture?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_39",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Show listings under categories on main page?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_44",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Override email Blocking?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_45",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Status of listings upon uninstalling plugin",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_46",
"std" => "draft",
"type" => "select",
"options" => $drafttrashoptions),

array("name" => "Status of deleted listings",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_47",
"std" => "draft",
"type" => "select",
"options" => $drafttrashoptions),

array("name" => "Login/Registration Settings",
"type" => "titles"),

array("name" => "Require login?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_3",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Login URL?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_4",
"std" => "$myloginurl",
"type" => "text"),

array("name" => "Registration URL?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_5",
"std" => "$myloginurl",
"type" => "text"),

array("name" => "Post/Category Settings",
"type" => "titles"),

array("name" => "Default new post status",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_1",
"std" => "pending",
"type" => "select",
"options" => $poststatusoptions),

array("name" => "Edit post status",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_19",
"std" => "publish",
"type" => "select",
"options" => $poststatusoptions),

array("name" => "Order Categories List By",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_7",
"std" => "name",
"type" => "select",
"options" => $categoryorderoptions),

array("name" => "Sort order for categories",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_8",
"std" => "ASC",
"type" => "select",
"options" => $categorysortoptions),

array("name" => "Show category post count?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_9",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Hide Empty Categories?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_10",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Show only parent categories category list?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_48",
"std" => "no",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Image Settings",
"type" => "titles"),

array("name" => "Allow image?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_6",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Number of free images?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_2",
"std" => "2",
"type" => "text"),

array("name" => "Show Thumbnail on main listings page?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_11",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Max Image File Size?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_13",
"std" => "100000",
"type" => "text"),

array("name" => "Minimum Image File Size?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_14",
"std" => "300",
"type" => "text"),

array("name" => "Max image width?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_15",
"std" => "500",
"type" => "text"),

array("name" => "Max image height?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_16",
"std" => "500",
"type" => "text"),

array("name" => "Thumbnail Width?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_17",
"std" => "150",
"type" => "text"),


array("name" => "General Payment Settings",
"type" => "titles"),

array("name" => "Currency Code",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_20",
"std" => "USD",
"type" => "text"),

array("name" => "Currency Symbol",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_12",
"std" => "$",
"type" => "text"),

array("name" => "Turn On Payments?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_21",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Put payment gateways in test mode?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_22",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Thank you for payment message",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_37",
"std" => "Thank you for your payment. Your payment is being verified and your listing reviewed. The verification and review process could take up to 48 hours.",
"type" => "text"),

array("name" => "Featured(Sticky) listing settings",
"type" => "titles"),

array("name" => "Offer Sticky Listings?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_31",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Sticky Listing Price(00.00)",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_32",
"std" => "39.99",
"type" => "text"),

array("name" => "Sticky listing page description text",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_33",
"std" => "You can upgrade your listing to featured status. Featured listings will always appear on top of regular listings.",
"type" => "text"),


array("name" => "Google Checkout Settings",
"type" => "titles"),

array("name" => "Google Checkout Merchant ID",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_23",
"std" => "",
"type" => "text"),

array("name" => "Google Checkout Sandbox Seller ID",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_24",
"std" => "",
"type" => "text"),

array("name" => "Hide Google Checkout?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_40",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "PayPal Gateway Settings (Will only work if paypal module installed)",
"type" => "titles"),

array("name" => "PayPal Business Email",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_35",
"std" => "",
"type" => "text"),

array("name" => "Hide PayPal?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_41",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),


array("name" => "2Checkout Gateway Settings (Will only work if 2checkout module installed)",
"type" => "titles"),

array("name" => "2Checkout Seller/Vendor ID",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_42",
"std" => "",
"type" => "text"),

array("name" => "Hide 2Checkout?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_43",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "ReCaptcha Settings",
"type" => "titles"),

array("name" => "reCAPTCHA Public Key",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_28",
"std" => "",
"type" => "text"),

array("name" => "reCAPTCHA Private Key",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_29",
"std" => "",
"type" => "text"),

array("name" => "Turn on reCAPTCHA?",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_30",
"std" => "yes",
"type" => "select",
"options" => $yesnooptions),

array("name" => "Permalink Settings",
"type" => "titles"),

array("name" => "Directory Listings Slug",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_49",
"std" => "$wpbdmposttype",
"type" => "text"),

array("name" => "Categories slug",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_50",
"std" => "$wpbdmposttypecategory",
"type" => "text"),

array("name" => "Tags slug",
"id" => $wpbusdirmanconfigoptionsprefix."_settings_config_51",
"std" => "$wpbdmposttypetags",
"type" => "text"),

);



////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Add actions and filters etc
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	add_action('init', 'wpbusdirman_install');
	add_action( 'wpbusdirman_listingexpirations_hook', 'wpbusdirman_listings_expirations' );
	add_action('init', 'wpbusdirman_dir_post_type');
	//add_action('init', 'wpbusdirman_create_the_pages');
	add_action('admin_menu', 'wpbusdirman_launch');
	add_action('wp_head', 'wpbusdirman_addcss');
	add_shortcode('WPBUSDIRMANUI', 'wpbusdirmanui_homescreen');
	add_shortcode('WPBUSDIRMANADDLISTING', 'wpbusdirman_displaypostform_direct');
	add_shortcode('WPBUSDIRMANMANAGELISTING', 'wpbusdirman_managelistings');
	add_shortcode('WPBUSDIRMANMVIEWLISTINGS', 'wpbusdirman_viewlistings');
	add_filter('single_template', 'wpbusdirman_single_template');
	add_filter('taxonomy_template', 'wpbusdirman_category_template');
	add_filter("wp_footer", "wpbusdirman_display_ac");
	add_filter('wp_list_pages_excludes', 'wpbusdirman_exclude_payment_pages');
	if( file_exists("$wpbusdirman_plugin_path/gateways/paypal.php") )
	{
		require("$wpbusdirman_plugin_path/gateways/paypal.php");
		$wpbusdirman_haspaypalmodule=1;
	}
	if( file_exists("$wpbusdirman_plugin_path/gateways/twocheckout.php") )
	{
		require("$wpbusdirman_plugin_path/gateways/twocheckout.php");
		$wpbusdirman_hastwocheckoutmodule=1;
	}
	if( file_exists("$wpbusdirman_plugin_path/gateways/check.php") )
	{
		require("$wpbusdirman_plugin_path/gateways/check.php");
		$wpbusdirman_hasgooglecheckoutmodule=1;
	}

	if($wpbusdirman_haspaypalmodule	== 1)
	{
		add_shortcode('WPBUSDIRMANPAYPAL', 'wpbusdirman_do_paypal');
	}
	if($wpbusdirman_hastwocheckoutmodule == 1)
	{
		add_shortcode('WPBUSDIRMANTWOCHECKOUT', 'wpbusdirman_do_twocheckout');
	}
	if($wpbusdirman_hasgooglecheckoutmodule == 1)
	{
		add_shortcode('WPBUSDIRMANGOOGLECHECKOUT', 'wpbusdirman_do_googlecheckout');
	}

	// Updated and refactored post manipulation functions.  Separated out in this new file because it was a bitch trying to find them in this behemoth.
	require("$wpbusdirman_plugin_path/wpbusdirman_listing_management.php");


	function wpbusdirman_exclude_payment_pages($output = '')
	{

		$wpbdmpaymentpages=array();
		global $wpdb,$table_prefix;

		$query="SELECT ID FROM {$table_prefix}posts WHERE post_content LIKE '%WPBUSDIRMANGOOGLECHECKOUT%' OR post_content LIKE '%WPBUSDIRMANPAYPAL%' OR post_content LIKE '%WPBUSDIRMANTWOCHECKOUT%'";
		 if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}

		 	while ($rsrow=mysql_fetch_row($res))
 			{
 				$wpbdmpaymentpages[]=$rsrow[0];
 			}

		if($wpbdmpaymentpages)
		{
			foreach ($wpbdmpaymentpages as $wpbdmpaymentpagestoexclude)
			{
				array_push($output, $wpbdmpaymentpagestoexclude);
			}
		}

		return $output;


	}

function wpbusdirman_single_template($single)
{
	global $wp_query, $post, $wpbdmposttype;
	$mywpbdmposttype=$post->post_type;


		if($mywpbdmposttype == $wpbdmposttype )
		{
			if(file_exists(TEMPLATEPATH . '/single/wpbusdirman-single.php'))
			return TEMPLATEPATH . '/single/wpbusdirman-single.php';
			if(file_exists(STYLESHEETPATH . '/single/wpbusdirman-single.php'))
			return STYLESHEETPATH . '/single/wpbusdirman-single.php';
			if(file_exists(WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-single.php'))
			return WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-single.php';
		}

return $single;

}

function wpbusdirman_category_template($category)
{
	global $wp_query, $post, $wpbdmposttype;

			if(file_exists(TEMPLATEPATH . '/single/wpbusdirman-category.php'))
			return TEMPLATEPATH . '/single/wpbusdirman-category.php';
			if(file_exists(STYLESHEETPATH . '/single/wpbusdirman-category.php'))
			return STYLESHEETPATH . '/single/wpbusdirman-category.php';
			if(file_exists(WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-category.php'))
			return WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-category.php';

return $category;

}


function wpbusdirman_addcss()
{
	$wpbusdirmanstylesheet="wpbusdirman.css";
	echo "\n".'<style type="text/css" media="screen">@import "'.WPBUSDIRMANURL.'css/'.$wpbusdirmanstylesheet.'";</style>
	 ';

}

function wpbusdirman_install()
{

	global $wpdb,$wpbusdirman_db_version,$wpbdmposttype,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$installed_ver = get_option( "wpbusdirman_db_version" );


	// Form Labels
	$wpbusdirman_postform_field_label_1=__("Business Name","WPBDM");
	$wpbusdirman_postform_field_label_2=__("Business Genre","WPBDM"); // Display Listing associated categories
	$wpbusdirman_postform_field_label_3=__("Short Business Description","WPBDM");
	$wpbusdirman_postform_field_label_4=__("Long Business Description","WPBDM");
	$wpbusdirman_postform_field_label_5=__("Business Website Address","WPBDM");
	$wpbusdirman_postform_field_label_6=__("Business Phone Number","WPBDM");
	$wpbusdirman_postform_field_label_7=__("Business Fax","WPBDM");
	$wpbusdirman_postform_field_label_8=__("Business Contact Email","WPBDM");
	$wpbusdirman_postform_field_label_9=__("Business Tags","WPBDM");

			if( isset($wpbusdirman_config_options) && !empty($wpbusdirman_config_options) && (is_array($wpbusdirman_config_options)) )
			{
				$wpbusdirman_installed_already=1;
			}
			else { $wpbusdirman_installed_already=0; }


	if(!$wpbusdirman_installed_already)
	{
	  	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  	//	Install the plugin
	  	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

			// Add Version Number
			add_option("wpbusdirman_db_version", $wpbusdirman_db_version);

			// Add settings options


			// Add default form options
			add_option("wpbusdirman_postform_field_label_1", $wpbusdirman_postform_field_label_1);
			add_option("wpbusdirman_postform_field_label_2", $wpbusdirman_postform_field_label_2);
			add_option("wpbusdirman_postform_field_label_3", $wpbusdirman_postform_field_label_3);
			add_option("wpbusdirman_postform_field_label_4", $wpbusdirman_postform_field_label_4);
			add_option("wpbusdirman_postform_field_label_5", $wpbusdirman_postform_field_label_5);
			add_option("wpbusdirman_postform_field_label_6", $wpbusdirman_postform_field_label_6);
			add_option("wpbusdirman_postform_field_label_7", $wpbusdirman_postform_field_label_7);
			add_option("wpbusdirman_postform_field_label_8", $wpbusdirman_postform_field_label_8);
			add_option("wpbusdirman_postform_field_label_9", $wpbusdirman_postform_field_label_9);

			// text = 1, select = 2, textarea=3 radio =4 multiselect =5 checkbox =6
			add_option("wpbusdirman_postform_field_type_1", 1);
			add_option("wpbusdirman_postform_field_type_2", 2);
			add_option("wpbusdirman_postform_field_type_3", 3);
			add_option("wpbusdirman_postform_field_type_4", 3);
			add_option("wpbusdirman_postform_field_type_5", 1);
			add_option("wpbusdirman_postform_field_type_6", 1);
			add_option("wpbusdirman_postform_field_type_7", 1);
			add_option("wpbusdirman_postform_field_type_8", 1);
			add_option("wpbusdirman_postform_field_type_9", 1);

			add_option("wpbusdirman_postform_field_options_1", '');
			add_option("wpbusdirman_postform_field_options_2", '');
			add_option("wpbusdirman_postform_field_options_3", '');
			add_option("wpbusdirman_postform_field_options_4", '');
			add_option("wpbusdirman_postform_field_options_5", '');
			add_option("wpbusdirman_postform_field_options_6", '');
			add_option("wpbusdirman_postform_field_options_7", '');
			add_option("wpbusdirman_postform_field_options_8", '');
			add_option("wpbusdirman_postform_field_options_9", '');

			add_option("wpbusdirman_postform_field_order_1", 1);
			add_option("wpbusdirman_postform_field_order_2", 2);
			add_option("wpbusdirman_postform_field_order_3", 3);
			add_option("wpbusdirman_postform_field_order_4", 4);
			add_option("wpbusdirman_postform_field_order_5", 5);
			add_option("wpbusdirman_postform_field_order_6", 6);
			add_option("wpbusdirman_postform_field_order_7", 7);
			add_option("wpbusdirman_postform_field_order_8", 8);
			add_option("wpbusdirman_postform_field_order_9", 9);


			add_option("wpbusdirman_postform_field_association_1", 'title');
			add_option("wpbusdirman_postform_field_association_2", 'category');
			add_option("wpbusdirman_postform_field_association_3", 'excerpt');
			add_option("wpbusdirman_postform_field_association_4", 'description');
			add_option("wpbusdirman_postform_field_association_5", 'meta');
			add_option("wpbusdirman_postform_field_association_6", 'meta');
			add_option("wpbusdirman_postform_field_association_7", 'meta');
			add_option("wpbusdirman_postform_field_association_8", 'meta');
			add_option("wpbusdirman_postform_field_association_9", 'tags');

			add_option("wpbusdirman_postform_field_validation_1", 'missing');
			add_option("wpbusdirman_postform_field_validation_2", 'missing');
			add_option("wpbusdirman_postform_field_validation_3", '');
			add_option("wpbusdirman_postform_field_validation_4", 'missing');
			add_option("wpbusdirman_postform_field_validation_5", 'url');
			add_option("wpbusdirman_postform_field_validation_6", '');
			add_option("wpbusdirman_postform_field_validation_7", '');
			add_option("wpbusdirman_postform_field_validation_8", 'email');
			add_option("wpbusdirman_postform_field_validation_9", '');

			add_option("wpbusdirman_postform_field_required_1", 'yes');
			add_option("wpbusdirman_postform_field_required_2", 'yes');
			add_option("wpbusdirman_postform_field_required_3", 'no');
			add_option("wpbusdirman_postform_field_required_4", 'yes');
			add_option("wpbusdirman_postform_field_required_5", 'no');
			add_option("wpbusdirman_postform_field_required_6", 'no');
			add_option("wpbusdirman_postform_field_required_7", 'no');
			add_option("wpbusdirman_postform_field_required_8", 'yes');
			add_option("wpbusdirman_postform_field_required_9", 'no');


			add_option("wpbusdirman_postform_field_showinexcerpt_1", 'yes');
			add_option("wpbusdirman_postform_field_showinexcerpt_2", 'yes');
			add_option("wpbusdirman_postform_field_showinexcerpt_3", 'no');
			add_option("wpbusdirman_postform_field_showinexcerpt_4", 'no');
			add_option("wpbusdirman_postform_field_showinexcerpt_5", 'yes');
			add_option("wpbusdirman_postform_field_showinexcerpt_6", 'yes');
			add_option("wpbusdirman_postform_field_showinexcerpt_7", 'no');
			add_option("wpbusdirman_postform_field_showinexcerpt_8", 'no');
			add_option("wpbusdirman_postform_field_showinexcerpt_9", 'no');


		/*wp_schedule_event( time(), 'daily', 'wpbusdirman_listings_expirations' );*/

		//wpbusdirman_convert_old_posts();
	 }
     else
     {

	  	//wpbusdirman_convert_old_posts();

	  	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	  	//	Update the plugin
	  	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

		if( $installed_ver != $wpbusdirman_db_version )
		{
			update_option("wpbusdirman_db_version", $wpbusdirman_db_version);
		}


    }

    $plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( 'WPBDM', null, $plugin_dir.'/languages' );


}


function wpbusdirman_convert_old_posts()
{
	global $wpbdmposttype,$wpbdmposttypecategory,$wpbdmposttypetags;

	$installed_ver = get_option( "wpbusdirman_db_version" );
	if( $installed_ver == 1.0 || $installed_ver == 1.1 || $installed_ver == 1.2 || $installed_ver == 1.3 || $installed_ver == 1.4 || $installed_ver == 1.5 || $installed_ver == 1.6 || $installed_ver == 1.7)
	{

			$wpbusdirman_postsposts=array();

			$wpbusdirman_postcats=get_option('wpbusdirman_settings_config_value_2');


			if(isset($wpbusdirman_postcats) && !empty($wpbusdirman_postcats))
			{
				$wpbusdirmanpostcats=explode(",",$wpbusdirman_postcats);
				$wpbusdirman_postcatitems=array();

				for ($i=0;isset($wpbusdirmanpostcats[$i]);++$i)
				{

					$wpbusdirman_postcatitems[]=$wpbusdirmanpostcats[$i];
				}
				if($wpbusdirman_postcatitems)
				{
					foreach($wpbusdirman_postcatitems as $wpbusdirman_postcatitem)
					{
						$wpbusdirman_catcat=get_posts($wpbusdirman_postcatitem);
					}
				}
				if($wpbusdirman_catcat)
				{
					foreach($wpbusdirman_catcat as $wpbusdirman_cat)
					{
						$wpbusdirman_postsposts[]=$wpbusdirman_cat->ID;
					}
				}
			}
			else
			{
				$args=array('post_status'=>array("pending","publish","draft","trash"),'meta_key'=>'lengthofterm','meta_value'=>range(1,100000));
				$wpbusdirman_postsdat=get_posts($args);

				if($wpbusdirman_postsdat)
				{
					foreach($wpbusdirman_postsdat as $wpbusdirman_postdat)
					{
						$wpbusdirman_postsposts[]=$wpbusdirman_postdat->ID;
					}
				}

			}

			if($wpbusdirman_postsposts)
			{
				foreach($wpbusdirman_postsposts as $wpbusdirman_postspost)
				{

					$wpbdmcategories = get_the_category($wpbusdirman_postspost);
					$wpbusdirmanfieldtagsobject=get_the_tags($wpbusdirman_postspost);

					if($wpbusdirmanfieldtagsobject)
					{
						foreach($wpbusdirmanfieldtagsobject as $wpbusdirmanfieldtags)
						{
						   $wpbusdirmantag=$wpbusdirmanfieldtags->slug;
						   $wpbusdirmantagids[]=$wpbusdirmanfieldtags->term_id;
						   $wpbdmtags[]=$wpbusdirmantag;
						}
					}



					if($wpbdmcategories)
					{
						foreach($wpbdmcategories as $wpbdmcategory)
						{
							$mywpbdmcats[]=$wpbdmcategory->term_id;
						}
					}

					// Convert pre 1.8 post tags
					if($wpbusdirmantagids)
					{
						foreach($wpbusdirmantagids as $wpbusdirmantagid)
						{
							wpbusdirman_change_taxonomy_type_tags($wpbusdirmantagid);
						}
					}

					// Convert pre 1.8 post categories
					if($mywpbdmcats)
					{
						foreach($mywpbdmcats as $mywpbdmcat)
						{
							wpbusdirman_change_taxonomy_type_category($mywpbdmcat);
						}
					}

					wp_set_post_terms( $wpbusdirman_postspost, $wpbdmtags, $wpbdmposttypetags, $append );
					wp_set_post_terms( $wpbusdirman_postspost, $mywpbdmcats, $wpbdmposttypecategory, $append );

					$wpbusdirman_conv_postarr = array();
					$wpbusdirman_conv_postarr['ID'] = $wpbusdirman_postspost;
					$wpbusdirman_conv_postarr['post_type'] = $wpbdmposttype;

					// Update the post into the database
					wp_update_post( $wpbusdirman_conv_postarr );

				}
			}


	}
}



function wpbusdirman_change_taxonomy_type_category($taxonomy)
{
	global $wpdb,$table_prefix,$wpbdmposttypecategory;
	$wpbusdirman_query="UPDATE $wpdb->term_taxonomy SET taxonomy='".$wpbdmposttypecategory."', parent='0',count='0' WHERE term_id='$taxonomy'";
	@mysql_query($wpbusdirman_query);
}

function wpbusdirman_change_taxonomy_type_tags($taxonomy)
{
	global $wpdb,$table_prefix,$wpbdmposttypetags;
	$wpbusdirman_query="UPDATE $wpdb->term_taxonomy SET taxonomy='".$wpbdmposttypetags."',count='0' WHERE term_id='$taxonomy'";
	@mysql_query($wpbusdirman_query);
}

function wpbusdirman_update_taxonomy_type_category($taxonomynm)
{
	global $wpdb,$table_prefix,$wpbdmposttypecategory;
	$wpbusdirman_query="UPDATE $wpdb->term_taxonomy SET taxonomy='".$wpbdmposttypecategory."' WHERE taxonomy='$taxonomynm'";
	@mysql_query($wpbusdirman_query);
}

function wpbusdirman_update_taxonomy_type_tags($taxonomynm)
{
	global $wpdb,$table_prefix,$wpbdmposttypetags;
	$wpbusdirman_query="UPDATE $wpdb->term_taxonomy SET taxonomy='".$wpbdmposttypetags."' WHERE taxonomy='$taxonomynm'";
	@mysql_query($wpbusdirman_query);
}

function wpbusdirman_adexpirations_hook(){}

function wpbusdirman_dir_post_type()
{

	global $wpbdmposttype,$wpbdmposttypecategory,$wpbdmposttypetags,$wpbusdirmanconfigoptionsprefix;

$wpbusdirman_config_options=get_wpbusdirman_config_options();


if(isset($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_49']) && !empty($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_49'])){$wpbdmposttypeslug=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_49'];}else {$wpbdmposttyleslug=$wpbdmposttype;}
if(isset($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_50']) && !empty($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_50'])){$wpbdmposttypecategoryslug=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_50'];}else {$wpbdmposttypecategoryslug=$wpbdmposttypecategory;}
if(isset($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_51']) && !empty($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_51'])){$wpbdmposttypetagslug=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_51'];}else {$wpbdmposttypetagslug=$wpbdmposttypetags;}



			  $labels = array(
			    'name' => _x('Directory', 'post type general name'),
			    'singular_name' => _x('Directory', 'post type singular name'),
			    'add_new' => _x('Add New Listing', 'listing'),
			    'add_new_item' => __('Add New Listing'),
			    'edit_item' => __('Edit Listing'),
			    'new_item' => __('New Listing'),
			    'view_item' => __('View Listing'),
			    'search_items' => __('Search Listings'),
			    'not_found' =>  __('No listings found'),
			    'not_found_in_trash' => __('No listings found in trash'),
			    'parent_item_colon' => ''
			  );
			  $args = array(
			    'labels' => $labels,
			    'public' => true,
			    'publicly_queryable' => true,
			    'show_ui' => true,
			    'query_var' => true,
			    'rewrite' => array('slug'=>$wpbdmposttypeslug,'with_front'=>false),
			    'capability_type' => 'post',
			    'hierarchical' => false,
			    'menu_position' => null,
			    'supports' => array('title','editor','author','categories','tags','thumbnail','excerpt','comments','custom-fields','trackbacks')
			  );
			  register_post_type($wpbdmposttype,$args);

	//Register directory category taxonomy
	register_taxonomy( $wpbdmposttypecategory, $wpbdmposttype, array( 'hierarchical' => true, 'label' => 'Directory Categories', 'singular_name' => 'Directory Category', 'show_in_nav_menus' => true, 'update_count_callback' => '_update_post_term_count','query_var' => true, 'rewrite' => array('slug'=>$wpbdmposttypecategoryslug) ) );
	register_taxonomy( $wpbdmposttypetags, $wpbdmposttype, array( 'hierarchical' => false, 'label' => 'Directory Tags', 'singular_name' => 'Directory Tag', 'show_in_nav_menus' => true, 'update_count_callback' => '_update_post_term_count', 'query_var' => true, 'rewrite' => array('slug'=>$wpbdmposttypetagslug) ) );
	// register_taxonomy_for_object_type('category', $wpbdmposttype);
	// register_taxonomy_for_object_type('post_tag', $wpbdmposttype);

flush_rewrite_rules( false );
	}

function wpbusdirman_create_the_pages()
{
	/*$wpbusdirman_gpid=wpbusdirmanmakepagemain($wpbdmpagename='Business Directory',$shortcode='[WPBUSDIRMANUI]');
	wpbusdirmanmakepage($wpbusdirman_gpid,$wpbdmpagename='Submit Listing',$shortcode='[WPBUSDIRMANADDLISTING]');
	wpbusdirmanmakepage($wpbusdirman_gpid,$wpbdmpagename='Manage Listings',$shortcode='[WPBUSDIRMANMANAGELISTING]');
	wpbusdirmanmakepage($wpbusdirman_gpid,$wpbdmpagename='View Listings',$shortcode='[WPBUSDIRMANMVIEWLISTINGS]');
	wpbusdirmanmakepage($wpbusdirman_gpid,$wpbdmpagename='Google Checkout',$shortcode='[WPBUSDIRMANGOOGLECHECKOUT]');*/
}
function wpbusdirman_launch()
{
	global $wpbusdirman_plugin_path;
	add_menu_page(WPBUSDIRMAN, 'WPBusDirMan', 'activate_plugins', 'wpbusdirman.php', 'wpbusdirman_home_screen', WPBUSDIRMANMENUICO);
	add_submenu_page('wpbusdirman.php', 'Manage Options ', 'Manage Options', 'activate_plugins', 'wpbdman_c1', 'wpbusdirman_config_admin');
	add_submenu_page('wpbusdirman.php', 'Manage Fees', 'Manage Fees', 'activate_plugins', 'wpbdman_c2', 'wpbusdirman_opsconfig_fees');
	add_submenu_page('wpbusdirman.php', 'Manage Fields', 'Manage Form Fields', 'activate_plugins', 'wpbdman_c3', 'wpbusdirman_buildform');
	add_submenu_page('wpbusdirman.php', 'Manage Featured', 'Manage Featured', 'activate_plugins', 'wpbdman_c4', 'wpbusdirman_featured_pending');
	add_submenu_page('wpbusdirman.php', 'Manage Payments', 'Manage Payments', 'activate_plugins', 'wpbdman_c5', 'wpbusdirman_manage_paid');

	add_submenu_page('wpbusdirman.php', 'Uninstall WPDB Manager', 'Uninstall', 'activate_plugins', 'wpbdman_m1', 'wpbusdirman_uninstall');
}

function wpbusdirman_admin_head()
{
	echo "<div class=\"wrap\"><div id=\"icon-edit-pages\" class=\"icon32\"><br></div>";
	echo "<h2>";
	echo WPBUSDIRMAN;
	echo "</h2>";
	echo "<div id=\"dashboard-widgets-wrap\">";
	echo "<div class=\"postbox\" style=\"padding:20px;width:90%;\">";
}


function wpbusdirman_admin_foot()
{
	echo "</div></div></div>";
}

function wpbusdirman_retrieveoptions($whichoptions)
{
		$wpbusdirman_field_vals=array();
		global $table_prefix;
		// Set field label values
		$query="SELECT count(*) FROM {$table_prefix}options WHERE option_name LIKE '%".$whichoptions."%'";
		if (!($res=mysql_query($query))) {die(__(' Failure retrieving table data ['.$query.'].'));}
		while ($rsrow=mysql_fetch_row($res))
		{
			list($wpbusdirman_count_label)=$rsrow;
		}


		for ($i=0;$i<($wpbusdirman_count_label);$i++)
		{
			$wpbusdirman_field_vals[]=($i+1);
		}

	return $wpbusdirman_field_vals;
}

function wpbdm_get_post_data($data,$wpbdmlistingid)
{
		global $table_prefix;
		// Set field label values
		$query="SELECT $data FROM {$table_prefix}posts WHERE ID = '$wpbdmlistingid'";
		if (!($res=mysql_query($query))) {die(__(' Failure retrieving table data ['.$query.'].'));}
		while ($rsrow=mysql_fetch_row($res))
		{
			list($wpbusdirman_post_data)=$rsrow;
		}

	return $wpbusdirman_post_data;
}





// Manage Fees
function wpbusdirman_opsconfig_fees()
{

	global $wpbusdirman_settings_config_label_21,$wpbusdirman_imagesurl,$wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_hasgooglecheckoutmodule,$wpbusdirman_labeltext,$wpbusdirman_amounttext,$wpbusdirman_actiontext,$wpbusdirman_appliedtotext,$wpbusdirman_allcatstext,$wpbusdirman_daytext,$wpbusdirman_daystext,$wpbusdirman_durationtext,$wpbusdirman_imagestext,$wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_action='';
	$hidenolistingfeemsg='';
	$hasnomodules='';

	wpbusdirman_admin_head();
	echo "<h3 style=\"padding:10px;\">";
	_e("Manage Fees","WPBDM");
	echo "</h3><p>";

if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == 'no')
{
	echo "<p>";
	_e("Payments are currently turned off. To manage fees you need to go to the Manage Options page and check the box next to 'Turn on payments' under 'General Payment Settings'","WPBDM");
	echo "</p>";
}
else
{

	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_settings_fees_label_');

		if(!empty($wpbusdirman_field_vals))
		{
			$wpbusdirman_field_vals_max=max($wpbusdirman_field_vals);
		}
		else {$wpbusdirman_field_vals_max='';}


		if(isset($_REQUEST['action']) && !empty($_REQUEST['action']))
		{
			$wpbusdirman_action=$_REQUEST['action'];
		}

		if(($wpbusdirman_action == 'addnewfee') || ($wpbusdirman_action == 'editfee') )
		{
			$hidenolistingfeemsg=1;

			if(isset($_REQUEST['feeid']) && !empty($_REQUEST['feeid']))
			{
				$wpbusdirman_feeid=$_REQUEST['feeid'];
			}

			if(isset($wpbusdirman_feeid) && !empty($wpbusdirman_feeid))
			{
				$wpbusdirmansavedfeelabel=get_option('wpbusdirman_settings_fees_label_'.$wpbusdirman_feeid);
				$wpbusdirmansavedfeeamount=get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirman_feeid);
				$wpbusdirmansavedfeeincrement=get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirman_feeid);
				$wpbusdirmansavedfeeimages=get_option('wpbusdirman_settings_fees_images_'.$wpbusdirman_feeid);
				$wpbusdirmansavedfeecategories=get_option('wpbusdirman_settings_fees_categories_'.$wpbusdirman_feeid);
				$wpbusdirmansavedfeedesc=get_option('wpbusdirman_settings_fees_desc_'.$wpbusdirman_feeid);
				$whichfeeid="<input type=\"hidden\" name=\"whichfeeid\" value=\"$wpbusdirman_feeid\">";
				$wpbusdirmanfeeadoredit="<input type=\"hidden\" name=\"wpbusdirmanfeeadoredit\" value=\"edit\">";
			}
			else
			{
				$wpbusdirmansavedfeelabel='';
				$wpbusdirmansavedfeeamount='';
				$wpbusdirmansavedfeeincrement='';
				$wpbusdirmansavedfeeimages='';
				$wpbusdirmansavedfeecategories='';

				$whichfeeid='';
				$wpbusdirmanfeeadoredit='';
			}

			echo "<form method=\"post\">";
			echo "<p>";
			_e("Fee Label","WPBDM");
			echo "<br/>";
			echo "<input type=\"text\" name=\"wpbusdirman_fees_label\" style=\"width:50%;\" value=\"$wpbusdirmansavedfeelabel\">";
			echo"</p>";
			echo "<p>";
			_e("Fee Amount","WPBDM");
			echo "<br/>";
			echo "<input type=\"text\" name=\"wpbusdirman_fees_amount\" style=\"width:10%;\" value=\"$wpbusdirmansavedfeeamount\">";
			echo"</p>";
			echo "<p>";
			_e("Benefits Description","WPBDM");
			echo "<br/>";
			echo "<textarea name=\"wpbusdirman_fees_desc\" id=\"editme\" style=\"width: 50%; height: 200px;\" value=\"$wpbusdirmansavedfeedesc\"></textarea>";
			echo"</p>";
			echo "<p>";
			_e("Listing Run in days","WPBDM");
			echo "<br/>";
			echo "<input type=\"text\" name=\"wpbusdirman_fees_increment\" value=\"$wpbusdirmansavedfeeincrement\" style=\"width:10%;\">";
			echo "</p>";
			echo "<p>";
			_e("Number of Images Allowed","WPBDM");
			echo "<br/>";
			echo "<input type=\"text\" name=\"wpbusdirman_fees_images\" value=\"$wpbusdirmansavedfeeimages\" style=\"width:10%;\">";
			echo "</p>";
			echo "<p>";
			_e("Apply to Category","WPBDM");
			echo "<br/>";
			echo "<select name=\"wpbusdirman_fees_categories[]\" MULTIPLE style=\"width:25%;height:80px;\">";
			echo "<option value=\"0\">$wpbusdirman_allcatstext</option>";
			$wpbusdirman_my_feecats=wpbusdirman_my_fee_cats();
			echo $wpbusdirman_my_feecats;

			echo "</select>";
			echo"</p>";
			echo $whichfeeid;
			echo $wpbusdirmanfeeadoredit;
			echo "<input type=\"hidden\" name=\"action\" value=\"updateoptions\" />";
			echo "<input name=\"updateoptions\" type=\"submit\" value=\"";
			if(isset($wpbusdirman_feeid) && !empty($wpbusdirman_feeid))
			{
				_e("Update Fee","WPBDM");
			}
			else
			{
				_e("Add Fee","WPBDM");
			}
			echo "\">";
			echo "</form>";

			echo '<script type="text/javascript" src="/wp-content/plugins/wp-business-directory-manager/jwizzy/jquery.wysiwyg.js"></script>';
			echo '<link rel="stylesheet" type="text/css" href="/wp-content/plugins/wp-business-directory-manager/jwizzy/jquery.wysiwyg.css" />';
			echo '<script type="text/javascript">jQuery(document).ready(function($) { $("#editme").wysiwyg(); $("#editme").wysiwyg(\'setContent\', \''.$wpbusdirmansavedfeedesc.'\');});	</script>';

		}
		elseif($wpbusdirman_action == 'deletefee')
		{

			if(isset($_REQUEST['feeid']) && !empty($_REQUEST['feeid']))
			{
				$whichfeeid=$_REQUEST['feeid'];

				delete_option( 'wpbusdirman_settings_fees_label_'.$whichfeeid);
				delete_option( 'wpbusdirman_settings_fees_amount_'.$whichfeeid);
				delete_option( 'wpbusdirman_settings_fees_increment_'.$whichfeeid);
				delete_option( 'wpbusdirman_settings_fees_images_'.$whichfeeid);
				delete_option( 'wpbusdirman_settings_fees_categories_'.$whichfeeid);
				delete_option( 'wpbusdirman_settings_fees_desc_'.$whichfeeid);
			}
			else
			{
				_e("Unable to determine the ID of the fee you are trying to delete. Action terminated","WPBDM");
			}

		}
		elseif($wpbusdirman_action == 'updateoptions')
		{
			if(isset($_REQUEST['whichfeeid']) && !empty($_REQUEST['whichfeeid']))
			{
				$whichfeeid=$_REQUEST['whichfeeid'];
			}

			$hidenolistingfeemsg=1;

			if(isset($whichfeeid) && !empty($whichfeeid))
			{
				$wpbusdirman_add_update_option="update_option";
			}
			else
			{
				$whichfeeid=($wpbusdirman_field_vals_max+1);
				$wpbusdirman_add_update_option="add_option";
			}

			$wpbusdirman_fees_categories=$_REQUEST['wpbusdirman_fees_categories'];
			$wpbusdirman_last = end($wpbusdirman_fees_categories);

			$wpbusdirmanfeecatids='';


			if(in_array(0,$wpbusdirman_fees_categories))
			{
				$wpbusdirmanfeecatids.=0;
			}
			else
			{
				if (count($wpbusdirman_fees_categories) > 0)
				{

					// loop through the array
					for ($i=0;$i<count($wpbusdirman_fees_categories);$i++)
					{
						$wpbusdirmanfeecatids.="$wpbusdirman_fees_categories[$i]";
						if(!($wpbusdirman_fees_categories[$i] == $wpbusdirman_last)){ $wpbusdirmanfeecatids.=","; }
					}
				}
			}

				$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_label_'.$whichfeeid, $_REQUEST['wpbusdirman_fees_label']  );
				$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_amount_'.$whichfeeid, $_REQUEST['wpbusdirman_fees_amount']  );
				$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_increment_'.$whichfeeid, $_REQUEST['wpbusdirman_fees_increment']  );
				$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_images_'.$whichfeeid, $_REQUEST['wpbusdirman_fees_images']  );
				$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_categories_'.$whichfeeid, $wpbusdirmanfeecatids  );
				$wpbusdirman_add_update_option( 'wpbusdirman_settings_fees_desc_'.$whichfeeid, $_REQUEST['wpbusdirman_fees_desc'] );

				_e("Task completed successfully","WPBDM");
				echo "<p><a href=\"?page=wpbdman_c2\">";
				_e("View current listing fees","WPBDM");
				echo "</a></p>";
		}

		if(!empty($wpbusdirman_field_vals) && (!$hidenolistingfeemsg))
		{
				echo "<p><a href=\"?page=wpbdman_c2&action=addnewfee\">";
				_e("Add New Listing Fee","WPBDM");
				echo "</a></p>";

				echo "<table class=\"widefat\" cellspacing=\"0\">";
				echo "<thead>";
				echo "<tr>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_labeltext;
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_amounttext;
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_durationtext;
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_imagestext;
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_appliedtotext;
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_actiontext;
				echo "</th>";
				echo "</tr>";
				echo "</thead>";
				echo "<tfoot>";
				echo "<tr>";

				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_labeltext;
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_amounttext;
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_durationtext;
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_imagestext;
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_appliedtotext;
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				echo $wpbusdirman_actiontext;
				echo "</th>";
				echo "</tr>";
				echo "</tfoot>";
				echo "<tbody>";

				if($wpbusdirman_field_vals)
				{
					foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
					{

						echo "<tr><td>".get_option('wpbusdirman_settings_fees_label_'.$wpbusdirman_field_val)."</td>";
						echo "<td>".get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirman_field_val)."</td>";
						echo "<td>".get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirman_field_val);
						if(get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirman_field_val) == 1)
						{ echo " $wpbusdirman_daytext";} else { echo " $wpbusdirman_daystext";}
						echo "</td>";
						echo "<td>".get_option('wpbusdirman_settings_fees_images_'.$wpbusdirman_field_val)."</td>";
						echo "<td>";

							$wpbusdirman_sfeecats=get_option('wpbusdirman_settings_fees_categories_'.$wpbusdirman_field_val);

							$wpbusdirmansfeecats=explode(",",$wpbusdirman_sfeecats);
							$wpbusdirman_sfeecatitems=array();

							for ($i=0;isset($wpbusdirmansfeecats[$i]);++$i)
							{
								$wpbusdirman_sfeecatitems[]=$wpbusdirmansfeecats[$i];
							}

							if(in_array('0',$wpbusdirman_sfeecatitems))
							{
									$wpbusdirman_thecat_nameall=$wpbusdirman_allcatstext;
							}
							else
							{
								$wpbusdirman_thecat_nameall='';
							}

							if(!(strcasecmp($wpbusdirman_thecat_nameall, $wpbusdirman_allcatstext) == 0))
							{
								$wpbusdirman_myfeecats=array();

									if($wpbusdirman_sfeecatitems)
									{
										foreach ($wpbusdirman_sfeecatitems as $wpbusdirman_sfeecatitem)
										{
												$wpbusdirman_thecat_name=&get_term( $wpbusdirman_sfeecatitem, $wpbdmposttypecategory, $output, $filter );

												if(!empty($wpbusdirman_thecat_name))
												{
													$wpbusdirman_myfeecats[]=$wpbusdirman_thecat_name->name;
												}
										}
									}

											$wpbusdirman_myfeecat_names = implode(',',$wpbusdirman_myfeecats);
											echo $wpbusdirman_myfeecat_names;
							}
							else
							{
								echo " $wpbusdirman_thecat_nameall ";
							}

						echo "</td>";
						echo "<td><a href=\"?page=wpbdman_c2&action=editfee&feeid=$wpbusdirman_field_val\">";
						_e("Edit","WPBDM");
						echo "</a> | <a href=\"?page=wpbdman_c2&action=deletefee&feeid=$wpbusdirman_field_val\">";
						_e("Delete","WPBDM");
						echo "</a></td></tr>";

					}
				}

				echo "</tbody></table>";
		}
		else
		{
			if(!$hidenolistingfeemsg)
			{
				if(!$hasnomodules)
				{
					_e("You do not have any listing fees setup yet.","WPBDM");
					echo "<p><a href=\"?page=wpbdman_c2&action=addnewfee\">";
					_e("Add New Listing Fee","WPBDM");
					echo "</a></p>";
				}
			}
		}
	}

wpbusdirman_admin_foot();

}

function wpbusdirman_my_fee_cats()
{
	global $wpbdmposttypecategory;

	$wpbusdirman_my_fee_cats='';

			$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');

			if($wpbusdirman_myterms)
			{
				foreach($wpbusdirman_myterms as $wpbusdirman_myterm)
				{
					$wpbusdirman_postcatitems[]=$wpbusdirman_myterm->term_id;
				}
			}

			$wpbusdirman_feecats=array();
			$wpbusdirman_feecats=get_option('wpbusdirman_settings_fees_categories');

			if(isset($wpbusdirman_feecats) && !empty($wpbusdirman_feecats))
			{
				$wpbusdirmanfeecats=explode(",",$wpbusdirman_feecats);
				$wpbusdirman_feecatitems=array();

				for ($i=0;isset($wpbusdirmanfeecats[$i]);++$i)
				{
					$wpbusdirman_feecatitems[]=$wpbusdirmanfeecats[$i];
				}
			}

			if($wpbusdirman_postcatitems)
			{
				foreach($wpbusdirman_postcatitems as $wpbusdirman_postcatitem)
				{
					if(in_array($wpbusdirman_postcatitem,$wpbusdirman_feecatitems)){$wpbusdirman_theselcat="selected";}else{ $wpbusdirman_theselcat='';}

					$wpbusdirman_my_fee_cats.="<option value=\"";
					$wpbusdirman_my_fee_cats.=$wpbusdirman_postcatitem;
					$wpbusdirman_my_fee_cats.="\" $wpbusdirman_theselcat>";
					$wpbdmtname=&get_term( $wpbusdirman_postcatitem, $wpbdmposttypecategory, $output, $filter );

					$wpbusdirman_my_fee_cats.=$wpbdmtname->name;



					$wpbusdirman_my_fee_cats.="</option>";
				}
			}

	return	$wpbusdirman_my_fee_cats;
}

function wpbusdirman_gpid(){
	global $wpdb;
	$wpbusdirman_pageid = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_content LIKE '%[WPBUSDIRMANUI]%' AND post_status='publish' AND post_type='page'");
	return $wpbusdirman_pageid;
}

function wpbusdirman_home_screen()
{


	global $wpbusdirman_db_version,$wpbdmposttypecategory,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$listyle="style=\"width:auto;float:left;margin-right:5px;\"";
	$listyle2="style=\"width:200px;float:left;margin-right:5px;\"";

	wpbusdirman_admin_head();

			$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');

			if($wpbusdirman_myterms)
			{
				foreach($wpbusdirman_myterms as $wpbusdirman_myterm)
				{
					$wpbusdirman_postcatitems[]=$wpbusdirman_myterm->term_id;
				}
			}




		if(!empty($wpbusdirman_postcatitems))
		{
			foreach($wpbusdirman_postcatitems as $wpbusdirman_postcatitem)
			{
				$wpbusdirman_tlincat=&get_term( $wpbusdirman_postcatitem, $wpbdmposttypecategory, $output, $filter );
				$wpbusdirman_totallistingsincat[]=$wpbusdirman_tlincat->count;
			}
			$wpbusdirman_totallistings=array_sum($wpbusdirman_totallistingsincat);
			$wpbusdirman_totalcatsindir=count($wpbusdirman_postcatitems);
		}
		else
		{
			$wpbusdirman_totallistings=0;
			$wpbusdirman_totalcatsindir=0;
		}

	echo "<h3 style=\"padding:10px;\">";
	_e("Options Menu","WPBDM");
	echo "</h3><p>";
	_e("You are using version","WPBDM");
	echo " <b>$wpbusdirman_db_version</b> </p>";

	echo "<ul>";
	echo "<li class=\"button\" $listyle><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c1\">";
	_e("Configure/Manage Options","WPBDM");
	echo "</a></li>";
	echo "<li class=\"button\" $listyle><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c2\">";
	_e("Setup/Manage Fees","WPBDM");
	echo "</a></li>";
	echo "<li class=\"button\" $listyle><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c3\">";
	_e("Setup/Manage Form Fields","WPBDM");
	echo "</a></li>";
	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_31'] == "yes")
	{
		echo "<li class=\"button\" $listyle><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c4\">";
		_e("Featured Listings Pending Upgrade","WPBDM");
		echo "</a></li>";
	}
	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes")
	{
		echo "<li class=\"button\" $listyle><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c5\">";
		_e("Manage Paid Listings","WPBDM");
		echo "</a></li>";
	}

	echo "</ul><br/>";
	echo "<div style=\"clear:both;\"></div>";
	echo "<ul>";
	echo "<li $listyle2>";
	_e("Listings in directory","WPBDM");
	echo ": (<b>$wpbusdirman_totallistings</b>)";
	echo "</li>";
	echo "<li $listyle2>";
	_e("Categories In Directory","WPBDM");
	echo ": (<b>$wpbusdirman_totalcatsindir</b>)";
	echo "</li>";
	echo "</ul>";

	echo "<div style=\"clear:both;\"></div>";

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_26'] == "yes")
	{
		echo "<h4>";
		_e("Tips for Use and other information","WPBDM");
		echo "</h4>";
		echo "<ol>";
		if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes")
		{
			echo "<li>";
			_e("Leave default post status set to pending to avoid misuse","WPBDM");
			echo "<br/>";
			_e("Listing payment status is not automatically updated after payment has been made. For this reason it is best to leave the listing default post status set to pending so you can verify that a listing has been paid for before it gets publised.","WPBDM");
			echo "</li>";

		echo "<li>";
		_e("Valid Merchant ID and sandbox seller ID required for Google checkout payment processing ","WPBDM");
		echo "</li>";
		}
		echo "<li>";
		_e("The plugin uses it's own page template to display single posts and category listings. You can modify the templates to make them match your site by editing the template files in the posttemplates folder which you will find inside the plugin folder. ","WPBDM");
		echo "</li>";
		echo "<li>";
		_e("To protect user privacy Email addresses are not displayed in listings. ","WPBDM");
		echo "</li>";
		echo "<li>";
		_e("reCaptcha human verification is built into the plugin contact form but comes turned off by default. To use it you need to turn it on. You also need to have a recaptcha public and private key. To obtain these visit recaptcha.net then enter the keys into he related boxes from the manage options page. ","WPBDM");
		echo "</li>";
		echo "<li>";
		_e("You can hide these tips by going to Configure/Manage Options and checking the box next to 'Hide tips for use and other information'","WPBDM");
		echo "</li>";
		echo "</ol>";



	}
	wpbusdirman_admin_foot();


}



function wpbusdirman_display_postform_preview()
{
		echo "<h3 style=\"padding:10px;\">";
		_e("Previewing the post form","WPBDM");
		echo "</h3>";
		echo "<div style=\"float:right; margin-top:-49px;margin-right:250px;border-left:1px solid#ffffff;padding:10px;\"><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c3\">";
		_e("Manage Form Fields","WPBDM");
		echo "</a></div>";
		echo "<p>";

		wpbusdirman_displaypostform($makeactive='-1',$errors='',$neworedit='',$wpbdmlistingid='');
}

function wpbusdirman_display_postform_add()
{

	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_3'] == "yes")
	{
		if(!is_user_logged_in())
		{
			$wpbusdirman_loginurl=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_4'];

			if(!isset($wpbusdirman_loginurl) || empty($wpbusdirman_loginurl))
			{
				$wpbusdirman_loginurl=get_option('siteurl').'/wp-login.php';
			}

			echo '<h3>Welcome to the Chelsea First Business Directory Signup</h3>';
			echo "<p>";
			echo 'In order to register your business we\'ll need you to create an account.  This is the account that you will later use to log in to edit your listing.';
			echo "</p>";
			echo '<div class="biz-btns">';
			echo '<a href="'.$wpbusdirman_loginurl.'" title="Login" class="login-btn"><img src="/wp-content/plugins/wp-business-directory-manager/images/login-btn.png"</a>';
			echo '<img style="margin: 0 14px;" src="/wp-content/plugins/wp-business-directory-manager/images/or.png" />';
			echo '<a href="'.$wpbusdirman_loginurl.'/?action=register" title="Register" class="register-btn"><img src="/wp-content/plugins/wp-business-directory-manager/images/register-btn.png"</a>';
			
			echo '</div>';
		}
		else
		{
			echo "<h3 style=\"padding:10px;\">";
			_e("Add New Listing","WPBDM");
			echo "</h3>";
			echo "<div style=\"float:right; margin-top:-49px;margin-right:250px;border-left:1px solid#ffffff;padding:10px;\"><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c3\">";
			_e("Manage Form Fields","WPBDM");
			echo "</a></div>";
			echo "<p>";

			wpbusdirman_displaypostform($makeactive=1,$errors='',$neworedit='',$wpbdmlistingid='');
		}

	}
	else
	{
		wpbusdirman_displaypostform($makeactive=1,$errors='',$neworedit='',$wpbdmlistingid='');
	}
}

function wpbusdirman_buildform()
{
	global $table_prefix;
	wpbusdirman_admin_head();
	$wpbusdirman_error=false;
	$wpbusdirman_notify='';
	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');
	$wpbusdirman_field_vals_max=max($wpbusdirman_field_vals);
	$wpbusdirman_autoincrementfieldorder=0;
	$wpbusdirman_error_message='';
	$wpbusdirmanaction='';

	if(isset($_REQUEST['action']) && !empty($_REQUEST['action']))
	{
		$wpbusdirmanaction=$_REQUEST['action'];
	}

	if ( $wpbusdirmanaction == 'viewpostform')
	{

		wpbusdirman_display_postform_preview();

	}
	elseif ( $wpbusdirmanaction == 'addnewlisting')
	{

		wpbusdirman_display_postform_add();
	}
	elseif ( $wpbusdirmanaction == 'updateoptions')
	{
		$whichtext=$_REQUEST['whichtext'];

		if(isset($whichtext) && !empty($whichtext))
		{
			$wpbusdirman_add_update_option="update_option";
		}
		else
		{
			$whichtext=($wpbusdirman_field_vals_max+1);
			$wpbusdirman_add_update_option="add_option";
			$wpbusdirman_autoincrementfieldorder=1;
		}


		if(!isset($_REQUEST['wpbusdirman_field_label']) || empty($_REQUEST['wpbusdirman_field_label']))
		{

				$wpbusdirman_error=true;
				$wpbusdirman_error_message.="<li>";
				$wpbusdirman_error_message.=__("Field NOT added! You have submitted the form without a field label. A field label is required before the field can be added. Please try adding the field again.","WPBDM");
				$wpbusdirman_error_message.="</li>";

		}
		else
		{

				if(!isset($_REQUEST['wpbusdirman_field_association']) || empty($_REQUEST['wpbusdirman_field_association']))
				{
					$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_association_'.$whichtext, "meta"  );
				}
				elseif(isset($_REQUEST['wpbusdirman_field_association']) && !empty($_REQUEST['wpbusdirman_field_association']) )
				{

					if( $_REQUEST['wpbusdirman_field_association'] != 'meta')
					{
						if(wpbusdirman_exists_association($_REQUEST['wpbusdirman_field_association'],$_REQUEST['wpbusdirman_field_label']))
						{
							$wpbusdirman_error=true;
							$wpbusdirman_error_message.="<li>";
							$wpbusdirman_error_message.=__("You tried to associate a field with a wordpress post title, category, tag, description, excerpt but another field is already associated with the element. The field has been associated with the post meta entity instead.","WPBDM");
							$wpbusdirman_error_message.="</li>";

							$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_association_'.$whichtext, "meta"  );
						}
						else
						{
							$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_association_'.$whichtext, $_REQUEST['wpbusdirman_field_association']  );
						}
					}
					else
					{
						$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_association_'.$whichtext, $_REQUEST['wpbusdirman_field_association']  );
					}
				}

				if(!isset($_REQUEST['wpbusdirman_field_required']) || empty($_REQUEST['wpbusdirman_field_required']))
				{
					$_REQUEST['wpbusdirman_field_required']="no";
				}

				if(!isset($_REQUEST['wpbusdirman_field_showinexcerpt']) || empty($_REQUEST['wpbusdirman_field_showinexcerpt']))
				{
					$_REQUEST['wpbusdirman_field_showinexcerpt']="no";
				}

				if( $_REQUEST['wpbusdirman_field_association'] == 'category')
				{
						if( $_REQUEST['wpbusdirman_field_type'] == 1  ||  $_REQUEST['wpbusdirman_field_type'] == 3 ||  $_REQUEST['wpbusdirman_field_type'] == 4 ||  $_REQUEST['wpbusdirman_field_type'] == 5 )
						{
							$wpbusdirman_error=true;
							$wpbusdirman_error_message.="<li>";
							$wpbusdirman_error_message.=__("The category field can only be assigned to the single option dropdown select list or checkbox type. It has been defaulted to a select list. If you want the user to be able to select multiple categories use the checkbox field type.","WPBDM");
							$wpbusdirman_error_message.="</li>";

							$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_type_'.$whichtext, "2"  );
						}
						else
						{
							$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_type_'.$whichtext, $_REQUEST['wpbusdirman_field_type']  );
						}
				}

				if($_REQUEST['wpbusdirman_field_validation'] == 'email')
				{
					if(!wpbusdirman_exists_validation($validation='email'))
					{
						$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_validation_'.$whichtext, $_REQUEST['wpbusdirman_field_validation']  );
					}
					else
					{
						$wpbusdirman_error=true;
						$wpbusdirman_error_message.="<li>";
						$wpbusdirman_error_message.=__("You already have a field using the email validation. At this time the system will allow only 1 valid email field. Change the validation for that field to something else then try again.","WPBDM");
						$wpbusdirman_error_message.="</li>";
					}
				}


				$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_label_'.$whichtext, $_REQUEST['wpbusdirman_field_label']  );
				$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_type_'.$whichtext, $_REQUEST['wpbusdirman_field_type']  );
				$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_options_'.$whichtext, $_REQUEST['wpbusdirman_field_options']  );
				$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_required_'.$whichtext, $_REQUEST['wpbusdirman_field_required']  );
				$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_showinexcerpt_'.$whichtext, $_REQUEST['wpbusdirman_field_showinexcerpt']  );


				$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_validation_'.$whichtext, $_REQUEST['wpbusdirman_field_validation']  );
				$wpbusdirman_newfieldorder='';
				$wpbusdirman_currentfieldorder=get_option('wpbusdirman_postform_field_order_'.$whichtext);
				if(isset($_REQUEST['wpbusdirman_field_order']) && !empty($_REQUEST['wpbusdirman_field_order']))
				{
					$wpbusdirman_newfieldorder=$_REQUEST['wpbusdirman_field_order'];
				}


				if($wpbusdirman_newfieldorder > $wpbusdirman_field_vals_max)
				{
					$wpbusdirman_newfieldorder=($wpbusdirman_field_vals_max);

					$wpbusdirman_error=true;
					$wpbusdirman_error_message.="<li>";
					$wpbusdirman_error_message.=__("You submitted a value that was greater than the actual number of fields existing. As a result, the field order value has been adjusted according to the number of fields that actually exist.","WPBDM");
					$wpbusdirman_error_message.="</li>";
				}

				if($wpbusdirman_autoincrementfieldorder)
				{
					$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_order_'.$whichtext, $whichtext  );
				}
				else
				{

					if($wpbusdirman_field_vals)
					{
						foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
						{
							$wpbusdirman_field_val_orders[]=get_option('wpbusdirman_postform_field_order_'.$wpbusdirman_field_val);

								if(get_option('wpbusdirman_postform_field_order_'.$wpbusdirman_field_val) == $wpbusdirman_newfieldorder)
								{
									$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_order_'.$wpbusdirman_field_val, $wpbusdirman_currentfieldorder  );
									$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_order_'.$whichtext, $wpbusdirman_newfieldorder  );
								}
								else
								{
									$wpbusdirman_add_update_option( 'wpbusdirman_postform_field_order_'.$whichtext, $wpbusdirman_newfieldorder  );
								}

						}
					}
				}
		}


			wpbusdirman_fields_list();


		if($wpbusdirman_error)
		{
			$wpbusdirman_notify="<div class=\"updated fade\" style=\"padding:10px;background:#FF8484;font-weight:bold;\"><ul>";
			$wpbusdirman_notify.=$wpbusdirman_error_message;
			$wpbusdirman_notify.="</ul></div>";
			echo "$wpbusdirman_notify";
		}
	}
	elseif(($wpbusdirmanaction == 'deletefield'))
	{
		if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
		{
			$wpbusdirman_fieldid_todel=$_REQUEST['id'];


			if(get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_label_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_type_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_options_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_options_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_order_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_order_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_association_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_required_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_required_'.$wpbusdirman_fieldid_todel);}
			if(get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_fieldid_todel)){delete_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_fieldid_todel);}

			$wpbusdirman_delete_message=__("The field has been deleted.","WPBDM");

		}

		else
		{
			$wpbusdirman_delete_message=__("There was no ID supplied for the field. No action has been taken","WPBDM");
		}


				$wpbusdirman_notify="<div class=\"updated fade\" style=\"padding:10px;\"><ul>";
				$wpbusdirman_notify.=$wpbusdirman_delete_message;
				$wpbusdirman_notify.="</ul></div>";
				echo "$wpbusdirman_notify";


				wpbusdirman_fields_list();
	}
	elseif(($wpbusdirmanaction == 'addnewfield') || ($wpbusdirmanaction == 'editfield'))
	{
		$wpbusdirman_fieldtoedit='';
		if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
		{
			$wpbusdirman_fieldtoedit=$_REQUEST['id'];
		}


		if(isset($wpbusdirman_fieldtoedit) && !empty($wpbusdirman_fieldtoedit))
		{

			echo "<p>";
			_e("Make your changes then submit the form to update the field","WPBDM");
			echo "<p><a href=\"?page=wpbdman_c3&action=addnewfield\">";
			_e("Add New Form Field","WPBDM");
			echo "</a></p>";
		}
		else
		{

			echo "<p>";
			_e("Add extra fields to the standard fields used in the form that users will fill out to submit their business directory listing.","WPBDM");
		}
			echo "</p>";
			echo "<h3 style=\"padding:10px;\">";
		if(isset($wpbusdirman_fieldtoedit) && !empty($wpbusdirman_fieldtoedit))
		{
			_e("Edit Field","WPBDM");
		}
		else
		{
			_e("Add New Field","WPBDM");
		}

		$wpbusdirman_currenttype=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_fieldtoedit);
		$wpbusdirman_currentassociation=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_fieldtoedit);
		$wpbusdirman_currentvalidation=get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_fieldtoedit);
		$wpbusdirman_currentrequired=get_option('wpbusdirman_postform_field_required_'.$wpbusdirman_fieldtoedit);
		$wpbusdirman_currentshowinexcerpt=get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_fieldtoedit);



		if($wpbusdirman_currentvalidation == 'email')
		{
			$wpbusdirman_validation1="selected";
		}
		else
		{
			$wpbusdirman_validation1="";
		}
		if($wpbusdirman_currentvalidation == 'url')
		{
			$wpbusdirman_validation2="selected";
		}
		else
		{
			$wpbusdirman_validation2="";
		}
		if($wpbusdirman_currentvalidation == 'missing')
		{
			$wpbusdirman_validation3="selected";
		}
		else
		{
			$wpbusdirman_validation3="";
		}
		if($wpbusdirman_currentvalidation == 'numericdeci')
		{
			$wpbusdirman_validation4="selected";
		}
		else
		{
			$wpbusdirman_validation4="";
		}
		if($wpbusdirman_currentvalidation == 'numericwhole')
		{
			$wpbusdirman_validation5="selected";
		}
		else
		{
			$wpbusdirman_validation5="";
		}
		if($wpbusdirman_currentvalidation == 'date')
		{
			$wpbusdirman_validation6="selected";
		}
		else
		{
			$wpbusdirman_validation6="";
		}
		if($wpbusdirman_currentassociation == 'title')
		{
			$wpbusdirman_associationselected1="selected";
		}
		else
		{
			$wpbusdirman_associationselected1="";
		}
		if($wpbusdirman_currentassociation == 'description')
		{
			$wpbusdirman_associationselected2="selected";
		}
		else
		{
			$wpbusdirman_associationselected2="";
		}
		if($wpbusdirman_currentassociation == 'category')
		{
			$wpbusdirman_associationselected3="selected";
		}
		else
		{
			$wpbusdirman_associationselected3="";
		}
		if($wpbusdirman_currentassociation == 'excerpt')
		{
			$wpbusdirman_associationselected4="selected";
		}
		else
		{
			$wpbusdirman_associationselected4="";
		}
		if($wpbusdirman_currentassociation == 'meta')
		{
			$wpbusdirman_associationselected5="selected";
		}
		else
		{
			$wpbusdirman_associationselected5="";
		}
		if($wpbusdirman_currentassociation == 'tags')
		{
			$wpbusdirman_associationselected6="selected";
		}
		else
		{
			$wpbusdirman_associationselected6="";
		}

		if($wpbusdirman_currenttype == 1)
		{
			$wpbusdirman_op_selected1="selected";
		}
		else
		{
			$wpbusdirman_op_selected1='';
		}
		if($wpbusdirman_currenttype == 2)
		{
			$wpbusdirman_op_selected2="selected";
		}
		else
		{
			$wpbusdirman_op_selected2='';
		}
		if($wpbusdirman_currenttype == 3)
		{
			$wpbusdirman_op_selected3="selected";
		}
		else
		{
			$wpbusdirman_op_selected3='';
		}
		if($wpbusdirman_currenttype == 4)
		{
			$wpbusdirman_op_selected4="selected";
		}
		else
		{
			$wpbusdirman_op_selected4='';
		}
		if($wpbusdirman_currenttype == 5)
		{
			$wpbusdirman_op_selected5="selected";
		}
		else
		{
			$wpbusdirman_op_selected5='';
		}
		if($wpbusdirman_currenttype == 6)
		{
			$wpbusdirman_op_selected6="selected";
		}
		else
		{
			$wpbusdirman_op_selected6='';
		}
		if($wpbusdirman_currentrequired == 'yes')
		{
			$wpbusdirman_required_selected1="selected";
		}
		else
		{
			$wpbusdirman_required_selected1='';
		}
		if($wpbusdirman_currentrequired == 'no')
		{
			$wpbusdirman_required_selected2="selected";
		}
		else
		{
			$wpbusdirman_required_selected2='';
		}
		if($wpbusdirman_currentshowinexcerpt == 'yes')
		{
			$wpbusdirman_showinexcerpt_selected1="selected";
		}
		else
		{
			$wpbusdirman_showinexcerpt_selected1='';
		}
		if($wpbusdirman_currentshowinexcerpt == 'no')
		{
			$wpbusdirman_showinexcerpt_selected2="selected";
		}
		else
		{
			$wpbusdirman_showinexcerpt_selected2='';
		}

		echo "</h3>";
		echo "<div style=\"float:right; margin-top:-49px;margin-right:250px;border-left:1px solid#ffffff;padding:10px;\"><a style=\"text-decoration:none;\" href=\"?page=wpbdman_c3&action=viewpostform\">";
		_e("Preview the form","WPBDM");
		echo "</a></div>";
		echo "<form method=\"post\">";
		echo "<p>";
		_e("Field Label","WPBDM");
		echo "<br/>";
		echo "<input type=\"text\" name=\"wpbusdirman_field_label\" style=\"width:50%;\" value=\"";
		echo get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_fieldtoedit);
		echo "\">";
		echo"</p>";
		_e("Field Type","");
		echo " <select name=\"wpbusdirman_field_type\">";
		echo "<option value=\"\">";
		_e("Select Field Type","WPBDM");
		echo "</option>";
		echo "<option value=\"1\" $wpbusdirman_op_selected1>";
		_e("Input Text Box","WPBDM");
		echo "</option>";
		echo "<option value=\"2\" $wpbusdirman_op_selected2>";
		_e("Select List","WPBDM");
		echo "</option>";
		echo "<option value=\"5\" $wpbusdirman_op_selected5>";
		_e("Multiple Select List","WPBDM");
		echo "</option>";
		echo "<option value=\"4\" wpbusdirman_op_selected4>";
		_e("Radio Button","WPBDM");
		echo "</option>";
		echo "<option value=\"6\" $wpbusdirman_op_selected6>";
		_e("Checkbox","WPBDM");
		echo "</option>";
		echo "<option value=\"3\"  $wpbusdirman_op_selected3>";
		_e("Textarea","WPBDM");
		echo "</option>";
		echo "</select>";
		echo "<p>";
		_e("Field Options","WPBDM");
		echo " (";
		_e("for drop down lists, radio buttons, checkboxes ","WPBDM");
		echo ") (";
		_e("separate by commas","WPBDM");
		echo ")<br/>";
		echo "<input type=\"text\" name=\"wpbusdirman_field_options\" style=\"width:90%;\" value=\"";
		echo get_option('wpbusdirman_postform_field_options_'.$wpbusdirman_fieldtoedit);
		echo "\">";
		/*if(isset($wpbusdirman_fieldtoedit) && !empty($wpbusdirman_fieldtoedit))
		{	echo "<p>";

			_e("Field Order","WPBDM");
			echo "(";
			_e("Numerical value to set position of field in form","WPBDM");
			echo ")";
			echo "<br/>";
			echo "<input type=\"text\" name=\"wpbusdirman_field_order\" style=\"width:20%;\" value=\"";
			echo get_option('wpbusdirman_postform_field_order_'.$wpbusdirman_fieldtoedit);
			echo "\">";
			echo "</p>";
		}*/
		echo "<p>";
		_e("Associate Field With","WPBDM");
		echo " <select name=\"wpbusdirman_field_association\">";
		echo "<option value=\"\">";
		_e("Select Option","WPBDM");
		echo "</option>";
		echo "<option value=\"title\" $wpbusdirman_associationselected1>";
		_e("Post Title","WPBDM");
		echo "</option>";
		echo "<option value=\"description\" $wpbusdirman_associationselected2>";
		_e("Post Content","WPBDM");
		echo "</option>";
		echo "<option value=\"category\" $wpbusdirman_associationselected3>";
		_e("Post Category","WPBDM");
		echo "</option>";
		echo "<option value=\"excerpt\" $wpbusdirman_associationselected4>";
		_e("Post Excerpt","WPBDM");
		echo "</option>";
		echo "<option value=\"meta\" $wpbusdirman_associationselected5>";
		_e("Post Meta","WPBDM");
		echo "</option>";
		echo "<option value=\"tags\" $wpbusdirman_associationselected6>";
		_e("Post Tags","WPBDM");
		echo "</option>";
		echo "</select></p>";
		echo "<p>";
		_e("Validate Against","WPBDM");
		echo " <select name=\"wpbusdirman_field_validation\">";
		echo "<option value=\"\">";
		_e("Select Option","WPBDM");
		echo "</option>";
		echo "<option value=\"email\" $wpbusdirman_validation1>";
		_e("Email Format","WPBDM");
		echo "</option>";
		echo "<option value=\"url\" $wpbusdirman_validation2>";
		_e("URL format","WPBDM");
		echo "</option>";
		echo "<option value=\"missing\" $wpbusdirman_validation3>";
		_e("Missing Value","WPBDM");
		echo "</option>";
		echo "<option value=\"numericwhole\" $wpbusdirman_validation4>";
		_e("Whole Number Value","WPBDM");
		echo "</option>";
		echo "<option value=\"numericdeci\" $wpbusdirman_validation5>";
		_e("Decimal Value","WPBDM");
		echo "</option>";
		echo "<option value=\"date\" $wpbusdirman_validation6>";
		_e("Date Format","WPBDM");
		echo "</option>";
		echo "</select></p>";
		echo "<p>";
		_e("Is Field Required?","WPBDM");
		echo " <select name=\"wpbusdirman_field_required\">";
		echo "<option value=\"\">";
		_e("Select Option","WPBDM");
		echo "</option>";
		echo "<option value=\"yes\" $wpbusdirman_required_selected1>";
		_e("Yes","WPBDM");
		echo "</option>";
		echo "<option value=\"no\" $wpbusdirman_required_selected2>";
		_e("No","WPBDM");
		echo "</option>";
		echo "</select></p>";
		echo "<p>";
		_e("Show this value in post excerpt?","WPBDM");
		echo " <select name=\"wpbusdirman_field_showinexcerpt\">";
		echo "<option value=\"\">";
		_e("Select Option","WPBDM");
		echo "</option>";
		echo "<option value=\"yes\" $wpbusdirman_showinexcerpt_selected1>";
		_e("Yes","WPBDM");
		echo "</option>";
		echo "<option value=\"no\" $wpbusdirman_showinexcerpt_selected2>";
		_e("No","WPBDM");
		echo "</option>";
		echo "</select></p>";
		echo "<input type=\"hidden\" name=\"action\" value=\"updateoptions\" />";
		echo "<input type=\"hidden\" name=\"whichtext\" value=\"$wpbusdirman_fieldtoedit\" />";
		echo "<input name=\"updateoptions\" type=\"submit\" value=\"";
		if(isset($wpbusdirman_fieldtoedit) && !empty($wpbusdirman_fieldtoedit))
		{
			_e("Update Field","WPBDM");
		}
		else
		{
			_e("Add New Field","WPBDM");
		}
		echo "\"></form>";

	}
	elseif($wpbusdirmanaction == 'post')
	{
		wpbusdirman_do_post();
	}
	else
	{
		wpbusdirman_fields_list();
	}

	wpbusdirman_admin_foot();
}

function wpbusdirman_exists_association($association,$label)
{

	$wpbusdirman_exists_association=false;

	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_association_');

	if($wpbusdirman_field_vals)
	{
		foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
		{

			if(get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val) == $association)
			{
				$wpbdmassocid=$wpbusdirman_field_val;
				$wpbusdirman_ftitle=get_option('wpbusdirman_postform_field_label_'.$wpbdmassocid);


				//If the field label value is the same as the association value then return false
				if($wpbusdirman_ftitle == $label)
				{
					$wpbusdirman_exists_association=false;
				}
				else
				{
					//Otherwise return true
					$wpbusdirman_exists_association=true;
				}
			}
		}
	}

	return $wpbusdirman_exists_association;
}

function wpbusdirman_exists_validation($validation)
{

	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_validation_');

	if($wpbusdirman_field_vals)
	{
		foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
		{

			if(get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_field_val) == $validation)
			{
				$wpbusdirman_exists_validation=true;
			}
			else
			{
				$wpbusdirman_exists_validation=false;
			}

		}
	}

	return $wpbusdirman_exists_validation;
}


		function wpbusdirman_generatePassword($length=6,$level=2)
		{

		   list($usec, $sec) = explode(' ', microtime());
		   srand((float) $sec + ((float) $usec * 100000));

		   $validchars[1] = "0123456789abcdfghjkmnpqrstvwxyz";
		   $validchars[2] = "0123456789abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		   $validchars[3] = "0123456789_!@#$%&*()-=+/abcdfghjkmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_!@#$%&*()-=+/";

		   $password  = "";
		   $counter   = 0;

		   while ($counter < $length)
		   {
			 $actChar = substr($validchars[$level], rand(0, strlen($validchars[$level])-1), 1);

			 // All character must be different
			 if (!strstr($password, $actChar))
			 {
				$password .= $actChar;
				$counter++;
			 }
		   }

		   return $password;

		}

function wpbusdirman_filterinput($input) {
	$input = strip_tags($input);
	$input = trim($input);
	return $input;
}

function wpbusdirman_fields_list()
{

	global $wpbusdirman_hide_formlist, $wpbusdirman_labeltext, $wpbusdirman_typetext,$wpbusdirman_optionstext,$wpbusdirman_ordertext,$wpbusdirman_actiontext,$wpbusdirman_associationtext,$wpbusdirman_validationtext,$wpbusdirman_requiredtext,$wpbusdirman_showinexcerpttext;

	if(!$wpbusdirman_hide_formlist)
	{
		echo "<h3 style=\"padding:10px;\">";
		_e("Manage Form Fields","WPBDM");
		echo "</h3><p>";
		_e("Make changes to your existing form fields.","WPBDM");
		echo "<p><a href=\"?page=wpbdman_c3&action=addnewfield\">";
		_e("Add New Form Field","WPBDM");
		echo "</a> | <a href=\"?page=wpbdman_c3&action=viewpostform\">";
		_e("Preview Form","WPBDM");
		echo "</a> | <a href=\"?page=wpbdman_c3&action=addnewlisting\">";
		_e("Add New Listing","WPBDM");
		echo "</a></p>";

		$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');

		echo "<table class=\"widefat\" cellspacing=\"0\">";
		echo "<thead>";
		echo "<tr>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_labeltext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_typetext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_associationtext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_validationtext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_optionstext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_requiredtext;
		echo "</th>";
		//echo "<th scope=\"col\" class=\"manage-column\">";
		//echo $wpbusdirman_ordertext;
		//echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_showinexcerpttext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_actiontext;
		echo "</th>";
		echo "</tr>";
		echo "</thead>";
		echo "<tfoot>";
		echo "<tr>";

		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_labeltext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_typetext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_associationtext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_validationtext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_optionstext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_requiredtext;
		echo "</th>";
		//echo "<th scope=\"col\" class=\"manage-column\">";
		//echo $wpbusdirman_ordertext;
		//echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_showinexcerpttext;
		echo "</th>";
		echo "<th scope=\"col\" class=\"manage-column\">";
		echo $wpbusdirman_actiontext;
		echo "</th>";
		echo "</tr>";
		echo "</tfoot>";
		echo "<tbody>";

		if($wpbusdirman_field_vals)
		{
			foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
			{
				$wpbdm_thefieldlabel=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
				if(!isset($wpbdm_thefieldlabel) || empty($wpbdm_thefieldlabel)){update_option( 'wpbusdirman_postform_field_label_'.$wpbusdirman_field_val, 'Unlabeled'.$wpbusdirman_field_val  );}
				$wpbdm_thefieldassociation=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
				if(!isset($wpbdm_thefieldassociation) || empty($wpbdm_thefieldassociation)){update_option( 'wpbusdirman_postform_field_association_'.$wpbusdirman_field_val, 'meta'  );}
				$wpbdm_thefieldrequired=get_option('wpbusdirman_postform_field_required_'.$wpbusdirman_field_val);
				if(!isset($wpbdm_thefieldrequired) || empty($wpbdm_thefieldrequired)){update_option( 'wpbusdirman_postform_field_required_'.$wpbusdirman_field_val, 'no'  );}
				$wpbdm_thefieldshowinexcerpt=get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val);
				if(!isset($wpbdm_thefieldshowinexcerpt) || empty($wpbdm_thefieldshowinexcerpt)){update_option( 'wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val, 'no'  );}
				$wpbdm_thefieldtype=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_val);
				if(!isset($wpbdm_thefieldtype) || empty($wpbdm_thefieldtype)){update_option( 'wpbusdirman_postform_field_type_'.$wpbusdirman_field_val, 1  );}



				echo "<tr><td>".get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val)."</td>";
				echo "<td>";
				$wpbusdirman_optypeval=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_val);
				if($wpbusdirman_optypeval == 1){ $wpbusdirman_optype_descr="Text Box";}
				if($wpbusdirman_optypeval == 2){ $wpbusdirman_optype_descr="Select List";}
				if($wpbusdirman_optypeval == 3){ $wpbusdirman_optype_descr="Textarea";}
				if($wpbusdirman_optypeval == 4){ $wpbusdirman_optype_descr="Radio Button";}
				if($wpbusdirman_optypeval == 5){ $wpbusdirman_optype_descr="Multi-Select List";}
				if($wpbusdirman_optypeval == 6){ $wpbusdirman_optype_descr="Checkbox";}
				echo $wpbusdirman_optype_descr;
				echo"</td>";
				echo "<td>".get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val)."</td>";
				echo "<td>".get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_field_val)."</td>";
				echo "<td>";
				$wpbusdirman_field_options=get_option('wpbusdirman_postform_field_options_'.$wpbusdirman_field_val);
				$wpbusdirman_field_options_array=explode(",",$wpbusdirman_field_options);

					for ($i=0;isset($wpbusdirman_field_options_array[$i]);++$i)
					{
						$wpbusdirman_field_options_arritems[$i]=trim($wpbusdirman_field_options_array[$i]);

							echo "<ul>";
							echo "<li>$wpbusdirman_field_options_array[$i]</li>";
							echo "</ul>";
					}

				echo "</td>";
				echo "<td>".get_option('wpbusdirman_postform_field_required_'.$wpbusdirman_field_val)."</td>";
				//echo "<td>".get_option('wpbusdirman_postform_field_order_'.$wpbusdirman_field_val)."</td>";
				echo "<td>".get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val)."</td>";
				echo "<td><a href=\"?page=wpbdman_c3&action=editfield&id=$wpbusdirman_field_val\">";
				_e("Edit","WPBDM");
				echo "</a> | <a href=\"?page=wpbdman_c3&action=deletefield&id=$wpbusdirman_field_val\">";
				_e("Delete","WPBDM");
				echo "</a></td></tr>";

			}
		}

		echo "</tbody></table>";
	}
}

function wpbusdirman_displaypostform_direct()
{
	wpbusdirman_displaypostform($makeactive=1,$wpbusdirmanerrors='',$neworedit='new',$wpbdmlistingid='');
}

function wpbusdirman_displaypostform($makeactive,$wpbusdirmanerrors,$neworedit,$wpbdmlistingid)
{

 	global $wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory,$wpbdmposttypetags,$wpbdmposttype;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	$wpbusdirmanselectedword="selected";
 	$wpbusdirmancheckedword="checked";
	 $wpbusdirman_field_value='';

 	// If there are no categories setup go no further

 	$args=array('hide_empty' => 0);
 	$wpbusdirman_postcats=get_terms( $wpbdmposttypecategory, $args);

 	if(!isset($wpbusdirman_postcats) || empty($wpbusdirman_postcats))
 	{
 		if(is_user_logged_in() && current_user_can('install_plugins'))
 		{
 			_e("There are no categories assigned to the business directory yet. You need to assign some categories to the business directory. Only admins can see this message. Regular users are seeing a message that they cannot add their listing at this time. Listings cannot be added until you assign categories to the business directory.","WPBDM");
 		}
 		else
 		{
 			_e("Your listing cannot be added at this time. Please try again later.","WPBDM");
 		}
 	}
 	else
	{


		if(($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_3'] == "yes") && !is_user_logged_in())
		{
				$wpbusdirman_loginurl=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_4'];

				if(!isset($wpbusdirman_loginurl) || empty($wpbusdirman_loginurl))
				{
					$wpbusdirman_loginurl=get_option('siteurl').'/wp-login.php';
				}

				echo '<h3>Welcome to the Chelsea First Business Directory Signup</h3>';
				echo "<p>";
				echo 'In order to register your business we\'ll need you to create an account.  This is the account that you will later use to log in to edit your listing.';
				echo "</p>";
				echo '<div class="biz-btns">';
				echo '<a href="'.$wpbusdirman_loginurl.'" title="Login" class="login-btn"><img src="/wp-content/plugins/wp-business-directory-manager/images/login-btn.png"</a>';
				echo '<img style="margin: 0 14px;" src="/wp-content/plugins/wp-business-directory-manager/images/or.png" />';
				echo '<a href="'.$wpbusdirman_loginurl.'/?action=register" title="Register" class="register-btn"><img src="/wp-content/plugins/wp-business-directory-manager/images/register-btn.png"</a>';

				echo '</div>';

		}
		else
		{
			$wpbusdirman_selectcattext=__("Choose One","WPBDM");
			$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');
			global $wpbusdirman_gpid,$permalinkstructure;
			$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
			if(!isset($permalinkstructure) || empty($permalinkstructure)){ $querysymbol="&amp";} else { $querysymbol="?";}



			echo "<div><form method=\"post\" action=\"$wpbusdirman_permalink\"><input type=\"hidden\" name=\"action\" value=\"viewlistings\"><input type=\"submit\" class=\"viewlistingsbutton\" value=\"";
			_e("View Listings","WPBDM");
			echo "\"></form>";
			echo "<form method=\"post\" action=\"$wpbusdirman_permalink\"><input type=\"submit\" class=\"viewlistingsbutton\" style=\"margin-right:10px;\" value=\"";
			_e("Directory","WPBDM");
			echo "\"></form>";
			echo "</div>";
			echo "<div class=\"clear\"></div>";

			echo "<FORM METHOD=\"POST\" ACTION=\"$wpbusdirman_permalink\"
				 ENCTYPE=\"application/x-www-form-urlencoded\">";
			echo "<input type=\"hidden\" name=\"formmode\" value=\"$makeactive\">";
			echo "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\">";
			echo "<input type=\"hidden\" name=\"wpbdmlistingid\" value=\"$wpbdmlistingid\">";
			echo "<input type=\"hidden\" name=\"action\" value=\"post\">";

			if(isset($wpbusdirmanerrors) && !empty($wpbusdirmanerrors)){echo "<ul id=\"wpbusdirmanerrors\">$wpbusdirmanerrors</ul>";}



			if($wpbusdirman_field_vals)
			{
				foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
				{
					$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
					$wpbusdirman_field_label_name=$wpbusdirman_field_label;
					// Get the field type
					$wpbusdirman_field_type=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_val);
					// Get the field options
					$wpbusdirman_field_options=get_option('wpbusdirman_postform_field_options_'.$wpbusdirman_field_val);
					// Get the field association
					$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);


					if($wpbusdirman_field_association == 'title')
					{
						$wpbusdirman_field_label_association="_title";
					}
					elseif($wpbusdirman_field_association == 'category')
					{
						$wpbusdirman_field_label_association="_category";
					}
					elseif($wpbusdirman_field_association == 'excerpt')
					{
						$wpbusdirman_field_label_association="_excerpt";
					}
					elseif($wpbusdirman_field_association == 'description')
					{
						$wpbusdirman_field_label_association="_description";
					}
					elseif($wpbusdirman_field_association == 'tags')
					{
						$wpbusdirman_field_label_association="_tags";
					}
					else
					{
						$wpbusdirman_field_label_association="_meta$wpbusdirman_field_val";
					}

					if(isset($wpbusdirmanerrors) && !empty($wpbusdirmanerrors))
					{
						if($wpbusdirman_field_label_association == "_category")
						{
							if($wpbusdirman_field_type == 2){$wpbusdirman_field_value=$_REQUEST['cat'];}
							elseif($wpbusdirman_field_type == 6){$wpbusdirman_field_value=$_REQUEST['wpbusdirman_field_label_category'];}
						}
						else
						{
							$wpbusdirman_field_value=$_REQUEST['wpbusdirman_field_label'.$wpbusdirman_field_label_association];
						}
					}
					else
					{

						if(isset($wpbdmlistingid) && !empty($wpbdmlistingid))
						{
							if($wpbusdirman_field_association == 'category')
							{

									$wpbusdirman_field_value=array();
									$wpbusdirman_postvalues=get_the_terms($wpbdmlistingid, $wpbdmposttypecategory);


									if($wpbusdirman_postvalues)
									{
										foreach($wpbusdirman_postvalues as $wpbusdirman_postvalue)
										{
											$wpbusdirman_field_value[]=$wpbusdirman_postvalue->term_id;
										}

									}
							}
							elseif($wpbusdirman_field_association == 'title')
							{
								$wpbusdirman_field_value=get_the_title($wpbdmlistingid);
							}
							elseif($wpbusdirman_field_association == 'description')
							{
								$wpbusdirman_field_value=wpbdm_get_post_data($data='post_content',$wpbdmlistingid);
							}
							elseif($wpbusdirman_field_association == 'excerpt')
							{
								$wpbusdirman_field_value=wpbdm_get_post_data($data='post_excerpt',$wpbdmlistingid);
							}
							elseif($wpbusdirman_field_association == 'tags')
							{

								$wpbusdirman_field_value='';
								$wpbusdirmanfieldtagsobject=get_the_terms($wpbdmlistingid, $wpbdmposttypetags);


								if($wpbusdirmanfieldtagsobject)
								{
									foreach($wpbusdirmanfieldtagsobject as $wpbusdirmanfieldtags)
									{
									   $wpbusdirmantag=$wpbusdirmanfieldtags->slug;
									   $wpbusdirmanfieldtagsarr[]=$wpbusdirmantag;
									}
								}

								if($wpbusdirmanfieldtagsarr)
								{
									$wpbusdirman_last_field_tag=end($wpbusdirmanfieldtagsarr);

									foreach($wpbusdirmanfieldtagsarr as $wpbusdirman_field_tag)
									{
										$wpbusdirman_field_value.="$wpbusdirman_field_tag";
										if($wpbusdirman_last_field_tag != $wpbusdirman_field_tag )
										{
											$wpbusdirman_field_value.=",";
										}
									}
								}

							}
							elseif($wpbusdirman_field_label_association="_meta$wpbusdirman_field_val")
							{
								$wpbusdirman_field_value=get_post_meta($wpbdmlistingid, $wpbusdirman_field_label, $single = true);
							}


						}

					}


					// If this is a text box option
					if($wpbusdirman_field_type == 1)
					{
						echo "<p class=\"wpbdmp\"><label>$wpbusdirman_field_label_name</label><br/>";
						$wpbusdirman_field_validation=get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_field_val);
						if($wpbusdirman_field_validation == 'date'){ _e("Format 01/31/1969","WPBDM");}
						echo "</p>";
						echo "<input type=\"text\" name=\"wpbusdirman_field_label$wpbusdirman_field_label_association\" class=\"intextbox\" value=\"$wpbusdirman_field_value\">";



					}
					// If this is a select list
					elseif($wpbusdirman_field_type == 2)
					{

							if($wpbusdirman_field_association == 'category')
							{
								if(is_array($wpbusdirman_field_value)){$wpbusdirman_field_value_selected=$wpbusdirman_field_value[0];}
								else {$wpbusdirman_field_value_selected=$wpbusdirman_field_value;}

								echo "<p class=\"wpbdmp\"><label>$wpbusdirman_field_label_name</label></p>";
								wp_dropdown_categories('taxonomy='.$wpbdmposttypecategory.'&show_option_none='.$wpbusdirman_selectcattext.'&orderby=name&selected='.$wpbusdirman_field_value_selected.'&order=ASC&hide_empty=0&hierarchical=1');
							}
							else
							{
								echo "<p class=\"wpbdmp\"><label>$wpbusdirman_field_label_name</label></p><select class=\"inselect\" name=\"wpbusdirman_field_label$wpbusdirman_field_label_association\">";

								$wpbusdirman_formselops=explode(",",$wpbusdirman_field_options);
								$wpbusdirman_formselop=array();

								for ($i=0;isset($wpbusdirman_formselops[$i]);++$i) {
									$wpbusdirman_formselop[]=$wpbusdirman_formselops[$i];
								}

								if($wpbusdirman_formselop)
								{
									foreach($wpbusdirman_formselop as $wpbusdirman_formseloption)
									{
										$wpbusdirman_formseloption=trim($wpbusdirman_formseloption);

										if($wpbusdirman_field_value == $wpbusdirman_formseloption)
										{
											$wpbusdirmanselected="selected";
										}
										else
										{
											$wpbusdirmanselected='';
										}
										echo "<option $wpbusdirmanselected value=\"$wpbusdirman_formseloption\" $wpbusdirmanselected>$wpbusdirman_formseloption</option>";
									}
								}

								echo "</select>";
							}
					}

					// If this is a textarea option
					elseif($wpbusdirman_field_type == 3)
					{
						$wpbusdirman_field_value=stripslashes($wpbusdirman_field_value);
						echo "<p class=\"wpbdmp\"><label>$wpbusdirman_field_label_name</label></p><textarea name=\"wpbusdirman_field_label$wpbusdirman_field_label_association\" class=\"intextarea\">$wpbusdirman_field_value</textarea>";

					}

					// If this is a radio option
					elseif($wpbusdirman_field_type == 4)
					{
						echo "<p class=\"wpbdmp\"><label>$wpbusdirman_field_label_name</label></p>";

							$wpbusdirman_formselops=explode(",",$wpbusdirman_field_options);
							$wpbusdirman_formselop=array();

							for ($i=0;isset($wpbusdirman_formselops[$i]);++$i) {
								$wpbusdirman_formselop[]=$wpbusdirman_formselops[$i];
							}

							if($wpbusdirman_formselop)
							{
								foreach($wpbusdirman_formselop as $wpbusdirman_formseloption)
								{
									$wpbusdirman_formseloption=trim($wpbusdirman_formseloption);
									if($wpbusdirman_formseloption == $wpbusdirman_field_value)
									{
										$wpbusdirmanchecked="checked";
									}
									else
									{
										$wpbusdirmanchecked='';
									}
									echo "<span style=\"padding-right:10px;\"><input type=\"radio\" name=\"wpbusdirman_field_label$wpbusdirman_field_label_association\" value=\"$wpbusdirman_formseloption\" $wpbusdirmanchecked />$wpbusdirman_formseloption</span>";
								}
							}

					}

					// If this is a multi-select list
					elseif($wpbusdirman_field_type == 5)
					{

							echo "<p class=\"wpbdmp\"><label>$wpbusdirman_field_label_name</label></p><select class=\"inselectmultiple\" name=\"wpbusdirman_field_label".$wpbusdirman_field_label_association."[]\" MULTIPLE>";

							$wpbusdirman_formselops=explode(",",$wpbusdirman_field_options);
							$wpbusdirman_formselop=array();

							for ($i=0;isset($wpbusdirman_formselops[$i]);++$i) {
								$wpbusdirman_formselop[]=$wpbusdirman_formselops[$i];
							}

							$wpbusdirmanmultivals=explode("\t",$wpbusdirman_field_value);
							$wpbusdirmanmultivalsarr=array();

							for ($a=0;isset($wpbusdirmanmultivals[$a]);++$a) {
								$wpbusdirmanmultivalsarr[]=trim($wpbusdirmanmultivals[$a]);
							}

							if($wpbusdirman_formselop)
							{
								foreach($wpbusdirman_formselop as $wpbusdirman_formseloption)
								{
									$wpbusdirman_formseloption=trim($wpbusdirman_formseloption);
									echo "<option ";
									if(in_array($wpbusdirman_formseloption,$wpbusdirmanmultivalsarr)){echo $wpbusdirmanselectedword;}
									echo "  value=\"$wpbusdirman_formseloption\">$wpbusdirman_formseloption</option>";
								}
							}

							echo "</select>";

					}

					// If this is a checkbox option
					elseif($wpbusdirman_field_type == 6)
					{
							echo "<p class=\"wpbdmp\"><label>$wpbusdirman_field_label_name</label></p>";

							if($wpbusdirman_field_association == 'category')
							{
								//Retrieve all categories
								$mywpbdmcatlist = get_terms($wpbdmposttypecategory, 'hide_empty=0');
								// echo '<pre>';
								// 	print_r($mywpbdmcatlist);
								// 	echo '</pre>';
								if($mywpbdmcatlist)
								{
									echo '<div id="signup-cat-list">';
									
									foreach($mywpbdmcatlist as $wpbusdirman_formseloption)
									{
										$mywpbdmcattermid = $wpbusdirman_formseloption->term_id;
										$mywpbdmcattermname = $wpbusdirman_formseloption->name;

										echo "<div id=\"wpbdmcheckboxclass\"><input type=\"checkbox\" name=\"wpbusdirman_field_label".$wpbusdirman_field_label_association."[]\" value=\"$mywpbdmcattermid\"";

										if($wpbusdirman_field_value == array() && in_array($wpbusdirman_field_value, $mywpbdmcattermid)){echo $wpbusdirmancheckedword;}
										echo "/>$mywpbdmcattermname";
										echo "</div>";
									}
									echo '</div>';
									
								}
							}

							else
							{


								$wpbusdirman_formselops=explode(",",$wpbusdirman_field_options);
								$wpbusdirman_formselop=array();

								for ($i=0;isset($wpbusdirman_formselops[$i]);++$i) {
									$wpbusdirman_formselop[]=$wpbusdirman_formselops[$i];
								}

								$wpbusdirmancboxvals=explode("\t",$wpbusdirman_field_value);
								$wpbusdirmanxboxvalsarr=array();

								for ($a=0;isset($wpbusdirmancboxvals[$a]);++$a) {
									$wpbusdirmanxboxvalsarr[]=trim($wpbusdirmancboxvals[$a]);
								}

								if($wpbusdirman_formselop)
								{
									foreach($wpbusdirman_formselop as $wpbusdirman_formseloption)
									{
										$wpbusdirman_formseloption=trim($wpbusdirman_formseloption);
										echo "<div id=\"wpbdmcheckboxclass\"><input type=\"checkbox\" name=\"wpbusdirman_field_label".$wpbusdirman_field_label_association."[]\" value=\"$wpbusdirman_formseloption\"";
										if(in_array($wpbusdirman_formseloption,$wpbusdirmanxboxvalsarr)){echo $wpbusdirmancheckedword;}
										echo "/>$wpbusdirman_formseloption</div>";
									}
								}

							}

						echo "<div style=\"clear:both;\"></div>";
					}


				}
			}


				echo "<p><input type=\"submit\" class=\"insubmitbutton\" value=\"";
				_e("Submit","WPBDM");
				echo "\" /></p>";
				echo "</form>";
		}
	}
}

function wpbusdirman_uninstall()
{

	global $message;
	$dirname="wpbdm";

	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) )
	{
		if($_REQUEST['action'] == 'wpbusdirman_d_install')
		{
			wpbusdirman_d_install();
		}
	}

	if( !isset($_REQUEST['action']) || empty($_REQUEST['action']) )
	{

		wpbusdirman_admin_head();

		echo "<h3 style=\"padding:10px;\">";
		_e("Uninstall","WPBDM");
		echo "</h3>";
		if(isset($message) && !empty($message))
		{
			echo $message;
		}
		_e("You have arrived at this page by clicking the Uninstall link. If you are certain you wish to uninstall the plugin, please click the link below to proceed. Please note that all your data related to the plugin, your ads, images and everything else created by the plugin will be destroyed","WPBDM");
		echo "<p><b>";
		_e("Important Information","WPBDM");
		echo "</b></p>";
		echo "<blockquote><p>1.";
		_e("If you want to keep your user uploaded images, please download the folder $dirname, which you will find inside your uploads directory, to your local drive for later use or rename the folder to something else so the uninstaller can bypass it","WPBDM");
		echo "</p>";
		echo "</blockquote>:";
		echo "<a href=\"?page=wpbdman_m1&action=wpbusdirman_d_install\">";
		_e("Proceed with Uninstalling WP Business Directory Manager Uninstall","WPBDM");
		echo "</a>";

		wpbusdirman_admin_foot();
	}
}

function wpbusdirman_d_install()
{


	global $wpdb,$wpbusdirman_plugin_path,$table_prefix,$wpbusdirman_plugin_dir,$wpbdmposttypecategory,$wpbusdirmanconfigoptionsprefix,$wpbdmposttype;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbdmdraftortrash=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_47'];


		$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');

		if($wpbusdirman_myterms)
		{
			foreach($wpbusdirman_myterms as $wpbusdirman_myterm)
			{
				$wpbusdirman_postcatitems[]=$wpbusdirman_myterm->term_id;
			}
		}

		if($wpbusdirman_postcatitems)
		{

			foreach($wpbusdirman_postcatitems as $wpbusdirman_postcatitem)
			{
				$wpbusdirman_catcat=get_posts($wpbusdirman_postcatitem);
			}

			if($wpbusdirman_catcat)
			{
				foreach($wpbusdirman_catcat as $wpbusdirman_cat)
				{
					$wpbusdirman_postsposts[]=$wpbusdirman_cat->ID;
				}
			}


				if($wpbusdirman_postsposts)
				{
					foreach($wpbusdirman_postsposts as $wpbusdirman_post)
					{
						// configure array of wpbusdirman post ids to trash
						 $wpbusdirman_unints_postarr = array();
						 $wpbusdirman_unints_postarr['ID'] = $wpbusdirman_post;
						 $wpbusdirman_unints_postarr['post_type'] = $wpbdmposttype;
						 $wpbusdirman_unints_postarr['post_status'] = $wpbdmdraftortrash;

						// Update the post into the database
						wp_update_post( $wpbusdirman_unints_postarr );

					}
				}
		}
	// Remove the plugin entries from the options table
	$wpbusdirman_query="DELETE FROM $wpdb->options WHERE option_name LIKE '%wpbusdirman_%'";
	@mysql_query($wpbusdirman_query);



	// Clear the listing expiration schedule
	wp_clear_scheduled_hook('wpbusdirman_listingexpirations_hook');

		$wpbdm_pluginfile=$wpbusdirman_plugin_dir."/wpbusdirman.php";
		$wpbusdirman_current = get_option('active_plugins');
		array_splice($wpbusdirman_current, array_search( $wpbdm_pluginfile, $wpbusdirman_current), 1 );
		update_option('active_plugins', $wpbusdirman_current);
		do_action('deactivate_' . $wpbdm_pluginfile );
		echo "<div style=\"padding:50px;font-weight:bold;\"><p>";
		_e("Almost done...","WPBDM");
		echo "</p><h1>";
		_e("One More Step","WPBDM");
		echo "</h1><a href=\"plugins.php?plugin=$wpbusdirman_plugin_dir&deactivate=true\">";
		_e("Please click here to complete the uninstallation process","WPBDM");
		echo "</a></h1></div>";
		die;

}


function wpbusdirman_opsconfig_categories()
{


}


function wpbusdirmanui_homescreen ()
{
	global $wpbdmimagesurl,$wpbusdirman_imagesurl,$wpbusdirman_plugin_path,$wpbdmposttypecategory,$wpbusdirmanconfigoptionsprefix,$wpbdmposttype;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_contact_errors=false;

	// If there are no categories setup go no further
 	$args=array('hide_empty' => 0);
 	$wpbusdirman_postcats=get_terms( $wpbdmposttypecategory, $args);


	if(!isset($wpbusdirman_postcats) || empty($wpbusdirman_postcats))
	{
 		if(is_user_logged_in() && current_user_can('install_plugins'))
 		{
 			_e("There are no categories assigned to the business directory yet. You need to assign some categories to the business directory. Only admins can see this message. Regular users are seeing a message that there are currently no listings in the directory. Listings cannot be added until you assign categories to the business directory. ","WPBDM");
 		}
 		else
 		{
			_e("There are currently no listings in the directory","WPBDM");
		}
	}
	else
	{

			// There are categories setup so proceed

			if(isset($_REQUEST['action']) && !empty($_REQUEST['action']))
			{
				$wpbusdirmanaction=$_REQUEST['action'];
			}
			elseif(isset($_REQUEST['do']) && !empty ($_REQUEST['do']))
			{
				$wpbusdirmanaction=$_REQUEST['do'];
			}
			else
			{
				$wpbusdirmanaction='';
			}

			if($wpbusdirmanaction == 'submitlisting')
			{
				wpbusdirman_displaypostform($makeactive='1',$wpbusdirmanerrors='',$neworedit='new',$wpbdmlistingid='');
			}
			elseif($wpbusdirmanaction == 'viewlistings')
			{
				wpbusdirman_viewlistings();
			}
			elseif($wpbusdirmanaction == 'renewlisting')
			{
				$wpbdmgpid=wpbusdirman_gpid();
				$wpbusdirman_permalink=get_permalink($wpbdmgpid);
				$neworedit="renew";
				if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
				{
					$wpbdmidtorenew=$_REQUEST['id'];
					wpbusdirman_renew_listing($wpbdmidtorenew,$wpbusdirman_permalink,$neworedit);
				}
			}
			elseif($wpbusdirmanaction == 'renewlisting_step_2')
			{
				if(isset($_REQUEST['wpbusdirmanlistingpostid']) && !empty($_REQUEST['wpbusdirmanlistingpostid']))
				{
					$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];
				}
				if(isset($_REQUEST['whichfeeoption']) && !empty($_REQUEST['whichfeeoption']))
				{
					$wpbusdirmanfeeoption=$_REQUEST['whichfeeoption'];
				}
				if(isset($_REQUEST['wpbusdirmanpermalink']) && !empty($_REQUEST['wpbusdirmanpermalink']))
				{
					$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
				}
				if(isset($_REQUEST['neworedit']) && !empty($_REQUEST['neworedit']))
				{
					$neworedit=$_REQUEST['neworedit'];
				}

				$wpbusdirmannumimgsallowed=get_option('wpbusdirman_settings_fees_images_'.$wpbusdirmanfeeoption);
				$wpbusdirmannumimgsleft=$wpbusdirmannumimgsallowed;
				$wpbusdirmanlistingtermlength=get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirmanfeeoption);

				$wpbusdirman_my_renew_post = array();
				$wpbusdirman_my_renew_post['ID'] = $wpbusdirmanlistingpostid;
				$wpbusdirman_my_renew_post['post_status'] = 'pending';

				// Update the post into the database
				wp_update_post( $wpbusdirman_my_renew_post );

				wpbusdirman_load_payment_page($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption);
			}
			elseif($wpbusdirmanaction == 'post')
			{
				wpbusdirman_do_post();
			}
			elseif($wpbusdirmanaction == 'editlisting')
			{
				if(isset($_REQUEST['wpbusdirmanlistingid']) && !empty($_REQUEST['wpbusdirmanlistingid']))
				{
					$wpbdmlistingid=$_REQUEST['wpbusdirmanlistingid'];
				}
				wpbusdirman_displaypostform($makeactive='',$errors='',$neworedit='edit',$wpbdmlistingid);
			}
			elseif($wpbusdirmanaction == 'deletelisting')
			{

				$wpbusdirman_config_options=get_wpbusdirman_config_options();
				$wpbdmdraftortrash=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_47'];

				if(isset($_REQUEST['wpbusdirmanlistingid']) && !empty($_REQUEST['wpbusdirmanlistingid']))
				{
					$wpbdmlistingid=$_REQUEST['wpbusdirmanlistingid'];
				}

				 if(isset($wpbdmlistingid) && !empty($wpbdmlistingid))
				 {
					 $wpbusdirman_del_postarr = array();
					 $wpbusdirman_del_postarr['ID'] = $wpbdmlistingid;
					 $wpbusdirman_del_postarr['post_type'] = $wpbdmposttype;
					 $wpbusdirman_del_postarr['post_status'] = $wpbdmdraftortrash;

					// Update the post into the database
					wp_update_post( $wpbusdirman_del_postarr );
					_e("The listing has been deleted.","WPBDM");
					wpbusdirman_managelistings();
				}
				else
				{
					_e("The system could not determine which listing you want to delete so nothing has been deleted.","WPBDM");
					wpbusdirman_managelistings();
				}

			}
			elseif($wpbusdirmanaction == 'upgradetostickylisting')
			{
				if(isset($_REQUEST['wpbusdirmanlistingid']) && !empty($_REQUEST['wpbusdirmanlistingid']))
				{
					$wpbdmlistingid=$_REQUEST['wpbusdirmanlistingid'];
				}

				wpbusdirman_upgradetosticky($wpbdmlistingid);
			}
			elseif($wpbusdirmanaction == 'sendcontactmessage')
			{
				$commentauthormessage='';
				$commentauthorname='';
				$commentauthoremail='';
				$commentauthorwebsite='';

				if(isset($_REQUEST['wpbusdirmanlistingpostid']) && !empty($_REQUEST['wpbusdirmanlistingpostid']))
				{
					$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];
				}
				if(isset($_REQUEST['wpbusdirmanpermalink']) && !empty($_REQUEST['wpbusdirmanpermalink']))
				{
					$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
				}
				if(isset($_REQUEST['commentauthormessage']) && !empty($_REQUEST['commentauthormessage']))
				{
					$commentauthormessage=$_REQUEST['commentauthormessage'];
				}


				global $post, $current_user, $user_identity;

				global $wpbusdirman_contact_form_values, $wpbusdirman_contact_form_errors;

				$wpbusdirman_contact_form_errors = '';

				if(is_user_logged_in())
				{

					$commentauthorname=$user_identity;
					$commentauthoremail=$current_user->data->user_email;
					$commentauthorwebsite=$current_user->data->user_url;

				}
				else
				{
					if(isset($_REQUEST['commentauthorname']) && !empty($_REQUEST['commentauthorname']))
					{
						$commentauthorname=htmlspecialchars( $_REQUEST['commentauthorname'] );
					}
					if(isset($_REQUEST['commentauthoremail']) && !empty($_REQUEST['commentauthoremail']))
					{
						$commentauthoremail=$_REQUEST['commentauthoremail'];
					}
					if(isset($_REQUEST['commentauthorwebsite']) && !empty($_REQUEST['commentauthorwebsite']))
					{
						$commentauthorwebsite=$_REQUEST['commentauthorwebsite'];
					}

				}
					if ( !isset($commentauthorname) || empty($commentauthorname) )
					{
						$wpbusdirman_contact_errors=true;
						$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
						$wpbusdirman_contact_form_errors.=__("Please enter your name.","WPBDM");
						$wpbusdirman_contact_form_errors.="</li>";

					}
					if(strlen($commentauthorname) < 3)
					{
						$wpbusdirman_contact_errors=true;
						$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
						$wpbusdirman_contact_form_errors.=__("Name needs to be at least 3 characters in length to be considered valid.","WPBDM");
						$wpbusdirman_contact_form_errors.="</li>";
					}
					if ( !isset($commentauthoremail) || empty($commentauthoremail) )
					{
						$wpbusdirman_contact_errors=true;
						$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
						$wpbusdirman_contact_form_errors.=__("Please enter your email.","WPBDM");
						$wpbusdirman_contact_form_errors.="</li>";
					}
					if ( !wpbusdirman_isValidEmailAddress($commentauthoremail) )
					{
						$wpbusdirman_contact_errors=true;
						$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
						$wpbusdirman_contact_form_errors.=__("Please enter a valid email.","WPBDM");
						$wpbusdirman_contact_form_errors.="</li>";
					}
					if( isset($commentauthorwebsite) && !empty($commentauthorwebsite) && !(wpbusdirman_isValidURL($commentauthorwebsite)) )
					{
						$wpbusdirman_contact_errors=true;
						$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
						$wpbusdirman_contact_form_errors.=__("Please enter a valid URL.","WPBDM");
						$wpbusdirman_contact_form_errors.="</li>";
					}

					$commentauthormessage = stripslashes($commentauthormessage);
					$commentauthormessage = trim(wp_kses( $commentauthormessage, array() ));
					if ( !isset($commentauthormessage ) || empty($commentauthormessage))
					{
						$wpbusdirman_contact_errors=true;
						$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
						$wpbusdirman_contact_form_errors.=__("You did not enter a message.","WPBDM");
						$wpbusdirman_contact_form_errors.="</li>";
					}

					if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_30'] == "yes")
					{

						$privatekey = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_29'];

						if(isset($privatekey) && !empty($privatekey))
						{
							require_once('recaptcha/recaptchalib.php');
							$resp = recaptcha_check_answer ($privatekey,
							$_SERVER["REMOTE_ADDR"],
							$_POST["recaptcha_challenge_field"],
							$_POST["recaptcha_response_field"]);

							if (!$resp->is_valid)
							{
											$wpbusdirman_contact_errors=true;
											$wpbusdirman_contact_form_errors.="<li class=\"wpbusdirmanerroralert\">";
											$wpbusdirman_contact_form_errors.=__("The reCAPTCHA wasn't entered correctly: ","WPBDM");
											$wpbusdirman_contact_form_errors.=" . $resp->error . ";
											$wpbusdirman_contact_form_errors.="</li>";
							}
						}
					}

				if($wpbusdirman_contact_errors)
				{
					wpbusdirman_contactform($wpbusdirmanpermalink,$wpbusdirmanlistingpostid,$commentauthorname,$commentauthoremail,$commentauthorwebsite,$commentauthormessage,$wpbusdirman_contact_form_errors);
				}
				else
				{

					$post_author = get_userdata( $post->post_author );

					$headers =	"MIME-Version: 1.0\n" .
							"From: $commentauthorname <$commentauthoremail>\n" .
							"Reply-To: $commentauthoremail\n" .
							"Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";

					$subject = "[" . get_option( 'blogname' ) . "] " . wp_kses( get_the_title($wpbusdirmanlistingpostid), array() );

					$wpbdmsendtoemail=wpbusdirman_get_the_business_email($wpbusdirmanlistingpostid);


					if(!isset($wpbdmsendtoemail) || empty($wpbdmsendtoemail))
					{
						$wpbdmsendtoemail=$post_author->user_email;
					}


					$time = date_i18n( __('l F j, Y \a\t g:i a'), current_time( 'timestamp' ) );

					$message = "Name: $commentauthorname
					Email: $commentauthoremail
					Website: $commentauthorwebsite

					$commentauthormessage

					Time: $time

					";
					if(wp_mail( $wpbdmsendtoemail, $subject, $message, $headers ))
					{
						_e("Your message has been sent","WPBDM");
					}
					else
					{
						_e("There was a problem encountered. Your message has not been sent","WPBDM");
					}
				}

			}
			elseif($wpbusdirmanaction == 'deleteimage')
			{
				if(isset($_REQUEST['wpbusdirmanlistingpostid']) && !empty($_REQUEST['wpbusdirmanlistingpostid']))
				{
					$wpbdmlistingid=$_REQUEST['wpbusdirmanlistingpostid'];
				}
				else
				{
					$wpbdmlistingid='';
				}
				if(isset($_REQUEST['wpbusdirmanimagetodelete']) && !empty($_REQUEST['wpbusdirmanimagetodelete']))
				{
					$wpbdmimagetodelete=$_REQUEST['wpbusdirmanimagetodelete'];
				}
				else
				{
					$wpbdmimagetodelete='';
				}
				if(isset($_REQUEST['wpbusdirmannumimgsallowed']) && !empty($_REQUEST['wpbusdirmannumimgsallowed']))
				{
					$wpbusdirmannumimgsallowed=$_REQUEST['wpbusdirmannumimgsallowed'];
				}
				else
				{
					$wpbusdirmannumimgsallowed='';
				}
				if(isset($_REQUEST['wpbusdirmannumimgsleft']) && !empty($_REQUEST['wpbusdirmannumimgsleft']))
				{
					$wpbusdirmannumimgsleft=$_REQUEST['wpbusdirmannumimgsleft'];
				}
				else
				{
					$wpbusdirmannumimgsleft='';
				}
				if(isset($_REQUEST['wpbusdirmanlistingtermlength']) && !empty($_REQUEST['wpbusdirmanlistingtermlength']))
				{
					$wpbusdirmanlistingtermlength=$_REQUEST['wpbusdirmanlistingtermlength'];
				}
				else
				{
					$wpbusdirmanlistingtermlength='';
				}
				if(isset($_REQUEST['wpbusdirmanpermalink']) && !empty($_REQUEST['wpbusdirmanpermalink']))
				{
					$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
				}
				else
				{
					$wpbusdirmanpermalink='';
				}
				if(isset($_REQUEST['neworedit']) && !empty($_REQUEST['neworedit']))
				{
					$neworedit=$_REQUEST['neworedit'];
				}
				else
				{
					$neworedit='';
				}

				wpbusdirman_deleteimage($imagetodelete=$wpbdmimagetodelete,$wpbdmlistingid,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanpermalink,$neworedit);
			}
			elseif($wpbusdirmanaction == 'payment_step_1')
			{

				if(isset($_REQUEST['wpbusdirmanlistingpostid']) && !empty($_REQUEST['wpbusdirmanlistingpostid']))
				{
					$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];
				}
				if(isset($_REQUEST['whichfeeoption']) && !empty($_REQUEST['whichfeeoption']))
				{
					$wpbusdirmanfeeoption=$_REQUEST['whichfeeoption'];
				}
				if(isset($_REQUEST['wpbusdirmanpermalink']) && !empty($_REQUEST['wpbusdirmanpermalink']))
				{
					$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
				}
				if(isset($_REQUEST['neworedit']) && !empty($_REQUEST['neworedit']))
				{
					$neworedit=$_REQUEST['neworedit'];
				}

				$wpbusdirmannumimgsallowed=get_option('wpbusdirman_settings_fees_images_'.$wpbusdirmanfeeoption);
				$wpbusdirmannumimgsleft=$wpbusdirmannumimgsallowed;
				$wpbusdirmanlistingtermlength=get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirmanfeeoption);

				wpbusdirman_image_upload_form($wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror='',$neworedit,$wpbusdirmanfeeoption);

			}
			elseif($wpbusdirmanaction == 'payment_step_2')
			{

				if(isset($_REQUEST['wpbusdirmanlistingpostid']) && !empty($_REQUEST['wpbusdirmanlistingpostid']))
				{
					$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];
				}
				if(isset($_REQUEST['wpbusdirmanfeeoption']) && !empty($_REQUEST['wpbusdirmanfeeoption']))
				{
					$wpbusdirmanfeeoption=$_REQUEST['wpbusdirmanfeeoption'];
				}
				if(isset($_REQUEST['wpbusdirmanpermalink']) && !empty($_REQUEST['wpbusdirmanpermalink']))
				{
					$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];
				}
				if(isset($_REQUEST['neworedit']) && !empty($_REQUEST['neworedit']))
				{
					$neworedit=$_REQUEST['neworedit'];
				}

				$wpbusdirmannumimgsallowed=get_option('wpbusdirman_settings_fees_images_'.$wpbusdirmanfeeoption);
				$wpbusdirmannumimgsleft=$wpbusdirmannumimgsallowed;
				$wpbusdirmanlistingtermlength=get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirmanfeeoption);

				wpbusdirman_load_payment_page($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption);
			}
			elseif($wpbusdirmanaction == 'wpbusdirmanuploadfile')
			{
				wpbusdirman_doupload();
			}
			else
			{
				global $wpbusdirman_gpid,$permalinkstructure;
				$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
				if(!isset($permalinkstructure) || empty($permalinkstructure)){ $querysymbol="&amp";} else { $querysymbol="?";}


				if(file_exists(TEMPLATEPATH . '/single/wpbusdirman-index-categories.php')){
				include TEMPLATEPATH . '/single/wpbusdirman-index-categories.php';
				} elseif(file_exists(STYLESHEETPATH . '/single/wpbusdirman-index-categories.php')){
				include STYLESHEETPATH . '/single/wpbusdirman-index-categories.php';
				} elseif(file_exists(WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-categories.php')) {
				include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-categories.php';
				}else {include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-categories.php';}

				if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_44'] == "yes"){

				if(file_exists(TEMPLATEPATH . '/single/wpbusdirman-index-listings.php')){
				include TEMPLATEPATH . '/single/wpbusdirman-index-listings.php';
				} elseif(file_exists(STYLESHEETPATH . '/single/wpbusdirman-index-listings.php')){
				include STYLESHEETPATH . '/single/wpbusdirman-index-listings.php';
				} elseif(file_exists(WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php')) {
				include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php';
				}else {include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php';	}
			}
		}
	}
}

// Retrieve the business's email addres for the contact form
function wpbusdirman_get_the_business_email($wpbusdirmanlistingpostid)
{

	$wpbdm_the_email='';
	wp_reset_query();
	$mypost=get_post($wpbusdirmanlistingpostid);
	$thepostid=$mypost->ID;
	$wpbdm_the_emailsarr=array();

	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');

	if($wpbusdirman_field_vals)
	{
		foreach($wpbusdirman_field_vals as $wpbusdirman_field_val):


			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);


			if($wpbusdirman_field_association == 'meta')
			{
				$wpbdm_meta_fields[]=$wpbusdirman_field_label;
			}

		endforeach;


		foreach($wpbdm_meta_fields as $wpbdm_meta_field)
		{

			$wpbdm_field_value=get_post_meta($thepostid, $wpbdm_meta_field, true);

				if(isset($wpbdm_field_value) && !empty($wpbdm_field_value) && (wpbusdirman_isValidEmailAddress($wpbdm_field_value)))
				{
					$wpbdm_the_emailsarr[]=$wpbdm_field_value;
				}

		}

	}

	$wpbdm_the_email=$wpbdm_the_emailsarr[0];
	return $wpbdm_the_email;
}

function wpbusdirman_the_image($wpbusdirman_pID,$size = 'medium' , $class = '')
{

	//setup the attachment array
	$att_array = array(
	'post_parent' => $wpbusdirman_pID,
	'post_type' => 'attachment',
	'post_mime_type' => 'image',
	'order_by' => 'menu_order'
	);

	//get the post attachments
	$attachments = get_children($att_array);

	//make sure there are attachments
	if (is_array($attachments))
	{
		//loop through them
		foreach($attachments as $att)
		{
			//find the one we want based on its characteristics
			if ( $att->menu_order == 0)
			{
				$image_src_array = wp_get_attachment_image_src($att->ID, $size);

				//get url - 1 and 2 are the x and y dimensions
				$url = $image_src_array[0];
				$caption = $att->post_excerpt;
				$image_html = '%s';

				//combine the data
				$wpbusdirman_img_html = sprintf($image_html,$url,$caption,$class);

				$wpbusdirman_image_url=$url;

			}

			return $wpbusdirman_image_url;
		}
	}
}

function wpbusdirman_do_post()
{
	global $wpbusdirman_gpid,$wpbdmposttype,$wpbdmposttypecategory,$wpbdmposttypetags,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');
	if(isset($_REQUEST['formmode']) && ($_REQUEST['formmode'] == -1)){ $makeactive=$_REQUEST['formmode'];}else { $makeactive='';}
	if(isset($_REQUEST['neworedit']) && !empty($_REQUEST['neworedit'])){ $neworedit=$_REQUEST['neworedit'];}else { $neworedit='';}
	if(isset($_REQUEST['wpbdmlistingid']) && !empty($_REQUEST['wpbdmlistingid'])){ $wpbdmlistingid=$_REQUEST['wpbdmlistingid'];}else { $wpbdmlistingid='';}


	if($makeactive == -1)
	{
		echo "<h3 style=\"padding:10px;\">";
		_e("Information Not Saved","WPBDM");
		echo "</h3><p>";
		_e("You are trying to submit the form in preview mode. You cannot save while in preview mode","WPBDM");
		echo " <a href=\"javascript:history.go(-1)\">";
		_e("Go Back","WPBDM");
		echo "</a></p>";
	}
	else
	{

		if (!(is_user_logged_in()) )
		{
			//require(ABSPATH . WPINC.'/registration.php');

			//Check if the email already exists and if it exists get the user's ID by email

			if($wpbusdirman_field_vals)
			{
				foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
				{
					$wpbusdirman_validation_op=get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_field_val);
					if($wpbusdirman_validation_op == 'email')
					{
						$wpbusdirman_email_numval=$wpbusdirman_field_val;
					}
					$wpbusdirman_association_op=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
					if($wpbusdirman_association_op == 'title')
					{
						$wpbusdirman_title_numval=$wpbusdirman_field_val;
					}
				}
			}

			$wpbusdirman_email_field=$_REQUEST['wpbusdirman_field_label_meta'.$wpbusdirman_email_numval];

			$guestrand=$wpbusdirman_user_pass=wpbusdirman_generatePassword(5,2);

			$wpbusdirman_display_name='Guest';
			$wpbusdirman_display_name.=" $guestrand";
			$wpbusdirman_user_login='guest_';
			$wpbusdirman_user_login.=" $guestrand";


			if(email_exists($wpbusdirman_email_field))
			{
				$wpbusdirman_UID_get=get_user_by_email($wpbusdirman_email_field);
				$wpbusdirman_UID=$wpbusdirman_UID_get->ID;
			}
			else
			{
				$wpbusdirman_user_pass=wpbusdirman_generatePassword(7,2);
				$wpbusdirman_UID=wp_insert_user(array('display_name'=>$wpbusdirman_display_name,'user_login'=>$wpbusdirman_user_login,'user_email'=>$wpbusdirman_email_field,'user_pass'=>$wpbusdirman_user_pass));
			}


		}
		elseif(is_user_logged_in())
		{
			global $current_user;
			get_currentuserinfo();

			$wpbusdirman_UID=$current_user->ID;
		}

		if(!isset($wpbusdirman_UID) || empty($wpbusdirman_UID))
		{
			$wpbusdirman_UID=1;
		}

		$wpbusdirmanposterrors=wpbusdirman_validate_data();

		if($wpbusdirmanposterrors)
		{
			wpbusdirman_displaypostform($makeactive,$wpbusdirmanposterrors,$neworedit,$wpbdmlistingid);
		}

		else
		{
			$post_title=wpbusdirman_filterinput($_REQUEST['wpbusdirman_field_label_title']);
			$post_excerpt=wpbusdirman_filterinput($_REQUEST['wpbusdirman_field_label_excerpt']);
			$post_content=wpbusdirman_filterinput($_REQUEST['wpbusdirman_field_label_description']);
			$post_tags=wpbusdirman_filterinput($_REQUEST['wpbusdirman_field_label_tags']);

			global $wpbusdirman_gpid,$permalinkstructure;
			$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);
			if(!isset($permalinkstructure) || empty($permalinkstructure)){ $querysymbol="&amp";} else { $querysymbol="?";}


		// Determine whether the category field is a select list or checkbox


		if(isset($_REQUEST['cat']) && !empty($_REQUEST['cat'])){
			$post_category_item= $_REQUEST['cat'];
			$inpost_category=array("$post_category_item");
		}elseif(isset($_REQUEST['wpbusdirman_field_label_category']) && !empty($_REQUEST['wpbusdirman_field_label_category']))
		{
			$inpost_category=$_REQUEST['wpbusdirman_field_label_category'];
		}

		if(isset($neworedit) && ($neworedit == 'edit'))
		{
			$post_status=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_19'];
			if($post_status == 'pending2'){$post_status="pending";}
			elseif($post_status == 'publish2'){$post_status="publish";}
		}
		else
		{
			$post_status=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'];
		}

		if(!isset($post_status) || empty($post_status))
		{
			$post_status='pending';
		}


		if ( empty($inpost_category) || 0 == count($inpost_category) || !is_array($inpost_category) ) {

			$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');

			if($wpbusdirman_myterms)
			{
				foreach($wpbusdirman_myterms as $wpbusdirman_myterm)
				{
					$wpbusdirman_postcatitems[]=$wpbusdirman_myterm->term_id;
				}
			}

				$post_category=$wpbusdirman_postcatitems[0];
		}
		else
		{
			$post_category = $inpost_category;
		}

		$post_tag=explode(",",$post_tags);
		$tags_input=array();

		for ($i=0;isset($post_tag[$i]);++$i)
		{
			$tags_input[]=$post_tag[$i];
		}


			$wpbusdirman_postID = wp_insert_post( array(
			'post_author'	=> $wpbusdirman_UID,
			'post_title'	=> $post_title,
			'post_content'	=> $post_content,
			'post_excerpt'	=> $post_excerpt,
			'post_status' 	=> $post_status,
			'post_type' 	=> $wpbdmposttype,
			'ID'	=> $wpbdmlistingid
		));

		wp_set_post_terms( $wpbusdirman_postID , $tags_input, $wpbdmposttypetags, $append );
		wp_set_post_terms( $wpbusdirman_postID , $post_category, $wpbdmposttypecategory, $append );

		// Handle the meta content
		$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');

		if($wpbusdirman_field_vals)
		{
			foreach($wpbusdirman_field_vals as $wpbusdirman_field_val)
			{
				$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
				$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);
				$wpbusdirman_field_type=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_val);

				if($wpbusdirman_field_association == 'meta')
				{
					$wpbusdirman_fieldmeta_set="wpbusdirman_field_label_meta$wpbusdirman_field_val";

					if($wpbusdirman_field_type == 6)
					{

						$wpbusdirman_the_fieldmeta=$_REQUEST[$wpbusdirman_fieldmeta_set];
						$wpbusdirmanfieldmeta='';
						if($wpbusdirman_the_fieldmeta)
						{
							foreach($wpbusdirman_the_fieldmeta as $wpbusdirman_thefieldmeta)
							{
								$wpbusdirmanfieldmeta.="$wpbusdirman_thefieldmeta\t";
							}
						}
					}
					elseif($wpbusdirman_field_type == 5)
					{

						$wpbusdirman_the_fieldmeta=$_REQUEST[$wpbusdirman_fieldmeta_set];
						$wpbusdirmanfieldmeta='';

						if (count($wpbusdirman_the_fieldmeta) > 0)
						{
							// loop through the array
							for ($i=0;$i<count($wpbusdirman_the_fieldmeta);$i++)
							{
								$wpbusdirmanfieldmeta.="$wpbusdirman_the_fieldmeta[$i]\t";
							}
						}
					}
					else
					{
						$wpbusdirmanfieldmeta=$_REQUEST[$wpbusdirman_fieldmeta_set];
					}

						add_post_meta($wpbusdirman_postID, $wpbusdirman_field_label, $wpbusdirmanfieldmeta, true) or update_post_meta($wpbusdirman_postID, $wpbusdirman_field_label, $wpbusdirmanfieldmeta);

							if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "no")
							{
								$wpbusdirmanlengthofterm=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_18'];
								add_post_meta($wpbusdirman_postID, "lengthofterm", $wpbusdirmanlengthofterm, true) or update_post_meta($wpbusdirman_postID, "lengthofterm", $wpbusdirmanlengthofterm);
							}
				}

			}
		}

			global $wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_hasgooglecheckoutmodule;

			if(!($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "no"))
			{
				// Payments are turned on so proceed in payments mode

				if(( $wpbusdirman_haspaypalmodule == 1) || ($wpbusdirman_hastwocheckoutmodule == 1) || ($wpbusdirman_hasgooglecheckoutmodule == 1))
				{
					// At least one payment module exists so continue in payments mode

					if(!($neworedit == 'edit'))
					{
						// This is a new submission so proceed accordingly

						echo "<h3>";
						_e("Step 2","WPBDM");
						echo "</h3>";

						$wpbusdirman_fee_to_pay_li=wpbusdirman_feepay_configure($post_category_item);

							if(isset($wpbusdirman_fee_to_pay_li) && !empty($wpbusdirman_fee_to_pay_li))
							{
								global $wpbusdirman_gpid,$permalinkstructure;
								$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);

								$wpbusdirman_fee_to_pay="<table cellpadding=\"0\" cellspacing=\"0\" id=\"wpbusdirmanpaymentoptionslist\">";
								$wpbusdirman_fee_to_pay.=$wpbusdirman_fee_to_pay_li;
								$wpbusdirman_fee_to_pay.="</table>";
								$neworedit='new';

								echo "<label>";
								_e("Select Listing Payment Option","WPBDM");
								echo "</label><br/>";
								echo "<p>";
								echo "<form method=\"post\" action=\"$wpbusdirman_permalink\">";
								echo "<input type=\"hidden\" name=\"action\" value=\"payment_step_1\"/>";
								echo "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirman_postID\"/>";
								echo "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirman_permalink\"/>";
								echo "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\"/>";
								echo $wpbusdirman_fee_to_pay;
								echo "<br/><input type=\"submit\" class=\"insubmitbutton\" value=\"";
								_e("Next","WPBDM");
								echo "\"/>";
								echo "</form>";
								echo "</p>";
							}
							else
							{
								if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
								{


									$wpbusdirmanlistingtermlength=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_18'];
									$wpbusdirmannumimgsallowed=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_2'];
									if(get_post_meta($wpbusdirman_postID, "image", $single = true))
									{
										$wpbusdirmanimagesinpost[]=get_post_meta($wpbusdirman_postID, "image", $single = false);
										$wpbusdirmantotalimagesuploaded=count($wpbusdirmanimagesinpost);
									}
									else
									{
										$wpbusdirmantotalimagesuploaded=0;
									}

									$wpbusdirmannumimgsleft=($wpbusdirmannumimgsallowed - $wpbusdirmantotalimagesuploaded);
									wpbusdirman_image_upload_form($wpbusdirman_postID,$wpbusdirman_permalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror='',$neworedit,$whichfeeoption='');
								}
								else
								{
									echo "<h3 style=\"padding:10px;\">";
									_e("Submission received","WPBDM");
									echo "</h3><p>";
									_e("Your submission has been received.","WPBDM");
									echo "</p>";
								}
							}

					}
					else
					{
						// We are editing so payment step must be skipped. Go to image management instead

						if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
						{
							// Images are allowed so proceed with image management
							echo "<h3>";
							_e("Step 2","WPBDM");
							echo "</h3>";


							$wpbusdirmanlistingtermlength=get_post_meta($wpbusdirman_postID, "termlength", $single=true);
							$wpbusdirmannumimgsallowed=get_post_meta($wpbusdirman_postID, "totalallowedimages", $single = true);

							$wpbusdirmanpostimages=get_post_meta($wpbusdirman_postID, "thumbnail", $single=false);
							$wpbusdirmantotalimagesuploaded=count($wpbusdirmanpostimages);

							$wpbusdirmannumimgsleft=($wpbusdirmannumimgsallowed - $wpbusdirmantotalimagesuploaded);
							wpbusdirman_image_upload_form($wpbusdirman_postID,$wpbusdirman_permalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror='',$neworedit,$whichfeeoption='');
						}
						else
						{
							// Images are not allowed so complete the process. No further steps exist.
							echo "<h3 style=\"padding:10px;\">";
							_e("Submission received","WPBDM");
							echo "</h3><p>";
							_e("Your submission has been received.","WPBDM");
							echo "</p>";
						}
					}

				}
				else
				{
					// No payment modules exist so switch to no payments mode

					if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
					{
						echo "<h3>";
						_e("Step 2","WPBDM");
						echo "</h3>";

						$wpbusdirmanlistingtermlength=get_post_meta($wpbusdirman_postID, "termlength", $single=true);
						$wpbusdirmannumimgsallowed=get_post_meta($wpbusdirman_postID, "totalallowedimages", $single = true);

							if(!isset($wpbusdirmannumimgsallowed) || empty ($wpbusdirmannumimgsallowed))
							{
								$wpbusdirmannumimgsallowed=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_2'];
;
							}

						$wpbusdirmanpostimages=get_post_meta($wpbusdirman_postID, "thumbnail", $single=false);
						$wpbusdirmantotalimagesuploaded=count($wpbusdirmanpostimages);

						$wpbusdirmannumimgsleft=($wpbusdirmannumimgsallowed - $wpbusdirmantotalimagesuploaded);
						wpbusdirman_image_upload_form($wpbusdirman_postID,$wpbusdirman_permalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror,$neworedit,$whichfeeoption);
					}
					else
					{
						echo "<h3 style=\"padding:10px;\">";
						_e("Submission received","WPBDM");
						echo "</h3><p>";
						_e("Your submission has been received.","WPBDM");
						echo "</p>";
					}
				}

			}
			else
			{
				// Payments are turned off so proceed in no payments mode

					if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
					{

						echo "<h3>";
						_e("Step 2","WPBDM");
						echo "</h3>";

						// If this is an edit

						if(isset($neworedit) && !empty($neworedit) && ($neworedit == 'edit'))
						{

							$wpbusdirmanlistingtermlength=get_post_meta($wpbusdirman_postID, "termlength", $single=true);
							if(!isset($wpbusdirmanlistingtermlength) || empty($wpbusdirmanlistingtermlength))
							{
								$wpbusdirmanlistingtermlength=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_18'];
							}

							$wpbusdirmannumimgsallowed=get_post_meta($wpbusdirman_postID, "totalallowedimages", $single = true);

							if(!isset($wpbusdirmannumimgsallowed) || empty ($wpbusdirmannumimgsallowed))
							{
								$wpbusdirmannumimgsallowed=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_2'];
;
							}

							if(get_post_meta($wpbusdirman_postID, "thumbnail", $single = false))
							{
								$wpbusdirmanimagesinpost[]=get_post_meta($wpbusdirman_postID, "image", $single = false);
								$wpbusdirmantotalimagesuploaded=count($wpbusdirmanimagesinpost);
							}
							else
							{
								$wpbusdirmantotalimagesuploaded=0;
							}

							$wpbusdirmannumimgsleft=($wpbusdirmannumimgsallowed - $wpbusdirmantotalimagesuploaded);

							wpbusdirman_image_upload_form($wpbusdirman_postID,$wpbusdirman_permalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror='',$neworedit,$whichfeeoption='');
						}
						else
						{
							$wpbusdirmanlistingtermlength=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_18'];
							$wpbusdirmannumimgsallowed=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_2'];
;
							if(get_post_meta($wpbusdirman_postID, "image", $single = false))
							{
								$wpbusdirmanimagesinpost[]=get_post_meta($wpbusdirman_postID, "image", $single = false);
								$wpbusdirmantotalimagesuploaded=count($wpbusdirmanimagesinpost);
							}
							else
							{
								$wpbusdirmantotalimagesuploaded=0;
							}
							$wpbusdirmannumimgsleft=($wpbusdirmannumimgsallowed - $wpbusdirmantotalimagesuploaded);
							wpbusdirman_image_upload_form($wpbusdirman_postID,$wpbusdirman_permalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror='',$neworedit,$whichfeeoption='');
						}
					}
					else
					{
						echo "<h3 style=\"padding:10px;\">";
						_e("Submission received","WPBDM");
						echo "</h3><p>";
						_e("Your submission has been received.","WPBDM");
						echo "</p>";
					}
			}



		}
	}
}

function wpbusdirman_image_upload_form($wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror,$neworedit,$whichfeeoption)
{
	global $wpbdmimagesurl,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

		if(isset($wpbusdirmanuerror) && !empty($wpbusdirmanuerror))
		{
			echo "<p>";
			foreach($wpbusdirmanuerror as $wpbusdirmanuerror)
			{
				echo $wpbusdirmanuerror;
			}
			echo "</p>";
		}

			if(isset($wpbusdirmanuerror) && !empty($wpbusdirmanuerror)){ echo "<p class=\"wpbusdirmaerroralert\">$wpbusdirmanuerror</p>"; }

			$wpbusdirmanimagesinpost=get_post_meta($wpbusdirmanlistingpostid, "image", $single = false);
			$wpbusdirmanimagesinposttotal=count($wpbusdirmanimagesinpost);


					if(!isset($wpbusdirmannumimgsallowed) || empty ($wpbusdirmannumimgsallowed))
					{
						$wpbusdirmannumimgsallowed=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_2'];
					}
					if(!isset($wpbusdirmannumimgsleft) || empty ($wpbusdirmannumimgsleft))
					{
						$wpbusdirmannumimgsleft=($wpbusdirmannumimgsallowed - $wpbusdirmanimagesinposttotal);
					}

			if( ($wpbusdirmanimagesinposttotal > 0) && ( $wpbusdirmannumimgsleft <= 0) )
			{
				echo "<p>";
				_e("It appears you do not have the ability to upload additional images at this time.","WPBDM");
				echo "</p>";
						if(get_post_meta($wpbusdirmanlistingpostid, "image", $single = true))
						{
							echo "<p>";
							_e("You can manage your current images below","WPBDM");
							echo "</p>";
							if($wpbusdirmanimagesinpost)
							{
								foreach($wpbusdirmanimagesinpost as $wpbusdirmanimage)
								{
									echo "<div style=\"float:left;margin-right:10px;margin-bottom:10px;\"><img src=\"$wpbdmimagesurl/thumbnails/$wpbusdirmanimage\" border=\"0\" height=\"100\" alt=\"$wpbusdirmanimage\"><br/>";
									echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
									echo "<input type=\"hidden\" name=\"action\" value=\"deleteimage\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmanimagetodelete\" value=\"$wpbusdirmanimage\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmannumimgsallowed\" value=\"$wpbusdirmannumimgsallowed\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmannumimgsleft\" value=\"$wpbusdirmannumimgsleft\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmanlistingtermlength\" value=\"$wpbusdirmanlistingtermlength\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\"/>";
									echo "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\"/>";
									echo "<input type=\"submit\" class=\"deletelistingbutton\" value=\"";
									_e("Delete Image","WPBDM");
									echo "\"></form></div>";
								}
							}

							echo "<div style=\"clear:both;\"></div>";

							if(isset($neworedit) && !empty($neworedit) && ($neworedit == 'edit'))
							{
								echo "<p>";
								_e("If you are not updating your images you can click the exit now button.","WPBDM");
								echo "</p>";

								echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
								echo "<p>";
								echo "<input type=\"submit\" class=\"exitnowbutton\" value=\"";
								_e("Exit Now","WPBDM");
								echo "\">";
								echo "</p>";
								echo "</form>";

							}

						}
						else
						{
							echo "<p>";
							if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending'){
							_e("Your submission has been received and is currently pending review","WPBDM");
							echo "</p>";
							}elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1']=='publish'){
							echo "<p>";
							_e("Your submission has been received and is currently published. Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM");
							echo "</p>";
							}

								echo "<p>";
								_e("You are finished with your listing.","WPBDM");
								echo "</p>";
								echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><input type=\"submit\" class=\"exitnowbutton\" value=\"";
								_e("Exit Now","WPBDM");
								echo "\"></form>";
						}

			}
			else
			{


				echo "<p>";
				echo "If you would like to include an image with your listing please upload the image of your choice. You are allowed [$wpbusdirmannumimgsallowed] images and have [$wpbusdirmannumimgsleft] image slots still available.";
				echo "</p>";

					echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\" ENCTYPE=\"Multipart/form-data\">";
					echo "<input type=\"hidden\" name=\"action\" value=\"wpbusdirmanuploadfile\"/>";
					echo "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/>";
					echo "<input type=\"hidden\" name=\"wpbusdirmannumimgsallowed\" value=\"$wpbusdirmannumimgsallowed\"/>";
					echo "<input type=\"hidden\" name=\"wpbusdirmannumimgsleft\" value=\"$wpbusdirmannumimgsleft\"/>";
					echo "<input type=\"hidden\" name=\"wpbusdirmanlistingtermlength\" value=\"$wpbusdirmanlistingtermlength\"/>";
					echo "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\"/>";
					echo "<input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\"/>";
					echo "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\"/>";


					for ($i=0;$i<$wpbusdirmannumimgsleft;$i++)
					{
						echo "<p><input name=\"wpbusdirmanuploadpic$i\"type=\"file\"></p>";

					}
						echo "<p><input class=\"insubmitbutton\" value=\"";
						_e("Upload File","WPBDM");
						echo "\" type=\"submit\"></p>";




				echo "</form>";

					if($wpbusdirmanimagesinposttotal >= 1)
					{

						if(get_post_meta($wpbusdirmanlistingpostid, "image", $single = true))
						{
							$wpbusdirmanimagesinpost=get_post_meta($wpbusdirmanlistingpostid, "image", $single = false);
							echo "<p>";
							_e("You can manage your current images below","WPBDM");
							echo "</p>";

							if($wpbusdirmanimagesinpost)
							{
								foreach($wpbusdirmanimagesinpost as $wpbusdirmanimage)
								{
									echo "<div style=\"float:left;margin-right:10px;margin-bottom:10px;\"><img src=\"$wpbdmimagesurl/thumbnails/$wpbusdirmanimage\" border=\"0\" height=\"100\" alt=\"$wpbusdirmanimage\"><br/>";
									echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
									echo "<input type=\"hidden\" name=\"action\" value=\"deleteimage\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmanimagetodelete\" value=\"$wpbusdirmanimage\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmannumimgsallowed\" value=\"$wpbusdirmannumimgsallowed\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmannumimgsleft\" value=\"$wpbusdirmannumimgsleft\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmanlistingtermlength\" value=\"$wpbusdirmanlistingtermlength\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\"/>";
									echo "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\"/>";
									echo "<input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\"/>";
									echo "<input type=\"submit\" class=\"deletelistingbutton\" value=\"";
									_e("Delete Image","WPBDM");
									echo "\" /></form></div>";
								}
							}

							echo "<div style=\"clear:both;\"></div>";
						}
					}


				if(isset($neworedit) && !empty($neworedit) && ($neworedit == 'edit'))
				{
					echo "<p>";
					_e("If you prefer not to add an image or you are otherwise finished managing your images you can click the exit now button.","WPBDM");
					echo "</p>";

							if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_19'] == 'pending2'){
							echo "<p>";
							_e("Your updated listing will be submitted for review.","WPBDM");
							echo "</p>";
							}elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_19']=='publish2'){
							echo "<p>";
							_e("Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM");
							echo "</p>";
							}

							echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
							echo "<p>";
							echo "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/><input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\"/>";
							echo "<input type=\"submit\" class=\"exitnowbutton\" value=\"";
							_e("Exit Now","WPBDM");
							echo "\">";
							echo "</p>";
							echo "</form>";

				}
				else
				{
					// This is not an edit but a new listing. Check if payments is necessary


					if(!($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "no"))
					{
						if(get_option('wpbusdirman_settings_fees_amount_'.$whichfeeoption) > 0)
						{

								echo "<p>";
								_e("If you prefer not to add any images please click Next to proceed to the next step.","WPBDM");
								echo "</p>";
								echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
								echo "<p>";
								echo "<input type=\"hidden\" name=\"action\" value=\"payment_step_2\"/><input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/><input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\"/><input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\"/><input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\"/><input type=\"submit\" class=\"exitnowbutton\" value=\"";
								_e("Next","WPBDM");
								echo "\" />";
								echo "</p>";
								echo "</form>";
						}
						else
						{

							_e("If you prefer not to add an image click exit now. Your listing will be submitted for review.","WPBDM");



								echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
								echo "<p>";
								echo "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/><input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\"/>";
								echo "<input type=\"submit\" class=\"exitnowbutton\" value=\"";
								_e("Exit Now","WPBDM");
								echo "\">";
								echo "</p>";
								echo "</form>";

						}
					}
					else
					{
							if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending'){$submitactionword =__("submit your listing for review","WPBDM");}
							elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1']=='publish'){$submitactionword =__("publish your listing","WPBDM");}
							else { $submitactionword =__("submit your listing.","WPBDM");}


							echo "<p>";
							_e("If you prefer not to upload an image at this time you can click the Exit now Button. Clicking the button will $submitactionword.","WPBDM");
							echo "</p>";
							echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
							echo "<p>";
							echo "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\"/><input type=\"hidden\" name=\"wpbusdirmanfeeoption\" value=\"$whichfeeoption\"/>";
							echo "<input type=\"submit\" class=\"exitnowbutton\" value=\"";
							_e("Exit Now","WPBDM");
							echo "\">";
							echo "</p>";
							echo "</form>";
					}
				}

			}

}

function wpbusdirman_doupload()
{

	global $wpbusdirmanimagesdirectory,$wpbusdirmanthumbsdirectory,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	if(isset($_REQUEST['wpbusdirmanlistingpostid']) && !empty($_REQUEST['wpbusdirmanlistingpostid'])){$wpbusdirmanlistingpostid=$_REQUEST['wpbusdirmanlistingpostid'];}
	if(isset($_REQUEST['wpbusdirmannumimgsallowed']) && !empty($_REQUEST['wpbusdirmannumimgsallowed'])){$wpbusdirmannumimgsallowed=$_REQUEST['wpbusdirmannumimgsallowed'];}else{$wpbusdirmannumimgsallowed=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_2'];
;}
	if(isset($_REQUEST['wpbusdirmanlistingtermlength']) && !empty($_REQUEST['wpbusdirmanlistingtermlength'])){$wpbusdirmanlistingtermlength=$_REQUEST['wpbusdirmanlistingtermlength'];}else{$wpbusdirmanlistingtermlength=365;}
	if(isset($_REQUEST['wpbusdirmannumimgsleft']) && !empty($_REQUEST['wpbusdirmannumimgsleft'])){$wpbusdirmannumimgsleft=$_REQUEST['wpbusdirmannumimgsleft'];}else{$wpbusdirmannumimgsleft='';}
	if(isset($_REQUEST['wpbusdirmanpermalink']) && !empty($_REQUEST['wpbusdirmanpermalink'])){$wpbusdirmanpermalink=$_REQUEST['wpbusdirmanpermalink'];}else{$wpbusdirmanpermalink='';}
	if(isset($_REQUEST['neworedit']) && !empty($_REQUEST['neworedit'])){$neworedit=$_REQUEST['neworedit'];}else{$neworedit='';}
	if(isset($_REQUEST['wpbusdirmanfeeoption']) && !empty($_REQUEST['wpbusdirmanfeeoption'])){$wpbusdirmanfeeoption=$_REQUEST['wpbusdirmanfeeoption'];}else{$wpbusdirmanfeeoption='';}


		//Create the plugin upload directories if they do not exist
		if ( !is_dir($wpbusdirmanimagesdirectory) )
		{
			@umask(0);
			@mkdir($wpbusdirmanimagesdirectory, 0777);
		}

		if ( !is_dir($wpbusdirmanthumbsdirectory) )
		{
			@umask(0);
			@mkdir($wpbusdirmanthumbsdirectory, 0777);
		}

		$wpbusdirmanimgmaxsize = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_13'];
		$wpbusdirmanimgminsize = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_14'];
		$wpbusdirmanimgmaxwidth = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_15'];
		$wpbusdirmanimgmaxheight = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_16'];
		$wpbusdirmanthumbnailwidth = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_17'];
		$wpbusdirmanallowedextensions = array(".jpg", ".gif", ".png");

		$wpbusdirmanerrornofiles=true;
		$wpbusdirmanuerror=array();

		for ($i=0;$i<$wpbusdirmannumimgsleft;$i++)
		{
			$wpbusdirmantheuploadedfilename = $_FILES['wpbusdirmanuploadpic'. $i]['name'];

			if(!empty($wpbusdirmantheuploadedfilename))
			{
				$wpbusdirmanerrornofiles=false;
			}

		}




		if ($wpbusdirmanerrornofiles)
		{
			$wpbusdirmanuerror[]="<p class=\"wpbusdirmanerroralert\">";
			$wpbusdirmanuerror[].=__("No file was selected","wpbusdirman");
			$wpbusdirmanuerror[].="</p>";
			$wpbusdirmanuploadformshow=wpbusdirman_image_upload_form($wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror,$neworedit,$whichfeeoption='');
			echo $wpbusdirmanuploadformshow;
		}

		else
		{
			wpbusdirmanuploadimages($wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanimgmaxsize,$wpbusdirmanimgminsize,$wpbusdirmanthumbnailwidth,$wpbusdirmanuploaded_actual_field_name='wpbusdirmanuploadpic',$required=false,$neworedit,$wpbusdirmanfeeoption);
		}

}

function wpbusdirman_validate_data()
{

		$wpbusdirman_field_item_array=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');

		$wpbusdirman_field_errors='';

		if($wpbusdirman_field_item_array)
		{
			foreach($wpbusdirman_field_item_array as $wpbusdirman_field_x_field)
			{

					$wpbusdirman_field_name=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_x_field);
					$wpbusdirman_field_validation=get_option('wpbusdirman_postform_field_validation_'.$wpbusdirman_field_x_field);
					$wpbusdirman_field_type=get_option('wpbusdirman_postform_field_type_'.$wpbusdirman_field_x_field);
					$wpbusdirman_field_options=get_option('wpbusdirman_postform_field_options_'.$wpbusdirman_field_x_field);
					$wpbusdirman_field_required=get_option('wpbusdirman_postform_field_required_'.$wpbusdirman_field_x_field);



					// Get the field association
					$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_x_field);


					if($wpbusdirman_field_association == 'title')
					{
						$wpbusdirman_field_label_association="_title";
					}
					elseif($wpbusdirman_field_association == 'category')
					{
						$wpbusdirman_field_label_association="_category";
					}
					elseif($wpbusdirman_field_association == 'excerpt')
					{
						$wpbusdirman_field_label_association="_excerpt";
					}
					elseif($wpbusdirman_field_association == 'description')
					{
						$wpbusdirman_field_label_association="_description";
					}
					elseif($wpbusdirman_field_association == 'tags')
					{
						$wpbusdirman_field_label_association="_tags";
					}
					else
					{
						$wpbusdirman_field_label_association="_meta$wpbusdirman_field_x_field";
					}

					if($wpbusdirman_field_association == 'category')
					{
						if($wpbusdirman_field_type == 2){$wpbusdirman_field_inputname="cat";}
						elseif($wpbusdirman_field_type == 6){$wpbusdirman_field_inputname="wpbusdirman_field_label_category";}
					}
					else
					{
						$wpbusdirman_field_inputname="wpbusdirman_field_label";
						$wpbusdirman_field_inputname.=$wpbusdirman_field_label_association;
					}

					if($wpbusdirman_field_required == 'yes')
					{

							if(!isset($_REQUEST[$wpbusdirman_field_inputname]) || empty($_REQUEST[$wpbusdirman_field_inputname]))
							{
								$error=true;
								$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
								$wpbusdirman_field_errors.=__("$wpbusdirman_field_name is required","awpdb");
								$wpbusdirman_field_errors.="</li>";
							}



					}

					if(($wpbusdirman_field_validation == 'missing') && ($wpbusdirman_field_required == 'yes'))
					{

							if(!isset($_REQUEST[$wpbusdirman_field_inputname]) || empty($_REQUEST[$wpbusdirman_field_inputname]))
							{
								$error=true;
								$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
								$wpbusdirman_field_errors.=__("$wpbusdirman_field_name is required","awpdb");
								$wpbusdirman_field_errors.="</li>";
							}

					}
					elseif( ($wpbusdirman_field_validation == 'url') && ( ( isset($_REQUEST[$wpbusdirman_field_inputname]) && !empty($_REQUEST[$wpbusdirman_field_inputname]) ) ) )
					{
							if( !wpbusdirman_isValidURL($_REQUEST[$wpbusdirman_field_inputname]) )
							{
								$error=true;
								$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
								$wpbusdirman_field_errors.=__("$wpbusdirman_field_name is badly formatted. Valid URL format required. Include http://","awpdb");
								$wpbusdirman_field_errors.="</li>";
							}
					}
					elseif(($wpbusdirman_field_validation == 'email') && ($wpbusdirman_field_required == 'yes'))
					{
						if (!wpbusdirman_isValidEmailAddress($_REQUEST[$wpbusdirman_field_inputname]))
						{
							$error=true;
							$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
							$wpbusdirman_field_errors.=__("$wpbusdirman_field_name is badly formatted. Valid Email format required.","awpdb");
							$wpbusdirman_field_errors.="</li>";
						}
					}
					elseif(($wpbusdirman_field_validation == 'numericdeci') && ($wpbusdirman_field_required == 'yes'))
					{
						if( !is_numeric($_REQUEST[$wpbusdirman_field_inputname]) )
						{
							$error=true;
							$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
							$wpbusdirman_field_errors.=__("$wpbusdirman_field_name must be a number.","awpdb");
							$wpbusdirman_field_errors.="</li>";
						}
					}
					elseif(($wpbusdirman_field_validation == 'numericwhole') && ($wpbusdirman_field_required == 'yes'))
					{
						if( !ctype_digit($_REQUEST[$wpbusdirman_field_inputname]) )
						{
							$error=true;
							$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
							$wpbusdirman_field_errors.=__("$wpbusdirman_field_name must be a number. Decimal values not allowed.","awpdb");
							$wpbusdirman_field_errors.="</li>";
						}
					}
					elseif(($wpbusdirman_field_validation == 'date') && ($wpbusdirman_field_required == 'yes'))
					{
						if( !wpbusdirman_is_ValidDate($_REQUEST[$wpbusdirman_field_inputname]) )
						{
							$error=true;
							$wpbusdirman_field_errors.="<li class=\"wpbusdirmanerroralert\">";
							$wpbusdirman_field_errors.=__("$wpbusdirman_field_name must be in the format 00/00/0000.","awpdb");
							$wpbusdirman_field_errors.="</li>";
						}
					}


				}
			}

		return $wpbusdirman_field_errors;
	}

function wpbusdirman_isValidURL($url)
{
 return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
}


function wpbusdirman_isValidEmailAddress($email) {
  if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
    return false;
  }

  $email_array = explode("@", $email);
  $local_array = explode(".", $email_array[0]);
  for ($i = 0; $i < sizeof($local_array); $i++) {
    if
(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
?'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",
$local_array[$i])) {
      return false;
    }
  }

  if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
    $domain_array = explode(".", $email_array[1]);
    if (sizeof($domain_array) < 2) {
        return false; // Not enough parts to domain
    }
    for ($i = 0; $i < sizeof($domain_array); $i++) {
      if
(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
?([A-Za-z0-9]+))$",
$domain_array[$i])) {
        return false;
      }
    }
  }
  return true;
}

function wpbusdirman_is_ValidDate($date)
{
	list($themonth,$theday,$theyear)=explode("/",$date);
	$theday=(int)$theday;
	$themonth=(int)$themonth;
	$theyear=(int)$theyear;
    	if ($theday!="" && $themonth!="" && $theyear!="")
    	{
    		if (is_numeric($theyear) && is_numeric($themonth) && is_numeric($theday))
       		{
           		 return checkdate($themonth,$theday,$theyear);
        	}
        }
    return false;
}

function wpbusdirmanuploadimages($wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanimgmaxsize,$wpbusdirmanimgminsize,$wpbusdirmanthumbnailwidth,$wpbusdirmanuploaded_actual_field_name,$required,$neworedit,$wpbusdirmanfeeoption)
{

	global $wpdb,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	$wpbusdirmanwpbusdirmanerroralert=false;
	$wpbusdirmanfilesuploaded=true;
	$wpbusdirmanuerror=array();
	$uploaddir=get_option('upload_path');

	if(!isset($uploaddir) || empty($uploaddir))
	{
		$uploaddir=ABSPATH;
		$uploaddir.="wp-content/uploads";
		//$uploaddir = trim($uploaddir,'/');
	}
	$wpbusdirmanuploaddir=$uploaddir;
	$wpbusdirmanuploaddir.="/wpbdm";
	$wpbusdirmanuploadthumbsdir=$wpbusdirmanuploaddir;
	$wpbusdirmanuploadthumbsdir.="/thumbnails";


		if ( !is_dir($wpbusdirmanuploaddir) )
		{
			umask(0);
			mkdir($wpbusdirmanuploaddir, 0777);
		}

		if ( !is_dir($wpbusdirmanuploadthumbsdir) )
		{
			umask(0);
			mkdir($wpbusdirmanuploadthumbsdir, 0777);
		}

		for($i=0;$i<$wpbusdirmannumimgsleft;$i++)
		{
			$wpbusdirmanuploadedfilename=addslashes($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['name']);
			$wpbusdirmanuploaded_ext=strtolower(substr(strrchr($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['name'],"."),1));
			$wpbusdirmanuploaded_ext_array=array('gif','jpg','jpeg','png');


			if (isset($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name']) && is_uploaded_file($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name']))
			{
				$wpbusdirman_imginfo = getimagesize($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name']);
				$wpbusdirman_imgfilesizeval=filesize($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name']);

				$wpbusdirmandesired_filename=mktime();
				$wpbusdirmandesired_filename.="_$i";

				if(isset($wpbusdirmanuploadedfilename) && !empty($wpbusdirmanuploadedfilename))
				{
					if (!(in_array($wpbusdirmanuploaded_ext, $wpbusdirmanuploaded_ext_array)))
					{
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbusdirmanuploadedfilename]";
					$wpbusdirmanuerror[].=__("had an invalid file extension and was not uploaded","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
					}
					elseif(filesize($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name']) <= $wpbusdirmanimgminsize)
					{
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">";
					$wpbusdirmanuerror[].=__("The size of $wpbusdirmanuploadedfilename was too small. The file was not uploaded. File size must be greater than $wpbusdirmanimgminsize bytes","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
					}
					elseif($wpbusdirman_imginfo[0]< $wpbusdirmanthumbnailwidth)
					{
					// width is too short
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbusdirmanuploadedfilename]";
					$wpbusdirmanuerror[].=__("did not meet the minimum width of [$wpbusdirmanthumbnailwidth] pixels. The file was not uploaded","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
					}
					elseif ($wpbusdirman_imginfo[1]< $wpbusdirmanthumbnailwidth)
					{
					// height is too short
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbusdirmanuploadedfilename]";
					$wpbusdirmanuerror[].=__("did not meet the minimum height of [$wpbusdirmanthumbnailwidth] pixels. The file was not uploaded","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
					}
					elseif(!isset($wpbusdirman_imginfo[0]) && !isset($wpbusdirman_imginfo[1]))
					{
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbusdirmanuploadedfilename]";
					$wpbusdirmanuerror[].=__("does not appear to be a valid image file","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
					}
					elseif( $wpbusdirman_imgfilesizeval > $wpbusdirmanimgmaxsize )
					{
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbusdirmanuploadedfilename]";
					$wpbusdirmanuerror[].=__("was larger than the maximum allowed file size of [$wpbusdirmanimgmaxsize] bytes. The file was not uploaded");
					$wpbusdirmanuerror[].="</p>";
					}
					elseif(!empty($wpbusdirmandesired_filename))
					{
						$wpbusdirmanuploadedfilename="$wpbusdirmandesired_filename.$wpbusdirmanuploaded_ext";

						if (!move_uploaded_file($_FILES[$wpbusdirmanuploaded_actual_field_name.$i]['tmp_name'],$wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename))
						{
							$wpbdmor=$wpbusdirmanuploadedfilename;
							$wpbusdirmanuploadedfilename='';
							$wpbusdirmanwpbusdirmanerroralert=true;
							$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">[$wpbdmor]";
							$wpbusdirmanuerror[].=__("could not be moved to the destination directory $wpbusdirmanuploaddir","wpbusdirman");
							$wpbusdirmanuerror[].="</p>";
						}
						else
						{
							if(!wpbusdirmancreatethumb($wpbusdirmanuploadedfilename,$wpbusdirmanuploaddir,$wpbusdirmanthumbnailwidth))
							{
								$wpbusdirmanwpbusdirmanerroralert=true;
								$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">";
								$wpbusdirmanuerror[].=__("Could not create thumbnail image of [ $wpbusdirmanuploadedfilename ]","wpbusdirman");
								$wpbusdirmanuerror[].="</p>";
							}

								@chmod($wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename,0644);

								// Add the image meta data to the post
								add_post_meta($wpbusdirmanlistingpostid, $wpbusdirman_field_label='image', $wpbusdirmanfieldmeta=$wpbusdirmanuploadedfilename, false) or update_post_meta($wpbusdirmanlistingpostid, $wpbusdirman_field_label='image', $wpbusdirmanfieldmeta=$wpbusdirmanuploadedfilename);
								add_post_meta($wpbusdirmanlistingpostid, $wpbusdirman_field_label='thumbnail', $wpbusdirmanfieldmeta=$wpbusdirmanuploadedfilename, false) or update_post_meta($wpbusdirmanlistingpostid, $wpbusdirman_field_label='thumbnail', $wpbusdirmanfieldmeta=$wpbusdirmanuploadedfilename);
								add_post_meta($wpbusdirmanlistingpostid, "totalallowedimages", $wpbusdirmannumimgsallowed, true) or update_post_meta($wpbusdirmanlistingpostid, "totalallowedimages", $wpbusdirmannumimgsallowed);

								if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes")
								{
									$wpbusdirmanlengthofterm=get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirmanfeeoption);
									$wpbusdirmanlistingcost=get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirmanfeeoption);
									add_post_meta($wpbusdirmanlistingpostid, "lengthofterm", $wpbusdirmanlengthofterm, true) or update_post_meta($wpbusdirmanlistingpostid, "lengthofterm", $wpbusdirmanlengthofterm);
									add_post_meta($wpbusdirmanlistingpostid, "listing_package", $wpbusdirmanfeeoption, true) or update_post_meta($wpbusdirmanlistingpostid, "listing_package", $wpbusdirmanfeeoption);

								}



						}
					}
				}
				else
				{
					$wpbusdirmanwpbusdirmanerroralert=true;
					$wpbusdirmanuerror[].="<p class=\"wpbusdirmanerroralert\">";
					$wpbusdirmanuerror[].=__("Unknown error encountered uploading image","wpbusdirman");
					$wpbusdirmanuerror[].="</p>";
				}
			}

		} // Close for $i...

			if ($wpbusdirmanwpbusdirmanerroralert)
			{
				$wpbusdirmanuploadformshow=wpbusdirman_image_upload_form($wpbusdirmanlistingpostid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror,$neworedit,$wpbusdirmanfeeoption);
				echo $wpbusdirmanuploadformshow;
			}
			else
			{
				if(isset($neworedit) && !empty($neworedit) && ($neworedit == 'edit'))
				{
					if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_19'] == 'pending2')
					{
						echo "<p>";
						_e("Your listing has been updated. Your listing is currently pending re-review and will become accessible again once the administrator has reviewed it.","WPBDM");
						echo "</p>";
					}
					elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_19'] == 'publish2')
					{
						echo "<p>";
						_e("Your listing has been updated. Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM");
						echo "</p>";
					}
					else
					{
						echo "<p>";
						_e("You are finished with your listing.","WPBDM");
						echo "</p>";
						echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><input type=\"submit\" class=\"exitnowbutton\" value=\"";
						_e("Exit Now","WPBDM");
						echo "\"></form>";
					}
				}
				else
				{
					// This was a new submission

					if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes")
					{
						$wpbusdirmanthisfeetopay=get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirmanfeeoption);
						if($wpbusdirmanthisfeetopay > 0)
						{
							wpbusdirman_load_payment_page($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption);
						}
						else
						{
							// There is no fee to pay so skip to end of process. Nothing left to do
							if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending')
							{
								echo "<p>";
								_e("Your submission has been received and is currently pending review","WPBDM");
								echo "</p>";
								add_post_meta($wpbusdirmanlistingID, "sticky", "pending", true) or update_post_meta($wpbusdirmanlistingID, "sticky", "pending");
								add_post_meta($wpbusdirmanlistingID, "paymentstatus", "pending", true) or update_post_meta($wpbusdirmanlistingID, "paymentstatus", "pending");
								add_post_meta($wpbusdirmanlistingID, "buyerfirstname", "Unknown", true) or update_post_meta($wpbusdirmanlistingID, "buyerfirstname", $first_name);
								add_post_meta($wpbusdirmanlistingID, "buyerlastname", "Unknown", true) or update_post_meta($wpbusdirmanlistingID, "buyerlastname", $last_name);
								add_post_meta($wpbusdirmanlistingID, "paymentgateway", "Check", true) or update_post_meta($wpbusdirmanlistingID, "paymentgateway", "Chamber");
								add_post_meta($wpbusdirmanlistingID, "payeremail", "Unknown", true) or update_post_meta($wpbusdirmanlistingID, "payeremail", "Unknown");
							}
							elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'publish')
							{
								echo "<p>";
								_e("Your submission has been received and is currently published. Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM");
								echo "</p>";
							}
							else
							{
										echo "<p>";
										_e("You are finished with your listing.","WPBDM");
										echo "</p>";
										echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><input type=\"submit\" class=\"exitnowbutton\" value=\"";
										_e("Exit Now","WPBDM");
										echo "\"></form>";
							}
						}
					}
					else
					{
						if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'pending')
						{
							echo "<p>";
							_e("Your submission has been received and is currently pending review","WPBDM");
							echo "</p>";
							add_post_meta($wpbusdirmanlistingID, "sticky", "pending", true) or update_post_meta($wpbusdirmanlistingID, "sticky", "pending");
							add_post_meta($wpbusdirmanlistingID, "paymentstatus", "pending", true) or update_post_meta($wpbusdirmanlistingID, "paymentstatus", "pending");
							add_post_meta($wpbusdirmanlistingID, "buyerfirstname", "Unknown", true) or update_post_meta($wpbusdirmanlistingID, "buyerfirstname", $first_name);
							add_post_meta($wpbusdirmanlistingID, "buyerlastname", "Unknown", true) or update_post_meta($wpbusdirmanlistingID, "buyerlastname", $last_name);
							add_post_meta($wpbusdirmanlistingID, "paymentgateway", "Check", true) or update_post_meta($wpbusdirmanlistingID, "paymentgateway", "Chamber");
							add_post_meta($wpbusdirmanlistingID, "payeremail", "Unknown", true) or update_post_meta($wpbusdirmanlistingID, "payeremail", "Unknown");
						}
						elseif($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_1'] == 'publish')
						{
							echo "<p>";
							_e("Your submission has been received and is currently published. Note that the administrator reserves the right to terminate without warning any listings that violate the site's terms of use.","WPBDM");
							echo "</p>";
						}
						else
						{
									echo "<p>";
									_e("You are finished with your listing.","WPBDM");
									echo "</p>";
									echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\"><input type=\"submit\" class=\"exitnowbutton\" value=\"";
									_e("Exit Now","WPBDM");
									echo "\"></form>";
						}
					}
				}
			}

}

function wpbusdirmancreatethumb($wpbusdirmanuploadedfilename,$wpbusdirmanuploaddir,$wpbusdirmanthumbnailwidth)
{
		$wpbusdirman_show_all=true;
		$wpbusdirman_thumbs_width=$wpbusdirmanthumbnailwidth;
		$mynewimg='';
		if (extension_loaded('gd')) {
			if ($wpbusdirman_imginfo=getimagesize($wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename)) {
				$width=$wpbusdirman_imginfo[0];
				$height=$wpbusdirman_imginfo[1];
				if ($width>$wpbusdirman_thumbs_width) {
					$newwidth=$wpbusdirman_thumbs_width;
					$newheight=$height*($wpbusdirman_thumbs_width/$width);
					if ($wpbusdirman_imginfo[2]==1) {		//gif
					} elseif ($wpbusdirman_imginfo[2]==2) {		//jpg
						if (function_exists('imagecreatefromjpeg')) {
							$myimg=@imagecreatefromjpeg($wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename);
						}
					} elseif ($wpbusdirman_imginfo[2]==3) {	//png
						$myimg=@imagecreatefrompng($wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename);
					}
					if (isset($myimg) && !empty($myimg)) {
						$gdinfo=wpbusdirman_GD();
						if (stristr($gdinfo['GD Version'], '2.')) {	// if we have GD v2 installed
							$mynewimg=@imagecreatetruecolor($newwidth,$newheight);
							if (imagecopyresampled($mynewimg,$myimg,0,0,0,0,$newwidth,$newheight,$width,$height)) {
								$wpbusdirman_show_all=false;
							}
						} else {	// GD 1.x here
							$mynewimg=@imagecreate($newwidth,$newheight);
							if (@imagecopyresized($mynewimg,$myimg,0,0,0,0,$newwidth,$newheight,$width,$height)) {
								$wpbusdirman_show_all=false;
							}
						}
					}
				}
			}
		}
		if (!is_writable($wpbusdirmanuploaddir.'/thumbnails')) {
			@chmod($wpbusdirmanuploaddir.'/thumbnails',0755);
			if (!is_writable($wpbusdirmanuploaddir.'/thumbnails')) {
				@chmod($wpbusdirmanuploaddir.'/thumbnails',0777);
			}
		}
		if ($wpbusdirman_show_all) {
			$myreturn=@copy($wpbusdirmanuploaddir.'/'.$wpbusdirmanuploadedfilename,$wpbusdirmanuploaddir.'/thumbnails/'.$wpbusdirmanuploadedfilename);
		} else {
			$myreturn=@imagejpeg($mynewimg,$wpbusdirmanuploaddir.'/thumbnails/'.$wpbusdirmanuploadedfilename,100);
		}
		@chmod($wpbusdirmanuploaddir.'/thumbnails/'.$wpbusdirmanuploadedfilename,0644);
	return $myreturn;
}



		function wpbusdirman_GD()
		{
			$myreturn=array();
			if (function_exists('gd_info'))
			{
				$myreturn=gd_info();
			} else
			{
				$myreturn=array('GD Version'=>'');
				ob_start();
				phpinfo(8);
				$info=ob_get_contents();
				ob_end_clean();
				foreach (explode("\n",$info) as $line)
				{
					if (strpos($line,'GD Version')!==false)
					{
						$myreturn['GD Version']=trim(str_replace('GD Version', '', strip_tags($line)));
					}
				}
			}
			return $myreturn;
		}

function wpbusdirman_managelistings()
{
	global $siteurl,$wpbdmimagesurl,$wpbusdirman_gpid,$permalinkstructure,$wpbdmposttype,$wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

		if(!(is_user_logged_in()))
		{
			$wpbusdirmanloginurl=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_4'];
			if(!isset($wpbusdirmanloginurl) || empty($wpbusdirmanloginurl))
			{
				$wpbusdirmanloginurl=$siteurl.'/wp-login.php';
			}

			echo "<p>";
			_e("You are not currently logged in. Please login first","WPBDM");
			echo "</p>";
			echo "<p><form method=\"post\" action=\"$wpbusdirmanloginurl\"><input type=\"submit\" class=\"insubmitbutton\" value=\"";
			_e("Login Now","WPBDM");
			echo "\"></form></p>";
		}
		else
		{

			// If there are no categories setup go no further

			$args=array('hide_empty' => 0);
			$wpbusdirman_postcats=get_terms( $wpbdmposttypecategory, $args);


			if(!isset($wpbusdirman_postcats) || empty($wpbusdirman_postcats))
			{
				if(is_user_logged_in() && current_user_can('install_plugins'))
				{
					echo "<p>";
					_e("There are no categories assigned to the business directory yet. You need to assign some categories to the business directory. Only admins can see this message. Regular users are seeing a message that they do not currently have any listings to manage. Listings cannot be added until you assign categories to the business directory. ","WPBDM");
					echo "</p>";
				}
				else
				{
					echo "<p>";
					_e("You do not currently have any listings to manage","WPBDM");
					echo "</p>";
				}
			}
			else
			{


					global $current_user;
					get_currentuserinfo();

					$wpbusdirman_CUID=$current_user->ID;


				//Reset Query
				wp_reset_query();

				$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);

				//The Query
				query_posts('author='.$wpbusdirman_CUID.'&post_type='.$wpbdmposttype);

				//The Loop
				if ( have_posts() ) :
				?>
				<p><?php _e("Your current listings are shown below. To edit a listing click the edit button. To delete a listing click the delete button.","WPBDM");?></p>
				<?php while ( have_posts() ) : the_post();?>

				<?php wpbusdirman_display_excerpt();?>


				<?php	endwhile;?>
				<div class="navigation">
					<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }else {?>
						<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
						<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
					<?php } ?>
				</div>
				<?php else:

				 _e("You do not currently have any listings in the directory","WPBDM");

				endif;

				//Reset Query
				wp_reset_query();

		}

	}

 }

function wpbusdirman_deleteimage($imagetodelete,$wpbdmlistingid,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanpermalink,$neworedit)
{

	global $wpbusdirmanimagesdirectory,$wpbusdirmanthumbsdirectory;

	if(isset($imagetodelete) && !empty($imagetodelete))
	{
		if(isset($wpbdmlistingid) && !empty($wpbdmlistingid))
		{
			delete_post_meta($wpbdmlistingid, "image", $imagetodelete);
			delete_post_meta($wpbdmlistingid, "thumbnail", $imagetodelete);

				if (file_exists($wpbusdirmanimagesdirectory.'/'.$imagetodelete))
				{
					@unlink($wpbusdirmanimagesdirectory.'/'.$imagetodelete);
				}
				if (file_exists($wpbusdirmanthumbsdirectory.'/'.$imagetodelete))
				{
					@unlink($wpbusdirmanthumbsdirectory.'/'.$imagetodelete);
				}

				$wpbusdirmannumimgsleft=($wpbusdirmannumimgsleft + 1);
		}

	}

	wpbusdirman_image_upload_form($wpbdmlistingid,$wpbusdirmanpermalink,$wpbusdirmannumimgsallowed,$wpbusdirmannumimgsleft,$wpbusdirmanlistingtermlength,$wpbusdirmanuerror='',$neworedit,$whichfeeoption='');



}

function wpbusdirman_load_payment_page($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption)
{
	global $wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_hasgooglecheckoutmodule,$wpbusdirman_gpid,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbusdirman_googlecheckout_button='';
	$wpbusdirman_paypal_button='';
	$wpbusdirman_twocheckout_button='';

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_21'] == "yes")
	{
		$wpbusdirmanlengthofterm=get_option('wpbusdirman_settings_fees_increment_'.$wpbusdirmanfeeoption);
		$wpbusdirmanlistingcost=get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirmanfeeoption);
		$wpbusdirmantotalallowedimages=get_option('wpbusdirman_settings_fees_images_'.$wpbusdirmanfeeoption);
		add_post_meta($wpbusdirmanlistingpostid, "lengthofterm", $wpbusdirmanlengthofterm, true) or update_post_meta($wpbusdirmanlistingpostid, "lengthofterm", $wpbusdirmanlengthofterm);
		add_post_meta($wpbusdirmanlistingpostid, "listing_package", $wpbusdirmanfeeoption, true) or update_post_meta($wpbusdirmanlistingpostid, "listing_package", $wpbusdirmanfeeoption);
		if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_6'] == "yes")
		{
			add_post_meta($wpbusdirmanlistingpostid, "totalallowedimages", $wpbusdirmantotalallowedimages, true) or update_post_meta($wpbusdirmanlistingpostid, "totalallowedimages", $wpbusdirmantotalallowedimages);
		}

	}

	echo "<h3>";
	_e("Finish and Pay","WPBDM");
	echo "</h3>";
	echo "<br/>";

	global $wpbusdirman_imagesurl;
	$your_package = get_package_array($wpbusdirmanlistingpostid);
		echo '<p><strong>You chose the '.$your_package[0].' package</strong></p>';
		echo '<p>Contact the Chelsea Chamber of Commerce at (734)-475-1145 to pay your listing fee and finalize your listing.</p>';
		$wpbusdirman_googlecheckout_button=pay_with_check_btn($wpbusdirmanlistingpostid,$wpbusdirmanfeeoption);
		echo "<div class=\"paymentbuttondiv\">";
		echo $wpbusdirman_googlecheckout_button;
		echo "</div>";
}

function wpbusdirman_feepay_configure($post_category_item)
{

	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	$wpbusdirman_get_currency_symbol=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_12'];

	if(!isset($wpbusdirman_get_currency_symbol) || empty($wpbusdirman_get_currency_symbol)){$wpbusdirman_get_currency_symbol="$";}
	$wpbusdirman_settings_fees_ops=wpbusdirman_retrieveoptions($whichoptionvalue='wpbusdirman_settings_fees_label_');
	$wpbusdirman_fee_to_pay_li = '';


	if($wpbusdirman_settings_fees_ops)
	{
		foreach($wpbusdirman_settings_fees_ops as $wpbusdirman_settings_fees_op)
		{
			// Get categories listed under this fee structure
			$wpbusdirman_categories_under=get_option('wpbusdirman_settings_fees_categories_'.$wpbusdirman_settings_fees_op);


			$wpbusdirman_savedpostcatitems=array();
			if(($wpbusdirman_categories_under) && is_array($wpbusdirman_categories_under))
			{
				$temp = explode(',',$wpbusdirman_categories_under);
				foreach ($temp as $categ_id)
				{
					$wpbusdirman_savedpostcatitems[]=trim($categ_id);
				}
			}elseif(!is_array($wpbusdirman_categories_under))
			{

				$wpbusdirman_savedpostcatitems[]=$wpbusdirman_categories_under;
			}
			else {
				$temp = explode(',',$wpbusdirman_categories_under);
					foreach ($temp as $categ_id)
					{
						$wpbusdirman_savedpostcatitems[]=trim($categ_id);
					}
			}

			if($wpbusdirman_savedpostcatitems)
			{

				if(in_array($post_category_item, $wpbusdirman_savedpostcatitems))
				{
					$wpbusdirman_get_fee=get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirman_settings_fees_op);
					$wpbusdirman_fee_op_name=get_option('wpbusdirman_settings_fees_label_'.$wpbusdirman_settings_fees_op);
					$wpbusdirman_settings_fees_desc=get_option('wpbusdirman_settings_fees_desc_'.$wpbusdirman_settings_fees_op);
					$wpbusdirman_fee_to_pay_li.="<tr><td class=\"input-cell\"><input type=\"radio\" class=\"fee_option\" name=\"whichfeeoption\" value=\"$wpbusdirman_settings_fees_op\" checked /></td>";
					$wpbusdirman_fee_to_pay_li .= '<td><span class="listing-label">'.$wpbusdirman_fee_op_name.'</span><span class="listing-fees">'.$wpbusdirman_get_fee.'</span></td></tr>"';		
				}

				if(in_array(0, $wpbusdirman_savedpostcatitems))
				{
					$wpbusdirman_get_fee=get_option('wpbusdirman_settings_fees_amount_'.$wpbusdirman_settings_fees_op);
					$wpbusdirman_fee_op_name=get_option('wpbusdirman_settings_fees_label_'.$wpbusdirman_settings_fees_op);
					$wpbusdirman_settings_fees_desc=get_option('wpbusdirman_settings_fees_desc_'.$wpbusdirman_settings_fees_op);
					$wpbusdirman_fee_to_pay_li.="<tr><td class=\"input-cell\"><input type=\"radio\" class=\"fee_option\" name=\"whichfeeoption\" value=\"$wpbusdirman_settings_fees_op\" checked /></td>";
					$wpbusdirman_fee_to_pay_li .= '<td><span class="listing-label">'.$wpbusdirman_fee_op_name.'</span><span class="listing-fees">'.$wpbusdirman_get_fee.'</span>';
					$wpbusdirman_fee_to_pay_li .= $wpbusdirman_settings_fees_desc;
					$wpbusdirman_fee_to_pay_li .= '</td></tr>';
				}
			}

		}
	}

	return $wpbusdirman_fee_to_pay_li;

}

function wpbusdirman_contactform($wpbusdirmanpermalink,$wpbusdirmanlistingpostid,$commentauthorname,$commentauthoremail,$commentauthorwebsite,$commentauthormessage,$wpbusdirmancontacterrors)
{

	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	if(!isset($wpbusdirmanpermalink) || empty($wpbusdirmanpermalink))
	{
		global $wpbusdirman_gpid,$wpbdmimagesurl;
		$wpbusdirmanpermalink=get_permalink($wpbusdirman_gpid);
	}

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_27'] == "yes")
	{
		if(isset($wpbusdirmancontacterrors) && !empty($wpbusdirmancontacterrors)){echo "<ul id=\"wpbusdirmanerrors\">$wpbusdirmancontacterrors</ul>";}

		echo "<p>";
		echo "<h4>";
		_e("Send Message to listing owner","WPBDM");
		echo "</h4></p>";
		echo "<p>";
		echo "<label>";
		_e("Listing Title: ","WPBDM");
		echo "</label>";
		echo get_the_title($wpbusdirmanlistingpostid);
		echo "</p>";

		echo "<form method=\"post\" action=\"$wpbusdirmanpermalink\">";
		if(!is_user_logged_in())
		{
			echo "<p><label style=\"width:4em;\">";
			_e("Your Name ","WPBDM");
			echo "</label><input type=\"text\" class=\"intextbox\" name=\"commentauthorname\" value=\"$commentauthorname\" /></p>";
			echo"<p><label style=\"width:4em;\">";
			_e("Your Email ","WPBDM");
			echo "</label><input type=\"text\" class=\"intextbox\" name=\"commentauthoremail\" value=\"$commentauthoremail\" /></p>";
			echo"<p><label style=\"width:4em;\">";
			_e("Website url ","WPBDM");
			echo "</label><input type=\"text\" class=\"intextbox\" name=\"commentauthorwebsite\" value=\"$commentauthorwebsite\" /></p>";
		}
		elseif(is_user_logged_in())
		{
			if(!isset($commentauthorname) || empty($commentauthorname))
			{
				global $post, $current_user;
				get_currentuserinfo();

				$commentauthorname = $current_user->user_login;
			}
			_e("You are currently logged in as ","WPBDM");
			echo $commentauthorname;
			_e(" Your message will be sent using your logged in contact email.","WPBDM");
		}
		echo"<p><label style=\"width:4em;\">";
		_e("Message","WPBDM");
		echo "</label><br/><br/><textarea name=\"commentauthormessage\" class=\"intextarea\">$commentauthormessage</textarea></p>";
		if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_30'] == "yes")
		{
			$publickey = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_28'];
			if(isset($publickey) && !empty($publickey))
			{
				require_once('recaptcha/recaptchalib.php');
				echo recaptcha_get_html($publickey);
			}
		}
		echo "<p>";
		echo "<input type=\"hidden\" name=\"action\" value=\"sendcontactmessage\" />";
		echo "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbusdirmanlistingpostid\" />";
		echo "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirmanpermalink\" />";
		echo "<input type=\"submit\" class=\"insubmitbutton\" value=\"Send\">";
		echo "</p>";
		echo "</form>";

	}
}


function wpbusdirman_upgradetosticky($wpbdmlistingid)
{
 	global $wpbusdirman_imagesurl,$wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_hasgooglecheckoutmodule,$wpbusdirman_gpid,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

 	echo "<h4>";
 	_e("Upgrade listing","WPBDM");
 	echo "</h4>";
 	$wpbdmstickydetailtext=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_33'];
 	if(isset($wpbdmstickydetailtext) && !empty($wpbdmstickydetailtext))
 	{
 		echo "<p>$wpbdmstickydetailtext</p>";
 	}

 	$wpbusdirman_stickylistingprice=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_32'];

 	add_post_meta($wpbdmlistingid, "sticky", "not paid", true) or update_post_meta($wpbdmlistingid, "sticky", "not paid");

	if($wpbusdirman_hasgooglecheckoutmodule == 1)
	{
		echo "<h4 class=\"paymentheader\">";
		_e("Pay Upgrade Fee via Google Checkout","WPBDM");
		echo "</h4>";
		$wpbusdirman_googlecheckout_button=wpbusdirman_googlecheckout_button($wpbdmlistingid,$wpbusdirmanfeeoption='32');
		echo "<div class=\"paymentbuttondiv\">";
		echo $wpbusdirman_googlecheckout_button;
		echo "</div>";
	}

	if($wpbusdirman_haspaypalmodule == 1)
	{
		echo "<h4 class=\"paymentheader\">";
		_e("Pay Upgrade Fee via PayPal","WPBDM");
		echo "</h4>";
		$wpbusdirman_paypal_button=wpbusdirman_paypal_button($wpbdmlistingid,$wpbusdirmanfeeoption='32',$wpbusdirman_imagesurl);
		echo "<div class=\"paymentbuttondiv\">";
		echo $wpbusdirman_paypal_button;
		echo "</div>";
	}

	if($wpbusdirman_hastwocheckoutmodule == 1)
	{
		echo "<h4 class=\"paymentheader\">";
		_e("Pay Upgrade Fee via 2Checkout","WPBDM");
		echo "</h4>";
		$wpbusdirman_twocheckout_button=wpbusdirman_twocheckout_button($wpbdmlistingid,$wpbusdirmanfeeoption='32',$wpbusdirman_gpid);
		echo "<div class=\"paymentbuttondiv\">";
		echo $wpbusdirman_twocheckout_button;
		echo "</div>";
	}

}


function wpbusdirman_featured_pending()
{
	global $wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	wpbusdirman_admin_head();

	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) && ($_REQUEST['action'] == 'upgradefeatured'))
	{
		if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
		{
			$wpbdmposttofeature=$_REQUEST['id'];
			update_post_meta($wpbdmposttofeature, "sticky", "approved");
			_e("The listing has been upgraded.","WPBDM");
		}
		else
		{
			_e("No ID was provided. Please try again","WPBDM");
		}
	}

	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) && ($_REQUEST['action'] == 'cancelfeatured'))
	{
		if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
		{
			$wpbdmposttofeature=$_REQUEST['id'];
			delete_post_meta($wpbdmposttofeature, "sticky","pending");
			_e("The listing has been downgraded.","WPBDM");
		}
		else
		{
			_e("No ID was provided. Please try again","WPBDM");
		}
	}

	echo "<h3 style=\"padding:10px;\">";
	echo "Manage Featured Listings pending manual upgrade";
	echo "</h3>";

	
	$wpbusdirman_pending='';

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_31'] == "no")
	{
		_e("You are not currently allowing sticky (featured) listings. To allow sticky listings check that option in the manage options page under the Featured/Sticky listing settings.","WPBDM");
	}
	else
	{
		global $wpbusdirman_valuetext, $wpbusdirman_labeltext,$wpbusdirman_actiontext;

			$arg = array('post_type' => 'wpbdm-directory', 'numberposts' => -1);
			$wpbusdirman_posts[]=get_posts($arg);

		if($wpbusdirman_posts)
		{

			foreach($wpbusdirman_posts[0] as $wpbusdirman_cat)
			{
				$wpbusdirman_posts_ids[]=$wpbusdirman_cat->ID;
			}
			// echo '<pre>';
			// print_r($wpbusdirman_posts_ids);
			// echo '</pre>';
		}

			if($wpbusdirman_posts_ids)
			{
				foreach($wpbusdirman_posts_ids as $wpbusdirman_post) {
						$wpbusdirman_pendingfeatured[]=$wpbusdirman_post;
				}
			}

					if(empty($wpbusdirman_pendingfeatured))
					{
						_e("Currently there are no listings waiting to be upgraded to sticky(featured) status","WPBDM");
					}
					else
					{
						echo '<h3>Click on a heading below to sort the table</h3>';
						echo "<table class=\"widefat\" id=\"sortable\" cellspacing=\"0\">";
						echo "<thead>";
						echo "<tr>";
						echo "<th scope=\"col\" class=\"manage-column\">";
						_e("Business Title","WPBDM");
						echo "</th>";
						echo "<th scope=\"col\" class=\"manage-column\">";
						echo 'Listing Level';
						echo "</th>";
						echo "<th scope=\"col\" class=\"manage-column\">";
						_e("Business ID","WPBDM");
						echo "</th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tfoot>";
						echo "<tr>";

						echo "<th scope=\"col\" class=\"manage-column\">";
						_e("Business Title","WPBDM");
						echo "</th>";
						echo "<th scope=\"col\" class=\"manage-column\">";
						echo 'Listing Level';
						echo "</th>";
						echo "<th scope=\"col\" class=\"manage-column\">";
						_e("Business ID","WPBDM");
						echo "</th>";
						echo "</tr>";
						echo "</tfoot>";
						echo "<tbody>";
						
					
					// Built out our little selecter guy.
						global $wpbusdir_fee_options;		
						
						if($wpbusdirman_pendingfeatured)	{
							
							foreach($wpbusdirman_pendingfeatured as $wpbusdirman_pendingfeatureditem) {
								
								
								unset($package_select);
								$package_select = '<select class="listing-plan" name="listing_plan">';

								foreach ($wpbusdir_fee_options as $option) {
									// print_R($option);	
								unset($current_package);
								$current_package = get_package_array($wpbusdirman_pendingfeatureditem);
									if ($option[0] == $current_package[0]) {
										$package_select .= '<option selected="selected" value="'.$option[5].'">'.$option[0].'</option>';
									} else {
										$package_select .= '<option value="'.$option[5].'">'.$option[0].'</option>';
									}
								}

								$package_select .= '</select>';
								$package_select .= '&nbsp;&nbsp;<a class="listing-plan-change button-secondary action" href="javascript:void()" rel="'.$wpbusdirman_pendingfeatureditem.'"';
								$package_select .= '>Change</a>';
								
								$sticky_meta = get_post_meta($wpbusdirman_pendingfeatureditem, "sticky", $single=true);
								if ($sticky_meta == 'approved') {$is_featured = 'Yes';} else {$is_featured = 'No';};
								$listing_package = get_package_array($wpbusdirman_pendingfeatureditem);
								$package_name = $listing_package[0];
								
								echo "<tr><td><a href=\"";
								echo get_permalink($wpbusdirman_pendingfeatureditem);
								echo "\">".get_the_title($wpbusdirman_pendingfeatureditem)."</a></td>";
								// echo "<td>$package_name</td>";
								echo '<td>';
								// echo '<pre>';
								// 	print_r($wpbusdir_fee_options);
								// echo '</pre>';
								echo $package_select;
								echo '</td>';
								echo "<td>$wpbusdirman_pendingfeatureditem</td>";
								echo "</tr>";
							}
						}
					}


		echo "</tbody></table>";
	}
	echo '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>';
	echo '<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js" type="text/javascript"></script>';
	echo '<script src="/wp-content/plugins/wp-business-directory-manager/jquery.tablesorter.min.js" type="text/javascript"></script>';
	echo '<script src="/wp-content/plugins/wp-business-directory-manager/manager.js" type="text/javascript"></script>';
	
	
	echo '<style> th.header {cursor: pointer;}</style>';
	
	wpbusdirman_admin_foot();

}

function wpbusdirman_manage_paid()
{
	global $wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	wpbusdirman_admin_head();

	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) && ($_REQUEST['action'] == 'setaspaid'))
	{
		if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
		{
			$wpbdmposttosetaspaid=$_REQUEST['id'];
			update_post_meta($wpbdmposttosetaspaid, "paymentstatus", "paid");
			_e("The listing status has been set as paid.","WPBDM");
		}
		else
		{
			_e("No ID was provided. Please try again","WPBDM");
		}
	}

	if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) && ($_REQUEST['action'] == 'setasnotpaid'))
	{
		if(isset($_REQUEST['id']) && !empty($_REQUEST['id']))
		{
			$wpbdmposttosetasnotpaid=$_REQUEST['id'];
			delete_post_meta($wpbdmposttosetasnotpaid, "paymentstatus","pending");
			delete_post_meta($wpbdmposttosetasnotpaid, "paymentstatus","refunded");
			delete_post_meta($wpbdmposttosetasnotpaid, "paymentstatus","unknown");
			delete_post_meta($wpbdmposttosetasnotpaid, "paymentstatus","cancelled");
			_e("The listing status has been changed non-paying.","WPBDM");
		}
		else
		{
			_e("No ID was provided. Please try again","WPBDM");
		}
	}

	echo "<h3 style=\"padding:10px;\">";
	echo "Manage Paid Listings";
	echo "</h3>";

	$wpbusdirman_pending='';

	if($wpbusdirman_config_options['_settings_config_21'] == "no")
	{
		_e("You are not currently charging any payment fees. To charge fees for listings check that option in the manage options page.","WPBDM");
	}
	else
	{
		global $wpbusdirman_valuetext, $wpbusdirman_labeltext,$wpbusdirman_actiontext,$wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule;

		$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');

		if($wpbusdirman_myterms)
		{
			foreach($wpbusdirman_myterms as $wpbusdirman_myterm)
			{
				$wpbusdirman_postcatitems[]=$wpbusdirman_myterm->term_id;
			}
		}

		if($wpbusdirman_postcatitems)
		{
			foreach($wpbusdirman_postcatitems as $wpbusdirman_postcatitem)
			{
				$wpbusdirman_catcat=get_posts($wpbusdirman_postcatitem);
			}
		}

		if($wpbusdirman_catcat)
		{

			foreach($wpbusdirman_catcat as $wpbusdirman_cat)
			{
				$wpbusdirman_postsposts[]=$wpbusdirman_cat->ID;
			}
		}


			if($wpbusdirman_postsposts)
			{
				foreach($wpbusdirman_postsposts as $wpbusdirman_post)
				{
					$wpbdmisapaid=get_post_meta($wpbusdirman_post, "paymentstatus",$single=true);

					if(isset($wpbdmisapaid) && ($wpbdmisapaid <> ''))
					{
						$wpbusdirman_paidlistings[]=$wpbusdirman_post;
					}
				}
			}

			if(empty($wpbusdirman_paidlistings))
			{
				_e("Currently there are no paid listings","WPBDM");
			}
			else
			{
				echo "<p style=\"float:right\"><a href=\"http://themestown.com/wp-business-directory-manager/manage-paid-listings\">";
				_e("Get info on managing paid listings","WPBDM");
				echo "</a></p>";


				echo "<table class=\"widefat\" id=\"sortable\" cellspacing=\"0\">";
				echo "<thead>";
				echo "<tr>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("Title","WPBDM");
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("ID","WPBDM");
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("Status","WPBDM");
				echo "</th>";
				if($wpbusdirman_haspaypalmodule == 1)
				{
					echo "<th scope=\"col\" class=\"manage-column\">";
					_e("Flag","WPBDM");
					echo "</th>";
				}
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("Gateway","WPBDM");
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("Buyer","WPBDM");
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("Payment Email","WPBDM");
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
						echo $wpbusdirman_actiontext;
						echo "</th>";
						echo "</tr>";
						echo "</thead>";
						echo "<tfoot>";
						echo "<tr>";

				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("Title","WPBDM");
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("ID","WPBDM");
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("Status","WPBDM");
				echo "</th>";
				if($wpbusdirman_haspaypalmodule == 1)
				{
					echo "<th scope=\"col\" class=\"manage-column\">";
					_e("Flag","WPBDM");
					echo "</th>";
				}
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("Gateway","WPBDM");
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("Buyer","WPBDM");
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
				_e("Payment Email","WPBDM");
				echo "</th>";
				echo "<th scope=\"col\" class=\"manage-column\">";
						echo $wpbusdirman_actiontext;
						echo "</th>";
						echo "</tr>";
						echo "</tfoot>";
						echo "<tbody>";
						if($wpbusdirman_paidlistings)
						{
							foreach($wpbusdirman_paidlistings as $wpbusdirman_paidlistingsitem)
							{
								$bfn=get_post_meta($wpbusdirman_paidlistingsitem, "buyerfirstname", true);
								$bln=get_post_meta($wpbusdirman_paidlistingsitem, "buyerlastname", true);
								$pflagged=get_post_meta($wpbusdirman_paidlistingsitem, "paymentflag", true);
								$pstat=get_post_meta($wpbusdirman_paidlistingsitem, "paymentstatus", true);
								if(!isset($pflagged) || empty($pflagged)){$pflagged="None";}
								$pemail=get_post_meta($wpbusdirman_paidlistingsitem, "payeremail", true);
								if(!isset($pemail) || empty($pemail)){$pemail="Unavailable";}


								echo "<tr><td><a href=\"";
								echo get_permalink($wpbusdirman_paidlistingsitem);
								echo "\">".get_the_title($wpbusdirman_paidlistingsitem)."</a></td>";
								echo "<td>$wpbusdirman_paidlistingsitem</td>";
								echo "<td>".get_post_meta($wpbusdirman_paidlistingsitem, "paymentstatus", true)."</td>";
								if($wpbusdirman_haspaypalmodule == 1)
								{
									echo "<td>$pflagged</td>";
								}
								echo "<td>".get_post_meta($wpbusdirman_paidlistingsitem, "paymentgateway", true)."</td>";
								echo "<td>$bfn $bln</td>";
								echo "<td>$pemail</td>";

								echo "<td>";
								_e("Set as","WPBDM");
								echo ": ";
								if(isset($pstat) && !empty($pstat) && ($pstat != 'paid'))
								{
									echo "<a href=\"?page=wpbdman_c5&action=setaspaid&id=$wpbusdirman_paidlistingsitem\">";
									_e("Paid","WPBDM");
									echo "</a>";
								}
								 echo "<a href=\"?page=wpbdman_c5&action=setasnotpaid&id=$wpbusdirman_paidlistingsitem\">";
								_e("Not paid","WPBDM");
								echo "</a>";
								echo "</td></tr>";
							}
						}

					}


		echo "</tbody></table>";
		echo '<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>';
		echo '<script src="/wp-content/plugins/wp-business-directory-manager/jquery.tablesorter.min.js" type="text/javascript"></script>';
		echo '<script type="text/javascript">';
		echo 'jQuery.noConflict();';
		echo 'jQuery(document).ready(function(){ jQuery("#sortable").tablesorter({sortList: [[0,0], [1,0]]});  });';
		echo '</script>';
		echo '<style> th.header {cursor: pointer;}</style>';
	}

	wpbusdirman_admin_foot();

}


function wpbusdirman_payment_thankyou()
{
	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	$wpbusdirman_payment_thankyou_message=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_37'];

	echo "<h3>";
	_e("Listing Sumitted","WPBDM");
	echo "</h3>";
	if(isset($wpbusdirman_payment_thankyou_message) && !empty($wpbusdirman_payment_thankyou_message))
	{
		echo "<p>$wpbusdirman_payment_thankyou_message</p>";
	}

}

function wpbusdirmanfindpage($shortcode) {
global $wpdb,$table_prefix;
$myreturn=false;

	$query="SELECT count(post_name) FROM {$table_prefix}posts WHERE post_content='$shortcode' AND post_type='page'";
	if (!($res=@mysql_query($query))) {trigger_error(mysql_error(),E_USER_ERROR);}
	if (mysql_num_rows($res) && mysql_result($res,0,0)) {
		$myreturn=true;
	}
	return $myreturn;
}

function wpbusdirmanmakepagemain($wpbdmpagename,$wpbdmpagecontent)
{

	$wpbusdirman_gpid='';
	if(!(wpbusdirmanfindpage($wpbdmpagecontent)))
	{

		// Create the main business directory page
		  $wpbusdirman_gpid=wp_insert_post(  $wpbusdirman_my_page );

			$wpbusdirman_gpid = wp_insert_post( array(
			'post_author'	=> 1,
			'post_title'	=> $wpbdmpagename,
			'post_content'	=> $wpbdmpagecontent,
			'post_status' 	=> 'publish',
			'post_type' 	=> 'page',
		));
  	}

  	return $wpbusdirman_gpid;

}

function wpbusdirmanmakepage($wpbusdirman_gpid,$wpbdmpagename,$wpbdmpagecontent)
{
	if(!(wpbusdirmanfindpage($wpbdmpagecontent)))
	{

		// Create the child pages
		if(!isset($wpbusdirman_gpid) || empty($wpbusdirman_gpid)){$wpbusdirman_gpid=wpbusdirman_gpid();}

			$wpbusdirman_gpid = wp_insert_post( array(
			'post_author'	=> 1,
			'post_title'	=> $wpbdmpagename,
			'post_content'	=> $wpbdmpagecontent,
			'post_status' 	=> 'publish',
			'post_type' 	=> 'page',
			'post_parent' => $wpbusdirman_gpid,
		));
	}

}

function wpbusdirman_sticky_payment_thankyou()
{

	echo "<h3>";
	_e("Listing Upgrade Payment Status","WPBDM");
	echo "</h3>";
	echo "<p>";
	_e("Thank you for your payment. Your listing upgrade request and payment notification have been sent. Contact the administrator if your listing is not upgraded within 24 hours.","WPBDM");
	echo "</p>";

}


function wpbusdirman_listings_expirations()
{
	global $wpbusdirman_gpid,$permalinkstructure,$nameofsite,$thisadminemail,$wpbdmposttypecategory,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();


		$wpbusdirman_myterms = get_terms($wpbdmposttypecategory, 'orderby=name&hide_empty=0');

		if($wpbusdirman_myterms)
		{
			foreach($wpbusdirman_myterms as $wpbusdirman_myterm)
			{
				$wpbusdirman_postcatitems[]=$wpbusdirman_myterm->term_id;
			}
		}

		if($wpbusdirman_postcatitems)
		{
			foreach($wpbusdirman_postcatitems as $wpbusdirman_postcatitem)
			{
				$args = array(
					'post_status' => 'publish',
					'meta_key' => 'lengthofterm',
					'post_type' => $wpbdmposttype,
					'meta_compare=>meta_value=0'
					);

				$wpbusdirman_catcat = get_posts($args);
				if ($wpbusdirman_catcat)
				{
					foreach ($wpbusdirman_catcat as $wpbusdirman_cat)
					{
						$wpbusdirman_postsposts[]=$wpbusdirman_cat->ID;

					}
				}
			}
		}


			if(!empty($wpbusdirman_postsposts))
			{

				foreach($wpbusdirman_postsposts as $listingwithtermlengthset)
				{

					$wpbusdirmantermlength=get_post_meta($listingwithtermlengthset, "lengthofterm", true);


						$wpbusdirmanpostdataarr=get_post( $listingwithtermlengthset );
						$wpbusdirmanpoststartdatebase=$wpbusdirmanpostdataarr->post_date;
						$wpbusdirmanpostauthorid=$wpbusdirmanpostdataarr->post_author;
						$wpbusdirmanpostauthoremail=get_the_author_meta( 'user_email', $wpbusdirmanpostauthorid );
						$wpbusdirmanstartdate = strtotime($wpbusdirmanpoststartdatebase);
						$wpbusdirmanexpiredate= date('Y-m-d', strtotime('+'.$wpbusdirmantermlength.' days', $wpbusdirmanstartdate));
						$wpbusdirmanlistingtitle=get_the_title($listingwithtermlengthset);
						$todaysdatestart=date('Y-m-d');
						$wpbusdirmantodaysdate=strtotime($todaysdatestart);

						//print_r($wpbusdirman_catcat);
						//print_r($wpbusdirman_postcatitems);
						//print_r($listingswithtermlengthset);
						//print_r($wpbusdirman_postsposts);
						//echo "Term Length: $wpbusdirmantermlength | Listing start date: $wpbusdirmanpoststartdatebase | expires: $wpbusdirmanexpiredate | Today's date: $todaysdatestart $wpbusdirmantodaysdate<br/>";
						//die;


						$wpbusdirmanexpiredatestrt = strtotime($wpbusdirmanexpiredate);
						if ($wpbusdirmanexpiredatestrt < $wpbusdirmantodaysdate)
						{
							// Ad has expired so change status and send email
							$wpbusdirman_my_expired_post = array();
							$wpbusdirman_my_expired_post['ID'] = $listingwithtermlengthset;
							$wpbusdirman_my_expired_post['post_status'] = 'wpbdmexpired';

							// Update the post into the database
							  wp_update_post( $wpbusdirman_my_expired_post );
							  // Email listing owner about the expiration

							$listingexpirationtext=__("has expired","WPBDM");


							$headers =	"MIME-Version: 1.0\n" .
									"From: $nameofsite <$thisadminemail>\n" .
									"Reply-To: $thisadminemail\n" .
									"Content-Type: text/plain; charset=\"" . get_option('blog_charset') . "\"\n";

							$subject = "[" . get_option( 'blogname' ) . "] " . wp_kses( $wpbusdirmanlistingtitle, array() );

							$time = date_i18n( __('l F j, Y \a\t g:i a'), current_time( 'timestamp' ) );


							if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_38'] == "yes")
							{
								$wpbusdirmanrenewlistingtext="To renew your listing click the link below";
								$wpbusdirmanrenewlistinglink=get_permalink($wpbusdirman_gpid);
								if(isset($permalinkstructure) && !empty($permalinkstructure))
								{
									$wpbusdirmanrenewlistinglink.="?do=renewlisting&id=$listingwithtermlengthset";
								}
								else
								{
									$wpbusdirmanrenewlistinglink.="&do=renewlisting&id=$listingwithtermlengthset";
								}

							}
							else
							{
								$wpbusdirmanrenewlistingtext="";
								$wpbusdirmanrenewlistinglink="";
							}

							$message = "
							$wpbusdirmanlistingtitle $listingexpirationtext

							$wpbusdirmanrenewlistingtext

							$wpbusdirmanrenewlistinglink

							Time: $time

							";

							@wp_mail( $wpbusdirmanpostauthoremail, $subject, $message, $headers );
						}

					}
			}
}

function wpbusdirman_renew_listing($wpbdmidtorenew,$wpbusdirman_permalink,$neworedit)
{
	global $wpbusdirman_haspaypalmodule,$wpbusdirman_hastwocheckoutmodule,$wpbusdirman_hasgooglecheckoutmodule;

	if(isset($wpbdmidtorenew) && !empty($wpbdmidtorenew))
	{

		$wpbdmrenewingtitle=get_the_title($wpbdmidtorenew);
		$wpbdmrenewingcat=get_the_category($wpbdmidtorenew);

		if($wpbdmrenewingcat)
		{
			foreach($wpbdmrenewingcat as $wpbdmrenewingcategory)
			{
				$wpbdmrenewingcatID=$wpbdmrenewingcategory->cat_ID;
			}
		}

		if(( $wpbusdirman_haspaypalmodule == 1) || ($wpbusdirman_hastwocheckoutmodule == 1) || ($wpbusdirman_hasgooglecheckoutmodule == 1))
		{
			echo "<h3>";
			_e("Renew Listing","WPBDM");
			echo "</h3>";

			$wpbusdirman_fee_to_pay_li=wpbusdirman_feepay_configure($wpbdmrenewingcatID);

			echo "<p>";
			_e("You are about to renew","WPBDM");
			echo ": $wpbdmrenewingtitle";
			echo "</p>";

			if(isset($wpbusdirman_fee_to_pay_li) && !empty($wpbusdirman_fee_to_pay_li))
			{
				global $wpbusdirman_gpid,$permalinkstructure;
				$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);

				$wpbusdirman_fee_to_pay="<table cellpadding=\"0\" cellspacing=\"0\" id=\"wpbusdirmanpaymentoptionslist\">";
				$wpbusdirman_fee_to_pay.=$wpbusdirman_fee_to_pay_li;
				$wpbusdirman_fee_to_pay.="</table>";
				$neworedit='new';

				echo "<label>";
				_e("Select Listing Payment Option","WPBDM");
				echo "</label><br/>";
				echo "<p>";
				echo "<form method=\"post\" action=\"$wpbusdirman_permalink\">";
				echo "<input type=\"hidden\" name=\"action\" value=\"renewlisting_step_2\"/>";
				echo "<input type=\"hidden\" name=\"wpbusdirmanlistingpostid\" value=\"$wpbdmidtorenew\"/>";
				echo "<input type=\"hidden\" name=\"wpbusdirmanpermalink\" value=\"$wpbusdirman_permalink\"/>";
				echo "<input type=\"hidden\" name=\"neworedit\" value=\"$neworedit\"/>";
				echo $wpbusdirman_fee_to_pay;
				echo "<br/><input type=\"submit\" class=\"insubmitbutton\" value=\"";
				_e("Next","WPBDM");
				echo "\"/>";
				echo "</form>";
				echo "</p>";
			}
		}
	}
	else
	{
		_e("There was no ID supplied. Cannot complete renewal. Please contact administrator","WPBDM");
	}

}

function wpbusdirman_viewlistings()
{
	global $wpbusdirman_plugin_path;
	wpbusdirman_menu_buttons();

			if(file_exists(TEMPLATEPATH . '/single/wpbusdirman-index-listings.php')){
			include TEMPLATEPATH . '/single/wpbusdirman-index-listings.php';
			} elseif(file_exists(STYLESHEETPATH . '/single/wpbusdirman-index-listings.php')){
			include STYLESHEETPATH . '/single/wpbusdirman-index-listings.php';
			} elseif(file_exists(WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php')){
			include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php';
			}else {include WPBUSDIRMAN_TEMPLATES_PATH . '/wpbusdirman-index-listings.php';	}
}


//Display the listing thumbnail
function wpbusdirman_display_the_thumbnail()
{

	global $wpbdmimagesurl,$post,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_11'] == "yes")
	{
		$tpostimg2=get_post_meta($post->ID, "image", true);
		if(isset($tpostimg2) && !empty($tpostimg2))
		{
			$wpbusdirman_theimg2=$tpostimg2;
		}
		else
		{
			$wpbusdirman_theimg2='';
		}

		if(isset($wpbusdirman_theimg2) && !empty($wpbusdirman_theimg2))
		{?>
			<span style="float:left;margin-right:10px;"><a href="<?php the_permalink();?>">
			<img class="wpbdmthumbs" src="<?php echo $wpbdmimagesurl;?>/thumbnails/<?php echo $wpbusdirman_theimg2;?>" alt="<?php the_title();?>" title="<?php the_title();?>" border="0">
			</a></span>
		<?php }
		else
		{
			$wpbdmusedef=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_39'];
			$wpbdmimgwidth=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_17'];
			if(!isset($wpbdmimgwidth) || empty($wpbdmimgwidth)){$wpbdmimgwidth="375";}
			if(!isset($wpbdmusedef) || empty($wpbdmusedef) || ($wpbdmusedef == 1))
			{?>
				<span style="float:left;margin-right:10px;"><a href="<?php the_permalink();?>">
				<img class="wpbdmthumbs" src="<?php echo $wpbusdirman_imagesurl;?>/default.png" width="<?php echo $wpbdmimgwidth;?>" alt="<?php the_title();?>" title="<?php the_title();?>" border="0"></a>
				</span>
			<?php }
		}
	}
}

// Configure the title for the category Page
function wpbusdirman_catpage_title()
{
	global $post,$wpbdmposttypecategory;
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
	//print_r($term);

	//$mywpbdmcat=get_the_term_list( $post->ID, $wpbdmposttypecategory, '', ', ', '' );
?>
	<h1><?php echo $term->name;?></h1>
<?php }


// Submit Listing and Directory menu buttons
function wpbusdirman_menu_buttons()
{
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);?>

	<div id="wpbusdirmancats">
	<?php wpbusdirman_menu_button_submitlisting();?>
	<?php wpbusdirman_menu_button_directory();?>
	</div>
	<div style="clear:both;"></div>
	<br/>
<?php
}


function wpbusdirman_menu_button_submitlisting()
{
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);?>

	<form method="post" action="<?php echo $wpbusdirman_permalink;?>"><input type="hidden" name="action" value="submitlisting"><input type="submit" class="submitlistingbutton" value="<?php _e("Submit A Listing","WPBDM");?>"></form>

<?php
}

function wpbusdirman_menu_button_viewlistings()
{
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);?>

	<form method="post" action="<?php echo $wpbusdirman_permalink;?>"><input type="hidden" name="action" value="viewlistings"><input type="submit" class="viewlistingsbutton"  style="margin-right:10px;" value="<?php _e("View Listings","WPBDM");?>"></form>

<?php
}

function wpbusdirman_menu_button_directory()
{
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);?>

	<form method="post" action="<?php echo $wpbusdirman_permalink;?>"><input type="submit" class="viewlistingsbutton" style="margin-right:10px;" value="<?php _e("Directory","WPBDM");?>"></form>

<?php
}

function wpbusdirman_menu_button_editlisting()
{
	global $post;
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);

		if(is_user_logged_in())
		{
			global $current_user;
			get_currentuserinfo();
			$wpbusdirmanloggedinuseremail=$current_user->user_email;
			$wpbusdirmanauthoremail=get_the_author_meta('user_email');

			if($wpbusdirmanloggedinuseremail == $wpbusdirmanauthoremail)
			{?>

				<form method="post" action="<?php echo $wpbusdirman_permalink;?>"><input type="hidden" name="action" value="editlisting"><input type="hidden" name="wpbusdirmanlistingid" value="<?php echo $post->ID;?>"><input type="submit" class="editlistingbutton" value="<?php _e("Edit Listing","WPBDM");?>"></form>
			<?php
			}
		}
}

function wpbusdirman_menu_button_upgradelisting()
{
	global $post,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);

		if(is_user_logged_in())
		{
			global $current_user;
			get_currentuserinfo();
			$wpbusdirmanloggedinuseremail=$current_user->user_email;
			$wpbusdirmanauthoremail=get_the_author_meta('user_email');

			$wpbdmpostissticky=get_post_meta($post->ID, "sticky", $single=true);

			if($wpbusdirmanloggedinuseremail == $wpbusdirmanauthoremail)
			{
				if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_31'] == "yes")
				{
					if( (!isset($wpbdmpostissticky) || empty($wpbdmpostissticky) || ($wpbdmpostissticky == 'not paid')) && ( $post->post_status == 'publish') )
					{
						?>
						<form method="post" action="<?php echo $wpbusdirman_permalink;?>"><input type="hidden" name="action" value="upgradetostickylisting"><input type="hidden" name="wpbusdirmanlistingid" value="<?php echo $post->ID;?>"><input type="submit" class="updradetostickylistingbutton" value="<?php _e("Upgrade Listing","WPBDM");?>"></form>
						<?php
					}
				}
			}
		}
}

function wpbusdirman_list_categories()
{
	global $wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	$wpbdm_hide_empty=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_10'];
	if(isset($wpbdm_hide_empty) && !empty($wpbdm_hide_empty) && ($wpbdm_hide_empty == "yes")){$wpbdm_hide_empty=1;}
	elseif(isset($wpbdm_hide_empty) && !empty($wpbdm_hide_empty) && ($wpbdm_hide_empty == "no")){$wpbdm_hide_empty=0;}

	$wpbdm_show_count=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_9'];
	if(isset($wpbdm_show_count) && !empty($wpbdm_show_count) && ($wpbdm_show_count == "yes")){$wpbdm_show_count=1;}
	elseif(isset($wpbdm_show_count) && !empty($wpbdm_show_count) && ($wpbdm_show_count == "no")){$wpbdm_show_count=0;}

	$wpbdm_show_parent_categories_only=$wpbusdirmanconfigoptionsprefix."_settings_config_48";
	if(isset($wpbdm_show_parent_categories_only) && !empty($wpbdm_show_parent_categories_only) && ($wpbdm_show_parent_categories_only == "yes")){$wpbdm_show_parent_categories_only=0;}
	elseif(isset($wpbdm_show_parent_categories_only) && !empty($wpbdm_show_parent_categories_only) && ($wpbdm_show_parent_categories_only == "no")){$wpbdm_show_parent_categories_only=1;}
	else {$wpbdm_show_parent_categories_only=1;}

$taxonomy     = $wpbdmposttypecategory;
$orderby      = $wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_7'];
$show_count   = $wpbdm_show_count;      // 1 for yes, 0 for no
$pad_counts   = 0;      // 1 for yes, 0 for no
$hierarchical = $wpbdm_show_parent_categories_only;      // 1 for yes, 0 for no
$title        = '';
$order=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_8'];
$hide_empty=$wpbdm_hide_empty;

$args = array(
  'taxonomy'     => $taxonomy,
  'orderby'      => $orderby,
  'show_count'   => $show_count,
  'pad_counts'   => $pad_counts,
  'hierarchical' => $hierarchical,
  'title_li'     => $title,
'order' =>$order,
'hide_empty' => $hide_empty

);


	wp_list_categories($args);


}

function wpbusdirman_dropdown_categories()
{
	global $post,$wpbusdirmanconfigoptionsprefix,$wpbdmposttypecategory;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);

	$wpbdm_hide_empty=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_10'];
	if(isset($wpbdm_hide_empty) && !empty($wpbdm_hide_empty) && ($wpbdm_hide_empty == "yes")){$wpbdm_hide_empty=1;}
	elseif(isset($wpbdm_hide_empty) && !empty($wpbdm_hide_empty) && ($wpbdm_hide_empty == "no")){$wpbdm_hide_empty=0;}

	$wpbdm_show_count=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_9'];
	if(isset($wpbdm_show_count) && !empty($wpbdm_show_count) && ($wpbdm_show_count == "yes")){$wpbdm_show_count=1;}
	elseif(isset($wpbdm_show_count) && !empty($wpbdm_show_count) && ($wpbdm_show_count == "no")){$wpbdm_show_count=0;}

	$wpbdm_show_parent_categories_only=$wpbusdirmanconfigoptionsprefix."_settings_config_48";
	if(isset($wpbdm_show_parent_categories_only) && !empty($wpbdm_show_parent_categories_only) && ($wpbdm_show_parent_categories_only == "yes")){$wpbdm_show_parent_categories_only=0;}
	elseif(isset($wpbdm_show_parent_categories_only) && !empty($wpbdm_show_parent_categories_only) && ($wpbdm_show_parent_categories_only == "no")){$wpbdm_show_parent_categories_only=1;}
	else {$wpbdm_show_parent_categories_only=1;}



	$wpbusdirman_postvalues=get_the_terms(get_the_ID(), $wpbdmposttypecategory);

	if($wpbusdirman_postvalues)
	{
		foreach($wpbusdirman_postvalues as $wpbusdirman_postvalue)
		{
			$wpbusdirman_field_value_selected=$wpbusdirman_postvalue->term_id;
		}
	}
	?>

	<form action="<?php bloginfo('url'); ?>" method="get">
	<?php
	$taxonomies = array($wpbdmposttypecategory);
	$args = array('echo'=>0,'show_option_none'=>$wpbusdirman_selectcattext,'orderby'=>$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_7'],'selected'=>$wpbusdirman_field_value_selected,'order'=>$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_8'],'hide_empty'=>$wpbdm_hide_empty,'hierarchical'=>$wpbdm_show_parent_categories_only);
	$select = get_terms_dropdown($taxonomies, $args);

	$select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
	echo $select;
	?>
		<noscript><div><input type="submit" value="Näytä" /></div></noscript>
	</form>
<?php
}

function get_terms_dropdown($taxonomies, $args){
global $wpbdmposttypecategory;
	$myterms = get_terms($taxonomies, $args);
	$output ="<select name='".$wpbdmposttypecategory."'>";

	if($myterms)
	{
		foreach($myterms as $term){
			$root_url = get_bloginfo('url');
			$term_taxonomy=$term->taxonomy;
			$term_slug=$term->slug;
			$term_name =$term->name;
			$link = $term_slug;
			$output .="<option value='".$link."'>".$term_name."</option>";
		}
	}

	$output .="</select>";
	return $output;
}


function wpbusdirman_catpage_query()
{
	global $wpbdmposttype,$wpbdmposttypecategory;
	$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

	$args=array(
	  $wpbdmposttypecategory => $term->name,
	  'post_type' => $wpbdmposttype,
	  'post_status' => 'publish',
	  'posts_per_page' => -1,
	'paged'=>$paged,
	'orderby'=>'meta_key=sticky&meta_value=approved',
	  'caller_get_posts'=> 1
	);
	$my_query = null;
	$my_query = new WP_Query($args);

	//query_posts($args);
	//$wpbusdirman_stickyids=array();
}

function wpbusdirman_indexpage_query()
{
	global $wpbdmposttype;
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$args=array('post_status' => 'publish','post_type' => $wpbdmposttype,'paged'=>$paged,orderby=>'meta_key=sticky&meta_value=approved' );
	query_posts($args);
	$wpbusdirman_stickyids=array();

}

// Display the listing fields in excerpt view
function wpbusdirman_display_the_listing_fields()
{?>


	<?php global $post,$wpbdmposttypecategory,$wpbdmposttypetags;
	$wpbusdirman_field_vals=wpbusdirman_retrieveoptions($whichoptions='wpbusdirman_postform_field_label_');

	if($wpbusdirman_field_vals)
	{
		foreach($wpbusdirman_field_vals as $wpbusdirman_field_val):

		if(get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val) == 'yes'):

			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);


			if($wpbusdirman_field_association == 'title'){?>
			<p><label><?php echo $wpbusdirman_field_label; ?></label>:
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
			<?php } elseif($wpbusdirman_field_association == 'category'){?>
			<p><label><?php echo $wpbusdirman_field_label; ?></label>:
			<?php echo get_the_term_list( $post->ID, $wpbdmposttypecategory, '', ', ', '' );?></p>
			<?php } elseif($wpbusdirman_field_association == 'meta'){
			$wpbusdirman_field_value=get_post_meta($post->ID, $wpbusdirman_field_label, $single = true);
			$wpbusdirman_field_value=preg_replace("/(http:\/\/[^\s]+)/","<a rel=\"no follow\" href=\"\$1\">\$1</a>",$wpbusdirman_field_value);
			$wpbusdirman_field_value=str_replace("\t",", ",$wpbusdirman_field_value);

			if(isset($wpbusdirman_field_value) && !empty($wpbusdirman_field_value) && (!wpbusdirman_isValidEmailAddress($wpbusdirman_field_value))){
			?>
			<p><label><?php echo $wpbusdirman_field_label; ?></label>:
			<?php echo $wpbusdirman_field_value;?>
			</p>
			<?php } ?>
			<?php }	elseif($wpbusdirman_field_association == 'excerpt'){?>
			<?php if(has_excerpt($post->ID)){?>
			<p><label><?php echo $wpbusdirman_field_label; ?></label>:
			<a href="<?php the_permalink(); ?>"><?php the_excerpt(); ?></a></p>
			<?php } ?>
			<?php } elseif($wpbusdirman_field_association == 'description'){?>
			<p><label><?php echo $wpbusdirman_field_label; ?></label>:
			<a href="<?php the_permalink(); ?>"><?php the_content(' '); ?></a></p>
			<?php } elseif($wpbusdirman_field_association == 'tags'){?>
			<?php if(get_the_term_list( $post->ID, $wpbdmposttypetags, '', ', ', '' ))
			{?>
			<p><label><?php echo $wpbusdirman_field_label; ?></label>:
			<?php echo get_the_term_list( $post->ID, $wpbdmposttypetags, '', ', ', '' );?>
			</p>
			<?php }

			}
		endif;
		endforeach;
	}

?>
<?php
}

function wpbusdirman_view_edit_delete_listing_button()
{
	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);?>

		<div style="clear:both;"></div>
		<div class="vieweditbuttons">
		<div class="vieweditbutton"><a href="<?php the_permalink(); ?>"><?php _e("View","WPBDM");?></a></div>

		<?php if(is_user_logged_in())
		{
			global $current_user;
			get_currentuserinfo();
			$wpbusdirmanloggedinuseremail=$current_user->user_email;
			$wpbusdirmanauthoremail=get_the_author_meta('user_email');

			if($wpbusdirmanloggedinuseremail == $wpbusdirmanauthoremail)
			{?>

		<div class="vieweditbutton"><form method="post" action="<?php echo $wpbusdirman_permalink;?>"><input type="hidden" name="action" value="editlisting"/><input type="hidden" name="wpbusdirmanlistingid" value="<?php echo get_the_id();?>"/><input type="submit" value="<?php _e("Edit","WPBDM");?>"/></form></div>
		<div class="vieweditbutton"><form method="post" action="<?php echo $wpbusdirman_permalink;?>"><input type="hidden" name="action" value="deletelisting"/><input type="hidden" name="wpbusdirmanlistingid" value="<?php echo get_the_id();?>"/><input type="submit" value="<?php _e("Delete","WPBDM");?>"/></form></div>

		<?php } }?>
	</div>
<?php
}

function wpbusdirman_display_excerpt()
{ 	$wpbusdirman_gpid=wpbusdirman_gpid();
	$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);?>
	<div id="wpbdmlistings">
	<div style="float:left;margin-right:20px;"><?php wpbusdirman_display_the_thumbnail();?></div>
	<?php wpbusdirman_display_the_listing_fields(); ?>
	<?php wpbusdirman_view_edit_delete_listing_button();?>
	<div style="clear:both;"></div>
	</div>
<?php }

function wpbusdirman_display_ac()
{
	global $wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();
	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_34'] == "yes"){?>
	<div class="wpbdmac">Directory powered by <a href="http://wpbusinessdirectorymanager.themestown.com/">WP Business Directory Manager</a> available via <a href="http://www.themestown.com">Themes Town</a></div>
<?php }
}

function wpbusdirman_display_main_image()
{
	global $post,$wpbdmimagesurl,$wpbusdirman_imagesurl,$wpbusdirmanconfigoptionsprefix;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_11'] == "yes")
	{
		$usingdefault=0;
			$wpbusdirmanpostimages=get_post_meta($post->ID, "thumbnail", $single=false);
			$wpbusdirmanpostimagestotal=count($wpbusdirmanpostimages);
			if($wpbusdirmanpostimagestotal >=1){$wpbusdirmanpostimagefeature=$wpbusdirmanpostimages[0];} else {$wpbusdirmanpostimagefeature='';}
			$wpbdmusedef=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_39'];
			if(!isset($wpbdmusedef) || empty($wpbdmusedef) || ($wpbdmusedef == 1)){ if(!isset($wpbusdirmanpostimagefeature) || empty($wpbusdirmanpostimagefeature)){$usingdefault=1;$wpbusdirmanpostimagefeature=$wpbusdirman_imagesurl.'/default-image-big.gif';}}
			if(isset($wpbusdirmanpostimagefeature) && !empty($wpbusdirmanpostimagefeature))
			{?>

				<a href="<?php the_permalink();?>">
				<img src="<?php if($usingdefault != 1){?><?php echo $wpbdmimagesurl; ?>/<?php }?><?php echo $wpbusdirmanpostimagefeature;?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" border="0"></a><br/>

			<?php }
	}
}

function wpbusdirman_display_extra_thumbnails()
{
	global $post,$wpbdmimagesurl;

			$wpbusdirmanpostimages=get_post_meta($post->ID, "thumbnail", $single=false);
			$wpbusdirmanpostimagestotal=count($wpbusdirmanpostimages);
			if($wpbusdirmanpostimagestotal >=1){$wpbusdirmanpostimagefeature=$wpbusdirmanpostimages[0];} else {$wpbusdirmanpostimagefeature='';}

		if($wpbusdirmanpostimagestotal > 1)
		{ ?>
			<div class="extrathumbnails">
			<?php foreach($wpbusdirmanpostimages as $wpbusdirmanpostimage)
			{

				if(!($wpbusdirmanpostimage == $wpbusdirmanpostimagefeature))
				{?>
					<a class="thickbox" href="<?php echo $wpbdmimagesurl; ?>/<?php echo $wpbusdirmanpostimage;?>">
					<img class="wpbdmthumbs" src="<?php echo $wpbdmimagesurl; ?>/thumbnails/<?php echo $wpbusdirmanpostimage;?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>" border="0"></a>
				<?php
				}
			}?>
			</div>
			<?php
		}

}

function wpbusdirman_single_listing_details()
{
		global $post,$wpbusdirman_gpid,$wpbdmimagesurl,$wpbusdirman_imagesurl,$wpbusdirmanconfigoptionsprefix;
		$wpbusdirman_config_options=get_wpbusdirman_config_options();

		$wpbusdirman_permalink=get_permalink($wpbusdirman_gpid);

		if(is_user_logged_in())
		{
			global $current_user;
			get_currentuserinfo();
			$wpbusdirmanloggedinuseremail=$current_user->user_email;
			$wpbusdirmanauthoremail=get_the_author_meta('user_email');

			$wpbdmpostissticky=get_post_meta($post->ID, "sticky", $single=true);
			if($wpbusdirmanloggedinuseremail == $wpbusdirmanauthoremail)
			{
				?>

				<div class="editlistingsingleview">
				<?php wpbusdirman_menu_button_editlisting();?>

				<?php wpbusdirman_menu_button_upgradelisting(); ?>
				</div>
				<div style="clear:both;"></div>
				<?php
			}

		}

			 if(isset($wpbdmpostissticky) && !empty($wpbdmpostissticky) && ($wpbdmpostissticky  == 'approved') ){?>
			<span class="featuredlisting"><img src="<?php echo $wpbusdirman_imagesurl;?>/featuredlisting.png" alt="<?php _e("Featured Listing","WPBDM");?>" border="0" title="<?php the_title(); ?>"></span>
			<?php }

			//Display the listing title
			wpbusdirman_the_listing_title();

			//Display the listing category
			wpbusdirman_the_listing_category();

			//Display the meta items
			wpbusdirman_the_listing_meta('single');

			//Display the listing excerpt
			wpbusdirman_the_listing_excerpt();

			//Display the listing excerpt
			wpbusdirman_the_listing_content();

			//Display the listing excerpt
			wpbusdirman_the_listing_tags();

			wpbusdirman_contactform($wpbusdirman_permalink,$post->ID,$commentauthorname='',$commentauthoremail='',$commentauthorwebsite='',$commentauthormessage='',$wpbusdirman_contact_form_errors='');
}



function wpbusdirman_the_listing_title()
{

	global $wpbusdirman_field_vals_pfl;

	if($wpbusdirman_field_vals_pfl)
	{
		foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val):

		$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
		$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);

		if($wpbusdirman_field_association == 'title'){?>
		<p><label><?php echo $wpbusdirman_field_label; ?></label>:
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></p>
		<?php }

		endforeach;
	}
}


function wpbusdirman_the_listing_tags()
{
		global $wpbdmposttypetags,$wpbusdirman_field_vals_pfl;

		if($wpbusdirman_field_vals_pfl)
		{
			foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val):

			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);

			if($wpbusdirman_field_association == 'tags')
			{
				if(get_the_term_list( $post->ID, $wpbdmposttypetags, '', ', ', '' ))
				{
					?>
					<p><label><?php echo $wpbusdirman_field_label; ?></label>:
					<?php echo get_the_term_list( $post->ID, $wpbdmposttypetags, '', ', ', '' );?>
					</p>
					<?php
				}
			}

			endforeach;
		}
	}

function wpbusdirman_the_listing_excerpt()
{

	global $wpbusdirman_field_vals_pfl;

	if($wpbusdirman_field_vals_pfl)
	{
		foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val):

		$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
		$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);

			if($wpbusdirman_field_association == 'excerpt')
			{
				if(has_excerpt($post->ID))
				{
					?>
						<p><label><?php echo $wpbusdirman_field_label; ?></label>:
						<?php the_excerpt(); ?>
						</p>
						<?php
					}
				}
		endforeach;
	}
}

function wpbusdirman_the_listing_content()
{
		global $wpbusdirman_field_vals_pfl;

		if($wpbusdirman_field_vals_pfl)
		{
			foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val):

			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);

				if($wpbusdirman_field_association == 'description')
				{
					?>
					<p><label><?php echo $wpbusdirman_field_label; ?></label>:
					<?php the_content(' '); ?>
					</p>
					<?php
				}
			endforeach;
		}
}

function wpbusdirman_the_listing_category()
{
		global $wpbdmposttypecategory,$wpbusdirman_field_vals_pfl;

		if($wpbusdirman_field_vals_pfl)
		{
			foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val):

			$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
			$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);

			if($wpbusdirman_field_association == 'category')
			{
				?>
				<p><label><?php echo $wpbusdirman_field_label; ?></label>:
				<?php echo get_the_term_list( $post->ID, $wpbdmposttypecategory, '', ', ', '' );?>
				</p>
				<?php
			}

			endforeach;
		}
}

function wpbusdirman_the_listing_meta($excerptorsingle)
{
	global $post,$wpbusdirmanconfigoptionsprefix,$wpbusdirman_field_vals_pfl;
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

	$overrideemailblocking=$wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_45'];

	if($wpbusdirman_field_vals_pfl)
	{
		foreach($wpbusdirman_field_vals_pfl as $wpbusdirman_field_val):


		$wpbusdirman_field_label=get_option('wpbusdirman_postform_field_label_'.$wpbusdirman_field_val);
		$wpbusdirman_field_association=get_option('wpbusdirman_postform_field_association_'.$wpbusdirman_field_val);

				if($wpbusdirman_field_association == 'meta')
				{
					$wpbusdirman_field_value=get_post_meta(get_the_ID(), $wpbusdirman_field_label, $single = true);
					$wpbusdirman_field_value=preg_replace("/(http:\/\/[^\s]+)/","<a rel=\"no follow\" href=\"\$1\">\$1</a>",$wpbusdirman_field_value);

					$wpbusdirman_field_value=str_replace("\t",", ",$wpbusdirman_field_value);

					if( isset($overrideemailblocking) && !empty($overrideemailblocking) && ($overrideemailblocking == "yes") )
					{
							if( isset($wpbusdirman_field_value) && !empty($wpbusdirman_field_value) )
							{
								if(isset($excerptorsingle) && !empty($excerptorsingle) && ($excerptorsingle == 'excerpt')){
								if(get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val) == 'yes'){
								?>
								<p><label><?php echo $wpbusdirman_field_label; ?></label>:
								<?php echo $wpbusdirman_field_value;?>
								</p>
								<?php
								}}else {?>
								<p><label><?php echo $wpbusdirman_field_label; ?></label>:
								<?php echo $wpbusdirman_field_value;?>
								</p>
								<?php }

							}
					}
					elseif($overrideemailblocking == "no")
					{
						if( isset($wpbusdirman_field_value) && !empty($wpbusdirman_field_value) &&  !wpbusdirman_isValidEmailAddress($wpbusdirman_field_value) )
						{
							if(isset($excerptorsingle) && !empty($excerptorsingle) && ($excerptorsingle == 'excerpt')){
							if(get_option('wpbusdirman_postform_field_showinexcerpt_'.$wpbusdirman_field_val) == 'yes'){

							?>
								<p><label><?php echo $wpbusdirman_field_label; ?></label>:
								<?php echo $wpbusdirman_field_value;?>
								</p>
							<?php
							}}else {?>
								<p><label><?php echo $wpbusdirman_field_label; ?></label>:
								<?php echo $wpbusdirman_field_value;?>
								</p>
							<?php }
						}
					}
				}
		endforeach;
	}
}

function wpbusdirman_latest_listings($numlistings)
{
	global $wpbdmposttype;
	$wpbdmpostheadline='';

		$args = array(
		'post_status' => 'publish',
		'post_type' => $wpbdmposttype,
		'numberposts' => $numlistings,
		'orderby' => 'date'
		);

		$wpbusdirman_theposts = get_posts($args);

	if($wpbusdirman_theposts)
	{
		foreach($wpbusdirman_theposts as $wpbusdirman_thepost)
		{
			$wpbdmpostheadline.="<li><a href=\"";
			$wpbdmpostheadline.=get_permalink($wpbusdirman_thepost->ID);
			$wpbdmpostheadline.="\">$wpbusdirman_thepost->post_title</a></li>";
		}
	}

	echo $wpbdmpostheadline;
}

function get_wpbusdirman_config_options()
{
	$mywpbusdirman_config_options=array();
	global $wpbusdirmanconfigoptionsprefix;

	$pstandwpbusdirman_config_options=get_option($wpbusdirmanconfigoptionsprefix.'_settings_config');

	if(isset($pstandwpbusdirman_config_options) && !empty($pstandwpbusdirman_config_options))
	{
		foreach ($pstandwpbusdirman_config_options as $pstandoption)
		{
			if(isset($pstandoption['id']) && !empty($pstandoption['id']))
			{
				$mywpbusdirman_config_options[$pstandoption['id']]=$pstandoption['std'];
			}

		}
	}

	return $mywpbusdirman_config_options;
}

function wpbusdirman_config_check_for_wpbusdirman_config_options()
{
	global $wpbusdirmanconfigoptionsprefix,$def_wpbusdirman_config_options,$poststatusoptions,$yesnooptions,$categoryorderoptions,$categorysortoptions;
	$wpbusdirmanconfigoptions=$wpbusdirmanconfigoptionsprefix.'_settings_config';
	$mysavedthemewpbusdirman_config_options=get_option($wpbusdirmanconfigoptions);

		$wpbusdirman_config_options = $mysavedthemewpbusdirman_config_options;

		if (!isset($wpbusdirman_config_options) || empty($wpbusdirman_config_options) || !is_array($wpbusdirman_config_options))
		{
			$wpbusdirman_config_options = $def_wpbusdirman_config_options;

			if($wpbusdirman_config_options)
			{
				foreach ($wpbusdirman_config_options as $optionvalue)
				{
					if(!isset($optionvalue['id']) || empty($optionvalue['id']))
					{
						$optionvalue['id']='';
					}
					if(!isset($optionvalue['wpbusdirman_config_options']) || empty($optionvalue['wpbusdirman_config_options']))
					{
						$optionvalue['wpbusdirman_config_options']='';
					}
					if(!isset($optionvalue['std']) || empty($optionvalue['std']))
					{
						$optionvalue['std']='';
					}

						$setmywpbusdirman_config_options[]=array("name" => $optionvalue['name'],
						"id" => $optionvalue['id'],
						"std" => $optionvalue['std'],
						"type" => $optionvalue['type'],
						"options" => $optionvalue['options']);

				}
			}

			update_option($wpbusdirmanconfigoptions,$setmywpbusdirman_config_options);
		}
}

function wpbusdirman_config_reconcile_options()
{
	global $wpbusdirmanconfigoptionsprefix,$def_wpbusdirman_config_options,$poststatusoptions,$yesnooptions,$categoryorderoptions,$categorysortoptions;
	$wpbusdirmanconfigoptions=$wpbusdirmanconfigoptionsprefix.'_settings_config';
	$wpbusdirman_config_options=get_wpbusdirman_config_options();

			$setmywpbusdirman_config_options=array();

				if($def_wpbusdirman_config_options)
				{
					foreach ($def_wpbusdirman_config_options as $optionvalue)
					{

						if(!isset($optionvalue['id']) || empty($optionvalue['id']))
						{
							$optionvalue['id']='';
						}
						if(!isset($optionvalue['wpbusdirman_config_options']) || empty($optionvalue['wpbusdirman_config_options']))
						{
							$optionvalue['wpbusdirman_config_options']='';
						}
						if(!isset($optionvalue['name']) || empty($optionvalue['name']))
						{
							$optionvalue['name']='';
						}
						if(!isset($optionvalue['std']) || empty($optionvalue['std']))
						{
							$optionvalue['std']='';
						}


						if(isset($wpbusdirman_config_options[$optionvalue['id']]) && !empty($wpbusdirman_config_options[$optionvalue['id']]))
						{
							$savedoptionvalue=$wpbusdirman_config_options[$optionvalue['id']];
						}
						elseif(isset($optionvalue['std']) && !empty($optionvalue['std']))
						{
							$savedoptionvalue=$optionvalue['std'];
						}
						else
						{
							$savedoptionvalue='';
						}
						$setmywpbusdirman_config_options[]=array("name" => $optionvalue['name'],
						"id" => $optionvalue['id'],
						"std" => $savedoptionvalue,
						"type" => $optionvalue['type'],
						"options" => $optionvalue['options']);
					}
				}

				update_option($wpbusdirmanconfigoptions,$setmywpbusdirman_config_options);

}

function wpbusdirman_config_admin() {
global $wpbusdirmanconfigoptionsprefix, $def_wpbusdirman_config_options,$poststatusoptions,$yesnooptions,$categoryorderoptions,$categorysortoptions;

wpbusdirman_config_reconcile_options();

//Begin the saving procedures
	$wpbusdirmanconfigoptions=$wpbusdirmanconfigoptionsprefix.'_settings_config';
	$mysavedthemewpbusdirman_config_options=get_option($wpbusdirmanconfigoptions);

		$wpbusdirman_config_options = $mysavedthemewpbusdirman_config_options;

		if (!isset($wpbusdirman_config_options) || empty($wpbusdirman_config_options) || !is_array($wpbusdirman_config_options))
		{
			$wpbusdirman_config_options = $def_wpbusdirman_config_options;

			if($wpbusdirman_config_options)
			{
				foreach ($wpbusdirman_config_options as $optionvalue)
				{
					if(isset($optionvalue['id']) && !empty($optionvalue['id']))
					{
						$savedoptionvalue=get_option($optionvalue['id']);
						if(!isset($savedoptionvalue) || empty ($savedoptionvalue))
						{
							$savedoptionvalue=$optionvalue['std'];
						}

						$setmywpbusdirman_config_options[]=array("name" => $optionvalue['name'],
						"id" => $optionvalue['id'],
						"std" => $savedoptionvalue,
						"type" => $optionvalue['type'],
						"options" => $optionvalue['options']);

						delete_option($optionvalue['id']);
					}
				}
			}

			update_option($wpbusdirmanconfigoptions,$setmywpbusdirman_config_options);
		}

		if( isset($_REQUEST['action']) && ( 'updatewpbusdirman_config_options' == $_REQUEST['action'] ))
		{
			$myoptionvalue='';

			if($wpbusdirman_config_options)
			{
				foreach ($wpbusdirman_config_options as $optionvalue)
				{

					if(isset($optionvalue['id']) && !empty($optionvalue['id']))
					{
						if( isset( $_REQUEST[ $optionvalue['id'] ] ) )
						{
							$myoptionvalue = $_REQUEST[ $optionvalue['id'] ];
						}
					}

					if(!isset($optionvalue['options']) || empty($optionvalue['options']))
					{
						$optionvalue['options']='';
					}

					if(!isset($optionvalue['id']) || empty($optionvalue['id']))
					{
						$optionvalue['id']='';
					}

					if(!isset($optionvalue['std']) || empty($optionvalue['std'] ))
					{
						$optionvalue['std']='';
					}


					$mywpbusdirman_config_options[]=array("name" => $optionvalue['name'],
					"id" => $optionvalue['id'],
					"std" => $myoptionvalue,
					"type" => $optionvalue['type'],
					"options" => $optionvalue['options']);

				}
			}
				update_option($wpbusdirmanconfigoptions,$mywpbusdirman_config_options);
				$wpbusdirman_config_optionsupdated=true;

		}
		else if( isset($_REQUEST['action']) && ( 'reset' == $_REQUEST['action'] ))
		{
			update_option($wpbusdirmanconfigoptions,$def_wpbusdirman_config_options);
			$wpbusdirman_config_optionsreset=true;
		}
//End the saving procedures
if( isset($_REQUEST['saved']) && !empty( $_REQUEST['saved'] )) echo '<div id="message" class="updated fade"><p><strong>'.$myasfwpname.' settings saved.</strong></p></div>';
if ( isset($_REQUEST['reset']) && !empty( $_REQUEST['reset'] )) echo '<div id="message" class="updated fade"><p><strong>'.$myasfwpname.' settings reset.</strong></p></div>';

$wpbusdirman_config_options=get_wpbusdirman_config_options();
$wpbusdirman_config_saved_options = get_option($wpbusdirmanconfigoptionsprefix.'_settings_config');

		if (!isset($wpbusdirman_config_saved_options) || empty($wpbusdirman_config_saved_options) || !is_array($wpbusdirman_config_saved_options))
		{
			$wpbusdirman_config_options = $def_wpbusdirman_config_options;
		}
		else
		{
			$wpbusdirman_config_options=$wpbusdirman_config_saved_options;
		}
		?>
  <div class="wrap">
  <h2><?php _e('WP Business Directory Main Settings','WPBDM');?></h2>
  <form method="post">
    <?php foreach ($wpbusdirman_config_options as $value) {

if ($value['type'] == "text") { ?>
    <div style="float: left; width: 880px; background-color:#E4F2FD; border-left: 1px solid #C2D6E6; border-right: 1px solid #C2D6E6;  border-bottom: 1px solid #C2D6E6; padding: 10px;">
      <div style="width: 200px; float: left;"><?php echo $value['name']; ?></div>
      <div style="width: 680px; float: left;">
        <input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" style="width: 400px;" type="<?php echo $value['type']; ?>" value="<?php if ( $wpbusdirman_config_options[ $value['id'] ] != "") { echo stripslashes($wpbusdirman_config_options[ $value['id'] ]); } else { echo $value['std']; } ?>" />
      </div>
    </div>
    <?php } elseif ($value['type'] == "text2") { ?>
    <div style="float: left; width: 880px; background-color:#E4F2FD; border-left: 1px solid #C2D6E6; border-right: 1px solid #C2D6E6;  border-bottom: 1px solid #C2D6E6; padding: 10px;">
      <div style="width: 200px; float: left;"><?php echo $value['name']; ?></div>
      <div style="width: 680px; float: left;">
        <textarea name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" style="width: 400px; height: 200px;" type="<?php echo $value['type']; ?>"><?php if ( $wpbusdirman_config_options[ $value['id'] ] != "") { echo stripslashes($wpbusdirman_config_options[ $value['id'] ]); } else { echo $value['std']; } ?>
</textarea>
      </div>
    </div>
    <?php } elseif ($value['type'] == "select") { ?>
    <div style="float: left; width: 880px; background-color:#E4F2FD; border-left: 1px solid #C2D6E6; border-right: 1px solid #C2D6E6;  border-bottom: 1px solid #C2D6E6; padding: 10px;">
      <div style="width: 200px; float: left;"><?php echo $value['name']; ?></div>
      <div style="width: 680px; float: left;">
        <select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" style="width: 400px;">
          <?php foreach ($value['options'] as $option) { ?>
          <option<?php if ( $wpbusdirman_config_options[ $value['id'] ] == $option) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?>><?php echo $option; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <?php } elseif ($value['type'] == "titles") { ?>
    <div style="float: left; width: 870px; padding: 15px; background-color:#2583AD; border: 1px solid #2583AD; color: #fff; font-size: 16px; font-weight: bold; margin-top: 25px;"> <?php echo $value['name']; ?> </div>
    <?php
}
}
?>
    <div style="clear: both;"></div>
    <p style="float: left;" class="submit">
      <input name="save" type="submit" value="Save changes" />
      <input type="hidden" name="action" value="updatewpbusdirman_config_options" />
    </p>
  </form>
  <form method="post">
    <p style="float: left;" class="submit">
      <input name="reset" type="submit" value="Reset" />
      <input type="hidden" name="action" value="reset" />
    </p>
  </form>
  <?php
}




?>
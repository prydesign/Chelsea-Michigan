<?php
/*
Plugin Name: My Calendar
Plugin URI: http://www.joedolson.com/articles/my-calendar/
Description: Accessible WordPress event calendar plugin. Show events from multiple calendars on pages, in posts, or in widgets.
Author: Joseph C Dolson
Author URI: http://www.joedolson.com
Version: 1.7.8
*/
/*  Copyright 2009-2011  Joe Dolson (email : joe@joedolson.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
global $mc_version;
$mc_version = '1.7.8';
// Enable internationalisation
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain( 'my-calendar',false, dirname( plugin_basename( __FILE__ ) ) ); 
global $wpdb;
// Define the tables used in My Calendar
define('MY_CALENDAR_TABLE', $wpdb->prefix . 'my_calendar');
define('MY_CALENDAR_CATEGORIES_TABLE', $wpdb->prefix . 'my_calendar_categories');
define('MY_CALENDAR_LOCATIONS_TABLE', $wpdb->prefix . 'my_calendar_locations');

// Define other plugin constants
$my_calendar_directory = get_bloginfo( 'wpurl' ) . '/' . PLUGINDIR . '/' . dirname( plugin_basename(__FILE__) );
define( 'MY_CALENDAR_DIRECTORY', $my_calendar_directory );

// Create a master category for My Calendar and its sub-pages
add_action('admin_menu', 'my_calendar_menu');
// Add the function that puts style information in the header
add_action('wp_head', 'my_calendar_wp_head');
// Add the function that deals with deleted users
add_action('delete_user', 'mc_deal_with_deleted_user');
// Add the widgets if we are using version 2.8
add_action('widgets_init', create_function('', 'return register_widget("my_calendar_today_widget");'));
add_action('widgets_init', create_function('', 'return register_widget("my_calendar_upcoming_widget");'));
// custom user actions
add_action( 'show_user_profile', 'mc_user_profile' );
add_action( 'edit_user_profile', 'mc_user_profile' );
add_action( 'profile_update', 'mc_user_save_profile');

add_action( 'init', 'my_calendar_add_feed' );
function my_calendar_add_feed() {
	if ( get_option('mc_show_rss') == 'true' ) {
		add_feed( 'my-calendar-rss', 'my_calendar_rss' );
	}
	if ( get_option('mc_show-ical') == 'true' ) {
		add_feed( 'my-calendar-ics', 'my_calendar_ical' );
	}
}

register_activation_hook( __FILE__, 'check_my_calendar' );
// add filters to text widgets which will process shortcodes
add_filter( 'widget_text', 'do_shortcode', 9 );

if ( ! function_exists( 'is_ssl' ) ) {
	function is_ssl() {
		if ( isset($_SERVER['HTTPS']) ) {
		if ( 'on' == strtolower($_SERVER['HTTPS']) )
		 return true;
		if ( '1' == $_SERVER['HTTPS'] )
		 return true;
		} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
		return true;
		}
	return false;
	}
}

if ( version_compare( get_bloginfo( 'version' ) , '3.0' , '<' ) && is_ssl() ) {
	$wp_content_url = str_replace( 'http://' , 'https://' , get_option( 'siteurl' ) );
} else {
	$wp_content_url = get_option( 'siteurl' );
}
$wp_content_url .= '/wp-content';
$wp_content_dir = ABSPATH . 'wp-content';
if ( defined('WP_CONTENT_URL') ) {
	$wp_content_url = constant('WP_CONTENT_URL');
}
if ( defined('WP_CONTENT_DIR') ) {
	$wp_content_dir = constant('WP_CONTENT_DIR');
}

$wp_plugin_url = $wp_content_url . '/plugins';
$wp_plugin_dir = $wp_content_dir . '/plugins';
$wpmu_plugin_url = $wp_content_url . '/mu-plugins';
$wpmu_plugin_dir = $wp_content_dir . '/mu-plugins';

function jd_calendar_plugin_action($links, $file) {
	if ($file == plugin_basename(dirname(__FILE__).'/my-calendar.php')) {
		$links[] = "<a href='admin.php?page=my-calendar-config'>" . __('Settings', 'my-calendar') . "</a>";
		$links[] = "<a href='admin.php?page=my-calendar-help'>" . __('Help', 'my-calendar') . "</a>";
	}
	return $links;
}
add_filter('plugin_action_links', 'jd_calendar_plugin_action', -10, 2);

include(dirname(__FILE__).'/my-calendar-settings.php' );
include(dirname(__FILE__).'/my-calendar-categories.php' );
include(dirname(__FILE__).'/my-calendar-locations.php' );
include(dirname(__FILE__).'/my-calendar-help.php' );
include(dirname(__FILE__).'/my-calendar-event-manager.php' );
include(dirname(__FILE__).'/my-calendar-styles.php' );
include(dirname(__FILE__).'/my-calendar-behaviors.php' );
include(dirname(__FILE__).'/my-calendar-widgets.php' );
include(dirname(__FILE__).'/date-utilities.php' );
include(dirname(__FILE__).'/my-calendar-install.php' );
include(dirname(__FILE__).'/my-calendar-upgrade-db.php' );
include(dirname(__FILE__).'/my-calendar-user.php' );
include(dirname(__FILE__).'/my-calendar-output.php' );
include(dirname(__FILE__).'/my-calendar-templates.php' );
//include(dirname(__FILE__).'/my-calendar-export.php' );
include(dirname(__FILE__).'/my-calendar-rss.php' );
include(dirname(__FILE__).'/my-calendar-ical.php' );

//include(dirname(__FILE__).'/my-calendar-postevent.php' );

function jd_show_support_box() {
?>
<div class="resources">
<ul>
<li><a href="http://mywpworks.com/wp-plugin-guides/my-calendar-plugin-beginners-guide/"><?php _e("Buy the Beginner's Guide",'my-calendar'); ?></a></li>
<li><a href="http://www.joedolson.com/articles/my-calendar/"><?php _e("Get Support",'my-calendar'); ?></a></li>
<li><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar-help"><?php _e("My Calendar Help",'my-calendar'); ?></a></li>
<li><strong><a href="http://www.joedolson.com/donate.php"><?php _e("Make a Donation",'my-calendar'); ?></a></strong></li>
<li><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<div>
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="UZBQUG2LKKMRW" />
<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif" name="submit" alt="Donate!" />
<img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</div>
</form>
</li>
</ul>

</div>
<?php
}

// Function to deal with events posted by a user when that user is deleted
function mc_deal_with_deleted_user($id) {
  global $wpdb;
  // This wouldn't work unless the database was up to date. Lets check.
  check_my_calendar();
  // Do the query
  $wpdb->get_results( "UPDATE ".MY_CALENDAR_TABLE." SET event_author=".$wpdb->get_var("SELECT MIN(ID) FROM ".$wpdb->prefix."users",0,0)." WHERE event_author=".$id );
}

// Function to add the calendar style into the header
function my_calendar_wp_head() {
  global $wpdb, $wp_query;
  // If the calendar isn't installed or upgraded this won't work
  check_my_calendar();
  $styles = mc_get_style_path( get_option( 'my_calendar_css_file' ),'url' );
	if ( get_option('my_calendar_use_styles') != 'true' ) {
	
		$this_post = $wp_query->get_queried_object();
		if (is_object($this_post)) {
			$id = $this_post->ID;
		} 
		if ( get_option( 'my_calendar_show_css' ) != '' ) {
		$array = explode( ",",get_option( 'my_calendar_show_css' ) );
			if (!is_array($array)) {
				$array = array();
			}
		}
		if ( @in_array( $id, $array ) || get_option( 'my_calendar_show_css' ) == '' ) {
	
// generate category colors
$category_styles = '';
$categories = $wpdb->get_results("SELECT * FROM " . MY_CALENDAR_CATEGORIES_TABLE . " ORDER BY category_id ASC");
	foreach ( $categories as $category ) {
			$class = "mc_".sanitize_title($category->category_name);
			$hex = (strpos($category->category_color,'#') !== 0)?'#':'';
			$color = $hex.$category->category_color;
			
		if ( get_option( 'mc_apply_color' ) == 'font' ) {
			$type = 'color';
		} else if ( get_option( 'mc_apply_color' ) == 'background' ) {
			$type = 'background';
		}
		if ( get_option( 'mc_apply_color' )  == 'font' || get_option( 'mc_apply_color' ) == 'background' ) {
		$category_styles .= "\n#jd-calendar .$class .event-title { $type: $color; }";
		}
	}	
	
echo "
<link rel=\"stylesheet\" href=\"$styles\" type=\"text/css\" media=\"all\" />
<style type=\"text/css\">
<!--
.js #jd-calendar .details { display: none; }
/* Styles from My Calendar - Joseph C Dolson http://www.joedolson.com/ */
$category_styles
.mc-event-visible {
display: block!important;
}
-->
</style>";

		}
	}
}

// Function to deal with adding the calendar menus
function my_calendar_menu() {
  global $wpdb;
  // We make use of the My Calendar tables so we must have installed My Calendar
  check_my_calendar();
  // Set admin as the only one who can use My Calendar for security
  $allowed_group = 'manage_options';
  // Use the database to *potentially* override the above if allowed
  $allowed_group = get_option('can_manage_events');


  // Add the admin panel pages for My Calendar. Use permissions pulled from above
	if (function_exists('add_menu_page')) {
		add_menu_page(__('My Calendar','my-calendar'), __('My Calendar','my-calendar'), $allowed_group, 'my-calendar', 'edit_my_calendar');
	}
	if (function_exists('add_submenu_page')) {
		add_submenu_page('my-calendar', __('Add/Edit Events','my-calendar'), __('Add/Edit Events','my-calendar'), $allowed_group, 'my-calendar', 'edit_my_calendar');
		add_action( "admin_head", 'my_calendar_write_js' );		
		add_action( "admin_head", 'my_calendar_add_styles' );
		// Note only admin can change calendar options
		add_submenu_page('my-calendar', __('Manage Categories','my-calendar'), __('Manage Categories','my-calendar'), 'manage_options', 'my-calendar-categories', 'my_calendar_manage_categories');
		add_submenu_page('my-calendar', __('Manage Locations','my-calendar'), __('Manage Locations','my-calendar'), 'manage_options', 'my-calendar-locations', 'my_calendar_manage_locations');		
		add_submenu_page('my-calendar', __('Settings','my-calendar'), __('Settings','my-calendar'), 'manage_options', 'my-calendar-config', 'edit_my_calendar_config');
		add_submenu_page('my-calendar', __('Style Editor','my-calendar'), __('Style Editor','my-calendar'), 'manage_options', 'my-calendar-styles', 'edit_my_calendar_styles');
		add_submenu_page('my-calendar', __('Behavior Editor','my-calendar'), __('Behavior Editor','my-calendar'), 'manage_options', 'my-calendar-behaviors', 'edit_my_calendar_behaviors');		
		add_submenu_page('my-calendar', __('My Calendar Help','my-calendar'), __('Help','my-calendar'), 'manage_options', 'my-calendar-help', 'my_calendar_help');		
	}
}
add_action( "admin_menu", 'my_calendar_add_javascript' );

// Function to add the javascript to the admin header
function my_calendar_add_javascript() { 
global $wp_plugin_url;
	if ($_GET['page'] == 'my-calendar') {
		wp_enqueue_script('jquery-ui-datepicker',$wp_plugin_url . '/my-calendar/js/ui.datepicker.js', array('jquery','jquery-ui-core') );
	}
}
function my_calendar_write_js() {
	if ($_GET['page']=='my-calendar') {
		echo '
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready(function($) {
	    $("#event_begin").datepicker({
			numberOfMonths: 2,
			dateFormat: "yy-mm-dd"
		});
	    $("#event_end").datepicker({
			numberOfMonths: 2,
			dateFormat: "yy-mm-dd",
		});
	var prevDate = $("#event_begin").datepicker( "getDate" );
	$("#event_end").datepicker( "option","defaultDate", prevDate );
	});

	//]]>	 
	</script>
	';
	}
}
function my_calendar_add_display_javascript() {
	wp_enqueue_script('jquery');
}
add_action('init','my_calendar_add_display_javascript');

function my_calendar_fouc() {
global $wp_query;
	if ( get_option('calendar_javascript') != 1 || get_option('list_javascript') != 1 || get_option('mini_javascript') != 1 ) {
		$scripting = "\n<script type='text/javascript'>\n";
		$scripting .= "jQuery('html').addClass('js');\n";
		$scripting .= "jQuery(document).ready(function($) { \$('html').removeClass('js') });\n";
		$scripting .= "</script>\n";
		$this_post = $wp_query->get_queried_object();
		if (is_object($this_post)) {
			$id = $this_post->ID;
		} 
		if ( get_option( 'my_calendar_show_js' ) != '' ) {
		$array = explode( ",",get_option( 'my_calendar_show_js' ) );
			if (!is_array($array)) {
				$array = array();
			}
		}
		if ( @in_array( $id, $array ) || trim ( get_option( 'my_calendar_show_js' ) ) == '' ) {	
			echo $scripting;
		}
	}
}

function my_calendar_calendar_javascript() {
  global $wpdb, $wp_query, $wp_plugin_url;

	if ( get_option('calendar_javascript') != 1 || get_option('list_javascript') != 1 || get_option('mini_javascript') != 1 || get_option('ajax_javascript') != 1 ) {
	  
	$list_js = stripcslashes( get_option( 'my_calendar_listjs' ) );
	$cal_js = stripcslashes( get_option( 'my_calendar_caljs' ) );
	$mini_js = stripcslashes( get_option( 'my_calendar_minijs' ) );
    $ajax_js = stripcslashes( get_option( 'my_calendar_ajaxjs' ) );
	
		$this_post = $wp_query->get_queried_object();
		if (is_object($this_post)) {
			$id = $this_post->ID;
		} 
		if ( get_option( 'my_calendar_show_js' ) != '' ) {
		$array = explode( ",",get_option( 'my_calendar_show_js' ) );
			if (!is_array($array)) {
				$array = array();
			}
		}
		if ( @in_array( $id, $array ) || get_option( 'my_calendar_show_js' ) == '' ) {
			$scripting = "<script type='text/javascript'>\n";
			if ( get_option('calendar_javascript') != 1 ) {	$scripting .= "\n".$cal_js; }
			if ( get_option('list_javascript') != 1 ) {	$scripting .= "\n".$list_js; }
			if ( get_option('mini_javascript') != 1 ) {	$scripting .= "\n".$mini_js; }
			if ( get_option('ajax_javascript') != 1 ) { $scripting .= "\n".$ajax_js; }
			$scripting .= "</script>";
			echo $scripting;
		}
	}	
}
add_action('wp_footer','my_calendar_calendar_javascript');
add_action('wp_head','my_calendar_fouc');

function my_calendar_add_styles() {
global $wp_plugin_url;
	if ($_GET['page'] == 'my-calendar' || $_GET['page'] == 'my-calendar-categories' || $_GET['page'] == 'my-calendar-locations' || $_GET['page'] == 'my-calendar-config' || $_GET['page'] == 'my-calendar-styles' || $_GET['page'] == 'my-calendar-help' || $_GET['page'] == 'my-calendar-behaviors' ) {
	echo '<link type="text/css" rel="stylesheet" href="'.$wp_plugin_url.'/my-calendar/js/ui.datepicker.css" />';
	echo '<link type="text/css" rel="stylesheet" href="'.$wp_plugin_url.'/my-calendar/mc-styles.css" />';
	}
}

function my_calendar_insert($atts) {
	extract(shortcode_atts(array(
				'name' => 'all',
				'format' => 'calendar',
				'category' => 'all',
				'showkey' => 'yes',
				'shownav' => 'yes',
				'time' => 'month'
			), $atts));
	if ( isset($_GET['format']) ) {
		$format = mysql_real_escape_string($_GET['format']);
	}	
	return my_calendar($name,$format,$category,$showkey,$shownav,$time);
}

function my_calendar_insert_upcoming($atts) {
	extract(shortcode_atts(array(
				'before' => 'default',
				'after' => 'default',
				'type' => 'default',
				'category' => 'default',
				'template' => 'default',
				'fallback' => '',
			), $atts));
	return my_calendar_upcoming_events($before, $after, $type, $category, $template, $fallback);
}

function my_calendar_insert_today($atts) {
	extract(shortcode_atts(array(
				'category' => 'default',
				'template' => 'default',
				'fallback' => '',
			), $atts));
	return my_calendar_todays_events($category, $template, $fallback);
}

function my_calendar_locations($atts) {
	extract(shortcode_atts(array(
				'show' => 'list',
				'type' => 'saved',
				'datatype' => 'name'
			), $atts));
	return my_calendar_locations_list($show,$type,$datatype);
}

function get_current_url() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
		$pageURL .= "://";
		if ($_SERVER["SERVER_PORT"] != "80") {
			$pageURL .= $_SERVER["HTTP_HOST"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
		} else {
			$pageURL .= $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
		}
	return $pageURL;
}
// add shortcode interpreter
add_shortcode('my_calendar','my_calendar_insert');
add_shortcode('my_calendar_upcoming','my_calendar_insert_upcoming');
add_shortcode('my_calendar_today','my_calendar_insert_today');
add_shortcode('my_calendar_locations','my_calendar_locations');

// Function to check what version of My Calendar is installed and install if needed
function check_my_calendar() {
	global $wpdb, $initial_listjs, $initial_caljs, $initial_minijs, $initial_ajaxjs,$mc_version,$stored_styles;
	$current_version = get_option('my_calendar_version');
	// If current version matches, don't bother running this.
	if ($current_version == $mc_version) {
		return true;
	}

  // Lets see if this is first run and create a table if it is!
  // Assume this is not a new install until we prove otherwise
  $new_install = false;
  $my_calendar_exists = false;
  $upgrade_path = array();
  
  // Determine the calendar version
  $tables = $wpdb->get_results("show tables;");
	foreach ( $tables as $table ) {
      foreach ( $table as $value )  {
		  if ( $value == MY_CALENDAR_TABLE ) {
		      $my_calendar_exists = true;
			  // check whether installed version matches most recent version, establish upgrade process.
		    } 
       }
    }
	
	if ( $my_calendar_exists == false ) {
      $new_install = true;
	// for each release requiring an upgrade path, add a version compare. Loop will run every relevant upgrade cycle.
    } else if ( version_compare( $current_version,"1.3.0","<" ) ) {
		$upgrade_path[] = "1.3.0";
	} else if ( version_compare( $current_version,"1.3.8","<" ) ) {
		$upgrade_path[] = "1.3.8";
	} else if ( version_compare( $current_version, "1.4.0", "<" ) ) {
		$upgrade_path[] = "1.4.0";
	} else if ( version_compare( $current_version, "1.4.7", "<" ) ) {
		$upgrade_path[] = "1.4.7";
	} else if ( version_compare( $current_version, "1.4.8", "<" ) ) {
		$upgrade_path[] = "1.4.8";
	} else if ( version_compare( $current_version, "1.5.0", "<" ) ) {
		$upgrade_path[] = "1.5.0";
	} else if ( version_compare( $current_version, "1.6.0", "<" ) ) {
		$upgrade_path[] = "1.6.0";
	} else if ( version_compare( $current_version, "1.6.2", "<" ) ) {
		$upgrade_path[] = "1.6.2";
	} else if ( version_compare( $current_version, "1.6.3", "<" ) ) {
		$upgrade_path[] = "1.6.3";
	} else if ( version_compare( $current_version, "1.7.0", "<" ) ) { 
		$upgrade_path[] = "1.7.0";
	} else if ( version_compare( $current_version, "1.7.1", "<" ) ) { 
		$upgrade_path[] = "1.7.1";
	}
	
	// having determined upgrade path, assign new version number
	update_option( 'my_calendar_version' , $mc_version );

	// Now we've determined what the current install is or isn't 
	if ( $new_install == true ) {
		  //add default settings
		mc_default_settings();
		$sql = "INSERT INTO " . MY_CALENDAR_CATEGORIES_TABLE . " SET category_id=1, category_name='General', category_color='#ffffff', category_icon='event.png'";
		$wpdb->query($sql);
    } 
			
// switch for different upgrade paths
	foreach ($upgrade_path as $upgrade) {
		switch ($upgrade) {
			case '1.3.0':
				add_option('my_calendar_listjs',$initial_listjs);
				add_option('my_calendar_caljs',$initial_caljs);
				add_option('my_calendar_show_heading','true');  
			break;
			case '1.3.8':
				update_option('my_calendar_show_css','');
			break;
			case '1.4.0':
			// change tables					
				add_option( 'mc_db_version', '1.4.0' );
				add_option( 'mc_event_link_expires','false' );
				add_option( 'mc_apply_color','default' );
				add_option( 'my_calendar_minijs', $initial_minijs);
				add_option( 'mini_javascript', 1);
				upgrade_db();
			break;
			case '1.4.7':
				add_option( 'mc_event_open', 'Registration is open' );
				add_option( 'mc_event_closed', 'Registration is closed' );
				add_option( 'mc_event_registration', 'false' );
				add_option( 'mc_short', 'false' );
				add_option( 'mc_desc', 'true' );
				upgrade_db();
			break;
			case '1.4.8':
				add_option('mc_input_options',array('event_short'=>'on','event_desc'=>'on','event_category'=>'on','event_link'=>'on','event_recurs'=>'on','event_open'=>'on','event_location'=>'on','event_location_dropdown'=>'on') );	
				add_option('mc_input_options_administrators','false');
			break;
			case '1.5.0':
				add_option('mc_event_mail','false');
				add_option('mc_event_mail_subject','');
				add_option('mc_event_mail_to','');
				add_option('mc_event_mail_message','');
				add_option('mc_event_approve','false');		
				add_option('mc_event_approve_perms','manage_options');
				add_option('mc_no_fifth_week','true');				
				upgrade_db();
			break;
			case '1.6.0':
				add_option('mc_user_settings_enabled',false);
				add_option('mc_user_location_type','state');
				add_option('my_calendar_show_js',get_option('my_calendar_show_css') );   
				upgrade_db();			
			break;
			case '1.6.2':
				$mc_user_settings = array(
				'my_calendar_tz_default'=>array(
					'enabled'=>'off',
					'label'=>'My Calendar Default Timezone',
					'values'=>array(
							"-12" => "(GMT -12:00) Eniwetok, Kwajalein",
							"-11" => "(GMT -11:00) Midway Island, Samoa",
							"-10" => "(GMT -10:00) Hawaii",
							"-9.5" => "(GMT -9:30) Marquesas Islands",
							"-9" => "(GMT -9:00) Alaska",
							"-8" => "(GMT -8:00) Pacific Time (US &amp; Canada)",
							"-7" => "(GMT -7:00) Mountain Time (US &amp; Canada)",
							"-6" => "(GMT -6:00) Central Time (US &amp; Canada), Mexico City",
							"-5" => "(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima",
							"-4.5" => "(GMT -4:30) Venezuela",
							"-4" => "(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz",
							"-3.5" => "(GMT -3:30) Newfoundland",
							"-3" => "(GMT -3:00) Brazil, Buenos Aires, Georgetown",
							"-2" => "(GMT -2:00) Mid-Atlantic",
							"-1" => "(GMT -1:00 hour) Azores, Cape Verde Islands",
							"0" => "(GMT) Western Europe Time, London, Lisbon, Casablanca",
							"1" => "(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris",
							"2" => "(GMT +2:00) Kaliningrad, South Africa",
							"3" => "(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg",
							"3.5" => "(GMT +3:30) Tehran",
							"4" => "(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi",
							"4.5" => "(GMT +4:30) Afghanistan",
							"5" => "(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent",
							"5.5" => "(GMT +5:30) Bombay, Calcutta, Madras, New Delhi",
							"5.75" => "(GMT +5:45) Nepal",
							"6" => "(GMT +6:00) Almaty, Dhaka, Colombo",
							"6.5" => "(GMT +6:30) Myanmar, Cocos Islands",
							"7" => "(GMT +7:00) Bangkok, Hanoi, Jakarta",
							"8" => "(GMT +8:00) Beijing, Perth, Singapore, Hong Kong",
							"9" => "(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk",
							"9.5" => "(GMT +9:30) Adelaide, Darwin",
							"10" => "(GMT +10:00) Eastern Australia, Guam, Vladivostok",
							"10.5" => "(GMT +10:30) Lord Howe Island",
							"11" => "(GMT +11:00) Magadan, Solomon Islands, New Caledonia",
							"11.5" => "(GMT +11:30) Norfolk Island",
							"12" => "(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka",
							"12.75" => "(GMT +12:45) Chatham Islands",
							"13" => "(GMT +13:00) Tonga",
							"14" => "(GMT +14:00) Line Islands"
							),
					),
				'my_calendar_location_default'=>array(
					'enabled'=>'off',
					'label'=>'My Calendar Default Location',
					'values'=>array(
								'AL'=>"Alabama",
								'AK'=>"Alaska", 
								'AZ'=>"Arizona", 
								'AR'=>"Arkansas", 
								'CA'=>"California", 
								'CO'=>"Colorado", 
								'CT'=>"Connecticut", 
								'DE'=>"Delaware", 
								'DC'=>"District Of Columbia", 
								'FL'=>"Florida", 
								'GA'=>"Georgia", 
								'HI'=>"Hawaii", 
								'ID'=>"Idaho", 
								'IL'=>"Illinois", 
								'IN'=>"Indiana", 
								'IA'=>"Iowa", 
								'KS'=>"Kansas", 
								'KY'=>"Kentucky", 
								'LA'=>"Louisiana", 
								'ME'=>"Maine", 
								'MD'=>"Maryland", 
								'MA'=>"Massachusetts", 
								'MI'=>"Michigan", 
								'MN'=>"Minnesota", 
								'MS'=>"Mississippi", 
								'MO'=>"Missouri", 
								'MT'=>"Montana",
								'NE'=>"Nebraska",
								'NV'=>"Nevada",
								'NH'=>"New Hampshire",
								'NJ'=>"New Jersey",
								'NM'=>"New Mexico",
								'NY'=>"New York",
								'NC'=>"North Carolina",
								'ND'=>"North Dakota",
								'OH'=>"Ohio", 
								'OK'=>"Oklahoma", 
								'OR'=>"Oregon", 
								'PA'=>"Pennsylvania", 
								'RI'=>"Rhode Island", 
								'SC'=>"South Carolina", 
								'SD'=>"South Dakota",
								'TN'=>"Tennessee", 
								'TX'=>"Texas", 
								'UT'=>"Utah", 
								'VT'=>"Vermont", 
								'VA'=>"Virginia", 
								'WA'=>"Washington", 
								'WV'=>"West Virginia", 
								'WI'=>"Wisconsin", 
								'WY'=>"Wyoming"),
					)
				);			
				update_option('mc_user_settings',$mc_user_settings);			
			break;
			case '1.6.3':
				add_option( 'my_calendar_ajaxjs',$initial_ajaxjs );
				add_option( 'ajax_javascript', 1 );
				add_option( 'my_calendar_templates', array(
					'title'=>'{title}'
				));
			break;	
			case '1.7.0': 
				update_option('mc_db_version','1.7.0');
				add_option('mc_show_rss','false');
				add_option('mc_show_ical','false');					
				add_option('mc_skip_holidays','false');	
				add_option('mc_event_edit_perms','manage_options');
				$original_styles = get_option('my_calendar_style');
				if ($original_styles != '') {
				$stylefile = mc_get_style_path('my-calendar.css');
					if ( mc_write_styles( $stylefile, $original_styles ) ) {
						delete_option('my_calendar_style');
					} else {
						add_option('my_calendar_file_permissions','false');
					}
				}
				add_option('my_calendar_stored_styles',$stored_styles);
				update_option('my_calendar_css_file','my-calendar.css');				
				// convert old widget settings into new defaults
				$type = get_option('display_upcoming_type');
				if ($type == 'events') {
					$before = get_option('display_upcoming_events');
					$after = get_option('display_past_events');
				} else {
					$before = get_option('display_upcoming_days');
					$after = get_option('display_past_days');
				}
				$category = get_option('display_in_category');
				$today_template = get_option('my_calendar_today_template'); 
				$upcoming_template = get_option('my_calendar_upcoming_template');
				$today_title = get_option('my_calendar_today_title');
				$today_text = get_option('my_calendar_no_events_text');
				$upcoming_title = get_option('my_calendar_upcoming_title');

				$defaults = array(
					'upcoming'=>array(	
						'type'=>$type,
						'before'=>$before,
						'after'=>$after,
						'template'=>$upcoming_template,
						'category'=>$category,
						'text'=>'',
						'title'=>$upcoming_title
					),
					'today'=>array(
						'template'=>$today_template,
						'category'=>'',
						'title'=>$today_title,
						'text'=>$today_text
					)
				);
				add_option('my_calendar_widget_defaults',$defaults);
				delete_option('display_upcoming_type');
				delete_option('display_upcoming_events');
				delete_option('display_past_events');
				delete_option('display_upcoming_days');
				delete_option('display_todays','true');
				delete_option('display_upcoming','true');
				delete_option('display_upcoming_days',7);				
				delete_option('display_past_days');
				delete_option('display_in_category');
				delete_option('my_calendar_today_template'); 
				delete_option('my_calendar_upcoming_template');
				delete_option('my_calendar_today_title');
				delete_option('my_calendar_no_events_text');
				delete_option('my_calendar_upcoming_title');			
			break;
			case '1.7.1':
				if (get_option('mc_location_type') == '') {
					update_option('mc_location_type','event_state');
				}
			break;
			default:
			break;
		}
}
	/* 
	if the user has fully uninstalled the plugin but kept the database of events, this will restore default 
	settings and upgrade db if needed.
	*/
	if ( get_option( 'my_calendar_uninstalled' ) == 'true' ) {
		mc_default_settings();	
		update_option( 'mc_db_version', '1.4.0' );
	}
}

function jd_cal_checkCheckbox( $theFieldname,$theValue,$theArray='' ){
	if (!is_array( get_option( $theFieldname ) ) ) {
	if( get_option( $theFieldname ) == $theValue ){
		echo 'checked="checked"';
	}
	} else {
		$theSetting = get_option( $theFieldname );
		if ( $theSetting[$theArray]['enabled'] == $theValue ) {
			echo 'checked="checked"';
		}
	}
}
function jd_cal_checkSelect( $theFieldname,$theValue,$theArray='' ){
	if (!is_array( get_option( $theFieldname ) ) ) {
	if( get_option( $theFieldname ) == $theValue ){
			echo 'selected="selected"';
	}
	} else {
		$theSetting = get_option( $theFieldname );
		if ( $theSetting[$theArray]['enabled'] == $theValue ) {
			echo 'selected="selected"';
		}
	}
}

// Function to return a prefix which will allow the correct placement of arguments into the query string.
function my_calendar_permalink_prefix() {
  // Get the permalink structure from WordPress
  $p_link = get_permalink();
  $real_link = get_current_url();

  // Now use all of that to get the My Calendar link prefix
  if (strstr($p_link, '?') && $p_link == $real_link) {
      $link_part = $p_link.'&';
    } else if ($p_link == $real_link) {
      $link_part = $p_link.'?';
    } else if (strstr($real_link, '?')) {
	
		if ( isset($_GET['month']) || isset($_GET['yr']) || isset($_GET['week']) ) {
			$link_part = '';
			$new_base = split('\?', $real_link);
			if(count($new_base) > 1) {
				$new_tail = split('&', $new_base[1]);
				foreach ($new_tail as $item) {
					if ( isset($_GET['month']) && isset($_GET['yr']) ) {
						if (!strstr($item, 'month') && !strstr($item, 'yr')) {
							$link_part .= $item.'&';
						}
					} 
					if ( isset($_GET['week']) && isset($_GET['yr']) ) {
						if (!strstr($item, 'week') && !strstr($item, 'yr')) {
							$link_part .= $item.'&';
						}
					} 
				}
			}
			$link_part = $new_base[0] . ($link_part ? "?$link_part" : '?');
		} else {
			$link_part = $real_link.'&';
		}
		
		
    } else {
      $link_part = $real_link.'?';
    }
  return $link_part;
}

function mc_select_category($category, $type='event') {
global $wpdb;
	if ($category == 'all' ) {
	 return '';
	} else {
 	if ( strpos( $category, "|" ) ) {
		$categories = explode( "|", $category );
		$numcat = count($categories);
		$i = 1;
		foreach ($categories as $key) {
			if ( is_numeric($key) ) {
				if ($i == 1) {
					$select_category .= ($type=='all')?" WHERE (":' (';
				}				
				$select_category .= " event_category = $key";
				if ($i < $numcat) {
					$select_category .= " OR ";
				} else if ($i == $numcat) {
					$select_category .= ($type=='all')?") ":' ) AND';
				}
			$i++;
			} else {
				$cat = $wpdb->get_row("SELECT category_id FROM " . MY_CALENDAR_CATEGORIES_TABLE . " WHERE category_name = '$key'");
				$category_id = $cat->category_id;
				if ($i == 1) {
					$select_category .= ($type=='all')?" WHERE (":' (';
				}
				$select_category .= " event_category = $category_id";
				if ($i < $numcat) {
					$select_category .= " OR ";
				} else if ($i == $numcat) {
					$select_category .= ($type=='all')?") ":' ) AND';
				}
				$i++;						
			}
		}
	} else {
		if (is_numeric($category)) {
		$select_category = ($type=='all')?" WHERE event_category = $category":" event_category = $category AND";
		} else {
		$cat = $wpdb->get_row("SELECT category_id FROM " . MY_CALENDAR_CATEGORIES_TABLE . " WHERE category_name = '$category'");
		$category_id = $cat->category_id;
			if (!$category_id) {
				//if the requested category doesn't exist, fail silently
				$select_category = "";
			} else {
				$select_category = ($type=='all')?" WHERE event_category = $category_id":" event_category = $category_id AND";
			}
		}
	}
	return $select_category;
	}
}
// used to generate upcoming events lists
function mc_get_all_events($category) {
global $wpdb;
	if ( $category!='default' ) {
		$select_category = mc_select_category($category,'all');
	} else {
		$select_category = "";
	}
	$limit_string = mc_limit_string('all');
	if ($select_category != '' && $limit_string != '') {
	$join = ' AND ';
	} else if ($select_category == '' && $limit_string != '' ) {
	$join = ' WHERE ';
	} else {
	$join = '';
	}
	$limits = $select_category . $join . $limit_string;
    $events = $wpdb->get_results("SELECT *,event_begin as event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) $limits");
	$offset = (60*60*get_option('gmt_offset'));
	$date = date('Y', time()+($offset)).'-'.date('m', time()+($offset)).'-'.date('d', time()+($offset));
    if (!empty($events)) {
        foreach($events as $event) {
			if ($event->event_recur != "S") {
				$orig_begin = $event->event_begin;
				$orig_end = $event->event_end;
				$numback = 0;
				$numforward = $event->event_repeats;
				$event_repetition = (int) $event->event_repeats;
				if ($event_repetition !== 0) {				
					switch ($event->event_recur) {
						case "D":
							for ($i=$numback;$i<=$numforward;$i++) {
								$begin = my_calendar_add_date($orig_begin,$i,0,0);
								$end = my_calendar_add_date($orig_end,$i,0,0);		
								${$i} = clone($event);
								${$i}->event_begin = $begin;
								${$i}->event_end = $end;							
								$arr_events[]=${$i};
							}
							break;
						case "W":
							for ($i=$numback;$i<=$numforward;$i++) {
								$begin = my_calendar_add_date($orig_begin,($i*7),0,0);
								$end = my_calendar_add_date($orig_end,($i*7),0,0);
								${$i} = clone($event);
								${$i}->event_begin = $begin;
								${$i}->event_end = $end;							
								$arr_events[]=${$i};
							}
							break;
						case "B":
							for ($i=$numback;$i<=$numforward;$i++) {
								$begin = my_calendar_add_date($orig_begin,($i*14),0,0);
								$end = my_calendar_add_date($orig_end,($i*14),0,0);
								${$i} = clone($event);
								${$i}->event_begin = $begin;
								${$i}->event_end = $end;							
								$arr_events[]=${$i};
							}
							break;							
						case "M":
							for ($i=$numback;$i<=$numforward;$i++) {
								$begin = my_calendar_add_date($orig_begin,0,$i,0);
								$end = my_calendar_add_date($orig_end,0,$i,0);
								${$i} = clone($event);
								${$i}->event_begin = $begin;
								${$i}->event_end = $end;							
								$arr_events[]=${$i};
							}
							break;
						case "U":
							for ($i=$numback;$i<=$numforward;$i++) {
								$approxbegin = my_calendar_add_date($orig_begin,0,$i,0);
								$approxend = my_calendar_add_date($orig_end,0,$i,0);
								$day_of_event = date('D',strtotime($event->event_begin) );
								$week_of_event = week_of_month( date('d',strtotime($event->event_begin) ) );
								for ($n=-6;$n<=6;$n++) {								
									$timestamp = strtotime(my_calendar_add_date($approxbegin,$n,0,0));
									$current_day = date('D',$timestamp);
									if ($current_day == $day_of_event) {
									$current_week = week_of_month( date( 'd',$timestamp));
									$current_date = date( 'd',$timestamp);
										if ($current_day == $day_of_event && $current_week == $week_of_event) {
											$date_of_event_this_month == $current_date;
											//echo "Principle<br />";
										} else {
											//echo "Busted<br />";
											for ($s = 1;$s<=31;$s++) {
												$string = date( 'Y', $timestamp ).'-'.date( 'm', $timestamp).'-'.$s;
												$week = week_of_month($s);
													if ( date('D',strtotime($string)) == $day_of_event && $week == $week_of_event ) {
														$date_of_event_this_month = $s;	
														break;
													}
											}
											if ( get_option('mc_no_fifth_week') == 'true' && $date_of_event_this_month == '' ) {
												$new_week_of_event = $week_of_event-1;
												for ($s=1;$s<=31;$s++) {
													$string = date( 'Y', $timestamp ).'-'.date('m', $timestamp).'-'.$s;
													if ( date('D',strtotime($string)) == $day_of_event && $week == $new_week_of_event ) {
														$date_of_event_this_month = $s;
														break;
													}
												}
											}
										}
										if ( ($current_day == $day_of_event && $current_week == $week_of_event) || ($current_date >= $date_of_event_this_month && $current_date <= $date_of_event_this_month+$day_diff && $date_of_event_this_month != '' ) ) {	
											$begin = my_calendar_add_date($approxbegin,$n,0,0);
											$end = my_calendar_add_date($approxend,$n,0,0);
											//$i=4;
											${$i} = clone($event);
											${$i}->event_begin = $begin;
											${$i}->event_end = $end;
											$arr_events[]=${$i};
										}
									}
								} 
							}
						break;
						case "Y":
							for ($i=$numback;$i<=$numforward;$i++) {
								$begin = my_calendar_add_date($orig_begin,0,0,$i);
								$end = my_calendar_add_date($orig_end,0,0,$i);
								${$i} = clone($event);
								${$i}->event_begin = $begin;
								${$i}->event_end = $end;							
								$arr_events[]=${$i};
							}
						break;
					}
				} else {
					switch ($event->event_recur) {
						case "D":
							$event_begin = $event->event_begin;
							$today = date('Y',time()+($offset)).'-'.date('m',time()+($offset)).'-'.date('d',time()+($offset));
							$nDays = 30;
							$fDays = 30;
								if (my_calendar_date_comp( $event_begin, my_calendar_add_date($today,-($nDays),0,0) )) {
									$diff = jd_date_diff_precise(strtotime($event_begin));
									$diff_days = $diff/(86400);
									$days = explode(".",$diff_days);
									$realStart = $days[0] - $nDays;
									$realFinish = $days[0] + $fDays;

									for ($realStart;$realStart<=$realFinish;$realStart++) { // jump forward to near present.
									$this_date = my_calendar_add_date($event_begin,($realStart),0,0);
										if ( my_calendar_date_comp( $event->event_begin,$this_date ) ) {
											${$realStart} = clone($event);
											${$realStart}->event_begin = $this_date;
											$arr_events[] = ${$realStart};
										}
									}
								} else {
							$realDays = -($nDays);
								for ($realDays;$realDays<=$fDays;$realDays++) { // for each event within plus or minus range, mod date and add to array.
								$this_date = my_calendar_add_date($event_begin,$realDays,0,0);
									if ( my_calendar_date_comp( $event->event_begin,$this_date ) == true ) {
										${$realDays} = clone($event);
										${$realDays}->event_begin = $this_date;
										$arr_events[] = ${$realDays};
									}
								}
							}
						break;
						case "W":
							$event_begin = $event->event_begin;
							$today = date('Y',time()+($offset)).'-'.date('m',time()+($offset)).'-'.date('d',time()+($offset));
							$nDays = 6;
							$fDays = 6;
				
								if (my_calendar_date_comp( $event_begin, my_calendar_add_date($today,-($nDays*7),0,0) )	) {							
									$diff = jd_date_diff_precise(strtotime($event_begin));
									$diff_weeks = $diff/(86400*7);
									$weeks = explode(".",$diff_weeks);
									$realStart = $weeks[0] - $nDays;
									$realFinish = $weeks[0] + $fDays;

									for ($realStart;$realStart<=$realFinish;$realStart++) { // jump forward to near present.
									$this_date = my_calendar_add_date($event_begin,($realStart*7),0,0);
									
									if ( my_calendar_date_comp( $event->event_begin,$this_date ) ) {
											${$realStart} = clone($event);
											${$realStart}->event_begin = $this_date;
											$arr_events[] = ${$realStart};
										}
									}
								
								} else {
								$realDays = -($nDays);
								for ($realDays;$realDays<=$fDays;$realDays++) { // for each event within plus or minus range, mod date and add to array.
								$this_date = my_calendar_add_date($event_begin,($realDays*7),0,0);
									if ( my_calendar_date_comp( $event->event_begin,$this_date ) ) {
										${$realDays} = clone($event);
										${$realDays}->event_begin = $this_date;
										$arr_events[] = ${$realDays};
									}
								}
								}
						break;
						case "B":
							$event_begin = $event->event_begin;
							$today = date('Y',time()+($offset)).'-'.date('m',time()+($offset)).'-'.date('d',time()+($offset));
							$nDays = 6;
							$fDays = 6;
							
								if (my_calendar_date_comp( $event_begin, my_calendar_add_date($today,-($nDays*14),0,0) )) {
									$diff = jd_date_diff_precise(strtotime($event_begin));
									$diff_weeks = $diff/(86400*14);
									$weeks = explode(".",$diff_weeks);
									$realStart = $weeks[0] - $nDays;
									$realFinish = $weeks[0] + $fDays;

									for ($realStart;$realStart<=$realFinish;$realStart++) { // jump forward to near present.
									$this_date = my_calendar_add_date($event_begin,($realStart*14),0,0);
										if ( my_calendar_date_comp( $event->event_begin,$this_date ) ) {
											${$realStart} = clone($event);
											${$realStart}->event_begin = $this_date;
											$arr_events[] = ${$realStart};
										}
									}
								
								} else {
								$realDays = -($nDays);
								for ($realDays;$realDays<=$fDays;$realDays++) { // for each event within plus or minus range, mod date and add to array.
								$this_date = my_calendar_add_date($event_begin,($realDays*14),0,0);
									if ( my_calendar_date_comp( $event->event_begin,$this_date ) ) {
										${$realDays} = clone($event);
										${$realDays}->event_begin = $this_date;
										$arr_events[] = ${$realDays};
									}
								}
								}
						break;
						
						case "M":
							$event_begin = $event->event_begin;
							$today = date('Y',time()+($offset)).'-'.date('m',time()+($offset)).'-'.date('d',time()+($offset));
							$nDays = 5;
							$fDays = 5;
							
								if (my_calendar_date_comp( $event_begin, my_calendar_add_date($today,-($nDays),0,0) )) {
									$diff = jd_date_diff_precise(strtotime($event_begin));
									$diff_days = $diff/(86400*30);
									$days = explode(".",$diff_days);
									$realStart = $days[0] - $nDays;
									$realFinish = $days[0] + $fDays;

									for ($realStart;$realStart<=$realFinish;$realStart++) { // jump forward to near present.
									$this_date = my_calendar_add_date($event_begin,0,$realStart,0);
										if ( my_calendar_date_comp( $event->event_begin,$this_date ) ) {
											${$realStart} = clone($event);
											${$realStart}->event_begin = $this_date;
											$arr_events[] = ${$realStart};
										}
									}								
								
								} else {							
								$realDays = -($nDays);
								for ($realDays;$realDays<=$fDays;$realDays++) { // for each event within plus or minus range, mod date and add to array.
								$this_date = my_calendar_add_date($event_begin,0,$realDays,0);
									if ( my_calendar_date_comp( $event->event_begin,$this_date ) == true ) {
										${$realDays} = clone($event);
										${$realDays}->event_begin = $this_date;
										$arr_events[] = ${$realDays};
									}
								}
								}
						break;
						// "U" is month by day
						case "U":
							$event_begin = $event->event_begin;
							$event_end = $event->event_end;
							$today = date('Y',time()+($offset)).'-'.date('m',time()+($offset)).'-'.date('d',time()+($offset));
							$nDays = 5;
							$fDays = 5;
							$day_of_event = date( 'D', strtotime($event->event_begin) );
							$week_of_event = week_of_month( date( 'd', strtotime($event->event_begin) ) );
							$day_diff = jd_date_diff($event_begin, $event_end);
							
								if (my_calendar_date_comp( $event_begin, my_calendar_add_date($today,-($nDays),0,0) )) {
									// this doesn't need to be precise; it only effects what dates will be checked.
									$diff = jd_date_diff_precise(strtotime($event_begin));
									$diff_days = floor($diff/(86400*30));
									$realStart = $diff_days - $nDays;
									$realFinish = $diff_days + $fDays;

									for ($realStart;$realStart<=$realFinish;$realStart++) { // jump forward to near present.
										$approxbegin = my_calendar_add_date($event_begin,0,$realStart,0);
										$approxend = my_calendar_add_date($event_end,0,$realStart,0);
										for ($n=-6;$n<=6;$n++) {
											$timestamp = strtotime(my_calendar_add_date($approxbegin,$n,0,0));
											$current_day = date('D',$timestamp);
											if ($current_day == $day_of_event) {
												$current_week = week_of_month( date( 'd',$timestamp));
												$current_date = date( 'd',$timestamp);
												if ($current_day == $day_of_event && $current_week == $week_of_event) {
													$date_of_event_this_month = $current_date;
												} else {
													for ($i = 1;$i<=31;$i++) {
													$string = date( 'Y', $timestamp ).'-'.date( 'm', $timestamp).'-'.$n;
													$week = week_of_month($i);
														if ( date('D',strtotime($string)) == $day_of_event && $week == $week_of_event ) {
															$date_of_event_this_month = $i;
															break;
														}											
													}
													if ( get_option('mc_no_fifth_week') == 'true' && $date_of_event_this_month == '' ) {
														$new_week_of_event = $week_of_event-1;
														for ($i=1;$i<=31;$i++) {
															$string = date( 'Y', $timestamp ).'-'.date('m', $timestamp).'-'.$i;
															if ( date('D',strtotime($string)) == $day_of_event && $week == $new_week_of_event ) {
																$date_of_event_this_month = $i;
																break;
															}
														}
													}
												}
												if ( ($current_day == $day_of_event && $current_week == $week_of_event) || ($current_date >= $date_of_event_this_month && $current_date <= $date_of_event_this_month+$day_diff && $date_of_event_this_month != '' ) ) {
													$begin = my_calendar_add_date($approxbegin,$n,0,0);
													$end = my_calendar_add_date($approxend,$n,0,0);
													${$realStart} = clone($event);
													${$realStart}->event_begin = $begin;
													${$realStart}->event_end = $end;							
													$arr_events[]=${$realStart};	
													break;
												}
											}
										}
									}									
								
								} else {							
								$realDays = -($nDays);
								for ($realDays;$realDays<=$fDays;$realDays++) { // for each event within plus or minus range, mod date and add to array.
										$approxbegin = my_calendar_add_date($event_begin,0,$realDays,0);
										$approxend = my_calendar_add_date($event_end,0,$realDays,0);
										for ($n=-6;$n<=6;$n++) {
											$timestamp = strtotime(my_calendar_add_date($approxbegin,$n,0,0));										
											$current_day = date('D',$timestamp);
											$current_week = week_of_month( date( 'd',$timestamp));
											$current_date = date( 'd',$timestamp);
											for ($i = 1;$i<=31;$i++) {
											$string = date( 'Y', $timestamp ).'-'.date( 'm', $timestamp).'-'.$n;
											$week = week_of_month($i);
												if ( date('D',strtotime($string)) == $day_of_event && $week == $week_of_event ) {
													$date_of_event_this_month = $i;
													break;
												}											
											}
											if ( get_option('mc_no_fifth_week') == 'true' && $date_of_event_this_month == '' ) {
												$new_week_of_event = $week_of_event-1;
												for ($i=1;$i<=31;$i++) {
													$string = date( 'Y', $timestamp ).'-'.date('m', $timestamp).'-'.$i;
													if ( date('D',strtotime($string)) == $day_of_event && $week == $new_week_of_event ) {
														$date_of_event_this_month = $i;
														break;
													}
												}					
											}											
											if ( ($current_day == $day_of_event && $current_week == $week_of_event) || ($current_date >= $date_of_event_this_month && $current_date <= $date_of_event_this_month+$day_diff && $date_of_event_this_month != '' ) ) {											
												$begin = my_calendar_add_date($approxbegin,$n,0,0);
												$end = my_calendar_add_date($approxend,$n,0,0);
												${$realDays} = clone($event);
												${$realDays}->event_begin = $begin;
												${$realDays}->event_end = $end;							
												$arr_events[]=${$realDays};
												break;
											} 
										}
									}
								}
						break;
						case "Y":
							$event_begin = $event->event_begin;
							$today = date('Y',time()+($offset)).'-'.date('m',time()+($offset)).'-'.date('d',time()+($offset));
							$nDays = 3;
							$fDays = 3;
								
								if (my_calendar_date_comp( $event_begin, my_calendar_add_date($today,-($nDays),0,0) )) {
									$diff = jd_date_diff_precise(strtotime($event_begin));
									$diff_days = $diff/(86400*365);
									$days = explode(".",$diff_days);
									$realStart = $days[0] - $nDays;
									$realFinish = $days[0] + $fDays;

									for ($realStart;$realStart<=$realFinish;$realStart++) { // jump forward to near present.
									$this_date = my_calendar_add_date($event_begin,0,0,$realStart);
										if ( my_calendar_date_comp( $event->event_begin,$this_date ) ) {
											${$realStart} = clone($event);
											${$realStart}->event_begin = $this_date;
											$arr_events[] = ${$realStart};
										}
									}								
								} else {							
								$realDays = -($nDays);
								for ($realDays;$realDays<=$fDays;$realDays++) { // for each event within plus or minus range, mod date and add to array.
								$this_date = my_calendar_add_date($event_begin,0,0,$realDays);
									if ( my_calendar_date_comp( $event->event_begin,$this_date ) == true ) {
										${$realDays} = clone($event);
										${$realDays}->event_begin = $this_date;
										$arr_events[] = ${$realDays};
									}
								}
								}
						break;
					}
				}
			} else {
				$arr_events[]=$event;
			}					
		}				
	} 
	return $arr_events;
}


function mc_limit_string($type='') {
global $user_ID;
	 $user_settings = get_option('mc_user_settings');
	 $limit_string = "";
	 if ( get_option('mc_user_settings_enabled') == 'true' && $user_settings['my_calendar_location_default']['enabled'] == 'on' || isset($_GET['loc']) && isset($_GET['ltype']) ) {
		if ( is_user_logged_in() ) {
			if (!isset($_GET['loc']) && !isset($_GET['ltype'])) {
				get_currentuserinfo();
				$current_settings = get_usermeta( $user_ID, 'my_calendar_user_settings' );
				$current_location = $current_settings['my_calendar_location_default'];
				$location_type = get_option('mc_location_type');
			} else {
				$current_location = urldecode($_GET['loc']);
				$location = urldecode($_GET['ltype']);
					switch ($location) {
						case "name":$location_type = "event_label";
						break;
						case "city":$location_type = "event_city";
						break;
						case "state":$location_type = "event_state";
						break;
						case "zip":$location_type = "event_postcode";
						break;
						case "country":$location_type = "event_country";
						break;
						default:$location_type = "event_label";
						break;
					}			
			}
			if ($current_location != 'none') {
				if ($select_category == "") {
					$limit_string = "$location_type='$current_location'";
					$limit_string .= ($type=='all')?'':" AND";
				} else {
					$limit_string = "AND $location_type='$current_location'";
					$limit_string .= ($type=='all')?'':" AND";				
				}
			} 
		}
	 }
	 return $limit_string;
}

function mc_user_timezone($type='') {
global $user_ID;
	 $user_settings = get_option('mc_user_settings');
	 if ( get_option('mc_user_settings_enabled') == 'true' && $user_settings['my_calendar_tz_default']['enabled'] == 'on' ) {
		if ( is_user_logged_in() ) {
			get_currentuserinfo();
			$current_settings = get_usermeta( $user_ID, 'my_calendar_user_settings' );
			$tz = $current_settings['my_calendar_tz_default'];
		} else {
			$tz = '';
		}
	 }
	 if ($tz == get_option('gmt_offset') || $tz == 'none' || $tz == '' ) {
		$gtz = '';
	 } else if ( $tz < get_option('gmt_offset') ) {
		$gtz = -(abs( get_option('gmt_offset') - $tz ) );
	 } else {
		$gtz = (abs( get_option('gmt_offset') - $tz ) );
	 }
	 return $gtz;
}
// Grab all events for the requested date from calendar
function my_calendar_grab_events($y,$m,$d,$category=null) {

	if (!checkdate($m,$d,$y)) {
		return;
	}

     global $wpdb;
	 if ( $category != null ) {
		$select_category = mc_select_category($category);
	 } else {
		$select_category = "";
	 }
     $arr_events = array();

     // set the date format
     $date = $y . '-' . $m . '-' . $d;

	 $limit_string = mc_limit_string();

	// echo $current_settings['my_calendar_location_default'];
    // First we check for conventional events. These will form the first instance of a recurring event
    // or the only instance of a one-off event
	// echo $category;
	// echo '<br />';
	
	if($category == 'all') {} else {$cat_name = 'wp_my_calendar_events_categories.category_id = \''.$category.'\' AND';}

		$sql1 = "select * from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id WHERE $cat_name $limit_string event_begin <= '$date' AND event_end >= '$date' AND event_recur = 'S' ORDER BY wp_my_calendar.event_id"; 
		
		
			// $event_cats = $wpdb->get_results($sql);

			// old SQL query "SELECT * FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_begin <= '$date' AND event_end >= '$date' AND event_recur = 'S' ORDER BY event_id"
// echo $sql1;
// echo '<br />';
     $events = $wpdb->get_results($sql1); 
// print_r($events);
	if (!empty($events)) {
		foreach($events as $event) {
			$this_event_start = strtotime("$event->event_begin $event->event_time");
			$this_event_end = strtotime("$event->event_end $event->event_endtime");
			$event->event_start_ts = $this_event_start;
			$event->event_end_ts = $this_event_end;
			$arr_events[]=$event;
		}
    }

// Beautiful new query
	$events = $wpdb->get_results("select *,event_begin AS event_original_begin from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id WHERE $category_name $limit_string event_recur = 'Y' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin)
UNION ALL
select *,event_begin AS event_original_begin from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id WHERE $category_name $limit_string event_recur = 'M' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats = 0
UNION ALL
select *,event_begin AS event_original_begin from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id WHERE $category_name $limit_string event_recur = 'M' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats != 0 AND (PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM '$date'),EXTRACT(YEAR_MONTH FROM event_begin))) <= event_repeats
UNION ALL
select *,event_begin AS event_original_begin from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id WHERE $category_name $limit_string event_recur = 'U' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats = 0
UNION ALL
select *,event_begin AS event_original_begin from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id WHERE $category_name $limit_string event_recur = 'B' AND '$date' >= event_begin AND event_repeats != 0 AND (event_repeats*14) >= (TO_DAYS('$date') - TO_DAYS(event_end))
UNION ALL
select *,event_begin AS event_original_begin from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id WHERE $category_name $limit_string event_recur = 'D' AND '$date' >= event_begin AND event_repeats = 0
UNION ALL
select *,event_begin AS event_original_begin from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id WHERE $category_name $limit_string event_recur = 'D' AND '$date' >= event_begin AND event_repeats != 0 AND (event_repeats) >= (TO_DAYS('$date') - TO_DAYS(event_end))	
ORDER BY wp_my_calendar.event_id");

	// Old query
	// $events = $wpdb->get_results("
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'Y' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin)
	// UNION ALL
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'M' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats = 0
	// UNION ALL
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'M' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats != 0 AND (PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM '$date'),EXTRACT(YEAR_MONTH FROM event_begin))) <= event_repeats
	// UNION ALL
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'U' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats = 0
	// UNION ALL
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'U' AND EXTRACT(YEAR FROM '$date') >= EXTRACT(YEAR FROM event_begin) AND event_repeats != 0 AND (PERIOD_DIFF(EXTRACT(YEAR_MONTH FROM '$date'),EXTRACT(YEAR_MONTH FROM event_begin))) <= event_repeats
	// UNION ALL	
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'B' AND '$date' >= event_begin AND event_repeats = 0
	// UNION ALL
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'B' AND '$date' >= event_begin AND event_repeats != 0 AND (event_repeats*14) >= (TO_DAYS('$date') - TO_DAYS(event_end))
	// UNION ALL
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'W' AND '$date' >= event_begin AND event_repeats = 0
	// UNION ALL
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'W' AND '$date' >= event_begin AND event_repeats != 0 AND (event_repeats*7) >= (TO_DAYS('$date') - TO_DAYS(event_end))	
	// UNION ALL
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'D' AND '$date' >= event_begin AND event_repeats = 0
	// UNION ALL
	// SELECT *,event_begin AS event_original_begin FROM " . MY_CALENDAR_TABLE . " JOIN " . MY_CALENDAR_CATEGORIES_TABLE . " ON (event_category=category_id) WHERE $select_category $limit_string event_recur = 'D' AND '$date' >= event_begin AND event_repeats != 0 AND (event_repeats) >= (TO_DAYS('$date') - TO_DAYS(event_end))	
	// ORDER BY event_id");
				
	if (!empty($events)) {
			foreach($events as $event) {
			// add timestamps for start and end
				$this_event_start = strtotime("$date $event->event_time");
				$this_event_end = strtotime("$date $event->event_endtime");
				$event->event_start_ts = $this_event_start;
				$event->event_end_ts = $this_event_end;			
				switch ($event->event_recur) {
					case 'Y':
				// Technically we don't care about the years, but we need to find out if the 
				// event spans the turn of a year so we can deal with it appropriately.
				$year_begin = date('Y',strtotime($event->event_begin));
				$year_end = date('Y',strtotime($event->event_end));

					if ($year_begin == $year_end) {
						if (date('m-d',strtotime($event->event_begin)) <= date('m-d',strtotime($date)) && 
							date('m-d',strtotime($event->event_end)) >= date('m-d',strtotime($date))) {
								$arr_events[]=$event;
						}
					} else if ($year_begin < $year_end) {
						if (date('m-d',strtotime($event->event_begin)) <= date('m-d',strtotime($date)) || 
							date('m-d',strtotime($event->event_end)) >= date('m-d',strtotime($date))) {
								$arr_events[]=$event;
						}
					}
					break;
					case 'M':
				    // Technically we don't care about the years or months, but we need to find out if the 
				    // event spans the turn of a year or month so we can deal with it appropriately.
				    $month_begin = date('m',strtotime($event->event_begin));
				    $month_end = date('m',strtotime($event->event_end));

					    if ($month_begin == $month_end) {
							if (date('d',strtotime($event->event_begin)) <= date('d',strtotime($date)) && 
								date('d',strtotime($event->event_end)) >= date('d',strtotime($date))) {
						      		$arr_events[]=$event;
							}
					    } else if ($month_begin < $month_end) {
							if ( ($event->event_begin <= date('Y-m-d',strtotime($date))) && (date('d',strtotime($event->event_begin)) <= date('d',strtotime($date)) || 
								date('d',strtotime($event->event_end)) >= date('d',strtotime($date))) )	{
						      		$arr_events[]=$event;
							}
					    }					
					break;
					case 'U':
				    // Technically we don't care about the years or months, but we need to find out if the 
				    // event spans the turn of a year or month so we can deal with it appropriately.
				    $month_begin = date( 'm',strtotime($event->event_begin) );
				    $month_end = date( 'm',strtotime($event->event_end) );
					$day_of_event = date( 'D',strtotime($event->event_begin) );
					$date_of_event = date( 'd',strtotime($event->event_begin) );
					//$end_day_of_event = date( 'D',strtotime($event->event_end) );
					//$end_date_of_event = date( 'd',strtotime($event->event_end) );					
					$current_day = date('D',strtotime($date));
					$current_date = date('d',strtotime($date));
					$week_of_event = week_of_month($date_of_event);
					$current_week = week_of_month($current_date);
					
					$day_diff = jd_date_diff($event->event_begin,$event->event_end);				
					
					for ($i=1;$i<=31;$i++) {
						$string = date( 'Y',strtotime($date) ).'-'.date('m',strtotime($date)).'-'.$i;
						$week = week_of_month($i);
						if ( date('D',strtotime($string)) == $day_of_event && $week == $week_of_event ) {
							$date_of_event_this_month = $i;
							break;
						}
					}
					if ( get_option('mc_no_fifth_week') == 'true' && $date_of_event_this_month == '' ) {
						$new_week_of_event = $week_of_event-1;
						for ($i=1;$i<=31;$i++) {
							$string = date( 'Y',strtotime($date) ).'-'.date('m',strtotime($date)).'-'.$i;
							if ( date('D',strtotime($string)) == $day_of_event && $week == $new_week_of_event ) {
								$date_of_event_this_month = $i;
								break;
							}
						}					
					}

					if ( my_calendar_date_comp($event->event_begin,$date) ) {
						if ( ($current_day == $day_of_event && $current_week == $week_of_event) || ($current_date >= $date_of_event_this_month && $current_date <= $date_of_event_this_month+$day_diff && $date_of_event_this_month != '' ) ) {	
							if ($month_begin == $month_end) {
								if (true) {
									$arr_events[]=$event;
								}
							} else if ($month_begin < $month_end) {
								if (true) {
									$arr_events[]=$event;
								}
							}	
						} else {
							break;
						}
					}						
					break;					
					case 'B':
				    // Now we are going to check to see what day the original event
				    // fell on and see if the current date is both after it and on 
				    // the correct day. If it is, display the event!
				    $day_start_event = date('w',strtotime($event->event_begin));
				    $day_end_event = date('w',strtotime($event->event_end));
				    $current_day = date('w',strtotime($date));
					$current_date = date('Y-m-d',strtotime($date));
					$start_date = $event->event_begin;
					
					if ($event->event_repeats != 0) {
						for ($n=0;$n<=$event->event_repeats;$n++) {
							if ( $current_date == my_calendar_add_date($start_date,(14*$n)) ) {
							    if ($day_start_event > $day_end_event) {
									if (($day_start_event <= $current_day) || ($current_day <= $day_end_event))	{
									$arr_events[]=$event;
							    	}
							    } else if (($day_start_event < $day_end_event) || ($day_start_event == $day_end_event)) {
									if (($day_start_event <= $current_day) && ($current_day <= $day_end_event))	{
									$arr_events[]=$event;
							    	}		
							    }
							}
						}	
					} else {
						// get difference between today and event start date in biweekly periods; grab enough events to fill max poss.
						$diffdays = jd_date_diff($start_date,$current_date);
						$diffper = floor($diffdays/14) - 2;
						$advanceper = get_option('my_calendar_show_months') * 3;
						$diffend = $diffper + $advanceper;
						for ($n=$diffper;$n<=$diffend;$n++) {
							if ( $current_date == my_calendar_add_date($start_date,(14*$n)) ) {
								$arr_events[]=$event;
							}
						}
					}
					break;
					case 'W':
				    // Now we are going to check to see what day the original event
				    // fell on and see if the current date is both after it and on 
				    // the correct day. If it is, display the event!
				    $day_start_event = date('D',strtotime($event->event_begin));
				    $day_end_event = date('D',strtotime($event->event_end));
				    $current_day = date('D',strtotime($date));

				    $plan = array("Mon"=>1,"Tue"=>2,"Wed"=>3,"Thu"=>4,"Fri"=>5,"Sat"=>6,"Sun"=>7);

				    if ($plan[$day_start_event] > $plan[$day_end_event]) {
						if (($plan[$day_start_event] <= $plan[$current_day]) || ($plan[$current_day] <= $plan[$day_end_event]))	{
						$arr_events[]=$event;
				    	}
				    } else if (($plan[$day_start_event] < $plan[$day_end_event]) || ($plan[$day_start_event]== $plan[$day_end_event])) {
						if (($plan[$day_start_event] <= $plan[$current_day]) && ($plan[$current_day] <= $plan[$day_end_event]))	{
						$arr_events[]=$event;
				    	}		
				    }					
					break;
					case 'D':
						$arr_events[]=$event;					
					break;
					
				}
			}
     	}
	 // $arr_events = array_unique($arr_events);
	// echo '<pre>';
	// print_r($arr_events);
	// echo '</pre>';
    return $arr_events;
}

function mc_month_comparison($month) {
	$offset = (60*60*get_option('gmt_offset'));
	$current_month = date("n", time()+($offset));
	if (isset($_GET['yr']) && isset($_GET['month'])) {
		if ($month == $_GET['month']) {
			return ' selected="selected"';
		  }
	} elseif ($month == $current_month) { 
		return ' selected="selected"'; 
	}
}

function mc_year_comparison($year) {
	$offset = (60*60*get_option('gmt_offset'));
		$current_year = date("Y", time()+($offset));
		if (isset($_GET['yr']) && isset($_GET['month'])) {
			if ($year == $_GET['yr']) {
				return ' selected="selected"';
			}
		} else if ($year == $current_year) {
			return ' selected="selected"';
		}
}


function my_calendar_is_odd( $int ) {
  return( $int & 1 );
}

function mc_can_edit_event($author_id) {
	global $user_ID;
	get_currentuserinfo();
	$user = get_userdata($user_ID);	
	
	if ( current_user_can( get_option('mc_event_edit_perms') ) ) {
			return true;
		} elseif ( $user_ID == $author_id ) {
			return true;
		} else {
			return false;
		}
}

// compatibility of clone keyword between PHP 5 and 4
if (version_compare(phpversion(), '5.0') < 0) {
	eval('
	function clone($object) {
	  return $object;
	}
	');
}

// Mail functions by Roland
function my_calendar_send_email( $details ) {
$event = event_as_array($details);

	if ( get_option('mc_event_mail') == 'true' ) {	
		$to = get_option('mc_event_mail_to');
		$subject = get_option('mc_event_mail_subject');
		$message = jd_draw_template( $event, get_option('mc_event_mail_message') );
		$mail = wp_mail($to, $subject, $message);
	}
}
?>
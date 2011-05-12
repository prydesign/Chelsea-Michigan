<?php
/*
Plugin Name: Silencesoft RSS Reader
Plugin URI: http://www.silencesoft.net
Description: A plugin to read external rss feeds
Version: 0.6
Author: Byron Herrera
Author URI: http://byronh.axul.net

    Copyright 2008  Byron Herrera  (email : bh at axul dot net)

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

// if ( function_exists('current_user_can') && !current_user_can('manage_options') ) die(__('Error.'));
// if (! user_can_access_admin_page()) wp_die( __('You do not have sufficient permissions to access this page.') );

define('SIL_RSS_VERSION', '0.6');
define('SIL_RSS_PLUGINDIR', dirname(__FILE__));
// define('SIL_RSS_URL', get_bloginfo('wpurl') . '/wp-content/plugins/sil_rss_reader/');
define('SIL_RSS_URL', get_bloginfo('wpurl') . '/wp-content/plugins/'.basename(dirname(__FILE__)).'/');

define('SIL_RSS_TABLE_SITES', $wpdb->prefix . "sil_rss");
define('SIL_RSS_TABLE_CATEGORIES', $wpdb->prefix . "sil_rss_categories");
define('SIL_RSS_TABLE_SITES_BY_CATEGORY', $wpdb->prefix . "sil_rss_by_category");

/*
$sil_rss_table_name = $wpdb->prefix . "sil_rss";
$sil_rss_table_categories = $wpdb->prefix . "sil_rss_categories";
$sil_rss_table_name_by_category = $wpdb->prefix . "sil_rss_by_category";
$sql = "alter table " . SIL_RSS_TABLE_SITES . "
add description VARCHAR(255) NOT NULL after image";
		$wpdb->query($sql);
echo $sql;

$sql = "CREATE TABLE " . $sil_rss_table_categories . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		category VARCHAR(100) NOT NULL,
		UNIQUE KEY id (id)
		);";

$wpdb->query($sql);
echo $sql;
$sql = "CREATE TABLE " . $sil_rss_table_name_by_category . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		category_id VARCHAR(100) NOT NULL,
		rss_id VARCHAR(100) NOT NULL,
		UNIQUE KEY id (id)
		);";
$wpdb->query($sql);
echo $sql;
*/

function sil_rss_install() {
	global $wpdb;

	$sil_rss_table_name = $wpdb->prefix . "sil_rss";
	$sil_rss_table_categories = $wpdb->prefix . "sil_rss_categories";
	$sil_rss_table_name_by_category = $wpdb->prefix . "sil_rss_by_category";

	$ok = 0;
	// if($wpdb->get_var("SHOW TABLES LIKE '$sil_rss_table_name'") != $sil_rss_table_name) {
	if(!sil_rss_table_exists($sil_rss_table_name)) {
		$sql = "CREATE TABLE " . $sil_rss_table_name . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time datetime NOT NULL,
		name VARCHAR(100) NOT NULL,
		author VARCHAR(100) NOT NULL,
		gravatar VARCHAR(255) NOT NULL,
		image VARCHAR(255) NOT NULL,
		description VARCHAR(255) NOT NULL,
		url VARCHAR(150) NOT NULL,
		link VARCHAR(150) NOT NULL,
		UNIQUE KEY id (id)
		);";

		$wpdb->query($sql);

		$feed_name = "WordPress Blog";
		$feed_author = "WordPress";
		$feed_description = "WordPress Blog";
		$feed_link = "http://wordpress.org/development/";
		$feed_url = "http://wordpress.org/development/feed/";

		$sql = "INSERT INTO " . $sil_rss_table_name .
		" (id, time, name, author, link, description, url) " .
		"VALUES (1, NOW(),'" . $wpdb->escape($feed_name) . "','" . $wpdb->escape($feed_author) . "','" . $wpdb->escape($feed_link) . "','" . $wpdb->escape($feed_descripton) . "','" . $wpdb->escape($feed_url) . "')";
		$wpdb->query($sql);

		$feed_name = "Web Log";
		$feed_author = "Web Log";
		$feed_description = "Web Log";
		$feed_link = "http://weblogtoolscollection.com/";
		$feed_url = "http://feeds.feedburner.com/weblogtoolscollection";
		$sql = "INSERT INTO " . $sil_rss_table_name .
		" (id, time, name, author, link, description, url) " .
		"VALUES (2, NOW(),'" . $wpdb->escape($feed_name) . "','" . $wpdb->escape($feed_author) . "','" . $wpdb->escape($feed_link) . "','" . $wpdb->escape($feed_descripton) . "','" . $wpdb->escape($feed_url) . "')";
		$wpdb->query($sql);

		$ok = 1;
	}

	if(!sil_rss_table_exists($sil_rss_table_categories)) {
		$sql = "CREATE TABLE " . $sil_rss_table_categories . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		category VARCHAR(100) NOT NULL,
		UNIQUE KEY id (id)
		);";

		$wpdb->query($sql);

		$category_name = "Test";
		$sql = "INSERT INTO " . $sil_rss_table_categories .
		" (id, category) " .
		"VALUES (1, '" . $wpdb->escape($category_name) . "')";
		$wpdb->query($sql);

	}

	if(!sil_rss_table_exists($sil_rss_table_name_by_category)) {
		$sql = "CREATE TABLE " . $sil_rss_table_name_by_category . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		category_id VARCHAR(100) NOT NULL,
		rss_id VARCHAR(100) NOT NULL,
		UNIQUE KEY id (id)
		);";

		$wpdb->query($sql);

		$sql = "INSERT INTO " . $sil_rss_table_name_by_category .
		" (id, category_id, rss_id) " .
		"VALUES (1, 1, 1)," .
		"(2, 1, 2)";
		$wpdb->query($sql);

	}

	
	if ($ok) {
		add_option("sil_rss_version", SIL_RSS_VERSION, 'no');

		// Options
		add_option("sil_rss_total", 10, 'no');
		add_option("sil_rss_multiple", 1, 'no');
		add_option("sil_rss_date_format", "F d", 'no');
		add_option("sil_rss_pre_list", "<ul class='sil_rss_list'>", 'no');
		add_option("sil_rss_post_list", '</ul>', 'no');
		add_option("sil_rss_html_list", '<li class="sil_rss_info">[image]<strong>[date]</strong> &middot; [blog] <br /><a href="[link]">[title]</a><br />[content] (...)<br /><a href="[url]">[author]</a></li>', 'no');
		add_option("sil_rss_image_size_h", 50, 'no');
		add_option("sil_rss_image_size_w", 50, 'no');
		add_option("sil_rss_use_cache", 1, 'no');
		add_option("sil_rss_show_categories", 1, 'no');
		add_option("sil_rss_show_feed_url", 1, 'no');
		
		// Widget
		add_option("sil_rss_widget_title", "RSS Feeds", 'no');
		add_option("sil_rss_widget_items", 5, 'no');
		add_option("sil_rss_widget_pre_list", "<ul class='sil_rss_widget_list'>", 'no');
		add_option("sil_rss_widget_post_list", '</ul>', 'no');
		add_option("sil_rss_widget_html_list", '<li class="sil_rss_info"><strong>[date]</strong> &middot; [blog] <br /><a href="[link]">[title]</a><br /><a href="[url]">[author]</a></li>', 'no');

		// External RSS
		add_option("sil_rss_external_title", "RSS Feeds", 'no');
		add_option("sil_rss_external_link", "http://www.mysite.com", 'no');
		add_option("sil_rss_external_description", "My Site Description.", 'no');
		add_option("sil_rss_external_html", '[image]<br /><strong>[author]</strong><br /><a href="[url]"><strong>[blog]</strong></a><br />[content]', 'no');

		// Export OPML
		add_option("sil_rss_opml_title", "OPML", 'no');
		add_option("sil_rss_opml_owner", "My Site", 'no');
		add_option("sil_rss_opml_email", "info@mysite.com", 'no');
		add_option("sil_rss_opml_date", date('Y-m-d H:m:s'), 'no');

	} else {
		// 
	}
}

register_activation_hook(__FILE__, 'sil_rss_install');
// register_activation_hook( basename(__FILE__), 'sil_rss_install' );


// update to v. 0.3
function sil_rss_update() {
	global $wpdb;

	$sil_rss_table_name = $wpdb->prefix . "sil_rss";
	$sil_rss_actual = get_option("sil_rss_version");
	if( $sil_rss_actual == SIL_RSS_VERSION) return;

	update_option("sil_rss_version", SIL_RSS_VERSION);

	if( $sil_rss_actual != "0.3") return;

	if ( !function_exists('dbDelta') ) {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	}

	add_option("sil_rss_show_categories", 1, 'no');
	add_option("sil_rss_show_feed_url", 1, 'no');
}

add_action('admin_menu', 'sil_rss_update');
// update to v. 0.4


function sil_rss_options_page() {

/*
if (isset($_POST['submitted']) && !empty($_POST['submitted']))
	{
		$sil_rss_total = (string) $_POST['sil_rss_total'];
		update_option("sil_rss_total", $sil_rss_total);

	}
	*/
	?>
<div class="wrap">
<h2>RSS Reader - <?php _e('Options', 'sil_rss'); ?></h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Feeds total', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_total" value="<?php echo get_option('sil_rss_total'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Multiple items for feed', 'sil_rss'); ?>:</th>
<td><select name="sil_rss_multiple">
<option value="1"<?php if (get_option('sil_rss_multiple') == '1') echo ' selected="selected"'; ?>>Yes</option>
<option value="0"<?php if (get_option('sil_rss_multiple') == '0') echo ' selected="selected"'; ?>>No</option>
</select></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Show categories navigator on page', 'sil_rss'); ?>:</th>
<td><select name="sil_rss_show_categories">
<option value="1"<?php if (get_option('sil_rss_show_categories') == '1') echo ' selected="selected"'; ?>>Yes</option>
<option value="0"<?php if (get_option('sil_rss_show_categories') == '0') echo ' selected="selected"'; ?>>No</option>
</select></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Show feed url on page', 'sil_rss'); ?>:</th>
<td><select name="sil_rss_show_feed_url">
<option value="1"<?php if (get_option('sil_rss_show_feed_url') == '1') echo ' selected="selected"'; ?>>Yes</option>
<option value="0"<?php if (get_option('sil_rss_show_feed_url') == '0') echo ' selected="selected"'; ?>>No</option>
</select></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Public OPML', 'sil_rss'); ?>:</th>
<td><select name="sil_rss_public_opml">
<option value="1"<?php if (get_option('sil_rss_public_opml') == '1') echo ' selected="selected"'; ?>>Yes</option>
<option value="0"<?php if (get_option('sil_rss_public_opml') == '0') echo ' selected="selected"'; ?>>No</option>
</select></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Date format', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_date_format" value="<?php echo get_option('sil_rss_date_format'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Use SimplePie cache', 'sil_rss'); ?>:</th>
<td><select name="sil_rss_use_cache">
<option value="1"<?php if (get_option('sil_rss_use_cache') == '1') echo ' selected="selected"'; ?>>Yes</option>
<option value="0"<?php if (get_option('sil_rss_use_cache') == '0') echo ' selected="selected"'; ?>>No</option>
</select></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('List start', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_pre_list" value="<?php echo get_option('sil_rss_pre_list'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('List item', 'sil_rss'); ?>:</th>
<td><textarea name="sil_rss_html_list" rows="8" cols="60"><?php echo get_option('sil_rss_html_list'); ?></textarea>
</tr>
<tr valign="top">
<th scope="row"><?php _e('List end', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_post_list" value="<?php echo get_option('sil_rss_post_list'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Image height', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_image_size_h" value="<?php echo get_option('sil_rss_image_size_h'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Image width', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_image_size_w" value="<?php echo get_option('sil_rss_image_size_w'); ?>" /></td>
</tr>

</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="sil_rss_total,sil_rss_multiple,sil_rss_date_format,sil_rss_show_categories,sil_rss_show_feed_url,sil_rss_use_cache,sil_rss_pre_list,sil_rss_html_list,sil_rss_post_list,sil_rss_image_size_h,sil_rss_image_size_w" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>

</div>
<?php
}

function sil_rss_save() {
	global $wpdb;
	$sil_rss_table_name = $wpdb->prefix . "sil_rss";

		$name = (string) $_POST['name'];
		$name = $wpdb->escape($name);
		$url = (string) $_POST['url'];
		$url = $wpdb->escape($url);
		$link = (string) $_POST['link'];
		$link = $wpdb->escape($link);
		$gravatar = (string) $_POST['gravatar'];
		$gravatar = $wpdb->escape($gravatar);
		// begin image
		$image = (string) $_FILES['image']['name'];
		if (strlen($image)) {
			$image = "_".$image;
			$image = time().ereg_replace("[^a-zA-Z0-9_.]", '_', $image);
			if (is_uploaded_file($_FILES['image']['tmp_name']))
			{
				if(move_uploaded_file($_FILES['image']['tmp_name'], SIL_RSS_PLUGINDIR.'/images/'.$image)) {
					$tmp_image = SIL_RSS_PLUGINDIR.'/images/'.$image;
					$tmp_size_w = get_option('sil_rss_image_size_w');
					$tmp_size_h = get_option('sil_rss_image_size_h');
					$info_image = getimagesize($tmp_image);
 					if ($info_image[0] > $tmp_size_w || $info_image[1] > $tmp_size_h) {
						// $tmp_image = sil_rss_resize_image($tmp_image, 50, 50, $tmp_image);
						sil_rss_resize_then_crop($tmp_image, $tmp_size_w, $tmp_size_h, 255, 255, 255);
					}
					if (isset($_GET['edit']) && !empty($_GET['edit']) && ($_GET['edit'] > 0)) {
						$sql = "SELECT * FROM ".$sil_rss_table_name." where id = '".(int)$_GET['edit']."'";
						$results = $wpdb->get_row($sql);
						if (is_file(SIL_RSS_PLUGINDIR.'/images/'.$results->image))
						if (unlink(SIL_RSS_PLUGINDIR.'/images/'.$results->image)) {
						}
						$image = " image = '".$image."',";
					}
				} else 
					$image = "";
			}
		}
		// end image
		$author = (string) $_POST['author'];
		$author = $wpdb->escape($author);
		$time = (string) $_POST['time'];
		$time = $wpdb->escape($time);
		$description = (string) $_POST['description'];
		$description = $wpdb->escape($description);
		if ($time == "") $time = "NOW()";
		else $time = "'".$time."'";
		//update_option("new_option_name", $new_option_name);
		// if (isset($_GET['edit_rss']) && !empty($_GET['edit_rss']) && ($_GET['edit_rss'] > 0)) {
		if (isset($_GET['edit']) && !empty($_GET['edit']) && ($_GET['edit'] > 0)) {
			$sql = "update " . SIL_RSS_TABLE_SITES .
			" set time = ".$time.", name = '".$name."', author = '".$author."',".$image." gravatar = '".$gravatar."', link = '".$link."', description = '".$description."', url = '".$url."' where id = '".(int)$_GET['edit']."'";
			// " set time = ".$time.", name = '".$name."', author = '".$author."', image = '".$image."', link = '".$link."', url = '".$url."' where id = '".(int)$_GET['edit_rss']."'";
		} else {
			$sql = "INSERT INTO " . SIL_RSS_TABLE_SITES .
			" (time, name, author, image, gravatar, link, description, url) " .
			"VALUES (".$time.",'" . $name . "','" . $author . "','" . $image . "','" . $gravatar . "','" . $link . "','" . $description . "','" . $url . "')";
		}
		$wpdb->query($sql);
		if ( !function_exists('wp_redirect') ) {
			include_once("../wp-includes/pluggable.php");
		}
		// wp_redirect('edit.php?page=sil_rss_reader/sil_rss_reader.php&action=saved');
		wp_redirect('admin.php?page=sil_rss_manage_page&ok=1');
		exit;
}

if (isset($_POST['action_rss']) && !empty($_POST['action_rss']))
{
	sil_rss_save();
}

function sil_rss_save_category() {
	global $wpdb;

		$category = (string) $_POST['category'];
		$category = $wpdb->escape($category);
		if (isset($_GET['edit_cat']) && !empty($_GET['edit_cat']) && ($_GET['edit_cat'] > 0)) {
			$sql = "update " . SIL_RSS_TABLE_CATEGORIES .
			" set category = '".$category."' where id = '".(int)$_GET['edit_cat']."'";
		} else {
			$sql = "INSERT INTO " . SIL_RSS_TABLE_CATEGORIES .
			" (category) " .
			"VALUES ('" . $category . "')";
		}
		$wpdb->query($sql);
		if ( !function_exists('wp_redirect') ) {
			include_once("../wp-includes/pluggable.php");
		}
		wp_redirect('admin.php?page=sil_rss_manage_categories_page');
		exit;
}

if (isset($_POST['action_cat']) && !empty($_POST['action_cat']))
{
	sil_rss_save_category();
}

function sil_rss_save_category_items() {
	global $wpdb;

	// save categories at cat page
	if (isset($_GET['edit_cat']) && !empty($_GET['edit_cat'])) {
		$sql = "select * from " . SIL_RSS_TABLE_SITES_BY_CATEGORY . " where category_id = " . (int)$_GET["edit_cat"];

	$results = $wpdb->get_results($sql);
	foreach($results as $result)
	{
		$delete = true;
		foreach ($_POST["right_cat"] as $keyC => $valueC) {
			if ($result->rss_id == $valueC) $delete = false;
		}
		if ($delete) {
			$sql = "delete from ". SIL_RSS_TABLE_SITES_BY_CATEGORY .
			" where id = '" . $result->id . "';";
			$wpdb->query($sql);
		}
	}
	
	$sql = "";
	foreach ($_POST["right_cat"] as $keyC => $valueC) {
		$insert = true;
		foreach($results as $result)
		{
			if ($result->rss_id == $valueC) $insert = false;
		}
		if ($insert) {
			if (strlen($sql)) $sql .= ", ";
			$sql .= "('".(int)$_GET["edit_cat"]."', '".$valueC."')";
		}
	}
	if (strlen($sql)) {
			$sql = "INSERT INTO " . SIL_RSS_TABLE_SITES_BY_CATEGORY .
			" (category_id, rss_id) 
			VALUES " . $sql;
			$wpdb->query($sql);
	}
	
	if ( !function_exists('wp_redirect') ) {
		include_once("../wp-includes/pluggable.php");
	}
		wp_redirect('admin.php?page=sil_rss_manage_categories_page');
		exit;
	}

	// save categories at post page
	if (isset($_GET['edit']) && !empty($_GET['edit'])) {
		$sql = "select * from " . SIL_RSS_TABLE_SITES_BY_CATEGORY . " where rss_id = " . (int)$_GET["edit"];

	$results = $wpdb->get_results($sql);
	foreach($results as $result)
	{
		$delete = true;
		foreach ($_POST["right_cat"] as $keyC => $valueC) {
			if ($result->category_id == $valueC) $delete = false;
		}
		if ($delete) {
			$sql = "delete from ". SIL_RSS_TABLE_SITES_BY_CATEGORY .
			" where id = '" . $result->id . "';";
			$wpdb->query($sql);
		}
	}
	
	$sql = "";
	foreach ($_POST["right_cat"] as $keyC => $valueC) {
		$insert = true;
		foreach($results as $result)
		{
			if ($result->category_id == $valueC) $insert = false;
		}
		if ($insert) {
			if (strlen($sql)) $sql .= ", ";
			$sql .= "('".(int)$_GET["edit"]."', '".$valueC."')";
		}
	}
	if (strlen($sql)) {
			$sql = "INSERT INTO " . SIL_RSS_TABLE_SITES_BY_CATEGORY .
			" (rss_id, category_id) 
			VALUES " . $sql;
			$wpdb->query($sql);
	}
	
	if ( !function_exists('wp_redirect') ) {
		include_once("../wp-includes/pluggable.php");
	}
		wp_redirect('admin.php?page=sil_rss_manage_page');
		exit;
	}

}

if (isset($_POST['action_cat_val']) && !empty($_POST['action_cat_val']))
{
	sil_rss_save_category_items();
}



function sil_rss_delete() {
	global $wpdb;
	$sil_rss_table_name = $wpdb->prefix . "sil_rss";
	
	$sql = "delete from " . SIL_RSS_TABLE_SITES .
	" where id = '".(int)$_GET['del_rss']."';";
	
	$wpdb->query($sql);
	if ( !function_exists('wp_redirect') ) {
		include_once("../wp-includes/pluggable.php");
	}
	// wp_redirect('edit.php?page=sil_rss_reader/sil_rss_reader.php&action=saved');
	wp_redirect('admin.php?page=sil_rss_manage_page');
}

if (isset($_GET['del_rss']) && !empty($_GET['del_rss']))
{
	sil_rss_delete();
}

function sil_rss_edit_page() {
	global $wpdb;
	$sil_rss_table_name = $wpdb->prefix . "sil_rss";

	// $sql = "SELECT * FROM ".$sil_rss_table_name." where id = '".(int)$_GET['edit_rss']."'";
	$sql = "SELECT * FROM ".$sil_rss_table_name." where id = '".(int)$_GET['edit']."'";

	// if (isset($_GET['edit_rss']) && !empty($_GET['edit_rss'])) {
	if (isset($_GET['edit']) && !empty($_GET['edit'])) {
		$results = $wpdb->get_row($sql);
		$result[id] = $results->id;
		$result[name] = $results->name;
		$result[author] = $results->author;
		$result[image] = $results->image;
		$result[gravatar] = $results->gravatar;
		$result[link] = $results->link;
		$result[description] = $results->description;
		$result[url] = $results->url;
		$result[time] = $results->time;
	}

if ($_GET["ok"] == 1)
	echo '<div id="message" class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>';
?>
<div class="wrap">
<h2>RSS Reader - Feed</h2>

<form method="post" enctype="multipart/form-data" action="">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Title', 'sil_rss'); ?></th>
<td><input type="text" name="name" value="<?php echo $result[name]; ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Site Url', 'sil_rss'); ?></th>
<td><input type="text" name="link" value="<?php echo $result[link]; ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Feed Url', 'sil_rss'); ?></th>
<td><input type="text" name="url" value="<?php echo $result[url]; ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Author', 'sil_rss'); ?></th>
<td><input type="text" name="author" value="<?php echo $result[author]; ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Description', 'sil_rss'); ?></th>
<td><textarea name="description" rows="8" cols="60"><?php echo $result[description]; ?></textarea></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Image', 'sil_rss'); ?></th>
<td><input name="image" type="file" /><?php if (strlen($result[image])) { ?><img src="<?php echo SIL_RSS_URL.'images/'.$result[image]; ?>" width="30" height="40" /><?php } ?></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Gravatar', 'sil_rss'); ?></th>
<td><input type="text" name="gravatar" value="<?php echo $result[gravatar]; ?>" /></td>
</tr>
<!--
<tr valign="top">
<th scope="row">Time</th>
<td><input type="text" name="time" value="<?php echo $result[time]; ?>" /></td>
</tr>
-->

</table>

<input type="hidden" name="action_rss" value="update" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>
<?php

	if (isset($_GET['edit']) && !empty($_GET['edit'])) {

?>
<script type="text/javascript">
	// Move options between Select Menus with Javascript
	// June 6, 2008 7:58 PM | Adrian J. Moreno
	// http://www.iknowkungfoo.com/blog/index.cfm/2008/6/6/Move-options-between-Select-Menus
function moveOption( fromID, toID, idx )
{   
   if (isNaN(parseInt(idx)))
   {
      var i = document.getElementById( fromID ).selectedIndex;
   }
   else
   {
      var i = idx;
   }

   var o = document.getElementById( fromID ).options[ i ];
   var theOpt = new Option( o.text, o.value, false, false );
   document.getElementById( toID ).options[document.getElementById( toID ).options.length] = theOpt;
   document.getElementById( fromID ).options[ i ] = null;
}
function moveOptions( fromID, toID )
{
   for (var x = document.getElementById( fromID ).options.length - 1; x >= 0 ; x--)
   {
      if (document.getElementById( fromID ).options[x].selected == true)
      {
         moveOption( fromID, toID, x );
      }
   }
}
function doSubmit( leftID, rightID )
{
   for (var x = document.getElementById( leftID ).options.length - 1; x >= 0 ; x--)
   {
   	document.getElementById( leftID ).options[x].selected = true;
   }
   for (var x = document.getElementById( rightID ).options.length - 1; x >= 0 ; x--)
   {
   	document.getElementById( rightID ).options[x].selected = true;
   }
}
</script>
<br /><br />
<h2>RSS Reader - <?php _e('Category items', 'sil_rss'); ?></h2>
<form method="post" action="" onSubmit="doSubmit('left_cat','right_cat');">
<table class="form-table">
   <tr>
      <td width="40%">
         <select id="left_cat" name="left_cat[]" multiple="multiple" size="6" style="height: auto;">
<?php
$sql = "select r.* from " . SIL_RSS_TABLE_CATEGORIES . " r left join " . SIL_RSS_TABLE_SITES_BY_CATEGORY . " c on r.id = c.category_id and c.rss_id = '" . (int)$_GET['edit'] . "' where c.category_id is null;";
$results = $wpdb->get_results($sql);
foreach($results as $result)
{
?>
            <option value="<?php echo $result->id; ?>"><?php echo $result->category; ?></option>
<?php
}
?>
         </select>
      </td>
      <td valign="middle" width="20%">
         <p><input type="button" id="moveRight2" value="&gt;" onclick="moveOptions('left_cat','right_cat')"></p>
         <p><input type="button" id="moveLeft2" value="&lt;" onclick="moveOptions('right_cat','left_cat')"></p>
      </td>
      <td width="40%">
         <select id="right_cat" name="right_cat[]" multiple="multiple" size="6" style="height: auto;">
<?php
$sql = "select r.* from " . SIL_RSS_TABLE_CATEGORIES . " r, " . SIL_RSS_TABLE_SITES_BY_CATEGORY . " c where r.id = c.category_id and c.rss_id = '" . (int)$_GET['edit'] . "';";
$results = $wpdb->get_results($sql);
foreach($results as $result)
{
?>
            <option value="<?php echo $result->id; ?>"><?php echo $result->category; ?></option>
<?php
}
?>
         </select>
      </td>
   </tr>
</table>

<input type="hidden" name="action_cat_val" value="update" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>
<?php
	}
}

function sil_rss_manage_page() {
	global $wpdb;
	$sil_rss_table_name = $wpdb->prefix . "sil_rss";

	/*
	if (isset($_GET['edit_rss']) && !empty($_GET['edit_rss'])) {
		sil_rss_edit_page();
		return;
	}
	*/
?>
<div class="wrap">
<h2>RSS Reader - <?php _e('Manage Feeds', 'sil_rss'); ?></h2>
			<br class="clear" />
		<table class="widefat">
  			<thead>
  				<tr>
					<th scope="col" class="check-column">&nbsp;</th>
					<th scope="col">&nbsp;</th>
					<th scope="col">&nbsp;</th>
					<th scope="col" width="30%"><?php _e('Name', 'sil_rss'); ?></th>
					<th scope="col"><?php _e('Site Url', 'sil_rss'); ?></th>
					<th scope="col"><?php _e('Feed Url', 'sil_rss'); ?></th>
					<!-- <th scope="col" width="30%">Date</th> -->
					<th scope="col">&nbsp;</th>
					<th scope="col">&nbsp;</th>
				</tr>
  			</thead>
  			<tbody>

<?php
$sql = "SELECT * FROM ".$sil_rss_table_name;

$results = $wpdb->get_results($sql);
foreach($results as $result)
{
?>
				  <tr id='rss-<?php echo $result->id; ?>' class='alternate'>
						<th scope="row" class="check-column"><input type="checkbox" name="eventcheck[]" value="<?php echo $event->id; ?>" /></th>
						<td><?php echo $result->id; ?></td>
						<td><?php if (strlen($result->image)) { ?><img src="<?php echo SIL_RSS_URL.'images/'.$result->image; ?>" width="30" height="40" /><?php } 
						elseif (strlen($result->gravatar)) { ?><?php echo get_avatar($result->gravatar, '30') ?><?php } ?></td>
						<td><?php echo $result->name; ?></td>
						<td><?php echo $result->link; ?></td>
						<td><?php echo $result->url; ?></td>
						<!-- <td><?php echo date("F d Y H:i", strtotime($result->time)); ?></td> -->
						<td><strong><a class="row-title" href="<?php echo get_option('siteurl').'/wp-admin/admin.php?page=sil_rss_edit_page&amp;edit='.$result->id; ?>" title="Edit"><?php _e("Edit"); ?></a></strong></td>
						<td><strong><a class="row-title" href="<?php echo get_option('siteurl').'/wp-admin/admin.php?page=sil_rss_edit_page&amp;del_rss='.$result->id; ?>" onclick="javascript:return confirm('Are you sure?')" title="Delete"><?php _e("Delete"); ?></a></strong></td>
					</tr>
<?php
}
?>
			</tbody>
		</table>

<strong><a class="row-title" href="<?php echo get_option('siteurl').'/wp-admin/admin.php?page=sil_rss_edit_page'; ?>" title="Add"><?php _e("New"); ?></a></strong>
</div>
<?php
}

/* Delete Category */
if (isset($_GET['del_cat']) && !empty($_GET['del_cat'])) {
	$sql = "delete from ". SIL_RSS_TABLE_SITES_BY_CATEGORY .
	" where category_id = '" . (int)$_GET['del_cat'] . "';";
	$wpdb->query($sql);
	$sql = "DELETE FROM ".SIL_RSS_TABLE_CATEGORIES." where id = '".(int)$_GET['del_cat']."'";
	$wpdb->query($sql);
	if ( !function_exists('wp_redirect') ) {
		include_once("../wp-includes/pluggable.php");
	}
	wp_redirect('admin.php?page=sil_rss_manage_categories_page');
	exit;
}

/* Edit Category */
function sil_rss_edit_category() {
	global $wpdb;

	if (isset($_GET['edit_cat']) && !empty($_GET['edit_cat'])) {
		$sql = "SELECT * FROM ".SIL_RSS_TABLE_CATEGORIES." where id = '".(int)$_GET['edit_cat']."'";
		$results = $wpdb->get_row($sql);
		$result[id] = $results->id;
		$result[category] = $results->category;
	}

?>
<div class="wrap">
<h2>RSS Reader - <?php _e('Category', 'sil_rss'); ?></h2>

<form method="post" action="">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('Title', 'sil_rss'); ?></th>
<td><input type="text" name="category" value="<?php echo $result[category]; ?>" /></td>
</tr>

</table>

<input type="hidden" name="action_cat" value="update" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>
<?php

	if (isset($_GET['edit_cat']) && !empty($_GET['edit_cat'])) {

?>
<script type="text/javascript">
	// Move options between Select Menus with Javascript
	// June 6, 2008 7:58 PM | Adrian J. Moreno
	// http://www.iknowkungfoo.com/blog/index.cfm/2008/6/6/Move-options-between-Select-Menus
function moveOption( fromID, toID, idx )
{   
   if (isNaN(parseInt(idx)))
   {
      var i = document.getElementById( fromID ).selectedIndex;
   }
   else
   {
      var i = idx;
   }

   var o = document.getElementById( fromID ).options[ i ];
   var theOpt = new Option( o.text, o.value, false, false );
   document.getElementById( toID ).options[document.getElementById( toID ).options.length] = theOpt;
   document.getElementById( fromID ).options[ i ] = null;
}
function moveOptions( fromID, toID )
{
   for (var x = document.getElementById( fromID ).options.length - 1; x >= 0 ; x--)
   {
      if (document.getElementById( fromID ).options[x].selected == true)
      {
         moveOption( fromID, toID, x );
      }
   }
}
function doSubmit( leftID, rightID )
{
   for (var x = document.getElementById( leftID ).options.length - 1; x >= 0 ; x--)
   {
   	document.getElementById( leftID ).options[x].selected = true;
   }
   for (var x = document.getElementById( rightID ).options.length - 1; x >= 0 ; x--)
   {
   	document.getElementById( rightID ).options[x].selected = true;
   }
}
</script>
<br /><br />
<h2>RSS Reader - <?php _e('Category items', 'sil_rss'); ?></h2>
<form method="post" action="" onSubmit="doSubmit('left_cat','right_cat');">
<table class="form-table">
   <tr>
      <td width="40%">
         <select id="left_cat" name="left_cat[]" multiple="multiple" size="6" style="height: auto;">
<?php
$sql = "select r.* from " . SIL_RSS_TABLE_SITES . " r left join " . SIL_RSS_TABLE_SITES_BY_CATEGORY . " c on r.id = c.rss_id and c.category_id = '" . (int)$_GET['edit_cat'] . "' where c.rss_id is null;";
$results = $wpdb->get_results($sql);
foreach($results as $result)
{
?>
            <option value="<?php echo $result->id; ?>"><?php echo $result->name; ?></option>
<?php
}
?>
         </select>
      </td>
      <td valign="middle" width="20%">
         <p><input type="button" id="moveRight2" value="&gt;" onclick="moveOptions('left_cat','right_cat')"></p>
         <p><input type="button" id="moveLeft2" value="&lt;" onclick="moveOptions('right_cat','left_cat')"></p>
      </td>
      <td width="40%">
         <select id="right_cat" name="right_cat[]" multiple="multiple" size="6" style="height: auto;">
<?php
$sql = "select r.* from " . SIL_RSS_TABLE_SITES . " r, " . SIL_RSS_TABLE_SITES_BY_CATEGORY . " c where r.id = c.rss_id and c.category_id = '" . (int)$_GET['edit_cat'] . "';";
$results = $wpdb->get_results($sql);
foreach($results as $result)
{
?>
            <option value="<?php echo $result->id; ?>"><?php echo $result->name; ?></option>
<?php
}
?>
         </select>
      </td>
   </tr>
</table>

<input type="hidden" name="action_cat_val" value="update" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>
<?php
	}

}


function sil_rss_manage_categories_page() {
	global $wpdb;

?>
<div class="wrap">
<h2>RSS Reader - <?php _e('Manage Categories', 'sil_rss'); ?></h2>
			<br class="clear" />
		<table class="widefat">
  			<thead>
  				<tr>
					<th scope="col" class="check-column">&nbsp;</th>
					<th scope="col">&nbsp;</th>
					<th scope="col"><?php _e('Name', 'sil_rss'); ?></th>
					<th scope="col">&nbsp;</th>
					<th scope="col">&nbsp;</th>
				</tr>
  			</thead>
  			<tbody>
<?php
$sql = "SELECT * FROM ".SIL_RSS_TABLE_CATEGORIES;

$results = $wpdb->get_results($sql);
foreach($results as $result)
{
?>
				  <tr id='rss-<?php echo $result->id; ?>' class='alternate'>
						<th scope="row" class="check-column"><input type="checkbox" name="eventcheck[]" value="<?php echo $event->id; ?>" /></th>
						<td><?php echo $result->id; ?></td>
						<td><?php echo $result->category; ?></td>
						<td><strong><a class="row-title" href="<?php echo get_option('siteurl').'/wp-admin/admin.php?page=sil_rss_edit_category&amp;edit_cat='.$result->id; ?>" title="Edit"><?php _e("Edit"); ?></a></strong></td>
						<td><strong><a class="row-title" href="<?php echo get_option('siteurl').'/wp-admin/admin.php?page=sil_rss_edit_category&amp;del_cat='.$result->id; ?>" onclick="javascript:return confirm('Are you sure?')" title="Delete"><?php _e("Delete"); ?></a></strong></td>
					</tr>
<?php
}
?>
			</tbody>
		</table>

<strong><a class="row-title" href="<?php echo get_option('siteurl').'/wp-admin/admin.php?page=sil_rss_edit_category'; ?>" title="Add"><?php _e("New"); ?></a></strong>
</div>
<?php
}

function sil_rss_options() {
	if (function_exists('add_options_page'))
	{
		// add_options_page('RSS Feeds', 'RSS Feeds', 8, 'sil_rss_reader', 'sil_rss_options_page');
	}
	if (function_exists('add_management_page'))
	{
		// add_management_page('Feed List', 'Manage Feeds', 8, 'sil_rss_reader', 'sil_rss_manage_page');
	}

	//__FILE__
	add_menu_page('Options', 'Sil RSS Reader', 8, 'sil_rss_manage_page', 'sil_rss_manage_page');
	add_submenu_page('sil_rss_manage_page', __('Options', 'sil_rss'), __('Options', 'sil_rss'), 8, 'sil_rss_options_page', 'sil_rss_options_page');
 	add_submenu_page('sil_rss_manage_page', __('Widget Options', 'sil_rss'), __('Widget Options', 'sil_rss'), 8, 'sil_rss_widget_options_page', 'sil_rss_widget_options_page');
	add_submenu_page('sil_rss_manage_page', __('RSS Options', 'sil_rss'), __('RSS Options', 'sil_rss'), 8, 'sil_rss_external_options_page', 'sil_rss_external_options_page');
	add_submenu_page('sil_rss_manage_page', __('OPML Options', 'sil_rss'), __('OPML Options', 'sil_rss'), 8, 'sil_rss_opml_options_page', 'sil_rss_opml_options_page');
	add_submenu_page('sil_rss_manage_page', __('Categories List', 'sil_rss'), __('Categories List', 'sil_rss'), 8, 'sil_rss_manage_categories_page', 'sil_rss_manage_categories_page');
	if ($_GET["edit_cat"]) $title = __('Edit Category', 'sil_rss'); else $title = __('New Category', 'sil_rss');
	add_submenu_page('sil_rss_manage_page', $title, $title, 8, 'sil_rss_edit_category', 'sil_rss_edit_category');
	add_submenu_page('sil_rss_manage_page', __('Feed List', 'sil_rss'), __('Feed List', 'sil_rss'), 8, 'sil_rss_manage_page', 'sil_rss_manage_page');
	if ($_GET["edit"]) $title = __('Edit Feed', 'sil_rss'); else $title = __('New Feed', 'sil_rss');
	add_submenu_page('sil_rss_manage_page', $title, $title, 8, 'sil_rss_edit_page', 'sil_rss_edit_page');
	add_submenu_page('sil_rss_manage_page', __('Information', 'sil_rss'), __('Information', 'sil_rss'), 8, 'sil_rss_info_page', 'sil_rss_info_page');

}

add_action('admin_menu', 'sil_rss_options');

function sil_rss_info_page() {
?>
<div class="wrap">
<h2>RSS Reader - <?php _e('Information', 'sil_rss'); ?></h2>
			<br class="clear" />
<b>Silencesoft RSS Reader</b><br />
by: Byron Herrera - bh at silencesoft dot net.<br />
http://www.silencesoft.net<br />
<br />
<b>Using Plugin:</b><br />
Add it on a page:<br />
[sil_rss:0:content:0]<br />
* First Param: 0 - The total of items to show.<br />
* Second Param: content - Type to show (content or widget).<br />
* Third Param : 0 - Category to show.<br />
Sample:<br />
[sil_rss:5:widget:1] - Show 5 items of category 1 like widget.<br />
<br />
<b>Call function:</b><br />
To show the list calling a function, use:<br />
< ?php echo sil_rss_show(20, "content", 0); ?><br />
Params are same like before.<br />
<br />
<b>List allblogs:</b><br />
To show the list of all blogs on a page, use:<br />
[sil_rss_list_blogs]<br />
or call it by a function<br />
< ?php echo sil_rss_list_blogs(); ?><br />
<br />
<b>RSS:</b><br />
Your url to subscribe to RSS is:
<a href="<?php echo get_bloginfo('wpurl'); ?>/?feed=external" target="_blank"><?php echo get_bloginfo('wpurl'); ?>/?feed=external</a><br /><br />
<b>Saving OPML:</b><br />
<br />
Your OPML file exported is on:
<a href="<?php echo get_bloginfo('wpurl'); ?>/?sil_opml" target="_blank"><?php echo get_bloginfo('wpurl'); ?>/?sil_opml</a>
</div>
<?php
}

function sil_rss_uninstall() {
	global $wpdb;
	$sil_rss_table_name = $wpdb->prefix . "sil_rss";

	// Drop MySQL Tables
	$SQL = "DROP TABLE `".$sil_rss_table_name;
	mysql_query($SQL) or die("An unexpected error occured.<br />".mysql_error());
	$sil_rss_table_categories = $wpdb->prefix . "sil_rss_categories";
	$SQL = "DROP TABLE `".$sil_rss_table_categories;
	mysql_query($SQL) or die("An unexpected error occured.<br />".mysql_error());

	$sil_rss_table_name_by_category = $wpdb->prefix . "sil_rss_by_category";
	$SQL = "DROP TABLE `".$sil_rss_table_name_by_category;
	mysql_query($SQL) or die("An unexpected error occured.<br />".mysql_error());

	// Delete Option
	delete_option('events_config');

}

function sil_rss_widget_options_page() {
 ?>
<div class="wrap">
<h2>RSS Reader - <?php _e('Widget Options', 'sil_rss'); ?></h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">

<tr valign="top">
	<th scope="row"><label for="sil_rss_widget_title"><?php _e('Widget Title', 'sil_rss'); ?>: </label></th>
	<td><input type="text" name="sil_rss_widget_title" value="<?php echo get_option('sil_rss_widget_title'); ?>" /></td>
</tr>
<tr valign="top">
	<th scope="row"><label for="sil_rss_widget_items"><?php _e('Number of items', 'sil_rss'); ?>: </label></th>
	<td><input type="text" name="sil_rss_widget_items" value="<?php echo get_option('sil_rss_widget_items'); ?>" /></td>
</tr>
<tr valign="top">
	<th scope="row"><label for="sil_rss_widget_pre_list"><?php _e('Widget list start', 'sil_rss'); ?>: </label></th>
	<td><input type="text" name="sil_rss_widget_pre_list" value="<?php echo get_option('sil_rss_widget_pre_list'); ?>" /></td>
</tr>
<tr valign="top">
	<th scope="row"><label for="sil_rss_widget_html_list"><?php _e('Widget list items', 'sil_rss'); ?>: </label></th>
	<td><textarea name="sil_rss_widget_html_list" rows="8" cols="60"><?php echo get_option('sil_rss_widget_html_list'); ?></textarea>
</tr>
<tr valign="top">
	<th scope="row"><label for="sil_rss_widget_post_list"><?php _e('Widget list end', 'sil_rss'); ?>: </label></th>
	<td><input type="text" name="sil_rss_widget_post_list" value="<?php echo get_option('sil_rss_widget_post_list'); ?>" /></td>
</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="sil_rss_widget_title,sil_rss_widget_items,sil_rss_widget_pre_list,sil_rss_widget_html_list,sil_rss_widget_post_list" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>

</div>
<?php

}

function sil_rss_widget($args) {
          extract($args);

    if(!isset($i))
    {
      $funcArgs = func_get_args();
      $i = $funcArgs[1];
    }
  $options = get_option("sil_rss_widget_".$i);
?>
              <?php echo $before_widget; ?>
                  <?php echo $before_title
                      . $options['widget_title']
                      . $after_title; ?>
                  <?php echo sil_rss_show($options['widget_items'], "widget", $options['widget_category'], $options['widget_pre_list'], $options['widget_html_list'], $options['widget_post_list']); ?>
              <?php echo $after_widget; ?>
<?php
}

function sil_rss_control($id)
{
	
	global $wpdb;

  $options = get_option("sil_rss_widget_".$id);
  if (!is_array( $options ))
	{
		$options = array(
      'widget_title' => get_option('sil_rss_widget_title'),
      'widget_items' => get_option('sil_rss_widget_items'),
      'widget_pre_list' => get_option('sil_rss_widget_pre_list'),
      'widget_html_list' => get_option('sil_rss_widget_html_list'),
      'widget_post_list' => get_option('sil_rss_widget_post_list')
      );
  }

	if ( $_POST['sil_rss_widget_save_'.$id] ) {
    $options["widget_title"] = $_POST['sil_rss_widget_title_'.$id];
    $options["widget_items"] = $_POST['sil_rss_widget_items_'.$id];
    $options["widget_category"] = $_POST['sil_rss_widget_category_'.$id];
    $options["widget_pre_list"] = stripslashes($_POST['sil_rss_widget_pre_list_'.$id]);
    $options["widget_html_list"] = stripslashes($_POST['sil_rss_widget_html_list_'.$id]);
    $options["widget_post_list"] = stripslashes($_POST['sil_rss_widget_post_list_'.$id]);
		update_option('sil_rss_widget_'.$id, $options);
  }
	$sql = "SELECT * FROM ".SIL_RSS_TABLE_CATEGORIES;
	$catHtml .= '<select name="sil_rss_widget_category_'.$id.'">'."\n";
	$results = $wpdb->get_results($sql);
	$catHtml .= '<option value="0">'.__('All', 'sil_rss').'</option>'."\n";
	foreach($results as $result)
	{
		if ($options['widget_category'] == $result->id) {
			$selected = ' selected="selected"';
			$category_name = $result->category;
		} else {
			$selected = '';
		}
		$catHtml .= '<option value="'.$result->id.'"'.$selected.'>'.$result->category.'</option>'."\n";
	}
	$catHtml .= '</select>'."\n";
?>
  <p>
    <label for="sil_rss_widget_title_<?php echo $id; ?>"><?php _e('Widget Title', 'sil_rss'); ?>: </label>
    <input type="text" name="sil_rss_widget_title_<?php echo $id; ?>" value="<?php echo $options['widget_title']; ?>" />
    <br />
    <label for="sil_rss_widget_items_<?php echo $id; ?>"><?php _e('Number of items', 'sil_rss'); ?>: </label>
    <input type="text" name="sil_rss_widget_items_<?php echo $id; ?>" value="<?php echo $options['widget_items']; ?>" />
    <br />
    <label for="sil_rss_widget_category_<?php echo $id; ?>"><?php _e('Category', 'sil_rss'); ?>: </label>
		<?php echo $catHtml; ?>
    <br />
    <label for="sil_rss_widget_pre_list_<?php echo $id; ?>"><?php _e('Widget list start', 'sil_rss'); ?>: </label>
    <input type="text" name="sil_rss_widget_pre_list_<?php echo $id; ?>" value="<?php echo $options['widget_pre_list']; ?>" />
    <br />
    <label for="sil_rss_widget_html_list_<?php echo $id; ?>"><?php _e('Widget list items', 'sil_rss'); ?>: </label>
    <textarea name="sil_rss_widget_html_list_<?php echo $id; ?>" rows="8" cols="25"><?php echo $options['widget_html_list']; ?></textarea>
    <br />
    <label for="sil_rss_widget_post_list_<?php echo $id; ?>"><?php _e('Widget list end', 'sil_rss'); ?>: </label>
    <input type="text" name="sil_rss_widget_post_list_<?php echo $id; ?>" value="<?php echo $options['widget_post_list']; ?>" />

    <input type="hidden" name="sil_rss_widget_save_<?php echo $id; ?>" value="1" />
  </p>
<?php
}

function sil_rss_external_options_page() {
?>
<div class="wrap">
<h2>RSS Reader - <?php _e('RSS Options', 'sil_rss'); ?></h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('RSS Title', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_external_title" value="<?php echo get_option('sil_rss_external_title'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Link', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_external_link" value="<?php echo get_option('sil_rss_external_link'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Description', 'sil_rss'); ?>:</th>
<td><textarea name="sil_rss_external_description" rows="8" cols="60"><?php echo get_option('sil_rss_external_description'); ?></textarea>
</tr>

<tr valign="top">
<th scope="row"><?php _e('List item', 'sil_rss'); ?>:</th>
<td><textarea name="sil_rss_external_html" rows="8" cols="60"><?php echo get_option('sil_rss_external_html'); ?></textarea>
</tr>

</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="sil_rss_external_title,sil_rss_external_link,sil_rss_external_description,sil_rss_external_html" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>

</div>
<?php
}

function sil_rss_start() {
	
	global $wpdb;
	global $wp_registered_widgets;
	
  /*
  register_sidebar_widget('RSS Reader', 'sil_rss_widget', 0);
  register_widget_control('RSS Reader', 'sil_rss_control', 250, 200, 0);
	$wp_registered_widgets[sanitize_title('RSS Reader')]['description'] = __('Show rss feeds on widget');
	*/

	$sql = "select count(*) as c from " . SIL_RSS_TABLE_CATEGORIES . ";";
	$results = $wpdb->get_row($sql);
	$i = 1;
	while ($i <= $results->c+1)
	{
	    register_sidebar_widget('RSS Reader - ' . $i, 'sil_rss_widget', $i);
  		register_widget_control('RSS Reader - ' . $i, 'sil_rss_control', 250, 200, $i);
			$wp_registered_widgets[sanitize_title('RSS Reader - ' . $i)]['description'] = __('Show rss category feeds on widget');
			$i++;
	}

}

add_action('plugins_loaded', 'sil_rss_start');

function sil_rss_show_feed($comment) {
	global $wpdb;
	$sil_rss_table_name = $wpdb->prefix . "sil_rss";

	$limit = get_option('sil_rss_total');

	// include_once('inc/lastRSS.php');
	if (!class_exists('SimplePie'))
		include_once(SIL_RSS_PLUGINDIR.'/inc/simplepie.inc');
	$rss = new SimplePie();
	$rss->set_cache_duration(3600);
	$rss->set_cache_location(SIL_RSS_PLUGINDIR.'/cache');

	if (get_option('sil_rss_use_cache') == '1')
		$rss->enable_cache(true);
	else
		$rss->enable_cache(false);
	// $rss->strip_htmltags(array_merge($rss->strip_htmltags, array('h1', 'a', 'img', 'div', 'p', 'pre', 'ol', 'ul', 'li')));
	$rss->strip_htmltags(false);

	if (isset($_GET["sil_cat"]) && (int)$_GET["sil_cat"] > 0)
		$category = $_GET["sil_cat"];
	if ($category)
		$sql = "select r.* from " . SIL_RSS_TABLE_SITES . " r, " . SIL_RSS_TABLE_SITES_BY_CATEGORY . " c where r.id = c.rss_id and c.category_id = '" . (int)$category . "';";
	else
		$sql = "SELECT * FROM " . SIL_RSS_TABLE_SITES;

	$results = $wpdb->get_results($sql);
	$tot = 0;
	foreach($results as $result)
	{
		$url_rss = $result->url;
		$rss->set_feed_url($url_rss);
		$rss->init();
		if ($rss->data)
		{
			if (get_option('sil_rss_multiple') == '1')
				$show = $limit;
			else
				$show = 1;
			$i = 0;
			$items = $rss->get_items(0, $show);
			foreach($items as $item)
			{
					if ($item->get_title() != "") {
						$title[$tot] = $item->get_title();
						$date[$tot] = $item->get_date('Y-m-d H:m:s');
						$link[$tot] = $item->get_permalink();
						$content[$tot] = $item->get_content();
						// $content[$tot] = strip_tags($content[$tot]);
						// $content[$tot] = substr($content[$tot], 0, 250);
						$blog[$tot] = $result->name;
						$author[$tot] = $result->author;
						$description[$tot] = $result->description;
						$image[$tot] = $result->image;
						$gravatar[$tot] = $result->gravatar;
						$url[$tot] = $result->link; // link to site
						$tot++;
						/*
						$i++;
						if ($i >= $show) break;
						*/
					}
			}
		}
	}

	if (is_array($date))
		array_multisort($date, SORT_DESC, $blog, $title, $link, $content, $author, $image, $gravatar, $description, $url);

header('Content-Type: text/xml; charset='.get_option('blog_charset'), true);
?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom">
<channel>
	<atom:link href="<?php echo get_bloginfo('wpurl'); ?>/?feed=external" rel="self" type="application/rss+xml" />
	<title><?php echo get_option('sil_rss_external_title'); ?></title>
	<link><?php echo get_option('sil_rss_external_link'); ?></link>
	<description><?php echo get_option('sil_rss_external_description'); ?></description>
<?php
/*
// <language>en-us</language>
// <lastBuildDate><?php echo strftime( "%a, %d %b %Y %T %Z" , date()); ?></lastBuildDate>
*/

$tmpHtml = get_option('sil_rss_external_html');

$tmpHtml = <<<EOT
<item>
<title>[title]</title>
<link>[link]</link>
<guid>[link]</guid>
<pubDate>[date]</pubDate>
<description>
<![CDATA[
{$tmpHtml}
]]>
</description>
</item>
EOT;

	for($i=0;$i<$limit;$i++)
	{
		if ($title[$i] != "") {
			if (strlen($image[$i]))
				$sImage = '<a href="'.$url[$i].'"><img src="'.SIL_RSS_URL.'images/'.$image[$i].'" width="'.(int)get_option('sil_rss_image_size_w').'" height="'.(int)get_option('sil_rss_image_size_h').'" /></a>';
				// $sImage = '<a href="'.$url[$i].'"><img src="'.SIL_RSS_URL.'images/'.$image[$i].'" style="float:left; margin-right:5px" width="'.(int)get_option('sil_rss_image_size_w').'" height="'.(int)get_option('sil_rss_image_size_h').'" /></a>';
			elseif (strlen($gravatar[$i])) 
				$sImage = get_avatar($gravatar[$i], (int)get_option('sil_rss_image_size_w'));
			else
				$sImage = '';
			$sDate = strftime( "%a, %d %b %Y %T %Z" , strtotime($date[$i])); 
			// $output .= '<li class="sil_rss_info">'.$sImage.'<strong>'.$sDate.'</strong> &middot; '.$blog[$i].' <br /><a href="'.$link[$i].'">'.$title[$i].'</a><br />'.$content[$i].' (...)<br /><a href="'.$url[$i].'">'.$author[$i].'</a></li>'."\n";
			$tmpOutput = str_replace('[image]', $sImage, $tmpHtml);
			$tmpOutput = str_replace('[link]', $link[$i], $tmpOutput);
			$tmpOutput = str_replace('[url]', $url[$i], $tmpOutput);
			$tmpOutput = str_replace('[description]', $description[$i], $tmpOutput);
			$tmpOutput = str_replace('[author]', $author[$i], $tmpOutput);
			$tmpOutput = str_replace('[title]', $title[$i], $tmpOutput);
			$tmpOutput = str_replace('[date]', $sDate, $tmpOutput);
			$tmpOutput = str_replace('[blog]', $blog[$i], $tmpOutput);
			$tmpOutput = str_replace('[content]', $content[$i], $tmpOutput);
			$output = $tmpOutput."\n";
			// $output .= '<li class="sil_rss_info">'.$sImage.'<strong>'.$sDate.'</strong> &middot; '.$blog[$i].' <br /><a href="'.$link[$i].'">'.$title[$i].'</a><br />'.$content[$i].' (...)<br /><a href="'.$url[$i].'">'.$author[$i].'</a></li>'."\n";
			// echo '<strong>'.date("F d", strtotime($date[$i])).'</strong> &middot; '.$blog[$i].' - <a href="'.$link[$i].'">'.$title[$i].'</a><br />';
			echo $output;

		}
		if ($i >= sizeof($title)-1) $i = $limit+1;
	}
?>
</channel>
</rss>
<?php
}

function sil_rss_opml_options_page() {

if ($_GET["ok"] == 1)
	echo '<div id="message" class="updated fade"><p><strong>' . __('Settings saved.') . '</strong></p></div>';

?>
<div class="wrap">
<h2>RSS Reader - <?php _e('OPML Options', 'sil_rss'); ?></h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>

<table class="form-table">

<tr valign="top">
<th scope="row"><?php _e('OPML Title', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_opml_title" value="<?php echo get_option('sil_rss_opml_title'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Owner Name', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_opml_owner" value="<?php echo get_option('sil_rss_opml_owner'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Owner Email', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_opml_email" value="<?php echo get_option('sil_rss_opml_email'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Date Created', 'sil_rss'); ?>:</th>
<td><input type="text" name="sil_rss_opml_date" value="<?php echo get_option('sil_rss_opml_date'); ?>" /></td>
</tr>
<tr valign="top">
<th scope="row"><?php _e('Public OPML', 'sil_rss'); ?>:</th>
<td><select name="sil_rss_public_opml">
<option value="1"<?php if (get_option('sil_rss_public_opml') == '1') echo ' selected="selected"'; ?>>Yes</option>
<option value="0"<?php if (get_option('sil_rss_public_opml') == '0') echo ' selected="selected"'; ?>>No</option>
</select></td>
</tr>
</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="sil_rss_opml_title,sil_rss_opml_owner,sil_rss_opml_email,sil_rss_opml_date" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>

</form>

<br /><br />
<h2>RSS Reader - <?php _e('Import OPML', 'sil_rss'); ?></h2>

<form method="post" enctype="multipart/form-data" action="">

<table class="form-table">
	<tr valign="top">
		<th scope="row"><?php _e('OPML Url', 'sil_rss'); ?>:</th>
		<td><input type="text" name="sil_rss_opml_url" value="http://" /></td>
	</tr>
	<tr valign="top">
		<th scope="row"><?php _e('OPML File', 'sil_rss'); ?>:</th>
		<td><input type="file" name="sil_rss_opml_file" /></td>
	</tr>
</table>

<input type="hidden" name="action_opml" value="update" />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Save Changes'); ?>" />
</p>
</form>

</div>

<?php
}

if (isset($_POST['action_opml']) && !empty($_POST['action_opml']))
{
	sil_rss_import_opml();
}

function sil_rss_import_opml() {
	global $wpdb;
	global $sql_insert;
	$sil_rss_table_name = $wpdb->prefix . "sil_rss";

	// $_FILES['image']['name']
	if ( isset($_POST["sil_rss_opml_url"]) && $_POST["sil_rss_opml_url"] != '' && $_POST["sil_rss_opml_url"] != 'http://' )
	$data = wp_remote_fopen((string)$_POST["sil_rss_opml_url"]);
	elseif (is_uploaded_file($_FILES['sil_rss_opml_file']['tmp_name']))
	$data = file_get_contents($_FILES['sil_rss_opml_file']['tmp_name']);
	else
	$data = "";

	if (!($xmlparser = xml_parser_create()) )
	{
		die ("Cannot create parser");
	}

	xml_set_element_handler($xmlparser, "sil_rss_start_tag", "sil_rss_end_tag");
	xml_set_character_data_handler($xmlparser, "sil_rss_tag_contents");

	if (!xml_parse($xmlparser, $data, true)) {
		$reason = xml_error_string(xml_get_error_code($xmlparser));
		$reason .= xml_get_current_line_number($xmlparser);
		// die($reason);
		echo $reason;
	}

	xml_parser_free($xmlparser);

	$sql = "INSERT INTO " . SIL_RSS_TABLE_SITES .
	" (time, name, author, image, link, description, url) VALUES " .
	$sql_insert;
	$sql = substr($sql, 0, -3);
	// echo $sql;

	$wpdb->query($sql);
	if ( !function_exists('wp_redirect') ) {
		include_once("../wp-includes/pluggable.php");
	}
	wp_redirect('admin.php?page=sil_rss_opml_options_page&ok=1');
	exit();
}

if (isset($_GET['sil_opml']))
{
	sil_rss_show_opml($_GET['sil_opml']);
}


function sil_rss_show_opml($category) {
	global $wpdb;
	$sil_rss_table_name = $wpdb->prefix . "sil_rss";

	if (get_option('sil_rss_public_opml') == '0') return;
	
	header('Content-Type: text/xml; charset='.get_option('blog_charset'), true);
echo "<?xml version=\"1.0\" encoding=\"".get_option('blog_charset')."\" ?>";
?>

<!--  OPML generated by sil_rss plugin for Wordpress -->
<opml version="1.1">
	<head>
		<title><?php echo get_option('sil_rss_opml_title'); ?></title>
	  <dateCreated><?php echo utf8_encode(strftime( "%a, %d %b %Y %T %Z", strtotime(get_option('sil_rss_opml_date')))); ?></dateCreated> 
	  <dateModified><?php echo utf8_encode(strftime( "%a, %d %b %Y %T %Z", strtotime(date('Y-m-d H:m:s')))); ?></dateModified> 
	  <ownerName><?php echo get_option('sil_rss_opml_owner'); ?></ownerName>
	  <ownerEmail><?php echo get_option('sil_rss_opml_email'); ?></ownerEmail>
	</head>
	<body>
<?php

$tmpHtml = <<<EOT
  <outline text="[title]" type="rss" xmlUrl="[url]" created="[date]" /> 
EOT;

	/*
	$sql = "SELECT * FROM ".$sil_rss_table_name;
	if ((int)$category)
		$sql = "where ...";
		*/

	// none category
?>
  <outline text="none">
<?php
	$sql = "select r.* from " . SIL_RSS_TABLE_SITES . " r left join " . SIL_RSS_TABLE_SITES_BY_CATEGORY . " c on r.id = c.rss_id where c.rss_id is null;";
	$results = $wpdb->get_results($sql);
	$tot = 0;
	foreach($results as $result)
	{
?>
	  <outline title="<?php echo utf8_encode($result->name); ?>" text="<?php echo utf8_encode($result->description); ?>" type="rss" htmlUrl="<?php echo $result->link; ?>" xmlUrl="<?php echo $result->url; ?>" created="<?php echo utf8_encode(strftime("%a, %d %b %Y %T %Z", strtotime($result->time))); ?>" /> 
<?php
	}
?>
	</outline>
<?php

	// categories
	$sql = "select * from " . SIL_RSS_TABLE_CATEGORIES . ";";
	$results = $wpdb->get_results($sql);
	foreach($results as $result)
	{
?>
  <outline text="<?php echo $result->category; ?>">
<?php
	$sql = "select r.* from " . SIL_RSS_TABLE_SITES . " r, " . SIL_RSS_TABLE_SITES_BY_CATEGORY . " c where r.id = c.rss_id and c.category_id = '" . $result->id . "';";
	$result_sites = $wpdb->get_results($sql);
	foreach($result_sites as $result)
	{
?>
	  <outline title="<?php echo $result->name; ?>" text="<?php echo $result->description; ?>" type="rss" htmlUrl="<?php echo $result->link; ?>" xmlUrl="<?php echo $result->url; ?>" created="<?php echo strftime("%a, %d %b %Y %T %Z", strtotime($result->time)); ?>" /> 
<?php
	}
?>
	</outline>
<?php
	}
?>
  </body>
</opml>
<?php
exit();
}

function sil_rss_init() {
	add_feed('external', 'sil_rss_show_feed');
	// add_feed('sil_opml', 'sil_rss_show_opml');
	// load_plugin_textdomain('sil_rss', 'wp-content/plugins/sil_rss_reader/lang');
	load_plugin_textdomain('sil_rss', 'wp-content/plugins/'.basename(dirname(__FILE__)).'/lang');
}

add_action('init','sil_rss_init');

function sil_rss_table_exists($table_name) {
	global $wpdb;
	
	foreach ($wpdb->get_col("SHOW TABLES",0) as $table ) {
		if ($table == $table_name) {
			return true;
		}
	}
	return false;
}

function sil_rss_show($total = 0, $to_show = "content", $category = 0, $html_pre_list = "", $html_list = "", $html_post_list = "") {
	global $wpdb;
	$sil_rss_table_name = $wpdb->prefix . "sil_rss";

	// Options de base
	// $url_flux_rss = 'http://www.pcinpact.com/include/news.xml';
	// $limit       = 10; // nombre d'actus à afficher

	$category = (int)$category;
	if (isset($_GET["sil_cat"]) && (int)$_GET["sil_cat"] > 0 && !$category && $to_show == "content")
		$category = (int)$_GET["sil_cat"];
	if ($total)
	$limit = $total;
	else
	$limit = get_option('sil_rss_total');

	$catHtml = '';
	if ($to_show == "content") {
		if (get_option('sil_rss_show_categories') == "1") {
			$sql = "SELECT * FROM ".SIL_RSS_TABLE_CATEGORIES;
	   	$catHtml = '<div id="sil_navigation">'."\n";
	   	$catHtml .= '<div id="sil_cat_list">'."\n";
			$catHtml .= '<select name="sil_categories" id="sil_categories"
			 onchange="location.href=document.getElementById(\'sil_categories\').options[selectedIndex].value">'."\n";
			$results = $wpdb->get_results($sql);
			if (strpos($_SERVER["REQUEST_URI"], "?")) {
				$site_url = $_SERVER['PHP_SELF']."?";
				foreach ($_GET as $key => $value) {
					if ($key == "sil_cat") {
						// $site_url .= $key."=".$result->id."&";
					} else
						$site_url .= $key."=".$value."&";
				}
			} else
				$site_url = $_SERVER['PHP_SELF'];
			if (!$category)
	   		$catHtml .= '<option value="'.$site_url.'">'.__('Choose a category', 'sil_rss').'</option>'."\n";
	   	else
				$catHtml .= '<option value="'.$site_url.'">'.__('All', 'sil_rss').'</option>'."\n";
		foreach($results as $result)
		{
			if ($category == $result->id) {
				$selected = ' selected="selected"'; 
				$category_name = $result->category;
			} else {
				$selected = '';
			}
			if (strpos($_SERVER["REQUEST_URI"], "?")) {
				$site_url = $_SERVER['PHP_SELF']."?";
				$ok = 0;
				foreach ($_GET as $key => $value) {
					if ($key == "sil_cat") {
						$site_url .= $key."=".$result->id."&";
						$ok = 1;
					} else 
						$site_url .= $key."=".$value."&";
				}
				if (!$ok) $site_url .= "sil_cat=".$result->id;
				// $site_url = $_SERVER["REQUEST_URI"]."&";
			} else
				$site_url = $_SERVER['PHP_SELF']."?sil_cat=".$result->id;
    		$catHtml .= '<option value="'.$site_url.'"'.$selected.'>'.$result->category.'</option>'."\n";
		}
			$catHtml .= '</select>'."\n";
   		$catHtml .= '</div>'."\n";
   	} // end if option

		if (get_option('sil_rss_show_feed_url') == "1") {
		   	$catHtml .= '<div id="sil_cat_rss">'."\n";
	   	if ($category)
		   	$catHtml .= '<a href="'.$_SERVER['PHP_SELF'].'?feed=external&sil_cat='.$category.'"><img src="'.SIL_RSS_URL.'images/rss/feed-icon-12x12.png" alt="RSS '.$category_name.'" /> RSS '.$category_name.'</a> - ';
   		$catHtml .= '<a href="'.$_SERVER['PHP_SELF'].'?feed=external"><img src="'.SIL_RSS_URL.'images/rss/feed-icon-12x12.png" alt="RSS General" /> RSS General</a>';
   		$catHtml .= '</div>'."\n";
   		$catHtml .= '</div>'."\n";
			$catHtml .= '<br style="clear:both;" />'."\n";
		}
	}

	/*
	lastRSS
	// on crée un objet lastRSS
	$rss = new lastRSS;
	// options lastRSS
	// $rss->cache_dir   = SIL_RSS_PLUGINDIR.'/cache'; // dossier pour le cache
	// $rss->cache_time  = 3600;      // fréquence de mise à jour du cache (en secondes)
	$rss->stripHTML = True;
	/*
	$rss->date_format = 'd/m';     // format de la date (voir fonction date() pour syntaxe)
	$rss->date_format = 'Y/m/d H:m:s';     // format de la date (voir fonction date() pour syntaxe)
	$rss->CDATA       = 'content'; // on retire les tags CDATA en conservant leur contenu
	/*
	// lecture du flux
	if ($rs = $rss->get($url_flux_rss))
	{
	for($i=0;$i<$limit;$i++)
	{
	// affichage de chaque actu
	echo '<strong>'.$rs['items'][$i]['pubDate'].'</strong> &middot; <a href="'.$rs['items'][$i]['link'].'">'.$rs['items'][$i]['title'].'</a><br />';
	}
	}
	else
	{
	die ('Flux RSS non trouvé');
	}
	*/
	
	// include_once('inc/lastRSS.php');
	if (!class_exists('SimplePie'))
		include_once(SIL_RSS_PLUGINDIR.'/inc/simplepie.inc');
	$rss = new SimplePie();
	$rss->set_cache_duration(3600);
	$rss->set_cache_location(SIL_RSS_PLUGINDIR.'/cache');
	if (get_option('sil_rss_use_cache') == '1')
		$rss->enable_cache(true);
	else
		$rss->enable_cache(false);
	// $rss->strip_htmltags(array_merge($rss->strip_htmltags, array('h1', 'a', 'img', 'div', 'p', 'pre', 'ol', 'ul', 'li')));
	$rss->strip_htmltags(false);

	if ($category && $to_show != "feed")
		$sql = "select r.* from " . SIL_RSS_TABLE_SITES . " r, " . SIL_RSS_TABLE_SITES_BY_CATEGORY . " c where r.id = c.rss_id and c.category_id = '" . (int)$category . "';";
	else
		if ($to_show == "feed")
			$sql = "SELECT * FROM " . SIL_RSS_TABLE_SITES . " where id = '" . (int)$category . "'";
		else
			$sql = "SELECT * FROM " . SIL_RSS_TABLE_SITES;

	$results = $wpdb->get_results($sql);
	$tot = 0;
	foreach($results as $result)
	{
		/*
		lastRSS
		// echo $result->name;
		$url_flux_rss = $result->url;
		if ($rs = $rss->get($url_flux_rss))
		{
			if (get_option('sil_rss_multiple') == '1')
			$show = $limit;
			else
			$show = 1;
			for($i=0;$i<$show;$i++)
			{
				if ($rs['items'][$i]['title'] != "") {
					$title[$tot] = $rs['items'][$i]['title'];
					$date[$tot] = $rs['items'][$i]['pubDate'];
					// if ($result->id == 3)
					// $date[$tot] = substr($date[$tot],6,4)."-".substr($date[$tot],3,2)."-".substr($date[$tot],0,2)." 00:00:00";
					$date[$tot] = date("Y-m-d H:m:s", strtotime($date[$tot]));
					$link[$tot] = $rs['items'][$i]['link'];
					$content[$tot] = $rs['items'][$i]['description'];
					$content[$tot] = substr($content[$tot], 0, 250);
					$blog[$tot] = $result->name;
					$author[$tot] = $result->author;
					$image[$tot] = $result->image;
					$url[$tot] = $result->link; // link to site
					$tot++;
					// echo '<strong>'.$rs['items'][$i]['pubDate'].'</strong> &middot; <a href="'.$rs['items'][$i]['link'].'">'.$rs['items'][$i]['title'].'</a><br />';
				}
			}
		}
		*/
		$url_rss = $result->url;
		$rss->set_feed_url($url_rss);
		$rss->init();
		if ($rss->data)
		{
			if (get_option('sil_rss_multiple') == '1')
				$show = $limit;
			else
				$show = 1;
			$i = 0;
			$items = $rss->get_items(0, $show);
			foreach($items as $item)
			{
					if ($item->get_title() != "") {
						$title[$tot] = $item->get_title();
						$date[$tot] = $item->get_date('Y-m-d H:m:s');
						$link[$tot] = $item->get_permalink();
						$content[$tot] = $item->get_content();
						$content[$tot] = strip_tags($content[$tot]);
						$content[$tot] = substr($content[$tot], 0, 200);
						$blog[$tot] = $result->name;
						$author[$tot] = $result->author;
						$description[$tot] = $result->description;
						$image[$tot] = $result->image;
						$gravatar[$tot] = $result->gravatar;
						$url[$tot] = $result->link; // link to site
						$tot++;
						/*
						$i++;
						if ($i >= $show) break;
						*/
					}
			}
		}
	}

	if (is_array($date))
		array_multisort($date, SORT_DESC, $blog, $title, $link, $content, $author, $image, $gravatar, $description, $url);

/*
echo "<pre>";
print_r($date);
echo "</pre>";
*/
	// $output = '<ul class="sil_rss_list">'."\n";

	if ($html_pre_list != "")
		$output = $html_pre_list;
	else
		if ($to_show == "widget")
			$output = get_option('sil_rss_widget_pre_list')."\n";
		else
			$output = get_option('sil_rss_pre_list')."\n";

	if ($html_list != "")
		$tmpHtml = $html_list;
	else
		if ($to_show == "widget")
			$tmpHtml = get_option('sil_rss_widget_html_list');
		else
			$tmpHtml = get_option('sil_rss_html_list');
	for($i=0;$i<$limit;$i++)
	{
		if ($title[$i] != "") {
			if (strlen($image[$i]))
				$sImage = '<a href="'.$url[$i].'"><img src="'.SIL_RSS_URL.'images/'.$image[$i].'" width="'.(int)get_option('sil_rss_image_size_w').'" height="'.(int)get_option('sil_rss_image_size_h').'" alt="' . $blog[$i] . '" /></a>';
			elseif (strlen($gravatar[$i])) 
				$sImage = '<a href="'.$url[$i].'">'.get_avatar($gravatar[$i], (int)get_option('sil_rss_image_size_w')).'</a>';
			else
				$sImage = '';
			// $sDate = date("F d", strtotime($date[$i]));
			$sDate = mysql2date(get_option('sil_rss_date_format'), $date[$i]);
			// $output .= '<li class="sil_rss_info">'.$sImage.'<strong>'.$sDate.'</strong> &middot; '.$blog[$i].' <br /><a href="'.$link[$i].'">'.$title[$i].'</a><br />'.$content[$i].' (...)<br /><a href="'.$url[$i].'">'.$author[$i].'</a></li>'."\n";
			$tmpOutput = str_replace('[image]', $sImage, $tmpHtml);
			$tmpOutput = str_replace('[link]', $link[$i], $tmpOutput);
			$tmpOutput = str_replace('[url]', $url[$i], $tmpOutput);
			$tmpOutput = str_replace('[description]', $description[$i], $tmpOutput);
			$tmpOutput = str_replace('[author]', $author[$i], $tmpOutput);
			$tmpOutput = str_replace('[title]', $title[$i], $tmpOutput);
			$tmpOutput = str_replace('[date]', $sDate, $tmpOutput);
			$tmpOutput = str_replace('[blog]', $blog[$i], $tmpOutput);
			$tmpOutput = str_replace('[content]', $content[$i], $tmpOutput);
			$output .= $tmpOutput."\n";
			// $output .= '<li class="sil_rss_info">'.$sImage.'<strong>'.$sDate.'</strong> &middot; '.$blog[$i].' <br /><a href="'.$link[$i].'">'.$title[$i].'</a><br />'.$content[$i].' (...)<br /><a href="'.$url[$i].'">'.$author[$i].'</a></li>'."\n";
			// echo '<strong>'.date("F d", strtotime($date[$i])).'</strong> &middot; '.$blog[$i].' - <a href="'.$link[$i].'">'.$title[$i].'</a><br />';
		}
		if ($i >= sizeof($title)-1) $i = $limit+1;
	}
	// $output .= '</ul>'."\n";

	if ($html_post_list != "")
		$output .= $html_post_list;
	else
		if ($to_show == "widget")
			$output .= get_option('sil_rss_widget_post_list')."\n";
		else
			$output .= get_option('sil_rss_post_list')."\n";
	return $catHtml.$output;
	// return $output;
}

function sil_rss_list_blogs() {
	global $wpdb;

	$sql = "SELECT * FROM " . SIL_RSS_TABLE_SITES ." order by name ASC";

	$results = $wpdb->get_results($sql);
	$tot = 0;
	foreach($results as $result)
	{
		$blog[$tot] = $result->name;
		$author[$tot] = $result->author;
		$description[$tot] = $result->description;
		$image[$tot] = $result->image;
		$gravatar[$tot] = $result->gravatar;
		$url[$tot] = $result->link; // link to site
		$tot++;
	}

	$tmpHtml = <<<EOF
<li class="sil_rss_blog">
<div>[image]
<p class="sil_rss_title"><a href="[url]"><strong>[blog]</strong></a><br />[url]
<br /><strong>[author]</strong></p>
[description]
</div>
</li>
EOF;

	$before_html = "<ul class='sil_rss_list'>\n";
	$after_html = "</ul>\n";

	for($i=0;$i<sizeof($blog);$i++)
	{
			if (strlen($image[$i]))
				$sImage = '<a href="'.$url[$i].'"><img src="'.SIL_RSS_URL.'images/'.$image[$i].'" width="'.(int)get_option('sil_rss_image_size_w').'" height="'.(int)get_option('sil_rss_image_size_h').'" alt="' . $blog[$i] . '" /></a>';
			elseif (strlen($gravatar[$i])) 
				$sImage = '<a href="'.$url[$i].'">'.get_avatar($gravatar[$i], (int)get_option('sil_rss_image_size_w')).'</a>';
			else
				$sImage = '';
			// $sDate = date("F d", strtotime($date[$i]));
			$tmpOutput = str_replace('[image]', $sImage, $tmpHtml);
			$tmpOutput = str_replace('[url]', $url[$i], $tmpOutput);
			$tmpOutput = str_replace('[description]', $description[$i], $tmpOutput);
			$tmpOutput = str_replace('[author]', $author[$i], $tmpOutput);
			$tmpOutput = str_replace('[blog]', $blog[$i], $tmpOutput);
			$output .= $tmpOutput."\n";
	}

	return $before_html.$output.$after_html;

}

function sil_rss_content($text) {
/*
echo "<pre>";
if (preg_match('/\[sil_rss:(.*?)\]/', $text, $match))
print_r($match);
echo "<pre>";
*/
	$str = '[sil_rss]';
	if (strpos($text, $str))
		$text = str_replace($str, sil_rss_show(), $text);
	while (preg_match('/\[sil_rss:(.*?)\]/', $text, $match)) {
		// [sil_rss:0:content:1]
		list($total, $show, $cat) = split(":", $match[1]);
		// $text = preg_replace('/\[sil_rss:(.*?)\]/', sil_rss_show($total, $show, $cat), $text);
		$line = "/\[sil_rss:$total:$show:$cat]/";
		$text = preg_replace($line, sil_rss_show($total, $show, $cat), $text);
	}
	$str = '[sil_rss_list_blogs]';
	if (strpos($text, $str))
		$text = str_replace($str, sil_rss_list_blogs(), $text);
	return $text;
}

add_filter('the_content', 'sil_rss_content');


function sil_rss_resize_image($img, $w, $h, $newfilename) {

	// PHP-GD: Resize Transparent Image PNG & GIF
	// http://www.akemapa.com/2008/07/10/php-gd-resize-transparent-image-png-gif/

	//Check if GD extension is loaded
	if (!extension_loaded('gd') && !extension_loaded('gd2')) {
		trigger_error("GD is not loaded", E_USER_WARNING);
		return false;
	}

	//Get Image size info
	$imgInfo = getimagesize($img);
	switch ($imgInfo[2]) {
		case 1: $im = imagecreatefromgif($img); break;
		case 2: $im = imagecreatefromjpeg($img);  break;
		case 3: $im = imagecreatefrompng($img); break;
		default:  trigger_error('Unsupported filetype!', E_USER_WARNING);  break;
	}
// echo "go...";
	//If image dimension is smaller, do not resize
	// if ($imgInfo[0] <= $w && $imgInfo[1] <= $h) {
	if ($imgInfo[0] < $w && $imgInfo[1] < $h) {
		$nHeight = $imgInfo[1];
		$nWidth = $imgInfo[0];
	} else {
		//yeah, resize it, but keep it proportional
		if ($w/$imgInfo[0] > $h/$imgInfo[1]) {
			$nWidth = $w;
			$nHeight = $imgInfo[1]*($w/$imgInfo[0]);
		} else {
			$nWidth = $imgInfo[0]*($h/$imgInfo[1]);
			$nHeight = $h;
		}
	}
	$nWidth = round($nWidth);
	$nHeight = round($nHeight);
	$nWidth = round(50);
	$nHeight = round(50);

	$newImg = imagecreatetruecolor($nWidth, $nHeight);

	/* Check if this image is PNG or GIF, then set if Transparent*/
	if(($imgInfo[2] == 1) OR ($imgInfo[2]==3)){
		imagealphablending($newImg, false);
		imagesavealpha($newImg,true);
		$transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
		imagefilledrectangle($newImg, 0, 0, $nWidth, $nHeight, $transparent);
	}
	imagecopyresampled($newImg, $im, 0, 0, 0, 0, $nWidth, $nHeight, $imgInfo[0], $imgInfo[1]);

	//Generate the file, and rename it to $newfilename
	switch ($imgInfo[2]) {
		case 1: imagegif($newImg,$newfilename); break;
		case 2: imagejpeg($newImg,$newfilename);  break;
		case 3: imagepng($newImg,$newfilename); break;
		default:  trigger_error('Failed resize image!', E_USER_WARNING);  break;
	}

	return $newfilename;
}


//function resize_then_crop
//Author Alan Reddan Silverarm Solutions
//Date 27/01/2005
function sil_rss_resize_then_crop($filein, $imagethumbsize_w,$imagethumbsize_h,
$red,$green,$blue)
{
	// Get new dimensions
	list($width, $height) = getimagesize($filein);
	$new_width = $width * $percent;
	$new_height = $height * $percent;
	if(preg_match("/.jpg/i", "$filein"))
	{
		$format = 'image/jpeg';
	}
	if (preg_match("/.gif/i", "$filein"))
	{
		$format = 'image/gif';
	}
	if(preg_match("/.png/i", "$filein"))
	{
		$format = 'image/png';
	}

	switch($format)
	{
		case 'image/jpeg':
		$image = imagecreatefromjpeg($filein);
		break;
		case 'image/gif';
		$image = imagecreatefromgif($filein);
		break;
		case 'image/png':
		$image = imagecreatefrompng($filein);
		break;
	}
	$width = $imagethumbsize_w ;
	$height = $imagethumbsize_h ;
	list($width_orig, $height_orig) = getimagesize($filein);

	if ($width_orig < $height_orig) {
		$height = ($imagethumbsize_w / $width_orig) * $height_orig;
	} else {
		$width = ($imagethumbsize_h / $height_orig) * $width_orig;
	}

	if ($width < $imagethumbsize_w)
	//if the width is smaller than supplied thumbnail size
	{
		$width = $imagethumbsize_w;
		$height = ($imagethumbsize_w/ $width_orig) * $height_orig;;
	}

	if ($height < $imagethumbsize_h)
	//if the height is smaller than supplied thumbnail size
	{
		$height = $imagethumbsize_h;
		$width = ($imagethumbsize_h / $height_orig) * $width_orig;
	}

	$temp2 = imagecreatetruecolor($width , $height);
	imagealphablending($temp2, true);
	if ($format == 'image/gif' || $format == 'image/png') {
		imagesavealpha($temp2,true);
		$bgcolor = imagecolorallocatealpha($temp2, 255, 255, 255, 127);
	} else {
		$bgcolor = imagecolorallocate($temp2, $red, $green, $blue);
	}
	ImageFilledRectangle($temp2, 0, 0, $width, $height, $bgcolor);

	imagecopyresampled($temp2, $image, 0, 0, 0, 0,
		$width, $height, $width_orig, $height_orig);

	$temp = imagecreatetruecolor($imagethumbsize_w , $imagethumbsize_h);
	imagealphablending($temp, true);
	// true color for best quality
	if ($format == 'image/gif' || $format == 'image/png') {
		imagesavealpha($temp,true);
		$bgcolor = imagecolorallocatealpha($temp, 255, 255, 255, 127);
	} else {
		$bgcolor = imagecolorallocate($temp, $red, $green, $blue);
	}

	ImageFilledRectangle($temp, 0, 0,
		$imagethumbsize_w , $imagethumbsize_h , $bgcolor);

	$w1 =($width/2) - ($imagethumbsize_w/2);
	$h1 = ($height/2) - ($imagethumbsize_h/2);

	imagecopyresampled($temp, $temp2, 0,0, $w1, $h1,
		$imagethumbsize_w , $imagethumbsize_h ,$imagethumbsize_w, $imagethumbsize_h);

	$image =& $temp;
	unset($temp);
	$temp = '';

	switch($format)
	{
		case 'image/jpeg':
			imagejpeg($newImg,$newfilename);
		break;
		case 'image/gif';
			imagegif($newImg,$newfilename);
		break;
		case 'image/png':
			imagepng($image,$filein);
		break;
	}

	return;

}

function sil_rss_start_tag($parser, $name, $attribs) {
	global $sql_insert;
	global $wpdb;
	$names = Array('title','text','type','htmlUrl');
	if ($name == "OUTLINE") {
		// echo "Current tag : ".$name."<br />";

		if (is_array($attribs)) {
			// echo "Attributes : <br />";
			/*
			while(list($key,$val) = each($attribs)) {
			// echo "Attribute ".$key." has value ".$val."<br />";
			}
			*/
			if ($attribs['TYPE'] == 'rss') {
				// echo $attribs['TITLE']."<br />";
				$sql_insert .= "(NOW(),'" . $wpdb->escape($attribs['TITLE']) . "','" . $wpdb->escape($attribs['TITLE']) . "','','" . $wpdb->escape($attribs['HTMLURL']) . "','" . $wpdb->escape($attribs['TEXT']) . "','" . $wpdb->escape($attribs['XMLURL']) . "'), \n";
			}
		}
	}
}

function sil_rss_end_tag($parser, $name) {
   // echo "Reached ending tag ".$name."<br /><br />";
}

function sil_rss_tag_contents($parser, $data) {
   // echo "Contents : ".$data."<br />";
}

?>

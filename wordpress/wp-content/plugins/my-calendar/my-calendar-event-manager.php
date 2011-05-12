<?php

function jd_option_selected($field,$value,$type='checkbox') {
	switch ($type) {
		case 'radio':		
		case 'checkbox':
		$result = ' checked="checked"';
		break;
		case 'option':
		$result = ' selected="selected"';
		break;
	}	
	if ($field == $value) {
		$output = $result;
	} else {
		$output = '';
	}
	return $output;
}

function edit_my_calendar() {
    global $current_user, $wpdb, $users_entries;

	if ( get_option('ko_calendar_imported') != 'true' ) {  
		if (function_exists('check_calendar')) {
		echo "<div id='message' class='updated'>";
		echo "<p>";
		_e('My Calendar has identified that you have the Calendar plugin by Kieran O\'Shea installed. You can import those events and categories into the My Calendar database. Would you like to import these events?','my-calendar');
		echo "</p>";
		?>
			<form method="post" action="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar-config">
			<div><input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('my-calendar-nonce'); ?>" /></div>
			<div>			
			<input type="hidden" name="import" value="true" />
			<input type="submit" value="<?php _e('Import from Calendar','my-calendar'); ?>" name="import-calendar" class="button-primary" />
			</div>
			</form>
		<?php
		echo "<p>";
		_e('Although it is possible that this import could fail to import your events correctly, it should not have any impact on your existing Calendar database. If you encounter any problems, <a href="http://www.joedolson.com/contact.php">please contact me</a>!','my-calendar');
		echo "</p>";
		echo "</div>";
		}
	}

// First some quick cleaning up 
$edit = $create = $save = $delete = false;

$action = !empty($_POST['event_action']) ? $_POST['event_action'] : '';
$event_id = !empty($_POST['event_id']) ? $_POST['event_id'] : '';

if ($_GET['mode'] == 'edit') {
	$action = "edit";
	$event_id = (int) $_GET['event_id'];
}
if ($_GET['mode'] == 'copy') {
	$action = "copy";
	$event_id = (int) $_GET['event_id'];	
}

// Lets see if this is first run and create us a table if it is!
check_my_calendar();

if ( !empty($_POST['mass_delete']) ) {
	$nonce=$_REQUEST['_wpnonce'];
    if (! wp_verify_nonce($nonce,'my-calendar-nonce') ) die("Security check failed");
	$events = $_POST['mass_delete'];
	$sql = 'DELETE FROM ' . MY_CALENDAR_TABLE . ' WHERE event_id IN (';	
	$i=0;
	foreach ($events as $value) {
		$value = (int) $value;
		$ea = "SELECT event_author FROM " . MY_CALENDAR_TABLE . " WHERE event_id = $value";
		$result = $wpdb->get_results( $ea, ARRAY_A );
		$total = count($events);
		
		if ( mc_can_edit_event( $result[0]['event_author'] ) ) {
			$sql .= mysql_real_escape_string($value).',';
			$i++;
		}
	}
	$sql = substr( $sql, 0, -1 );
	$sql .= ')';
	$result = $wpdb->query($sql);
	if ( $result !== 0 && $result !== false ) {
		$message = "<div class='updated'><p>".sprintf(__('%1$d events deleted successfully out of %2$d selected','my-calendar'), $i, $total )."</p></div>";
	} else {
		$message = "<div class='error'><p><strong>".__('Error','my-calendar').":</strong>".__('Your events have not been deleted. Please investigate.','my-calendar')."</p></div>";
	}
	echo $message;
}

if ($_GET['mode'] == 'delete') {
	    $sql = "SELECT event_title, event_author FROM " . MY_CALENDAR_TABLE . " WHERE event_id=" . (int) $_GET['event_id'];
	   $result = $wpdb->get_results( $sql, ARRAY_A );
	if ( mc_can_edit_event( $result[0]['event_author'] ) ) {
	?>
		<div class="error">
		<p><strong><?php _e('Delete Event','my-calendar'); ?>:</strong> <?php _e('Are you sure you want to delete this event?','my-calendar'); ?></p>
		<form action="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar" method="post">
		<div>
		<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('my-calendar-nonce'); ?>" />		
		<input type="hidden" value="delete" name="event_action" />
		<input type="hidden" value="<?php echo (int) $_GET['event_id']; ?>" name="event_id" />
		<input type="submit" name="submit" class="button-primary" value="<?php _e('Delete','my-calendar'); echo " &quot;".$result[0]['event_title']."&quot;"; ?>" />
		</div>
		</form>
		</div>
	<?php
	} else {
	?>
		<div class="error">
		<p><strong><?php _e('You do not have permission to delete that event.','my-calendar'); ?></strong></p>
		</div>
	<?php
	}
}


// Approve and show an Event ...by Roland
if ( $_GET['mode'] == 'approve' ) {
	if ( current_user_can( get_option( 'mc_event_approve_perms' ) ) ) {
	    $sql = "UPDATE " . MY_CALENDAR_TABLE . " SET event_approved = 1 WHERE event_id=" . (int) $_GET['event_id'];
		$result = $wpdb->get_results( $sql, ARRAY_A );
	} else {
	?>
		<div class="error">
		<p><strong><?php _e('You do not have permission to approve that event.','my-calendar'); ?></strong></p>
		</div>
	<?php
	}
}

// Reject and hide an Event ...by Roland
if ($_GET['mode'] == 'reject') {
	if ( current_user_can( get_option( 'mc_event_approve_perms' ) ) ) {
	    $sql = "UPDATE " . MY_CALENDAR_TABLE . " SET event_approved = 2 WHERE event_id=" . (int) $_GET['event_id'];
		$result = $wpdb->get_results( $sql, ARRAY_A );
	} else {
	?>
		<div class="error">
		<p><strong><?php _e('You do not have permission to reject that event.','my-calendar'); ?></strong></p>
		</div>
	<?php
	}
}

if ( isset( $_POST['event_action'] ) ) {
	$nonce=$_REQUEST['_wpnonce'];
    if (! wp_verify_nonce($nonce,'my-calendar-nonce') ) die("Security check failed");
	$proceed = false;
	$output = mc_check_data($action,$_POST);
	if ($action == 'add' || $action == 'copy' ) {
		$response = my_calendar_save($action,$output);
	} else {
		$response = my_calendar_save($action,$output,$event_id);	
	}
	echo $response;
}

?>

<div class="wrap">
<?php 
my_calendar_check_db();
?>
	<?php
	if ( $action == 'edit' || ($action == 'edit' && $error_with_saving == 1) ) {
		?>
		<h2><?php _e('Edit Event','my-calendar'); ?></h2>
		<?php jd_show_support_box(); ?>
		<?php
		if ( empty($event_id) ) {
			echo "<div class=\"error\"><p>".__("You must provide an event id in order to edit it",'my-calendar')."</p></div>";
		} else {
			jd_events_edit_form('edit', $event_id);
		}	
	} else if ( $action == 'copy' || ($action == 'copy' && $error_with_saving == 1)) { ?>
		<h2><?php _e('Copy Event','my-calendar'); ?></h2>
		<?php jd_show_support_box(); ?>
		<?php
		if ( empty($event_id) ) {
			echo "<div class=\"error\"><p>".__("You must provide an event id in order to edit it",'my-calendar')."</p></div>";
		} else {
			jd_events_edit_form('copy', $event_id);
		}		
	} else {
	?>	
		<h2><?php _e('Add Event','my-calendar'); ?></h2>
		<?php jd_show_support_box(); ?>
		<?php jd_events_edit_form(); ?>

		<h2><?php _e('Manage Events','my-calendar'); ?></h2>
		<?php if ( get_option('mc_event_approve') == 'true' ) { ?>
		<ul class="links">
		<li><a <?php echo ($_GET['limit']=='published')?' class="active-link"':''; ?> href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;limit=published">Published</a></li>
		<li><a <?php echo ($_GET['limit']=='reserved')?' class="active-link"':''; ?>  href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;limit=reserved">Reserved</a></li> 
		<li><a <?php echo ($_GET['limit']=='all' || !isset($_GET['limit']))?' class="active-link"':''; ?>  href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;limit=all">All</a></li>
		</ul>
		<?php } ?>
		<?php 
		
		$sortby = ( isset( $_GET['sort'] ) )?(int) $_GET['sort']:$sortby = 'default';

		if ( isset( $_GET['order'] ) ) {
			$sortdir = ( $_GET['order'] == 'ASC' )?'ASC':'default';
		} else {
			$sortdir = 'default';
		}
		if ( isset( $_GET['limit'] ) ) {
			switch ($_GET['limit']) {
				case 'reserved':$limit = 'reserved';
				break;
				case 'published':$limit ='published';
				break;
			}
		} else {
			$limit = 'all';
		}
		jd_events_display_list($sortby,$sortdir,$limit);
	}
	?>
</div>
<?php
} 


function my_calendar_save( $action,$output,$event_id=false ) {
global $wpdb,$event_author;
	$proceed = $output[0];

	if ( ( $action == 'add' || $action == 'copy' ) && $proceed == true ) {
		$add = $output[2];
		
		$event_list = $wpdb->get_results("
		select * from wp_my_calendar order by event_id desc
		");
		$event_id = $event_list[0]->event_id + 1;
		
		// Updating our category relationships
		foreach ($add['event_category'] as $cat) {
				$cat_entry = array('event_id' => $event_id, 'category_id' => $cat);
				$formats = array('%d', '%d');
				$insert_cats = $wpdb->insert( 
						'wp_my_calendar_events_categories', 
						$cat_entry, 
						$formats
						);
			}
		if (!$insert_cats) {
			$message = "<div class='error'><p><strong>". __('Error','my-calendar') .":</strong>". _e('Your categories are screwed up mate.','my-calendar').
			"</p></div>";
		} else {
			unset($add['event_category']);
		}

		// Adding the rest of this rubbish
		
		$formats = array('%s','%s','%s','%s','%s','%s','%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%f','%f','%s','%s','%d','%d','%d','%s', '%d');		
		$result = $wpdb->insert( 
				MY_CALENDAR_TABLE, 
				$add, 
				$formats 
				);

		if ( !$result ) {
			$message = "<div class='error'><p><strong>". __('Error','my-calendar') .":</strong>". _e('I\'m sorry! I couldn\'t add that event to the database.','my-calendar') . "</p></div>";	      
		} else {
	    // Call mail function
			$sql = "SELECT * FROM ". MY_CALENDAR_TABLE." WHERE event_id = ".$wpdb->insert_id;
			$event = $wpdb->get_results($sql);
			my_calendar_send_email( $event[0] );
			$message = "<div class='updated'><p>". __('Event added. It will now show in your calendar.','my-calendar') . "</p></div>";
		}
	}
	if ( $action == 'edit' && $proceed == true ) {
		$event_author = (int) ($_POST['event_author']);
		if ( mc_can_edit_event( $event_author ) ) {	
			$update = $output[2];
			
			// echo '<pre>';
			// 	print_r($update);
			// 	echo '</pre>'; 
			// Updating our category relationships
			// Incase we're removing a category here, lets remove the existing categories so we can add the new ones :)
			$delete_cats = $wpdb->query("DELETE FROM wp_my_calendar_events_categories WHERE event_id = $event_id");
			if(!$delete_cats) {echo 'Something went wrong removing the old categories...but don\'t panic!';}
			foreach ($update['event_category'] as $cat) {
					unset($cat_entry);
					$cat_entry = array('event_id' => $event_id, 'category_id' => $cat);
					$formats = array('%d', '%d');
					$insert_cats = $wpdb->insert( 
							'wp_my_calendar_events_categories', 
							$cat_entry, 
							$formats
							);
				}
			if (!$insert_cats) {
				$message = "<div class='error'><p><strong>". __('Error','my-calendar') .":</strong>". _e('Your categories are screwed up mate.','my-calendar').
				"</p></div>";
			} else {
				unset($update['event_category']);
			}

			// Adding the rest of this rubbish

			$formats = array('%s','%s','%s','%s','%s','%s','%d','%d','%s','%s','%s','%s','%s','%s','%s','%s','%s','%d','%f','%f','%s','%s','%d','%d','%d','%s', '%d' );
			// $wpdb->show_errors();
			$result = $wpdb->update( 
					MY_CALENDAR_TABLE, 
					$update, 
					array( 'event_id'=>$event_id ),
					$formats, 
					'%d' );
			//$wpdb->print_error();
				if ( $result === false ) {
					$message = "<div class='error'><p><strong>".__('Error','my-calendar').":</strong>".__('Your event was not updated.','my-calendar')."</p></div>";
				} else if ( $result === 0 ) {
					$message = "<div class='updated'><p>".__('Nothing was changed in that update.','my-calendar')."</p></div>";
				} else {
					$message = "<div class='updated'><p>".__('Event updated successfully','my-calendar')."</p></div>";
				}
		} else {
			$message = "<div class='error'><p><strong>".__('You do not have sufficient permissions to edit that event.','my-calendar')."</strong></p></div>";
		}			
	}

	if ( $action == 'delete' ) {
// Deal with deleting an event from the database
		if ( empty($event_id) )	{
			$message = "<div class='error'><p><strong>".__('Error','my-calendar').":</strong>".__("You can't delete an event if you haven't submitted an event id",'my-calendar')."</p></div>";
		} else {
			$sql = "DELETE FROM " . MY_CALENDAR_TABLE . " WHERE event_id='" . mysql_real_escape_string($event_id) . "'";
			$wpdb->query($sql);
			$sql = "SELECT event_id FROM " . MY_CALENDAR_TABLE . " WHERE event_id='" . mysql_real_escape_string($event_id) . "'";
			$result = $wpdb->get_results($sql);
			if ( empty($result) || empty($result[0]->event_id) ) {
				return "<div class='updated'><p>".__('Event deleted successfully','my-calendar')."</p></div>";
			} else {
				$message = "<div class='error'><p><strong>".__('Error','my-calendar').":</strong>".__('Despite issuing a request to delete, the event still remains in the database. Please investigate.','my-calendar')."</p></div>";
			}	
		}
	}
	$message = $message ."\n". $output[3];
	return $message;
}

function jd_acquire_form_data($event_id=false) {
global $wpdb,$users_entries;
	if ( $event_id !== false ) {
		if ( intval($event_id) != $event_id ) {
			return "<div class=\"error\"><p>".__('Sorry! That\'s an invalid event key.','my-calendar')."</p></div>";
		} else {
			$data = $wpdb->get_results("SELECT * FROM " . MY_CALENDAR_TABLE . " WHERE event_id='" . mysql_real_escape_string($event_id) . "' LIMIT 1");
			if ( empty($data) ) {
				return "<div class=\"error\"><p>".__("Sorry! We couldn't find an event with that ID.",'my-calendar')."</p></div>";
			}
			$data = $data[0];
		}
		// Recover users entries if they exist; in other words if editing an event went wrong
		if (!empty($users_entries)) {
		    $data = $users_entries;
		}
	} else {
	  // Deal with possibility that form was submitted but not saved due to error - recover user's entries here
	  $data = $users_entries;
	}
	return $data;

}

// The event edit form for the manage events admin page
function jd_events_edit_form($mode='add', $event_id=false) {
	global $wpdb,$users_entries,$user_ID;
	if ($event_id != false) {
		$data = jd_acquire_form_data($event_id);
	}
?>

	<h2><?php if ($mode == "add") { _e('Add an Event','my-calendar'); } else if ($mode == "copy") { _e('Copy Event','my-calendar'); } else { _e('Edit Event'); } ?></h2>
	<?php 
	if ($data->event_approved != 1 && $mode == 'edit' ) {
	$message = __('This event must be approved in order for it to appear on the calendar.','my-calendar');
	} else {
	$message = "";
	}
	echo ($message != '')?"<div class='error'><p>$message</p></div>":'';
	?>
	<form name="my-calendar" id="my-calendar" method="post" action="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar">
	<?php my_calendar_print_form_fields($data,$mode,$event_id); ?>
			<p>
                <input type="submit" name="save" class="button-primary" value="<?php _e('Save Event','my-calendar'); ?> &raquo;" />
			</p>
	</form>

<?php
}
function my_calendar_print_form_fields( $data,$mode,$event_id,$context='' ) {
	global $user_ID,$wpdb;
	get_currentuserinfo();
	$user = get_userdata($user_ID);		
	$mc_input_administrator = (get_option('mc_input_options_administrators')=='true' && current_user_can('manage_options'))?true:false;
	$mc_input = get_option('mc_input_options');
?>
<div>
<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('my-calendar-nonce'); ?>" />
<input type="hidden" name="event_action" value="<?php echo $mode; ?>" />
<input type="hidden" name="event_id" value="<?php echo $event_id; ?>" />
<input type="hidden" name="event_author" value="<?php echo $user_ID; ?>" />
<input type="hidden" name="event_nonce_name" value="<?php echo wp_create_nonce('event_nonce'); ?>" />
</div>
<div id="poststuff" class="jd-my-calendar">
<div class="postbox">	
	<div class="inside">	
        <fieldset>
		<legend><?php _e('Enter your Event Information','my-calendar'); ?></legend>
		<p>
		<label for="event_title"><?php _e('Event Title','my-calendar'); ?><span><?php _e('(required)','my-calendar'); ?></span></label> <input type="text" id="event_title" name="event_title" class="input" size="60" value="<?php if ( !empty($data) ) echo htmlspecialchars(stripslashes($data->event_title)); ?>" />
<?php if ( $mode == 'edit' ) { ?>
	<?php if ( get_option( 'mc_event_approve' ) == 'true' ) { ?>
		<?php if ( current_user_can( get_option('mc_event_approve_perms') ) ) { // (Added by Roland P. ?>
				<input type="checkbox" value="1" id="event_approved" name="event_approved"<?php if ( !empty($data) && $data->event_approved == '1' ) { echo " checked=\"checked\""; } else if ( !empty($data) && $data->event_approved == '0' ) { echo ""; } else if ( get_option( 'mc_event_approve' ) == 'true' ) { echo "checked=\"checked\""; } ?> /> <label for="event_approved"><?php _e('Publish','my-calendar'); ?><?php if ($event->event_approved != 1) { ?> <small>[<?php _e('You must approve this event to promote it to the calendar.','my-calendar'); ?>]</small> <?php } ?></label>
		<?php } else { // case: editing, approval enabled, user cannot approve ?>
				<input type="hidden" value="0" name="event_approved" /><?php _e('An administrator must approve your new event.','my-calendar'); ?>
		<?php } ?> 
	<?php } else { // Case: editing, approval system is disabled - auto approve ?>	
				<input type="hidden" value="1" name="event_approved" />
	<?php } ?>
<?php } else { // case: adding new event (if use can, then 1, else 0) ?>
<?php if ( current_user_can( get_option('mc_event_approve_perms') ) ) { $dvalue = 1; } else { $dvalue = 0; } ?>
			<input type="hidden" value="<?php echo $dvalue; ?>" name="event_approved" />
<?php } ?>
		</p>
		<?php if ($mc_input['event_desc'] == 'on' || $mc_input_administrator ) { ?>
		<?php if ($context != 'post') { ?>
		<p>
		<label for="event_desc"><?php _e('Event Description (<abbr title="hypertext markup language">HTML</abbr> allowed)','my-calendar'); ?></label><br /><textarea id="event_desc" name="event_desc" class="input" rows="5" cols="80"><?php if ( !empty($data) ) echo htmlspecialchars(stripslashes($data->event_desc)); ?></textarea>
		</p>
		<?php } ?>
		<?php } ?>
		<?php if ($mc_input['event_short'] == 'on') { ?>
		<p>
		<label for="event_short"><?php _e('Event Short Description (<abbr title="hypertext markup language">HTML</abbr> allowed)','my-calendar'); ?></label><br /><textarea id="event_short" name="event_short" class="input" rows="2" cols="80"><?php if ( !empty($data) ) echo htmlspecialchars(stripslashes($data->event_short)); ?></textarea>
		</p>
		<?php } ?>
		<p>
		<label for="event_image"><?php _e('Image URL','my-calendar'); ?></label><br />
		<input type="text" name="event_image" size="50" value="<?php if(!empty($data)) {echo $data->event_image;} ?>"/>
		</p>
		<p>
		<label for="event_image"><?php _e('Featured event?','my-calendar'); ?></label><br />
		<input type="checkbox" name="event_featured" <?php if(!empty($data)) {if($data->event_featured == 1) {echo 'checked="checked"';}} ?>/>
		</p>
		<?php //Event host field added by Jeff Allen - http://jdadesign.net
		 function my_calendar_getUsers() {
			global $wpdb;
			$authors = $wpdb->get_results( "SELECT ID, user_nicename, display_name from $wpdb->users ORDER BY display_name" );
			return $authors;
		 }
		 ?>
	<p>
	<label for="event_host"><?php _e('Event Host','my-calendar'); ?></label>
	<select id="event_host" name="event_host">
		<?php 
			 // Grab all the users and list them
			$userList = my_calendar_getUsers();				 
			foreach($userList as $u) {
			 echo '<option value="'.$u->ID.'"';
					if ( $data->event_host == $u->ID ) {
					 echo ' selected="selected"';
					} else if( $u->ID == $user->ID && empty($data->event_host) ) {
				    echo ' selected="selected"';
					}
				echo '>'.$u->display_name."</option>\n";
			}
		?>
	</select>
	</p>			
		<?php if ($mc_input['event_category'] == 'on') { ?>
        <p>
		<label for="event_category"><?php _e('Event Category','my-calendar'); ?></label>
		<div id="event_category">
			<?php
			// Grab all the categories and list them
			
			// $sql = "select wp_my_calendar.event_id, wp_my_calendar_categories.category_id from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id"; 
			
			$sql = "SELECT * FROM " . MY_CALENDAR_CATEGORIES_TABLE;
				$cats = $wpdb->get_results($sql);
			
			// Get our event's categories
			$sql = "select wp_my_calendar.event_id, wp_my_calendar_categories.category_id from wp_my_calendar join wp_my_calendar_events_categories on wp_my_calendar.event_id = wp_my_calendar_events_categories.event_id join wp_my_calendar_categories on wp_my_calendar_events_categories.category_id = wp_my_calendar_categories.category_id WHERE wp_my_calendar.event_id = $event_id"; 
			
				$event_cats = $wpdb->get_results($sql);
				// print_r($event_cats);
				foreach($cats as $cat) {
					echo '<input type="checkbox" name="category_id[]" value="'.$cat->category_id.'"';
					if (!empty($data)) {
						foreach ($event_cats as $category) {
							if ($category->category_id == $cat->category_id){
							 echo 'checked="checked"';
							}
						}
					}
					echo ' />'.$cat->category_name;
				}
			?>
			</div>
            </p>
			<?php } else { ?>
			<div>
			<input type="hidden" name="event_category" value="1" />
			</div>
			<?php } ?>
			<?php if ($mc_input['event_link'] == 'on') { ?>
			<p>
			<?php if ($context != 'post') { ?><label for="event_link"><?php _e('Event Link (Optional)','my-calendar'); ?></label> <input type="text" id="event_link" name="event_link" class="input" size="40" value="<?php if ( !empty($data) ) { echo htmlspecialchars($data->event_link); } ?>" /> <?php } ?><input type="checkbox" value="1" id="event_link_expires" name="event_link_expires"<?php if ( !empty($data) && $data->event_link_expires == '1' ) { echo " checked=\"checked\""; } else if ( !empty($data) && $data->event_link_expires == '0' ) { echo ""; } else if ( get_option( 'mc_event_link_expires' ) == 'true' ) { echo " checked=\"checked\""; } ?> /> <label for="event_link_expires"><?php _e('This link will expire when the event passes.','my-calendar'); ?></label>
			</p>
			<?php } ?>
			</fieldset>
</div>
</div>
<div class="postbox">	
<div class="inside">
			<fieldset><legend><?php _e('Event Date and Time','my-calendar'); ?></legend>
			<p>
			<?php _e('Enter the beginning and ending information for the first occurrence of this event.','my-calendar'); ?><br />
			<label for="event_begin"><?php _e('Start Date (YYYY-MM-DD)','my-calendar'); ?> <span><?php _e('(required)','my-calendar'); ?></span></label> <input type="text" id="event_begin" name="event_begin" class="calendar_input" size="12" value="<?php if ( !empty($data) ) { esc_attr_e($data->event_begin);} else { echo date_i18n("Y-m-d");} ?>" /> <label for="event_time"><?php _e('Time (hh:mm)','my-calendar'); ?></label> <input type="text" id="event_time" name="event_time" class="input" size="12"	value="<?php 
					$offset = (60*60*get_option('gmt_offset'));
					if ( !empty($data) ) {
						echo ($data->event_time == "00:00:00")?'':date("H:i",strtotime($data->event_time));
					} else {
						echo date_i18n("H:i",time()+$offset);
					}?>" /> 
			</p>				
			<p>
			<label for="event_end"><?php _e('End Date (YYYY-MM-DD)','my-calendar'); ?></label> <input type="text" name="event_end" id="event_end" class="calendar_input" size="12" value="<?php if ( !empty($data) ) {esc_attr_e($data->event_end);} ?>" /> <label for="event_endtime"><?php _e('End Time (hh:mm)','my-calendar'); ?></label> <input type="text" id="event_endtime" name="event_endtime" class="input" size="12" value="<?php
					if ( !empty($data) ) {
						echo ($data->event_endtime == "00:00:00")?'':date("H:i",strtotime($data->event_endtime));
					} else {
						echo '';
					}?>" /> 
			</p>
			<p>
			<?php _e('Current time difference from GMT is ','my-calendar'); echo get_option('gmt_offset'); _e(' hour(s)', 'my-calendar'); ?>
			</p> 
			</fieldset>
</div>
</div>
			<?php if ($mc_input['event_recurs'] == 'on') { ?>
<div class="postbox">
<div class="inside">
			<fieldset>
			<legend><?php _e('Recurring Events','my-calendar'); ?></legend> 
			<?php if ( $data->event_repeats != NULL ) { $repeats = $data->event_repeats; } else { $repeats = 0; } ?>
			<p>
			<label for="event_repeats"><?php _e('Repeats for','my-calendar'); ?></label> <input type="text" name="event_repeats" id="event_repeats" class="input" size="1" value="<?php echo $repeats; ?>" /> 
			<label for="event_recur"><?php _e('Units','my-calendar'); ?></label> <select name="event_recur" class="input" id="event_recur">
				<option class="input" <?php echo jd_option_selected( $data->event_recur,'S','option'); ?> value="S"><?php _e('Does not recur','my-calendar'); ?></option>
				<option class="input" <?php echo jd_option_selected( $data->event_recur,'D','option'); ?> value="D"><?php _e('Daily','my-calendar'); ?></option>						
				<option class="input" <?php echo jd_option_selected( $data->event_recur,'W','option'); ?> value="W"><?php _e('Weekly','my-calendar'); ?></option>
				<option class="input" <?php echo jd_option_selected( $data->event_recur,'B','option'); ?> value="B"><?php _e('Bi-weekly','my-calendar'); ?></option>						
				<option class="input" <?php echo jd_option_selected( $data->event_recur,'M','option'); ?> value="M"><?php _e('Date of Month (e.g., the 24th of each month)','my-calendar'); ?></option>
				<option class="input" <?php echo jd_option_selected( $data->event_recur,'U','option'); ?> value="U"><?php _e('Day of Month (e.g., the 3rd Monday of each month)','my-calendar'); ?></option>
				<option class="input" <?php echo jd_option_selected( $data->event_recur,'Y','option'); ?> value="Y"><?php _e('Annually','my-calendar'); ?></option>
			</select><br />
					<?php _e('Enter "0" if the event should recur indefinitely. Your entry is the number of events after the first occurrence of the event: a recurrence of <em>2</em> means the event will happen three times.','my-calendar'); ?>
			</p>
			</fieldset>	
</div>
</div>				
			<?php } else { ?>
			<div>
			<input type="hidden" name="event_repeats" value="0" />
			<input type="hidden" name="event_recur" value="S" />
			</div>
		
			<?php } ?>

			<?php if ($mc_input['event_open'] == 'on') { ?>			
<div class="postbox">
<div class="inside">
			<fieldset>
			<legend><?php _e('Event Registration Status','my-calendar'); ?></legend>
			<p><em><?php _e('My Calendar does not manage event registrations. Use this for information only.','my-calendar'); ?></em></p>
			<p>
			<input type="radio" id="event_open" name="event_open" value="1" <?php if (!empty($data)) { echo jd_option_selected( $data->event_open,'1'); } else { echo " checked='checked'"; } ?> /> <label for="event_open"><?php _e('Open','my-calendar'); ?></label> 
			<input type="radio" id="event_closed" name="event_open" value="0" <?php if (!empty($data)) {  echo jd_option_selected( $data->event_open,'0'); } ?> /> <label for="event_closed"><?php _e('Closed','my-calendar'); ?></label>
			<input type="radio" id="event_none" name="event_open" value="2" <?php if (!empty($data)) { echo jd_option_selected( $data->event_open, '2' ); } ?> /> <label for="event_none"><?php _e('Does not apply','my-calendar'); ?></label>	
			</p>	
			<p>
			<input type="checkbox" name="event_group" id="event_group" <?php echo jd_option_selected( $data->event_group,'1'); ?> /> <label for="event_group"><?php _e('If this event recurs, it can only be registered for as a complete series.','my-calendar'); ?></label>
			</p>				
			</fieldset>
</div>
</div>			
			<?php } else { ?>
			<div>
			<input type="hidden" name="event_open" value="2" />
			</div>

			<?php } ?>

			<?php if ($mc_input['event_location'] == 'on' || $mc_input['event_location_dropdown'] == 'on') { ?>

<div class="postbox">
<div class="inside">
			<fieldset>
			<legend><?php _e('Event Location','my-calendar'); ?></legend>
			<?php } ?>
			<?php if ($mc_input['event_location_dropdown'] == 'on') { ?>
			<?php $locations = $wpdb->get_results("SELECT location_id,location_label FROM " . MY_CALENDAR_LOCATIONS_TABLE . " ORDER BY location_id ASC");
				if ( !empty($locations) ) {
			?>				
			<p>
			<label for="location_preset"><?php _e('Choose a preset location:','my-calendar'); ?></label> <select name="location_preset" id="location_preset">
				<option value="none"> -- </option>
				<?php
				foreach ( $locations as $location ) {
					$selected = ($data->event_label == $location->location_label)?" selected='selected'":'';
					echo "<option value=\"".$location->location_id."\"$selected>".stripslashes($location->location_label)."</option>";
				}
?>
			</select>
			</p>
<?php
				} else {
				?>
				<input type="hidden" name="location_preset" value="none" />
				<p><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar-locations"><?php _e('Add recurring locations for later use.','my-calendar'); ?></a></p>
				<?php
				}
			?>
			<?php } else { ?>
				<input type="hidden" name="location_preset" value="none" />			
			<?php } ?>
			<?php if ($mc_input['event_location'] == 'on') { ?>			
			<p>
			<?php _e('All location fields are optional: <em>insufficient information may result in an inaccurate map</em>.','my-calendar'); ?>
			</p>	
			<p>
			<label for="event_label"><?php _e('Name of Location (e.g. <em>Joe\'s Bar and Grill</em>)','my-calendar'); ?></label> <input type="text" id="event_label" name="event_label" class="input" size="40" value="<?php if ( !empty($data) ) esc_attr_e(stripslashes($data->event_label)); ?>" />
			</p>
			<p>
			<label for="event_street"><?php _e('Street Address','my-calendar'); ?></label> <input type="text" id="event_street" name="event_street" class="input" size="40" value="<?php if ( !empty($data) ) esc_attr_e(stripslashes($data->event_street)); ?>" />
			</p>			
			<p>
			<label for="event_street2"><?php _e('Street Address (2)','my-calendar'); ?></label> <input type="text" id="event_street2" name="event_street2" class="input" size="40" value="<?php if ( !empty($data) ) esc_attr_e(stripslashes($data->event_street2)); ?>" />
			</p>
			<p>
			<label for="event_city"><?php _e('City','my-calendar'); ?></label> <input type="text" id="event_city" name="event_city" class="input" size="40" value="<?php if ( !empty($data) ) esc_attr_e(stripslashes($data->event_city)); ?>" /> <label for="event_state"><?php _e('State/Province','my-calendar'); ?></label> <input type="text" id="event_state" name="event_state" class="input" size="10" value="<?php if ( !empty($data) ) esc_attr_e(stripslashes($data->event_state)); ?>" /> <label for="event_postcode"><?php _e('Postal Code','my-calendar'); ?></label> <input type="text" id="event_postcode" name="event_postcode" class="input" size="10" value="<?php if ( !empty($data) ) esc_attr_e(stripslashes($data->event_postcode)); ?>" />
			</p>			
			<p>
			<label for="event_country"><?php _e('Country','my-calendar'); ?></label> <input type="text" id="event_country" name="event_country" class="input" size="10" value="<?php if ( !empty($data) ) esc_attr_e(stripslashes($data->event_country)); ?>" />
			</p>
			<p>
			<label for="event_zoom"><?php _e('Initial Zoom','my-calendar'); ?></label> 
				<select name="event_zoom" id="event_zoom">
				<option value="16"<?php if ( !empty( $data ) && ( $data->event_zoom == 16 ) ) { echo " selected=\"selected\""; } ?>><?php _e('Neighborhood','my-calendar'); ?></option>
				<option value="14"<?php if ( !empty( $data ) && ( $data->event_zoom == 14 ) ) { echo " selected=\"selected\""; } ?>><?php _e('Small City','my-calendar'); ?></option>
				<option value="12"<?php if ( !empty( $data ) && ( $data->event_zoom == 12 ) ) { echo " selected=\"selected\""; } ?>><?php _e('Large City','my-calendar'); ?></option>
				<option value="10"<?php if ( !empty( $data ) && ( $data->event_zoom == 10 ) ) { echo " selected=\"selected\""; } ?>><?php _e('Greater Metro Area','my-calendar'); ?></option>
				<option value="8"<?php if ( !empty( $data ) && ( $data->event_zoom == 8 ) ) { echo " selected=\"selected\""; } ?>><?php _e('State','my-calendar'); ?></option>
				<option value="6"<?php if ( !empty( $data ) && ( $data->event_zoom == 6 ) ) { echo " selected=\"selected\""; } ?>><?php _e('Region','my-calendar'); ?></option>
				</select>
			</p>			
			<fieldset>
			<legend><?php _e('GPS Coordinates (optional)','my-calendar'); ?></legend>
			<p>
			<small><?php _e('If you supply GPS coordinates for your location, they will be used in place of any other address information to provide your map link.','my-calendar'); ?></small>
			</p>
			<p>
			<label for="event_latitude"><?php _e('Latitude','my-calendar'); ?></label> <input type="text" id="event_latitude" name="event_latitude" class="input" size="10" value="<?php if ( !empty( $data ) ) esc_attr_e(stripslashes($data->event_latitude)); ?>" /> <label for="event_longitude"><?php _e('Longitude','my-calendar'); ?></label> <input type="text" id="event_longitude" name="event_longitude" class="input" size="10" value="<?php if ( !empty( $data ) ) esc_attr_e(stripslashes($data->event_longitude)); ?>" />
			</p>			
			</fieldset>	
			<?php } ?>
			<?php if ($mc_input['event_location'] == 'on' || $mc_input['event_location_dropdown'] == 'on') { ?>
			</fieldset>
		</div>
		</div>
</div>
			<?php }
}

// Used on the manage events admin page to display a list of events
function jd_events_display_list($sortby='default',$sortdir='default',$status='all',$type='normal') {
	global $wpdb;
	if ($sortby == 'default') {
		$sortbyvalue = 'event_begin';
	} else {
		switch ($sortby) {
		    case 1:$sortbyvalue = 'event_ID';
			break;
			case 2:$sortbyvalue = 'event_title';
			break;
			case 3:$sortbyvalue = 'event_desc';
			break;
			case 4:$sortbyvalue = 'event_begin';
			break;
			case 5:$sortbyvalue = 'event_author';
			break;
			case 6:$sortbyvalue = 'event_category';
			break;
			case 7:$sortbyvalue = 'event_label';
			break;
			default:$sortbyvalue = 'event_begin';
		}
	}
	if ($sortdir == 'default') {
		$sortbydirection = 'DESC';
	} else {
		$sortbydirection = $sortdir;
	}
	
	switch ($status) {
		case 'all':$limit = '';
		break;
		case 'reserved':$limit = 'WHERE event_approved = 0';
		break;
		case 'published':$limit = 'WHERE event_approved = 1';
		break;
		default:$limit = '';
	}
	
	$events = $wpdb->get_results("SELECT * FROM " . MY_CALENDAR_TABLE . " $limit ORDER BY $sortbyvalue $sortbydirection");

	if ($sortbydirection == 'DESC') {
		$sorting = "&amp;order=ASC";
	} else {
		$sorting = '';
	}
	
	if ( !empty($events) ) {
		?>
		<form action="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar" method="post">
		<div>
		<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('my-calendar-nonce'); ?>" />
		</div>
<table class="widefat page fixed" id="my-calendar-admin-table" summary="<?php _e('Table of Calendar Events','my-calendar'); ?>">
	<thead>
	<tr>
		<th class="manage-column n4" scope="col"><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;sort=1<?php echo $sorting; ?>"><?php _e('ID','my-calendar') ?></a></th>
		<th class="manage-column" scope="col"><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;sort=2<?php echo $sorting; ?>"><?php _e('Title','my-calendar') ?></a></th>
		<th class="manage-column n1" scope="col"><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;sort=7<?php echo $sorting; ?>"><?php _e('Location','my-calendar') ?></a></th>
		<th class="manage-column n8" scope="col"><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;sort=3<?php echo $sorting; ?>"><?php _e('Description','my-calendar') ?></a></th>
		<th class="manage-column n5" scope="col"><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;sort=4<?php echo $sorting; ?>"><?php _e('Start Date','my-calendar') ?></a></th>
		<th class="manage-column n6" scope="col"><?php _e('Recurs','my-calendar') ?></th>
		<th class="manage-column n3" scope="col"><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;sort=5<?php echo $sorting; ?>"><?php _e('Author','my-calendar') ?></a></th>
		<th class="manage-column n2" scope="col"><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;sort=6<?php echo $sorting; ?>"><?php _e('Category','my-calendar') ?></a></th>
		<th class="manage-column n7" scope="col"><?php _e('Edit / Delete','my-calendar') ?></th>
	</tr>
	</thead>
		<?php
		$class = '';
		$sql = "SELECT * FROM " . MY_CALENDAR_CATEGORIES_TABLE ;
        $categories = $wpdb->get_results($sql);
			
		foreach ( $events as $event ) {
			$class = ($class == 'alternate') ? '' : 'alternate';
			$author = get_userdata($event->event_author); 
			if ($event->event_link != '') { 
			$title = "<a href='$event->event_link'>$event->event_title</a>";
			} else {
			$title = $event->event_title;
			}
			?>
			<tr class="<?php echo $class; ?>">
				<th scope="row"><input type="checkbox" value="<?php echo $event->event_id; ?>" name="mass_delete[]" id="mc<?php echo $event->event_id; ?>" /> <label for="mc<?php echo $event->event_id; ?>"><?php echo $event->event_id; ?></label></th>
				<td><?php echo stripslashes($title); ?></td>
				<td><?php echo stripslashes($event->event_label); ?></td>
				<td><?php echo substr(strip_tags(stripslashes($event->event_desc)),0,60); ?>&hellip;</td>
				<?php if ($event->event_time != "00:00:00") { $eventTime = date_i18n(get_option('time_format'), strtotime($event->event_time)); } else { $eventTime = get_option('my_calendar_notime_text'); } ?>
				<td><?php echo "$event->event_begin, $eventTime"; ?></td>
				<?php /* <td><?php echo $event->event_end; ?></td> */ ?>
				<td>
				<?php 
					// Interpret the DB values into something human readable
					if ($event->event_recur == 'S') { _e('Never','my-calendar'); } 
					else if ($event->event_recur == 'D') { _e('Daily','my-calendar'); }
					else if ($event->event_recur == 'W') { _e('Weekly','my-calendar'); }
					else if ($event->event_recur == 'B') { _e('Bi-Weekly','my-calendar'); }
					else if ($event->event_recur == 'M') { _e('Monthly (by date)','my-calendar'); }
					else if ($event->event_recur == 'U') { _e('Monthly (by day)','my-calendar'); }
					else if ($event->event_recur == 'Y') { _e('Yearly','my-calendar'); }
				?>&thinsp;&ndash;&thinsp;<?php
					if ($event->event_recur == 'S') { echo __('N/A','my-calendar'); }
					else if ($event->event_repeats == 0) { echo __('Forever','my-calendar'); }
					else if ($event->event_repeats > 0) { echo $event->event_repeats.' '.__('Times','my-calendar'); }					
				?>				
				</td>
				<td><?php echo $author->display_name; ?></td>
                                <?php
								$this_category = $event->event_category;
								foreach ($categories as $key=>$value) {
									if ($value->category_id == $this_category) {
										$this_cat = $categories[$key];
									} 
								}
                                ?>
				<td><div class="category-color" style="background-color:<?php echo (strpos($this_cat->category_color,'#') !== 0)?'#':''; echo $this_cat->category_color;?>;"> </div> <?php echo stripslashes($this_cat->category_name); ?></td>
				<?php unset($this_cat); ?>
				<td>
				<a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;mode=copy&amp;event_id=<?php echo $event->event_id;?>" class='copy'><?php echo __('Copy','my-calendar'); ?></a> &middot; 
				<?php if ( mc_can_edit_event( $event->event_author ) ) { ?>
				<a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;mode=edit&amp;event_id=<?php echo $event->event_id;?>" class='edit'><?php echo __('Edit','my-calendar'); ?></a> &middot; <a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;mode=delete&amp;event_id=<?php echo $event->event_id;?>" class="delete"><?php echo __('Delete','my-calendar'); ?></a>
				<?php } else { _e("Not editable.",'my-calendar'); } ?>
				<?php if ( get_option( 'mc_event_approve' ) == 'true' ) { ?>
				 &middot; 
						<?php if ( current_user_can( get_option('mc_event_approve_perms') ) ) { // Added by Roland P.?>
							<?php	// by Roland 
							if ( $event->event_approved == '1' )  { ?>
								<a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;mode=reject&amp;event_id=<?php echo $event->event_id;?>" class='reject'><?php echo __('Reject','my-calendar'); ?></a>
							<?php } else { 	?>
								<a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar&amp;mode=approve&amp;event_id=<?php echo $event->event_id;?>" class='publish'><?php echo __('Approve','my-calendar'); ?></a>		
							<?php } ?>
						<?php } else { ?>
							<?php	// by Roland 
							if ( $event->event_approved == '1' )  { ?>
								<?php echo __('Approved','my-calendar'); ?>
							<?php } else if ($event->event_approved == '2' ) { 	?>
								<?php echo __('Rejected','my-calendar'); ?>							
							<?php } else { ?>
								<?php echo __('Awaiting Approval','my-calendar'); ?>		
							<?php } ?>
						<?php } ?>	
				<?php } ?>					
				</td>	
			</tr>
<?php
		}
?>
		</table>
		<p>
		<input type="submit" class="button-primary delete" value="Delete checked events" />
		</p>
		</form>
<?php
	} else {
?>
		<p><?php _e("There are no events in the database!",'my-calendar') ?></p>
<?php	
	}
}
function mc_check_data($action,$_POST) {
global $wpdb, $current_user, $users_entries;

if (!wp_verify_nonce($_POST['event_nonce_name'],'event_nonce')) {
return;
}

$errors = "";
if ( $action == 'add' || $action == 'edit' || $action == 'copy' ) {
	$title = !empty($_POST['event_title']) ? trim($_POST['event_title']) : '';
	$desc = !empty($_POST['event_desc']) ? trim($_POST['event_desc']) : '';
	$short = !empty($_POST['event_short']) ? trim($_POST['event_short']) : '';
	$begin = !empty($_POST['event_begin']) ? trim($_POST['event_begin']) : '';
	$end = !empty($_POST['event_end']) ? trim($_POST['event_end']) : $begin;
	$time = !empty($_POST['event_time']) ? trim($_POST['event_time']) : '';
	$endtime = !empty($_POST['event_endtime']) ? trim($_POST['event_endtime']) : '';
	$recur = !empty($_POST['event_recur']) ? trim($_POST['event_recur']) : '';
	$repeats = !empty($_POST['event_repeats']) ? trim($_POST['event_repeats']) : 0;
	$host = !empty($_POST['event_host']) ? $_POST['event_host'] : $current_user->ID;	
	$category = !empty($_POST['category_id']) ? $_POST['category_id'] : '';
    $linky = !empty($_POST['event_link']) ? trim($_POST['event_link']) : '';
    $expires = !empty($_POST['event_link_expires']) ? $_POST['event_link_expires'] : '0';
    $approved = !empty($_POST['event_approved']) ? $_POST['event_approved'] : '0';
	$location_preset = !empty($_POST['location_preset']) ? $_POST['location_preset'] : '';
    $event_author = !empty($_POST['event_author']) ? $_POST['event_author'] : $current_user->ID;
	$event_open = !empty($_POST['event_open']) ? $_POST['event_open'] : '2';
	$event_group = !empty($_POST['event_group']) ? 1 : 0;
	$image = !empty($_POST['event_image']) ? trim($_POST['event_image']) : '';
	$featured = !empty($_POST['event_featured']) ? 1 : 0;
	// set location
		if ($location_preset != 'none') {
			$sql = "SELECT * FROM " . MY_CALENDAR_LOCATIONS_TABLE . " WHERE location_id = $location_preset";
			$location = $wpdb->get_row($sql);
			$event_label = $location->location_label;
			$event_street = $location->location_street;
			$event_street2 = $location->location_street2;
			$event_city = $location->location_city;
			$event_state = $location->location_state;
			$event_postcode = $location->location_postcode;
			$event_country = $location->location_country;
			$event_longitude = $location->location_longitude;
			$event_latitude = $location->location_latitude;
			$event_zoom = $location->location_zoom;
		} else {
			$event_label = !empty($_POST['event_label']) ? $_POST['event_label'] : '';
			$event_street = !empty($_POST['event_street']) ? $_POST['event_street'] : '';
			$event_street2 = !empty($_POST['event_street2']) ? $_POST['event_street2'] : '';
			$event_city = !empty($_POST['event_city']) ? $_POST['event_city'] : '';
			$event_state = !empty($_POST['event_state']) ? $_POST['event_state'] : '';
			$event_postcode = !empty($_POST['event_postcode']) ? $_POST['event_postcode'] : '';
			$event_country = !empty($_POST['event_country']) ? $_POST['event_country'] : '';
			$event_longitude = !empty($_POST['event_longitude']) ? $_POST['event_longitude'] : '';	
			$event_latitude = !empty($_POST['event_latitude']) ? $_POST['event_latitude'] : '';	
			$event_zoom = !empty($_POST['event_zoom']) ? $_POST['event_zoom'] : '';	
	    }
	// Deal with those who have magic quotes turned on
	if ( ini_get('magic_quotes_gpc') ) {
		$title = stripslashes($title);
		$desc = stripslashes($desc);
		$short = stripslashes($short);
		$begin = stripslashes($begin);
		$end = stripslashes($end);
		$time = stripslashes($time);
		$endtime = stripslashes($endtime);
		$recur = stripslashes($recur);
		$host = stripslashes($host);
		$repeats = stripslashes($repeats);
		// $category = stripslashes($category);
		$linky = stripslashes($linky);	
		$expires = stripslashes($expires);
		$event_open = stripslashes($event_open);
		$event_label = stripslashes($event_label);
		$event_street = stripslashes($event_street);
		$event_street2 = stripslashes($event_street2);
		$event_city = stripslashes($event_city);
		$event_state = stripslashes($event_state);
		$event_postcode = stripslashes($event_postcode);
		$event_country = stripslashes($event_country);	
		$event_longitude = stripslashes($event_longitude);	
		$event_latitude = stripslashes($event_latitude);	
		$event_zoom = stripslashes($event_zoom);
		$event_group = stripslashes($event_group);	
		$approved = stripslashes($approved);	
	}
	// Perform some validation on the submitted dates - this checks for valid years and months
	$date_format_one = '/^([0-9]{4})-([0][1-9])-([0-3][0-9])$/';
    $date_format_two = '/^([0-9]{4})-([1][0-2])-([0-3][0-9])$/';
	if ((preg_match($date_format_one,$begin) || preg_match($date_format_two,$begin)) && (preg_match($date_format_one,$end) || preg_match($date_format_two,$end))) {
            // We know we have a valid year and month and valid integers for days so now we do a final check on the date
        $begin_split = split('-',$begin);
	    $begin_y = $begin_split[0]; 
	    $begin_m = $begin_split[1];
	    $begin_d = $begin_split[2];
        $end_split = split('-',$end);
	    $end_y = $end_split[0];
	    $end_m = $end_split[1];
	    $end_d = $end_split[2];
        if (checkdate($begin_m,$begin_d,$begin_y) && checkdate($end_m,$end_d,$end_y)) {
// Ok, now we know we have valid dates, we want to make sure that they are either equal or that the end date is later than the start date
			if (strtotime($end) >= strtotime($begin)) {
			$start_date_ok = 1;
			$end_date_ok = 1;
			} else {
				$errors .= "<div class='error'><p><strong>".__('Error','my-calendar').":</strong> ".__('Your event end date must be either after or the same as your event begin date','my-calendar')."</p></div>";
			}
		} else {
				$errors .= "<div class='error'><p><strong>".__('Error','my-calendar').":</strong> ".__('Your date formatting is correct but one or more of your dates is invalid. Check for number of days in month and leap year related errors.','my-calendar')."</p></div>";
		}
	} else {
		$errors .= "<div class='error'><p><strong>".__('Error','my-calendar').":</strong> ".__('Both start and end dates must be in the format YYYY-MM-DD','my-calendar')."</p></div>";
	}
        // We check for a valid time, or an empty one
        $time_format_one = '/^([0-1][0-9]):([0-5][0-9])$/';
		$time_format_two = '/^([2][0-3]):([0-5][0-9])$/';
        if (preg_match($time_format_one,$time) || preg_match($time_format_two,$time) || $time == '') {
            $time_ok = 1;
			if ( strlen($time) == 5 ) { $time = $time . ":00";	}
			if ( strlen($time) == 0 ) { $time = "00:00:00"; }
        } else {
			$errors .= "<div class='error'><p><strong>".__('Error','my-calendar').":</strong> ".__('The time field must either be blank or be entered in the format hh:mm','my-calendar')."</p></div>";
	    }
        // We check for a valid end time, or an empty one
        if (preg_match($time_format_one,$endtime) || preg_match($time_format_two,$endtime) || $endtime == '') {
            $endtime_ok = 1;
			if ( strlen($endtime) == 5 ) { $endtime = $endtime . ":00";	}
			if ( strlen($endtime) == 0 ) { $endtime = "00:00:00"; }
        } else {
            $errors .= "<div class='error'><p><strong>".__('Error','my-calendar').":</strong> ".__('The end time field must either be blank or be entered in the format hh:mm','my-calendar')."</p></div>";
	    }		
		// We check to make sure the URL is acceptable (blank or starting with http://)                                                        
		if ($linky == '') {
			$url_ok = 1;
		} else if ( preg_match('/^(http)(s?)(:)\/\//',$linky) ) {
			$url_ok = 1;
		} else {
			$linky = "http://" . $linky;
		}
	}
	// The title must be at least one character in length and no more than 255 - only basic punctuation is allowed
	$title_length = strlen($title);
	if ( $title_length > 1 && $title_length <= 255 ) {
	    $title_ok =1;
	} else {
		$errors .= "<div class='error'><p><strong>".__('Error','my-calendar').":</strong> ".__('The event title must be between 1 and 255 characters in length.','my-calendar')."</p></div>";
	}
	// We run some checks on recurrance                                                                        
	if (($repeats == 0 && $recur == 'S') || (($repeats >= 0) && ($recur == 'W' || $recur == 'B' || $recur == 'M' || $recur == 'U' || $recur == 'Y' || $recur == 'D'))) {
	    $recurring_ok = 1;
	} else {
		$errors .= "<div class='error'><p><strong>".__('Error','my-calendar').":</strong> ".__('The repetition value must be 0 unless a type of recurrence is selected.','my-calendar')."</p></div>";
	}
	if ($start_date_ok == 1 && $end_date_ok == 1 && $time_ok == 1 && $endtime_ok == 1 && $url_ok == 1 && $title_ok == 1 && $recurring_ok == 1) {
		$proceed = true;
		if ($action == 'add' || $action == 'copy' ) {
			$submit = array(
				'event_begin'=>$begin, 
				'event_end'=>$end, 
				'event_title'=>$title, 
				'event_desc'=>$desc, 			
				'event_time'=>$time, 
				'event_recur'=>$recur, 
				'event_repeats'=>$repeats, 
				'event_author'=>$current_user->ID,
				'event_category'=>$category, 
				'event_link'=>$linky,
				'event_label'=>$event_label, 
				'event_street'=>$event_street, 
				'event_street2'=>$event_street2, 
				'event_city'=>$event_city, 
				'event_state'=>$event_state, 
				'event_postcode'=>$event_postcode, 
				'event_country'=>$event_country,
				'event_endtime'=>$endtime, 								
				'event_link_expires'=>$expires, 				
				'event_longitude'=>$event_longitude,
				'event_latitude'=>$event_latitude,
				'event_zoom'=>$event_zoom,
				'event_short'=>$short,
				'event_open'=>$event_open,
				'event_group'=>$event_group,
				'event_approved'=>$approved,
				'event_host'=>$host,
				'event_image'=>$image,
				'event_featured'=>$featured				
				);
			
		} else if ($action == 'edit') {
			$submit = array(
				'event_begin'=>$begin, 
				'event_end'=>$end, 
				'event_title'=>$title, 
				'event_desc'=>$desc, 			
				'event_time'=>$time, 
				'event_recur'=>$recur, 
				'event_repeats'=>$repeats, 
				'event_category'=>$category, 
				'event_link'=>$linky,
				'event_label'=>$event_label, 
				'event_street'=>$event_street, 
				'event_street2'=>$event_street2, 
				'event_city'=>$event_city, 
				'event_state'=>$event_state, 
				'event_postcode'=>$event_postcode, 
				'event_country'=>$event_country,
				'event_endtime'=>$endtime, 				
				'event_link_expires'=>$expires, 				
				'event_longitude'=>$event_longitude,
				'event_latitude'=>$event_latitude,
				'event_zoom'=>$event_zoom,
				'event_short'=>$short,
				'event_open'=>$event_open,
				'event_group'=>$event_group,
				'event_approved'=>$approved,
				'event_host'=>$host,
				'event_image'=>$image,
				'event_featured'=>$featured		
				);		
		}		
		
	} else {
	    // The form is going to be rejected due to field validation issues, so we preserve the users entries here
		$users_entries->event_title = $title;
		$users_entries->event_desc = $desc;
		$users_entries->event_begin = $begin;
		$users_entries->event_end = $end;
		$users_entries->event_time = $time;
		$users_entries->event_endtime = $endtime;
		$users_entries->event_recur = $recur;
		$users_entries->event_repeats = $repeats;
		$users_entries->event_host = $host;
		$users_entries->event_category = $category;
		$users_entries->event_link = $linky;
		$users_entries->event_link_expires = $expires;
		$users_entries->event_label = $event_label;
		$users_entries->event_street = $event_street;
		$users_entries->event_street2 = $event_street2;
		$users_entries->event_city = $event_city;
		$users_entries->event_state = $event_state;
		$users_entries->event_postcode = $event_postcode;
		$users_entries->event_country = $event_country;	
		$users_entries->event_longitude = $event_longitude;		
		$users_entries->event_latitude = $event_latitude;		
		$users_entries->event_zoom = $event_zoom;
		$users_entries->event_author = $event_author;
		$users_entries->event_open = $event_open;
		$users_entries->event_short = $short;
		$users_entries->event_group = $event_group;
		$users_entries->event_approved = $approved;
		$users_entries->event_image = $image;
		$users_entries->event_featured = $featured;
		$proceed = false;
	}
	$data = array($proceed, $users_entries, $submit,$errors);
	return $data;
}
?>
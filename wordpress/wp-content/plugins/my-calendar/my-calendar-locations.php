<?php
// Function to handle the management of locations

function my_calendar_manage_locations() {
  global $wpdb;

  // My Calendar must be installed and upgraded before this will work
  check_my_calendar();
  
  
?>
<div class="wrap">
<?php 
echo my_calendar_check_db();
?>
<?php
  // We do some checking to see what we're doing
  if (!empty($_POST)) {
	$nonce=$_REQUEST['_wpnonce'];
    if (! wp_verify_nonce($nonce,'my-calendar-nonce') ) die("Security check failed");
  }
  
  if (isset($_POST['mode']) && $_POST['mode'] == 'add') {
      $sql = "INSERT INTO " . MY_CALENDAR_LOCATIONS_TABLE . " SET location_label='".mysql_real_escape_string($_POST['location_label'])."', location_street='".mysql_real_escape_string($_POST['location_street'])."', location_street2='".mysql_real_escape_string($_POST['location_street2'])."', location_city='".mysql_real_escape_string($_POST['location_city'])."', location_state='".mysql_real_escape_string($_POST['location_state'])."', location_postcode='".mysql_real_escape_string($_POST['location_postcode'])."', location_country='".mysql_real_escape_string($_POST['location_country'])."', location_longitude='".mysql_real_escape_string($_POST['location_longitude'])."', location_latitude='".mysql_real_escape_string($_POST['location_latitude'])."', location_zoom='".mysql_real_escape_string($_POST['location_zoom'])."'";
      $results = $wpdb->query($sql);
	  if ($results) {
      echo "<div class=\"updated\"><p><strong>".__('Location added successfully','my-calendar')."</strong></p></div>";
	  } else {
      echo "<div class=\"error\"><p><strong>".__('Location could not be added to database','my-calendar')."</strong></p></div>";
	  }
    } else if (isset($_GET['mode']) && isset($_GET['location_id']) && $_GET['mode'] == 'delete') {
      $sql = "DELETE FROM " . MY_CALENDAR_LOCATIONS_TABLE . " WHERE location_id=".mysql_real_escape_string($_GET['location_id']);
      $results = $wpdb->query($sql);
	  if ($results) {
      echo "<div class=\"updated\"><p><strong>".__('Location deleted successfully','my-calendar')."</strong></p></div>";
	  } else {
      echo "<div class=\"error\"><p><strong>".__('Location could not be deleted','my-calendar')."</strong></p></div>";	  
	  }
    } else if (isset($_GET['mode']) && isset($_GET['location_id']) && $_GET['mode'] == 'edit' && !isset($_POST['mode'])) {
      $sql = "SELECT * FROM " . MY_CALENDAR_LOCATIONS_TABLE . " WHERE location_id=".mysql_real_escape_string($_GET['location_id']);
      $cur_loc = $wpdb->get_row($sql);
	  
      mc_show_location_form('edit', $cur_loc);
    } else if (isset($_POST['mode']) && isset($_POST['location_id']) && isset($_POST['location_label']) && isset($_POST['location_street']) && $_POST['mode'] == 'edit') {
      $sql = "UPDATE " . MY_CALENDAR_LOCATIONS_TABLE . " SET location_label='".mysql_real_escape_string($_POST['location_label'])."', location_street='".mysql_real_escape_string($_POST['location_street'])."', location_street2='".mysql_real_escape_string($_POST['location_street2'])."', location_city='".mysql_real_escape_string($_POST['location_city'])."', location_state='".mysql_real_escape_string($_POST['location_state'])."', location_postcode='".mysql_real_escape_string($_POST['location_postcode'])."', location_country='".mysql_real_escape_string($_POST['location_country'])."', location_longitude='".mysql_real_escape_string($_POST['location_longitude'])."', location_latitude='".mysql_real_escape_string($_POST['location_latitude'])."', location_zoom='".mysql_real_escape_string($_POST['location_zoom'])."' WHERE location_id=".mysql_real_escape_string($_POST['location_id']);
	  $results = $wpdb->query($sql);
      if ( $results === false ) {
	  echo "<div class=\"error\"><p><strong>".__('Location could not be edited.','my-calendar')."</strong></p></div>";
	  } else if ( $results == 0 ) {
	  echo "<div class=\"updated\"><p><strong>".__('Location was not changed.','my-calendar')."</strong></p></div>";  
	  } else {
	  echo "<div class=\"updated\"><p><strong>".__('Location edited successfully','my-calendar')."</strong></p></div>";
	  }
	}

	if ($_GET['mode'] != 'edit' || $_POST['mode'] == 'edit') {
	mc_show_location_form('add');
  } 
}

function mc_show_location_form($view='add',$cur_loc='') {
?>
<?php if ($view == 'add') { ?>
<h2><?php _e('Add New Location','my-calendar'); ?></h2>
<?php } else { ?>
<h2><?php _e('Edit Location','my-calendar'); ?></h2>
<?php } ?>
<?php jd_show_support_box(); ?>   
<div id="poststuff" class="jd-my-calendar">
<div class="postbox">
<h3><?php _e('Location Editor','my-calendar'); ?></h3>
	<div class="inside">	   
    <form name="my-calendar"  id="my-calendar" method="post" action="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar-locations">
	<div><input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('my-calendar-nonce'); ?>" /></div>	
		<?php if ( $view == 'add' ) { ?>
			<div>
			<input type="hidden" name="mode" value="add" />
            <input type="hidden" name="location_id" value="" />
			</div>
		<?php } else { ?>
		<div>
			<input type="hidden" name="mode" value="edit" />
            <input type="hidden" name="location_id" value="<?php echo $cur_loc->location_id ?>" />
		</div>
		<?php } ?>
			<fieldset>
			<legend><?php _e('Event Location','my-calendar'); ?></legend>
			<p>
			<?php _e('All location fields are optional: <em>insufficient information may result in an inaccurate map</em>.','my-calendar'); ?>
			</p>
			<p>
			<label for="location_label"><?php _e('Name of Location (e.g. <em>Joe\'s Bar and Grill</em>)','my-calendar'); ?></label> <input type="text" id="location_label" name="location_label" class="input" size="40" value="<?php if ( !empty( $cur_loc ) ) echo htmlspecialchars(stripslashes($cur_loc->location_label)); ?>" />
			</p>
			<p>
			<label for="location_street"><?php _e('Street Address','my-calendar'); ?></label> <input type="text" id="location_street" name="location_street" class="input" size="40" value="<?php if ( !empty( $cur_loc ) ) echo htmlspecialchars(stripslashes($cur_loc->location_street)); ?>" />
			</p>			
			<p>
			<label for="location_street2"><?php _e('Street Address (2)','my-calendar'); ?></label> <input type="text" id="location_street2" name="location_street2" class="input" size="40" value="<?php if ( !empty( $cur_loc ) ) echo htmlspecialchars(stripslashes($cur_loc->location_street2)); ?>" />
			</p>
			<p>
			<label for="location_city"><?php _e('City','my-calendar'); ?></label> <input type="text" id="location_city" name="location_city" class="input" size="40" value="<?php if ( !empty( $cur_loc ) ) echo htmlspecialchars(stripslashes($cur_loc->location_city)); ?>" /> <label for="location_state"><?php _e('State/Province','my-calendar'); ?></label> <input type="text" id="location_state" name="location_state" class="input" size="10" value="<?php if ( !empty( $cur_loc ) ) echo htmlspecialchars($cur_loc->location_state); ?>" /> <label for="location_postcode"><?php _e('Postal Code','my-calendar'); ?></label> <input type="text" id="location_postcode" name="location_postcode" class="input" size="10" value="<?php if ( !empty( $cur_loc ) ) echo htmlspecialchars($cur_loc->location_postcode); ?>" />
			</p>			
			<p>
			<label for="location_country"><?php _e('Country','my-calendar'); ?></label> <input type="text" id="location_country" name="location_country" class="input" size="10" value="<?php if ( !empty( $cur_loc ) ) echo htmlspecialchars(stripslashes($cur_loc->location_country)); ?>" />
			</p>
			<p>
			<label for="location_zoom"><?php _e('Initial Zoom','my-calendar'); ?></label> 
				<select name="location_zoom" id="location_zoom">
				<option value="16"<?php if ( !empty( $cur_loc ) && ( $cur_loc->location_zoom == 16 ) ) { echo " selected=\"selected\""; } ?>><?php _e('Neighborhood','my-calendar'); ?></option>
				<option value="14"<?php if ( !empty( $cur_loc ) && ( $cur_loc->location_zoom == 14 ) ) { echo " selected=\"selected\""; } ?>><?php _e('Small City','my-calendar'); ?></option>
				<option value="12"<?php if ( !empty( $cur_loc ) && ( $cur_loc->location_zoom == 12 ) ) { echo " selected=\"selected\""; } ?>><?php _e('Large City','my-calendar'); ?></option>
				<option value="10"<?php if ( !empty( $cur_loc ) && ( $cur_loc->location_zoom == 10 ) ) { echo " selected=\"selected\""; } ?>><?php _e('Greater Metro Area','my-calendar'); ?></option>
				<option value="8"<?php if ( !empty( $cur_loc ) && ( $cur_loc->location_zoom == 8 ) ) { echo " selected=\"selected\""; } ?>><?php _e('State','my-calendar'); ?></option>
				<option value="6"<?php if ( !empty( $cur_loc ) && ( $cur_loc->location_zoom == 6 ) ) { echo " selected=\"selected\""; } ?>><?php _e('Region','my-calendar'); ?></option>
				</select>
			</p>
			<fieldset>
			<legend><?php _e('GPS Coordinates (optional)','my-calendar'); ?></legend>
			<p>
			<small><?php _e('If you supply GPS coordinates for your location, they will be used in place of any other address information to pinpoint your location.','my-calendar'); ?></small>
			</p>
			<p>
			 <label for="location_latitude"><?php _e('Latitude','my-calendar'); ?></label> <input type="text" id="location_latitude" name="location_latitude" class="input" size="10" value="<?php if ( !empty( $cur_loc ) ) { echo htmlspecialchars(stripslashes($cur_loc->location_latitude)); } else { echo '0.000000'; } ?>" />
			 <label for="location_longitude"><?php _e('Longitude','my-calendar'); ?></label> <input type="text" id="location_longitude" name="location_longitude" class="input" size="10" value="<?php if ( !empty( $cur_loc ) ) { echo htmlspecialchars(stripslashes($cur_loc->location_longitude)); } else { echo '0.000000'; } ?>" />
			</p>			
			</fieldset>
			<p>
                <input type="submit" name="save" class="button-primary" value="<?php if ($view == 'edit') { _e('Save Changes','my-calendar'); } else { _e('Add Location','my-calendar'); } ?> &raquo;" />
			</p>
			</fieldset>
		</form>
	</div>
</div>
<?php mc_manage_locations(); ?>
</div>
<?php
}

function mc_manage_locations() {
global $wpdb;
?>
    <h2><?php _e('Manage Locations','my-calendar'); ?></h2>
<?php
    
    // We pull the locations from the database	
    $locations = $wpdb->get_results("SELECT * FROM " . MY_CALENDAR_LOCATIONS_TABLE . " ORDER BY location_id ASC");

 if ( !empty($locations) )
   {
     ?>
     <table class="widefat page fixed" id="my-calendar-location-listing" summary="Manage Locations Listing">
       <thead> 
       <tr>
         <th class="manage-column" scope="col"><?php _e('ID','my-calendar') ?></th>
	 <th class="manage-column" scope="col"><?php _e('Location','my-calendar') ?></th>
	 <th class="manage-column" scope="col"><?php _e('Edit','my-calendar') ?></th>
	 <th class="manage-column" scope="col"><?php _e('Delete','my-calendar') ?></th>
       </tr>
       </thead>
       <?php
       $class = '';
       foreach ( $locations as $location ) {
	   $class = ($class == 'alternate') ? '' : 'alternate';
           ?>
           <tr class="<?php echo $class; ?>">
	     <th scope="row"><?php echo $location->location_id; ?></th>
	     <td><?php echo stripslashes($location->location_label) . "<br />" . stripslashes($location->location_street) . "<br />" . stripslashes($location->location_street2) . "<br />" . stripslashes($location->location_city) . ", " . stripslashes($location->location_state) . " " . stripslashes($location->location_postcode); ?></td>
	     <td><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar-locations&amp;mode=edit&amp;location_id=<?php echo $location->location_id;?>" class='edit'><?php echo __('Edit','my-calendar'); ?></a></td>
         <td><a href="<?php bloginfo('wpurl'); ?>/wp-admin/admin.php?page=my-calendar-locations&amp;mode=delete&amp;location_id=<?php echo $location->location_id;?>" class="delete" onclick="return confirm('<?php echo __('Are you sure you want to delete this category?','my-calendar'); ?>')"><?php echo __('Delete','my-calendar'); ?></a></td>
         </tr>
                <?php
          }
      ?>
      </table>
      <?php
   } else {
     echo '<p>'.__('There are no locations in the database yet!','my-calendar').'</p>';
   }
?>
<p><em>
<?php _e('Please note: editing or deleting locations stored for re-use will have no effect on any event previously scheduled at that location. The location database exists purely as a shorthand method to enter frequently used locations into event records.','my-calendar'); ?>
</em></p>
  </div>
<?php
}
?>

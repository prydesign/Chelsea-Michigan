<?php 
// Template Name: Event View
get_header(); ?>

<div id="side-bar">
	<div class="sidebar-ad">
	<!-- Sidebar -->
	<script type='text/javascript'>
	GA_googleFillSlot("Sidebar");
	</script>
	</div>
	<div class="sidebar-ad">
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/pages/Chelsea-Michigan/156736827715825" width="250" show_faces="true" stream="false" header="false"></fb:like-box>
	</div>
</div>


<?php
$event_id = event_view_id();
$sql = "SELECT * from wp_my_calendar WHERE event_id = $event_id LIMIT 1";
$events = $wpdb->get_results($sql);

// echo '<pre>';
// print_r($event);
// echo '</pre>';

foreach ($events as $event) { 

	$map_string = $event->event_street.' '.$event->event_street2.' '.$event->event_city.' '.$event->event_state.' '.$event->event_postcode.' '.$event->event_country;
	$address .= '<div class="address vcard">';
			$address .= "<div class=\"adr\">";
				if ($event->event_label != "") {
					$address .= "<strong class=\"org\">".stripslashes($event->event_label)."</strong><br />";
				}
				if ($event->event_street != "") {
					$address .= "<div class=\"street-address\">".stripslashes($event->event_street)."</div>";
				}
				if ($event->event_street2 != "") {
					$address .= "<div class=\"street-address\">".stripslashes($event->event_street2)."</div>";
				}
				if ($event->event_city != "") {
					$address .= "<span class=\"locality\">".stripslashes($event->event_city).",</span>";
				}
				if ($event->event_state != "") {
					$address .= " <span class=\"region\">".stripslashes($event->event_state)."</span> ";
				}
				if ($event->event_postcode != "") {
					$address .= " <span class=\"postal-code\">".stripslashes($event->event_postcode)."</span>";
				}	
				if ($event->event_country != "") {
					$address .= "<div class=\"country-name\">".stripslashes($event->event_country)."</div>";
				}
			$address .= "</div>";
		if ( $event->event_longitude != '0.000000' && $event->event_latitude != '0.000000') {
				$map_string = str_replace(" ","+",$map_string);
				if ($event->event_label != "") {
					$map_label = stripslashes($event->event_label);
				} else {
					$map_label = stripslashes($event->event_title);
				}
				$zoom = ($event->event_zoom != 0)?$event->event_zoom:'15';
				$map_string_label = urlencode($map_label);
				if ($event->event_longitude != '0.000000' && $event->event_latitude != '0.000000') {
					$map_string = "$event->event_latitude,$event->event_longitude+($map_string_label)";
				}
				$map = "<a href=\"http://maps.google.com/maps?f=q&amp;z=$zoom&amp;q=$map_string\">Map<span> to $map_label</span></a>";
				$address .= "<div class=\"url map\">$map</div>";
		}
	$address .= "</div>";




?>



<div id="text">
	<table cellpadding="0" cellspacing="0" id="event-details">
		<tr>
	<?php if(!empty($event->event_image) || $event->event_image != 0) { ?>
		<td style="padding-right: 14px" valign="top">
			<img style="padding: 4px; border: 1px solid #ccc" src="<?php echo $event->event_image; ?>" alt="<?php echo $event->event_title; ?>" width="300">
		</td>
	<?php } ?>
		<td valign="top">
			<h1 id="event-title"><?php echo $event->event_title; ?></h1>
			
			<?php	$begin_date = strtotime($event->event_begin); $end_date = strtotime($event->event_end)?>
			<div style="float: left; margin-right: 34px;">
				<h4>When</h4>
				Starts on <?php echo strftime('%a. %b. %e, %Y', $begin_date); ?><br />
				Ends on <?php echo strftime('%a. %b. %e, %Y', $end_date); ?><br />
				<?php 
				if ( $event->event_time != "00:00:00" && $event->event_time != '' ) {
					echo "<span class='event-time' title='".$event->event_begin.'T'.$event->event_time."'>Time: ".date_i18n(get_option('time_format'), strtotime($event->event_time)); }
					if ($event->event_endtime != "00:00:00" && $event->event_endtime != '' ) {
						echo "<span class='time-separator'>&thinsp;&ndash;&thinsp;</span><span class='event-time' title='".$event->event_end.'T'.$event->event_endtime."'>".date_i18n(get_option('time_format'), strtotime($event->event_endtime))."</span>";
					}
				?>
			</div>
			<div style="float: left;">
				<h4>Where</h4>
				<?php echo $address; ?>
			</div>
			<br class="clearer" />
			<h4>Details</h4>
			<div class="event-desc">
				<?php echo $event->event_desc; ?>
				<br /><?php if ($event->event_link != 0 && !empty($event->event_link)) { ?>
						<a href="<?php echo $event->event_link;?>">Event Website</a>
					<?php } ?>
			</div>
		</td>
	</tr>
</table>
<?php } ?>
</div>

<?php get_footer(); ?>

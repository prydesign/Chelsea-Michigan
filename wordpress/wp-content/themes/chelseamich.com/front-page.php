	<?php
	// Template Name: Front Page
	
	?>
	<?php get_header(); ?>
	<div id="home-splash">
		<img src="/wp-content/themes/chelseamich.com/images/01.jpg" />
		<img src="/wp-content/themes/chelseamich.com/images/02.jpg" />
		<img src="/wp-content/themes/chelseamich.com/images/03.jpg" />
		<img src="/wp-content/themes/chelseamich.com/images/04.jpg" />
		<img src="/wp-content/themes/chelseamich.com/images/05.jpg" />
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#home-splash').cycle({
				fx: 'fade',
				speed: 2000,
				timeout: 8000
			})
		});
	</script>
	<div id="home-ticker">
		<div id="ticker-fade">
			<?php insert_newsticker(); ?>
		</div>
	</div>
	<div id="home-ad">
		<!-- Homepage -->
		<script type='text/javascript'>
		GA_googleFillSlot("Homepage");
		</script>
		<!-- <span>Sponsored Link: The Common Grill</span> -->
	</div>
	<p id="home-blurb">
		<span>This is Chelsea...</span> Where you can come for an hour to stroll the streets and shop the shops. Or come for 
		a day to take in a play, explore the SculptureWalk, and grab a burger. Or even come for a lifetime and be part of this thriving community. However long you stay, we promise that you'll find Chelsea to be something else.
	</p>
	<br class="clearer" />
	
	<div class="home-box" id="visit-box">
		<p>Not sure what to do? Just browse and grab a Chelsea VisiTic... They're handy one-sheet guides that'll take you where you'd like to go. </p>
		<span>I would like to stay for:</span>
		<a href="/visit#hour" title="An Hour"><img src="/wp-content/themes/chelseamich.com/images/home-hour-btn.png"></a>
		<a href="/visit#day" title="A Day"><img src="/wp-content/themes/chelseamich.com/images/home-day-btn.png"></a>
		<a href="/visit#life" title="A Lifetime"><img src="/wp-content/themes/chelseamich.com/images/home-lifetime-btn.png"></a>
	</div>
	
	<div class="home-box" id="calendar-box">
		
		<?php $recent_events = get_recent_events(); ?>
		<?php // print_r($recent_events); ?>
		
		<?php foreach($recent_events as $event) { ?>
			<?php 
				$begin_date = strtotime($event->event_begin); 
				$even_odd = ( 'even' != $even_odd ) ? 'even' : '';
			?>
			<div class="home-cal-event <?php echo $even_odd; ?>">
				<span><?php echo strftime('%b. %e', $begin_date); ?> : <a href="/event?eid=<?php echo $event->event_id ?>"><?php $text = $event->event_title; $text=stripslashes($text); echo truncate($text, 30); ?></a></span>
				<?php $text = $event->event_short; $text=stripslashes($text); echo truncate($text, 60); ?>
			</div>

		<?php } ?>	
	</div>
	
	<div class="home-box" id="directory-box" style="margin-right: 0">
		<?php $listing = get_random_listing(); ?>
		<?php wp_reset_query(); wp_reset_postdata();?>
		
		<?php foreach ($listing as $item) { setup_postdata($item); ?>
			<?php $listing_thumb = get_post_meta($item->ID, 'image', true); ?>
			<img id="home-biz-thumb-end" src="/wp-content/themes/chelseamich.com/images/home-chelsea-biz-thumb-end.png">
		
			<div id="home-biz-thumb">
				<a href="<?php echo $item->guid; ?>" title="<?php echo $item->post_title; ?>"><img width="106" height="106" src="/wp-content/uploads/wpbdm/thumbnails/<? echo $listing_thumb; ?>" alt="<?php echo $item->post_title; ?>"/></a>
			</div>	
		
				<div id="home-biz-description">
					<h3><?php echo $item->post_title; ?></h3>
					<p><?php $text = get_the_excerpt(); echo truncate($text, 120); ?></p>

					<a href="<?php echo $item->guid; ?>">+ LEARN MORE</a>
				</div>
			
			<br class="clearer" />
			<div id="home-biz-bottom">
				<div>
					<strong>HOURS</strong><br />
					<?php $hours = get_post_meta($item->ID, 'Hours of Operation', true); $hours = str_replace('/n', ' ', $hours); echo $hours; ?>
				</div>
				<div>
					<strong>CONTACT</strong><br />
					Tel: <?php echo get_post_meta($item->ID, 'Business Phone Number', true); ?><br />
				</div>
				<br class="clearer" />
			</div>
		<?php } ?>
	</div>
	
	<br class="clearer" />
	<?php get_footer(); ?>
<?php get_header(); ?>


	<?php if (have_posts()) : ?>	
	
	<?php while (have_posts()) : the_post(); ?>
	
	<div class="wpbdmentry">	
	<?php // wpbusdirman_menu_buttons will print the menu buttons Submit Listing and Directory?>
		
		<div class="wpbdmsingleimages">
		<?php // wpbusdirman_display_main_image grabs the first image from the array of images associated with the listing and sets it as the main image. Must be used inside the loop ?>
		<?php wpbusdirman_display_main_image();?>
		<?php // wpbusdirman_display_extra_thumbnails will loop through and display any additional images associated with the listing. Must be used inside the loop ?>
		<?php wpbusdirman_display_extra_thumbnails();?>
		</div><!--end div wpbdmsingleimages -->
		
		<div class="wpbdmsingledetails">		
		<?php wpbusdirman_menu_buttons();?>	
		<?php wpbusdirman_single_listing_details();?>
		</div><!-- close div wpbdmsingledetails-->
		
			<div style="clear:both;"></div>

				<p class="postmetadata">

			<?php _e("This listing was submitted","WPBDM"); ?>
			<?php /* This is commented, because it requires a little adjusting sometimes.
				You'll need to download this plugin, and follow the instructions:
				http://binarybonsai.com/wordpress/time-since/ */
				/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
			<?php _e("on","WPBDM");?> <?php the_time('l, F jS, Y') ?> <?php _e("at","WPBDM");?> <?php the_time() ?>
			</p>
			<p>
			<?php _e("You can follow any responses to this listing through the","WPBDM");?> <?php post_comments_feed_link('RSS 2.0'); ?> <?php _e("feed","WPBDM");?>.

			<?php if ( comments_open() && pings_open() ) {
				// Both Comments and Pings are open ?>
				<?php _e("You can","WPBDM");?> <a href="#respond"><?php _e("leave a response","WPBDM");?></a>, <?php _e("or","WPBDM");?> <a href="<?php trackback_url(); ?>" rel="trackback"><?php _e("trackback","WPBDM");?></a> <?php _e("from your own site","WPBDM");?>.

			<?php } elseif ( !comments_open() && pings_open() ) {
				// Only Pings are Open ?>
				<?php _e("Responses are currently closed, but you can","WPBDM");?> <a href="<?php trackback_url(); ?> " rel="trackback"><?php _e("trackback","WPBDM");?></a> <?php _e("from your own site","WPBDM");?>.

			<?php } elseif ( comments_open() && !pings_open() ) {
				// Comments are open, Pings are not ?>
				<?php _e("You can skip to the end and leave a response. Pinging is currently not allowed","WPBDM");?>.

			<?php } elseif ( !comments_open() && !pings_open() ) {
				// Neither Comments, nor Pings are open ?>
				<?php _e("Both comments and pings are currently closed","WPBDM");?>.

			<?php } ?>
			</p>

			<?php global $wpbusdirmanconfigoptionsprefix;
				$wpbusdirman_config_options=get_wpbusdirman_config_options();
				if($wpbusdirman_config_options[$wpbusdirmanconfigoptionsprefix.'_settings_config_36'] == "yes")
				{?>
			<p><?php comments_template(); ?>
			<?php } ?>
	

		
<div style="clear:both;"></div>
	</div><!--close div wpbdmentry-->
	
	<?php endwhile;?>
	
	<?php else: ?>

	<p>Sorry, no posts matched your criteria.</p>
	<!--end wpbusdirmantemplate-->
	<?php endif;?>
	<div style="clear:both;"></div>	

<?php get_footer(); ?>

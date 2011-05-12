<?php get_header(); ?>

<script src="/wp-content/themes/chelseamich.com/galleria/galleria-1.2.2.min.js"></script>


	<?php if (have_posts()) : ?>	
	
	<?php while (have_posts()) : the_post(); ?>
	<div id="side-bar">
		<a href="/directory" id="back" title="Back"><img src="/wp-content/themes/chelseamich.com/images/back-to-list.png" alt="Back to the Directory"/></a>
		<h3>Contact</h3>
		<div class="address">
			<?php
			$street = get_post_meta($post->ID, 'Street Address', true);
			$street2 = get_post_meta($post->ID, 'Street Address (2)', true);
			$city = get_post_meta($post->ID, 'City', true);
			$zip = get_post_meta($post->ID, 'Zipcode', true);
			?>
			<?php echo the_title().'<br />'.$street.' '.$street2.'<br />'.$city.', Mi'.$zipcode.'<br />'; buildGoogleLink($street, $street2, $city, $zip); ?>
		</div>
			<h3>Telephone</h3>
			<div class="phone">
				<?php echo get_post_meta($post->ID, 'Business Phone Number', true); ?>
			</div>

		<?php if(get_post_meta($post->ID, 'Business Website Address', true)) {echo '<h3>Website</h3>';echo '<div class="website"><a href="'.get_post_meta($post->ID, 'Business Website Address', true).'">'.get_post_meta($post->ID, 'Business Website Address', true).'</a></div>';}?>
		
		<h3>Hours</h3>
		<div class="hours">
			<?php $hours = get_post_meta($post->ID, 'Hours of Operation', true); $hours = str_replace('/n', ' ', $hours); echo $hours; ?>
		</div>
	</div>
	
	
	<div id="text">	
		<h1 class="listing-title"><?php the_title(); ?></h1>
		<?php the_excerpt(); ?>
	<?php // wpbusdirman_menu_buttons will print the menu buttons Submit Listing and Directory?>
		
		<div id="galleria">
		<?php // wpbusdirman_display_main_image grabs the first image from the array of images associated with the listing and sets it as the main image. Must be used inside the loop ?>
		<?php wpbusdirman_display_main_image();?>
		<?php // wpbusdirman_display_extra_thumbnails will loop through and display any additional images associated with the listing. Must be used inside the loop ?>
		<?php wpbusdirman_display_extra_thumbnails();?>
		</div><!--end div wpbdmsingleimages -->
		
		<div id="listing-content">
			<?php the_content(); ?>
		</div>
		
			<div style="clear:both;"></div>

				<p class="postmetadata">

			<?php _e("This listing was submitted","WPBDM"); ?>
			<?php /* This is commented, because it requires a little adjusting sometimes.
				You'll need to download this plugin, and follow the instructions:
				http://binarybonsai.com/wordpress/time-since/ */
				/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
			<?php _e("on","WPBDM");?> <?php the_time('l, F jS, Y') ?> <?php _e("at","WPBDM");?> <?php the_time() ?>
			</p>

		
<div style="clear:both;"></div>
	</div><!--close div wpbdmentry-->
	
	<?php endwhile;?>
	
	<?php else: ?>

	<p>Sorry, no posts matched your criteria.</p>
	<!--end wpbusdirmantemplate-->
	<?php endif;?>
	<div style="clear:both;"></div>	
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			// Load the classic theme
			Galleria.loadTheme('/wp-content/themes/chelseamich.com/galleria/themes/classic/galleria.classic.min.js');

			// Initialize Galleria
			$('#galleria').galleria({
              width: 565,
              height: 420,
			 	  imageCrop: 'width'
          });	
		});
	</script>
<?php get_footer(); ?>

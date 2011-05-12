<?php get_header(); ?>

<div id="directory-sub-header">
	<div id="directory-ad">
		<!-- Top_Banner -->
		<script type='text/javascript'>
		GA_googleFillSlot("Top_Banner");
		</script>
	</div>
	<a id="newsletter-signup" href="#"><img src="/wp-content/themes/chelseamich.com/images/directory-what-else.png" /></a>
</div>

<div id="side-bar">
	<ul>
		<li <?php if(is_page('Directory')) {echo 'class="current_page_item"';} ?>>
			<a href="/directory">View All</a>
		</li>
	<?php wpbusdirman_list_categories(); ?></ul>
</div>

<div id="text" class="cat-view">
	
		<?php // will print the title of the category page 
		global $premier_listings, $enhanced_listings, $standard_listings; ?>
		<?php wpbusdirman_catpage_title();?>		

			<?php // function wpbusdirman_catpage_query sets up the query. Must use before the loop. Necessary for retrieving the correct category posts ?>
			<?php wpbusdirman_catpage_query();?>
			<!-- List premier listings -->
			<?php if ($premier_listings): ?>
			<div class="directory-listing-bar premier"> </div>
		 	<?php endif; ?>
			<?php foreach($premier_listings as $post) : setup_postdata($post); ?>
				<div class="directory-listing premier">
					<div class="listing-pic">
						<?php $listing_thumb = get_post_meta($post->ID, 'image', true); if(!empty($listing_thumb)) {  ?>
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="/wp-content/uploads/wpbdm/thumbnails/<? echo $listing_thumb; ?>" alt="<?php the_title(); ?>"/></a>
						<?php } else { ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="/wp-content/themes/chelseamich.com/images/placeholder.png" alt="<?php the_title(); ?>"/></a>
						<?php } ?>
					</div>	
					<div class="listing-info">
						<h2><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
						<p><?php $text = get_the_excerpt(); echo truncate($text, 250); ?>  <a class="more-link" href="<?php the_permalink() ?>">More</a></p>
						<div class="hours">
							Hours: <?php $hours = get_post_meta($post->ID, 'Hours of Operation', true); $hours = str_replace('/n', ' ', $hours); echo $hours; ?>
						</div>
						<div class="address">
							<?php
							$street = get_post_meta($post->ID, 'Street Address', true);
							$street2 = get_post_meta($post->ID, 'Street Address (2)', true);
							$city = get_post_meta($post->ID, 'City', true);
							$zip = get_post_meta($post->ID, 'Zipcode', true);
							?>
							<?php echo $street.' '.$street2.' '.$city.', Mi'.$zipcode; buildGoogleLink($street, $street2, $city, $zip); ?>
							<?php echo '&nbsp; &nbsp; | &nbsp; &nbsp; Tel: '; echo get_post_meta($post->ID, 'Business Phone Number', true); ?>
							<?php if(get_post_meta($post->ID, 'Business Website Address', true)) {echo ' &nbsp; &nbsp; | &nbsp; &nbsp; ';echo '<a href="http://'.get_post_meta($post->ID, 'Business Website Address', true).'">'.get_post_meta($post->ID, 'Business Website Address', true).'</a>';}?>
						</div>
					</div>
					<br class="clearer" />
				</div>
			<?php endforeach; ?>
				<br class="clearer" />

			<!-- List Enhanced Listings -->
			<?php
			// Rocking some alternating styles
			$style_classes = array('first','second');
			$style_index = 0;
			?>
			<?php if ($enhanced_listings): ?>
				<div class="directory-listing-bar enhanced"> </div>
			<?php endif; ?>
			<?php foreach($enhanced_listings as $post) : setup_postdata($post); ?>
				<div class="directory-listing enhanced <?php $this_style = $style_index%2; echo "$style_classes[$this_style]"; $style_index++; ?>">
					<div class="listing-pic">
						<?php $listing_thumb = get_post_meta($post->ID, 'image', true); if(!empty($listing_thumb)) {  ?>
						<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="/wp-content/uploads/wpbdm/thumbnails/<? echo $listing_thumb; ?>" alt="<?php the_title(); ?>"/></a>
						<?php } else { ?>
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><img src="/wp-content/themes/chelseamich.com/images/placeholder.png" alt="<?php the_title(); ?>"/></a>
						<?php } ?>
					</div>
					<div class="listing-info">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<p><?php $text = get_the_excerpt(); echo truncate($text, 250); ?></p>
							<div class="hours">
								Hours: <?php $hours = get_post_meta($post->ID, 'Hours of Operation', true); $hours = str_replace('/n', ' ', $hours); echo $hours; ?>
							</div>
							<div class="address">
								<?php
								$street = get_post_meta($post->ID, 'Street Address', true);
								$street2 = get_post_meta($post->ID, 'Street Address (2)', true);
								$city = get_post_meta($post->ID, 'City', true);
								$zip = get_post_meta($post->ID, 'Zipcode', true);
								?>
								<?php echo $street.' '.$street2.' '.$city.', Mi'.$zipcode; buildGoogleLink($street, $street2, $city, $zip); ?>
								<br />
								<?php echo 'Tel: '; echo get_post_meta($post->ID, 'Business Phone Number', true); ?>
								<br />
								<?php if(get_post_meta($post->ID, 'Business Website Address', true)) {echo '<a href="http://'.get_post_meta($post->ID, 'Business Website Address', true).'">'.get_post_meta($post->ID, 'Business Website Address', true).'</a>';}?>
							</div>
					</div>
					<br class="clearer" />
				</div>
			<?php endforeach; ?>
			<br class="clearer" />

			<!-- List the poor folks -->
			<?php
			// Rocking some alternating styles
			$style_classes = array('first','second', 'third');
			$style_index = 0;
			?>
			<?php if ($standard_listings): ?>
				<div class="directory-listing-bar standard"> </div>	
			<?php endif; ?>	
			<?php foreach($standard_listings as $post) : setup_postdata($post); ?>
				<div class="directory-listing standard <?php $this_style = $style_index%3; echo "$style_classes[$this_style]"; $style_index++; ?>">
					<div class="listing-info">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<div class="address">
							<?php
							$street = get_post_meta($post->ID, 'Street Address', true);
							$street2 = get_post_meta($post->ID, 'Street Address (2)', true);
							$city = get_post_meta($post->ID, 'City', true);
							$zip = get_post_meta($post->ID, 'Zipcode', true);
							?>
							<?php echo $street.' '.$street2.' '.$city.', Mi'.$zipcode; buildGoogleLink($street, $street2, $city, $zip); ?>
							<br />
							<?php echo 'Tel: '; echo get_post_meta($post->ID, 'Business Phone Number', true); ?>
							<br />
							<?php if(get_post_meta($post->ID, 'Business Website Address', true)) {echo '<a href="http://'.get_post_meta($post->ID, 'Business Website Address', true).'">'.get_post_meta($post->ID, 'Business Website Address', true).'</a>';}?>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
				<br class="clearer" />
			

						<div class="navigation">
						<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }else {?>
							<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
							<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
						<?php } ?>
					</div>

</div>

<?php get_footer();?>

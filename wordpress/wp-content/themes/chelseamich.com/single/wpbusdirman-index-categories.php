<?php get_header(); ?>

<div id="directory-sub-header">
	<div id="directory-ad">
		<a href="#"><img src="/wp-content/themes/chelseamich.com/images/directory-ad.png" /></a>
	</div>
	<a id="newsletter-signup" href="#"><img src="/wp-content/themes/chelseamich.com/images/directory-what-else.png" /></a>
</div>

<div id="side-bar">
	<ul><?php wpbusdirman_list_categories(); ?></ul>
</div>

<div id="text">
	

			<div class="wpbdmentry">

		<?php // will print the title of the category page ?>
		<?php wpbusdirman_catpage_title();?>
		<?php // wpbusdirman_menu_buttons will print the menu buttons Submit Listing and Directory?>
		<?php wpbusdirman_menu_buttons();?>				

			<?php // function wpbusdirman_catpage_query sets up the query. Must use before the loop. Necessary for retrieving the correct category posts ?>
			<?php wpbusdirman_catpage_query();?>
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post();?>

			<?php // wpbusdirman_display_excerpt will display the relevant post excerpts?>
			<?php wpbusdirman_display_excerpt();?>

			<?php endwhile;?>
					<div class="navigation">
						<?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); }else {?>
							<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
							<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
						<?php } ?>
					</div>

				<?php else:

				 _e("No listings found in category","WPBDM");

				endif;?>
	</div>

</div>

<?php get_footer();?>

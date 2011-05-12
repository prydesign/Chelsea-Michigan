<?php get_header();?>


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
<?php get_footer(); ?>

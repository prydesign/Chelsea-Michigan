<div class="wpbdmentry">
<?php // wpbusdirman_menu_buttons will print the menu buttons Submit Listing and Directory?>
	<?php // wpbusdirman_indexpage_query sets up the query. Must use before the loop. Necessary for retrieving the correct posts ?>
	<?php wpbusdirman_indexpage_query();?>

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
							
				<?php else: ?>
				<p><?php _e("There were no listings found in the directory","WPBDM"); ?></p>
				
				<?php endif;?>
				<?php wp_reset_query();?>
</div>
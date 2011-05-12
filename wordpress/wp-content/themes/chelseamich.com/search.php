<?php get_header(); ?>
<?php 
if( is_search() )  :
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	query_posts("s=$s&paged=$paged&post_type=post");
endif; 
?>
<div id="side-bar" class="blog">
	<ul>
	<?php
		/* When we call the dynamic_sidebar() function, it'll spit out
		 * the widgets for that widget area. If it instead returns false,
		 * then the sidebar simply doesn't exist, so we'll hard-code in
		 * some default sidebar stuff just in case.
		 */
		if ( ! dynamic_sidebar( 'Blog Sidebar' ) ) : ?>
		
		
		<?php endif; // end primary widget area ?>
		</ul>
</div>

<div id="text" class="blog">
	<?php if ( have_posts() ) : ?>
					<h1 class="cat-page-title"><?php printf( __( 'Search Results for: "%s"', 'twentyten' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
						<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
							<div class="post">
								<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
								<div class="post-meta">
									<small>Posted on <?php the_time('l, F jS, Y') ?> by <?php the_author(); ?>  |  Filed under <?php the_category(', ') ?></small>
								</div>
							</div>
						<?php endwhile; // end of the loop. ?>
	<?php else : ?>
					<div id="post-0" class="post no-results not-found">
						<h2 class="entry-title"><?php _e( 'Nothing Found', 'twentyten' ); ?></h2>
							<p><?php _e( 'Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'twentyten' ); ?></p>
					</div><!-- #post-0 -->
	<?php endif; ?>
</div>

<?php get_footer(); ?>

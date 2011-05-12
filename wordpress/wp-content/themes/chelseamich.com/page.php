<?php get_header(); ?>

<div id="side-bar">
	<div class="sidebar-ad">
	<!-- Sidebar -->
	<script type='text/javascript'>
	GA_googleFillSlot("Sidebar");
	</script>
	<?php if (is_page('Residents')) { ?>
	<?php
		/* When we call the dynamic_sidebar() function, it'll spit out
		 * the widgets for that widget area. If it instead returns false,
		 * then the sidebar simply doesn't exist, so we'll hard-code in
		 * some default sidebar stuff just in case.
		 */
		
		if ( ! dynamic_sidebar( 'RSS Sidebar' )) : ?>
		
		
		<?php endif; // end primary widget area ?>
		<?php } ?>
	</div>
	<div id="side-bar">
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/pages/Chelsea-Michigan/156736827715825" width="250" show_faces="true" stream="false" header="false"></fb:like-box>
	</div>
</div>


<div id="text">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php the_content(); ?>


	<?php endwhile; // end of the loop. ?>
	
	<?php if (is_page('Residents')) { ?>
		
		<h2 class="residents-title">Residential News &amp; Views</h2>
		
	  <?php query_posts('category_name=Local&posts_per_page=10'); ?>

	  <?php while (have_posts()) : the_post(); ?>
			<div class="post">
				<h2 class="entry-title residents"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
				<div class="post-meta">
					<small>Posted on <?php the_time('l, F jS, Y') ?> by <?php the_author(); ?>  |  Filed under <?php the_category(', ') ?></small>
				</div>
			</div>
	  <?php endwhile;?>
	<?php } ?>	
	
</div>

<?php get_footer(); ?>

<?php get_header(); ?>

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
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/pages/Chelsea-Michigan/156736827715825" width="250" show_faces="true" stream="false" header="false"></fb:like-box>
</div>

<div id="text" class="blog">
	<h1 class="cat-page-title"><?php
		printf( __( 'Viewing category archives for: %s', 'twentyten' ), '<span>' . single_cat_title( '', false ) . '</span>' );
	?></h1>
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div class="post">
			<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h2>
			<?php the_excerpt(); ?>
			<div class="post-meta">
				<small>Posted on <?php the_time('l, F jS, Y') ?> by <?php the_author(); ?>  |  Filed under <?php the_category(', ') ?></small>
			</div>
		</div>
	<?php endwhile; // end of the loop. ?>
</div>

<?php get_footer(); ?>

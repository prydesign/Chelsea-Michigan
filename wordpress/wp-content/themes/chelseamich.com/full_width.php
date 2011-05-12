<?php

//  Template Name: Full Width
?>

<?php get_header(); ?>

<div id="text" style="width: 922px;">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; // end of the loop. ?>
</div>

<?php get_footer(); ?>

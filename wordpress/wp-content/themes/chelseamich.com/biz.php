<?php
/**  Template Name: Biz
 */

get_header(); ?>
<a href="/"><img src="/wp-content/themes/chelsea/images/header.png" /></a>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		<div class="entry-content">
			<?php the_content(); ?>
		</div>
<?php endwhile; ?>

<?php get_footer(); ?>

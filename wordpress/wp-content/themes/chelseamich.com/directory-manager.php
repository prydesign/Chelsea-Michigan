<?php 

// Template Name: Directory Management

get_header(); ?>

<div id="side-bar">
	<ul>
		<li <?php if(is_page('Directory')) {echo 'class="current_page_item"';} ?>>
			<a href="/directory">View All</a>
		</li>
	<?php wpbusdirman_list_categories(); ?></ul>
</div>

<div id="text">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

		<h1 class="entry-title"><?php the_title(); ?></h1>
		<?php the_content(); ?>


	<?php endwhile; // end of the loop. ?>
</div>

<?php get_footer(); ?>

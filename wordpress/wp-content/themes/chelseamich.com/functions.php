<?php
add_theme_support( 'post-thumbnails' ); 

function new_excerpt_length($length) {
	return 50;
}
add_filter('excerpt_length', 'new_excerpt_length');


function subpage_header() {
	$num = rand(1, 68);
	$output = '/wp-content/themes/chelseamich.com/images/subpage-headers/'.$num.'.jpg';
	return $output;
}


function buildGoogleLink($street, $street2, $city, $zip) {
	$map_string = $street.' '.$street2.' '.$city.'+MI'.$zip.'+US';	
	$map_string = str_replace(" ","+",$map_string);
	$map = " &nbsp; <a href=\"http://maps.google.com/maps?f=q&z=15&q=$map_string\">Map</a>";
	echo $map;
}


function truncate ($str, $length=10, $trailing='...') {
   // take off chars for the trailing
   $length-=mb_strlen($trailing);
   if (mb_strlen($str)> $length)
   {
      // string exceeded length, truncate and add trailing dots
      return mb_substr($str,0,$length).$trailing;
   }
   else
   {
      // string was already short enough, return the string
      $res = $str;
   }
   return $res;
}

register_sidebar( array(
	'name' => 'Blog Sidebar',
	'id' => 'sidebar-widgets',
	'description' => __( 'The sidebar widget area on the blog', 'twentyten' ),
	'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
	'after_widget' => '</li>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
) );

register_sidebar( array(
	'name' => 'RSS Sidebar',
	'id' => 'sidebar-rss',
	'description' => __( 'The sidebar widget area on the residents page', 'twentyten' ),
	'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
	'after_widget' => '</li>',
	'before_title' => '<h3>',
	'after_title' => '</h3>',
) );

// Functions for the homepage displays

function get_random_listing() {

	//get one random post from category 14, display post title, and excerpt.
	//display WritersName custom field if it exists.
	$args=array(
	  'orderby' => rand,
	  'post_type' => 'wpbdm-directory',
	  'post_status' => 'publish',
	  'posts_per_page' => 1,
	  'count'=> 1,
	  'meta_key' => 'listing_package',
	  'meta_value'=> '1'
	);
	$my_query = null;
	$my_query = query_posts($args);
	return $my_query;
	
}

function get_recent_events() {
	global $wpdb;
	$today = date('Y-m-d');
	// echo $today;
	$sql = "SELECT * FROM wp_my_calendar WHERE event_begin <= '$today' AND event_end >= '$today' LIMIT 4";
	$recent_events = $wpdb->get_results($sql);
	return $recent_events;
}

add_filter('query_vars', 'parameter_queryvars' );

function parameter_queryvars( $qvars ) {
	$qvars[] = 'eid';
	return $qvars;
}

function event_view_id() {
	global $wp_query;
	
	if (isset($wp_query->query_vars['eid'])) {
	return $wp_query->query_vars['eid'];
	}
}

function chelsea_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
			<table cellpadding="0" cellspacing="0">
				<tr>
					<td class="comment-avatar" valign="top">
						<?php echo get_avatar( $comment, 40 ); ?>
					</td>
					<td valign="top">
						<div class="comment-author vcard">
							<?php printf( __( '%s <span class="says">says:</span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
						</div><!-- .comment-author .vcard -->
						<?php if ( $comment->comment_approved == '0' ) : ?>
							<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?></em>
							<br />
						<?php endif; ?>

						<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<?php
								/* translators: 1: date, 2: time */
								printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );
							?>
						</div><!-- .comment-meta .commentmetadata -->

						<div class="comment-body"><?php comment_text(); ?></div>

						<div class="reply">
							<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
						</div><!-- .reply -->
					</td>
				</tr>
			</table>
			

	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}

?>
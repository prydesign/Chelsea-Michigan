<!DOCTYPE html>
<html>
	<head>
		<link rel="profile" href="http://gmpg.org/xfn/11" >
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" >
		<meta charset="<?php bloginfo( 'charset' ); ?>" >
		
		<title></title>
<link rel="stylesheet" type="text/css" media="all" href="/wp-content/themes/chelseamich.com/style.css" >
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>

		
		<?php wp_head(); ?>
				<script type="text/javascript" src="/wp-content/themes/chelseamich.com/master.js"></script>
		<script type="text/javascript" src="/wp-content/themes/chelseamich.com/jquery.slider.js"></script>
		
		<script type='text/javascript' src='http://partner.googleadservices.com/gampad/google_service.js'>
		</script>
		<script type='text/javascript'>
		GS_googleAddAdSenseService("ca-pub-4986827791162887");
		GS_googleEnableAllServices();
		</script>
		<script type='text/javascript'>
		GA_googleAddSlot("ca-pub-4986827791162887", "Homepage");
		GA_googleAddSlot("ca-pub-4986827791162887", "Sidebar");
		GA_googleAddSlot("ca-pub-4986827791162887", "Top_Banner");
		</script>
		<script type='text/javascript'>
		GA_googleFetchAds();
		</script>
		
	</head>
	<body <?php body_class(); ?>>
		<div id="wrapper">
			<div id="header">
				<?php if(is_front_page()) { ?>
					<h1 id="logo"><img src="/wp-content/themes/chelseamich.com/images/logo.png" alt="Chelsea Michigan" /></h1>
				<?php } else { ?>
					<a href="/" title="Return to Home" id="logo"><img src="/wp-content/themes/chelseamich.com/images/logo.png" alt="Chelsea Michigan" /></a>
				<?php }; ?>
				
				<ul id="main-nav">
					<li><a href="/visit" class="visit-link main <?php if(is_page('VisiTics')){ echo 'active'; } ?>" title="Visit Chelsea">Visit</a></li>
					<img class="divider" src="/wp-content/themes/chelseamich.com/images/nav-divider.png" />
					<li><a href="/calendar" class="calendar-link main <?php if(is_page('Community Calendar')){ echo 'active'; } ?>" title="View the Chelsea Community Calendar">Calendar</a></li>
					<img class="divider" src="/wp-content/themes/chelseamich.com/images/nav-divider.png" />
					<li><a href="/directory" class="directory-link main <?php if(is_page('Directory')){ echo 'active'; } ?>" title="Browse Chelsea Businesses">Directory</a></li>
					<img class="divider" src="/wp-content/themes/chelseamich.com/images/nav-divider.png" />
					<?php wp_nav_menu(array('menu' => 'main', 'items_wrap' => '%3$s', 'container'=> false)); ?>
					<li id="nav-end"><img src="/wp-content/themes/chelseamich.com/images/nav-right.png" /></li>
					<br class="clearer" />
				</ul>	
				<br class="clearer" />
				<?php if(!is_front_page()) { ?>
					<div id="header-image">
						<?php if (has_post_thumbnail( $post->ID )) {echo get_the_post_thumbnail( $post->ID, 'post-thumbnail' );} else {?>
							<img class="side-banner" src="<?php echo subpage_header(); ?>" alt="Chelsea" />
						<?php }; ?>
					</div>
				<?php }; ?>
			</div>	
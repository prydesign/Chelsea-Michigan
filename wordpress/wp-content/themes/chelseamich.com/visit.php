<?php
// Template Name: VisiTics Page
?>
<?php get_header(); ?>

<div id="side-bar">
	<div class="sidebar-ad">
	<!-- Sidebar -->
	<script type='text/javascript'>
	GA_googleFillSlot("Sidebar");
	</script>
	</div>
	
	<div class="sidebar-ad">
		<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="http://www.facebook.com/pages/Chelsea-Michigan/156736827715825" width="250" show_faces="true" stream="false" header="false"></fb:like-box>
	</div>
</div>

<link rel="stylesheet" href="/wp-content/themes/chelseamich.com/slider-style/slider.css" />
<script type="text/javascript">
	jQuery(document).ready(function($) {	
		// Activate ze sliders
		$('#length-slider .slider').slider({max: 2, step: 1, animate: true});
		$('#group-slider .slider').slider({max: 4, step: 1, animate: true});
		$('#activity-slider .slider').slider({max: 2, step: 1, animate: true});
	});
</script>

<div id="text">
	<img class="visitic-title" src="/wp-content/themes/chelseamich.com/images/visitic-page-title.png" />
	<p>Adjust the below sliders to reveal your perfect Chelsea visit.</p>
	<div id="sliders">
		
		<div id="length-slider"><div class="slider"></div></div>
		
		<div id="group-slider"><div class="slider"></div></div>
		
		<div id="activity-slider"><div class="slider"></div></div>
		
	</div>
	<div id="visitic-bar"><a id="view-all-link" href="#view-all">View All</a></div>
	
	<div id="visitic-results">
		<!-- Visitic filtered results go here -->
	</div>
		
</div>

<?php get_footer();?>
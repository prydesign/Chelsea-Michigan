<?php

// Template Name: Calendar

 get_header(); ?>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$( "#calendar-tabs" ).tabs({
			load: function(event, ui) {
			        $('.tab-nav a', ui.panel).live('click', function() {
			            $(ui.panel).load(this.href);
			            return false;
			        });
			}
		});
		
		$('#prevMonth').live('click', function() {
			$('.ui-tabs-panel').load(this.href);
			return false;
		});
		
		$('#nextMonth').live('click', function() {
			$('.ui-tabs-panel').load(this.href);
			return false;
		});
		
	});
</script>
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

<div id="text">
	<div id="calendar-tabs">
		<ul class="tab-nav">
			<li><a href="/calendar/all">View All</a></li>
			<li><a href="/calendar/visitors">Visitors</a></li>
			<li><a href="/calendar/residents">Residents</a></li>
			<br class="clearer" />
		</ul>
		<br class="clearer" />
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

			<?php the_content(); ?>

		<?php endwhile; // end of the loop. ?>
		
		<div id="calendar-bar"><a name="day-display"></a></div>
		<div id="calendar-day">
			
		</div>
	</div>
</div>

<?php get_footer(); ?>

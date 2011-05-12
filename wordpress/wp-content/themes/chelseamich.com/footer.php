<br class="clearer" />
			<div id="footer">
				<div id="social">
					<a href="http://www.facebook.com/pages/Chelsea-Michigan/156736827715825" title="Chelsea on Facebook"><img src="/wp-content/themes/chelseamich.com/images/footer-fb.jpg" alt="Facebook" /></a>
					<a href="http://www.twitter.com/ExploreChelsea" title="Chelsea on Twitter"><img src="/wp-content/themes/chelseamich.com/images/footer-twitter.jpg" alt="Twitter" /></a>
					<a href="http://www.youtube.com/explorechelsea" title="Chelsea on YouTube"><img src="/wp-content/themes/chelseamich.com/images/footer-youtube.jpg" alt="YouTube" /></a>
					<a href="http://www.flickr.com/photos/62531847@N03/" title="Chelsea on Flickr"><img src="/wp-content/themes/chelseamich.com/images/footer-flickr.jpg" alt="Flickr" /></a>
					<a href="/feed" title="Chelsea on RSS"><img src="/wp-content/themes/chelseamich.com/images/footer-rss.jpg" alt="RSS" /></a>
					<!-- <a href="#linkedin" title="Chelsea on LinkedIn"><img src="/wp-content/themes/chelseamich.com/images/footer-linkedin.jpg" alt="LinkedIn" /></a> -->
				</div>
				<ul id="footer-nav">
						<?php wp_nav_menu(array('menu' => 'footer', 'items_wrap' => '%3$s', 'container'=> false)); ?>
				</ul>
				<br class="clearer" />
				<div id="newsletter">
				</div>
				<div id="copy">
					&copy;<?php echo date('Y'); ?> The City of Chelsea. All rights reserved. 
				</div>
			</div>
		</div> <!-- closing up the #wrapper -->
	<?php wp_footer(); ?>
	<script>
		jQuery(document).ready(function($) {
			
			if($('#rss-reader-1')) {
				
				$('#rss-reader-1 ul li:even').addClass('even');
				
			}
		});
	</script>
	
	</body>
</html>
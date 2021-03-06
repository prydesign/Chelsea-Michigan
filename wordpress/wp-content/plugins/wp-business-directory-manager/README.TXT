=== WP BUSINESS DIRECTORY MANAGER ===
Contributors: A. Lewis
Tags: business directory,ads,classifieds
Requires at least: 3.0
Tested up to: 3.0.1
Stable tag: trunk

WP Business Directory Manager is a flexible plugin designed for business directories but useful for a variety of other purposes.  

== Description ==

WP Business Directory Manager is designed to be used for managing a business directory listing service on your WordPress powered website. All  form fields are editable so you can collect and display any kind of data from business information for a business directory, to personal information for a dating profile.


For more information and support please visit http://themestown.com


== Credits ==

Copyright 2009 A. Lewis 


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA



== Installation ==

Backup your WordPress database and files before you install or update. If you are upgrading from any of versions 1.0 - 1.7 it is important to note that version 1.8 is built for Wordpress 3.0 and above and will not work properly with versions of WordPress prior to 3.0. It is also important to note that while version 1.8 will attempt to convert your current listings over to the new 3.0 format, it is possible for the conversion to go awry in some areas so a clean installation and manual transfer of data is recommended.

1. 	Upload the folder wp-buisness-directory-manager with its contents to wp-content/plugins/

2.   	Activate the plugin

3.	Create a new WordPress Page for your business directory. You can name the page whatever you want. Insert the following code into the page [WPBUSDIRMANUI]. The plugin needs this code to work.

4.      Create another page for your google checkout processor. Again you can name it anything you want. It must be created as a child page under your the business directory page you created in step 3. Insert [WPBUSDIRMANGOOGLECHECKOUT] as the page content. (Include the brackets) 

5. 	If you want to include a "Submit Listings" page, create a child page under the parent page you've created. Name the page whatever you want. Insert the following code into the page [WPBUSDIRMANADDLISTING]. The "Submit Listings" page needs this code to work.

6. 	If you want to include a "Manage Listings" page, create a second child under the under the parent page you've created. Name the second child page whatever you want. Insert the following code into the page [WPBUSDIRMANMANAGELISTING]. The "Manage Listings" page needs this code to work.

7.  	You should see a menu tab with the name WPBusDirMan in your dashboard sidebar. You need to click the tab to access "Manage Options", "Manage Fees" and "Manage Form Fields" and other options. 

8. 	(Version 1.8 and above) You will also see a new dashboard menu tab named "Directory". This tab is where you manage your listings. It works very much the same as the "Posts" tab. Your directory listings are saved as a custom post type. You create your directory categories by going to Directory->Categories and adding the directory categories there. In previous versions you added the categories via Posts->Categories but now that the directory listings are handled via their own custom post type they also use their own custom categories and custom tags. This keeps everything separate from your regular posts and regular categories.

Manage Form Fields allows you to change the form to suit your needs. You can edit the existing fields and add new fields
Manage Options allows you to setup such options as the category IDs that need to be reserved for the plugin's use. The plugin is fully integrated with WordPress and uses your WordPress categories. You have to tell it which categories to use for the Business Directory. For best results please do not use business directory categories for any other purpose . In other words, do not save your regular WordPress posts in categories that are being used by the business directory plugin.  

Once you have configured your options, adjusted your form fields to suit your needs, and setup your fees if you are charging fees for adding listings, your directory should now be ready and working. 
If you are having problems please visit http://www.themestown.com for help.


== Screenshots ==


== Changelog ==

Version 1.8.9 - This version has a small fix for the Google checkout page and a fix for some permalink issues causing a variety of problems with pages. Regarding the google checkout it seems some previous versions were displaying the shortcode at all times and not showing the actual page content. Change made: The line 
		
		add_shortcode('WPBUSDIRMANGOOGLECHECKOUT', 'wpbusdirman_do_googlecheckout'); 
		
		was changed to 
		
		if($wpbusdirman_hasgooglecheckoutmodule == 1)
		{
			add_shortcode('WPBUSDIRMANGOOGLECHECKOUT', 'wpbusdirman_do_googlecheckout');
		}
		
		and moved to below
		
		if($wpbusdirman_hastwocheckoutmodule == 1)
		{
			add_shortcode('WPBUSDIRMANTWOCHECKOUT', 'wpbusdirman_do_twocheckout');
		}
		
		The update also adds 3 new options under a new setting "Permalink Options" that allows you to change the wpbdm-directory slug, the wpbdm-category slug and the wpbdm-tags slug. Please take note of the fact that you cannot use "category" or "tags" or any already reserved WordPress taxonomy names for your slugs as it will make your pages fail to load.


Version 1.8.8 - This version has a small fix in a function related to the payment process on sites that have only one fee setting applied to all categories.

Version 1.8.7 - This version attempts to fix the issues with the contact form not sending the email to the business but instead sending it to the user who was logged in when the listing was submitted.
		
		This version attempts to fix the issues with some top level categories showing no listings found. 
		
		This version fixes the issue with the extra thumbnails not showing up in the listing
		
		This version should fix the issue with the cannot redeclare username_exists() error 
		
		
		

Version 1.8.6 - Removes call to include the WordPress registration.php in order to fix "Fatal error: Cannot redeclare username_exists() (previously declared in..." error 

Version 1.8.5 - Fixes a bug that prevented the payment step from kicking in (applies for version 1.8 and above only)

Version 1.8.4 - Removes automatic creation of pages at setup to correct bug that caused multiple instances of the business directory page and sub pages to be created.
`		Installation process now includes manual creation of the necessary pages.

Version 1.8.3 - Removes conversion process that attempted to convert listings made with versions 1.0 - 1.7 to the new 1.8 custom post type format


Version 1.8.2 - Fixes IE bug where "View" button does not work in listing excerpts
		Fixes Missing argument 1 for wpbusdirman_the_listing_meta() bug
		Fixes missing quote bug near img alt on a couple of lines 



Version 1.8 of WP Business Directory Manager makes use of WordPress 3.0+ custom post types. The way the plugin works has been completely changed to use custom post types. When you upgrade to version 1.8 it will convert your previous installation to the new format. The new format conversion is not guaranteed to work 100% so you are very strongly advised if you have listings in your directory presently to export those listings in case you might need to re-add them manually. 

Plugin now uses a custom post type for posts related to the directory

Plugin now uses a custom category taxonomy for categories related to the directory

Plugin now uses a custom tags taxonomy for tags related to the directory listings

Plugin now allows for better integration with your theme. See the "Usage Doc" section for information on how to customize your theme to use the plugin functions.

Bug related to "Manage Fees" has been fixed. This bug resulted in admin being told payments were not turned on even though payments were in fact turned on.



== Upgrade Notice ==

1. If you are upgrading please export your current data first. There is always the possibility of something going wrong during the upgrading process. Failing to save your data before you upgrade can prove costly if something goes wrong and your data gets lost. Backup your database. Backup your files. 

2. If you are not using WP 3.0 or above you should not install version 1.8 of WP Business Directory Manager. You should continue to use the previous versions, but please keep in mind that free support for previous versions is no longer available. Only 1.8 of the plugin will be actively maintained.

3. If you are upgrading it is recommended that you do a manual upgrade rather than using the automatic upgrade option available via your dashboard. If you use the automatic upgrade option please, again, backup your data first.

== Usage Doc ==

1. Setting up your categories

Go to your Dashboard and look for the "Directory" menu box. Listed under the Directory menu box are the links "Add New", "Categories" and "Tags". Click the "Categories" link and add your categories just the same way you would normally add categories under Posts->Categories.

2. Matching the design to your theme

	If you need to match the template to your theme, you must create a folder inside your theme folder and name the folder "single"; then inside the folder single, you will need to place a duplicate of your theme's single.php, and category.php but you must rename the files to wpbusdirman-single.php, and wpbusdirman-category.php. If you will also need to create your own custom wpbusdirman-index-listings.php (used to display the listing excerpts when the View Listings link is clicked and also the excerpts shown when the Main directory page link is clicked (if you are displaying the listings under the categories) you should also create a file wpbusdirman-index-listings.php using the wpbusdirman-index-listings.php from the "posttemplates" folder as the model. Put your custom copy of wpbusdirman-index-listings.php inside the "single" folder in your theme folder along with the custom wpbusdirman-single.php and wpbusdirman-category.php.
	You can also create your own wpbusdirman-index-categories.php using the one in the posttemplates folder as your model and store that in your theme's "single" folder as well. wpbusdirman-index-categories.php shows the directory categories when the main business directory link is called.
	
	You will need to add some functions to these files in order to activate the plugin features. Most themes will have a single.php, but it's possible your theme might not have a category.php. If your theme does not have a category.php you should use whichever of your theme files displays your category posts for the wpbusdirman-category.php file. Often it will be archives.php. Once you have created these pages refer to the instructions below for setting them up to work with the plugin.

	Refer to the information below to configure your copies of wpbusdirman-single.php, wpbusdirman-category.php and wpbusdirman-index-listings.php. As long as your own custom copies of these files exist in your themes folder inside a folder named "single" the plugin will use your files to display the relevant content instead of using the default copies found in the plugin posttemplate folder

	******Functions for use in wpbusdirman-single.php

	To show the listing images:

		1. The function wpbusdirman_display_main_image() grabs the first image from the array of images associated with the listing and sets it as the main image. Must be used inside the loop.

		<?php wpbusdirman_display_main_image();?>

		2. The function wpbusdirman_display_extra_thumbnails() will loop through and display any additional images associated with the listing. Must be used inside the loop.

		<?php wpbusdirman_display_extra_thumbnails();?>

	To show the listing details:

		1. You can use the function wpbusdirman_single_listing_details() inside the loop to show the listing details

		<?php wpbusdirman_single_listing_details();?>

		2. If you want to rearrange the listing details you can use the functions below instead of using wpbusdirman_single_listing_details()

	The individual listing detail pieces can be used via the below method and functions:

		Code to display the listing title:

		<?php the_title(); ?> OR <?php wpbusdirman_the_listing_title();?>

		Code to display the listing category:

		<?php wpbusdirman_the_listing_category();?> OR (to show the category name without the label) <?php global $wpbdmposttypecategory; echo get_the_term_list( $post->ID, $wpbdmposttypecategory, '', ', ', '' );?>

		Code to display the meta items ( They will be looped through and displayed in the order as they were pulled from the database. (Any ordering you might have set in the plugin's fields manager will not take effect here. At this time that feature does not work with the displaying of custom field data):

		<?php wpbusdirman_the_listing_meta('single');?>

		Code to display the listing excerpt (Short description or whatever you have named the short description field):

		<?php the_excerpt(); ?>	OR <?php wpbusdirman_the_listing_excerpt();?>

		Code to display the listing content (Long description or whatever you have named the long description field):

		<?php the_content();?> OR <?php wpbusdirman_the_listing_content();?>

		Code to display the listing tags:

		<?php wpbusdirman_the_listing_tags();?>
		
		Code to display an edit listing button on the single listing page (If user is not logged in this button will not show)		
		
		<?php wpbusdirman_menu_button_editlisting();?>
		
		Code to display an upgrade listing button on the single listing page (If user is not logged in this button will not show)	
		
		<?php wpbusdirman_menu_button_upgradelisting();?>		
		
	To add the contact listing owner form to wpbusdirman-single.php use:
	
		<?php global $wpbusdirman_permalink; wpbusdirman_contactform($wpbusdirman_permalink,$post->ID,$commentauthorname='',$commentauthoremail='',$commentauthorwebsite='',$commentauthormessage='',$wpbusdirman_contact_form_errors='');?>

	
	******Functions for use in wpbusdirman-category.php
	
		1. The function wpbusdirman_catpage_title() will print the title of the category page 
	
		<?php wpbusdirman_catpage_title();?>
	
		2. The function wpbusdirman_menu_buttons will print the menu buttons "Submit Listing" and "Directory"
	
		<?php wpbusdirman_menu_buttons();?>	
		
		If you want to break these up you can use <?php wpbusdirman_menu_button_submitlisting();?> and <?php wpbusdirman_menu_button_directory();?>
		
		If you also want to put a "View Listings" button on this page to take the viewer back to the main index of listings you can use <?php wpbusdirman_menu_button_viewlistings();?>
		
		You also have available <?php wpbusdirman_dropdown_categories();?> which you can use to display a drop down select list of the categories where desired.
		
	
		3. !!IMPORTANT!! Before you can display the category excerpts you have to let the system know where you need it to pull the category data from. 
		To do this, you need to add the function wpbusdirman_catpage_query just outside the loop. The loop is the code that begins with "if ( have_posts() ) :". Just before that you need to place:
		
		<?php wpbusdirman_catpage_query();?>
		
		4. Once you have placed wpbusdirman_catpage_query just before the loop starts, you now need to place the display excerpts code just inside the loop. So you should have something that looks like:
		
		<?php wpbusdirman_catpage_query();?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post();?>				
		<?php wpbusdirman_display_excerpt();?>
		<?php endwhile;?>
		
		[NOTE] wpbusdirman_display_excerpt() will pull in a preconfigured excerpt setup. If you want to control how the excerpts look you will need to use the individual listing detail pieces as shown below:
		
		Code to display the listing title:

		<?php the_title(); ?> OR <?php wpbusdirman_the_listing_title();?>

		Code to display the listing category:

		<?php wpbusdirman_the_listing_category();?> OR (to show the category name without the label) <?php global $wpbdmposttypecategory; echo get_the_term_list( $post->ID, $wpbdmposttypecategory, '', ', ', '' );?>

		Code to display the meta items ( They will be looped through and displayed in the order as they were pulled from the database. (Any ordering you might have set in the plugin's fields manager will not take effect here. At this time that feature does not work with the displaying of custom field data):

		<?php wpbusdirman_the_listing_meta('excerpt');?>

		Code to display the listing excerpt (Short description or whatever you have named the short description field):

		<?php the_excerpt(); ?>	OR <?php wpbusdirman_the_listing_excerpt();?>

		Code to display the listing content (Long description or whatever you have named the long description field):

		<?php the_content();?> OR <?php wpbusdirman_the_listing_content();?>

		Code to display the listing tags:

		<?php wpbusdirman_the_listing_tags();?>	
		
		Code to display the listing thumbnail
		
		<?php wpbusdirman_display_the_thumbnail();?>
		
		Code to display the View Listings, Edit Listing, Delete Listing buttons (Note that the edit and delete button will only be displayed if the user is logged in and owns the listing)
		
		<?php wpbusdirman_view_edit_delete_listing_button();?>
		
		
		[NOTE] If you want the system to exclude fields based on your "Manage Form Fields" settings where you have checked to exclude certain items from the excerpt you need to use the function wpbusdirman_the_listing_meta('excerpt') instead of wpbusdirman_the_listing_meta('single').
		wpbusdirman_the_listing_meta('single') will show all the meta items regardless of your "Manage Form Fields" settings while wpbusdirman_the_listing_meta('excerpt') will exclude those meta items that you have set to not show in the excerpt.
		
	******Functions for use in wpbusdirman-index-categories.php
			
		[NOTE] Do not include any reference to get_header(), get_sidebar() or get_footer() in wpbusdirman-index-listings.php. These are not needed and having them in the file will cause problems.
		
		1. Code to show "Submit A Listing" submit button
		
		<?php wpbusdirman_menu_button_submitlisting();?>
		
		2. Code to show View Listings submit button (this is for viewing index of listing excerpts. It is not for viewing a single listing)
		
		<?php wpbusdirman_menu_button_viewlistings();?>
		
		3. Code to display the listing categories
		<?php wpbusdirman_list_categories(); ?>
		
			
		
	******Functions for use in wpbusdirman-index-listings.php
	
	[NOTE] Do not include any reference to get_header(), get_sidebar() or get_footer in wpbusdirman-index-listings.php. These are not needed and having them in the file will cause problems.
	
		1. !!IMPORTANT!! Before you can display the listing excerpts you have to let the system know where you need it to pull the listing data from. 
		To do this, you need to add the function wpbusdirman_indexpage_query just outside the loop. The loop is the code that begins with "if ( have_posts() ) :". Just before that you need to place:

		<?php wpbusdirman_indexpage_query();?>

		2. Once you have placed wpbusdirman_indexpage_query just before the loop starts, you now need to place the display excerpts code just inside the loop. So you should have something that looks like:
		
		<?php wpbusdirman_indexpage_query();?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post();?>				
		<?php wpbusdirman_display_excerpt();?>
		<?php endwhile;?>
		
		[NOTE] wpbusdirman_display_excerpt() will pull in a preconfigured excerpt setup. If you want to control how the excerpts look you will need to use the individual listing detail pieces as shown below:
		
		Code to display the listing title:

		<?php the_title(); ?> OR <?php wpbusdirman_the_listing_title();?>

		Code to display the listing category:

		<?php wpbusdirman_the_listing_category();?> OR (to show the category name without the label) <?php global $wpbdmposttypecategory; echo get_the_term_list( $post->ID, $wpbdmposttypecategory, '', ', ', '' );?>

		Code to display the meta items ( They will be looped through and displayed in the order as they were pulled from the database. (Any ordering you might have set in the plugin's fields manager will not take effect here. At this time that feature does not work with the displaying of custom field data):

		<?php wpbusdirman_the_listing_meta('excerpt');?>

		Code to display the listing excerpt (Short description or whatever you have named the short description field):

		<?php the_excerpt(); ?>	OR <?php wpbusdirman_the_listing_excerpt();?>

		Code to display the listing content (Long description or whatever you have named the long description field):

		<?php the_content();?> OR <?php wpbusdirman_the_listing_content();?>

		Code to display the listing tags:

		<?php wpbusdirman_the_listing_tags();?>	
		
		Code to display the listing thumbnail
		
		<?php wpbusdirman_display_the_thumbnail();?>
		
		Code to display the View Listings, Edit Listing, Delete Listing buttons (Note that the edit and delete button will only be displayed if the user is logged in and owns the listing)
		
		<?php wpbusdirman_view_edit_delete_listing_button();?>		
		
		
		[NOTE] If you want the system to exclude fields based on your "Manage Form Fields" settings where you have checked to exclude certain items from the excerpt you need to use the function wpbusdirman_the_listing_meta('excerpt') instead of wpbusdirman_the_listing_meta('single').
		wpbusdirman_the_listing_meta('single') will show all the meta items regardless of your "Manage Form Fields" settings while wpbusdirman_the_listing_meta('excerpt') will exclude those meta items that you have set to not show in the excerpt.
		
		3. !!IMPORTANT!! - You must reset the query by adding <?php wp_reset_query();?> after the loop ends. 

3. Adding a menu of the directory categories

To add a menu of the directory categories to your theme use the code below:
<?php global $wpbdmposttypecategory; wp_list_categories('title_li=&taxonomy='.$wpbdmposttypecategory); ?>
You can filter the function the way you would normally filter wp_list_categories. Just make sure it includes 'taxonomy='.$wpbdmposttypecategory so that it knows you only want the plugin directory categories.


4. Adding a latest posts widget

To add a latest listings widget to your sidebar use the function <?php wpbusdirman_latest_listings(10);?>
The 10 in the example is the number of listing headlines you want to show. This function will change once WordPress gets around to allowing you to use the the standard wp_get_archives function to show latest posts from custom post types.


== Frequently Asked Questions ==


1. How do I setup my categories?
In version 1.8 and above you setup your categories under the "Directory" menu tab that will appear in the dashboard menu when you install the plugin. Under the Directory menu tab there is a link "Categories". You click this and add your categories the same way you do with your normal WordPress blog.

2. Why isn't the email address showing up in the listings?
The email address is not showing up because it is being programmatically blocked. The reason for this is to protect the privacy of your users so that their email address does not appear for public viewing where email harvesting bots can grab them and add them to spam email lists. 

3. Okay, I understand that the plugin is blocking the email address to protect the user's privacy, but I still prefer to display the email address in the listing pages. How can I do that?
To override the email block, go to the WPBusDirMan menu tab that appears in the dashboard menu. Click the "Manage Options" link. Look under "Miscellaneous settings". You will see an option "Override email Blocking?". Check that option and it should make the email address visible.






=== Simplr User Registration Form ===
Contributors: mpvanwinkle77
Donate link: http://www.mikevanwinkle.com/
Tags: registration, signup, wordpress 3.0, cms, users, user management
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 1.7

This plugin allows users to easily add a custom user registration form anywhere on their site using simple shortcode.

== Description ==

The goal of this plugin is to give developers and advanced Wordpress users a simple way to create role-specific registration forms for their Wordpress website. For instance, you might be running an education based site in which you wanted both teachers and students to participate. This plugin enables you to create distinct registration forms for each type of registrant.

Because the focus is on separating registrants, I have not focused on creating a highly customizable form like <a href="http://wordpress.org/extend/plugins/register-plus/" title="Register Plus">Register Plus</a>. 

To use this plugin simply employ the shortcode <code>[register]</code> on any Wordpress post or page. The default role is "subscriber". To apply another role to the registration simply use the the role parameter, for instance: <code>[Register role="editor"]</code>. If you have created custom roles you may use them as well. 

For advanced options, insert the shortcode using the TinyMCE button.

== Installation ==

1. Download `simplr_reg_page.zip` and upload contents to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `[Register role="YOUROLE"]` in the page or post you would like to contain a registration form.

== Frequently Asked Questions ==

See plugin settings page for detailed instructions

== Screenshots ==

screenshot-1.(png|jpg|jpeg|gif)

== Changelog ==

= 1.0 =
* Initial Version
= 1.1 =
-fixed stylesheet path
= 1.5 =
Added filters for adding fields and validation.
= 1.7 =
Added implementation button to WordPres TinyMCE Editor.
Add new filters and hooks. 
Email validation.
Allows user to set their own password.
Additional security to prevent registering administrative role via plugin.
=== Silencesoft RSS Reader ===
by: Byron Herrera - bh at silencesoft dot net
Contributors: silence
Donate link: http://www.silencesoft.net/
Tags: rss reader, external feed, rss
Requires at least: 2.0.2
Tested up to: 3.0.1
Stable tag: 0.6

A plugin to read external rss feeds

== Description ==

This is a plugin that allows read some external rss feeds and show it on a page or in a widget.
Sites can be categorized and you can add an image or use a gravatar.

Using Plugin:
---

Add it on a page:
[sil_rss:0:content:0]
* First Param: 0 - The total of items to show.
  With 0 uses the number in options. 
* Second Param: content - Type to show (content or widget or feed).
  With feed it shows a feed from a site.
* Third Param : 0 - Category to show with content or widget.
  With feed option, it uses this parameter to know the feed. 

Sample:
[sil_rss:20:content:0] - Show 20 items from all sites.
[sil_rss:5:widget:1] - Show 5 items of category 1 like widget.
[sil_rss:0:feed:1] - Show number of items defined on options from site 1.

Call function:
To show the list calling a function, use:
<?php echo sil_rss_show(20, "content", 0); ?>
Params are same like before.

List all blogs:
To show the list of all blogs on a page, use:
[sil_rss_list_blogs]
or call it by a function
<?php echo sil_rss_list_blogs(); ?>

RSS:
Your url to subscribe to RSS is:
your_url/?feed=external
Saving OPML:

Your OPML file exported is on:
your_url/?sil_opml

Thanks to
---
Autoritas Consulting, sponsors of this plugin. :)
<http://www.autoritas.es/>


== Installation ==

Upload plugin folder to plugins folder.
Chmod 777 cache and images folders.
Change Options as necesary.

== Changelog ==

= 0.6 =
* Fixed to use multiple categories on one page.

= 0.5 =
* Deleted some styles.
* Fixed plugin path.
* Added option to edit item categories.
* Added public opml option

= 0.4 =
* Added new options to show or hide categories and links on top of page.
* Added a new option to show feeds from a site.

= 0.3 =
* First release.

=== News-Ticker ===

Contributors: Daniel Sachs
Tags: news, ticker, fading, scrolling, rss, rss2, news feed, comments feed, news ticker, featured posts, gallery, image gallery
Requires at least: 2.0
Tested up to: 2.9
Stable tag: 2.0.0

Inserts a fading or sliding text banner (ticker) with Posts, Entries or Comments RSS feeds or Images.

== Description ==

**A JQuery based News Ticker Displays a sliding or fading list of post titles, rss reeds or comments and excerpts with links to post. Starting from ver.2.0 the ticker can also be used as a featured posts image gallery**

You can display:

* Most Popular Posts over the last x days (via Wordpress.com Stats Plugin)
* Most Commented Posts
* Recent Posts
* Recent Comments
* Specific Posts
* Number of Posts to display
* Your default RSS feed, your Comments RSS or even an RSS feed from another site, like Twitter or your favorite news site.

You can set:

* Category Filter
* Ticker Length in characters 	
* Ticker Speed
* Ticker Timeout (time between the transition)
* Ticker Animation : Fade, Slide Up, Slide Down, Left, Right or Expand
* Ticker content : Image, Date, Excerpt

The plugin is used on http://18elements.com homepage

Version 2.0

**PLEASE NOTE! It is highly recommended to deactivate the plugin and to delete the files prior to v2.0 installation

* Displays images attached to the post
* New javascript handler
* Four more transitions added
* The ticker stops on hover
* Option to show/hide the image, the date and the excerpt of the post 
* Added classes for easier ticker styling

Version 1.5.0

* External RSS Feed option added
* Small bugfixes

Version 1.0.2

* Better jQuery handling
* Added date display for tickers

Version 1.0.1

* Small bug fixes

Version 1.0

* initial release

== Installation ==

1. Upload `news-ticker` directory to the `/wp-content/plugins/` directory.
2. Insert `<?php if ( function_exists('insert_newsticker') )  { insert_newsticker(); } ?>` into your template file.
3. Activate the plugin via the Plugins menu.
4. Configure options via the Settings > News-Ticker menu.

**Please note: if your theme doesn't use wp_head()  function the ticker will display a simple list of items.

== Frequently Asked Questions  ==

**How do I style the ticker?

The news ticker comes with unique classes and id's for your css styling
1. The ticker is id="news-ticker"
2. The date is class="tickerDate"
3. The title is class="tickerLink"
4. The excerpt is class="tickerText"

for more info visit the plugin page >


== Screenshots ==

1. The Setup Page



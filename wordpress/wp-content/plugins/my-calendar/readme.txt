
=== My Calendar ===
Contributors: joedolson
Donate link: http://www.joedolson.com/donate.php
Tags: calendar, dates, times, events, scheduling, event manager
Requires at least: 2.9.2
Tested up to: 3.1-RC5
Stable tag: trunk

Accessible WordPress event calendar plugin. Show events from multiple calendars on pages, in posts, or in widgets.

== Description ==

My Calendar provides basic event management and provides numerous methods to display your events. The plug-in can support individual calendars within WordPress Multi-User, or multiple calendars displaying different categories of events. 

Basic Features:

*	Standard calendar or list views of events in calendar
* 	Show events by week or by month
* 	Mini-calendar view for compact displays
*	Widget to show today's events
*	Configurable widget to show upcoming or past events 
*	Widget templating to control what information is displayed in widget output.
*	Calendar can be displayed including a single category, all categories, or a selection of categories
*	Disable default CSS and default JavaScript or display only on specific Pages/Posts
*	Editable CSS styles and JavaScript behaviors
* 	Events can be configured to be added by any level of user; directly to calendar or reserved for administrative approval
* 	Help information within the plugin for shortcode usage and widget templates.
* 	Store and display the following information for each event: title, description, alternate description, event category, URL, start date, start time, end date, end time, registration status (open, closed or irrelevant), event location.
* 	Email notification to administrator when events are scheduled or reserved
*	Location Manager for storing frequently used venues
*   Import method from Kieran O'Shea's Calendar plugin
* 	Integrated Help file to guide in use of shortcodes and template tags

This calendar is a branch from [Kieran O'Shea's Calendar plugin](http://wordpress.org/extend/plugins/calendar/). You can import any previous scheduled events from Kieran's calendar into this one. 

Languages available:

* American English (Default)
* Japanese ([Daisuke Abe](http://www.alter-ego.jp/)) - to 1.7.1
* Danish ([Jakob Smith](http://www.omkalfatring.dk/)) - to 1.7.0
* Italian ([Sabir Musta](http://mustaphasabir.altervista.org)) - to 1.7.0
* Czech ([Jan Rybarik](http://janrybarik.cz)) - to 1.6.3
* Brazilian Portuguese (Leonardo Kfoury) - to 1.6.0?
* German (Roland P) - to 1.5.0
* Dutch (Luud Heck) - to 1.4.9

Older translations

* Spanish ([Esteban Truelsegaard](http://www.netmdp.com))
* Finnish (Ilpo Puhakka)

New or updated translations are always appreciated. The translation file is included in the download. 

== Installation ==

1. Upload the `/my-calendar/` directory into your WordPress plugins directory.

2. Activate the plugin on your WordPress plugins page

3. Configure My Calendar using the following pages in the admin panel:

   My Calendar -> Add/Edit Events
   My Calendar -> Manage Categories
   My Calendar -> Manage Locations
   My Calendar -> Settings   
   My Calendar -> Style Editor
   My Calendar -> Behavior Editor

   
4. Edit or create a page on your blog which includes the shortcode [my_calendar] and visit
   the page you have edited or created. You should see your calendar. Visit My Calendar -> Help for assistance
   with shortcode options or widget configuration.

== Changelog ==

= 1.7.8 =

* Bug fix: Behaviors page limits lost on settings refresh
* Bug fix: Fix {enddate} shortcode output.
* Bug fix: iCal output improvements
* Modification: RSS and iCal output are disabled entirely when turned off, rather than just hidden.
* Modification: Added styles for days out of current month

= 1.7.7 =

* Bug fix: Upcoming Events widget fault in 'dates' mode.

= 1.7.6 = 

* Bug fix: Upcoming Events widget in days mode was not offsetting time using GMT reference. (Committed silently in 1.7.5)
* Bug fix: Default template not rendered in Today's Events when template left blank
* Bug fix: Slashes not stripped in category key.
* Bug fix: Upcoming Events widget if no upcoming events
* Bug fix: Error with retrieval of Author's ID
* Fixed some non-translatable text strings
* Logic change: Upcoming Events now bases choice on time rather than date (events happening later today are future, rather than only events happening tomorrow or later.)
* Enhancement: respects custom wp-content location definitions

= 1.7.5 =

* Bug fix: Error with upcoming events when selected by dates and holiday skipping enabled.
* Bug fix: Upcoming Events widget title defaulted to 'Today's Events'
* Change: Reversed order of Latitude/Longitude on forms to match Google's implementation.

= 1.7.4 =

* Bug fix: Upcoming events templates ran htmlentities on output

= 1.7.3 = 

* Bug fix: upcoming events substitute text still not appearing in some contexts. 
* Bug fix: Today's event substitute text had assignment in place of comparison
* Bug fix: Event location not saved properly on edit if Location Fields are disabled on input
* Bug fix: Fixed date and time issues in iCal output
* Bug fix: Fixed character set issue in RSS output
* Bug fix: Major problem with Holiday category event delimiting
* Danish translation updated to 1.7.0
* Japanese translation updated to 1.7.1
* Minor documentation and readme.txt updates
* Added additional fallback settings for widgets
* Fixed minor installation issue with version detection.
* Added CSS hook .nextmonth on dates occurring past the end of the currently displayed month.
* Added check for '#' symbol on hex colors in category management.

= 1.7.2 =

* Bug fix: Fixed import from Calendar feature.
* Bug fixed: Upcoming events widget default text fixed
* Italian translation updated to 1.7.0

= 1.7.1 =

* Default setting for custom user location type not set
* Reset for inherit.css styles missing
* Widget shortcodes stripped HTML
* Added a fallback function for exif_imagetype 'cuz some servers don't have it available by default.
* Nonce missing in database upgrade
* Ability to edit text for shortcode fallback (No events text) lost.
* Widget defaults not installed on new installation
* Mini and List jQuery did not prevent default link action
* Changed install action to default User settings to off.

= 1.7.0 =

* Fix in AJAX navigation for IE
* Fix in JavaScript to re-activate close button
* Fixed bug with locations list not registering current location type in form mode
* Fixed bug with upcoming events and today's events output when regions limits were set
* Fixed bug with upcoming events producing incorrect dates for events recurring on a specific day of the month.
* Revision of Widgeting setup to offer multi-widget support (will require you to re-setup your widgets)
* Revision of style editor to use external stylesheets. 
* Revision of style support to add option for custom stylesheets stored outside of plugin directory
* Added: multiple base stylesheets
* Added: Event markup in hCal format
* Added Weekly mode for list and grid view
* Added RSS and iCal exports for upcoming events (enable and disable in settings)
* Added option to block display of an event if there is an event that day which is in a designated 'Holiday' category.
* Added permission setting to allow non-administrators to edit or delete any event.
* Added Czech translation (to 1.6.3)
* Updated Italian and Danish translations
* Security: Implemented nonces

= 1.6.3 =

* Updated jQuery to fix conflicts in previous versions and so behaviors would work with AJAX navigation. Not updated by upgrade; use Behaviors reset to apply. 
* Incorporated option to enable AJAX navigation for next/previous navigation.
* Fixed bug with multi-month display in list format where January could not be displayed.
* Revised settings page for clarity. 
* Fixed some default settings issues.
* Fixed a bug where the locations lists didn't respect the datatype parameter.
* Added templating to event titles for calendar grid or list output.

= 1.6.2 = 

* Fixed broken style editor. (The way it was broken was awfully weird...kinda wonder how I did it!)
* Fixed missing div in calendar list output.
* Removed debugging call which had been left from testing.
* Fixed storage of initial settings for user settings (array did not store probably initially.)
* Added Italian translation by [Sabir Musta](http://mustaphasabir.altervista.org)

= 1.6.1 =

* Bug fix in event saving

= 1.6.0 =

* Feature: User profile defined time zone preference
* Feature: User profile defined location preference
* Feature: Define event host as separate from event author
* Feature: Added ability to hide Prev/Next links as shortcode attribute
* Change: Separated Style editing from JS editing

= 1.5.4 =

* Fixed: Bug with permissions in event approval process.

= 1.5.3 = 

* Fixed: Bug which broke the {category} template tag
* Fixed: Bug which moved extra parameters before the "?" in URLs
* Fixed: Bug which produced an incorrect date with day/month recurring events on dates with no remainder
* Added: Japanese translation by [Daisuke Abe](http://www.alter-ego.jp/)

= 1.5.2 =

* Fixed: Bug where event data wasn't remembered if an error was triggered on submission.

= 1.5.1 =

* Fixed: Bug where events recurring monthly by days appeared on wrong date when month begins on Sunday.
* Fixed: Bug where events recurring monthly by days appeared on dates prior to the scheduled event start.
* Performance improvement: Added SQL join to incorporate category data in event object
* Added quicktag to provide access to category color and icon in widget templates
* Changed link expiration to be associated with the end date of events rather than the beginning date.
* Updated readme plugin description, help files, and screenshots.

= 1.5.0 =

* Added: German translation.
* Updated: Danish translation.
* Added: Administrator notification by email feature [Contributions by Roland]
* Added: Reservations and Approval system for events. [Contributions by Roland]
* Added: Events can be recurring on x day of month, e.g. 3rd Monday of the month.

= 1.4.10 =

* Fixed: Failed to increment internal version pointer in previous version. 
* Fixed: Invalid styles created if category color set to default.
* Fixed: (Performance) Default calendar view attempted to select invalid category.
* Updated: Danish translation.

= 1.4.9 = 

* Fixed: Bug where location edits couldn't be saved if location fields were on and dropdown was off
* Fixed: Bug where latitude and longitude were switched on Google Maps links
* Fixed: Bug where map link would not be provided if no location data was entered except Lat/Long coordinates.

= 1.4.8 =

* Added: Ability to copy events to create a new instance of that event
* Added: Customization of which input elements are visible separate from what output is shown.
* Fixed: Issue where one JS element could not be fully disabled
* Fixed: Internationalization fault with Today's Events showing events from previous day 
* Fixed some assorted text errors and missing internationalization strings.
* Fixed issue where the 'Help' link was added to all plug-in listings.
* Reorganized settings page UI.

= 1.4.7 =

* Fixed: Bug where infinitely recurring events whose first occurrence was in the future were not rendered in upcoming events
* Fixed: Bug where infinitely recurring bi-weekly events only rendered their first event in calendar view
* Added: Option to indicate whether registration for an event is open or closed, with customizable text.
* Added: Option to supply a short description alternative to the full description.

= 1.4.6 = 

* Fixed: Flash of unstyled content prevention scripts weren't disabled when other scripting was disabled.
* Fixed: Categories which started with numerals couldn't have custom styles.
* Fixed: Locations required valid 0 float value to save records on some servers; now supplied by default.

= 1.4.5 = 

* Fixed a bug with editing and adding locations
* Fixed a bug with error messages when adding categories
* Fixed a bug with identification of current day (again?)
* Added Danish translation (Thanks to Jakob Smith)

= 1.4.4 = 

* Fixed a bug where event end times tags were not rendered when blank in widget templates
* Fixed a bug with event adding and updating for Windows IIS
* Fixed a bug with international characters
* Reduced number of SQL queries made.
* Moved JavaScript output to footer.
* Improved error messages.
* Significant edits to basic codebase to improve efficiency.
* Fixed bug where full default styles didn't initially load on new installs.
* Re-organized default styles to make it easier for users to customize colors.

= 1.4.3 = 

* Fixed a bug where event end times were displaying the start time instead when editing.
* Fixed a bug introduced by the mini calendar option which displayed titles twice in list format.
* Fixed a bunch of typos.
* Added a loop which automatically adds the mini calendar styles if you don't already have them.
* Fixed a bug where JS didn't run if the 'show only on certain pages' option was used.
* Added a qualifier for upgrading databases when you haven't added any events.

= 1.4.2 =

* Fixed a bug in the widget display code which caused problems displaying multiple categories.

= 1.4.1 =

* Database upgrade didn't run for some users in 1.4.0. Added manual check and upgrade if necessary.

= 1.4.0 =

* Bug fixed: Today's Events widget was not taking internationalized time as it's argument
* Added end time field for events
* Added option for links to expire after events have occurred.
* Added options for alternate applications of category colors in output.
* Added ability to use My Calendar shortcodes in text widgets.
* Added GPS location option for locations
* Added zoom selection options for map links
* Lengthened maximum length for category and event titles
* Added a close link on opened events details boxes.
* Added an option for a mini calendar display type in shortcode
* Optimized some SQL queries and reduced total number of queries significantly.
* Extended the featured to show CSS only on certain pages to include JavaScript as well.
* Upcoming events widget only allowed up to 99 events to be shown forward or back. Changed to 999.
* Attempted to solve a problem with infinitely recurring events not appearing in upcoming events. Let me know.
* Added setting to change Previous Month/Next Month text.
* Yeah, that's enough for now.

= 1.3.8 = 

* Fixed problem with CSS editing which effectively disabled CSS unless a specific choice had been made for pages to show CSS

= 1.3.7 =

* Aren't you enjoying the daily upgrades? I made a mistake in 1.3.5 which hid text in an incorrect way, causing problems in some contexts.

= 1.3.6 =

* Fixed an issue where not having defined Pages to show CSS resulted in a PHP warning for some configs.

= 1.3.5 =

* Fix for flash of unstyled content issue.
* Added configuration for time text on events with non-specific time.
* Fixed bug where, in list views with multiple months, events occurring on days which did not exist in the previous month were not rendered. (Such as March 30th where previous month was February.)
* Fixed bug where the multi-month view setting for lists caused previous/next events buttons to skip months in calendar view.
* Added option to disable category icons.
* Added option to insert text in calendar caption/title area, appended to the month/year information.
* Fixed a bug where it was not possible to choose the "Show by days" option in the upcoming events widget.
* Updated documentation to match
* Fixed a bug where upcoming events in Days mode did not display correct date
* Added an option to define text to be displayed in place of Today's Events widget if there are no events scheduled.
* Minor changes to default CSS
* Ability to show CSS and JavaScript only on selected pages.

= 1.3.4 =

* Fixed a bug with map link and address display which I forgot to deal with in previous release.

= 1.3.3 = 

* Fixed bug with upgrade path which caused locations database to be created on every activation (also cause of errors with some other plugins). (Thanks to Steven J. Kiernan)
* Made clone object PHP 4 compatible (Thanks to Peder Lindkvist)
* Corrected errors in shortcode functions for today's events
* Corrected rendering of non-specific time events as happening at midnight in widget output

= 1.3.2 = 

* Fixed bugs with unstripped slashes in output
* Fixed a bug where users could not add location information in events if they had not added any recurring locations
* Removed requirement that address string must be five characters to display a link

= 1.3.1 = 

* Corrected incorrect primary key in upgrade path.
* Added version incrementing in upgrade path.

= 1.3.0 = 

* Fixed a CSS class which was applied to an incorrect element.
* Revisions to the Calendar import methods
* Moved style editing to its own page
* Added JavaScript editing to allow for customization of jQuery behaviors.
* Internationalized date formats
* Shortcode support for multiple categories.
* Shortcode support for custom templates in upcoming and today's events
* Added a settings option to eliminate the heading in list format display.
* Fixed a bug which treated the event repetition value as a string on event adding or updating, not allowing some users to use '0' as an event repetition.
* Made events listing sortable in admin view
* Minor revisions in admin UI.
* Added database storage for frequently used venues or event locations.
* Modified JavaScript for list display to automatically expand events scheduled for today.

= 1.2.1 = 

* Corrected a typo which broke the upcoming events widget.

= 1.2.0 = 

* Added shortcodes to support inserting upcoming events and todays events lists into page/post content.
* Added option to restrict upcoming events widgets by category
* More superficial CSS changes
* Added Brazilian Portuguese language files
* Fixed bug where I reversed the future and past variable values for upcoming events widgets
* Fixed bug in multi-user permissions.
* Added feature to look for a custom location for icons to prevent overwriting of custom icons on upgrade.

= 1.1.0 =

* Fixed some problems with Upcoming Events past events not scrolling off; hopefully all!
* Fixed some problems with fuzzy interpretations of the numbers of past/future events displayed in Upcoming Events.
* Added Bi-weekly events
* Added restrictions so that admin level users can edit any events but other users can only edit their own events
* Removed character restrictions on event titles
* Revised default stylesheet 

= 1.0.2 =

* Fixed problems with editing and deleting events or categories in multiblog installation
* Fixed escaping/character set issue
* Fixed issue when blog address and wp address did not match (introduced in 1.0.1)
* Added import method to transfer events and categories from Kieran O'Shea's Calendar plugin

= 1.0.1 =

* Added missing template code for event end dates.
* Changed defaults so that styles and javascript are initially turned on.
* Removed function collisions with Calendar
* Fixed bug where My Calendar didn't respect the timezone offset in identifying the current day.
* Fixed bug where multiblog installations in WP 3.0 were unable to save events and settings.
* Added Spanish translation, courtesy of [Esteban Truelsegaard](http://www.netmdp.com). Thanks!

= 1.0.0 =

* Initial launch.

== Frequently Asked Questions ==

= Hey! Why don't you have any Frequently Asked Questions here! =

Because the majority of users end up on my web site asking for help anyway -- and it's simply more difficult to maintain two copies of my Frequently Asked Questions. Please visit [my web site FAQ](http://www.joedolson.com/articles/my-calendar/faq/) to read my Frequently Asked Questions!

== Screenshots ==

1. Calendar using calendar list format.
2. Calendar using monthly calendar format.
3. Event management page
4. Category management page
5. Settings page
6. Location management
7. Style and behavior editing

== Upgrade Notice ==

Upgrading from version 1.6.3 or below will require you to re-configure your upcoming events and today's events widgets.
=== CalendarPress ===
Contributors: grandslambert
Donate link: http://calendarpress.net/donate.html
Tags: calendar, calendars, event calendar, meetings, shows, events, RSVP, registration
Requires at least: 3.0
Tested up to: 6.1.1
Stable tag: 1.0.0

Add an event calendar with details, Google Maps, directions, RSVP system and more.

== Description ==

Add an event calendar with details, directions, RSVP system and more.

= Current Features =

* RSVP System with three methods of signup.
* Upcoming Events Widget
* Multisite support
* Signup system that allows a Yes/No/Maybe option like Facebook or to limit signups.
* Shortcodes to display calendars, lists, and single events.

= Future Features =

* Waiting List
* Fully Customizable
* Google Maps
* Featured Events
* Share and move events between sites

= Requirements =

* Must be run on servers with PHP5.
* Requires Wordpress 3.0 or newer.

== Installation ==

1. Upload `calendar-press` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure the plugin on the Settings menu screen.

== Changelog ==

= 1.0.0 - November 30th, 2022 =

* Updated code to remove deprecated functions
* Fixed all code errors to be compliant
* Removed code that was no longer in use.
* Switched versioning to SemVer

= 0.4.3 - February 21st, 2010 =

* Fixed an issue that broke the settings page and footer on non-network sites.
* Fixed an issue where some of the meta boxes were not saving data correctly.
* Fixed an issue that prevented AJAX from working in subfolder installs.

= 0.4.2 - February 20th, 2010 =

* Fixed an issue that prevented some event showing in the widget.
* Added option to store templates files in a folder named 'calendar-press' in theme folders - to be required after version 0.5.0.
* Added two new shortcodes - [event-list] to list events and [event-show] to show a single event in a post or page.
* Changed [calendarpress] to [event-calendar] and will support old shortcode until verison 1.0.
* Renamed all template tags prefixed with 'cp_' to be prefixed with 'event_' to avoid conflicts with other plugins.

= 0.4.1 - February 16th, 2010 =

* Fixed an issue that dispalayed errors when no registration type was selected.
* Fixed an issue where overflow button was still displayed even if the option was not active.
* Fixed an issue that displayed errors on yes/no/maybe type pages on events with no signups.
* Fixed an issue that broke the WordPress updates page when plugin was active.

= 0.4.0 - February 10th, 2010 =

* Added support for pretty permalinks.
* Add network support so events can be created and shared across the network.
* Removed the need to select a page to display the calendar on.
* Added the calendarpress shortcode to allow display of events on posts and pages.
* Added support for various registration types (signups, yes/no/maybe, none).
* Updated how taxonomies work and allows for creating your own taxonomies.
* Renamed the plural taxonomy names to singular, requires reactivation of plugin.
* Split the details and signup boxes on the editor screen.
* Added an option to use a Yes/No/Maybe registration option similar to Facebook.
* Fixed an issue when selecting begin date on editor - now updates end date as well.
* Fixed an issue where the time could not be set to 12:00pm (noon).
* Updated the registration screens to refresh and show registraions rather than just hide the buttons.
* Separated code into various classes to improve loading speed.
* Fixed issue with events that span several days not showing on all days (Thanks to Christopher Hess).

= 0.3.0 - August 10th, 2010 =

* Fixed permalinks if using default style permalinks.
* Added support for cookies to remember what month in the calendar was last viewed.
* Added the ability to change the name of the plugin - no longer see "CalendarPress" as the menu item.
* Update the settings page to have tabs for easier management of plugin settings.
* Changed the post type from "events" to "event" to better follow naming structure. Requires plugin reactivation.
* Added a simple listing widget for your sidebar. More to come soon.

= 0.2.3 - June 31st, 2010 =

* Fixed a bug that prevented new events from being saved.
* Added option on event editor to hide the signup options (check box to show).

= 0.2.2 - June 30th, 2010 =

* Fixed a bug that set published date to current date when saving on all post types - should only affect events.
* Fixed a bug that displayed January, 1970 when looking at the December calendar.
* Fixed a bug that would prevent February from displaying towards the end of any month with more than 28 days.

= 0.2.1 - June 30th, 2010 =

* Added the missing language file for translators.

= 0.2.0 - June 29th, 2010 =

* Rewritten to use the Custom Post Types in Wordpress 3.0
* Fixed some issues with templates.
* Added template tags to display most event data.
* Added comment support, thumbnails, categories, and tags.
* Does not convert old events - sorry.

= 0.1.0 - January 15th, 2010 =

* Early Beta release to allow users to begin testing the functionality.
* Not all features are included - more cool features coming soon.

== Upgrade Notice ==

= 0.4.1 =
Removes several error messages pertaining to event registations.

= 0.4.0 =
Major changes that will require updates to work on all sites, including single sites.

= 0.3.0 =
Fixes permalinks when using default style.

= 0.2.3 =
Urgent Upgrade! Can't create new events on some themes.

= 0.2.2 =
Urgent Upgrade! Causes dates to change when publishing posts and pages. Fixed!

= 0.2.0 =
This is a complete rewrite - will NOT convert old events - no conversion system planned.

= 0.1.0 =
None, it's brand new!

== Frequently Asked Questions ==

= How do I display events? =

You must first create a page for your events to be displayed on. Then you will need to select that page in the drop down on the CalendarPress Settings page.

= What URL will my events have? =

Your events will adopt the URL of the page you select to display your calendar on, with the slug for the event appended to that.

= Where can I get support? =

http://support.calendarpress.net

== Screenshots ==


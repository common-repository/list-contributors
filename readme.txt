=== Plugin Name ===
Contributors: Carter Fort
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=V8B3ED9PGP9CC
Plugin Name: List Contributors
Tags: authors, contributing, listing, biography, jquery, CMS
Requires at least: 2.8.0
Tested up to: 2.9.2
Stable tag: trunk

Create a special section on your website to show off contributing authors and their posts in style.

== Description ==

List Contributors does exactly what it says; shows a list of your contributing authors and their posts. Uses jQuery to hide/show their biographies. Allows for rich-text descriptions as well as photographs. Integrates seamlessly with the WordPress User database.

*Feature List*

* Identify your contributors from a drop-down menu of all registered WordPress users on your site.
* Each contributor gets a description and an optional photograph.
* Accordion-like slides hide and show contributor biographies and photographs.
* Turn jQuery on or off from the admin panel to prevent conflicts.
* Display your contributors using template tag or shortcode.


== Installation ==


1. Upload the List Contributors directory to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. You have a few display options:
	* Template tag: `<?php if (function_exists('list_contributors_handler')) echo list_contributors_handler(); ?>`
	* Shortcode: [list-contributors]


== Frequently Asked Questions ==

None yet.
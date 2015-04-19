=== Easy Pricing Tables Lite by Fatcat Apps ===
Contributors: davidhme, fatcatapps
Donate link: https://fatcatapps.com/easypricingtables/?utm_campaign=donate%2Blink&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral
Tags: pricing table, price table, comparison table, table, pricing grid, responsive pricing table, sales page, pricing page, woocommerce
Author URI: https://fatcatapps.com/?utm_campaign=author%2Buri&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral
Plugin URI: https://fatcatapps.com/easypricingtables/?utm_campaign=plugin%2Buri&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral
Requires at least: 3.6
Tested up to: 4.1.1
Stable tag: 2.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create a Beautiful, Responsive and Highly Converting Pricing Table in Less Than 5 Minutes with Easy Pricing Tables for WordPress. No Coding Required.

== Description ==
*   The **Easy Pricing Tables** WordPress Plugin makes it easy to create and publish beautiful pricing tables and comparison tables on your WordPress site. You will be able to set up and publish your pricing table in no time.

*   [View screenshots of pricing tables built with this plugin](https://wordpress.org/plugins/easy-pricing-tables/screenshots/).

*   [View Easy Pricing Tables Premium Live Demo &raquo;](http://ept-demo.fatcatapps.com/?utm_campaign=description%2Bdemo%2Blink&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral)

> #### Easy Pricing Tables Premium
> Easy Pricing Tables Premium comes with the following features.<br />
>
> Ten Gorgeous Designs.<br />
> Fully Customizable (Colors, etc...).<br />
> Priority Email Support.<br />
> Tooltips.<br />
> Google Analytics Integration.<br />
> Pricing Toggles (switch between currencies or monthly/yearly pricing.<br />
> And much more....<br />
>
> [Learn more about Easy Pricing Tables Premium >>](https://fatcatapps.com/easypricingtables/?utm_campaign=description%2Bcta&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral)

= Overview =

*   Easy Pricing Tables implements conversion rate optimization (CRO) best practices and guides you through the process of creating a pricing table that converts 

*   Easy Pricing Tables works with any WordPress theme you have installed. After installing the plugin and creating your first pricing table, you can publish your table anywhere on your site using a shortcode.


= Easy Pricing Tables Features =

*   Works with any WordPress Theme
*   Responsive Pricing Tables
*   Intuitive User Interface
*   Built-in Conversion Rate Optimization Best Practices
*   Create Unlimited Pricing Rows
*	Customize Your Design: Font-size, Color Pickers and Rounded Borders.
*   Use Drag & Drop To Reorder Columns
*   Featured Your Most Popular Pricing Column
*   Custom CSS - Add Custom CSS to your pricing table


*   [Check out Easy Pricing Tables Premium](https://fatcatapps.com/easypricingtables/?utm_campaign=ept-description-cta&utm_source=wordpress.org%2Fplugins&utm_medium=referral)


= Relevant Links =

*   [Easy Pricing Tables Premium live demo](http://ept-demo.fatcatapps.com/?utm_campaign=ept-description-demo&utm_source=wordpress.org%2Fplugins&utm_medium=referral)
*   [FatcatApps.com](http://fatcatapps.com/)
*	[Other plugins by FatcatApps](https://profiles.wordpress.org/fatcatapps/#content-plugins)
*	[FatcatApps founder, David Hehenberger, on Twitter](https://twitter.com/davidhme)

== Installation ==

1. Upload `easy-pricing-tables.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. In your sidebar, select 'Pricing Tables -> Add New' to create a new table

== Frequently Asked Questions ==

= I'm using S2 member / WooCommerce / etc and would like to replace your built-in button with a button generated from a shortcode =
To disable the pricing table button and replace it with a shortcode, simply enter the following in the 'Button Text' row:
`[shortcode][my-example-shortcode/][/shortcode]`

= My table rows aren't aligned properly =
If within the same row, you use a lot more text in some features than in others, your feature height alignment might be weird.
This problem is due to tables being responsive instead of fixed width. You can fix it by adding manual linebreaks for your features.
<br/><br/> results in one linebreak.

= I want to change the design for each individual column =
This currently isn't supported in the user interface. However, each column has its own unique HTML class that can be modified using a css class selector.

*	Class of the first column: ptp-col-id-0
*	Class of the second column: ptp-col-id-1
*	Class of the third column: ptp-col-id-2
*	etc...

= How do I change the design of individual feature rows? =
This currently isn't supported in the user interface. However, each feature row has its own unique HTML class that can be modified using a css class selector.

*	Class of the first feature row: ptp-row-id-0
*	Class of the second feature row: ptp-row-id-1
*	Class of the third feature row: ptp-row-id-2
*	etc...

= I want to adjust my column width =
This currently isn't supported in the user interface. This plugin uses a fixed percentage column width based on how many columns your pricing table has.
For example, if your pricing table has 3 columns, each column has the following HTML class: ``ptp-three-col``.

The default CSS in this case looks like this:
`
.ptp-three-col {
	width: 31%;
}
`

Example code for changing your column width you can add to your theme:
`
.ptp-three-col {
width: 25%!important;
}
`

= Does this plugin use JavaScript? =
We're using a small jQuery library called (jquery.matchHeight.js)[https://github.com/liabru/jquery-match-height] for the "Automatically match Row Height" feature. This small library won't significantly impact load time, but please be aware that we are loading a JavaScript file if you enable "Automatically match Row Height".

== Screenshots ==
1. Example of a pricing table with 3 columns
2. Example of a pricing table with 2 columns
3. Creating a new table
4. Design options of free plugin
6. Additional settings of [Easy Pricing Tables Premium](https://fatcatapps.com/easypricingtables/?utm_campaign=screenshots%2Bcta&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral)
7. One of the designs from [Easy Pricing Tables Premium](https://fatcatapps.com/easypricingtables/?utm_campaign=screenshots%2Bcta&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral)
8. Another design from [Easy Pricing Tables Premium](https://fatcatapps.com/easypricingtables/?utm_campaign=screenshots%2Bcta&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral)
9. Another design from [Easy Pricing Tables Premium](https://fatcatapps.com/easypricingtables/?utm_campaign=screenshots%2Bcta&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral)
10. Another design from [Easy Pricing Tables Premium](https://fatcatapps.com/easypricingtables/?utm_campaign=screenshots%2Bcta&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral)
11. Another design from [Easy Pricing Tables Premium](https://fatcatapps.com/easypricingtables/?utm_campaign=screenshots%2Bcta&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral)
12. Another design from [Easy Pricing Tables Premium](https://fatcatapps.com/easypricingtables/?utm_campaign=screenshots%2Bcta&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral)
13. Another design from [Easy Pricing Tables Premium](https://fatcatapps.com/easypricingtables/?utm_campaign=screenshots%2Bcta&utm_source=https%3A%2F%2Fwordpress.org%2Fplugins%2Feasy-pricing-tables%2F&utm_medium=referral)

== Changelog ==

= Easy Pricing Tables 2.0 =
* Updated version to 2.0 to correspond with Easy Pricing Tables Premium
* Updated copy of Easy Pricing Tables Premium banner
* Updated anonymous tracking script
* Fixed error that occured when activating Easy Pricing Tables Premium when Easy Pricing Tables Lite was installed.

= Easy Pricing Tables 1.9.5.4 =
* Updated readme.txt

= Easy Pricing Tables 1.9.5.3 =
* Confirmed compatibility with WordPress 4.0.1

= Easy Pricing Tables 1.9.5 =
* Improvement: Empty rows will now automatically be hidden on smartphones
* Improvement: Changed mouse cursor from arrow to hand on button hover
* Improvement: Changed aesthetics (CSS) of the tabs in the Easy Pricing Tables editor
* Improvement: Made the pricing table columns in the Easy Pricing Tables editor wider
* Changed version number of Easy Pricing Tables to correspond to Easy Pricing Tables Premium
* Added Easy Pricing Tables - icon to media button

= Easy Pricing Tables 1.7.1 =
* Fixed CSS bug related to the "rounded pricing table corners" - feature
* Fixed a rare bug by adding jquey lib dependency after matchHeight lib
* Minor CSS change in the UI
* Add unique ID element to every call to action button

= Easy Pricing Tables 1.7 =
* Add Custom CSS Feature

= Easy Pricing Tables 1.6.1.1 =
* Tiny CSS fix (user interface)

= Easy Pricing Tables 1.6.1 =
* Small user interface improvements


= Easy Pricing Tables 1.6.0.3 =
* Confirmed compatibility with WordPress 4.0


= Easy Pricing Tables 1.6.0.2 =
* Confirmed compatibility with WordPress 3.9.2
* Updated to Drip API v2.0


= Easy Pricing Tables 1.6.0.1 =
* Fixed a minor issue that occured while deploying v1.6 that caused 'Automatically match Row Height' to not work.


= Easy Pricing Tables 1.6 =
* Automatically match Row Height - feature. For all newly created tables, all rows will now be forced to be of the same height using JS (you can activate/deactivate this in Design -> General Settings -> Automatically match Row Height)


= Easy Pricing Tables 1.5.5 =
* Fixed a JS bug (jquery-ui loaded in non easy-pricing-tables post-types)


= Easy Pricing Tables 1.5.4 =
* Improved the plugins' compatibility with shortcode-button generators
* Fixed a JS error in the Easy Pricing Tables editor
* Added the ability to use only one column
* Added 'template'-Tab


= Easy Pricing Tables 1.5.3 =
* Fixed a bug that added unnecessary CSS to some pages
* Confirmed compatibility with WordPress 3.9.1

= Easy Pricing Tables 1.5.2.1 =
* Updated links from http://easypricingtables.com to http://fatcatapps.com/

= Easy Pricing Tables 1.5.2 =
* Confirmed compatibility with WordPress 3.9
* Changed Easy Pricing Tables icon

= Easy Pricing Tables 1.5.1 =
* Bugfix: Unable to resize bullet font
* Bugfix: Preview didn't work for about 1% of sites
* Bugfix: "Pricing Table" menu disappears
* Improve HTML & CSS by removing a couple of !importants from my code
* Changes to email opt-in system
* Added Easy Pricing Tables Premium - preview

= Easy Pricing Tables 1.5.0.2 =
* Fixed incompatibility with netstudio-wp theme
* Added YouTube video link to readme.txt

= Easy Pricing Tables 1.5 =
* Easy Pricing Tables now supports translations (contact me if you'd like to submit a translation).
* Added Lithuanian language support. 
* Improved compatibility with legacy browsers (in particular old Internet Exploreer versions).
* Easy Pricing Tables is now compatible with http://wordpress.org/plugins/responsive-mobile-friendly-tooltip/.
* Small UI improvement: moved the "Pricing Tables" setting link in the left-hand navigation further down.

= Easy Pricing Tables 1.4.4 =
* UI improvements
* Added support for shortcode buttons like S2 member
* Fixed incompatibility with the Theme Foundry Basis theme
* Decreased file size of plugin
* Fixed CSS incompatibility with some themes
* Added an update notice

= Easy Pricing Tables 1.4.3 =
* Added the ability to clone existing tables
* Added row IDs to pricing table HTML (now you can change alternate row colors using CSS)
* Bugfixes
* Fixed CSS conflicts with some themes
* Minor UX improvements


= Easy Pricing Tables 1.4.2.2 =
* Fixed versioning issue

= Easy Pricing Tables 1.4.2.1 =
* Updated readme.txt (FAQ, screenshots, markdown formatting issues)

= Easy Pricing Tables 1.4.2 =
* Updated readme.txt
* Added sidebar link to Easy Pricing Tables Premium
* Fixed CSS conflict with Dynamik Genesis Child Theme
* Added ability to rename "Most Popular" Featured Label

= Easy Pricing Tables 1.4.1 =
* Fixed button-color bug introduced with version 1.4

= Easy Pricing Tables 1.4 =
* Bufix: Fixed CSS generation algorithm
* Fixed PHP 5.4 strict notices
* Improved compatibility with other plugins using the WPAlchemy framework
* UI improvements: Shortcodes can now be directly added via the editor
* After install, ask users if they want to allow usage tracking
* After install, ask users if they want to get a pricing-table mini-course
* Users can now change the text in the "most popular" label

= Easy Pricing Tables 1.3.2 =
* Improved CSS to cause less theme conflicts
* Various bugfixes
* Refactored table generation code
* UI improvements

= Easy Pricing Tables 1.3.1 =
* Minor Improvements

= Easy Pricing Tables 1.3 =
* Fixed incompatibility with s2 Member plugin
* Added font-size options
* Changed post-type 'public' => false
* Minor bugfixes
* Minor UI improvements

= Easy Pricing Tables 1.2.1 =
* Bugfix

= Easy Pricing Tables 1.2 =
* Added column drag & drop
* UI improvements
* Bugfixes
* Fixed CSS issues on the "Add new pricing tables"-page caused by WooThemes

= Easy Pricing Tables 1.1.2 =
* Fixed bug related to colorpicker introduced with version 1.1.0

= Easy Pricing Tables 1.1.1 =
* Fixed Woothems incompatibility

= Easy Pricing Tables 1.1.0 =
* Added new design options (colors & rounded corners)
* Added tabs to backend UI
* Replaced default-text with HTML5 placeholder
* Various bugfixes
* Added new screenshots
* Added banner for Wordpress directory

= Easy Pricing Tables 1.0.2.1 =
* Minor CSS Bugfix

= Easy Pricing Tables 1.0.2 =
* Fixed typos
* Improved responsive CSS
* Improved button design
* Changed design of highlighted column
* Bugfixes

= Easy Pricing Tables 1.0.1 =
* Improved readme.txt

= Easy Pricing Tables 1.0.0 =
* Initial release


== Upgrade Notice ==



`<?php code(); // goes in backticks ?>`
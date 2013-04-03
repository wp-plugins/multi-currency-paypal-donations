=== Multi Currency PayPal Donations ===
Contributors: Nick Verwymeren 
Donate link:http://nickandsarajane.com/donate
Tags: paypal, donation, donate, multi currency
Requires at least: 2.8
Tested up to: 3.5.1
Stable tag: 2.2.1

Receive PayPal donations through Wordpress in multiple currencies with the lowest possible fees.

== Description ==

<span style="font-family: 'Lucida Grande'; font-size: 11px; white-space: pre-wrap;">PayPal charges high fees for cross border transactions. If you are one of the fortunate few that have paypal accounts in multiple currencies then this plugin is for you. It allows you to route different currencies to specific paypal accounts. Even if you don't have multiple paypal accounts this is still a great plugin for accepting donations.</span>

== Installation ==

1. Unzip mcpd.zip file

2. Upload \`multi-currency-donations\` folder to the \`/wp-content/plugins/\` directory

3. Activate the plugin through the 'Plugins' menu in WordPress

4. To use place [paypalDonationForm] in your editor where you want the form to show up. The form needs a minimum width of 350px to display properly.
== Screenshots ==
1. screenshot-1.png

2. screenshot-2.png

== Changelog ==
= 2.2.1 =
* Removed boxover.js

= 2.2 =
* Removed PayPal IPN functionality (wasn't working anyways)
* Fixed problem with donate buttons styles not working
* Changed help hover to opentip
* Fixed Monthly and One-Time check option not saving properly
* Fixed a problem when nothing is in the monthly box it showed NaN

= 2.1.2 =
* bug fixes

= 2.0 =
* Much improved admin interface
* Donations are now tracked using paypals IPN
* Bug fixes

= 1.2 =
*minor bug fixes

= 1.1 =
* Added the ability to specify a default currency

= 1.0.3 =
* Added mcpd_manualDisplay() function so that you can add the form to a template file

= 1.0.2 =
* Fixed var_dump problem

= 1.0.1 =
* Fixed some include problems
* Fixed bad references to css
* Fixed some javascript errors

= 1.0 =
* Plugin release.

==Readme Generator== 

This Readme file was generated using <a href = 'http://sudarmuthu.com/wordpress/wp-readme'>wp-readme</a>, which generates readme files for WordPress Plugins.

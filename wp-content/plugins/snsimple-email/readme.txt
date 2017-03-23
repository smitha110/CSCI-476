=== Simple Email Form ===
Contributors: seannewby
Tags: email, contact, form, simple, reCAPTCHA, responsive
Requires at least: 4.1
Tested up to: 4.7
Stable tag: 2.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Simple Email Form creates a simple email contact form to your WordPress site.

== Description ==

Easily add a simple email contact form to your WordPress site.

= Features =

* Easily add the simple email form to a page or post using the shortcode "[simple-email-form]".
* Add simple email form to a template using the "simple-email-form" action.
* Customize the template by adding simple-email-form.php to your theme.
* Ability to display your email address above the form (optional, your email address is encoded with HTML entities if shown).
* [Google reCAPTCHA](https://www.google.com/recaptcha/intro/index.html) (optional).
* Responsive, so it looks good on any device.
* Client and server side validation.
* AJAX form submission.
* Scripts and Styles are minified and only loaded when used.

== Installation ==

= 1. Upload to your plugins folder, usually `wp-content/plugins/` =

The plugin is in the form of a directory called 'simple-email-form'.

= 2. Activate the plugin on the plugin screen. =

= 3. Change settings on the "Settings > Simple Email Form" options screen. =

== Screenshots ==

1. Simple Email Form options page.

2. Simple Email Form in action using the Twenty Sixteen theme.

== Changelog ==

= v1.0 =

* First Stable version

= v2.0 =

* Rewrote plugin to work with latest version of WordPress (4.6.1).
* Removed TinyMCE button to insert shortcode.
* Replaced captcha with Google reCAPTCHA v2.
* Added AJAX form submission.

= v2.0.1 =

* Fixed bugs with some translations (updated .pot).
* Added language code support for Google Recaptcha.
* Fixed bug with new lines in email message body.

= v2.0.2 =

* Fixed missing translation (updated .pot).
* Fixed email subject encoding issue.
* Added "simple-email-form-headers" filter.

* v2.0.3 =

* Changed text domain from "simple-email-form" to "snsimple-email" for easier WP translations.

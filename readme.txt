=== Responsive Subheader ===
Contributors: DMBarber
Donate link: http://dmbwebdesigns.com/donate
Tags: sub-header, responsive, front page, slider, page-title, featured image, editable, color-picker
Requires at least: 3.5
Tested up to: 4.1.1
Stable tag: 1.0.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily add new depth to your pages with an individualized sub-header display of your featured image and a custom post with slider for the Front page.

== Description ==

This plugin works best when you have set static pages for your Front and Posts pages.

Easily add a responsive subheader to your pages. There is a separate subheader for your Front page, created when you add a new 'Responsive Subheader' post and a second type for all other pages. Be sure to add a featured image to your pages if you want the subheader to display on them.

Features:
 - Custom, editable subheader on the Front page
 - Use a featured image as the subheader for all subpages
 - Attach multiple images to the Front page Responsive Subheader to make a slideshow
 - Choose your transition type for the slideshow
 - Choose your custom color for the subheader backgrounds
 - Each page will have its own unique subheader
 - The page title is displayed on all subpage subheaders


Create a new 'Responsive Subheader' and add a 'Featured Image' to it. The title is only for reference; it is not displayed on pages. Be sure to add a 'Featured Image' (at least 720px x 200px) to your pages. You can even add a featured image to the page you set as your 'Posts' page.

Be sure to choose a background color and the overlay style you will want displayed on your subheader. On your sub-pages (those not the Front page) the page title will be displayed over the colored background on the right side. The image you choose as the 'Featured Image' for the page will be displayed on the left side. This is a dynamic, responsive subheader that should display properly on all screens.

This plugin will add the option for your theme to use thumbnail images automatically. It will also add the image sizes it needs to the list of available sizes.

If you have not done so already, please, create a child theme for your current theme. Then copy the 'header.php' file into your child theme folder. Only edit the copied file - never alter your original theme files!

Once you have copied over the 'header.php' file to your child theme, open it up in a text editor and add the following code (including the opening and closing php tags) to the bottom of the file:

    <?php
    // Output of Responsive Subheader block
    if( class_exists( 'ResponsiveSubheader' ) ) {
      display_responsive_subheader_here();
    }
    ?>


-- How to use the Slider Feature on the Front Page --

Uncheck the setting to use the 'Featured Image' when you create your Responsive Subheader in the admin area.
Attach - do NOT insert - the images that you would like to use to your Responsive Subheader post. To attach without inserting - in the Edit Responsive Subheader screen - click on the 'Add Media' button. When the 'Insert Media' modal window opens select the 'Upload Files' option tab. Upload the files you would like to use for your slider - Do NOT click on 'Insert into post' button - just 'x' out of the modal window to close it.

Your attachment images will be automatically resized down to 800px x 432px for this plugin to use, but if you prefer, you may resize them manually before attaching. If your images are smaller than that size, please enlarge them before uploading. Images smaller than 800px x 432px may not display properly and cause problems. The software lacks the capability to resize smaller images to a larger format. This will need to be done manually before uploading.

Please Note: this plugin currently does not implement the ability to detach files from a post - not yet anyway. To do this, you will need to add another plugin or delete the image you uploaded as an attachment in the media library to detach it.

If you have a featured image set to your post, this will not be included as a slide in the slideshow. Unchecking the 'Use Featured Image' checkbox only tells the plugin to setup display mulitple image attachments as a slideshow, or, if you only have one other image attached to use that instead.

== Installation ==

There are two easy ways to install this plugin:

Download from the website:
1. Unzip the downloaded file and upload all the contents to your wp-content/plugins directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.

Install from WordPress:

1. Simply search for 'Responsive Sub-header' and install from your WordPress plugin page.
1. Activate the plugin through the 'Plugins' menu in WordPress.

Usage:
1. On your admin panel create a new Responsive Sub-header and add a featured image.
1. Add featured images to the pages you want to which you wish to add a sub-header
1. Edit your header.php file to add the tiny code snippet supplied with this plugin
1. Enjoy the magic!

== Frequently Asked Questions ==
Q: Is it easy to use?
A: Responsive Sub-header is extremely easy to use! Just add a small snippit of code
 to your header.php file and set your featured image. Thats all it takes to add new
depth you to your website.

Q: Do I need to format my pictures to a certain size?
A: You may if you wish, however, you do not need to.

== Changelog ==

= 1.0.0 =
 * Initial release.

= 1.0.4 =
 * Added un-install file
 * Fixed some minor issues with image sizing and margins
 * Removed white background from front page sub-header
 * Updated usage page and this readme files
 * Enhanced admin settings panel by making overlay selection more distinctive

== Upgrade Notice ==

= 1.0.0 =
 * Initial release

== Screenshots ==

1. Write a post and add a featured image to create a dazzling new front page sub-header.
2. Turn your boring old front page...
3. ... into an exciting, eye-catching, engaging front page!
4. Each sub-page utilizes its own featured image as a sub-header banner complete with page title.

== Upcoming Releases ==

In future updates I hope to:
 * Add additional features to the front-page sub-header such as background and font color settings.
 * Add a feature to easily attach and detach images from the Responsive Sub-header for the slider
 * Add more overlays
 * Add a manual cropping function for your sub-page attached images
 * Add translations


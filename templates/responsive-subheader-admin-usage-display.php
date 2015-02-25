<div class="wrap">

  <h2>Responsive Subheader Usage</h2>

  <p>This plugin works best when you have set static pages for your Front and Posts pages.</p>

  <p>Easily add a responsive subheader to your pages. There is a separate subheader for your Front page, created when you add a new 'Responsive Subheader' and a second type for all other pages. Be sure to add a featured image to your pages if you want the subheader to display on them.</p>

  <p>If you have not done so already, please, <strong><a href="http://codex.wordpress.org/Child_Themes" target="_blank"> create a child theme</a></strong> for your current theme. Then copy the 'header.php' file into your child theme folder. Only edit the copied file - <strong>never alter your original theme files!</strong></p>

  <p>Once you have copied over the 'header.php' file to your child theme, open it up in a text editor and add the following code (including the opening and closing php tags) to the bottom of the file:</p>
  <pre>
    &lt;&quest;php
    // Output of Responsive Subheader block
    if&#40; class_exists&#40; &apos;ResponsiveSubheader&apos; &#41; &#41; &#123;
      display_responsive_subheader_here&#40;&#41;&#59;
    &#125;
    &quest;&gt;
  </pre>

  <p>Create a new 'Responsive Subheader' and add a 'Featured Image' to it. And be sure to add a 'Featured Image' (at least 720px x 200px) to your pages. You can even add a featured image to the page you set as your 'Posts' page.</p>

  <p>Be sure to choose a background color and the overlay style you will want displayed on your subheader. On your sub-pages (those not the Front page) the page title will be displayed over a colored background of your choice on the right side. The image you choose as the 'Featured Image' for the page will be displayed on the left side. This is a dynamic, responsive subheader that should display properly on all screens.</p>

  <h3>How to use the Slider Feature on the Front Page</h3>
  <p>
    <ol>
      <li>Uncheck the setting to use the 'Featured Image' when you create your <a href="<?php echo admin_url(); ?>edit.php?post_type=responsive_subheader" rel="nofollow" title="Link to create a Responsive Subheader in the admin section">Responsive Subheader</a> in the admin area.</li>
      <li><strong>Attach</strong> - do NOT insert - the images that you would like to use to your Responsive Subheader post. To attach without inserting - in the Edit Responsive Subheader screen - click on the 'Add Media' button. When the 'Insert Media' modal window opens select the 'Upload Files' option tab. Upload the files you would like to use for your slider - Do NOT click on 'Insert into post' button - just 'x' out of the window to close it.</li>
      <li>Your attachment images will be automatically resized to 800px x 432px for this plugin to use, but if you prefer, you may resize them manually before attaching.</li>
    </ol>
  <span><strong>Please Note:</strong> this plugin currently does not implement the ability to detach files from a post - not yet anyway. To do this, you will need to add another plugin or delete the image in the media library.</span>
  </p>
</div>


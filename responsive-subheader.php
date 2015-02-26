<?php
/*
Plugin Name: Responsive Subheader
Plugin URI: http://dmbwebdesigns.com/wp/plugins?name=responsive-subheader
Description: A plugin to show a post's Featured Image as an editable block on the front-page and as a static block on all subpages of the website.
Version: 1.0.4
Author: Dennis M. Barber
Author URI: http://dmbwebdesigns.com/
License: GPLv2
Text Domain: responsivesubheader
*/
/*  Copyright 2015 Dennis M. Barber (email: dennis@dmbwebdesigns.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

	You may find a copy of the GNU General Public License at http://www.gnu.org/licenses/gpl.html
*/
/*  NivoSlider Copyright and License

    Copyright (c) 2010-2012 Dev7studios

    Permission is hereby granted, free of charge, to any person
    obtaining a copy of this software and associated documentation
    files (the "Software"), to deal in the Software without
    restriction, including without limitation the rights to use,
    copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the
    Software is furnished to do so, subject to the following
    conditions:

    The above copyright notice and this permission notice shall be
    included in all copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
    EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
    OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
    NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
    HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
    WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
    FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
    OTHER DEALINGS IN THE SOFTWARE.

 */

// Disable direct access
defined( 'ABSPATH' ) or die( 'No direct access allowed.' );

// Register activation
register_activation_hook(   __FILE__, array( 'ResponsiveSubheader', 'responsive_subheader_activate' ) );
// Register deactivation
register_deactivation_hook( __FILE__, array( 'ResponsiveSubheader', 'responsive_subheader_deactivate' ) );
// Register uninstall
register_uninstall_hook( __FILE__, array( 'ResponsiveSubheader', 'responsive_subheader_uninstall' ) );

if( !class_exists( 'ResponsiveSubheader' ) ) :

class ResponsiveSubheader
{
  /**
   * @var string
   */
  public $version = '1.0.3';

  /**
   * @var string
   */
  private
    $post
  ;

  /**
   * Setup ResponsvieSubheader class
   */
  public function __construct()
  {
    $this->add_actions();
    $this->define_constants();
    $this->add_image_helpers();

    // On plugin activation, flush the rules
    register_activation_hook(__FILE__, array( $this, 'responsive_subheader_flush') );
  }

  /**
   * Add actions to for class
   */
  private function add_actions()
  {
    add_action( 'init', array( $this, 'responsive_subheader_custom_post_type') );
    add_action( 'admin_init', array( $this, 'responsive_subheader_admin_init' ) );
    add_action( 'admin_menu', array( $this, 'add_responsvive_subheader_settings_menu' ) );
    add_action( 'save_post', array( $this, 'rs_featured_image_use_save_meta_box_data' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'responsive_subheader_enqueue_scripts' ) );
  }

  /**
   * Create class constants
   */
  private function define_constants()
  {
    if( !defined( 'RS_PLUGINS_URL' ) )  define( 'RS_PLUGINS_URL', plugins_url( '', __FILE__ )  );
    if( !defined( 'RS_DIRNAME' ) )      define( 'RS_DIRNAME', dirname( __FILE__ ) );
    if( !defined( 'RS_JS_URL' ) )       define( 'RS_JS_URL', RS_PLUGINS_URL . '/js/' );
    if( !defined( 'RS_STYLES' ) )       define( 'RS_STYLES', RS_PLUGINS_URL . '/css/' );
    if( !defined( 'RS_OVERLAYS' ) )     define( 'RS_OVERLAYS', RS_PLUGINS_URL . '/images/overlays/' );
    if( !defined( 'RS_PLACEHOLDERS' ) ) define( 'RS_PLACEHOLDERS', RS_PLUGINS_URL . '/images/placeholders/' );
    if( !defined( 'RS_TEMPLATES' ) )    define( 'RS_TEMPLATES', RS_DIRNAME . '/templates/' );
    if( !defined( 'RS_OVERLAY_PATH' ) ) define( 'RS_OVERLAY_PATH', plugin_dir_path( __FILE__ ).'images/overlays' );
  }

  /**
   * Check to make sure the current theme has post-thumbnail support and add it if
   * it is lacking. Also add two image sizes for the responsive subheader class to
   * use when creating the subheader(s).
   */
  public function add_image_helpers()
  {
    // Check for and add post thumbnails if not supported...
    if( !current_theme_supports( 'post-thumbnails' ) )
      add_theme_support( 'post-thumbnails' );

    // ...then add custom post-thumbnail size for sub-header thumbnail

    // Add post-thumbnail size to exact dimensions for sub-header
    add_image_size( 'page-sub-header', 800, 200, true );
    // Add post-thumbnail size to exact dimensions for front-page
    add_image_size( 'front-sub-header', 800, 432, true );
  }

  /**
   * Create Custom Post Type for subheader post output. This post type is the main
   * section of this plugin and should never be altered. Without this post type
   * the plugin will not function.
   */
  public function responsive_subheader_custom_post_type()
  {
    register_post_type( 'responsive_subheader',
      array(
        'labels' => array(
          'name'              => __( 'Responsive Subheaders' ),
          'singular_name'     => __( 'Responsive Subheader' ),
          'add_new'           => __( 'Add New', 'repsonsive subheader' ),
          'add_new_item'      => __( 'Add New Responsive Subheader' ),
          'new_item'          => __( 'New Responsive Subheader' ),
          'edit_item'         => __( 'Edit Responsive Subheader' ),
          'view_item'         => __( 'View Responsive Subheader' )
        ),
        'description'         => 'A post-type that is used on the frontpage to show some text and setup the featured image display.',
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 20.1,
        'has_archive'         => false,
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'exclude_from_search' => true,
        'menu_icon'           => 'dashicons-align-center'
      )
    );
  }

  /**
   * Flush the cache on activation to ensure the CPT is available
   */
  public function responsive_subheader_flush()
  {
    // Run the init
    $this->responsive_subheader_custom_post_type();

    // Now do the flush to make this CPT available
    flush_rewrite_rules();
  }

  /**
   * Create the admin settings menu and add the admin style sheet
   */
  public function add_responsvive_subheader_settings_menu()
  {
    // Add Admin Settings Page Menu Link
    add_submenu_page(
      'edit.php?post_type=responsive_subheader',
      'Responsive Subheader Image Settings',
      'Subheader Settings & Overlay Image',
      'manage_options',
      'rs-overlay-img-opts',
      array( $this, 'create_responsive_subheader_admin_page' )
    );

    // Add Usage Page Menu Link
    add_submenu_page(
      'edit.php?post_type=responsive_subheader',
      'Responsive Subheader Usage Details',
      'How To Use',
      'manage_options',
      'rs-usage-menu-link',
      array( $this, 'rs_admin_usage_display' )
    );

    // add admin stylesheet
    add_action( 'admin_enqueue_scripts', array( $this, 'responsive_subheader_admin_enqueue_scripts' ) );
  }

  /**
  * Register and add settings
  */
  public function responsive_subheader_admin_init()
  {
    register_setting( 'rs-overlay-img-opts', 'rs_overlay_img' );
    register_setting( 'rs-overlay-img-opts', 'rs_subheader_bg_color' );

    // Setting to choose Featured Image or alternate image(s) to be used on the front page
    add_meta_box( 'featured-image-use', 'Use Featured Image or Alternate', array( $this, 'rs_featured_image_use_meta_box' ), 'responsive_subheader', 'normal', 'low' );
    // Show the attachment images, if any, for the post to be used as a slideshow
    add_meta_box( 'responsive-subheader-attachment-images', 'Attached Images', array( $this, 'rs_admin_get_attachment_images'), 'responsive_subheader', 'normal', 'low' );
  }

  /**
   * Register and enqueue admin scripts
   */
  public function responsive_subheader_admin_enqueue_scripts()
  {
    // Regiser custom styles for Responsive Subheader
    wp_enqueue_style( 'responsive_subheader_admin_stylesheet',  RS_STYLES.'responsive-subheader-admin-stylesheet.css', array(), $this->version, 'all' );
    // Add the color picker to the admin settings page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-script', RS_JS_URL . 'rs-admin-controls.js', array( 'wp-color-picker' ), false, true );
  }

  /**
   * Admin page callback
   */
  public function create_responsive_subheader_admin_page()
  {
    // Check user permissions
    if( !current_user_can( 'manage_options' ) )
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

    // Render the settings template
    include( sprintf( "%s/responsive-subheader-admin-settings-page.php", RS_TEMPLATES ) );
  }

  /**
   * Register and enqueue template scripts
   */
  public function responsive_subheader_enqueue_scripts()
  {
    // Register style sheet for Responsive Subheader
    wp_register_style( 'responsive-subheader-style', RS_STYLES . 'responsive_subheader_stylesheet.css' );
    wp_enqueue_style( 'responsive-subheader-style' );

    // Register slider css for front page sliders
    wp_register_style( 'rs-nivo-style', RS_STYLES . 'nivo-slider.css');
    wp_enqueue_style( 'rs-nivo-style' );
  }

  /**
   * Make sure jQuery is available and then load the dependent slider scripts for the front-page
   */
  public function responsive_subheader_enqueue_slider_scripts()
  {
    // Make sure jQuery is available if not loaded by the parent theme
    wp_enqueue_script( 'jquery' );
    // Add the Nivo Slider script to the page footer after jquery is loaded
    wp_enqueue_script( 'rs-nivo-slider-js', RS_JS_URL . 'jquery.nivo.slider.pack.js', array( 'jquery' ), null, true );
    // Add the controls for the Nivo Slider
    wp_enqueue_script( 'rs-nivo-controls-js', RS_JS_URL . 'rs-nivo-controls.js', array( 'jquery', 'rs-nivo-slider-js' ), null, true );
  }

  /**
   * Make sure jQuery is available and then load the dependent slider scripts for the front-page
   */
  public function responsive_subheader_enqueue_subheader_scripts()
  {
    // Make sure jQuery is available if not loaded by the parent theme
    wp_enqueue_script( 'jquery' );
    // Load the necessary JavaScript for the subheader
    wp_enqueue_script( 'rs-subheader-scripts-js', RS_JS_URL . 'rs-subheader-scripts.js', array( 'jquery' ), null, true );
  }

  /**
   * Activate the plugin
   */
  public static function responsive_subheader_activate()
  {
    // Check permissions
    if( !current_user_can( 'activate_plugins' ) )
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "activate-plugin_{$plugin}" );
  }

  /**
   * Deactivate the plugin
   */
  public static function responsive_subheader_deactivate()
  {
    // Check permissions
    if( !current_user_can( 'activate_plugins' ) )
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "deactivate-plugin_{$plugin}" );
  }


  /**
   * Uninstall the plugin
   */
  protected function responsive_subheader_uninstall()
  {
    if( !current_user_can( 'activate_plugins' ) )
      wp_die( __( 'You do not have sufficient permissions to access this page.' ) );

    check_admin_referer('bulk-plugins');

    // Important: Check if the file is the one
    // that was registered during the uninstall hook.
    if ( __FILE__ !== WP_UNINSTALL_PLUGIN )
      return;

    // Render the settings template
    include( sprintf( "%s/uninstall.php", RS_DIRNAME ) );

  }

  /**
   * Shows a meta box so the user can disable the automatic use of the featured
   * image and instead use an alternate attached image or a slider from several
   * attached images
   *
   * @param WP_Post $post The object for the current post/page.
   */
  public function rs_featured_image_use_meta_box( )
  {

    global $post;

    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'rs_featured_image_use_nonce', 'rs_featured_image_use_meta_box_nonce' );

    /*
     * If this is the first time the post is created we want to have this setting checked
     * automatically, so check for the pagename first. Else we can use get_post_meta() to
     * retrieve an existing value from the database and use the value for the form.
     */
    if( pathinfo( $_SERVER['PHP_SELF'], PATHINFO_FILENAME) === 'post-new' )
      $iFeaturedImageValue = 1;
    else
      $iFeaturedImageValue = get_post_meta( $post->ID, '_rs_featured_image_use_value_key', true );

    $sEffect = get_post_meta($post->ID, '_rs_featured_image_slider_effect', true);

    // Select options for front-page slider effects
    $aEffects = array('sliceDown', 'sliceDownLeft', 'sliceUp', 'sliceUpLeft', 'sliceUpDown', 'sliceUpDownLeft', 'fold', 'fade', 'random', 'slideInRight', 'slideInLeft', 'boxRandom', 'boxRain', 'boxRainReverse', 'boxRainGrow', 'boxRainGrowReverse');

    // Set output
    $sMetaBoxDisplay  = '';
    // Use Featured Image checkbox
    $sMetaBoxDisplay .= '<p>' . __( 'To use an alternate attached image, or set of attached images as a slider, you must uncheck this setting:', 'responsivesubheader' ) . '</p>';
    $sMetaBoxDisplay .= '
      <input type="hidden" name="rs_use_featured_image" value="0" />
      <label for="rs_use_featured_image">
      <input type="checkbox" id="rs_use_featured_image" name="rs_use_featured_image" value="1"';
    $sMetaBoxDisplay .= ($iFeaturedImageValue == 1) ? ' checked />' : ' />' ; // end of input for rs_use_featured_image
    $sMetaBoxDisplay .= __( 'Use Featured Image', 'responsivesubheader' );
    $sMetaBoxDisplay .= '</label><br>';

    // Slider Effect Option
    $sMetaBoxDisplay .= '<p>' . __( 'If you are attaching alternate images and using the built in slider you may choose the type of effect you would like to use on your front-page slider here:', 'responsivesubheader' ) . '</p>';
    $sMetaBoxDisplay .= '<label for=""></label>';
    $sMetaBoxDisplay .= '<select id="_rs_featured_image_slider_effect" name="_rs_featured_image_slider_effect" class="" >';
    foreach($aEffects as $sVal)
    {
      $sMetaBoxDisplay .= '<option value="'.$sVal.'"';
      $sMetaBoxDisplay .= ($sVal === $sEffect) ? ' selected' : '';
      $sMetaBoxDisplay .= '>'.$sVal.'</option>';
    }
    $sMetaBoxDisplay .= '</select><br>';

    echo $sMetaBoxDisplay;
  }

  /**
   * Save the meta box selection when the post is saved.
   *
   * @param int $post_id The ID of the post being saved.
   */
  public function rs_featured_image_use_save_meta_box_data( $post_id )
  {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if ( ! isset( $_POST['rs_featured_image_use_meta_box_nonce'] ) )
      return;

    // Verify that the nonce is valid.
    if ( ! wp_verify_nonce( $_POST['rs_featured_image_use_meta_box_nonce'], 'rs_featured_image_use_nonce' ) )
      return;

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
      return;

    // Check the user's permissions.
    if ( ! current_user_can( 'edit_post', $post_id ) )
      return;

    // Make sure that values are set.
    if ( !isset( $_POST['rs_use_featured_image'] ) || !isset( $_POST['_rs_featured_image_slider_effect'] ) ) {
      return;
    }

    // Sanitize user input.
    $iFeaturedImageData = absint( $_POST['rs_use_featured_image'] );
    $sSliderEffectValue = sanitize_text_field( $_POST['_rs_featured_image_slider_effect'] );

    // Update the meta field(s) in the database.
    update_post_meta( $post_id, '_rs_featured_image_use_value_key', $iFeaturedImageData );
    update_post_meta( $post_id, '_rs_featured_image_slider_effect', $sSliderEffectValue );
  }

  /**
   * Get the attachment images attached to the current post
   *
   * @global object $post
   * @return html block of attachment images
   */
  public function rs_admin_get_attachment_images()
  {
    echo '<p>NOTE: If you have added a Featured Image to this post type, that will be included with the attachments.</p>';
    global $post;

    $images = $this->rs_get_images_from_post( $post->ID );

    foreach($images as $attachment)
      echo wp_get_attachment_image( $attachment->ID, 'thumbnail' );
  }

  /**
   *  Render the 'Usage' meta box on the responsive subheader post edit page
   */
  public function rs_admin_usage_display()
  {
    $this->rs_get_template(RS_TEMPLATES . 'responsive-subheader-admin-usage-display.php');
  }

  /**
   * Retrieve the template and insert the variables for output
   *
   * @param string $path_to_template_file the relative path to the template file from the function
   * @param array $template_parts elements used in the template for the subheader
   * @output html
   */
  private function rs_get_template( $path_to_template_file = '', $template_parts = array() )
  {
    // The $template_parts array is used inside the template
    ob_start();
    include( sprintf( $path_to_template_file ) );
    $display = ob_get_contents();
    ob_end_clean();

    echo $display;
  }

  /**
   * Initiate template parts array
   */
  private function rs_set_template_parts_array()
  {
    $parts = array();
    $parts['subheader_display']  = true;
    $parts['subheader_type']     = 'page';
    $parts['subheader_thumb']    = '<img src="' . RS_PLACEHOLDERS . 'placeholder_800x432.png' . '" alt="Your Featured Image from the Responsive Subheader goes here!" />';
    $parts['subheader_content']  = '<h1>Edit your Responsive Subheader to change this text!</h1>You will be so glad you did!';

    return $parts;
  }

  /**
   * Gets the information to use in the Responsive Subheader template by querying the necessary data
   *
   * @global object $post
   * @output html function calls the template file which outputs the preformatted html
   */
  public function responsive_subheader_display()
  {
    if( post_type_exists( 'responsive_subheader' ) )
    {
      // Set the variables to display plugin output in case there is not post written yet
      $template_parts = $this->rs_set_template_parts_array();

      // Check if is frontpage
      if( is_front_page() && is_page() )
      {
        // Set the template to use
        $template_parts['subheader_type'] = 'frontpage';

        // Get the post type and only show the latest post
        $args = array( 'post_type' => 'responsive_subheader', 'posts_per_page' => 1 );

        $loop = new WP_Query( $args );

        if( $loop->post_count > 0 )
        {
          // Set the post content
          $post = $loop->post;

          // Get post meta data
          $aPostMeta = get_post_meta( $post->ID, '', false );

          // Set slider effect
          if(isset($aPostMeta['_rs_featured_image_slider_effect'][0]))
            $template_parts['slider_effect'] = (string) ( isset( $aPostMeta['_rs_featured_image_slider_effect'][0] ) ) ? $aPostMeta['_rs_featured_image_slider_effect'][0] : 'boxRain' ;

          // Display featured image or alternate
          if(isset($aPostMeta['_rs_featured_image_use_value_key'][0]))
            $bUseFeatured = $aPostMeta['_rs_featured_image_use_value_key'][0];
          else
            $bUseFeatured = false;

          // Set the contents for the text to display next to the front-page thumbnail/slider
          $template_parts['subheader_content'] = $post->post_content;

          // Get the post thumbnail
          if( has_post_thumbnail( $post->ID ) && (bool) $bUseFeatured )
          {
            $template_parts['subheader_thumb'] = get_the_post_thumbnail( $post->ID, 'full' );
          }
          else
          {
            // Get attached images from responsive_subheader post and return preformatted
            $images = $this->rs_make_image_html_wrapper( $this->rs_get_images_from_post($post->ID) );

            // Check the $images array is set setup the display type
            if(isset($images) && count( $images ) && $images['type'] === 'single' ) // show single image
              $template_parts['subheader_thumb'] = $images['html'];
            elseif(isset($images) &&  count( $images ) && $images['type'] === 'slider' ) // show images as a slider
            {
              // Insert slider style and javascript script
              $this->responsive_subheader_enqueue_slider_scripts();
              // set image slider html as the thumbnail to display
              $template_parts['subheader_thumb'] = $images['html'];
            }
            else // show "empty" image in browser
              $template_parts['subheader_thumb'] = '<img src="" alt="No image to display" width="800" height="432" />';
          }
        }

        // Restore Original Post Data
        wp_reset_postdata();
      }
      elseif( is_page() && !is_front_page() ) // subpage setup
      {
        // Set the post_title variable
        $template_parts['post_title'] = '';

        // Get current post (page)
        global $post;

        // Get the title/parent title of the page to display in the sub-header
        if( !empty( $post->post_parent ) ) // If parent title exists, use it...
          $template_parts['post_title'] = get_the_title( $post->post_parent );
        else if( !empty( $post->post_title ) ) // ...else use current title...
          $template_parts['post_title'] = $post->post_title;

        // Get the thumbnail
        if( has_post_thumbnail() )
          $template_parts['subheader_thumb'] = get_the_post_thumbnail( $post->ID, 'page-sub-header' );
        else // show the placeholder
          $template_parts['subheader_thumb'] = '<img src="' . RS_PLACEHOLDERS . 'placeholder_subpage.png' . '" alt="Your Featured Image from the Responsive Subheader goes here!" />';

        // Load the JavaScript file
        if( isset( $template_parts['subheader_thumb'] ) )
          $this->responsive_subheader_enqueue_subheader_scripts();

      } // end if is_page and is the static blog page
      elseif( ( is_home() && !is_front_page() ) )
      {
        /**
         * This is slightly different. If we use global $post here we end up with the
         * data for the last post instead of for the page. We have to make sure we are
         * using the ID for the static blog page.
         */
        $blog = get_post( get_option( 'page_for_posts' ) );

        if( $blog->post_type === 'page' )
        {
          // Set the post content
          $template_parts['post_title'] = $blog->post_title;

          // Get the post thumbnail
          if( has_post_thumbnail( $blog->ID ) )
            $template_parts['subheader_thumb'] = get_the_post_thumbnail( $blog->ID, 'page-sub-header' );
          else
            $template_parts['subheader_display'] = false; // no thumbnail = no display
        }
      }
      elseif( is_single() || is_archive() || is_category() || is_tag() || is_author() || is_tax() || is_404() || is_time() || is_date() || is_year() || is_month() || is_day() || is_new_day() || is_attachment() || is_feed() ) // do not show subheader on these pages
      {
        $template_parts['subheader_display'] = false;
      }

      // Display Template
      $this->rs_get_template( RS_TEMPLATES . "responsive-subheader-template.php", $template_parts );

    }
    else
      return; // post type not defined!
  }

  /**
   * Get a preformatted html wrapped image(s) to displa on the front page as
   * either a static image or a slider using Nivo Slider Javascript.
   *
   * @param int $post_id the $post-ID to be used to get image attachments
   * @return array Type of image set being returned ('single' or 'slider') and
   * the pre-formatted html wrapping of the image attachements
   */
  private function rs_get_images_from_post( $post_id )
  {
    $args = array(
      'post_parent'     => $post_id,      // post id
      'post_type'       => 'attachment',  // get attached, not inserted, images
      'orderby'         => 'name',        // set the order to display the images
      'order'           => 'ASC',         // from a to z
      'numberposts'     => 5,             // only add upto the first 5 images
      'post_mime_type'  => 'image',       // set the type (do not change this! ...yet)
    );
    // Get the attached images
    $images = get_children( $args );

    return $images;
  }

  /**
   * Format the images to be used in the slider
   *
   * @param array $images
   * @return html sets up the images to be used by the Nivo Image slider jQuery plugin
   */
  private function rs_make_image_html_wrapper( $images )
  {
    $return = array();
    if(!empty($images) && count( $images ) > 1 )
    {
      $return['type'] = 'slider';
      $return['html'] = '
        <div class="slider-wrapper theme-default">
          <div class="ribbon"></div>
          <div id="slider" class="nivoSlider rs_slider">';
      foreach( $images as $image)
        $return['html'] .= wp_get_attachment_image( $image->ID, 'front-sub-header' );
      $return['html'] .= '
          </div>
        </div>';
    }
    elseif(isset($images->ID))
    {
      $return['type'] = 'single';
      $return['html'] = wp_get_attachment_image( $image->ID, 'front-sub-header' );
    }

    return $return;
  }

  /**
   * Get the chosen overlay image file if it exists
   */
  public function rs_get_overlay_img_option()
  {
    return get_option( 'rs_overlay_img' );
  }

  /**
   * Get the subheader background color if it exists
   */
  public function rs_get_subheader_bg_color()
  {
    return get_option( 'rs_subheader_bg_color' );
  }

  /**
   * Get all image overlay file names for use on the admin settings form
   */
  public function rs_get_imagefiles_from_dir()
  {
    $aImages = array();
    // Get image file names
    if( is_string( RS_OVERLAY_PATH ) )
      $aImages = $this->rs_parse_image_filenames( scandir( RS_OVERLAY_PATH ) );

    return $aImages;
  }

  /**
   * Return an array of only image file names from the directory where they are
   * stored.
   *
   * @param array $aFiles array of filenames to be checked for image types
   */
  private function rs_parse_image_filenames( $aFiles )
  {
    $aNewFiles = array();
    $aAllowedTypes = array(
      'gif',
      'png',
      'jpg',
      'jpeg',
      'tiff',
    );
    if(is_array($aFiles))
    {
      foreach($aFiles as $aFile)
      {
        $aParts = pathinfo($aFile);
        if(in_array($aParts['extension'], $aAllowedTypes))
          $aNewFiles[] = $aFile;
      }

      return $aNewFiles;
    }
    else
      return;
  }
}

endif;

// Instantiate the class
if(class_exists( 'ResponsiveSubheader' ) )
{
  $responsive_subheader = new ResponsiveSubheader();

  /**
   * Display the subheader on pages
   *
   * @global class ResponsiveSubheader $responsive_subheader
   * @return html formatted output of the subheader image(s)
   */
  function display_responsive_subheader_here()
  {
    global $responsive_subheader;
    return $responsive_subheader->responsive_subheader_display();
  }

}

<?php
/**
 * Create Responsive Subheader template for html output
 */

if( $template_parts['subheader_display'] === true )
{
  switch($template_parts['subheader_type'])
  {
    /**
     * HTML template for responsive subheader output for frontpage
     */
    case 'frontpage':
      $html = '
          <div id="sub-header-wrapper">

            <div class="sub-header-container">

              <span class="headpost-text-column">'.$template_parts['subheader_content'].'</span>

              <div class="headpost-thumb-column">'

                . $template_parts['subheader_thumb'] .

              '</div>

            </div><!-- sub-header-container -->

          </div><!-- sub-header-wrapper -->
          <script>
          var NivoOptions = {
              sliderEffect: \'';
      $html .= ( isset( $template_parts['slider_effect'] ) ) ? $template_parts['slider_effect'] : "boxRain";
      $html .= '\'
          };
          </script>';

      echo $html;
    break;

    /**
     * HTML template for responsive subheader output for subpage
     */
    case 'page':
      $sOverlay = RS_OVERLAYS . get_option( 'rs_overlay_img' );
      $sColor = get_option( 'rs_subheader_bg_color' );
      $html = '
        <div id="sub-header-wrapper">

          <div id="sub-header" class="banner-container"';
      $html .= ( !empty( $sColor ) ) ? ' style="background-color:'.$sColor.'">' : '>';
      $html .= '
            <div id="sub-header-banner-container">

            ' . $template_parts['subheader_thumb'] . '

            </div><!-- #sub-header-banner-container -->

            <div id="banner-overlay-container" class="visible-overlay">

              <img src="' . $sOverlay . '" alt="banner overlay" />

            </div><!-- #banner-overlay-container -->

            <h2 class="page-title visible-title';
      $html .= ( !empty( $sColor ) ) ? ' rs-title-has-color" data-title-bgcolor="'.$sColor.'">' : '">';
      $html .= $template_parts['post_title'] . '</h2>

          </div><!-- #sub-header -->

        </div><!-- #sub-header-wrapper -->';

      echo $html;
    break;

    default:
      break;
  }
}


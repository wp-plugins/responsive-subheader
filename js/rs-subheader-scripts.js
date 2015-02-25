jQuery(document).ready(function() {
  var sTitleBgColor = jQuery('.rs-title-has-color').data( "titleBgcolor" );
  if(sTitleBgColor != undefined)
    jQuery('.rs-title-has-color').css('background-color', sTitleBgColor);
});

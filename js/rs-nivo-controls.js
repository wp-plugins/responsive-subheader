jQuery(window).load(function(){
	jQuery('#slider').nivoSlider({
		effect: NivoOptions.sliderEffect, // slider effects see list below
    boxCols: 16,                      // for box animations
    boxRows: 8,                       // for box animations
		animSpeed: 500,                   // transition speed
		pauseTime: 4500,                  // how long each slide will show
		directionNav: true,               // Next & Prev navigation
		controlNav: true,                 // 1,2,3... navigation
		pauseOnHover: true,               // stop animation while hovering
		manualAdvance: false,             // force manual transitions
 		prevText: '<',                    // Prev directionNav text
		nextText: '>',                    // Next directionNav text
		randomStart: false                // start on a random slide
	});
});
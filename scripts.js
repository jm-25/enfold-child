/* ----------------------------------------------- */
/* Sticky Footer */
/* ----------------------------------------------- */
var $content = jQuery("#content-wrapper"),
		$footer = jQuery("#footer"),
		$socket = jQuery("#socket"),
   	$header = jQuery("#header");

jQuery(window).on('load', function () {
	positionFooter()  
});


jQuery(window).on('resize', function () {
	positionFooter()    
});



function positionFooter() {
	$content.css({"min-height": "initial"});
	var $windowHeight = jQuery(window).height(),
			$adminBarHeight = jQuery('#wpadminbar').outerHeight(),
			$headerHeight = $header.outerHeight(),
			$footerHeight = $footer.outerHeight() + $socket.outerHeight();
			
	if ($windowHeight > (jQuery('#main').outerHeight() + Number($adminBarHeight) + Number($headerHeight)) ){
		if (jQuery('#wpadminbar').length){
			contentHeight = $windowHeight - ($adminBarHeight + $headerHeight + $footerHeight );
		}else{
			contentHeight = $windowHeight - ($headerHeight + $footerHeight);
		}
			$content.css({"min-height": contentHeight + "px"});

	}
}
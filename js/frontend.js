(function( $ ) {

	$(".ContentWidgetSlideshow > li:gt(0)").hide();

	setInterval(function() {
	  $('.ContentWidgetSlideshow > li:first')
		.fadeOut(1000)
		.next()
		.fadeIn(1000)
		.end()
		.appendTo('.ContentWidgetSlideshow');
	}, 3000);
	
})( jQuery )	
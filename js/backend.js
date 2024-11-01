(function( $ ) {

	$(".contentWidgetStyleToggler a").click(function(e){
		e.preventDefault();
		$(".ContentWidgetStyle").slideToggle();
	});
	
	$(document).on('widget-updated', function (e) {
		$(".contentWidgetStyleToggler a").click(function(e){
			e.preventDefault();
			$(".ContentWidgetStyle").slideToggle();
		});
	});
	
})( jQuery )
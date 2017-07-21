
(function($){
	var $countdown = $("#countdown"),
		$countdowns = $(".giveasap_countdown");

	if( $countdown.length ) {
		var $timestamp = $countdown.attr("data-end");
		var $timezone = $countdown.attr("data-timezone");
		$countdown.countdown({ 
    		until: new Date($timestamp * 1000), format: 'dHMS'});
	}

	if( $countdowns.length ) {
		$countdowns.each(function(){
			var $timestamp = $(this).attr("data-end");
			var $timezone = $(this).attr("data-timezone");
			$(this).countdown({ 
	    		until: new Date($timestamp * 1000), format: 'dHMS'});
		});
	} 
	

 	$("#giveasap_show_rules").on( 'click', function(){
 		$(".giveasap_rules_extended").toggleClass("active");
 	});
})(jQuery);
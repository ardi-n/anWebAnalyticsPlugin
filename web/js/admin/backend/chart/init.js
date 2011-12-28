/**
 * render charts for admin homepage
 */
;(function($) {
	
	
	$('.wa-chart-holder').each(function(i, holder) {
		
		var $chartContainer = $(holder).parent();
		
		$(holder).waChart({});
		
		$(holder).waChart('setDataTable', $chartContainer.find('.wa-chart-data'));
		
		$(holder).waChart('draw', parseInt(0.95 * $chartContainer.width()), 300);
	});


})(jQuery);
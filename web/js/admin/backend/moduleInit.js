;(function($) {
	
	$('[data-filters-loaded-listener]').bind('filters_loaded.webanalytics', function(e, $box) {
		
		var $datetimepickers = $box.find('.datepicker_me');
		
		var date = new Date();
		
		$datetimepickers.datetimepicker({
			dateFormat:		'yy-mm-dd',
			defaultDate:	date,
			maxDate:		date
		});
	});


	
	$('input.sf_admin_field_autocomplete').live('mouseover.webanalytics', function() {
		
		var autocompleteField = this;
		
		$(this).autocomplete({
			source:		$(this).attr('data-autocomplete-href'),
			minLength:	3,
			select:		function(event, ui) {
				
				var $realField = $('#' + $(autocompleteField).attr('rel'));
				
				$realField.val(ui.item ? ui.item.id : '');
			}
		});
	});
	
	
	$('.sf_admin_filter .sf_admin_field_reload_filters').live('change.webanalytics', function() {
		
		var $filtersBox = $('.dm_filter_box');
		
		$filtersBox.block();
		
		$.ajax({
			url:		$filtersBox.attr('data-load-url'),
			data:		{ type: this.value },
			success:	function(data) {
				
				$filtersBox.html(data);
			},
			complete:	function() {
				$filtersBox.unblock();
			}
		});
	});
	
})(jQuery);
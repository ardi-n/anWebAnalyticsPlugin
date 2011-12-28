(function($)
{

	/*
	 * EXTEND ADMIN CTRL
	 */
  $.dm.ctrl = $.extend($.dm.ctrl, {
	  
	  filters: function()
	    {
	      var self = this, $box = self.$.find('div.dm_filter_box');
	      
	      self.$.find('a.dm_open_filter_box').click(function()
	      {
	        $box.slideToggle(200);
	      })
	      .bind('mouseover', function()
	      {
	        !$box.hasClass('loaded') && $box.addClass('loaded').load($box.attr('data-load-url'), function()
	        {
	        	$('[data-filters-loaded-listener]').trigger('filters_loaded.webanalytics', [$box]);
	        });
	      });
	    }
  });
  
})(jQuery);
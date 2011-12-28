(function($) {

$.widget('ui.waPage', {

  _init : function()
  {
    this.initialize();
  },
  
  initialize:			function() {
	  
	  this._fetchConfiguredElements();
	  
	  this.onUnload();
  },
  
  _fetchConfiguredElements:	function() {
	  
	  var widget = this;
	  
	  widget.configuredElements = [];
	  
	  $.ajax({
		  url:		widget.options.get_page_elements_href,
		  data:		{ layout_id: widget.options.layout_id },
		  dataType:	'json',
		  success:	function(data) {
			  widget.initializeConfiguredElements(data);
		  }
	  });
  },
  
  initializeConfiguredElements:	function(data) {
	  
	  var widget = this;
	  
	  $.each(data, function(id, elemData) {
		  
		 var $elem = $('#'+id);
		 $elem.waPageElement($.extend(widget.options, elemData));
		 widget.configuredElements.push($elem[0]);
		 
		 console.log(id, $('#'+id), widget.configuredElements.length);
	  });
  },
  
  
  onUnload:		function() {
	  
	  var widget = this;
	  
	  $(window).bind('unload.webanalytics', function() {
		  
		  var data = {};
		  
		  $.each(widget.configuredElements, function(k, elem) {
			  alert($(elem).attr('id'));
			  data[$(elem).attr('id')] = $(elem).waPageElement('getEvents');
		  });
		  
		  data = { wa: { events: data, page_id: widget.options.page_id, page_module: widget.options.page_module, layout_id: widget.options.layout_id } };
		  
		  alert($.param(data));
		  $.ajax({
			  url:			widget.options.collect_href,
			  data:			data,
			  type:			'post'
		  });
	  });
  }

});

})(jQuery);

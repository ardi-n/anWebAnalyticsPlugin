(function($) {

$.widget('ui.waPage', {

  _init : function()
  {
    this.initialize();
  },
  
  initialize:			function() {
	  
	  this.$pageElements = $(':visible[id]').not('.dm_widget').not('.dm_zone');
	  this.$elementDescriptor = $('<div class="wa-descriptor"><a href="#">Edit</a><span></span></div>');
	  
	  $('body').append(this.$elementDescriptor);
	  
	  this._fetchConfiguredElements();
	  
	  this.initializeElements();
  },
  
  _fetchConfiguredElements:	function() {
	  
	  var widget = this;
	  
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
	  
	  $.each(data, function(id, elemData) {
		  
		  $('#'+id).addClass('wa-configured-page-element');
	  });
  },
  
  initializeElements:	function() {
	  
	  var 
	  	descriptorTimeout,
	  	widget = this;
	  
	  /*
	   * init jQuery UI Widget
	   */
	  this.$pageElements.waPageElement(widget.options);
	  
	  this.$pageElements.bind('mouseenter.webanalytics', function(e) {
			
		e.stopPropagation();
		widget.$elementDescriptor.hide();
		if (descriptorTimeout) clearTimeout(descriptorTimeout);
		
		var element = this,
			elementOffset = $(element).offset();
		
		
		widget.$pageElements.removeClass('wa-selected');
		$(element).addClass('wa-selected');
		
		widget.$elementDescriptor.css('left', e.pageX);
		widget.$elementDescriptor.css('top', e.pageY);
		
		var urlParams = {
				id:		$(element).attr('id')
		};
		
		var $editTrigger = widget.$elementDescriptor.find('a');
		$editTrigger.attr('href', widget.options.edit_page_element_href + '?' + $.param(urlParams));
		$editTrigger.unbind('click');
		$editTrigger.bind('click', function(e) {
			$(element).waPageElement('openEditDialog');
			e.preventDefault();
		});
		widget.$elementDescriptor.find('span').text($(element).waPageElement('describe'));
		
		descriptorTimeout = setTimeout(function() {
			widget.$elementDescriptor.fadeIn();
		}, 1000);
		
	});
  }

});

})(jQuery);
  
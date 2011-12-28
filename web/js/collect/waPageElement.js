(function($) {
  
$.widget('ui.waPageElement', {

  _init : function()
  {
	this.events = [];
	  
	this.is_displayed = false;
	
    this.initialize();

    this.element.data('loaded', true);
  },
  
  initialize:	function() {
	  
	  var widget = this;
	  
	  $.each(this.options.settings, function(key, value) {
		  
		  if (key.indexOf('AnWebAnalytics') == 0 && value) {
			  
			  var eventType = key.replace('AnWebAnalytics', '').replace('Event', '');
			  
			  if (typeof widget['_handle'+eventType] != 'undefined') {
				  
				  widget['_handle'+eventType]();
			  }
		  }
	  });
  },
  
  
  _handleImpression:	function() {
	  
	  var widget = this;
	  
	  $(window).bind('scroll.webanalytics', function() {
		  
		  
		  if ((! widget.is_displayed) && widget.element.is(':visible') && widget._isScrolledIntoView()) {
			  
			  widget.is_displayed = true;
			  widget.events.push({
				  type:					'impression',
				  created_at:			Math.floor(new Date().getTime() / 1000)
			  });
		  }
	  });
  },
  /**
   * helper function to decide whether an element is in viewport
   */
  _isScrolledIntoView:	function() {
	  
    var elem = this.element,
    	docViewTop = $(window).scrollTop(),
        docViewBottom = docViewTop + $(window).height(),
        elemTop = $(elem).offset().top,
     elemBottom = elemTop + $(elem).height();
    //Is more than half of the element visible
    return ((elemTop + ((elemBottom - elemTop)/2)) >= docViewTop && ((elemTop + ((elemBottom - elemTop)/2)) <= docViewBottom));
  },
  
  
  _handleClick:		function() {
	  
	  var widget = this;
	  
	  widget.element.bind('click.webanalytics', function(e) {
		  
		  widget.events.push({
			  type:		'click',
			  page_x:	e.pageX,
			  page_y:	e.pageY,
			  created_at:			Math.floor(new Date().getTime() / 1000)
		  });
		  
	  });
  },
  
  
  _handleTextSelect:	function() {
	  
	  var widget = this;
	  
	  this.element.bind('mouseup.webanalytics', function(e) {
		  
		  var 
			t = '';
		
		  if(window.getSelection){
		    t = window.getSelection().toString();
		  }else if(document.getSelection){
		    t = document.getSelection();
		  }else if(document.selection){
		    t = document.selection.createRange().text;
		  }
		  
		  if (t.length > 0) {
			  
			  widget.events.push({
				  type:					'textselect',
				  selected_text:		t,
				  created_at:			Math.floor(new Date().getTime() / 1000)
			  });
		  }
	  });
  },
  
  
  _handleType:		function() {
	  
	  var widget = this;
	  
	  this.element.filter('input[type=text], textarea').bind('focusout.webanalytics', function(e) {
		    
		  var
			typedText = $(this).val();
		  
		  if (typedText.length > 0) {
				
			widget.events.push({
				type:			'type',
				typed_text:		typedText,
				created_at:			Math.floor(new Date().getTime() / 1000)
			});
		  }
		  
	  });
	  
  },
  
  
  getEvents:		function() {
	  
	  return this.events;
  }
  
});

})(jQuery);
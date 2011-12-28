(function($) {

$.widget('ui.waChart', {

	
  options:	{
	  width:	800,
	  height:	250
  },
  
  /**
   * holds ref to the plot object
   */
  plot:	null,
	
  _init : function()
  {
    this.initialize();
  },
  
  
  initialize:	function() {
	  
	  
	  this.previousPoint = null;
	  this.latestPosition = null;
	  
	  var widget = this;
	  
	  $(window).resize(function() {
		  
		  widget.draw(parseInt(0.95 * $(widget.element).parent().width()), 300);
	  });
  },
  
 
  
  
  setDataTable:	function($table) {
	  
	  var widget = this;
		this.labels = [];
		this.longLabels = [];
	    this.data = [];
		  
		$table.find('th').each(function (k) {
			widget.labels.push([k, $(this).html()]);
			if ($(this).attr('data-long-label')) {
				widget.longLabels.push([k, $(this).attr('data-long-label')]);
			}
	  	});
		$table.find('td').each(function (k) {
			widget.data.push([k, $(this).html()]);
	  	});
		
		$table.css({
	        position: "absolute",
	        left: "-9999em",
	        top: "-9999em"
	    });
  },
  
  
  
  draw:			function(width, height) {
	  
	  $(this.element).width(width);
	  $(this.element).height(height);
	  
	  var
	  	widget = this,
	  	showPoints = this.data.length / width < 0.1;
	  
	  this.plot = $.plot(this.element, [
                        	{
                        		data: this.data
                        	}
                        ],
                        {
	  						series:	{
	  							lines: { show: true },
	  							points: { show: showPoints }
	  						},
	  						crosshair:	{ mode: showPoints ? '' : 'x' },
	  						xaxis:	{
	  							ticks:	this.labels
	  						},
	  						grid:	{ hoverable: true, clickable: true }
                        });
	  
	  if (showPoints)
		  $(this.element).bind("plothover", $.proxy(this, '_showTooltipForPoints'));
	  else
		  $(this.element).bind("plothover", function(e, pos, item) {
			  widget.latestPosition = pos;
			  if (! widget.showTooltipForCrosshairTimeout) {
				  
				  widget.showTooltipForCrosshairTimeout = setTimeout(function() { widget._showTooltipForCrosshair(); }, 50);
			  }
		  });
  },
  
  _doShowTooltip:	function(x, y, contents) {
	  
	  var $tooltip = $('#wa-chart-tooltip');
	  
	  if (! $tooltip.length) {
	  
		  $('<div id="wa-chart-tooltip">' + contents + '</div>').css( {
			  position: 'absolute',
			  display: 'none',
			  top: y + 5,
			  left: x + 5,
			  border: '1px solid #fdd',
			  padding: '2px',
			  'background-color': '#fee',
			  opacity: 0.80
		  }).appendTo("body").fadeIn(200);
		  
	  }
	  else {
		  $tooltip.html(contents).css({
			  top: y+5,
			  left: x+5
		  });
	  }
  },
  
  _showTooltipForPoints:	function(e, pos, item) {
	  
	     
	  
	if (item) {
		if (this.previousPoint != item.dataIndex) {
			  
		  this.previousPoint = item.dataIndex;
		  $("#wa-chart-tooltip").remove();
		  var 
		  	x = item.datapoint[0].toFixed(2),
		  	y = item.datapoint[1].toFixed(2);
		  
		  this._doShowTooltip(item.pageX, item.pageY, this.longLabels[parseInt(x)][1]);
		}
	}
	else {
		$("#wa-chart-tooltip").remove();
		this.previousPoint = null;
	}
  },
  
  
  _showTooltipForCrosshair:		function() {
	  
	  var pos = this.latestPosition;
	  
	  this.showTooltipForCrosshairTimeout = null;
	  
	  var axes = this.plot.getAxes();
	  
	  if (pos.x < axes.xaxis.min || pos.x > axes.xaxis.max ||
			  pos.y < axes.yaxis.min || pos.y > axes.yaxis.max) {
		  $('#wa-chart-tooltip').remove();
		  return;
	  }
	  
	  var i, j, dataset = this.plot.getData();
	  
	  for (i=0; i<dataset.length; i++) {
		  
		  var series = dataset[i];
		  
		  // find the nearest points, x-wise
		  for (j=0; j<series.data.length; ++j) {
			  
			  if (series.data[j][0] > pos.x) break;
			  
			  //now interpolate
			  var y, p1 = series.data[j-1], p2 = series.data[j];
			  if (p1 == null)
				  y = p2[1];
			  else if (p2 == null)
				  y = p1[1];
			  else
				  y = p1[1] + (p2[1] - p1[1]) * (pos.x - p1[0]) / (p2[0] - p1[0]);

			  this._doShowTooltip(pos.pageX, pos.pageY, this.longLabels[parseInt(pos.x)][1]);
		  }
	  }
  }
  
  

});

})(jQuery);
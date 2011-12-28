(function($) {
  
$.widget('ui.waPageElement', {

  _init : function()
  {
    this.initialize();

    this.element.data('loaded', true);
  },
  
  
  
  describe:		function() {
	  
	  var elem = this.element;
	  
	  elemName = $(elem).get(0).tagName;
	  if ($(elem).attr('id')) {
			elemName += '[id=' + $(elem).attr('id') + ']';
	  }
	  if ($(elem).attr('name')) {
		  elemName += '[name=' + $(elem).attr('name') + ']';
	  }
	  if ($(elem).attr('type')) {
		  elemName += '[type=' + $(elem).attr('type') + ']';
	  }
		
	  return elemName;
  },
  
  

  openEditDialog: function()
  {
    var widget = this, restoreState = {}, dialogClass = widget.element.attr('id')+'_edit_dialog';

    $.dm.removeTipsy();

    if ($('body > div.'+dialogClass).length)
    {
      $('body > div.'+dialogClass).find('div.ui-dialog-content').dialog('moveToTop');
      return;
    }
    
    var is_input =  widget.element.is('textarea, input[type=text], input[type=email]');
    
    var $dialog = $.dm.ctrl.ajaxDialog({
      url:          widget.options.edit_page_element_href,
      data:         {dom_id: widget.getId(), is_input: is_input, layout_id: widget.options.layout_id, page_id: widget.options.page_id, page_module: widget.options.page_module},
      title:        $('a.wa_page_element_edit', widget.element).tipsyTitle(),
      width:        600,
      'class':      'wa_page_element_edit_dialog_wrap '+dialogClass,
      resizable:    true,
      resize:       function(event, ui)
      {
        $dialog.maximizeContent('textarea.markItUpEditor');
      },
      beforeClose:  function()
      {
        
      }
    });
    
    $dialog.listenToFocusedInputs = function()
    {
      var $form = $('div.wa_page_element_edit', $dialog);
      
      $form.find('.dm_form_elements :input').focus(function() {
        restoreState.activeElementName = $(this).attr('name');
      });
    };
    
    $dialog.saveState = function()
    {
      var $form = $('div.wa_page_element_edit', $dialog);
      
      // Save active tab
      if ($tabbedFormActiveTab = $form.find('ul.ui-tabs-nav > li.ui-tabs-selected:first').orNot())
      {
        restoreState.activeTab = $tabbedFormActiveTab.find('>a').attr('href');
      }
      
      // Save focused element
      if (restoreState.activeElementName)
      {
        var s = ':input[name="'+restoreState.activeElementName+'"]:visible';
        var activeElement = $form.find(s).filter('input[type=text], textarea');
        if(activeElement.length > 0)
        {
          restoreState.activeSelection = [activeElement[0].selectionStart, activeElement[0].selectionEnd];
          restoreState.activeElementScrollTop = activeElement.scrollTop();
        }
      }
    };
    
    $dialog.restoreState = function()
    {
      var $form = $('div.wa_page_element_edit', $dialog);
      
      // Restore active tab
      if(restoreState.activeTab)
      {
        $form.find('div.dm_tabbed_form').tabs('select', restoreState.activeTab);
      }
      
      // Maximize content
      $dialog.maximizeContent('textarea.markItUpEditor');
      
      // Restore focused element
      if (restoreState.activeElementName)
      {
        var s = ':input[name="'+restoreState.activeElementName+'"]:visible';
        var activeElement = $form.find(s);
        activeElement.focus();
        x = activeElement;
        if(activeElement.length > 0)
        {
          if (activeElement[0].setSelectionRange)
          {
            activeElement[0].setSelectionRange(restoreState.activeSelection[0], restoreState.activeSelection[1]);
          }
          activeElement.scrollTop(restoreState.activeElementScrollTop);
        }
      }

      $dialog.listenToFocusedInputs();
    };
    
    $dialog.maximizeContent = function(elToMaximize)
    {
      var $form = $dialog.find('form.dm_form');
      var formHeight = $form.height();
      var dialogHeight = $dialog.height();
      var $maximizable = $form.find(elToMaximize);
      $maximizable.height($maximizable.height() + dialogHeight - formHeight);
    };
    
    $dialog.bind('dmAjaxResponse', function()
    {
      $dialog.prepare();

      $('a.delete', $dialog).click(function()
      {
        if (confirm($(this).tipsyTitle()+" ?"))
        {
          $.dm.removeTipsy();
          widget._delete();
          $dialog.dialog('close');
        }
      });
      
      var $form = $('div.wa_page_element_edit', $dialog);
      if (!$form.length)
      {
        return;
      }
      
      
      /*
       * Apply generic front form abilities
       */
      $form.dmFrontForm();
      /*
       * Apply specific widget form abilities
       */
      if ((formClass = $form.metadata().form_class) && $.isFunction($form[formClass]))
      {
        $form[formClass](widget);
      }
      

      // Maximize content
      $dialog.restoreState();
      
      // enable tool tips
      $dialog.parent().find('a[title], input[title]').tipsy({gravity: $.fn.tipsy.autoSouth});
      
      $form.find('form').dmAjaxForm({
        beforeSubmit: function(data) {
          $dialog.block();
          $dialog.saveState();
        },
        error: function(xhr, textStatus, errorThrown)
        {
          $dialog.unblock();
          widget.element.unblock();
          $.dm.ctrl.errorDialog('Error when updating the widget', xhr.responseText);
        },
        success: function(data)
        {
          if('saved' == data)
          {
            $dialog.dialog('close');
            widget.element.addClass('wa-configured-page-element');
            return;
          }

          parts = data.split(/\_\_DM\_SPLIT\_\_/);

          // update widget content
          if(parts[1])
          {
            widget.replace(parts[1]);
          }

          $form.trigger('submitSuccess');

          // update dialog content
          $dialog.html(parts[0]).trigger('dmAjaxResponse');
        }
      });
    });
  },
  
  
  replace: function(html)
  {
    if($encodedAssets = $('>div.dm_encoded_assets', '<div>'+html+'</div>'))
    {
      this.element.append($encodedAssets).dmExtractEncodedAssets();
    }
    
    this.element
    .attr('class', $('>div:first', '<div>'+html+'</div>').attr('class'))
    .find('div.dm_widget_inner')
    .html($('>div.dm_widget_inner', html).html())
    .attr('class', $('>div.dm_widget_inner', html).attr('class'))
    .end()
    .unblock();
  },
  
  
  _delete: function()
  {
    var self = this;
    self.deleted = true;
    
    $.ajax({
      url:      self.options.edit_page_element_href,
      data:     {id: self.getId()}
    });
    
    self.element.slideUp(500, function() {self.destroy();self.element.remove();$.dm.removeTipsy();});
  },


  

  
  
  initialize: function()
  {
    var self = this;
    
    this.id = this.element.attr('id');
    
    $('> a.wa_page_element_edit', this.element).click(function() {
      if (!self.element.hasClass('dm_dragging')) {
        self.openEditDialog();
      }
    }).tipsy({gravity: $.fn.tipsy.autoSouth});


  },
  
  getId: function()
  {
    return this.id;
  }

});

})(jQuery);
<?php

/**
 * 
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class AnWebAnalyticsPageElementFrontForm extends PluginAnWebAnalyticsPageElementForm {
	
	
	public function configure() {
		
		parent::configure();
		
		$this->useFields(array(
			'name',
			'dm_layout_id',
			$this->getOption('page_field_name')
		));
		
		$this->setWidget('dom_id', new sfWidgetFormInputText(array(), array(
			'readonly' => true
		)));
		
		$this->setValidator('dom_id', new sfValidatorString());
		
		$this->setWidget('dm_layout_id', new sfWidgetFormInputHidden());
		$this->setWidget($this->getOption('page_field_name'), new sfWidgetFormInputHidden());
		
		$this->setWidget('is_common', new sfWidgetFormInputCheckbox());
		$this->setValidator('is_common', new sfValidatorBoolean());
		$this->widgetSchema->setHelp('is_common', 'Are these settings common for subpages that has this layout?');
		$this->widgetSchema->setHelp('name', 'Human-friendly name for this region of page.');
		
		$this->configureSettings();
		
	}
	
	
	protected function configureSettings() {
		
		$settings = $this->object->settings;
		
		$eventTypes = AnWebAnalyticsEventTable::getInstance()->getOption('subclasses');
		$config = sfConfig::get('app_anWebAnalyticsPlugin_events');
		
		foreach ($eventTypes as $eventType) {
			
			$map = Doctrine_Core::getTable($eventType)->getOption('inheritanceMap');
			$type = isset($map['type']) ? $map['type'] : '';
			
			if (isset($config[$type]) && $config[$type]['front_configurable']) {
				
				$name = $type.'_event';
				$this->setWidget($name, new sfWidgetFormInputCheckbox());
				$this->getWidget($name)->addOption('class', $eventType);
				$this->setValidator($name, new sfValidatorBoolean());
				
				if (isset($settings[$eventType])) {
					$this->setDefault($name, $settings[$eventType]);
				}
			}
		}
	}
	
	
	
	public function render($attributes = array())
  {
    $attributes = dmString::toArray($attributes, true);

    return
    $this->open($attributes).
    $this->renderContent($attributes).
    $this->renderActions().
    $this->close();
  }

  protected function renderContent($attributes)
  {
    return '<ul class="dm_form_elements">'.$this->getFormFieldSchema()->render($attributes).'</ul>';
  }

  protected function renderActions()
  {
    return sprintf(
      '<div class="actions">
        <div class="actions_part clearfix">%s</div>
        <div class="actions_part clearfix">%s%s</div>
      </div>',
      sprintf('<a class="dm cancel close_dialog button fleft">%s</a>', $this->__('Cancel')),
      $this->getService('user')->can('widget_delete') && ! $this->isNew()
      ? sprintf('<a class="dm delete button red fleft" title="%s">%s</a>', $this->__('Delete these settings'), $this->__('Delete'))
      : '',
      sprintf('<input type="submit" class="submit and_save green fright" name="and_save" value="%s" />', $this->__('Save and close'))
    );
  }
	
  
  
  
  protected function doUpdateObject($values) {
  
  	parent::doUpdateObject($values);
  	
  	$settings = array();
  	
  	foreach ($values as $key => $value) {
  	
  		if (isset($this->widgetSchema[$key]) && ($class = $this->widgetSchema[$key]->getOption('class'))) {
  		
  			$settings[$class] = $value;
  		}
  	}
  	
  	$this->object->settings = $settings;
  }
  
	
	
}
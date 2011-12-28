<?php

/**
 * Binds custom models to web analytics events
 * Model records can then be tested if they were involved
 * in some kind of an event (click, impression), what user did it,
 * when it happened, how many times
 * @author ardi
 *
 */
class AnEventBindableModels extends Doctrine_Template {

  
  protected $_options = array(
  
    'foreignModels' => array(
      'relations' => array(),
      'columns' => array()
    )
  );
  
  
  
  public function setTableDefinition() {
  
    $models = $this->getTable()->getEventDispatcher()
                      ->filter(new sfEvent($this, 'web_analytics.filter_event_models'), $this->_options['foreignModels'])
                      ->getReturnValue();
    
    foreach ($models['relations'] as $rel => $options) {
    
      $this->hasOne($rel, $options);
    }
    
    $this->hasColumns($models['columns']);
    
  }
  

}
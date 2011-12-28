<?php

/**
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class AnPageAreaEventTypesBindable extends Doctrine_Template {

  /**
   * 
   * custom events can be added by app designers and
   * this Template allows custom events to added;
   * in Edit Mode admin can select elements that will be tracked
   *
   */
  public function setTableDefinition() {
  
    $eventClasses = Doctrine_Core::getTable('AnWebAnalyticsEvent')->getOption('subclasses');
    
    /*
     * add custom-event-setting fields
     */
    if (is_array($eventClasses)) {
    
      foreach ($eventClasses as $eClass) {
      
        $inhMap = Doctrine_Core::getTable($eClass)->getOption('inheritanceMap');
        if (is_array($inhMap) && isset($inhMap['type'])) {
        
          /*
           * whether an event is loggable inside an area
           * can be further customized to be logged on specific elements therefore use of ARRAY TYPE
           * which in turn can be bound to foreign models
           * this will be an array of boolean values
           */
          $this->hasColumn(sprintf('%s_loggable', $inhMap['type']), 'boolean');
        }
      }
    }
  }
  

}
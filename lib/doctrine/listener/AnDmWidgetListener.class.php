<?php

/**
 * postSave is necessary to save just updated (if any) area values
 * form widget does not save, only object is saved during executeEdit
 * so no embedded forms can be persisted
 * @author ardi
 *
 */
class AnDmWidgetListener extends Doctrine_Record_Listener {

  public function postSave(Doctrine_Event $e) {
  
    if ($areaValues = AnWebAnalyticsPageAreaForm::getAreaValues($e->getInvoker()->getId())) {
      /*
       * if we're here then values were checked and widget edit form passed the validation
       */
      $area = $e->getInvoker()->getAnalyticsArea();
      
      $area->fromArray($areaValues);
      // for the first time init some values
      $area->setPageId(dmContext::getInstance()->getPage()->id);
      
      $area->save();
      AnWebAnalyticsPageAreaForm::unsetAreaValues($e->getInvoker()->getId());
    }
  }

}
<?php

/**
 * base action class responsible for gathering information from user visits
 * @author ardi
 *
 */
abstract class BaseanWebAnalyticsActions extends myFrontBaseActions {

  /**
   * Gathers info about events which happened during ONE
   * visit on a site's subpage
   */
  public function executeCollect(sfWebRequest $request) {
  
    $this->forward404Unless($request->isXmlHttpRequest() && $request->isMethod('post'));

    $data = $request->getPostParameter('wa');
    
    //$this->getLogger()->debug(print_r($data, true));
    
    
    if (! isset($data['events']) || ! is_array($data['events']) || ! isset($data['page_id']) || ! isset($data['page_module'])) return sfView::NONE;
    
    
    $e = $this->getContext()->getEventDispatcher()->notifyUntil(new sfEvent($this, 'an.web_analytics.determine_element_page_field', array(
    	'page_module' => $data['page_module']
    )));
    
    $pageFieldName = $e->isProcessed() ? $e->getReturnValue() : 'page_id';
    
    
    foreach ($data['events'] as $elemId => $elemEvents) {
    	
    	$elem = AnWebAnalyticsPageElementTable::getInstance()->findOneByDomIdAndDmLayoutId($elemId, $data['layout_id']);
    	if (! $elem) continue;
    	
    	$WA = $this->getService('web_analytics');
    	
    	foreach ($elemEvents as $elemEvent) {
    	
    		if (! isset($elemEvent['type'])) continue;
    		$class = 'AnWebAnalytics' . ucfirst($elemEvent['type']) . 'Event';
    		if (! Doctrine_Core::isValidModelClass($class)) continue;
    		
    		$dbEvent = new $class;
    		
    		if (isset($elemEvent['created_at'])) {
    			$elemEvent['created_at'] = date('Y-m-d H:i:s', $elemEvent['created_at']);
    		}
    		$dbEvent->fromArray($elemEvent); // pass some event custom properties
    		
    		if ($dbEvent->offsetExists($pageFieldName)) {
    			$dbEvent->set($pageFieldName, $data['page_id']);
    		}
    		$dbEvent->setPageElement($elem);

    		$dbEvent->setPageVisit($WA->getPreviousPageSession());

    		$dbEvent->save();
    	}
    }
    
    return sfView::NONE;
  }
  

}
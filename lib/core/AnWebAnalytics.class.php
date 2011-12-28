<?php

/**
 * base class that is registered as a DIC service
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class AnWebAnalytics {
  
  
  protected
  
    /**
     * DIC Service
     * @var sfWebRequest
     */
    $_request,
  
    /**
     * @var AnWebAnalyticsVisitor
     */
    $_visitor,
    /**
     * @var AnWebAnalyticsVisit
     */
    $_visit,
    /**
     * @var sfEventDispatcher
     */
    $_dispatcher,
    
    /**
     * @var PluginAnWebAnalyticsVisitPageAssoc
     */
    $_prevPageSession,
  
  	/**
     * @var PluginAnWebAnalyticsVisitPageAssoc
     */
    $_currentPageSession;
  
  

  public function __construct(sfWebRequest $request, sfEventDispatcher $dispatcher, $options) {
  
    $this->_request = $request;
    $this->_dispatcher = $dispatcher;
  }

  
  
  public function getVisitor() {
  
    if (! $this->_visitor) {
    
      $visitorCookie = sfConfig::get('app_anWebAnalyticsPlugin_visitor_cookie', 'an_web_analytics_visitor');
      $this->_visitor = AnWebAnalyticsVisitorTable::getInstance()->find($this->_request->getCookie($visitorCookie));
    }
    
    return $this->_visitor;
  }
  
  
  public function getVisit() {
  
    if (! $this->_visit) {
    
      $this->_visit = AnWebAnalyticsVisitTable::getInstance()->find(session_id());
    }
    
    return $this->_visit;
  }
  
  
  
  public function connectPageWithVisit(DmPage $page) {
  
  	$event = $this->_dispatcher->notifyUntil(new sfEvent($page, 'an.web_analytics.connect_page_visit', array('visit' => $this->getVisit())));
		
		if (! $event->isProcessed()) {
		
			// default
			$conn = AnWebAnalyticsVisitDmPageAssocTable::getInstance()->connectPageWithVisit($page, $this->getVisit());
		}
		else {
			$conn = $event->getReturnValue();
		}

		$this->_currentPageSession = $conn;

  }
  
  
  
  public function getCurrentPageSession() {
  
  	return $this->_currentPageSession;
  }
  
  
  
  public function getPreviousPageSession() {
  
  	if (! $this->_prevPageSession) {
  	
  		$this->_prevPageSession = AnWebAnalyticsVisitPageAssocTable::getInstance()->getPrevious($this->getVisit());
  	}
  	return $this->_prevPageSession;
  }
  
  
	
  
}
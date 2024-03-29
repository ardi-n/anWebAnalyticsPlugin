<?php

/**
 * PluginAnWebAnalyticsEvent
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginAnWebAnalyticsEvent extends BaseAnWebAnalyticsEvent
{

  public function preInsert($event) {

  	// maybe it's already set
  	if (($page = sfContext::getInstance()->getPage()) && ! $this->getPageId()) {
    	$this->setPageId($page->id);
  	}
    
    $WA = $this->getService('web_analytics');
    
    // maybe it's already set
    if (! $this->getVisitorCookieId()) {
    	$this->setVisitor($WA->getVisitor());
    }
    
    // maybe it's already set
    if (! $this->getVisitCookieId()) {
    	$this->setVisit($WA->getVisit());
    }
    
    // maybe it's already set
    if (! $this->getPageVisitId()) {
    	$this->setPageVisit($WA->getCurrentPageSession());
    }
    
    $this->setVisitorIp($this->getService('request')->getRemoteAddress());
  }
  
  
  public function getProperties() {
  
  	return array(
  		'page_element' => $this->getPageElement(),
  		'page_x' => $this->page_x,
  		'page_y' => $this->page_y
  	);
  }
  
}
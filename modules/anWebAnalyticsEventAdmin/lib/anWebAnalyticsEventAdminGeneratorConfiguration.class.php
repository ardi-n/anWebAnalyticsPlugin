<?php

/**
 * anWebAnalyticsEventAdmin module configuration.
 *
 * @package    Krecisie
 * @subpackage anWebAnalyticsEventAdmin
 * @author     Adrian Nowicki <me@adrian-nowicki.com>
 * @version    SVN: $Id: configuration.php 12474 2008-10-31 10:41:27Z fabien $
 */
class anWebAnalyticsEventAdminGeneratorConfiguration extends BaseAnWebAnalyticsEventAdminGeneratorConfiguration
{
	
	public
		$processedType;
	
	
	
	
	
	
	
	
	public function compile() {
		
		parent::compile();
		
		$types = array();
		
		foreach (AnWebAnalyticsEventTable::getInstance()->getOption('subclasses') as $s) {
			
			$type = sfInflector::tableize(str_replace(array('AnWebAnalytics', 'Event'), '', $s));
			$types[] = $type;
		}
		
		$this->configuration['list']['event_types'] = $types;
		
	}
	
	/**
	 * overwrite filter class if there is specific subclass set by
	 * $filters['type'] param
	 * 
	 * @param array $filters
	 */
	public function getFilterForm($filters)
	{
		$class = $this->getFilterFormClass();
	
		if (isset($filters['type'])) {
			
			$class = sprintf('AnWebAnalytics%sEventFormFilter', sfInflector::camelize($filters['type']));
		}
		
		return new $class($filters, $this->getFilterFormOptions());
	}
	
	
	public function getListDisplay() {
		
		$type = $this->processedType;
		/*
		 * if event type is specified then show its unique properties in separate columns
		*/
		if ($type && method_exists($this, $method = sprintf('get%sListDisplay', sfInflector::camelize($type)))) {
			
			return $this->$method();
		}
		
		return array('type', 'visitor_cookie_id', 'visit_cookie_id', 'page_visit_id', '_link', '_properties', 'created_at');
	}
	
	
	public function getImpressionListDisplay() {
		
		return array('visitor_cookie_id', 'visit_cookie_id', 'page_visit_id', '_link', 'page_element_id', 'page_x', 'page_y', 'created_at');
	}
	
	
	public function getClickListDisplay() {
		
		return array('visitor_cookie_id', 'visit_cookie_id', 'page_visit_id', '_link', 'page_x', 'page_y', 'created_at');
	}
	
	public function getTypeListDisplay() {
	
		return array('visitor_cookie_id', 'visit_cookie_id', 'page_visit_id', '_link', 'typed_text', 'created_at');
	}
	
	public function getTextSelectListDisplay() {
	
		return array('visitor_cookie_id', 'visit_cookie_id', 'page_visit_id', '_link', 'selected_text', 'created_at');
	}
	
	public function getArticleImpressionListDisplay() {
	
		return array('visitor_cookie_id', 'visit_cookie_id', 'page_visit_id', '_link', 'page', 'created_at');
	}
	
	public function getCommentListDisplay() {
	
		return array('visitor_cookie_id', 'visit_cookie_id', 'page_visit_id', '_link', 'comment_id', 'created_at');
	}
	
	public function getRateListDisplay() {
	
		return array('visitor_cookie_id', 'visit_cookie_id', 'page_visit_id', '_link', 'rate', 'created_at');
	}
	
	
	public function getPollVoteListDisplay() {
		
		return array('visitor_cookie_id', 'visit_cookie_id', 'page_visit_id', '_link', '_poll_id', '_poll_answers', 'created_at');
	}
	
}

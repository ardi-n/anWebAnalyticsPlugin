<?php

/**
 * PluginAnWebAnalyticsVisitPageAssoc
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginAnWebAnalyticsVisitPageAssoc extends BaseAnWebAnalyticsVisitPageAssoc
{

	/**
	 * set datestart for a session within a page
	 * also set dateend for a session of previously visited page 
	 * 
	 * @param Doctrine_Event $event
	 */
	public function preInsert($event) {
	
		$this->setDatestart(date('Y-m-d H:i:s'));
		
		$visitPageAssoc = $this->getService('web_analytics')->getPreviousPageSession();
		if ($visitPageAssoc) {// if this page is is not visited first
		
			$visitPageAssoc->setDateend(date('Y-m-d H:i:s'));
			$visitPageAssoc->save();
		}
	}
	
	
	
}
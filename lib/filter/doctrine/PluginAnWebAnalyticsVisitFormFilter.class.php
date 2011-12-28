<?php

/**
 * PluginAnWebAnalyticsVisit form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginAnWebAnalyticsVisitFormFilter extends BaseAnWebAnalyticsVisitFormFilter
{
	
	public function setup() {
		
		parent::setup();
		
		$this->setWidget('user_cookie_id', new sfWidgetFormInputHidden());
	}
	
}

<?php

/**
 * PluginAnWebAnalyticsTypeEvent form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginAnWebAnalyticsTypeEventFormFilter extends BaseAnWebAnalyticsTypeEventFormFilter
{
	
	public function configure() {
		
		$this->useFields(array(
			'type',
			'typed_text',
			'created_at'
		));
	}
	
}

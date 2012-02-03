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
		
		$this->setWidget('created_at', new sfWidgetFormFilterDate(array(
					'from_date' => new sfWidgetFormInputText(array(), array('style' => 'float: none;', 'class' => 'datepicker_me')),
					'to_date' => new sfWidgetFormInputText(array(), array('style' => 'float: none;', 'class' => 'datepicker_me')),
					'template' => '%from_date% - %to_date% (from - to)',
					'with_empty' => false
		)));
		
		$this->validatorSchema['created_at'] = new sfValidatorDateRange(array(
				      'required' => false,  
				      'from_date' => new sfValidatorDateTime(array('required' => false)),  
				      'to_date' => new sfValidatorDateTime(array('required' => false))  
		));
		
	}
	
	 
	public function getJavascripts() {
	
		return array_merge(parent::getJavascripts(), array(
				'lib.ui-core',
				'lib.ui-widget',
				'lib.ui-position',
//				'/dmCorePlugin/lib/jquery-ui/js/minified/jquery.ui.autocomplete.min.js',
				'/dmCorePlugin/lib/jquery-ui/js/minified/jquery.ui.slider.min.js',
				'lib.ui-datepicker',
				'anWebAnalyticsPlugin.ui-timepicker'
		));
	}
	
	
	public function getStylesheets() {
	
		return array_merge(parent::getStylesheets(), array(
				'lib.ui-datepicker' => null,
				'anWebAnalyticsPlugin.ui-datetimepicker' => null,
				'anWebAnalyticsPlugin.ui-autocomplete' => null
//				'anWebAnalyticsPlugin.ui-custom' => null
		));
	}
	
}

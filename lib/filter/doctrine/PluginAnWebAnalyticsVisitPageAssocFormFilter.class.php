<?php

/**
 * PluginAnWebAnalyticsVisitPageAssoc form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginAnWebAnalyticsVisitPageAssocFormFilter extends BaseAnWebAnalyticsVisitPageAssocFormFilter
{
	
	public function setup() {
		
		parent::setup();
		
		$this->setWidget('visit_cookie_id', new sfWidgetFormInputHidden());
		
		$this->setWidget('datestart', new sfWidgetFormFilterDate(array(
			'from_date' => new sfWidgetFormDmDate(array(), array('style' => 'float: none;')),
			'to_date' => new sfWidgetFormDmDate(array(), array('style' => 'float: none;')),
			'template' => '%from_date% - %to_date% (from - to)',
			'with_empty' => false
		)));
		
		$this->validatorSchema['datestart'] = new sfValidatorDateRange(array(
		      'required' => false,  
		      'from_date' => new dmValidatorDate(array('required' => false)),  
		      'to_date' => new dmValidatorDate(array('required' => false))  
		));
		
		$this->setWidget('page_id', new sfWidgetFormInputHidden());
		$this->setValidator('page_id', new sfValidatorDoctrineChoice(array(
		  		'model' => 'DmPage',
		  		'multiple' => false,
		  		'required' => false
		)));
		
		$this->setWidget('page_title', new sfWidgetFormInputText(array(), array(
			'class' => 'sf_admin_field_autocomplete',
			'rel' => $this->getWidget('page_id')->generateId($this->widgetSchema->generateName('page_id')),
			'data-autocomplete-href' => 
							$this->getService('helper')->link(array(
								'sf_route' => 'an_web_analytics_visit_page_assoc',
								'action' => 'fetchChoices'
							))
							->getHref()
		)));
		
		$this->setValidator('page_title', new sfValidatorPass());
	}
	
	
	public function getJavascripts() {
		
		return array_merge(parent::getJavascripts(), array(
			'lib.ui-core',
			'lib.ui-widget',
			'lib.ui-position',
			'/dmCorePlugin/lib/jquery-ui/js/minified/jquery.ui.autocomplete.min.js',
			'/dmCorePlugin/lib/jquery-ui/js/minified/jquery.ui.slider.min.js',
			'lib.ui-datepicker',
			'anWebAnalyticsPlugin.ui-timepicker'
		));
	}
	
	
	public function getStylesheets() {
		
		return array_merge(parent::getStylesheets(), array(
			'lib.ui-datepicker' => null,
			'anWebAnalyticsPlugin.ui-datetimepicker' => null,
			'anWebAnalyticsPlugin.ui-autocomplete' => null,
//			'anWebAnalyticsPlugin.ui-custom' => null
		));
	}
	
}

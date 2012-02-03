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
			'from_date' => new sfWidgetFormInputText(array(), array('style' => 'float: none;', 'class' => 'datepicker_me')),
			'to_date' => new sfWidgetFormInputText(array(), array('style' => 'float: none;', 'class' => 'datepicker_me')),
			'template' => '%from_date% - %to_date% (from - to)',
			'with_empty' => false
		)));
		
		$this->validatorSchema['datestart'] = new sfValidatorDateRange(array(
		      'required' => false,  
		      'from_date' => new sfValidatorDateTime(array('required' => false)),  
		      'to_date' => new sfValidatorDateTime(array('required' => false))  
		));
		
		
		$this->setWidget('dateend', new sfWidgetFormFilterDate(array(
					'from_date' => new sfWidgetFormInputText(array(), array('style' => 'float: none;', 'class' => 'datepicker_me')),
					'to_date' => new sfWidgetFormInputText(array(), array('style' => 'float: none;', 'class' => 'datepicker_me')),
					'template' => '%from_date% - %to_date% (from - to)',
					'with_empty' => false
		)));
		
		$this->validatorSchema['dateend'] = new sfValidatorDateRange(array(
				      'required' => false,  
				      'from_date' => new sfValidatorDateTime(array('required' => false)),  
				      'to_date' => new sfValidatorDateTime(array('required' => false))  
		));
		
		
		$this->setWidget('page_id', new sfWidgetFormInputHidden());
		$this->setValidator('page_id', new sfValidatorString(array(
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
	
	
	/**
	 * filter results based on page
	 * the page can be a static one DmPage or some other model
	 * defined by user so the value contains information
	 * on how field is named
	 * 
	 * @param unknown_type $query
	 * @param unknown_type $field
	 * @param unknown_type $value
	 */
	public function addPageIdColumnQuery($query, $field, $value) {
	
		list($realField, $realValue) = explode('|', $value);
		
		if ($this->getTable()->hasColumn($realField)) {
			
			$a = $query->getRootAlias();
			$query->andWhere("$a.$realField = ?", $realValue);
		}
		
	}
}

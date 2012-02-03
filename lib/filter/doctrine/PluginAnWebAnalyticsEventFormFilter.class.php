<?php

/**
 * PluginAnWebAnalyticsEvent form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginAnWebAnalyticsEventFormFilter extends BaseAnWebAnalyticsEventFormFilter
{
	
	public function setup() {
		
		parent::setup();
		
		$this->setWidget('visitor_cookie_id', new sfWidgetFormInputHidden());
		$this->setWidget('visit_cookie_id', new sfWidgetFormInputHidden());
		
		$this->getValidator('visit_cookie_id')->setOption('required', false);
		
		$this->setWidget('page_id', new sfWidgetFormInputHidden());
		$this->setValidator('page_id', new sfValidatorString(array(
		  		'required' => false
		)));
		// additional field that is autocompleted with title, also page_id is filled
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
		
		$this->setupType();
		
		$this->setupCreatedAt();
	}
	
	
	protected function setupCreatedAt() {
		
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
		/*
		$this->setWidget('created_at', new sfWidgetFormChoice(array('choices' => array(
		        'hour' => $this->getI18n()->__('This hour'),
		        'today' => $this->getI18n()->__('Today'),
		        'week'  => $this->getI18n()->__('Past %number% days', array('%number%' => 7)),
		        'month' => $this->getI18n()->__('This month'),
		        'year'  => $this->getI18n()->__('This year')
		))));
		
		$this->setValidator('created_at', new sfValidatorChoice(array(
			'choices' => array_keys($this->getWidget('created_at')->getOption('choices'))
		)));
		*/
	}
	
	
	protected function setupType() {
	
		$eventSubclasses = Doctrine_Core::getTable('AnWebAnalyticsEvent')->getOption('subclasses');
		
		$typeChoices = array('' => '');
		
		foreach ($eventSubclasses as $eventSubclass) {
		
			$map = Doctrine_Core::getTable($eventSubclass)->getOption('inheritanceMap');
			if (isset($map['type'])) $typeChoices[$map['type']] = $map['type'];
		}
		
		$this->setWidget('type', new sfWidgetFormChoice(array(
			'choices' => $typeChoices
		), array(
			'class' => 'sf_admin_field_reload_filters'
		)));
		$this->setValidator('type', new sfValidatorChoice(array(
			'choices' => array_keys($typeChoices),
			'required' => false
		)));
	}
	
	
	
	protected function setupInheritance() {
		
		$this->useFields(array(
			'type',
			'created_at',
			'visitor_ip',
			'visitor_cookie_id',
			'visit_cookie_id',
			'page_element_id'
		));
	}
	
	
	
	public function getFields() {
	
		return array_merge(parent::getFields(), array(
			'type' => 'Enum'
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
	
	
	public function getJavascripts() {
		
		return array_merge(parent::getJavascripts(), array(
			'lib.ui-core',
			'lib.ui-widget',
			'lib.ui-position',
			'/dmCorePlugin/lib/jquery-ui/js/minified/jquery.ui.slider.min.js',
			'lib.ui-datepicker',
			'/dmCorePlugin/lib/jquery-ui/js/minified/jquery.ui.autocomplete.min.js',
			'anWebAnalyticsPlugin.ui-timepicker'
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

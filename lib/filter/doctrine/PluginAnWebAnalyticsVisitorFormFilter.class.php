<?php

/**
 * PluginAnWebAnalyticsVisitor form.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage filter
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: sfDoctrineFormFilterPluginTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class PluginAnWebAnalyticsVisitorFormFilter extends BaseAnWebAnalyticsVisitorFormFilter
{
	
	public function configure() {
		
		parent::configure();
		
		$this->setWidget('dm_page_id', new sfWidgetFormInputText());
		$this->setValidator('dm_page_id', new sfValidatorDoctrineChoice(array(
			'model' => 'DmPage',
			'required' => false
		)));
		
		$this->setupCreatedAt();
		
		$this->setupOs();
		
		$this->setupBrowser();
	}
	
	
	protected function setupCreatedAt() {
	
		$this->setWidget('created_at', new sfWidgetFormChoice(array('choices' => array(
			        'hour' => $this->getI18n()->__('This hour'),
			        'today' => $this->getI18n()->__('Today'),
			        'week'  => $this->getI18n()->__('Past %number% days', array('%number%' => 7)),
			        'month' => $this->getI18n()->__('This month'),
			        'year'  => $this->getI18n()->__('This year')
		))));
	
		$this->setValidator('created_at', new sfValidatorChoice(array(
				'choices' => array_keys($this->getWidget('created_at')->getOption('choices')),
				'required' => false
		)));
	}
	
	
	protected function setupOs() {
		
		$os = AnWebAnalyticsVisitorTable::getInstance()->fetchOsInUse();
		
		$this->setWidget('os', new sfWidgetFormChoice(array(
			'choices' => array_combine($os, $os),
			'multiple' => true
		)));
		
	}
	
	
	protected function setupBrowser() {
		
		$browsers = AnWebAnalyticsVisitorTable::getInstance()->fetchBrowsersInUse();
		
		$this->setWidget('browser', new sfWidgetFormChoice(array(
					'choices' => $browsers,
					'multiple' => true
		)));
	}
	
	
	
	public function addDmPageIdColumnQuery($query, $field, $value) {
		
		$a = $query->getRootAlias();
		$query
			->innerJoin("$a.Visits vs")
			->innerJoin("vs.DmPagesSeen dps WITH dps.id = ?", $value);
	}
	
	
	public function addBrowserColumnQuery($query, $field, $browsers) {
		
		$a = $query->getRootAlias();
		
		$or = array();
		$params = array();
		foreach ($browsers as $browser) {
			
			$or[] = "$a.browser LIKE ?";
			$params[] = '%'.$browser.'%';
		}
		
		$query->andWhere(implode(' OR ', $or), $params);
		
	}
	
	
	
	public function getFields() {
		
		return array_merge(parent::getFields(), array(
			'os' => 'Enum'
		));
	}
	
	
}

<?php

/**
 * 
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class AnWebAnalyticsVisitorAdminChartFormFilter extends AnWebAnalyticsVisitorFormFilter {
	
	public
		$chartHelper;
	
	
	public function configure() {
		
		parent::configure();
		
		$this->chartHelper = new AnWebAnalyticsAdminChartFormFilterHelper();
		$this->chartHelper->dateTimeField = 'created_at';
		
		$this->setWidgets(array('period_groupby' => new sfWidgetFormChoice(array(
			'choices' => AnWebAnalyticsAdminChartFormFilterHelper::$period_choices
		))));
		$this->setValidator('period_groupby', new sfValidatorChoice(array(
			'choices' => array_keys(AnWebAnalyticsAdminChartFormFilterHelper::$period_choices)
		)));
		
		
	}
	
	
	
	
	public function addPeriodGroupbyColumnQuery($query, $field, $value) {
		
		$this->chartHelper->addPeriodGroupbyColumnQuery($query, $field, $value);
	}
	
	
	public function getRealChartFilters() {
	
		return $this->chartHelper->getRealChartFilters();
	}
	
	
	public function getRealChartValues() {
	
		if (! $this->isValid()) return false;
		
		return $this->chartHelper->getRealChartValues($this->getValues());
	}
	
	 
	public function getFinalData($filterValues) {
		
		$q = $this->buildQuery($filterValues);//var_dump($q->getSqlQuery());exit;
		$data = $q->fetchArray();
		//var_dump($data);exit;
		return $this->chartHelper->getFinalData($data, $filterValues['period_groupby']);
	}
	
}
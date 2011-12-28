<?php

/**
 * 
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class AnWebAnalyticsEventAdminChartFormFilter extends AnWebAnalyticsEventFormFilter {
	
	protected static
		$period_choices = array('minute' => 'Minute', 'hour' => 'Hour', 'day' => 'Day', 'week' => 'Week', 'month' => 'Month');
	
	protected static
		$period_formats = array('minute' => 'i', 'hour' => 'H', 'day' => 'j', 'week' => 'W', 'month' => 'n', 'year' => 'Y');
	
	
	public
		$chartHelper;
	
	
	public function configure() {
		
		parent::configure();
		
		$this->chartHelper = new AnWebAnalyticsAdminChartFormFilterHelper();
		$this->chartHelper->dateTimeField = 'created_at';
		
		$this->setWidgets(array('period_groupby' => new sfWidgetFormChoice(array(
			'choices' => self::$period_choices
		))));
		$this->setValidator('period_groupby', new sfValidatorChoice(array(
			'choices' => array_keys(self::$period_choices)
		)));
		
		
	}
	
	
	
	public function getRealChartFilters() {
	
		return $this->chartHelper->getRealChartFilters();
	}
	
	
	public function getRealChartValues() {
	
		if (! $this->isValid()) return false;
	
		return $this->chartHelper->getRealChartValues($this->getValues());
	}
	
	
	public function addPeriodGroupbyColumnQuery($query, $field, $value) {
		
		$this->chartHelper->addPeriodGroupbyColumnQuery($query, $field, $value);
	}
	
	
	public function getFinalData($filterValues) {
	
		$q = $this->buildQuery($filterValues);
		$data = $q->fetchArray();
	
		return $this->chartHelper->getFinalData($data, $filterValues['period_groupby']);
	}
	
	
	
	
}
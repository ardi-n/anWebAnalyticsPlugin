<?php

/**
 * 
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class AnWebAnalyticsVisitAdminChartFormFilter extends AnWebAnalyticsVisitFormFilter {
	
	protected static
		$period_choices = array('day' => 'Day', 'week' => 'Week', 'month' => 'Month');
	
	
	public function configure() {
		
		parent::configure();
		
		$this->setWidgets(array('period_groupby' => new sfWidgetFormChoice(array(
			'choices' => self::$period_choices
		))));
		$this->setValidator('period_groupby', new sfValidatorChoice(array(
			'choices' => array_keys(self::$period_choices)
		)));
		
		
	}
	
	
	
	
	
	public function addPeriodGroupbyColumnQuery($query, $field, $value) {
		
		$a = $query->getRootAlias();
		
		$query
			->addSelect("YEAR($a.created_at) AS year");
		
		switch ($value) {
			
			case 'week':
				$query
					->addSelect("WEEKOFYEAR($a.created_at) AS week")
					->addGroupBy("year, week");
				break;
			case 'month':
				$query
					->addSelect("MONTH($a.created_at) AS month")
					->addGroupBy("year, month");
				break;
			default:
				$query
					->addSelect("DAYOFYEAR($a.created_at) AS day")
					->addGroupBy("year, day");
				break;
		}
	}
	
	
	public function getRealChartFilters() {
	
		return array(
			'period_groupby'
		);
	}
	
	
	public function getRealChartValues() {
	
		if (! $this->isValid()) return false;
		
		$realChartValueNames = $this->getRealChartFilters();
		$values = $this->getValues();
		$realChartValues = array();
		
		foreach ($realChartValueNames as $realChartValueName) {
		
			if (isset($values[$realChartValueName])) $realChartValues[$realChartValueName] = $values[$realChartValueName];
		}
		
		return $realChartValues;
	}
	
	
	
}
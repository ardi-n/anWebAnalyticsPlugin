<?php

/**
 * 
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class anWebAnalyticsEventAdminComponents extends myAdminBaseComponents {
	
	
	
	
	
	public function executeChart(sfWebRequest $request) {
		
		$this->chartFilters = new AnWebAnalyticsEventAdminChartFormFilter();
		$this->chartFilters->setTableMethod('countQuery');
		
		$actions = $this->getController()->getAction('anWebAnalyticsEventAdmin', 'index');
		$actions->preExecute();
		
		$filters = $actions->getMergedFiltersForChart();
		$this->data = $this->chartFilters->getFinalData($filters);
		$this->groupby = $filters['period_groupby'];
//var_dump($this->data);exit;
	}
	
	
	public function executeChartForHome(sfWebRequest $request) {
		
		$this->chartFilters = new AnWebAnalyticsEventAdminChartFormFilter();
		$this->chartFilters->setTableMethod('countQuery');
		
		$this->data = $this->chartFilters->getFinalData($this->getVar('filterValues'));

	}
	
	
}
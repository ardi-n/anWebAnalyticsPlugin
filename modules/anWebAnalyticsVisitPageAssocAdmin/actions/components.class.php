<?php

/**
 * 
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class anWebAnalyticsVisitPageAssocAdminComponents extends myAdminBaseComponents {
	
	
	public function executeChart(sfWebRequest $request) {
	
		$this->chartFilters = new AnWebAnalyticsVisitPageAssocAdminChartFormFilter();
		$this->chartFilters->setTableMethod('countQuery');
		
		$actions = $this->getController()->getAction('anWebAnalyticsVisitPageAssocAdmin', 'index');
		$actions->preExecute();
		
		$filters = $actions->getMergedFiltersForChart();
		try {
			$this->data = $this->chartFilters->getFinalData($filters);
		}
		catch (sfException $e) {
			$this->error = $e->getMessage();
		}
		$this->groupby = $filters['period_groupby'];
	
	}
	
	
}
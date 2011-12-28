<?php


class anWebAnalyticsVisitorAdminComponents extends myAdminBaseComponents
{

	public function executeChart(sfWebRequest $request) {
	
		$this->chartFilters = new AnWebAnalyticsVisitorAdminChartFormFilter();
		$this->chartFilters->setTableMethod('countQuery');
		
		$actions = $this->getController()->getAction('anWebAnalyticsVisitorAdmin', 'index');
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

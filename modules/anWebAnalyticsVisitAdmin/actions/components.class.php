<?php


class anWebAnalyticsVisitAdminComponents extends myAdminBaseComponents
{

	public function executeChart(sfWebRequest $request) {
	
		$this->chartFilters = new AnWebAnalyticsVisitAdminChartFormFilter();
		$this->chartFilters->setTableMethod('countQuery');
		
		$actions = $this->getController()->getAction('anWebAnalyticsVisitAdmin', 'index');
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

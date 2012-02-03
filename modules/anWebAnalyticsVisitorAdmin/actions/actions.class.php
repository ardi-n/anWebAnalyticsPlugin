<?php

require_once dirname(__FILE__).'/../lib/anWebAnalyticsVisitorAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/anWebAnalyticsVisitorAdminGeneratorHelper.class.php';

/**
 * anWebAnalyticsVisitorAdmin actions.
 *
 * @package    Krecisie
 * @subpackage anWebAnalyticsVisitorAdmin
 * @author     Adrian Nowicki <me@adrian-nowicki.com>
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class anWebAnalyticsVisitorAdminActions extends autoAnWebAnalyticsVisitorAdminActions
{
	
	public function getMergedFiltersForChart() {
	
		return array_merge($this->getFilters(), $this->getChartFilters());
	}
	
	protected function getChartFilters() {
	
		return $this->getUser()->getAttribute('anWebAnalyticsVisitorAdmin.chartFilters', array('period_groupby' => 'day'), 'admin_module');
	}
	
	
	
	
	protected function setChartFilters($filters) {
	
		$this->getUser()->setAttribute('anWebAnalyticsVisitorAdmin.chartFilters', $filters, 'admin_module');
	}
	
	
	
	
	
	
	public function executeFilter(sfWebRequest $request)
	{
		$this->setPage(1);
	
		if ($request->hasParameter('_reset'))
		{
			$this->setFilters($this->configuration->getFilterDefaults());
	
			$this->redirect('@an_web_analytics_visitor');
		}
	
		$this->filters = $this->configuration->getFilterForm($this->getFilters());
	
		$this->filters->bind($request->getParameter($this->filters->getName()));
		if ($this->filters->isValid())
		{
			$this->setFilters($this->filters->getValues());
	
			$filterValues = array_merge($this->getFilters(), $this->getChartFilters());
			//var_dump($filterValues);exit;
			$this->chartFilters = new AnWebAnalyticsVisitorAdminChartFormFilter($filterValues);
			
			$this->chartFilters->bind($request->getParameter($this->chartFilters->getName()));
			//		var_dump($request->getParameter($this->chartFilters->getName()));exit;
			if ($this->chartFilters->isValid())
			{
				$this->setChartFilters($this->chartFilters->getRealChartValues());
			}
			
			$this->redirect('@an_web_analytics_visitor');
		}
	
		$this->pager = $this->getPager();
		$this->sort = $this->getSort();
	
		$this->setTemplate('index');
	}
	
	
	
	
	public function executeShowFilters(sfWebRequest $request)
	{
		
		$filters = $this->configuration->getFilterForm($this->getFilters());
	
		$filterValues = array_merge($this->getFilters(), $this->getChartFilters());
		$chartFilters = new AnWebAnalyticsVisitorAdminChartFormFilter($filterValues);
	
		return $this->renderAsync(array(
					'html' => $this->getPartial('filters', array(
		      	'configuration' => $this->configuration,
		      	'form' => $filters,
		      	'chartFilters' => $chartFilters
		))
		));
	}
	
	
}

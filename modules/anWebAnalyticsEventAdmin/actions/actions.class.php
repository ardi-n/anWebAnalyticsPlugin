<?php

require_once dirname(__FILE__).'/../lib/anWebAnalyticsEventAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/anWebAnalyticsEventAdminGeneratorHelper.class.php';

/**
 * anWebAnalyticsEventAdmin actions.
 *
 * @package    Krecisie
 * @subpackage anWebAnalyticsEventAdmin
 * @author     Adrian Nowicki <me@adrian-nowicki.com>
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class anWebAnalyticsEventAdminActions extends autoAnWebAnalyticsEventAdminActions
{

	public function getMergedFiltersForChart() {
		
		return array_merge($this->getFilters(), $this->getChartFilters());
	}
	
	
	protected function getChartFilters() {
		
		return $this->getUser()->getAttribute('anWebAnalyticsEventAdmin.chartFilters', array('period_groupby' => 'day'), 'admin_module');
	}
	
	
	

	protected function setChartFilters($filters) {
		
		$this->getUser()->setAttribute('anWebAnalyticsEventAdmin.chartFilters', $filters, 'admin_module');
	}
	
	
	
	
	
	
	/**
	 * Chart Filter Data are submitted together
	 * 
	 * @param sfWebRequest $request
	 */
	public function executeFilter(sfWebRequest $request)
	{
		$this->setPage(1);
	
		if ($request->hasParameter('_reset'))
		{
			$this->setFilters($this->configuration->getFilterDefaults());
	
			$this->redirect('@an_web_analytics_event');
		}
	
		$this->filters = $this->configuration->getFilterForm($this->getFilters());
	
		$this->filters->bind($request->getParameter($this->filters->getName()));
		if ($this->filters->isValid())
		{
			$this->setFilters($this->filters->getValues());
	
			$filterValues = array_merge($this->getFilters(), $this->getChartFilters());
			//var_dump($filterValues);exit;
			$this->chartFilters = new AnWebAnalyticsEventAdminChartFormFilter($filterValues);
				
			$this->chartFilters->bind($request->getParameter($this->chartFilters->getName()));
			//		var_dump($request->getParameter($this->chartFilters->getName()));exit;
			if ($this->chartFilters->isValid())
			{
				$this->setChartFilters($this->chartFilters->getRealChartValues());
			}
				
			$this->redirect('@an_web_analytics_event');
		}
	
		$this->pager = $this->getPager();
		$this->sort = $this->getSort();
	
		$this->setTemplate('index');
	}
	
	
	/**
	 * fetch choices for field_name based on field_part_value
	 * 
	 * @param sfWebRequest $request
	 */
	public function executeFetchChoices(sfWebRequest $request) {
		
		$model = sfInflector::camelize($request->getParameter('model'));
		
		if (Doctrine_Core::isValidModelClass($model)) {
		
			$e = $this->getContext()->getEventDispatcher()->notifyUntil(new sfEvent($this, 'an.web_analytics.find_choices', array('model' => $model, 'part_value' => $request->getParameter('term'))));
			if ($e->isProcessed()) {
				
				return $this->renderJson($e->getReturnValue());
			}
		}
		
		
		$this->forward404();
	}
	
	
	
	
	public function executeShowFilters(sfWebRequest $request)
	{
		if ($request->getParameter('type')) {
			
			$filters = $this->getFilters();
			$this->setFilters(array_merge($filters, array('type' => $request->getParameter('type'))));
			
		}
		
		$filters = $this->configuration->getFilterForm($this->getFilters());
		
		$filterValues = array_merge($this->getFilters(), $this->getChartFilters());
		$chartFilters = new AnWebAnalyticsEventAdminChartFormFilter($filterValues);
		
		return $this->renderAsync(array(
				'html' => $this->getPartial('filters', array(
	      	'configuration' => $this->configuration,
	      	'form' => $filters,
	      	'chartFilters' => $chartFilters
				))
		));
	}
	
	
	
	public function executeStickChartToHome(sfWebRequest $request) {
		
		$window = new AnWebAnalyticsAdminHomeWindow();
		$window->setIdUser($this->getUser()->getDmUser()->getId());
		$window->setModuleComponent('anWebAnalyticsEventAdmin/chartForHome');
		
		$filterValues = array_merge($this->getFilters(), $this->getChartFilters());
		$window->setFilter($filterValues);
		
		$window->save();
		
		return $this->renderText('ok');
	}
	
}

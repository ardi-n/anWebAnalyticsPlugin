<?php

/**
 * 
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class BaseanWebAnalyticsPageElementActions extends dmBaseActions {
	
	
	public function executeEdit(sfWebRequest $request) {

		$helper = $this->getHelper();
		
		$pageElement = AnWebAnalyticsPageElementTable::getInstance()->findOneByDomIdAndDmLayoutId($request->getParameter('dom_id'), $request->getParameter('dm_layout_id'));
		
		$e = $this->getContext()->getEventDispatcher()->notifyUntil(new sfEvent($this, 'an.web_analytics.determine_element_page_field', array(
					    	'page_module' => $request->getParameter('page_module')
		)));
		$pageFieldName = $e->isProcessed() ? $e->getReturnValue() : 'page_id';
		
		$form = new AnWebAnalyticsPageElementFrontForm($pageElement, array(
			'page_field_name' => $pageFieldName
		));
		
		if (! $pageElement) {
			$form->setDefault('dom_id', $request->getParameter('dom_id'));
			$form->setDefault('dm_layout_id', $request->getParameter('layout_id'));
			$form->setDefault($pageFieldName, $request->getParameter('page_id'));
		}
		
		if ($request->isMethod('post') && $form->bindAndValid($request))
		{
			$form->save();
		
			return $this->renderText('saved');
		}
		
		
		return $this->renderAsync(array(
		      'html'  => $helper->tag('div.wa.wa_page_element_edit.'.$request->getParameter('id').'_form',
    										array(
    											'json' => array('form_class' => $request->getParameter('id').'_form', 'form_name' => $form->getName()),
    											'data-edit-href' => $helper->link('@wa_edit_page_element')->param('id', $request->getParameter('id'))->getHref(),
    											'data-delete-href' => $helper->link('@wa_delete_page_element')->param('id', $request->getParameter('id'))->getHref()
    										),
    											$form->render('.dm_form.list.little')
    							),
    		  'js'    => array_merge(array('lib.hotkeys'), $form->getJavascripts()),
		      'css'   => $form->getStylesheets()
		), true);
		
	}
	
	
	
	public function executeGetConfiguredElements(sfWebRequest $request) {
	
		$layoutId = $request->getParameter('layout_id');
		
		$configuredElements = AnWebAnalyticsPageElementTable::getInstance()->fetchLayoutPageElements($layoutId);
		
		return $this->renderJson($configuredElements);
	}
	
	
}
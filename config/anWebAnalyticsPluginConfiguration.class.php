<?php


class anWebAnalyticsPluginConfiguration extends sfPluginConfiguration {

  
  public function configure() {
  
    require_once(sfConfig::get('dm_core_dir').'/lib/config/dmConfig.php');
    if (dmConfig::isCli()) {
      // this setting makes it possible to dispatch an event to add relations & columns into AnWebAnalyticsEvent
      myDoctrineTable::setEventDispatcher($this->dispatcher);
    }
    
    if ($this->configuration instanceof frontConfiguration) {
    
      $this->dispatcher->connect('dm.context.loaded', array($this, 'listenToContextLoaded'));
    
      $this->dispatcher->connect('user.sign_in', array($this, 'listenToUserSignIn'));
    
      $this->dispatcher->connect('user.method_not_found', array($this, 'listenToUserMethodNotFound'));
    
      $this->dispatcher->connect('dm.context.change_page', array($this, 'listenToChangePage'));
    
    }
    
    $this->dispatcher->connect('admin.edit_object', array($this, 'listenToEventModification'));
    $this->dispatcher->connect('admin.delete_object', array($this, 'listenToEventModification'));
    
    
    
    $this->dispatcher->connect('dm.admin_homepage.filter_windows', array($this, 'filterAdminWindows'));
    
  }
  
  
  /**
   * listen when user signs in and check whether connection between
   * current visitor and this DmUser exists
   * if no, create one
   * 
   * @param sfEvent $e
   */
  public function listenToUserSignIn(sfEvent $e) {
  
    $user = $e->getSubject();
    $visitor = $user->getWAVisitor();
    
    AnWebAnalyticsVisitorDmUserAssocTable::getInstance()->connectDmUserWithVisitor($visitor, $user->getDmUser());
  }
  
  
  
  /**
   * 
   * notifyUntil Event Listener
   * @param sfEvent $e
   */
  public function listenToUserMethodNotFound(sfEvent $e) {
  
    switch ($e['method']) {
    
      case 'getWAVisitor':
        $WA = sfContext::getInstance()->getServiceContainer()->getService('web_analytics');
        $e->setReturnValue($WA->getVisitor());
        return true;
      break;
      default:
        return false;
      break;
    }
    
  }
  
  
  
  public function listenToChangePage(sfEvent $e) {
  
    $page = $e['page'];
    $context = $e->getSubject();
    
    $WA = $context->getServiceContainer()->getService('web_analytics');
    
    $WA->connectPageWithVisit($page);
    
    $editConfig = $context->getResponse()->getJavascriptConfig();
    $editConfig = isset($editConfig['wa']) ? $editConfig['wa'] : array();
    $editConfig['layout_id'] = $page->getPageView()->getDmLayoutId();
    $editConfig['page_module'] = $page->getModule();
    $editConfig['page_id'] = $page->getId() ? $page->getId() : $page->getRecordId();
    
    if ($context->getUser()->can('manage_web_analytics')) {
    
    	
    }
    
    $context->getResponse()->addJavascriptConfig('wa', $editConfig);
  }
  
  
  
  public function listenToContextLoaded(sfEvent $e) {
  
    $context = $e->getSubject();
    
    if ($context->getConfiguration() instanceof frontConfiguration) {
    
    	$editConfig = array(
    			'get_page_elements_href' => $context->getHelper()->link('@wa_get_page_elements')->getHref()
    		);
    	
    	if ($context->getUser()->can('manage_web_analytics')) {
    		
    		$editConfig['edit_page_element_href'] = $context->getHelper()->link('@wa_edit_page_element')->getHref();
    		
    		$context->getResponse()->addStylesheet('anWebAnalyticsPlugin.configurePageElements');
    		$context->getResponse()->addJavascript('anWebAnalyticsPlugin.adminPage');
    		$context->getResponse()->addJavascript('anWebAnalyticsPlugin.adminPageElement');
    		
    	}
    	else {
    		$editConfig['collect_href'] = $context->getHelper()->link('@wa_collect_data')->getHref();
    		$context->getResponse()->addJavascript('lib.ui-core');
    		$context->getResponse()->addJavascript('lib.ui-widget');
    		$context->getResponse()->addJavascript('lib-page.json2xml');
    		$context->getResponse()->addJavascript('anWebAnalyticsPlugin.page');
    		$context->getResponse()->addJavascript('anWebAnalyticsPlugin.pageElement');
    	}
    	
    	$context->getResponse()->addJavascriptConfig('wa', $editConfig);
    	$context->getResponse()->addJavascript('anWebAnalyticsPlugin.frontStart');
    } 
    
    /*
     * if widget is saved successfully then trigger saving procedure of pending area
     */
//    dmDb::table('DmWidget')->addRecordListener(new AnDmWidgetListener());
  }
  
  
  
 /**
  * Web Analytics Events are not editable nor creatable
  *
  * @param sfEvent $event
  */
  public function listenToEventModification(sfEvent $event) {
  	 
  	$object = $event['object'];
  	 
  	if ($object instanceof AnWebAnalyticsEvent) {
  
  		$event->getSubject()->forward404();
  	}
  }
  

  
  public function filterAdminWindows(sfEvent $e, array $windows) {
  	
  	$windows[0] = array();
  	unset($windows[1]['visitChart']);
  	
  	$definedWindows = AnWebAnalyticsAdminHomeWindowTable::getInstance()->findByIdUser(sfContext::getInstance()->getUser()->getDmUser()->getId());
  	
  	foreach ($definedWindows as $k => $definedWindow) {
  		
  		$moduleComponent = $definedWindow->getModuleComponent();
  		$moduleComponent = explode('/', $moduleComponent);
  		$col = $k % 3;
  		$windows[$col][] = sfContext::getInstance()->getHelper()->renderComponent($moduleComponent[0], $moduleComponent[1], array('filterValues' => $definedWindow->getFilter()));
  	}
  	
  	return $windows;
  }
  
  
  
}
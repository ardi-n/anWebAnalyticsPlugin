<?php


class AnWebAnalyticsFilter extends sfFilter {


  /**
   * Executes this filter.
   *
   * @param sfFilterChain $filterChain A sfFilterChain instance
   */
  public function execute($filterChain)
  {
    
//    $filterChain->execute();
    /*
     * form submissions (POST request) are likely to be measured
     * I have to find generic way to see if there was a validation error
     * if not so, submission can be logged as successful
     */
    if ($this->getContext()->getRequest()->isXmlHttpRequest() || ! $this->getContext()->getRequest()->isMethod('get')) {
    
      $filterChain->execute();
      return;
    }
    
    $visitorCookie = sfConfig::get('app_anWebAnalyticsPlugin_visitor_cookie', 'krecisie-visitor');
   
    $visitorIdentity = $this->getContext()->getRequest()->getCookie($visitorCookie);
    
    // THIS IS FIRST TIME VISITOR OR SHE DELETED IDENTIFICATION COOKIE
    if (! $visitorIdentity) {
    
      $visitorIdentity = $this->calculateNewIdentity();
      
      // SET COOKIE THAT WILL LAST ABOUT 20 YEARS FROM NOW
      $this->getContext()->getResponse()->setCookie($visitorCookie, $visitorIdentity, time()+20*365*24*60*60);
      
      $visitor = new AnWebAnalyticsVisitor();
      $visitor->fromArray(array(
          'user_cookie_id' => $visitorIdentity,
          'browser' => sprintf('%s|%s', $this->getContext()->get('browser')->getName(), $this->getContext()->get('browser')->getVersion()),
          'os' => $this->getContext()->get('browser')->getOperatingSystem()
        ));
    }
    else {
    
      $visitor = dmDb::table('AnWebAnalyticsVisitor')->find($visitorIdentity);
      
      if (! $visitor) {
      
        $visitorIdentity = $this->calculateNewIdentity();
        // SET COOKIE THAT WILL LAST ABOUT 20 YEARS FROM NOW
        $this->getContext()->getResponse()->setCookie($visitorCookie, $visitorIdentity, time()+20*365*24*60*60);
        $visitor = new AnWebAnalyticsVisitor();
        $visitor->fromArray(array(
          'user_cookie_id' => $visitorIdentity,
          'browser' => sprintf('%s|%s', $this->getContext()->get('browser')->getName(), $this->getContext()->get('browser')->getVersion()),
          'os' => $this->getContext()->get('browser')->getOperatingSystem()
        ));
      }
    }
    
    
    $visit = dmDb::table('AnWebAnalyticsVisit')->find(session_id());
    
    if (! $visit) {
    
      $visit = new AnWebAnalyticsVisit();
      $visit->fromArray(array(
        'visit_cookie_id' => session_id(),
        'user_cookie_id' => $visitorIdentity,
        'referer' => $this->getContext()->getRequest()->getReferer(),
        'visitor_ip' => $this->getContext()->getRequest()->getRemoteAddress()
      ));
    }
    
    /*
     * NOW I CREATE BINDING BETWEEN PAGE AND VISITOR SESSION
     * LATER I'LL UPDATE ASSOCIATIVE RECORD WITH START AND END TIME OF PAGE VISIT
     * THROUGH AJAX CALL MADE ON UNLOADING PAGE
     */ 
    
    $visitor->Visits[] = $visit;
    
    $visitor->save();
    
    $filterChain->execute();
  }
  
  
  
  protected function calculateNewIdentity() {
  
    $visitorIdentity = md5( sprintf('%s-%d-%d', $this->getContext()->getRequest()->getRemoteAddress(), time(),  mt_rand(0, 100000)) );
    
    return $visitorIdentity;
  }
  
  
  
}
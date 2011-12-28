<?php



echo _link(array('sf_route' => 'an_web_analytics_visit', 'action' => 'filter'))->param(
	'an_web_analytics_visit_form_filter', array(
		'user_cookie_id' => $an_web_analytics_visitor['user_cookie_id']
)
)->text(__("Show user's visits"));

echo ' ';

echo _link(array('sf_route' => 'an_web_analytics_event', 'action' => 'filter'))->param(
	'an_web_analytics_event_form_filter', array(
		'visitor_cookie_id' => $an_web_analytics_visitor['user_cookie_id']
)
)->text(__("Show user's actions"));
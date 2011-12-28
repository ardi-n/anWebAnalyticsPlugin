<?php


echo _link(array('sf_route' => 'an_web_analytics_visit_page_assoc', 'action' => 'filter'))->param(
	'an_web_analytics_visit_page_assoc_form_filter', array(
		'visit_cookie_id' => $an_web_analytics_visit['visit_cookie_id']
	)
)->text(__("Show visit's pageviews"));
<?php


echo _link(array('sf_route' => 'an_web_analytics_event', 'action' => 'filter'))->param(
	'an_web_analytics_event_form_filter', array(
		'page_visit_id' => $an_web_analytics_visit_page_assoc['id']
)
)->text(__("Show events"));
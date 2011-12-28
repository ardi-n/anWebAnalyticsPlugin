<?php

use_helper('Date');
use_helper('WA');

use_javascript('anWebAnalyticsPlugin.flot');
use_javascript('anWebAnalyticsPlugin.flot-resize');
use_javascript('anWebAnalyticsPlugin.flot-crosshair');
use_javascript('anWebAnalyticsPlugin.backendChart');
use_javascript('anWebAnalyticsPlugin.chartInit');

use_stylesheet('anWebAnalyticsPlugin.backendChart');

if (isset($error)) {
	echo $error;
	return;
}

$nbData = count($data);
$labelStep = ceil($nbData / 60) + 3;

echo _open('div.wa-chart-container');

echo _open('table.wa-chart-data');

	echo _open('tfoot');
	
		echo _open('tr');
		foreach ($data as $k => $item) {
	
			$showAxisLabel = $k % $labelStep == 0 || $k == count($data)-1;
			$label = wa_format_label($item, $k>0 ? $data[$k-1] : null, $groupby);
			$longLabel = wa_format_label($item, $k>0 ? $data[$k-1] : null, $groupby, true);
			
			echo _tag('th', array('data-long-label' => __('%c% new visitors on %d%', array('%c%' => $item['COUNT'], '%d%' => $longLabel))), $showAxisLabel ? $label : '');
		}
		echo _close('tr');
	echo _close('tfoot');


	echo _open('tbody');
	
	echo _open('tr');
	foreach ($data as $item) {
	
		echo _tag('td', $item['COUNT']);
	}
	echo _close('tr');
	
	echo _close('tbody');

 
echo _close('table');

echo _tag('div.wa-chart-holder');


use_javascripts_for_form($chartFilters);

echo _link(array('sf_route' => 'an_web_analytics_visit', 'action' => 'stickChartToHome'))->text(__('Stick chart with search parameters set up to homepage'));

//print_r($data);
<?php

use_helper('Date');

use_javascript('anWebAnalyticsPlugin.flot');
use_javascript('anWebAnalyticsPlugin.flot-resize');
use_javascript('anWebAnalyticsPlugin.flot-crosshair');
use_javascript('anWebAnalyticsPlugin.backendChart');
use_javascript('anWebAnalyticsPlugin.chartInit');

use_stylesheet('anWebAnalyticsPlugin.backendChart');

echo _open('div.dm_box');

	echo _tag('div.title', _tag('h2', __('Custom Analytics Chart')));

	echo _open('div.dm_box_inner.dm_data.m5');
	
		echo _open('div.wa-chart-container');
		
		echo _open('table.wa-chart-data');
		
			echo _open('tfoot');
			
				echo _open('tr');
				foreach ($data as $k => $item) {
			
					$showAxisLabel = $k == 0 || $k == count($data)-1 || $k == ceil((count($data)-1)/2);
					echo _tag('th', array('data-long-label' => __('%c% events on %d%', array('%c%' => $item['COUNT'], '%d%' => format_date($item['created_at'], 'D')))), $showAxisLabel ? format_date($item['created_at'], 'd') : '');
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
		
		
		echo _close('div');// container

	echo _close('div');//.dm_box_inner
	
echo _close('div');//.dm_box
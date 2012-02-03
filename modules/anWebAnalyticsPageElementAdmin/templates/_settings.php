<?php

echo _open('dl');

foreach ($an_web_analytics_page_element['settings'] as $setting => $value) {
	
	echo _tag('dt', __('Log "%event%" events', array('%event%' => str_replace(array('AnWebAnalytics', 'Event'), '', $setting))));
	if ($value == 1) {
	
		echo _tag('dd', _tag('span.s16block.s16_tick'));
	}
	elseif ($value == 0) {
		echo _tag('dd', _tag('span.s16block.s16_cross'));
	}
	
}

echo _close('dl');
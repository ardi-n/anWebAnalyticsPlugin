<?php

echo _open('dl');

foreach ($an_web_analytics_page_element['settings'] as $setting => $value) {
	
	echo _tag('dt', $setting);
	echo _tag('dd', $value);
}

echo _close('dl');
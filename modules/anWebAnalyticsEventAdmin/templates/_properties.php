<?php

echo _open('dl');
foreach ($an_web_analytics_event['properties'] as $name => $value) {

	if (! $value) continue;
	echo _tag('dt', sfInflector::humanize($name));
	echo _tag('dd', $value);

}
echo _close('dl');
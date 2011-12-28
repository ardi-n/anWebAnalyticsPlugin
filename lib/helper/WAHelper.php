<?php


function wa_format_label($item, $prevItem = null, $groupby, $long = false, $createdAtField = 'created_at') {
	
	$l = '';
	
	if ($groupby == 'minute') {
		
		if ($prevItem && $prevItem['hour'] != $item['hour']) {
			$l .= str_pad($item['hour'], 2, '0', STR_PAD_LEFT).':';
		}
		$l .= str_pad($item['minute'], 2, '0', STR_PAD_LEFT);
		
		return $l;
	}
	elseif ($groupby == 'hour') {
		
		if ($prevItem && $prevItem['day'] != $item['day']) {
			$l .= date('D', strtotime($item[$createdAtField])) . ' ';
		}
		$l .= str_pad($item['hour'], 2, '0', STR_PAD_LEFT).':00';
		
		return $l;
	}
	elseif ($groupby == 'day') {
	//var_dump($item);
		return format_date($item[$createdAtField], $long ? 'P' : 'p');
	}
	elseif ($groupby == 'week') {
	
		return format_daterange(
								strtotime($item['year'].'W'.$item['week']),
								strtotime($item['year'].'W'.($item['week']+1)),
								$long ? 'P' : 'p',
								'%s - %s',
								'%s',
								'%s');
	}
	elseif ($groupby == 'month') {
	
		return date('F Y', strtotime($item[$createdAtField]));
	}
	else {
		return $item['year'];
	}
}
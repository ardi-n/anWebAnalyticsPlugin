<?php

/**
 * 
 * 
 * @author Adrian Nowicki <me@adrian-nowicki.com>
 *
 */
class AnWebAnalyticsAdminChartFormFilterHelper {
	
	public static
		$period_choices = array('minute' => 'Minute', 'hour' => 'Hour', 'day' => 'Day', 'week' => 'Week', 'month' => 'Month'),
	
		$period_formats = array('minute' => 'i', 'hour' => 'H', 'day' => 'j', 'week' => 'W', 'month' => 'n', 'year' => 'Y');
	
	
	
	public
		$dateTimeField = 'created_at';
	
	
	
	
	public function addPeriodGroupbyColumnQuery($query, $field, $value) {
	
		$a = $query->getRootAlias();
		$dateTimeField = $this->dateTimeField;
	
		$query
		->addSelect("YEAR($a.$dateTimeField) AS year")
		->addSelect("MONTH($a.$dateTimeField) AS month")
		->addSelect("WEEKOFYEAR($a.$dateTimeField) AS week")
		->addSelect("DAYOFMONTH($a.$dateTimeField) AS day")
		->addSelect("HOUR(TIME($a.$dateTimeField)) AS hour")
		->addSelect("MINUTE(TIME($a.$dateTimeField)) AS minute");
	
		switch ($value) {
				
			case 'minute':
				$query
				->addGroupBy("year, day, hour, minute");
				break;
			case 'hour':
				$query
				->addGroupBy("year, day, hour");
				break;
			case 'week':
				$query
				->addGroupBy("year, week");
				break;
			case 'month':
				$query
				->addGroupBy("year, month");
				break;
			default:
				$query
			->addGroupBy("year, month, day");
			break;
		}
	}
	
	
	
	
	
	public function getRealChartFilters() {
	
		return array(
				'period_groupby'
		);
	}
	
	
	public function getRealChartValues($values) {
	
		$realChartValueNames = $this->getRealChartFilters();
		$realChartValues = array();
	
		foreach ($realChartValueNames as $realChartValueName) {
	
			if (isset($values[$realChartValueName])) $realChartValues[$realChartValueName] = $values[$realChartValueName];
		}
	
		return $realChartValues;
	}
	
	
	
	
	public function getFinalData($data, $period) {
	
		$periods = array_keys(self::$period_formats);
		// chosen period
		$periodIndex = array_search($period, $periods);
		$greaterPeriod = $periods[$periodIndex+1];
	
		$modData = array();
		foreach ($data as $k => $item) {
	
//			if (count($modData) > 60) {var_dump($period, $modData);exit;}//throw new sfException('The number of points on X-axis exceeds 60 thus making chart hard to read. Try to narrow down the results.');
				
			if ($k > 0) {
					
				$prevItem = $data[$k-1];
	
	
				if ($prevItem[$greaterPeriod] != $item[$greaterPeriod] || $prevItem[$period] != $item[$period]-1) {
	
					$tempGreaterPeriod = $prevItem[$greaterPeriod];
					$tempPeriod = $prevItem[$period];
					$tempCreatedAt = date('Y-m-d H:i:s', strtotime("+1 {$period}s", strtotime($prevItem[$this->dateTimeField])));
					
					for (; $tempCreatedAt <= $item[$this->dateTimeField]; $tempCreatedAt = date('Y-m-d H:i:s', strtotime("+1 {$period}s", strtotime($tempCreatedAt)))) {
						
						$tempTime = strtotime($tempCreatedAt);
						
						$tempPeriod = date(self::$period_formats[$period], $tempTime);
						$tempPeriod = $tempPeriod[0] == '0' ? $tempPeriod[1] : $tempPeriod;
						
						$tempGreaterPeriod = date(self::$period_formats[$greaterPeriod], $tempTime);
						$tempGreaterPeriod = $tempGreaterPeriod[0] == '0' ? $tempGreaterPeriod[1] : $tempGreaterPeriod;
						$tempYear = date('Y', $tempTime);
						 
						if ($tempGreaterPeriod != $item[$greaterPeriod] || $tempPeriod != $item[$period]) {
							
							$modDataItem = array('COUNT' => 0, $greaterPeriod => $tempGreaterPeriod,  $period => $tempPeriod, $this->dateTimeField => $tempCreatedAt);
							$modDataItem['year'] = $tempYear;
							
							$modData[] = $modDataItem;
						}
					}
					/*
					while ($tempGreaterPeriod != $item[$greaterPeriod] || $tempPeriod != $item[$period]) {
	
						$tempCreatedAt = date('Y-m-d H:i:s', strtotime("+1 {$period}s", strtotime($tempCreatedAt)));
	
						$tempPeriod = date(self::$period_formats[$period], strtotime($tempCreatedAt));
						$tempPeriod = $tempPeriod[0] == '0' ? $tempPeriod[1] : $tempPeriod;
	
						$tempGreaterPeriod = date(self::$period_formats[$greaterPeriod], strtotime($tempCreatedAt));
						$tempGreaterPeriod = $tempGreaterPeriod[0] == '0' ? $tempGreaterPeriod[1] : $tempGreaterPeriod;
						//var_dump($tempPeriod, $item[$period], $tempGreaterPeriod, $item[$greaterPeriod], $tempCreatedAt);exit;
						$tempYear = date('Y', strtotime($tempCreatedAt));
						$modDataItem = array('COUNT' => 0, $greaterPeriod => $tempGreaterPeriod,  $period => $tempPeriod, $this->dateTimeField => $tempCreatedAt);
						$modDataItem['year'] = $tempYear;
	
						$modData[] = $modDataItem;
					}
						
						*/
				}
	  
				$modData[] = $item;
	
			}
			else {
				$modData[] = $item;
			}
		}
	//var_dump($period, $modData);exit;
		return $modData;
	}
	
}
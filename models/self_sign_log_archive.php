<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class SelfSignLogArchive extends AppModel {
    var $name = 'SelfSignLogArchive';
    var $belongsTo = array(
	'User' => array(
	    'className' => 'User',
	    'foreignKey' => 'user_id'
	),
	'Kiosk' => array(
	    'className' => 'Kiosk',
	    'foreignKey' => 'kiosk_id'
	),
	'Location' => array(
	    'className' => 'Location',
	    'foreignKey' => 'location_id'
	),
	'Admin' => array(
	    'className' => 'user',
	    'foreignKey' => 'last_activity_admin_id'
	)
    );

    function  afterFind($results, $primary = false) {
	parent::afterFind($results, $primary);
	foreach ($results as $key => $value) {
	    if(isset ($value['SelfSignLogArchive']['created'], $value['SelfSignLogArchive']['closed'])) {
		$created = strtotime($value['SelfSignLogArchive']['created'] );
		$closed = strtotime($value['SelfSignLogArchive']['closed']);
		$closedIn = $closed - $created;
		$results[$key]['SelfSignLogArchive']['closed_in'] = $this->_time_duration($closedIn);
	    }
	    else {
		$results[$key]['SelfSignLogArchive']['closed_in'] = null;
	    }
	    if(isset($value['SelfSignLogArchive']['created'])) {
		$results[$key]['SelfSignLogArchive']['created'] = $this->formatDateTimeAfterFind($value['SelfSignLogArchive']['created']);
	    }
	    if(isset($value['SelfSignLogArchive']['modified'])) {
		$results[$key]['SelfSignLogArchive']['modified'] = $this->formatDateTimeAfterFind($value['SelfSignLogArchive']['modified']);
	    }
	    if(isset($value['SelfSignLogArchive']['closed'])) {
		$results[$key]['SelfSignLogArchive']['closed'] = $this->formatDateTimeAfterFind($value['SelfSignLogArchive']['closed']);
	    }
	}
	return $results;
    }

    function _time_duration($seconds, $use = null, $zeros = false) {
	// Define time periods
	$periods = array(
	    'years' => 31556926,
	    'Months' => 2629743,
	    'weeks' => 604800,
	    'days' => 86400,
	    'hours' => 3600,
	    'minutes' => 60,
	    'seconds' => 1
	);

	// Break into periods
	$seconds = (float) $seconds;
	$segments = array();
	foreach ($periods as $period => $value) {
	    if ($use && strpos($use, $period[0]) === false) {
		continue;
	    }
	    $count = floor($seconds / $value);
	    if ($count == 0 && !$zeros) {
		continue;
	    }
	    $segments[strtolower($period)] = $count;
	    $seconds = $seconds % $value;
	}

	// Build the string
	$string = array();
	foreach ($segments as $key => $value) {
	    $segment_name = substr($key, 0, -1);
	    $segment = $value . ' ' . $segment_name;
	    if ($value != 1) {
		$segment .= 's';
	    }
	    $string[] = $segment;
	}

	return implode(', ', $string);
    }

}
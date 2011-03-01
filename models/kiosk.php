<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class Kiosk extends AppModel {

    var $name = 'Kiosk';
    var $validate = array(
	'location_id' => array(
	    'notempty' => array(
		'rule' => array('notempty'),
		'message' => 'Please provide a location name',
	    //'allowEmpty' => false,
	    //'required' => false,
	    //'last' => false, // Stop validation after this rule
	    //'on' => 'create', // Limit validation to 'create' or 'update' operations
	    ),
	),
	'location_recognition_name' => array(
	    'notempty' => array(
		'rule' => array('notempty'),
		'message' => 'Please provide a location recognition name',
	    //'allowEmpty' => false,
	    //'required' => false,
	    //'last' => false, // Stop validation after this rule
	    //'on' => 'create', // Limit validation to 'create' or 'update' operations
	    ),
	),
    );
    var $hasMany = array(
	'KioskButton' => array(
	    'className' => 'KioskButton',
	    'foreignKey' => 'kiosk_id'
	),
	'SelfSignLog' => array(
	    'className' => 'SelfSignLog',
	    'foreignKey' => 'kiosk_id'
	),
	'SelfSignLogArchive' => array(
	    'className' => 'SelfSignLogArchive',
	    'foreignKey' => 'kiosk_id'
	)
    );

    var $belongsTo = array(
	    'Location' => array(
		    'className' => 'Location',
		    'foreignKey' => 'location_id',
		    'conditions' => '',
		    'fields' => '',
		    'order' => ''
	    )
    );
    
    function getKioskLocationId() {
	$oneStop = env('HTTP_USER_AGENT');
	$arrOneStop = explode('##', $oneStop);
	if(isset($arrOneStop[1])) {
	$oneStopLocation = $arrOneStop[1];
	    $kiosk = $this->find('first',
			    array('conditions' => array(
				'Kiosk.location_recognition_name' => $oneStopLocation,
				'Kiosk.deleted' => 0)));
	}

	if(isset($kiosk)) {
	    return $kiosk['Kiosk']['location_id'];
	}
	else return false;
    }

    function delete($id = null) {
	if($id) {
	    $data['Kiosk']['id'] = $id;
	    $data['Kiosk']['deleted'] = 1;
	    if ($this->save($data)) {
		return true;
	    }
	}
	return false;
    }
}
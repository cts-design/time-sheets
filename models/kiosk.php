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
    
	var $hasAndBelongsToMany = array(
		'KioskSurvey' => array(
			'className' => 'KioskSurvey',
			'joinTable' => 'kiosks_kiosk_surveys',
			'foreign_key' => 'kiosk_id',
			'associationForeignKey' => 'kiosk_survey_id',
			'unique' => false
		)
	);
	
	var $actsAs = array('Containable');
	
    function getKioskLocationId() {
	$oneStop = env('HTTP_USER_AGENT');
	$arrOneStop = explode('##', $oneStop);
	if(isset($arrOneStop[1])) {
	$oneStopLocation = $arrOneStop[1];
	$kiosk = $this->find('first',
		array('conditions' => array('Kiosk.location_recognition_name' => $oneStopLocation, 'Kiosk.deleted' => 0),
		'recursive' => -1
		));
	}

	if(isset($kiosk)) {
	    return $kiosk['Kiosk']['location_id'];
	}
	else return false;
    }

	function getKioskName($kioskId) {
		$this->id = $kioskId;
		$name = $this->field('location_description');
		if($name) {
			return $name;
		}
		else return false;
	}

	function isKiosk($defaultKiosk = '')
	{
		$oneStop = env('HTTP_USER_AGENT');
		$arrOneStop = explode('##', $oneStop);

		$oneStopLocation = (isset($arrOneStop[1]) && $arrOneStop[1] != '' ? $arrOneStop[1] : $defaultKiosk);

		$this->recursive = -1;
		$this->Behaviors->attach('Containable');
		$this->contain(array('KioskSurvey', 'Location'));

		$kiosk = $this->find('first', array(
			'conditions' => array(
				'location_recognition_name' => $oneStopLocation,
				'deleted' => 0
			)
		));

		return $kiosk;
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

    public function redirectToSurvey()
    {
    	// Redirects to a survey if: Kiosk Survey == 'forced'
    	$this->loadModel('Setting');
		$kiosk_survey_setting = $this->Setting->getSettings('Kiosk', 'Survey');

		if($kiosk_survey_setting == 'force')
		{
			$this->redirect('/kiosk/kiosks/survey/' . $kiosk['Kiosk']['id']);
		}
		else if($kiosk_survey_setting == 'prompt')
		{
			$this->redirect('/kiosk/kiosks/prompt/' . $kiosk['Kiosk']['id']);
		}
    }
}

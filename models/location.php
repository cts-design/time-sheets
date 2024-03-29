<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class Location extends AppModel {
	var $name = 'Location';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $validate =  array(
	    'name' => array(
		'rule' => array('notempty'),
		'message' => 'Please provide a name for the location.'
	    )
	);
	var $hasMany = array(
		'FtpDocumentScanner' => array(
			'className' => 'FtpDocumentScanner',
			'foreignKey' => 'location_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'User' => array(
		    'className' => 'User',
		    'foreignKey' => 'location_id',
		    'dependent' => false
		),
		'Kiosk' => array(
		    'className' => 'Kiosk',
		    'foreignKey' => 'location_id',
		    'dependent' => false
		),
		'SelfSignLog' => array(
		    'className' => 'SelfSignLog',
		    'foreignKey' => 'location_id',
		    'dependent' => false
		),
		'SelfSignLogArchive' => array(
		    'className' => 'SelfSignLogArchive',
		    'foreignKey' => 'location_id',
		    'dependent' => false
		),
		'QueuedDocument' => array(
		    'className' => 'QueuedDocument',
		    'foreignKey' => 'scanned_location_id'
		),
		'Event' => array(
			'className' => 'Event',
			'foreignKey' => 'location_id'
		)
	);

    function delete($id = null) {
	if($id) {
	    $data['Location']['id'] = $id;
	    $data['Location']['deleted'] = 1;
	    if ($this->save($data)) {
		return true;
	    }
	}
	return false;
    }
	
	function getLocationName($locationId) {
		$this->id = $locationId;
		$name = $this->field('name');
		if($name) {
			return $name;
		}
		else {
			return false;
		}
	}
    
    function findAllNotHidden() {
        return $this->find('list', array(
            'fields' => array('Location.public_name', 'Location.public_name'),
            'conditions' => array('Location.hidden !=' => '1')
        ));
    }
}

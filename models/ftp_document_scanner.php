<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class FtpDocumentScanner extends AppModel {
	var $name = 'FtpDocumentScanner';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	var $validate = array(
	    'device_ip' => array(
		'ip' => array(
		    'rule' => 'ip',
		    'message' => 'Please provide a valid IP address'
		),
		'notEmpty' => array(
		    'rule' => array('notempty'),
		    'message' => 'Please provide a IP address for the device'
		)
	    ),
	    'device_name' => array(
		'notEmpty' => array(
		    'rule' => array('notempty'),
		    'message' => 'Please provide a name for the device'
		)
	    ),
	    'location_id' => array(
		'notEmtpy' => array(
		    'rule' => array('notempty'),
		    'message' => 'Please select the device location'
		)
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

}

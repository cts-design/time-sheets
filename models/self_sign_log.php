<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class SelfSignLog extends AppModel {

    var $name = 'SelfSignLog';
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
		    'className' => 'User',
		    'foreignKey' => 'last_activity_admin_id'
		)
    );
    var $validate = array(
		'other' => array(
		    'other' => array(
			'rule' => array('notempty'),
			'message' => 'Please provide a description.',
			'on' => 'create'
		    )
		)
    );
}
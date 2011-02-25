<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class KioskButton extends AppModel {

    var $name = 'KioskButton';
    var $primaryKey = 'button_id';

    

    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
	'Kiosk' => array(
	    'className' => 'Kiosk',
	    'foreignKey' => 'kiosk_id',
	    'conditions' => '',
	    'fields' => '',
	    'order' => ''
	),
	'MasterKioskButton' => array(
	    'className' => 'MasterKioskButton',
	    'foreignKey' => 'id'
	)
    );


}
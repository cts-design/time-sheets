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
	
	function getLogoutMessage($id=null, $buttonId=null, $kioskId) {
		if(!$buttonId && !$id){
			return false;
		}
		if($buttonId) {
			$button = $this->find('first', array('conditions' => array(
				'KioskButton.button_id' => $buttonId,
				'KioskButton.kiosk_id' => $kioskId)));	
		}
		if($id) {
			$button = $this->find('first', array('conditions' => array(
				'KioskButton.id' => $id,
				'KioskButton.kiosk_id' => $kioskId)));
		}	
		$message = $button['KioskButton']['logout_message'];	
		if($message) {
			return $message;
		}
		else {
			return false;
		}
	}

}
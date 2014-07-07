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
	
	function getLogoutMessage($id = FALSE, $button_id = FALSE, $kiosk_id = FALSE)
	{
		if(!!$button_id && !$id) //If button id is true and the ID is false
		{
			$button = $this->find('first', array(
				'conditions' => array(
					'KioskButton.button_id' => $button_id
				)
			));
		}
		else if(!$button_id && !!$id && !!$kiosk_id) // if button id is false but the id and kiosk id have been passed
		{
			$button = $this->find('first', array(
				'conditions' => array(
					'KioskButton.id' => $id,
					'KioskButton.kiosk_id' => $kiosk_id
				)
			));
		}
		else
		{
			return FALSE;
		}

		if($button['KioskButton']['action'] == 'logout')
		{
			return $button['KioskButton']['action_meta'];
		}
		else
		{
			return FALSE;
		}
	}

}
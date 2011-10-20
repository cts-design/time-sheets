<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class MasterKioskButton extends AppModel {

    var $name = 'MasterKioskButton';
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    var $validate = array(
	'name' => array(
	    'notempty' => array(
		'rule' => array('notempty'),
		'message' => 'Please provide a name for the button',
	    ),
	    'maxLength' => array(
		'rule' => array('maxLength', 30),
		'message' => 'Button name must not be longer than 30 characters long.'
	    )
	)
    );
	
	var $actsAs = array(
		'Translatable' => array(
			'name'
		),
		'AtlasTranslate' => array(
			'name'
		)
	);

    var $hasMany = array(
	'KioskButton' => array(
	'className' => 'KioskButton',
	'foreignKey' => 'id'
    ));

    function delete($id = null) {
	if($id) {
	    $data['MasterKioskButton']['id'] = $id;
	    $data['MasterKioskButton']['deleted'] = 1;
	    if ($this->save($data, false)) {
		return true;
	    }
	}
	return false;
    }
	
	function aftersave() {
		
	}

}

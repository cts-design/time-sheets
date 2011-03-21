<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class FiledDocument extends AppModel {
	var $name = 'FiledDocument';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Admin' => array(
			'className' => 'User',
			'foreignKey' => 'admin_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'LastActAdmin' => array(
			'className' => 'User',
			'foreignKey' => 'last_activity_admin_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'Cat1' => array(
		    'className' => 'DocumentFilingCategory',
		    'foreignKey' => 'cat_1'
		),
		'Cat2' => array(
		    'className' => 'DocumentFilingCategory',
		    'foreignKey' => 'cat_2'
		),
		'Cat3' => array(
		    'className' => 'DocumentFilingCategory',
		    'foreignKey' => 'cat_3'
		),
		'Location' => array(
		    'className' => 'Location',
		    'foreignKey' => 'filed_location_id'
		)
	);

	var $validate = array(
	    'filename' => array(
		'notEmpty' => array(
		    'rule' => 'notEmpty',
		    'message' => 'Filename is required'
		)
	    ),
	    'admin_id' => array(
		'notEmpty' => array(
		    'rule' => 'notEmpty',
		    'message' => 'Admin Id required'
		)
	    ),
	    'filed_location_id' => array(
		'notEmpty' => array(
		    'rule' => 'notEmpty',
		    'message' => 'Filed location is required'
		)
	    ),
	    'id' => array(
		'notEmpty' => array(
		    'rule' => 'notEmpty',
		    'message' => 'Id is required'
		)
	    )
	 );

    function  beforeDelete($cascade = true) {
	parent::beforeDelete($cascade);
	if(isset($this->data['FiledDocument'])) {
		$adminId = $this->data['FiledDocument']['last_activity_admin_id'];
		$reason = $this->data['FiledDocument']['reason'];
		$deletedLocation = $this->data['FiledDocument']['deleted_location_id'];		
	}
	$delDoc = ClassRegistry::init('DeletedDocument');
	$this->recursive = -1;
	$doc = $this->read(null, $this->id);
	if($doc) {
		foreach($doc as $k => $v) {
		    $this->data['DeletedDocument'] = $v;
		    $this->data['DeletedDocument']['last_activity_admin_id'] = $adminId;
		    $this->data['DeletedDocument']['deleted_reason'] = $reason;
		    $this->data['DeletedDocument']['deleted_location_id'] = $deletedLocation;
		    unset($this->data['DeletedDocument']['modified']);
		    unset($this->data['FiledDocument']);
		}		
	}
	if($delDoc->save($this->data)) {
	    return true;
	}
	return false;
    }
}

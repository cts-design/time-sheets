<?php
class DeletedDocument extends AppModel {
	var $name = 'DeletedDocument';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Admin' => array(
			'className' => 'User',
			'foreignKey' => 'admin_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
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
		'Location' => array(
			'className' => 'Location',
			'foreignKey' => 'deleted_location_id',
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
		)
	);
}

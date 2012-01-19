<?php
class DocumentQueueFilter extends AppModel {
	var $name = 'DocumentQueueFilter';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'User' => array(
			'className' => 'User',
			'foreignKey' => 'user_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $validate = array(
	    'user_id' => array(
			'unique' => array(
			    'rule' => 'unique',
			    'message' => 'Filters already exist for this user',
			    'on' => 'create'
			)
	    )
	);	
}  
?>
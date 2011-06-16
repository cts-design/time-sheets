<?php
class WatchedFilingCat extends AppModel {
	var $name = 'WatchedFilingCat';
	//The Associations below have been created with all possible keys, those that are not needed can be removed
	


	
	var $belongsTo = array(
		'Program' => array(
			'className' => 'Program',
			'foreignKey' => 'program_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'DocumentFilingCategory' => array(
			'className' => 'DocumentFilingCategory',
			'foreignKey' => 'cat_id')		
	);
}
?>
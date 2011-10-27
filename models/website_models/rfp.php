<?php
class Rfp extends AppModel {
	var $name = 'Rfp';
	var $displayField = 'title';
    var $actsAs = array(
    	'Translatable' => array(
			'title', 'byline', 'description'
		),
		'AtlasTranslate' => array(
			'title', 'byline', 'description'
		)
    );	
	var $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must include a title'
			)
		),
		'byline' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must include a byline'
			)		
		),
		'description' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must include a description'
			)		
		),
		'deadline' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must include a deadline'
			)		
		),
		'expires' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must include a expiration'
			)		
		),
	);
}
?>
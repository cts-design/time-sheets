<?php
class HotJob extends AppModel {
	var $name = 'HotJob';
	var $displayField = 'title';
	
	var $validate = array(
		'employer' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must include an employer or check not specified'
			)
		),
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must provide a title for this job'
			)
		),
		'description' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must provide a description for this job'
			)
		)
	);
}
?>
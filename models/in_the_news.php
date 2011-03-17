<?php
class InTheNews extends AppModel {
	var $name = 'InTheNews';
	var $displayField = 'title';
	
	var $validate = array(
		'title' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must provide a title'
			)
		),
		'reporter' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must provide the articles reporter'
			)
		),
		'summary' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must provide a summary'
			)
		),
		'link' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must provide a link to the article'
			)
		),
		'posted_date' => array(
			'notEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'You must provide the date the article was posted'
			)
		),
	);
}
?>
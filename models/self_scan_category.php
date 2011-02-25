<?php
class SelfScanCategory extends AppModel {
	var $name = 'SelfScanCategory';
	var $actsAs = array('Tree');
	var $displayField = 'name';

    var $belongsTo = array(
	'DocumentQueueCategory' => array(
	    'className' => 'DocumentQueueCategory',
	    'foreignKey' => 'queue_cat_id'
	),
	'DocumentFilingCategory' => array(
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
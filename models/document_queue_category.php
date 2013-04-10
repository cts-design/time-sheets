<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class DocumentQueueCategory extends AppModel {
	var $name = 'DocumentQueueCategory';
	var $displayField = 'name';

	var $hasMany = array(
	    'QueuedDocument' => array(
			'className' => 'QueuedDocument',
			'foreignKey' => 'queue_category_id'
	    ),
	    'BarCodeDefinition' => array(
			'className' => 'BarCodeDefinition'
		));

	var $validate = array(
	    'ftp_path' => array(
		'rule' => array('notempty'),
		'message' => 'Please provide the FTP path'
	    ),
	    'name' => array(
		'rule' => array('notempty'),
		'message' => 'Please provide a name for the category'
	    )
	);
}

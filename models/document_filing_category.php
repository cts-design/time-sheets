<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class DocumentFilingCategory extends AppModel {
	var $name = 'DocumentFilingCategory';
	
	var $actsAs = array('Tree');
	
	var $hasMany = array(
		'WatchedFilingCat' => array(
			'className' => 'WatchedFilingCat'
		),
		'BarCodeDefinition' => array(
			'className' => 'BarCodeDefinition'
		)		
		
	);
	
	var $validate = array(
	    'name' => array(
		'notEmpty' => array(
		    'rule' => array('notempty'),
		    'message' => 'Please provide a name for the category.'
		)  
	    )
	);

	function isSecure($catId) {
		$this->id = $catId;
		return $this->field('secure');
	}

    function delete($id = null) {
	if($id) {
	    $data['DocumentFilingCategory']['id'] = $id;
	    $data['DocumentFilingCategory']['deleted'] = 1;
	    if ($this->save($data)) {
		return true;
	    }
	}
	return false;
    }
}
<?php
/* SelfSignLog Test cases generated on: 2010-09-30 15:09:08 : 1285861928*/
App::import('Model', 'SelfSignLog');
App::import('Lib', 'AtlasTestCase');

class SelfSignLogTestCase extends AtlasTestCase {

	function startTest() {
		$this->SelfSignLog =& ClassRegistry::init('SelfSignLog');
	}
	
	function testValidation() {
		$this->SelfSignLog->create();		
		$invalidData = array(
			'SelfSignLog' => array(
				'other' => ''
			)
		);			
		$this->SelfSignLog->save($invalidData);
		$invalidFields = $this->SelfSignLog->invalidFields();
		$this->assertEqual($invalidFields['other'], 'Please provide a description.');
	}	

	function endTest() {
		unset($this->SelfSignLog);
		ClassRegistry::flush();
	}

}
?>
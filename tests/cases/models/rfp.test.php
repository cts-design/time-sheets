<?php
/* Rfp Test cases generated on: 2011-02-28 17:47:47 : 1298915267*/
App::import('Model', 'Rfp');
App::import('Lib', 'AtlasTestCase');
class RfpTestCase extends AtlasTestCase {
	function startTest() {
		$this->Rfp =& ClassRegistry::init('Rfp');
	}

	function endTest() {
		unset($this->Rfp);
		ClassRegistry::flush();
	}
	
	function testValidation() {
		$this->Rfp->create();
		
		$invalidRecordNoName = array(
			'Rfp' => array(
				'title' => '',
				'byline' => 'my sweet byline',
				'description' => 'my sweet description',
				'deadline' => '0000-00-00 00:00:00',
				'expires' => '0000-00-00 00:00:00'
			)
		);
		
		$invalidRecordNoByline = array(
			'Rfp' => array(
				'title' => 'my sweet title',
				'byline' => '',
				'description' => 'my sweet description',
				'deadline' => '0000-00-00 00:00:00',
				'expires' => '0000-00-00 00:00:00'
			)		
		);
		
		$invalidRecordNoDescription = array(
			'Rfp' => array(
				'title' => 'my sweet title',
				'byline' => 'my sweet byline',
				'description' => '',
				'deadline' => '0000-00-00 00:00:00',
				'expires' => '0000-00-00 00:00:00'
			)			
		);
		
		$invalidRecordNoDeadline = array(
			'Rfp' => array(
				'title' => 'my sweet title',
				'byline' => 'my sweet byline',
				'description' => 'my sweet description',
				'deadline' => '',
				'expires' => '0000-00-00 00:00:00'
			)		
		);
		
		$invalidRecordNoExpiration = array(
			'Rfp' => array(
				'title' => 'my sweet title',
				'byline' => 'my sweet byline',
				'description' => 'my sweet description',
				'deadline' => '0000-00-00 00:00:00',
				'expires' => ''
			)		
		);
		
		$this->assertFalse($this->Rfp->save($invalidRecordNoName));
		$this->assertFalse($this->Rfp->save($invalidRecordNoByline));
		$this->assertFalse($this->Rfp->save($invalidRecordNoDescription));
		$this->assertFalse($this->Rfp->save($invalidRecordNoDeadline));
		$this->assertFalse($this->Rfp->save($invalidRecordNoExpiration));
	}

}
?>
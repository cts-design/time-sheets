<?php
/* DocumentQueueCategory Test cases generated on: 2010-11-05 19:11:42 : 1288984782*/
App::import('Model', 'DocumentQueueCategory');
App::import('Lib', 'AtlasTestCase');

class DocumentQueueCategoryTestCase extends CakeTestCase {


	function startTest() {
		$this->DocumentQueueCategory =& ClassRegistry::init('DocumentQueueCategory');
	}

	function endTest() {
		unset($this->DocumentQueueCategory);
		ClassRegistry::flush();
	}

	function testValidation() {
		$this->DocumentQueueCategory->create();
		
		$invalidRecordNoName = array(
			'DocumentQueueCategory' => array(
				'name' => '',
				'ftp_path' => '/etc/path/etc',
				'deleted' => 0
			)
		);
		
		$invalidRecordNoFtpPath = array(
			'DocumentQueueCategory' => array(
				'name' => 'New Name',
				'ftp_path' => '',
				'deleted' => 0
			)
		);
		
		$this->assertFalse($this->DocumentQueueCategory->save($invalidRecordNoName));
		$this->assertFalse($this->DocumentQueueCategory->save($invalidRecordNoFtpPath));
	}
	
	function testDelete() {
		$this->DocumentQueueCategory->delete(2);
		
		$result = $this->DocumentQueueCategory->read(null, 2);
		$this->assertTrue($result['DocumentQueueCategory']['deleted'], 1);
	}
}
?>
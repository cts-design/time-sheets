<?php
/* HotJob Test cases generated on: 2011-02-28 13:48:28 : 1298900908*/
App::import('Model', 'HotJob');
App::import('Lib', 'AtlasTestCase');
class HotJobTestCase extends AtlasTestCase {
	var $fixtures = array('app.hot_job');

	function startTest() {
		$this->HotJob =& ClassRegistry::init('HotJob');
	}

	function endTest() {
		unset($this->HotJob);
		ClassRegistry::flush();
	}

	function testValidation() {
		$this->HotJob->create();
		
		$invalidRecordNoEmployer = array(
			'HotJob' => array(
				'employer' => '',
				'title' => 'CNC Swiss Lathe 7 Axis Operator',
				'description' => 'Must have HS/GED w/5 yrs exp in CNC lathe machinery & familiar w/ISO 9001 requirements. Will set-up, program & operate 2 CNC Swiss CNC lathe 7 axis machines. Pay: $15-25/hr.',
				'location' => 'Pinellas County',
				'url' => 'http://cncists.com',
				'reference_number' => '9509835',
				'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
				'file' => ''
			)
		);
		
		$invalidRecordNoTitle = array(
			'HotJob' => array(
				'employer' => 'CNCists',
				'title' => '',
				'description' => 'Must have HS/GED w/5 yrs exp in CNC lathe machinery & familiar w/ISO 9001 requirements. Will set-up, program & operate 2 CNC Swiss CNC lathe 7 axis machines. Pay: $15-25/hr.',
				'location' => 'Pinellas County',
				'url' => 'http://cncists.com',
				'reference_number' => '9509835',
				'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
				'file' => ''
			)
		);
		
		$invalidRecordNoDescription = array(
			'HotJob' => array(
				'employer' => 'CNCists',
				'title' => 'CNC Swiss Lathe 7 Axis Operator',
				'description' => '',
				'location' => 'Pinellas County',
				'url' => 'http://cncists.com',
				'reference_number' => '9509835',
				'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
				'file' => ''
			)
		);
		
		$this->assertFalse($this->HotJob->save($invalidRecordNoEmployer));
		$this->assertFalse($this->HotJob->save($invalidRecordNoTitle));
		$this->assertFalse($this->HotJob->save($invalidRecordNoDescription));
	}
}
?>
<?php
/* Alert Test cases generated on: 2011-12-01 10:45:06 : 1322754306*/
App::import('Model', 'Alert');
App::import('Lib', 'AtlasTestCase');
class AlertTestCase extends AtlasTestCase {

	public function startTest() {
		$this->Alert =& ClassRegistry::init('Alert');
	}
	
	public function testGetSelfSignAlerts() {
		$result = $this->Alert->getSelfSignAlerts(
			array('level_1' => '10', 'user_id' => '9', 'location_id' => '1'), 'Test');
		$this->assertEqual($result[0]['username'], 'dnolan');
	}

	public function endTest() {
		unset($this->Alert);
		ClassRegistry::flush();
	}

}
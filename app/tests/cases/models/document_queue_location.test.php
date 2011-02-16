<?php
/* DocumentQueueLocation Test cases generated on: 2010-10-19 15:10:42 : 1287503862*/
App::import('Model', 'DocumentQueueLocation');

class DocumentQueueLocationTestCase extends CakeTestCase {
	var $fixtures = array('app.document_queue_location');

	function startTest() {
		$this->DocumentQueueLocation =& ClassRegistry::init('DocumentQueueLocation');
	}

	function endTest() {
		unset($this->DocumentQueueLocation);
		ClassRegistry::flush();
	}

}
?>
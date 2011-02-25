<?php
/* DocumentQueueCategory Test cases generated on: 2010-11-05 19:11:42 : 1288984782*/
App::import('Model', 'DocumentQueueCategory');

class DocumentQueueCategoryTestCase extends CakeTestCase {
	var $fixtures = array('app.document_queue_category');

	function startTest() {
		$this->DocumentQueueCategory =& ClassRegistry::init('DocumentQueueCategory');
	}

	function endTest() {
		unset($this->DocumentQueueCategory);
		ClassRegistry::flush();
	}

}
?>
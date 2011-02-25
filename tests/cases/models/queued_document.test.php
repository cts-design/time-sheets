<?php
/* QueuedDocument Test cases generated on: 2010-11-08 13:11:35 : 1289221955*/
App::import('Model', 'QueuedDocument');

class QueuedDocumentTestCase extends CakeTestCase {
	var $fixtures = array('app.queued_document');

	function startTest() {
		$this->QueuedDocument =& ClassRegistry::init('QueuedDocument');
	}

	function endTest() {
		unset($this->QueuedDocument);
		ClassRegistry::flush();
	}

}
?>
<?php
/* QueuedDocument Test cases generated on: 2010-11-08 13:11:35 : 1289221955*/
App::import('Model', 'QueuedDocument');
App::import('Lib', 'AtlasTestCase');
class QueuedDocumentTestCase extends AtlasTestCase {

	public function startTest() {
		$this->QueuedDocument =& ClassRegistry::init('QueuedDocument');
	}

	public function testCheckLocked() {
		$result = $this->QueuedDocument->checkLocked(2);
		$this->assertEqual($result, 49);
	}

	public function testLockDocument () {
		$result = $this->QueuedDocument->lockDocument(48, 2);
		$this->assertEqual($result['QueuedDocument']['id'], 48);
		$this->assertTrue($result['QueuedDocument']['locked_status']);
	}

	public function testUnlockDocument() {
		$result = $this->QueuedDocument->unlockDocument(49);
		$this->assertEqual($result, 49);
	}

	public function endTest() {
		unset($this->QueuedDocument);
		ClassRegistry::flush();
	}

}
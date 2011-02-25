<?php
/* Location Test cases generated on: 2010-11-05 19:11:13 : 1288983613*/
App::import('Model', 'Location');

class LocationTestCase extends CakeTestCase {
	var $fixtures = array('app.location', 'app.ftp_document_scanner');

	function startTest() {
		$this->Location =& ClassRegistry::init('Location');
	}

	function endTest() {
		unset($this->Location);
		ClassRegistry::flush();
	}

}
?>
<?php
/* DocumentFilingLocation Test cases generated on: 2010-10-19 15:10:41 : 1287503861*/
App::import('Model', 'DocumentFilingLocation');

class DocumentFilingLocationTestCase extends CakeTestCase {
	var $fixtures = array('app.document_filing_location');

	function startTest() {
		$this->DocumentFilingLocation =& ClassRegistry::init('DocumentFilingLocation');
	}

	function endTest() {
		unset($this->DocumentFilingLocation);
		ClassRegistry::flush();
	}

}
?>
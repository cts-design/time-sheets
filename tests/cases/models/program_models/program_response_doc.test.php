<?php
/* ProgramResponseDoc Test cases generated on: 2011-04-06 18:32:37 : 1302114757*/
App::import('Model', 'ProgramResponseDoc');

class ProgramResponseDocTestCase extends CakeTestCase {
	var $fixtures = array('app.program_response_doc', 'app.program_response', 'app.program', 'app.program_field', 'app.program_email', 'app.watched_filing_cat');

	function startTest() {
		$this->ProgramResponseDoc =& ClassRegistry::init('ProgramResponseDoc');
	}

	function endTest() {
		unset($this->ProgramResponseDoc);
		ClassRegistry::flush();
	}

}
?>
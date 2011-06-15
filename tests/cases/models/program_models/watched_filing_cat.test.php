<?php
/* WatchedFilingCat Test cases generated on: 2011-04-06 15:17:53 : 1302103073*/
App::import('Model', 'WatchedFilingCat');

class WatchedFilingCatTestCase extends CakeTestCase {
	var $fixtures = array('app.watched_filing_cat', 'app.program', 'app.program_field', 'app.program_response', 'app.program_email');

	function startTest() {
		$this->WatchedFilingCat =& ClassRegistry::init('WatchedFilingCat');
	}

	function endTest() {
		unset($this->WatchedFilingCat);
		ClassRegistry::flush();
	}

}
?>
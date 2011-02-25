<?php
/* SelfSignLogArchive Test cases generated on: 2010-10-28 18:10:43 : 1288290463*/
App::import('Model', 'SelfSignLogArchive');

class SelfSignLogArchiveTestCase extends CakeTestCase {
	var $fixtures = array('app.self_sign_log_archive');

	function startTest() {
		$this->SelfSignLogArchive =& ClassRegistry::init('SelfSignLogArchive');
	}

	function endTest() {
		unset($this->SelfSignLogArchive);
		ClassRegistry::flush();
	}

}
?>
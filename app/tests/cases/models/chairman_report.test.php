<?php
/* ChairmanReport Test cases generated on: 2011-02-09 18:13:19 : 1297275199*/
App::import('Model', 'ChairmanReport');

class ChairmanReportTestCase extends CakeTestCase {
	var $fixtures = array('app.chairman_report');

	function startTest() {
		$this->ChairmanReport =& ClassRegistry::init('ChairmanReport');
	}

	function endTest() {
		unset($this->ChairmanReport);
		ClassRegistry::flush();
	}

}
?>
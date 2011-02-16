<?php
/* Test Test cases generated on: 2010-11-10 19:11:36 : 1289417796*/
App::import('Model', 'Test');

class TestTestCase extends CakeTestCase {
	var $fixtures = array('app.test');

	function startTest() {
		$this->Test =& ClassRegistry::init('Test');
	}

	function endTest() {
		unset($this->Test);
		ClassRegistry::flush();
	}

}
?>
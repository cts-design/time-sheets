<?php
/* Admin Test cases generated on: 2010-09-24 12:09:21 : 1285331661*/
App::import('Model', 'Admin');

class AdminTestCase extends CakeTestCase {
	var $fixtures = array('app.admin');

	function startTest() {
		$this->Admin =& ClassRegistry::init('Admin');
	}

	function endTest() {
		unset($this->Admin);
		ClassRegistry::flush();
	}

}
?>
<?php
/* Navigation Test cases generated on: 2011-02-04 19:51:40 : 1296849100*/
App::import('Model', 'Navigation');

class NavigationTestCase extends CakeTestCase {
	var $fixtures = array('app.navigation');

	function startTest() {
		$this->Navigation =& ClassRegistry::init('Navigation');
	}

	function endTest() {
		unset($this->Navigation);
		ClassRegistry::flush();
	}

}
?>
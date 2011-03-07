<?php
/* InTheNews Test cases generated on: 2011-03-07 19:52:03 : 1299527523*/
App::import('Model', 'InTheNews');
App::import('Lib', 'AtlasTestCase');
class InTheNewsTestCase extends AtlasTestCase {
	var $fixtures = array('app.in_the_news');

	function startTest() {
		$this->InTheNews =& ClassRegistry::init('InTheNews');
	}

	function endTest() {
		unset($this->InTheNews);
		ClassRegistry::flush();
	}

}
?>
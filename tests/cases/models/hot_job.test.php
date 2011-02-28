<?php
/* HotJob Test cases generated on: 2011-02-28 13:48:28 : 1298900908*/
App::import('Model', 'HotJob');
App::import('Lib', 'AtlasTestCase');
class HotJobTestCase extends AtlasTestCase {
	var $fixtures = array('app.hot_job');

	function startTest() {
		$this->HotJob =& ClassRegistry::init('HotJob');
	}

	function endTest() {
		unset($this->HotJob);
		ClassRegistry::flush();
	}

}
?>
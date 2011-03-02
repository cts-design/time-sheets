<?php
/* Rfp Test cases generated on: 2011-02-28 17:47:47 : 1298915267*/
App::import('Model', 'Rfp');
App::import('Lib', 'AtlasTestCase');
class RfpTestCase extends AtlasTestCase {
	var $fixtures = array('app.rfp');

	function startTest() {
		$this->Rfp =& ClassRegistry::init('Rfp');
	}

	function endTest() {
		unset($this->Rfp);
		ClassRegistry::flush();
	}

}
?>
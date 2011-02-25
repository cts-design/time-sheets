<?php
/* SelfScanCategory Test cases generated on: 2010-12-15 21:12:28 : 1292447908*/
App::import('Model', 'SelfScanCategory');

class SelfScanCategoryTestCase extends CakeTestCase {
	var $fixtures = array('app.self_scan_category');

	function startTest() {
		$this->SelfScanCategory =& ClassRegistry::init('SelfScanCategory');
	}

	function endTest() {
		unset($this->SelfScanCategory);
		ClassRegistry::flush();
	}

}
?>
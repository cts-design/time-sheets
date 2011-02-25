<?php
/* Kiosk Test cases generated on: 2010-09-27 15:09:40 : 1285601080*/
App::import('Model', 'Kiosk');

class KioskTestCase extends CakeTestCase {
	var $fixtures = array('app.kiosk');

	function startTest() {
		$this->Kiosk =& ClassRegistry::init('Kiosk');
	}

	function endTest() {
		unset($this->Kiosk);
		ClassRegistry::flush();
	}

}
?>
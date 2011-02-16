<?php
/* KioskButton Test cases generated on: 2010-09-27 19:09:29 : 1285615769*/
App::import('Model', 'KioskButton');

class KioskButtonTestCase extends CakeTestCase {
	var $fixtures = array('app.kiosk_button', 'app.kiosk');

	function startTest() {
		$this->KioskButton =& ClassRegistry::init('KioskButton');
	}

	function endTest() {
		unset($this->KioskButton);
		ClassRegistry::flush();
	}

}
?>
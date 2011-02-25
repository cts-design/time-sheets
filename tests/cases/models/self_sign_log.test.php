<?php
/* SelfSignLog Test cases generated on: 2010-09-30 15:09:08 : 1285861928*/
App::import('Model', 'SelfSignLog');

class SelfSignLogTestCase extends CakeTestCase {
	var $fixtures = array('app.self_sign_log', 'app.user');

	function startTest() {
		$this->SelfSignLog =& ClassRegistry::init('SelfSignLog');
	}

	function endTest() {
		unset($this->SelfSignLog);
		ClassRegistry::flush();
	}

}
?>
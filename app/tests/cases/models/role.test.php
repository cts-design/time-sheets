<?php
/* Role Test cases generated on: 2010-11-10 16:11:26 : 1289404826*/
App::import('Model', 'Role');

class RoleTestCase extends CakeTestCase {
	var $fixtures = array('app.role', 'app.user', 'app.self_sign_log', 'app.kiosk', 'app.kiosk_button', 'app.master_kiosk_button', 'app.self_sign_log_archive', 'app.user_transaction');

	function startTest() {
		$this->Role =& ClassRegistry::init('Role');
	}

	function endTest() {
		unset($this->Role);
		ClassRegistry::flush();
	}

}
?>
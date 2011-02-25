<?php
/* UserTransaction Test cases generated on: 2010-10-19 16:10:28 : 1287504148*/
App::import('Model', 'UserTransaction');

class UserTransactionTestCase extends CakeTestCase {
	var $fixtures = array('app.user_transaction');

	function startTest() {
		$this->UserTransaction =& ClassRegistry::init('UserTransaction');
	}

	function endTest() {
		unset($this->UserTransaction);
		ClassRegistry::flush();
	}

}
?>
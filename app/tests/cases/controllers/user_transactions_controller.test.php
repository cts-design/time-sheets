<?php
/* UserTransactions Test cases generated on: 2010-10-28 12:10:07 : 1288269787*/
App::import('Controller', 'UserTransactions');

class TestUserTransactionsController extends UserTransactionsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class UserTransactionsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.user_transaction');

	function startTest() {
		$this->UserTransactions =& new TestUserTransactionsController();
		$this->UserTransactions->constructClasses();
	}

	function endTest() {
		unset($this->UserTransactions);
		ClassRegistry::flush();
	}

	function testAdminIndex() {

	}

	function testAdminView() {

	}

	function testAdminAdd() {

	}

	function testAdminEdit() {

	}

	function testAdminDelete() {

	}

}
?>
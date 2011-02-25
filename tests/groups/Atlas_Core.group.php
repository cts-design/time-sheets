<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class AtlasCoreGroupTest extends TestSuite {

/**
 * label property
 *
 * @var string 'All cake/libs/controller/* (Not yet implemented)'
 * @access public
 */
	var $label = 'Atlas Core Test Cases';
/**
 * LibControllerGroupTest method
 *
 * @access public
 * @return void
 */
	function AtlasCoreGroupTest() {
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'deleted_documents_controller');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'document_filing_categories_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'document_filing_category');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'document_queue_categories_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'document_queue_category');

            //TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'filed_documents_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'filed_document');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'ftp_document_scanners_controller');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'kiosk_buttons_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'kiosk_button');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'kiosks_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'kiosk');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'locations_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'location');
			
			TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'module_access_control');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'queued_documents_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'queued_document');

            //TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'roles_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'role');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'self_scan_categories_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'self_scan_category');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'self_sign_log_archives_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'self_sign_log_archive');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'self_sign_logs_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'self_sign_log');

            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'user_transactions_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'user_transaction');

            //TestManager::addTestFile($this, TESTS . 'cases' . DS . 'controllers' . DS . 'users_controller');
            TestManager::addTestFile($this, TESTS . 'cases' . DS . 'models' . DS . 'user');
	}
}

<?php
/* DocumentFilingCategories Test cases generated on: 2010-10-19 17:10:54 : 1287509754*/
App::import('Controller', 'DocumentFilingCategories');
App::import('Lib', 'AtlasTestCase');
class TestDocumentFilingCategoriesController extends DocumentFilingCategoriesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class DocumentFilingCategoriesControllerTestCase extends AtlasTestCase {
	var $fixtures = array(
            'app.aco',
            'app.aro',
            'app.aro_aco',
            'chairman_report',
            'deleted_document',
            'document_filing_category',
            'document_queue_category',
            'document_transaction',
            'filed_document',
            'ftp_document_scanner',
            'kiosk',
            'kiosk_button',
            'location',
            'master_kiosk_button',
            'navigation',
            'page',
            'press_release',
            'queued_document',
            'role',
            'self_scan_category',
            'self_sign_log',
            'self_sign_log_archive',
            'user',
            'user_transaction'
        );

	function startTest() {
		$this->DocumentFilingCategories =& new TestDocumentFilingCategoriesController();
		$this->DocumentFilingCategories->constructClasses();
	}

	function endTest() {
		unset($this->DocumentFilingCategories);
		ClassRegistry::flush();
	}

	function testAdminIndex() {
            $result = $this->testAction('/admin/document_filing_categories', array('return' => 'vars'));
            FireCake::log($result['data']);
            $expected = array(
                '0' => array(
                    'DocumentFilingCategory' => array(
                        'id' => 1,
                        'parent_id' => NULL,
                        'name' => 'Valid Category',
                        'order' => 9999,
                        'deleted' => 0,
                        'created' => '2010-10-19 15:57:41',
                        'modified' => '2010-10-19 15:57:41'
                    ),
                    'children' => array(
                        '0' => array(
                            'DocumentFilingCategory' => array(
                                'id' => 3,
                                'parent_id' => 1,
                                'name' => 'A Nested Valid Category',
                                'order' => 9999,
                                'deleted' => 0,
                                'created' => '2010-10-19 15:57:41',
                                'modified' => '2010-10-19 15:57:41'
                            ),
                            'children' => array(
                                '0' => array(
                                    'DocumentFilingCategory' => array(
                                        'id' => 4,
                                        'parent_id' => 3,
                                        'name' => 'A Second Level Nested Valid Category',
                                        'order' => 9999,
                                        'deleted' => 0,
                                        'created' => '2010-10-19 15:57:41',
                                        'modified' => '2010-10-19 15:57:41'
                                    ),
                                    'children' => array()
                                )
                            )
                        )
                    )
                ),
                '1' => array(
                    'DocumentFilingCategory' => array(
                        'id' => 2,
                        'parent_id' => NULL,
                        'name' => 'Another Valid Category',
                        'order' => 9999,
                        'deleted' => 0,
                        'created' => '2010-10-19 15:57:41',
                        'modified' => '2010-10-19 15:57:41'
                    ),
                    'children' => array()
                )
            );

            $this->assertEqual($result['data'], $expected);
	}

        // should fail if you try to nest more than 3 levels
        function testAdminAddNestedTooFar() {
            $this->DocumentFilingCategories->data = array(
                'DocumentFilingCategory' => array(
                    'name' => 'A Nested Category Too Deep',
                    'parent_id' => 4,
                    'order' => 1000,
                    'created' => '0000-00-00 00:00:00',
                    'modified' => '0000-00-00 00:00:00'
                )
            );
            $this->DocumentFilingCategories->admin_add();

            $flashMessage = $this->DocumentFilingCategories->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Categories cannot be more than three levels deep',
                'element' => 'flash_failure',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

	function testAdminAddRootCategory() {
            $this->DocumentFilingCategories->data = array(
                'DocumentFilingCategory' => array(
                    'name' => 'A Root Category',
                    'parent_id' => NULL,
                    'created' => '0000-00-00 00:00:00',
                    'modified' => '0000-00-00 00:00:00'
                )
            );
            $this->DocumentFilingCategories->admin_add();

            $flashMessage = $this->DocumentFilingCategories->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The category has been saved',
                'element' => 'flash_success',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
	}
        
        function testAdminAddNestedOneLevels() {
            $this->DocumentFilingCategories->data = array(
                'DocumentFilingCategory' => array(
                    'name' => 'A Nested Category',
                    'parent_id' => 1,
                    'created' => '0000-00-00 00:00:00',
                    'modified' => '0000-00-00 00:00:00'
                )
            );
            $this->DocumentFilingCategories->admin_add();

            $flashMessage = $this->DocumentFilingCategories->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The category has been saved',
                'element' => 'flash_success',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminAddNestedTwoLevels() {
            $this->DocumentFilingCategories->data = array(
                'DocumentFilingCategory' => array(
                    'name' => 'A Nested Category Two Deep',
                    'parent_id' => 3,
                    'created' => '0000-00-00 00:00:00',
                    'modified' => '0000-00-00 00:00:00'
                )
            );
            $this->DocumentFilingCategories->admin_add();

            $flashMessage = $this->DocumentFilingCategories->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The category has been saved',
                'element' => 'flash_success',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }
       
	function testAdminEditWithoutPassingId() {
            $this->DocumentFilingCategories->admin_edit();
            
            $flashMessage = $this->DocumentFilingCategories->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Invalid category',
                'element' => 'flash_failure',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
	}

        function testAdminEditWithValidData() {
            $this->DocumentFilingCategories->data = array(
                'DocumentFilingCategory' => array(
                    'name' => 'An Edited Name'
                )
            );

            $this->DocumentFilingCategories->admin_edit(1);

            $flashMessage = $this->DocumentFilingCategories->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The category has been saved',
                'element' => 'flash_success',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminEditWithInvalidData() {
            $this->DocumentFilingCategories->data = array(
                'DocumentFilingCategory' => array(
                    'name' => ''
                )
            );

            $this->DocumentFilingCategories->admin_edit(1);

            $flashMessage = $this->DocumentFilingCategories->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'The category could not be saved. Please, try again.',
                'element' => 'flash_failure',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

        function testAdminDeleteWithoutPassingId() {
            $this->DocumentFilingCategories->admin_delete();

            $flashMessage = $this->DocumentFilingCategories->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Invalid id for category',
                'element' => 'flash_failure',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
	}

        function testAdminDeleteCategoryWithChildren() {
            $this->DocumentFilingCategories->admin_delete(1);

            $flashMessage = $this->DocumentFilingCategories->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Cannot delete category that has children',
                'element' => 'flash_failure',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
        }

	function testAdminDelete() {
            $this->DocumentFilingCategories->admin_delete(2);

            $flashMessage = $this->DocumentFilingCategories->Session->read('Message.flash');
            $expectedFlashMessage = array(
                'message' => 'Category deleted',
                'element' => 'flash_success',
                'params' => array()
            );
            $this->assertEqual($flashMessage, $expectedFlashMessage);
	}

}
?>
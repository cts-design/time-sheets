<?php
/* FiledDocuments Test cases generated on: 2010-11-24 15:11:59 : 1290612419*/
App::import('Controller', 'FiledDocuments');
class TestFiledDocumentsController extends FiledDocumentsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
		return $this->redirectUrl;
	}
}

class FiledDocumentsControllerTestCase extends CakeTestCase {
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
            'module_access_control',
            'navigation',
            'page',
            'press_release',
            'program',
            'program_email',
            'program_field',
            'program_instruction',
            'program_paper_form',
            'program_response',
            'program_response_doc',
            'queued_document',
            'role',
            'self_scan_category',
            'self_sign_log',
            'self_sign_log_archive',
            'user',
            'user_transaction',
            'watched_filing_cat'
        );

	function startTest() {
		$this->FiledDocuments =& new TestFiledDocumentsController();
		$this->FiledDocuments->constructClasses();
		$this->FiledDocuments->Component->initialize($this->FiledDocuments);	
	}

	function endTest() {
		$this->FiledDocuments->Session->destroy();
		unset($this->FiledDocuments);
		ClassRegistry::flush();
	}
	
    function testAdminIndexNoId() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'username' => 'dnolan',
            'role_id' => 2,
            'location_id' => 1
        ));
        $result = $this->testAction('/admin/filed_documents/index', array('return' => 'vars'));
        $result = Set::extract('/filedDocuments/.[1]', $result);		
        $this->assertEqual($result[0]['FiledDocument']['id'], 112);
    }
    
    function testAdminIndexWithId() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $result = $this->testAction('/admin/filed_documents/index/10', array('return' => 'vars'));
        $result = Set::extract('/filedDocuments/.[1]', $result);		
        $this->assertEqual($result[0]['FiledDocument']['id'], 111);
    }
    
    function testAdminIndexWithBadId() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $result = $this->testAction('/admin/filed_documents/index/30', array('return' => 'vars'));	
        $this->assertEqual($result['filedDocuments'], array());
    }
    
    function testAdminView() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $result = $this->testAction('/admin/filed_documents/view/111', array('return' => 'result'));
        $this->assertEqual($result['id'], 'Lorem ipsum dolor sit amet');		
    }
    
    function testAdminViewNoId() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $this->FiledDocuments->params = Router::parse('/admin/filed_documents/view');
        $this->FiledDocuments->beforeFilter();
        $this->FiledDocuments->Component->startup($this->FiledDocuments);
        $this->FiledDocuments->admin_view();	
        $this->assertEqual($this->FiledDocuments->Session->read('Message.flash.element'), 'flash_failure');		
    }	
        
    function testAdminEditWithValidData() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));	
        $this->FiledDocuments->data = array(
            'FiledDocument' => array(
                'title' => 'Valid Title',
                'edit_type' => 'user'
            ),
            'User' => array(
            'firstname' => 'Daniel',
            'lastname' => 'Test',
            'ssn' => '123441234')
        );
        $this->FiledDocuments->params = Router::parse('/admin/filed_documents/edit/1');
        $this->FiledDocuments->beforeFilter();
        $this->FiledDocuments->Component->startup($this->FiledDocuments);
        $this->FiledDocuments->admin_edit(1);	
        $this->assertEqual($this->FiledDocuments->Session->read('Message.flash.element'), 'flash_success');
    }
    
    
    function testAdminEditWithInvalidId() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));	
        $this->FiledDocuments->params = Router::parse('/admin/filed_documents/edit');
           $this->FiledDocuments->beforeFilter();
        $this->FiledDocuments->Component->startup($this->FiledDocuments);
        $this->FiledDocuments->admin_edit();	
        $this->assertEqual($this->FiledDocuments->Session->read('Message.flash.element'), 'flash_failure');
    }
    
    function testAdminEditWithInvalidData() {
        $this->FiledDocuments->data = array(
            'FiledDocument' => array(
                'title' => 'Valid Title',
                'edit_type' => 'user'
            ),
            'User' => array(
            'firstname' => 'Daniel',
            'lastname' => 'Test',
            'ssn' => '123441')
        );		
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan'
        ));	
        $this->FiledDocuments->params = Router::parse('/admin/filed_documents/edit/11');
           $this->FiledDocuments->beforeFilter();
        $this->FiledDocuments->Component->startup($this->FiledDocuments);		
        $this->FiledDocuments->admin_edit(11);	
        $this->assertEqual($this->FiledDocuments->Session->read('Message.flash.element'), 'flash_failure');
    }	
        
    function testAdminDeleteValidRecord() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'username' => 'dnolan',
            'role_id' => 2,
            'location_id' => 1
        ));			
        $this->FiledDocuments->data = array(
            'FiledDocument' => array(
                'id' => 111,
                'reason' => 'Duplicate Scan'
            )
        );
        $this->FiledDocuments->params = Router::parse('/admin/filed_documents/delete/111');
           $this->FiledDocuments->beforeFilter();
        $this->FiledDocuments->Component->startup($this->FiledDocuments);		
        $this->FiledDocuments->admin_delete();
        $this->assertEqual($this->FiledDocuments->Session->read('Message.flash.element'), 'flash_success');
    }

    function testAdminDeleteInvalidRecord() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));	    	
        $this->FiledDocuments->data = array(
            'FiledDocument' => array(
                'id' => 33,
                'reason' => 'Duplicate Scan'
            )
        );
        $this->FiledDocuments->params = Router::parse('/admin/filed_documents/delete/33');
           $this->FiledDocuments->beforeFilter();
        $this->FiledDocuments->Component->startup($this->FiledDocuments);			
        $this->FiledDocuments->admin_delete();
        $this->assertEqual($this->FiledDocuments->redirectUrl, $this->FiledDocuments->referer());
        $this->assertEqual($this->FiledDocuments->Session->read('Message.flash.element'), 'flash_failure');
    }

    function testAdminDeleteWithNoSpecifiedRecord() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $this->FiledDocuments->params = Router::parse('/admin/filed_documents/delete');
           $this->FiledDocuments->beforeFilter();
        $this->FiledDocuments->Component->startup($this->FiledDocuments);			
        $this->FiledDocuments->admin_delete();			    	
        $this->assertEqual($this->FiledDocuments->Session->read('Message.flash.element'), 'flash_failure');
        $this->assertEqual($this->FiledDocuments->redirectUrl, $this->FiledDocuments->referer());
   }
    
    function testAdminViewAllDocsNoAjax(){
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $result = $this->testAction('/admin/filed_documents/view_all_docs', array('return' => 'vars'));	
        $this->assertEqual($result['title_for_layout'], 'Filed Document Archive');    			
    } 		
    
    function testAdminViewAllDocsAjax(){
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);			
        $this->assertEqual($result['docs'][0]['id'], 1);
        $this->assertEqual($result['totalCount'], 3);		    			
    }
    
    function testAdminViewAllDocsWithFilters(){
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $this->__savedGetData['filters'] = 	
            '{"fromDate":"","toDate":"","searchType1":"","cusSearch1":"","cat_1":"2","cat_2":"","admin_id":"","filed_location_id":""}';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        unset($this->__savedGetData);	
        $this->assertEqual($result['docs'][0]['id'], 111);
        $this->assertEqual($result['totalCount'], 2);		    			
    }

	function testAdminViewAllDocsWithCustomerSearchFirstSet(){
	    $this->FiledDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));

        // test firstname
		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType1":"firstname","cusScope1":"containing","cusSearch1":"dan","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType1":"firstname","cusScope1":"matching exactly","cusSearch1":"Daniel","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

        // test lastname
		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType1":"lastname","cusScope1":"containing","cusSearch1":"te","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType1":"lastname","cusScope1":"matching exactly","cusSearch1":"Test","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

        // test ssn
		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType1":"last4","cusScope1":"containing","cusSearch1":"1234","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType1":"last4","cusScope1":"matching exactly","cusSearch1":"1234","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		unset($this->__savedGetData);	

        $this->assertEqual($result['docs'][0]['id'], 1);
        $this->assertEqual($result['totalCount'], 2);

		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType1":"fullssn","cusScope1":"containing","cusSearch1":"1244","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType1":"fullssn","cusScope1":"matching exactly","cusSearch1":"123441244","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		unset($this->__savedGetData);	

        $this->assertEqual($result['docs'][0]['id'], 112);
        $this->assertEqual($result['totalCount'], 1);
	}

	function testAdminViewAllDocsWithCustomerSearchSecondSet(){
	    $this->FiledDocuments->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));

        // test firstname
		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType2":"firstname","cusScope2":"containing","cusSearch2":"dan","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType2":"firstname","cusScope2":"matching exactly","cusSearch2":"Daniel","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

        // test lastname
		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType2":"lastname","cusScope2":"containing","cusSearch2":"te","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType2":"lastname","cusScope2":"matching exactly","cusSearch2":"Test","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

        // test ssn
		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType2":"last4","cusScope2":"containing","cusSearch2":"1234","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType2":"last4","cusScope2":"matching exactly","cusSearch2":"1234","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		unset($this->__savedGetData);	
        $this->assertEqual($result['docs'][0]['id'], 1);
        $this->assertEqual($result['totalCount'], 2);

		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType2":"fullssn","cusScope2":"containing","cusSearch2":"1244","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';

		unset($this->__savedGetData);	
		$this->assertEqual($result['docs'][0]['id'], 1);
		$this->assertEqual($result['totalCount'], 2);

		$this->__savedGetData['filters'] =	
            '{"fromDate":"","toDate":"","searchType2":"fullssn","cusScope2":"matching exactly","cusSearch2":"123441244","cat_1":"","cat_2":"","admin_id":"","filed_location_id":""}';
		$_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
		$result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
		unset($this->__savedGetData);	

        $this->assertEqual($result['docs'][0]['id'], 112);
        $this->assertEqual($result['totalCount'], 1);
	}



    function testAdminViewAllDocsSorting(){
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $this->__savedGetData['sort'] = 'id';
        $this->__savedGetData['direction'] = 'DESC';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        unset($this->__savedGetData);
        $this->assertEqual($result['docs'][0]['id'], 112);
        $this->assertEqual($result['totalCount'], 3);		    			
    }
    
    function testAdminViewAllDocsNoResults(){
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $this->__savedGetData['filters'] = 	
            '{"fromDate":"03/14/2011","toDate":"03/14/2011","searchType":"","cusSearch":"","cat_1":"2","cat_2":"","cat_3":"","admin_id":"","filed_location_id":""}';
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $result = json_decode($this->testAction('/admin/filed_documents/view_all_docs'), true);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        unset($this->__savedGetData);
        $this->assertEqual($result['docs'], array());	    			
    }

    function testAdminReport() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $result = $this->testAction('/admin/filed_documents/report');
        unset($this->__savedGetData);
        Configure::write('debug', 2);
        $this->assertEqual($result['data'][0]['id'], 1);			
    }
     
    function testAdminReportWithFilters() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $this->__savedGetData['filters'] = 	
            '{"fromDate":"","toDate":"","searchType":"","cusSearch":"","cat_1":"2","cat_2":"","admin_id":"","filed_location_id":""}';
        $result = $this->testAction('/admin/filed_documents/report');
        unset($this->__savedGetData);
        Configure::write('debug', 2);
        $this->assertEqual($result['data'][0]['id'], 111);			
    }
    
    function testAdminReportNoResults() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $this->FiledDocuments->params = Router::parse('/admin/filed_documents/report');
        $this->FiledDocuments->params['url']['filters'] = 	
            '{"fromDate":"3/14/2011","toDate":"3/14/2011","searchType":"","cusSearch":"","cat_1":"2","cat_2":"6","admin_id":"","filed_location_id":""}';
        $this->FiledDocuments->params['url']['url'] = '/admin/filed_documents/report';
           $this->FiledDocuments->beforeFilter();
        $this->FiledDocuments->Component->startup($this->FiledDocuments);			
        $this->FiledDocuments->admin_report();	
        $this->assertEqual($this->FiledDocuments->Session->read('Message.flash.element'), 'flash_failure');			
    }	

    function testAdminGetAllAdmins() {
        $this->FiledDocuments->Session->write('Auth.User', array(
            'id' => 2,
            'role_id' => 2,
            'username' => 'dnolan',
            'location_id' => 1
        ));
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
        $result = json_decode($this->testAction('/admin/filed_documents/get_all_admins'), true);
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $expected = array(
            'admins' => array(
                array(
                    'id' => 20,
                    'name' => 'Admin, Sally'
                ),
                array(
                    'id' => 21,
                    'name' => 'Admin, Another'
                )
            ),
            'success' => true
        );
        $this->assertEqual($result, $expected);
    }

}
?>

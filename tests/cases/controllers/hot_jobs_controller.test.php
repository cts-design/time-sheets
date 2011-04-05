<?php
/* HotJobs Test cases generated on: 2011-02-28 13:49:09 : 1298900949*/
App::import('Controller', 'HotJobs');
App::import('Lib', 'AtlasTestCase');
class TestHotJobsController extends HotJobsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class HotJobsControllerTestCase extends AtlasTestCase {
	function startTest() {
		$this->HotJobs =& new TestHotJobsController();
		$this->HotJobs->constructClasses();
		$this->HotJobs->Component->initialize($this->HotJobs);
		
		// set up the session
		$this->HotJobs->Session->write('Auth.User', array(
			'id' => 1,
			'username' => 'bcordell',
			'location_id' => 1
		));
	}

	function endTest() {
		$this->HotJobs->Session->destroy();
		unset($this->HotJobs);
		ClassRegistry::flush();
	}

	function testIndex() {
		$return = $this->testAction('/hot_jobs', array('return' => 'vars'));
		$expected = array(
			'hotJobs' => array(
				array(
					'HotJob' => array(
						'id' => 1,
						'employer' => 'CNCists',
						'title' => 'CNC Swiss Lathe 7 Axis Operator',
						'description' => 'Must have HS/GED w/5 yrs exp in CNC lathe machinery & familiar w/ISO 9001 requirements. Will set-up, program & operate 2 CNC Swiss CNC lathe 7 axis machines. Pay: $15-25/hr.',
						'location' => 'Pinellas County',
						'url' => 'http://cncists.com',
						'reference_number' => '9509835',
						'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
						'file' => ''
					)
				),
				array(
					'HotJob' => array(
						'id' => 2,
						'employer' => 'Test Pests',
						'title' => 'Pest Control Tech/Sales',
						'description' => 'No exp necessary, willing to train. Must be min 18 yrs old, have a valid driver’s license w/clean driving record, must pass a drug test & be physically fit w/ability to crawl under houses & in attics. Background checks will be perform. Unemployment compensation recipients encouraged to apply. Pay: $10/hr plus comm.',
						'location' => 'Pinellas County',
						'url' => 'http://cncists.com',
						'reference_number' => '9544375',
						'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
						'file' => ''
					)
				),
			)
		);
		
		$this->assertEqual($return, $expected);
	}

	function testAdminIndex() {
		$return = $this->testAction('/admin/hot_jobs', array('return' => 'vars'));
		debug($return);
		$expected = array(
			'hotJobs' => array(
				array(
					'HotJob' => array(
						'id' => 1,
						'employer' => 'CNCists',
						'title' => 'CNC Swiss Lathe 7 Axis Operator',
						'description' => 'Must have HS/GED w/5 yrs exp in CNC lathe machinery & familiar w/ISO 9001 requirements. Will set-up, program & operate 2 CNC Swiss CNC lathe 7 axis machines. Pay: $15-25/hr.',
						'location' => 'Pinellas County',
						'url' => 'http://cncists.com',
						'reference_number' => '9509835',
						'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
						'file' => ''
					)
				),
				array(
					'HotJob' => array(
						'id' => 2,
						'employer' => 'Test Pests',
						'title' => 'Pest Control Tech/Sales',
						'description' => 'No exp necessary, willing to train. Must be min 18 yrs old, have a valid driver’s license w/clean driving record, must pass a drug test & be physically fit w/ability to crawl under houses & in attics. Background checks will be perform. Unemployment compensation recipients encouraged to apply. Pay: $10/hr plus comm.',
						'location' => 'Pinellas County',
						'url' => 'http://cncists.com',
						'reference_number' => '9544375',
						'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
						'file' => ''
					)
				),
			)
		);
		
		$this->assertEqual($return, $expected);
	}

	function testAdminAddWithValidRecord() {
		$this->HotJobs->data = array(
			'HotJob' => array(
				'employer' => 'New Employer',
				'title' => 'Wicked cool job',
				'description' => 'Must be cool dude, can\'t be a goof off',
				'location' => 'Pinellas County',
				'url' => 'http://new-employer.com',
				'reference_number' => '1234567',
				'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
				'file' => array(
					'error' => 4
				)
			)
		);
		
		$this->HotJobs->params = Router::parse('/admin/hot_jobs/add');
		$this->HotJobs->beforeFilter();
		$this->HotJobs->Component->startup($this->HotJobs);
		$this->HotJobs->admin_add();
		
		$this->assertFlashMessage($this->HotJobs, 'The hot job has been saved', 'flash_success');
	}
	
	function testAdminAddWithInvalidRecord() {
		$this->HotJobs->data = array(
			'HotJob' => array(
				'employer' => '',
				'title' => 'Wicked cool job',
				'description' => 'Must be cool dude, can\'t be a goof off',
				'location' => 'Pinellas County',
				'url' => 'http://new-employer.com',
				'reference_number' => '1234567',
				'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
				'file' => array(
					'error' => 4
				)				
			)
		);
		
		$this->HotJobs->params = Router::parse('/admin/hot_jobs/add');
		$this->HotJobs->beforeFilter();
		$this->HotJobs->Component->startup($this->HotJobs);
		$this->HotJobs->admin_add();
		
		$this->assertFlashMessage($this->HotJobs, 'The hot job could not be saved. Please, try again.', 'flash_failure');
	}

	function testAdminEditWithNoId() {
		$this->HotJobs->params = Router::parse('/admin/hot_jobs/edit/');
		$this->HotJobs->beforeFilter();
		$this->HotJobs->Component->startup($this->HotJobs);
		$this->HotJobs->admin_edit();
		
		$this->assertFlashMessage($this->HotJobs, 'Invalid hot job', 'flash_failure');		
	}

	function testAdminEdit() {
		$this->HotJobs->data = array(
			'HotJob' => array(
				'id' => 2,
				'employer' => 'Test Pests Changed Name',
				'title' => 'Pest Control Tech/Sales',
				'description' => 'No exp necessary, willing to train. Must be min 18 yrs old, have a valid driver’s license w/clean driving record, must pass a drug test & be physically fit w/ability to crawl under houses & in attics. Background checks will be perform. Unemployment compensation recipients encouraged to apply. Pay: $10/hr plus comm.',
				'location' => 'Pinellas County',
				'url' => 'http://cncists.com',
				'reference_number' => '9544375',
				'contact' => 'Email resume in Word wit Ref # to hloeun@worknetpinellas.org',
				'file' => array(
					'error' => 4
				)			
			)
		);
		
		$this->HotJobs->params = Router::parse('/admin/hot_jobs/edit/2');
		$this->HotJobs->beforeFilter();
		$this->HotJobs->Component->startup($this->HotJobs);
		$this->HotJobs->admin_edit();
		
		// was the record changed?
		$result = $this->HotJobs->HotJob->read(null, 2);
		$this->assertEqual($result['HotJob']['employer'], 'Test Pests Changed Name');
		$this->assertFlashMessage($this->HotJobs, 'The hot job has been saved', 'flash_success');
	}

	function testAdminDelete() {
		$this->HotJobs->params = Router::parse('/admin/hot_jobs/delete/2');
		$this->HotJobs->beforeFilter();
		$this->HotJobs->Component->startup($this->HotJobs);
		$this->HotJobs->admin_delete(2);
		
		// was the record changed?
		$this->assertFalse($this->HotJobs->HotJob->read(null, 2));	
	}
	
//	function testAdminDeleteWithNoId() {
//		$this->HotJobs->params = Router::parse('/admin/hot_jobs/delete');
//		$this->HotJobs->beforeFilter();
//		$this->HotJobs->Component->startup($this->HotJobs);
//		$this->HotJobs->admin_delete();

//		$this->assertEqual($this->HotJobs->Session->read('Message.flash.element'), 'flash_failure');
//	}

}
?>
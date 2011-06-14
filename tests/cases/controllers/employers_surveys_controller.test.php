<?php
/* EmployersSurveys Test cases generated on: 2011-03-23 15:46:23 : 1300909583*/
App::import('Controller', 'EmployersSurveys');
App::import('Lib', 'AtlasTestCase');
class TestEmployersSurveysController extends EmployersSurveysController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class EmployersSurveysControllerTestCase extends AtlasTestCase {

	var $fixtures = array(
		'app.aco',
	    'app.aro',
	    'app.aros_aco',
	    'career_seekers_survey',
	    'chairman_report',
	    'deleted_document',
	    'document_filing_category',
	    'document_queue_category',
	    'document_transaction',
	    'employers_survey',
	    'featured_employer',
	    'filed_document',
	    'ftp_document_scanner',
	    'helpful_article',
	    'hot_job',
	    'kiosk',
	    'kiosk_button',
	    'in_the_news',
	    'location',
	    'master_kiosk_button',
	    'module_access_control',
	    'navigation',
	    'page',
	    'press_release',
	    'queued_document',
	    'rfp',
	    'role',
	    'self_scan_category',
	    'self_sign_log',
	    'self_sign_log_archive',
	    'survey',
	    'survey_question',
	    'user',
	    'user_transaction');


	function startTest() {
		$this->EmployersSurveys =& new TestEmployersSurveysController();
		$this->EmployersSurveys->constructClasses();
	}

    function endTest() {
    		$this->EmployersSurveys->Session->destroy();
            unset($this->EmployersSurveys);
            ClassRegistry::flush();
    }

    function testIndex() {
        $result = $this->testAction('/employers_surveys', array('return' => 'view'));
        $this->assertPattern('/survey careerSeekersSurveys/', $result);
        $this->assertPattern('/EmployersSurveyAddForm/', $result);
    }
	
	function testAdd() {
		$this->EmployersSurveys->data = array(
			'EmployersSurvey' => array(
				'first_name' => 'Crash',
				'last_name' => 'Test Dummy',
				'title' => '',
				'date_you_worked_with_the_business_services_team_or_the_website' => array(
					'month' => '01',
					'day' => '10',
					'year' => '2010'
				),
				'are_your_comments_related_to_the_business_services_team_or_the_website' => '',
				'names_of_staff_who_assisted_you' => '',
				'overall_how_satisfied_are_you_with_the_services_you_received' => '',
				'think_about_what_you_expected' => '',
				'think_about_the_ideal_services_for_other_people' => '',
				'food_stamps' => '1',
				'unemployment_comp' => '1',
				'wia' => '0',
				'vocational_rehab' => '1',
				'welfare_transition' => '1',
				'veterans_services' => '0',
				'universal_services' => '0',
				'how_did_you_learn' => 'Billboard',
				'if_you_chose_Other' => '',
				'industry' => 'Business Services',
				'gender' => 'Male',
				'age' => '55-64',
				'highest_level_of_education_attained' => 'Associates Degree',
				'please_share_any_comments' => ''
			)
		);
		
			$this->EmployersSurveys->params = Router::parse('/employers_surveys/add');
	   		$this->EmployersSurveys->beforeFilter();
	    	$this->EmployersSurveys->Component->startup($this->EmployersSurveys);			
	        $this->EmployersSurveys->add();			    	
	        $this->assertEqual($this->EmployersSurveys->Session->read('Message.flash.element'), 'flash_success');
	}
	
	function testSuccess() {
        $result = $this->testAction('/employers_surveys/success', array('return' => 'view'));
		$result = htmlentities($result);
		$this->assertPattern('/Survey Submitted/', $result);
		$this->assertPattern('/Thank you for completing our Employer Survey/', $result);
	}

	function testAdminIndex() {
	    $this->EmployersSurveys->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
			
		$result = $this->testAction('/admin/employers_surveys', array('return' => 'vars'));
		
		$secondExpectedRecord = array(
			'EmployersSurvey' => array(
				'id' => 1,
				'answers' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'created' => '2011-03-23 15:46:08',
				'modified' => '2011-03-23 15:46:08'
			)
		);

		$this->assertEqual($result['employersSurveys'][0], $secondExpectedRecord);
	}
	
	function testAdminRead() {
	    $this->EmployersSurveys->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		
		$expected = array(
			'EmployersSurvey' => array(
				'first_name' => 'John',
				'last_name' => 'Daly',
				'title' => '',
				'date_you_worked_with_the_business_services_team_or_the_website' => array(
					'month' => '03',
					'day' => '23',
					'year' => '2011'
				),
				'are_your_comments_related_to_the_business_services_team_or_the_website' => '',
				'names_of_staff_who_assisted_you' => '',
				'overall_how_satisfied_are_you_with_the_services_you_received' => '',
				'think_about_what_you_expected' => '',
				'think_about_the_ideal_services_for_other_people' => '',
				'food_stamps' => '1',
				'unemployment_comp' => '1',
				'wia' => '0',
				'vocational_rehab' => '1',
				'welfare_transition' => '1',
				'veterans_services' => '0',
				'universal_services' => '0',
				'how_did_you_learn' => 'Billboard',
				'if_you_chose_Other' => '',
				'industry' => 'Business Services',
				'gender' => 'Male',
				'age' => '55-64',
				'highest_level_of_education_attained' => 'Associates Degree',
				'please_share_any_comments' => ''
			)
		);
		
		$result = $this->testAction('/admin/employers_surveys/read', array('return' => 'vars'));
	}

	function testAdminDestroy() {
	    $this->EmployersSurveys->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		
		$this->EmployersSurveys->params = Router::parse('/admin/employers_surveys/destroy');
   		$this->EmployersSurveys->params['form']['surveys'] = "1";
   		$this->EmployersSurveys->beforeFilter();
    	$this->EmployersSurveys->Component->startup($this->EmployersSurveys);			
        $result = json_decode($this->EmployersSurveys->admin_destroy(), true);
		$this->assertFalse($this->EmployersSurveys->EmployersSurvey->read(null, 1));
	}
	
	function testAdminInvalidDestroy() {
	    $this->EmployersSurveys->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		
		$this->EmployersSurveys->params = Router::parse('/admin/employers_surveys/destroy');
   		$this->EmployersSurveys->params['form']['surveys'] = "10000";
   		$this->EmployersSurveys->beforeFilter();
    	$this->EmployersSurveys->Component->startup($this->EmployersSurveys);			
        $result = json_decode($this->EmployersSurveys->admin_destroy(), true);
		$this->assertFalse($result['success']);
	}

}
?>
<?php
/* CareerSeekersSurveys Test cases generated on: 2011-03-21 16:24:46 : 1300724686*/
App::import('Controller', 'CareerSeekersSurveys');
App::import('Lib', 'AtlasTestCase');
class TestCareerSeekersSurveysController extends CareerSeekersSurveysController {
        var $autoRender = false;

        function redirect($url, $status = null, $exit = true) {
                $this->redirectUrl = $url;
        }
}

class CareerSeekersSurveysControllerTestCase extends AtlasTestCase {
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
            $this->CareerSeekersSurveys =& new TestCareerSeekersSurveysController();
            $this->CareerSeekersSurveys->constructClasses();
    }

    function endTest() {
    		$this->CareerSeekersSurveys->Session->destroy();
            unset($this->CareerSeekersSurveys);
            ClassRegistry::flush();
    }

    function testIndex() {
        $result = $this->testAction('/career_seekers_surveys', array('return' => 'view'));
        $this->assertPattern('/survey careerSeekersSurveys/', $result);
        $this->assertPattern('/CareerSeekersSurveyAddForm/', $result);
    }
	
	function testAdd() {
		$this->CareerSeekersSurveys->data = array(
			'CareerSeekersSurvey' => array(
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
		
			$this->CareerSeekersSurveys->params = Router::parse('/career_seekers_surveys/add');
	   		$this->CareerSeekersSurveys->beforeFilter();
	    	$this->CareerSeekersSurveys->Component->startup($this->CareerSeekersSurveys);			
	        $this->CareerSeekersSurveys->add();			    	
	        $this->assertEqual($this->CareerSeekersSurveys->Session->read('Message.flash.element'), 'flash_success');
	}
	
	function testSuccess() {
        $result = $this->testAction('/career_seekers_surveys/success', array('return' => 'view'));
		$result = htmlentities($result);
		$this->assertPattern('/Survey Submitted/', $result);
		$this->assertPattern('/Thank you for completing our Career Seeker Survey/', $result);
	}

	function testAdminIndex() {
	    $this->CareerSeekersSurveys->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
			
		$result = $this->testAction('/admin/career_seekers_surveys', array('return' => 'vars'));
		
		$secondExpectedRecord = array(
			'CareerSeekersSurvey' => array(
				'id' => 1,
				'answers' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'created' => '2011-03-21 16:24:29',
				'modified' => '2011-03-21 16:24:29'
			)
		);

		$this->assertEqual($result['careerSeekersSurveys'][0], $secondExpectedRecord);
	}
	
	function testAdminRead() {
	    $this->CareerSeekersSurveys->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		
		$expected = array(
			'CareerSeekersSurvey' => array(
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
		
		$result = $this->testAction('/admin/career_seekers_surveys/read', array('return' => 'vars'));
	}

	function testAdminDestroy() {
	    $this->CareerSeekersSurveys->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		
		$this->CareerSeekersSurveys->params = Router::parse('/admin/career_seekers_surveys/destroy');
   		$this->CareerSeekersSurveys->params['form']['surveys'] = "1";
   		$this->CareerSeekersSurveys->beforeFilter();
    	$this->CareerSeekersSurveys->Component->startup($this->CareerSeekersSurveys);			
        $result = json_decode($this->CareerSeekersSurveys->admin_destroy(), true);
		$this->assertFalse($this->CareerSeekersSurveys->CareerSeekersSurvey->read(null, 1));
	}
	
	function testAdminInvalidDestroy() {
	    $this->CareerSeekersSurveys->Session->write('Auth.User', array(
	        'id' => 2,
	        'role_id' => 2,
	        'username' => 'dnolan',
	        'location_id' => 1
	    ));
		
		$this->CareerSeekersSurveys->params = Router::parse('/admin/career_seekers_surveys/destroy');
   		$this->CareerSeekersSurveys->params['form']['surveys'] = "10000";
   		$this->CareerSeekersSurveys->beforeFilter();
    	$this->CareerSeekersSurveys->Component->startup($this->CareerSeekersSurveys);			
        $result = json_decode($this->CareerSeekersSurveys->admin_destroy(), true);
		$this->assertFalse($result['success']);
	}
}
?>
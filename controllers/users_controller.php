<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

App::import('Core', 'HttpSocket');

class UsersController extends AppController {

	var $name = 'Users';
	var $components = array('Email');
	var $helpers = array('Nav');

	function beforeFilter() {
		parent::beforeFilter();

		$this->Security->blackHoleCallback = 'forceSSL';
		$this->Security->requireSecure();
		$this->User->recursive = 0;
		
		if(!empty($this->data['User'])) {
			foreach($this->data['User'] as $k => $v) {
				$this->data['User'][$k] = trim($v, ' ');
			}
		}
		if(isset($this->data['User']['username'])) {
			// TODO add the other login types
			if($this->params['action'] == 'admin_login' || $this->params['action'] == 'kiosk_self_sign_login')
			{
				$this->User->Behaviors->disable('Disableable');
				$user = $this->User->find('first', array('conditions' => array(
					'username' => $this->data['User']['username'],
					'password' => Security::hash($this->data['User']['password'], null, true))));
				if($user['User']['disabled'] == 1)
				{
					$this->Session->setFlash(__('This account has been disabled.', true), 'flash_failure');
					$this->redirect($this->referer());
				}
			}
		}
		if($this->params['action'] == 'kiosk_mini_registration')
		{
			$this->Security->validatePost = false;
		}
		$this->Auth->allowedActions = array(
			'kiosk_mini_registration',
			'admin_password_reset',
			'admin_login',
			'admin_logout',
			'kiosk_id_card_login',
			'kiosk_self_sign_login',
			'kiosk_sign_in_redirect',
			'login',
			'registration',
			'logout',
			'forgot_password',
			'reset_password',
			'kiosk_auto_logout');
		if($this->Auth->user('role_id') > 1) {
			$this->Auth->allow(
				'admin_auto_complete_customer',
				'admin_auto_complete_ssn_ajax',
				'admin_get_customers_by_first_and_last_name',
				'admin_get_customers_by_ssn',
				'admin_get_all_admins',
				'admin_customer_search',
				'admin_get_customer_by_id'
			);
		}

		if($this->params['action'] == 'kiosk_self_sign_login' && $this->RequestHandler->isPost())
		{
			$this->User->setValidation('customerLogin');
            $this->User->set($this->data);

            if($this->User->validates())
            {
            	$count = $this->User->find('count', array(
                    'conditions' => array(
                        'User.username' => $this->data['User']['username'],
                        'and' => array(
                            'User.password' => $this->Auth->password($this->data['User']['password'])))));

            	if($count == 0)
            	{
            		$this->redirect(array(
            			'action' => 'mini_registration',
                        $this->data['User']['username'], 
                        'kiosk' => true
                    	)
            		);
            	}
            }
		}
		
		if($this->Auth->user() &&  $this->params['action'] == 'admin_dashboard' ) {
			if(! $this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Users/admin_dashboard', '*')) {
				$this->Session->setFlash(__('You are not authorized to access the admin dashboard.', true) , 'flash_failure') ;
				$this->redirect('/');
			}
		}
		if($this->Auth->user() && $this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'Users/admin_resolve_login_issues', '*') == true) {
			$this->Auth->allow('admin_request_ssn_change', 'admin_get_admin_list');
		}

		if($this->params['action'] == 'admin_login' && $this->RequestHandler->isAjax()) {
			$this->Security->validatePost = false;
		}
		
		if($this->Auth->user('id') == NULL)
		{
			$this->set('user_logged_in', FALSE);
		}
		else
		{
			$this->set('user_logged_in', TRUE);
		}
	}

	function admin_index($disabled=false) {
		$this->set('title_for_layout', 'Customers');

		// check to see if user can view full ssn
		$role = $this->User->Role->findById($this->Session->read('Auth.User.role_id'));
		$canViewFullSsn = ($role['Role']['can_view_full_ssn']) ? true : false;

		if($disabled) {
			$this->User->Behaviors->disable('Disableable');
		}
		$this->User->recursive = -1;

		if (!empty($this->params['url'])) {
			$formParams = $this->params['url'];
		}

		unset($formParams['url']);

		if (empty($formParams)) {
			if (isset($this->params['named']['basic_search_term'])) {
				$formParams['basic_search_term'] = $this->params['named']['basic_search_term'];
				$formParams['search_type'] = 'basic';
			} else {
				$formParams['search_type'] = 'advanced';
				$formParams['search_by1'] = (isset($this->params['named']['search_by1'])) ? $this->params['named']['search_by1'] : '';
				$formParams['search_scope1'] = (isset($this->params['named']['search_scope1'])) ? $this->params['named']['search_scope1'] : '';
				$formParams['search_term1'] = (isset($this->params['named']['search_term1'])) ? $this->params['named']['search_term1'] : '';
				$formParams['search_by2'] = (isset($this->params['named']['search_by2'])) ? $this->params['named']['search_by2'] : '';
				$formParams['search_scope2'] = (isset($this->params['named']['search_scope2'])) ? $this->params['named']['search_scope2'] : '';
				$formParams['search_term2'] = (isset($this->params['named']['search_term2'])) ? $this->params['named']['search_term2'] : '';
			}
		}

		// set up the default paginate options
		$conditions = array('User.role_id' => 1);
		$limit = Configure::read('Pagination.customer.limit');
		$order = array('User.lastname' => 'ASC');

		if (!empty($formParams)) {
			switch ($formParams['search_type']) {
				case 'basic':
					$this->basicSearch($formParams, $conditions, $limit, $order);
					break;

				case 'advanced':
					$this->advancedSearch($formParams, $conditions, $limit, $order);
					break;
			}
		} else {
			$this->paginate = array(
				'conditions' => $conditions,
				'limit' => $limit,
				'order' => $order
			);
		}

		$this->set(compact('canViewFullSsn'));
		$this->set('users', $this->paginate('User'));
	}

	function admin_add() {
		$this->User->Behaviors->disable('Disableable');
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->params['form'])) {
				$this->data['User'] = $this->params['form'];
			}
		}
		if (!empty($this->data)) {
			$this->User->create();
			$this->User->setValidation('customerMinimum');
			if ($this->User->save($this->data)) {
				$this->Transaction->createUserTransaction('Customer',
					null, null, 'Added customer '. $this->data['User']['lastname'] .
					', ' . $this->data['User']['firstname'] . ' - ' . substr($this->data['User']['ssn'], -4));
				$message = __('The customer has been saved', true);
				if($this->RequestHandler->isAjax()) {
					$data['success'] = true;
					$data['message'] = $message;
				}
				else {
					$this->Session->setFlash($message, 'flash_success');
					$this->redirect(array('action' => 'index'));
				}
			}
			else {
				$message = __('The customer could not be saved. Please, try again.', true);
				if($this->RequestHandler->isAjax()) {
					$errors = $this->User->invalidFields();
					if(!empty($errors)) {
						foreach($errors as $k => $v) {
							$data['errors'][$k] = $v;
						}
						$message = __('Form has errors. Please correct them.', true);
					}
					$data['success'] = false;
					$data['message'] = $message;
				}
				else {
					$this->Session->setFlash($message, 'flash_failure');
				}
			}
		}
		if($this->RequestHandler->isAjax()){
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
		else {
			$data = array(
				'states' => $this->states,
				'genders' => $this->genders,
				'statuses' => $this->statuses
			);
			$this->set('title_for_layout', 'Add Customer');
			$this->set($data);
		}
	}

	function admin_edit($id = null) {
		$this->User->Behaviors->disable('Disableable');
		$this->set('title_for_layout', 'Edit Customer');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid customer', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			$this->User->setValidation('customerMinimum');
			if ($this->User->save($this->data)) {
				$this->Transaction->createUserTransaction('Customer',
					null, null, 'Edited customer '. $this->data['User']['lastname'] .
					', ' . $this->data['User']['firstname'] . ' - ' . substr($this->data['User']['ssn'],-4));
				$this->Session->setFlash(__('The customer has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			}
			else {
				$this->Session->setFlash(__('The customer could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
			if($this->data['User']['role_id'] != 1) {
				$this->Session->setFlash(__('Invalid customer', true), 'flash_failure');
				$this->redirect($this->referer());
			}
		}
		$data = array(
			'states' => $this->states,
			'genders' => $this->genders,
			'statuses' => $this->statuses
		);
		$this->set($data);
	}

	function dashboard() {
		$this->loadModel('Program');
		$this->loadModel('Ecourse');

		$this->User->EcourseUser->Behaviors->attach('Containable');
		$this->Ecourse->Behaviors->attach('Containable');

		$this->Program->contain(array(
			'ProgramResponse' => array(
				'conditions' => array(
					'user_id' => $this->Auth->user('id')
				),
				'fields' => array('id', 'status')
			)
		));

		$programs = $this->Program->query(
			"SELECT * FROM programs AS Program
			LEFT JOIN program_responses AS ProgramResponse
			ON Program.id = ProgramResponse.program_id
			AND ProgramResponse.user_id = " . $this->Auth->user('id') .	
			" WHERE Program.in_test = 0
			AND Program.show_in_dash = 1"
		);


		$esignProgram = $this->Program->find('first', array(
			'conditions' => array(
				'Program.type' => array('Esign', 'esign', 'E-sign', 'e-sign')
			)
		));

		if($programs) {
			$orientations = array();
			$registrations = array();
			$ecourses = array();
			$enrollments = array();
			foreach($programs as $program) {
				switch($program['Program']['type']) {
				case 'orientation':
					$orientations[] = $program;
					break;
				case 'registration':
					$registrations[] = $program;
					break;
				case 'enrollment':
					$enrollments[] = $program;
					break;
				case 'ecourse':
					$ecourses[] = $program;
					break;
				}
			}
		}

		$eventRegistrations = $this->User->EventRegistration->find('all',
			array(
				'conditions' => array('EventRegistration.user_id' => $this->Auth->user('id'))
			)
		);

		$assignedEcourses = $this->User->EcourseUser->find('all',
			array(
				'conditions' => array(
					'Ecourse.type' => 'customer',
					'Ecourse.disabled' => '0',
					'EcourseUser.user_id' => $this->Auth->user('id')
				),
				'contain' => array(
					'Ecourse' => array(
						'EcourseModule',
						'EcourseResponse' => array(
							'conditions' => array(
								'EcourseResponse.user_id' => $this->Auth->user('id'),
								'EcourseResponse.reset' => 0
							),
							'EcourseModuleResponse'
						)
					)
				)
			)
		);

		$completedAssignedEcourses = $this->Ecourse->EcourseResponse->find('all',
			array(
				'conditions' => array(
					'EcourseResponse.user_id' => $this->Auth->user('id'),
					'EcourseResponse.status' => 'completed',
					'Ecourse.requires_user_assignment' => 1,
					'Ecourse.disabled' => '0'
				),
				'contain' => array(
					'Ecourse' => array(
						'EcourseModule',
						'EcourseResponse' => array(
							'EcourseModuleResponse'
						)
					)
				)
			)
		);


		$publicEcourses = $this->Ecourse->find('all',
			array(
				'conditions' => array(
					'Ecourse.requires_user_assignment' => 0,
					'Ecourse.type' => 'customer',
					'Ecourse.disabled' => '0'
				),
				'contain' => array(
					'EcourseModule' => array('order' => 'EcourseModule.order ASC'),
					'EcourseResponse' => array(
						'conditions' => array(
							'EcourseResponse.user_id' => $this->Auth->user('id'),
							'EcourseResponse.reset' => 0
						),
						'EcourseModuleResponse'
					)
				)
			)
		);

		$ecourses = array();

		if ($assignedEcourses) {
			foreach ($assignedEcourses as $ecourse) {
				$ecourses[]['Ecourse'] = $ecourse['Ecourse'];
			}
		}

		if ($publicEcourses) {
			foreach ($publicEcourses as $key => $ecourse) {
				$ecourse['Ecourse']['EcourseModule'] = $ecourse['EcourseModule'];
				$ecourse['Ecourse']['EcourseResponse'] = $ecourse['EcourseResponse'];
				$ecourses[]['Ecourse'] = $ecourse['Ecourse'];
			}
		}

		if ($completedAssignedEcourses) {
			$i = 0;
			foreach ($completedAssignedEcourses as $ecourse) {
				$ecourse['Ecourse']['EcourseModule'] = $ecourse['Ecourse']['EcourseModule'];
				$ecourse['Ecourse']['EcourseResponse'][$i] = $ecourse['EcourseResponse'];
				$ecourse['Ecourse']['EcourseResponse'][$i]['EcourseModuleResponse'] = $ecourse['EcourseModuleResponse'];
				$ecourses[]['Ecourse'] = $ecourse['Ecourse'];
				$i++;
			}
		}

		$title_for_layout = 'Customer Dashboard';
		$this->set(compact('title_for_layout', 'orientations', 'registrations', 'enrollments', 'eventRegistrations', 'ecourses', 'esignProgram'));
	}

	function edit($id=null) {
		$this->User->Behaviors->disable('Disableable');
		$this->set('title_for_layout', 'Edit Profile');
		if ((!$id && empty($this->data)) || $id != $this->Auth->user('id')) {
			$this->Session->setFlash(__('Invalid profile', true), 'flash_failure');
			$this->redirect('/');
		}
		if (!empty($this->data)) {
			if ($this->User->save($this->data)) {
				$this->data = $this->User->read(null, $this->Auth->user('id'));
				$this->Session->write('Auth.User.email', $this->data['User']['email']);
				$this->Transaction->createUserTransaction('Customer',
					null, null, 'Edited profile '. $this->data['User']['lastname'] .
					', ' . $this->data['User']['firstname'] . ' - ' . substr($this->data['User']['ssn'],-4));
				$this->Session->setFlash(__('profile has been saved', true), 'flash_success');
				if($this->Session->read('Auth.redirect') != '') {
					$this->redirect($this->Session->read('Auth.redirect'));
				}
				else {
					$this->redirect('/');
				}
			}
			else {
				$this->Session->setFlash(__('Profile could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
			if($this->data['User']['role_id'] != 1) {
				$this->Session->setFlash(__('Invalid profile', true), 'flash_failure');
				$this->redirect('/');
			}
		}
		$data = array(
			'states' => $this->states,
			'genders' => $this->genders
		);
		$this->set($data);
	}

	/*
		Will be the initial action when program routes to '/kiosk' 
		@name kiosk redirect
	*/
	function kiosk_sign_in_redirect()
	{
		$this->loadModel('Kiosk');
		$oneStop = env('HTTP_USER_AGENT');
		$arrOneStop = explode('##', $oneStop);
		if(!isset($arrOneStop[1])) {
			$oneStopLocation = '';
		}
		else {
			$oneStopLocation = $arrOneStop[1];
		}
		$this->Kiosk->recursive = -1;
		$this->Kiosk->Behaviors->attach('Containable');
		$this->Kiosk->contain(array('KioskSurvey', 'Location'));
		$settings = Cache::read('settings');
		$fields = Set::extract('/field',  json_decode($settings['SelfSign']['KioskRegistration'], true));
		$kiosk = $this->Kiosk->find('first', array(
			'conditions' => array(
				'Kiosk.location_recognition_name' => $oneStopLocation, 'Kiosk.deleted' => 0)));

		if($kiosk['Kiosk']['default_sign_in'] == 'id_card')
		{
			$this->redirect(array('controller' => 'users', 'action' => 'kiosk_id_card_login'));	
		}
		else if($kiosk['Kiosk']['default_sign_in'] == 'user_login')
		{
			$this->redirect(array('controller' => 'users', 'action' => 'kiosk_self_sign_login'));
		}
		else
		{
			$this->redirect(array('controller' => 'users', 'action' => 'kiosk_self_sign_login'));
		}
	}

	function kiosk_self_sign_login() {
		$this->loadModel('Kiosk');
		$oneStop = env('HTTP_USER_AGENT');
		$arrOneStop = explode('##', $oneStop);

		$oneStopLocation = ( isset($arrOneStop[1]) ? $arrOneStop[1] : '' );

		$this->Kiosk->recursive = -1;
		$this->Kiosk->Behaviors->attach('Containable');
		$this->Kiosk->contain(array('KioskSurvey', 'Location'));
		$settings = Cache::read('settings');
		$fields = Set::extract('/field',  json_decode($settings['SelfSign']['KioskRegistration'], true));
		$kiosk = $this->Kiosk->find('first', array(
			'conditions' => array(
				'Kiosk.location_recognition_name' => $oneStopLocation, 'Kiosk.deleted' => 0)));

		/* start of if */
		if (isset($this->data['User']['login_type']) && $this->data['User']['login_type'] == 'kiosk') {
			if ($this->Auth->user()) {
				$user = $this->Auth->user();
				
				if($kiosk['Kiosk']['default_sign_in'] == 'id_card') {
					$idCard = $this->Session->read('idCard');
					if(!empty($idCard) && $idCard != NULL) {
						$this->User->create();
						$this->User->id = $this->Auth->user('id');
						$this->User->saveField('id_card_number', $idCard['id_full']);
						$this->User->save();

						/*
						$update_user['id'] = $this->Auth->user('id');
						$update_user['firstname'] = $idCard['first_name'];
						$update_user['middle_initial'] = $idCard['middle_name'][0];
						$update_user['lastname'] = $idCard['last_name'];
						$update_user['dob'] = $idCard['birth_month'] . '/' . $idCard['birth_day'] . '/' . $idCard['birth_year'];
						$update_user['address_1'] = $idCard['street'];
						$update_user['city'] = $idCard['city'];
						$update_user['state'] = $idCard['state'];
						$update_user['id_card_number'] = $idCard['id_full'];

						*/

						$this->Session->delete('idCard');
					}
				}
				$this->sendCustomerLoginAlert($user, $kiosk);

				if($user['User']['veteran']) {
					$this->sendCustomerDetailsAlert('veteran', $user, $kiosk);
				}
				foreach($user['User'] as $k => $v) {
					if($v === 'Spanish') {
						$this->sendCustomerDetailsAlert('spanish', $user, $kiosk);
					}
					if(in_array($k, $fields) && empty($v) && $v != 0) {
						$this->redirect(
							array('controller' => 'kiosks', 'action' => 'self_sign_edit', $user['User']['id']));
					}
				}
				$this->Transaction->createUserTransaction('Self Sign',
					null, $this->User->SelfSignLog->Kiosk->getKioskLocationId(), 'Logged in at self sign kiosk' );
				if($settings['SelfSign']['KioskConfirmation'] === 'on') {
					$this->redirect(array('controller' => 'kiosks', 'action' => 'self_sign_confirm'));
				}
				else {
					$this->redirect(array('controller' => 'kiosks', 'action' => 'self_sign_service_selection'));
				}
			}
		}
		/* end of if */
		$this->set('kioskHasSurvey', (empty($kiosk['KioskSurvey'])) ? false : true);
		$this->set('kiosk', $kiosk);
		$this->set('title_for_layout', 'Self Sign Kiosk');
		$this->layout = 'kiosk';
	}

	public function kiosk_id_card_login() {
		$this->loadModel('Kiosk');
		$oneStop = env('HTTP_USER_AGENT');
		$arrOneStop = explode('##', $oneStop);
		if(!isset($arrOneStop[1])) {
			$oneStopLocation = '';
		}
		else {
			$oneStopLocation = $arrOneStop[1];
		}
		$this->Kiosk->recursive = -1;
		$this->Kiosk->Behaviors->attach('Containable');
		$this->Kiosk->contain(array('KioskSurvey', 'Location'));
		$settings = Cache::read('settings');
		$fields = Set::extract('/field',  json_decode($settings['SelfSign']['KioskRegistration'], true));
		$kiosk = $this->Kiosk->find('first', array(
			'conditions' => array(
				'Kiosk.location_recognition_name' => $oneStopLocation, 'Kiosk.deleted' => 0)));
		
		if($this->RequestHandler->isPost()) {
			if(!empty($this->data)) {
				$data = $this->User->decodeIdString($this->data);

				if($data['success']) {
					$this->User->recursive = -1;
					$user = $this->User->findByIdCardNumber( $data['id_full'] );

					if(!$user) {
						$this->Session->write('idCard', $data);
					}
					if($user && $this->Auth->login($user)) {
						$this->Transaction->createUserTransaction('Self Sign',
							null, $this->User->SelfSignLog->Kiosk->getKioskLocationId(), 'Logged in at self sign kiosk' );
						if($settings['SelfSign']['KioskConfirmation'] === 'on') {
							$this->redirect(array('controller' => 'kiosks', 'action' => 'self_sign_confirm'));
						}
						else {
							$this->redirect(array('controller' => 'kiosks', 'action' => 'self_sign_service_selection'));
						}
					}
					else {
						$this->Session->setFlash('Please fill out information below to continue', 'flash_failure');
						$this->redirect(array('action' => 'self_sign_login'));
					}
				}
				else {
					$this->Session->setFlash($data['message'], 'flash_failure');
					$this->redirect(array('action' => 'id_card_login'));
				}
			}
		}

		$this->set('kioskHasSurvey', (empty($kiosk['KioskSurvey'])) ? false : true);
		$this->set('kiosk', $kiosk);
		$this->set('title_for_layout', 'Self Sign Kiosk');
		$this->layout = 'kiosk';
	}

	function login($type = NULL) {
		$loginType 	= 'website';
		$render 	= 'login';
		$program 	= FALSE;
		$program_id = FALSE;

		if( isset($this->params['pass'][0]) )
		{
			$loginType 	= $this->params['pass'][0]; //Represents program type ie. program/child/website/auditor
		}

		if( isset($this->params['pass'][1]) )
		{
			$program_id = $this->params['pass'][1]; //Represents program id if type == program
		}

		$ssn_length = Configure::read('Login.' . $loginType . '.ssn_length');
		$this->set('ssn_length', $ssn_length);

		$this->User->setValidation('last' . $ssn_length . 'ssn');

		if($loginType === 'program')
		{
			if(!$program_id)
			{
				$this->redirect(array(
					'action' => 'login',
					$loginType
				));
			}

			$this->loadModel('Program');
			$this->Program->contain(array(
				'ProgramInstruction' => array(
					'conditions' => array('ProgramInstruction.type' => 'login')
				)
			));

			$program = $this->Program->findById( $program_id );

			$session_redirect = '/programs/' . $program['Program']['type'] . '/' . $program_id;
			$this->Session->write( 'Auth.redirect', $session_redirect );

			$title_for_layout = $program['Program']['name'] . ' Login';

			if( !empty($program['ProgramInstruction'][0]) )
			{
				$instructions = $program['ProgramInstruction'][0]['text'];
				$this->set('instructions', $instructions);
			}

			if($program['Program']['atlas_registration_type'] == 'child')
			{
				$loginType = $program['Program']['atlas_registration_type'];
			}
		}

		if(!empty($this->data) && !$this->Auth->user())
		{
			$username 			= $_POST['data']['User']['username'];
			$password			= $_POST['data']['User']['password'];
			
			$this->User->recursive = -1;

			$conditions = array(
				'User.username' => $username
			);

			switch($ssn_length)
			{
				case 2:
					$conditions['User.ssn LIKE'] = '_______' . $password;
					break;
				case 3:
					$conditions['User.ssn LIKE'] = '______' . $password;
					break;
				case 4:
					$conditions['User.ssn LIKE'] = '%' . $password;
					break;
				case 5:
					$conditions['User.ssn LIKE'] = '%' . $password;
					break;
				case 6:
					$conditions['User.ssn LIKE'] = '___' . $password;
					break;
				case 7:
					$conditions['User.ssn LIKE'] = '__' . $password;
					break;
				case 8:
					$conditions['User.ssn LIKE'] = '_' . $password;
					break;
				default:
					$conditions['User.ssn'] =  $password;
			}

			$validation_data = array(
				'User' => array(
					'username' => $username,
					'password' => $password
				)
			);

			//Checks to see if that user exists in the database
			$login_user = $this->User->find('first', array(
				'conditions' => $conditions
			));

			//If the user does not exist they need to be redirected to registration
			if(empty($login_user['User']))
			{
				switch($loginType)
				{
					case 'website':
						$this->redirect(array(
							'action' => 'registration', 
							'regular',
		                    $username, 
		                    'kiosk' => false
	                    ));
						break;
					case 'child':
						$this->redirect(array(
							'action' => 'registration', 
							'child',
		                    $username, 
		                    'kiosk' => false
	                    ));
						break;
					case 'program':
						$this->redirect(array(
							'action' => 'registration', 
							'regular',
		                    $username,
		                    'program',
		                    $program_id, 
		                    'kiosk' => false
	                    ));
	                    break;
	                case 'child_program':
	                	$this->redirect(array(
	                		'action' => 'registration', 
	                		'child', 
	                		'program',
                        	$this->data['User']['username'], 
                        	'kisok' => false
                        ));
	                	break;
				}
			}

			$this->User->set($validation_data);

			if($this->User->validates())
			{
				$this->Auth->login($login_user['User']['id']);
			}
		}

		
		if($this->Auth->user()){
			if ($this->Auth->user('role_id') > 3)
			{
				$this->User->Role->recursive = -1;
				$role = $this->User->Role->find('first', array(
					'fields' => array('Role.name'),
					'conditions' => array('Role.id' => $this->Auth->user('role_id'))
				));
				$this->Session->write('Auth.User.role_name', $role['Role']['name']);
			}

			if (preg_match('/auditor/i', $this->Auth->user('role_name')))
			{
				$this->Transaction->createUserTransaction('Auditor', null, null, 'Logged into auditor dashboard');
				$this->redirect(array('action' => 'dashboard', 'auditor' => true));
			}
			else
			{	
				$this->Transaction->createUserTransaction('Website',
					null, null, 'Logged in using website.' );
				if($this->Auth->user('email') == null || preg_match('(none|nobody|noreply)', $this->Auth->user('email')))
				{
					$this->Session->setFlash('Please complete your profile to continue.', 'flash_success');
					$this->redirect(array(
						'controller' => 'users', 
						'action' => 'edit', 
						$this->Auth->user('id')
					));
				}
				
				if($this->Session->read('Auth.redirect') != '')
				{
					$this->redirect($this->Session->read('Auth.redirect'));
				}
				else {
					$this->redirect(array('action' => 'dashboard', 'admin' => false));
				}
			}
			
		}

		switch($loginType)
		{
			case 'child':
				$title_for_layout 	= 'Child Login';
				$render 			= 'child_login';
				break;

			case 'auditor':
				$title_for_layout 	= 'Auditor Login';
				$render 			= 'auditor_login';
				break;
			default:
				$title_for_layout 	= 'User Login';
				$render 			= 'login';
		}

		$this->set(compact('loginType', 'title_for_layout'));
		$this->render($render);
	}

	function logout($type=null, $logoutMsg=null) {
		if ($this->Auth->user('role_id') == '1') {

			if($type == 'web') {
				$this->Session->destroy();
				$this->redirect('/');
			}

			if($type == 'selfSign') {
				if(!empty($logoutMsg)) {
					$msg = $logoutMsg;
				}
				else {
					$msg = 'Your log in has been recorded, someone will be with you shortly.';
				}
			}
			else {
				$msg = 'You have logged out successfully.';
			}
			$this->Session->destroy();
			$this->Session->setFlash($msg, 'flash_success_modal');
			$this->redirect('/kiosk');
		}
		if (preg_match('/auditor/i', $this->Session->read('Auth.User.role_name'))) {
			$this->Session->destroy();
			$this->redirect('/users/login/auditor');
		}
		if ($this->Auth->user('role_id') != 1) {
			$this->redirect(array('action' => 'login', 'admin' => true));
		}
	}

	/*
	function registration($type=null, $lastname=null) {
		$usePassword = Configure::read('Registration.usePassword');

		if ($usePassword) {
			$this->User->editValidation('password');
		}
		
		if(isset($this->params['pass'][2], $this->params['pass'][3]) &&
			$this->params['pass'][2] === 'program') {
				$this->loadModel('Program');
				$this->Program->contain(array('ProgramInstruction' =>
					array('conditions' => array('ProgramInstruction.type' => 'registration'))));
				$program = $this->Program->findById($this->params['pass'][3]);
				if($program) {
					$this->set('instructions', $program['ProgramInstruction'][0]['text']);
					$title_for_layout = $program['Program']['name'] . ' Registration';
				}
			}
		if(!empty($this->data)) {
			$this->User->Behaviors->disable('Disableable');
			$this->User->create();
			if ($usePassword) {
				$this->User->editValidation('password');
			} else if(Configure::read('Registration.ssn') == 'last4') {
				if($this->data['User']['registration'] == 'child_website') {
					$this->User->editValidation('last4');
				}
				else {
					$this->User->editValidation('last4');
				}
				$this->data['User']['ssn'] =
					$this->data['User']['ssn_1'] .
					$this->data['User']['ssn_2'] .
					$this->data['User']['ssn_3'];
				$this->data['User']['ssn_confirm'] =
					$this->data['User']['ssn_1_confirm'] .
					$this->data['User']['ssn_2_confirm'] .
					$this->data['User']['ssn_3_confirm'];
			}
			if ($this->User->save($this->data)) {
				$userId = $this->User->getInsertId();
				if ($usePassword) {
					$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
				} else {
					$this->data['User']['password'] = Security::hash($this->data['User']['ssn'], null, true);
				}
				$this->data['User']['username'] = $this->data['User']['lastname'];
				$this->Auth->login($this->data);
				$this->Transaction->createUserTransaction('Website',
					$userId, null, 'User self registered using the website.');
				$this->Session->setFlash(__('Your account has been created.', true), 'flash_success');
				if($this->Session->read('Auth.redirect') != '') {
					$this->redirect($this->Session->read('Auth.redirect'));
				}
				else{
					$this->redirect('/');
				}
			}
			else {
				$this->Session->setFlash(__('The information could not be saved. Please, try again.', true), 'flash_failure');
				if(isset($this->data['User']['ssn']))
					unset($this->data['User']['ssn']);
				if(isset($this->data['User']['ssn_confirm']))
					unset($this->data['User']['ssn_confirm']);
			}
		}
		if (empty($this->data)) {
			if($lastname) {
				$this->data['User']['lastname'] = $lastname;
			}
		}
		if(! isset($title_for_layout)) {
			$title_for_layout = 'Registration';
		}
		$states = $this->states;
		$this->set(compact('title_for_layout', 'states', 'usePassword'));
		if(isset($type) && $type == 'child' ||
			isset($this->data['User']['registration']) && $this->data['User']['registration'] == 'child_website') {
				$this->render('child_registration');
			}
	}*/

	function registration($type = NULL)
	{
		$usePassword 		= Configure::read('Registration.usePassword');
		$title_for_layout	= 'Registration';

		if($type != NULL)
			$ssn_length		= Configure::read('Registration.' . $type . '.ssn_length');
		else
			$ssn_length		= Configure::read('Registration.default.ssn_length');

		if($type == 'program')
		{
			$this->loadModel('Program');
			$this->Program->contain(array(
				'ProgramInstruction' => array(
					'conditions' => array(
						'ProgramInstruction.type' => 'registration'
					)
				)
			));

			if(isset( $this->params['pass'][3] ))
			{
				$program = $this->Program->findById($this->params['pass'][3]);
				$this->set('instructions', $program['ProgramInstruction'][0]['text']);
				$title_for_layout = $program['Program']['name'] . ' Registration';
			}
			else
			{
				$program = FALSE;
			}
		}

		if($this->RequestHandler->isPost() && !empty($this->data))
		{
			$this->User->editValidation('last' . $ssn_length . 'ssn');

			if( $usePassword )
			{
				$this->data['User']['password'] = Security::hash($this->data['User']['password'], null, true);
			}
			else
			{
				$this->data['User']['password'] = Security::hash($this->data['User']['ssn'], null, true);
			}

			$this->data['User']['username'] = $this->data['User']['lastname'];

			if($this->User->validates() && $this->User->save($this->data))
			{
				$user_id = $this->User->getInsertId();
				$user = $this->User->findById( $user_id );
				$this->Auth->login($user);

				$this->Transaction->createUserTransaction('Website',
					$user_id, null, 'User self registered using the website.');

				$this->Session->setFlash(__('Your account has been created.', true), 'flash_success');

				if($this->Session->read('Auth.redirect') != '') {
					$this->redirect($this->Session->read('Auth.redirect'));
				}
				else{
					$this->redirect('/');
				}
			}
			else
			{
				$this->Session->setFlash('There are mistakes in your information');
			}
		}

		$this->set(compact('title_for_layout', 'ssn_length', 'usePassword'));

		switch($type)
		{
			case 'child':
				$this->render('child_registration');
				break;
			default:
				$this->render('registration');
		}
	}

	function kiosk_auto_logout() {
		$this->Session->destroy();
		$this->Session->setFlash(__('You have been logged out due to inactivity.', true), 'flash_failure');
		$this->redirect('/kiosk');
	}

	function kiosk_mini_registration($lastname=null) {
		$this->loadModel('Kiosk');
		$this->Kiosk->recursive = -1;
		$this->User->recursive  = -1;
		if (!empty($this->data)) {
			$this->User->Behaviors->disable('Disableable');
			$this->User->setValidation('customerMinimum');
			if(Configure::read('Registration.ssn') == 'last4') {
				$this->User->editValidation('last4');
				$this->data['User']['ssn'] =
					$this->data['User']['ssn_1'] .
					$this->data['User']['ssn_2'] .
					$this->data['User']['ssn_3'];
				$this->data['User']['ssn_confirm'] =
					$this->data['User']['ssn_1_confirm'] .
					$this->data['User']['ssn_2_confirm'] .
					$this->data['User']['ssn_3_confirm'];
			}
			$this->User->create();
			if ($this->User->save($this->data)) {
				$userId = $this->User->getInsertId();
				$this->data['User']['password'] = Security::hash($this->data['User']['ssn'], null, true);
				$this->data['User']['username'] = $this->data['User']['lastname'];
				$this->Auth->login($this->data);
				$this->Transaction->createUserTransaction('Self Sign',
					$userId, $this->Kiosk->getKioskLocationId(), 'User self registered using a kiosk.');
				$this->Session->setFlash(__('Your account has been created.', true), 'flash_success');
				$this->redirect(array('controller' => 'kiosks', 'action' => 'self_sign_confirm'));
			}
			else {
				$this->Session->setFlash(__('The information could not be saved. Please, try again.', true), 'flash_failure');
				if(isset($this->data['User']['ssn']))
					unset($this->data['User']['ssn']);
				if(isset($this->data['User']['ssn_confirm']))
					unset($this->data['User']['ssn_confirm']);
			}
		}
		if (empty($this->data)) {
			$this->data['User']['lastname'] = $lastname;
			$kiosk = $this->Kiosk->findByLocationId(
				$this->Kiosk->getKioskLocationId()
			);
			if($kiosk['Kiosk']['default_sign_in'] == 'id_card') {
				$idCard = $this->Session->read('idCard');
				if(!empty($idCard) && $idCard != NULL) {
					$this->data['User']['firstname'] = $idCard['first_name'];
					$this->data['User']['middle_initial'] = substr($idCard['middle_name'], 1);
					$this->data['User']['lastname'] = $idCard['last_name'];
					$this->data['User']['dob'] = $idCard['birth_month'] . '/' . $idCard['birth_day'] . '/' . $idCard['birth_year'];
					$this->data['User']['address_1'] = $idCard['street'];
					$this->data['User']['city'] = $idCard['city'];
					$this->data['User']['state'] = $idCard['state'];
					$this->data['User']['id_card_number'] = $idCard['id_full'];

					$this->Session->delete('idCard');
				}
			}
		}
		$settings = Cache::read('settings');
		$fields = Set::extract('/field',  json_decode($settings['SelfSign']['KioskRegistration'], true));
		$title_for_layout = 'Self Sign Kiosk';
		$states = $this->states;
		$genders = $this->genders;
		$this->set(compact('title_for_layout', 'states', 'fields', 'genders'));
		$this->layout = 'kiosk';
	}

	function admin_login() {
		if($this->RequestHandler->isAjax()) {
			$this->Auth->login($this->data);
			if($this->Auth->user()) {
				$response = array('success' => true, 'sessId' => $this->Session->id());
			}
			else {
				$response = array('success' => false);
			}
			$this->set(compact('response'));
			$this->render('/users/admin_login_ajax');
		}
		else {
			$this->set('title_for_layout', Configure::read('Company.name') . ' | Administration Login');
			if ($this->Auth->user()) {
				$this->Transaction->createUserTransaction('Administrator', null, null, 'Logged into administrator area');
				$this->redirect(array('action' => 'dashboard', 'admin' => true));
			}
		}
	}

	function admin_logout() {
		// check if the user has a locked document in the document filing queue
		$this->User->QueuedDocument->checkLocked($this->Auth->user('id'));
		$this->Session->destroy();
		$this->redirect( array('action' => 'login', 'admin' => true));
	}

	function admin_index_admin($disabled=false) {
		$this->User->recursive = 0;

		$auditorRole = $this->User->Role->find('first', array(
			'conditions' => array(
				'Role.name' => array('Auditor', 'auditor')
			)
		));

		if($disabled) {
			$this->User->Behaviors->disable('Disableable');
		}
		$perms = $this->Acl->Aro->find('all', array('conditions' => array('Aro.model' => 'User')));
		foreach($perms as $k => $v) {
			if(!empty($v['Aco'])) {
				$newPerms[] = $v;
			}
		}
		if(isset($newPerms)) {
			$filteredPerms = Set::extract('/Aro/foreign_key', $newPerms);
		}
		else
			$filteredPerms = array();

		if(! empty($this->data) && $this->data['User']['search_term'] != '') {
			$this->paginate = array(
				'conditions' => array(
					'User.role_id >' => 2,
					'User.role_id <>' => $auditorRole['Role']['id'],
					$this->data['User']['search_by'] . ' LIKE' => '%' . $this->data['User']['search_term'] . '%'
				),
				'limit' => Configure::read('Pagination.admin.limit'),
				'order' => array(
					'User.lastname' => 'asc'
				)
			);

			if($this->Auth->user('role_id') == 2) {
				$this->paginate['conditions']['User.role_id >'] = 1;
			}

			$data = array(
				'users' => $this->paginate('User'),
				'perms' => $filteredPerms,
				'title_for_layout' => 'Administrators'
			);

			$this->set($data);
			$this->passedArgs['search_by'] = $this->data['User']['search_by'];
			$this->passedArgs['search_term'] = $this->data['User']['search_term'];
		}
		elseif(isset($this->passedArgs['search_term'], $this->passedArgs['search_by'])) {
			$this->paginate = array(
				'conditions' => array(
					'User.role_id >' => 2,
					'User.role_id <>' => $auditorRole['Role']['id'],
					$this->passedArgs['search_by'] . ' LIKE' => '%' . $this->passedArgs['search_term'] . '%'
				),
				'limit' => Configure::read('Pagination.admin.limit'),
				'order' => array(
					'User.lastname' => 'asc'
				)
			);

			if($this->Auth->user('role_id') == 2) {
				$this->paginate['conditions']['User.role_id >'] = 1;
			}

			$data = array(
				'users' => $this->paginate('User'),
				'perms' => $filteredPerms,
				'title_for_layout' => 'Administrators'
			);

			$this->set($data);
		}
		else {
			$this->paginate = array(
				'conditions' => array(
					'User.role_id >' => 2,
					'User.role_id <>' => $auditorRole['Role']['id']
				),
				'limit' => Configure::read('Pagination.admin.limit'),
				'order' => array(
					'User.lastname' => 'asc'
				)
			);

			if($this->Auth->user('role_id') == 2) {
				$this->paginate['conditions']['User.role_id >'] = 1;
			}
			elseif($this->Auth->user('role_id') > 3) {
				$this->paginate['conditions']['User.role_id >'] = 3;
			}

			$data = array('users' => $this->paginate('User'), 'perms' => $filteredPerms, 'title_for_layout' => 'Administrators');
			$this->set($data);
		}
	}

	function admin_index_auditor($disabled=false) {
		$this->User->recursive = -1;
		$this->User->Behaviors->attach('Containable');
		$this->User->Role->recursive = -1;
		$auditorRole = $this->User->Role->find('first', array(
			'conditions' => array(
				'Role.name' => array('Auditor', 'auditor')
			)
		));

		$this->paginate = array(
			'conditions' => array(
				'User.role_id' => $auditorRole['Role']['id']
			),
			'contain' => array(
				'Audit'
			),
			'limit' => Configure::read('Pagination.admin.limit'),
			'order' => array('User.lastname' => 'asc')
		);

		$data = array(
			'auditors' => $this->paginate('User'),
			'title_for_layout' => 'Auditors'
		);

		$this->set($data);
	}

	function admin_add_admin() {
		$this->set('title_for_layout', 'Add Administrator');
		$this->User->Behaviors->disable('Disableable');
		if (!empty($this->data)) {
			$this->User->create();
			$this->User->setValidation('admin');
			if ($this->data['User']['email'] != Configure::read('PrePop.email')) {
				$userEmail = $this->data['User']['email'];
				$this->set(compact('userEmail'));
			}
			if ($this->User->save($this->data)) {
				$message = 'Welcome to the Atlas system.' . "\r\n\r\n";
				$message .= 'Your username is: ' . substr($this->data['User']['firstname'], 0, 1).$this->data['User']['lastname'] . "\r\n\r\n";
				$message .= 'Your password is: ' . $this->data['User']['pass'] . "\r\n\r\n";
				$message .= 'You can now login at ' . Router::url('/admin', true);
				$this->Email->from = Configure::read('System.email');
				$this->Email->to = $this->data['User']['firstname']." ".$this->data['User']['lastname']."<".$this->data['User']['email'].">";
				$this->Email->subject = 'Welcome to Atlas.';
				$this->Email->send($message);
				$this->Transaction->createUserTransaction('Administrator',
					null, null, 'Added administrator '. $this->data['User']['lastname'] . ', ' . $this->data['User']['firstname'] );
				$this->Session->setFlash(__('The admin has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index_admin'));
			}
			else {
				$this->Session->setFlash(__('The admin could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if($this->Auth->user('role_id') == 2) {
			$conditions = array("NOT" => array(array('Role.id' => array(1))));
		}
		elseif($this->Auth->user('role_id') == 3) {
			$conditions = array("NOT" => array(array('Role.id' => array(1,2))));
		}
		else $conditions = array("NOT" => array(array('Role.id' => array(1,2,3))));

		$data = array(
			'roles' => $this->User->Role->find('list', array(
				'conditions' => $conditions)),
			'locations' => $this->User->Location->find('list')
		);
		$this->set($data);
	}

	function admin_edit_admin($id = null) {
		$this->User->Behaviors->disable('Disableable');
		$this->set('title_for_layout', 'Edit Administrator');
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid admin', true), 'flash_failure');
			$this->redirect(array('action' => 'index_admin'));
		}
		if (!empty($this->data)) {
			if ($this->data['User']['pass'] == '') {
				unset($this->data['User']['pass']);
			}
			else {
				$message = 'Your password has been changed' . "\r\n\r\n";
				$message .= 'Your new password is: ' . $this->data['User']['pass'] . "\r\n\r\n";
				$this->Email->from = Configure::read('System.email');
				$this->Email->to = $this->data['User']['firstname']." ".$this->data['User']['lastname']."<".$this->data['User']['email'].">";
				$this->Email->subject = 'Your Atlas password has been changed.';
				$this->Email->send($message);
			}
			$this->User->setValidation('admin');
			if ($this->User->save($this->data)) {
				$this->Transaction->createUserTransaction('Administrator', null, null,
					'Edited administrator '. $this->data['User']['lastname'] . ', ' . $this->data['User']['firstname']);
				$this->Session->setFlash(__('The admin has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index_admin'));
			}
			else {
				$this->Session->setFlash(__('The admin could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		if($this->Auth->user('role_id') == 2) {
			$conditions = array("NOT" => array(array('Role.id' => array(1))));
		}
		elseif($this->Auth->user('role_id') == 3){
			$conditions = array("NOT" => array(array('Role.id' => array(1,2))));
		}
		else $conditions = array("NOT" => array(array('Role.id' => array(1,2,3))));
		$data = array(
			'roles' => $this->User->Role->find('list', array(
				'conditions' => $conditions)),
			'locations' => $this->User->Location->find('list')
		);
		$this->set($data);
	}

	function admin_dashboard() {
		$this->loadNavigationConfig();
		$this->loadPluginConfigs();
		$this->loadModel('Ecourse');

		$this->Ecourse->Behaviors->attach('Containable');
		$this->User->EcourseUser->Behaviors->attach('Containable');

		$assignedEcourses = $this->User->EcourseUser->find('all',
			array(
				'conditions' => array(
					'Ecourse.disabled' => '0',
					'Ecourse.type' => 'staff',
					'EcourseUser.user_id' => $this->Auth->user('id')
				),
				'contain' => array(
					'Ecourse' => array(
						'EcourseModule',
						'EcourseResponse' => array(
							'conditions' => array(
								'EcourseResponse.user_id' => $this->Auth->user('id'),
								'EcourseResponse.reset' => 0
							),
							'EcourseModuleResponse'
						)
					)
				)
			)
		);

		$publicEcourses = $this->Ecourse->find('all',
			array(
				'conditions' => array(
					'Ecourse.requires_user_assignment' => 0,
					'Ecourse.type' => 'staff',
					'Ecourse.disabled' => '0'
				),
				'contain' => array(
					'EcourseModule' => array('order' => 'EcourseModule.order ASC'),
					'EcourseResponse' => array(
						'conditions' => array(
							'EcourseResponse.user_id' => $this->Auth->user('id'),
							'EcourseResponse.reset' => 0
						),
						'EcourseModuleResponse'
					)
				)
			)
		);

		$completedAssignedEcourses = $this->Ecourse->EcourseResponse->find('all',
			array(
				'conditions' => array(
					'Ecourse.disabled' => '0',
					'Ecourse.requires_user_assignment' => 1,
					'EcourseResponse.user_id' => $this->Auth->user('id'),
					'EcourseResponse.status' => 'completed'
				),
				'contain' => array(
					'Ecourse' => array(
						'EcourseModule'
					),
					'EcourseModuleResponse'
				)
			)
		);

		$ecourses = array();

		if ($assignedEcourses) {
			foreach ($assignedEcourses as $ecourse) {
				$ecourses[]['Ecourse'] = $ecourse['Ecourse'];
			}
		}

		if ($publicEcourses) {
			foreach ($publicEcourses as $key => $ecourse) {
				$ecourse['Ecourse']['EcourseModule'] = $ecourse['EcourseModule'];
				$ecourse['Ecourse']['EcourseResponse'] = $ecourse['EcourseResponse'];
				$ecourses[]['Ecourse'] = $ecourse['Ecourse'];
			}
		}

		if ($completedAssignedEcourses) {
			$i = 0;
			foreach ($completedAssignedEcourses as $ecourse) {
				$ecourse['Ecourse']['EcourseModule'] = $ecourse['Ecourse']['EcourseModule'];
				$ecourse['Ecourse']['EcourseResponse'][$i] = $ecourse['EcourseResponse'];
				$ecourse['Ecourse']['EcourseResponse'][$i]['EcourseModuleResponse'] = $ecourse['EcourseModuleResponse'];
				$ecourses[]['Ecourse'] = $ecourse['Ecourse'];
				$i++;
			}
		}

		$title_for_layout = 'Administration Dashboard';
		$this->set(compact('title_for_layout', 'ecourses'));
	}

	function admin_password_reset() {
		$this->set('title_for_layout', 'Password Reset');
		if (isset($this->data['User']['email'])) {
			$user = $this->User->find('first', array('conditions' => array ('User.email' => $this->data['User']['email'])));
			$this->data['User']['id'] = $user['User']['id'];
			$this->data['User']['role_id'] = $user['User']['role_id'];
			if ($user['User']['email'] != '') {
				$tempPassword = rand(10000,100000);
				$this->data['User']['password'] = Security::hash($tempPassword, null, true);
				unset($this->data['User']['email']);
				if ($this->User->save($this->data, array('validate' => false))) {
					// Fire off the E-Mail
					$message = Router::url('/admin', true) . "\n\n" . 'Temp Password: ' . $tempPassword ;
					$this->Email->from = Configure::read('System.email');
					$this->Email->to = $user['User']['firstname']." ".$user['User']['lastname']." <".$user['User']['email'].">";
					$this->Email->subject = 'Password Reset Request';
					if($this->Email->send($message)) {
						// Set flash message
						$this->Session->setFlash(__('Your password has been changed. Please check your E-Mail.', true), 'flash_success');
						// Redirect to login page
						$this->redirect(array('action' => 'login', 'admin' => true));
					}
					else {
						$this->Session->setFlash(__('Error occured sending E-Mail. Please retry.', true), 'flash_failure');
						$this->redirect(array('action' => 'password_reset'));
					}
				}
				else {
					$this->Session->setFlash(__('Error saving record. Please retry.', true), 'flash_failure');
					$this->redirect(array('action' => 'password_reset'));
				}
			}
			else {
				// E-Mail address provided was not found in system.
				$this->Session->setFlash(__('E-Mail address not found', true), 'flash_failure');
				$this->redirect(array('action' => 'password_reset'));
			}
		}
	}

	function admin_resolve_login_issues() {
		if($this->RequestHandler->isAjax()) {
			if($this->RequestHandler->isPost())  {
				$postData = json_decode($this->params['form']['user'], true);
				$this->data['User']['id'] = $postData['id'];
				$this->data['User']['lastname'] = $postData['lastname'];
				$this->data['User']['username'] = $this->data['User']['lastname'];
				if($this->User->save($this->data, array('validate' => false))){
					$data['success'] = true;
					$this->data = $this->User->read(null, $postData['id']);
					$this->Transaction->createUserTransaction('Customer', null, null,
						'Edited last name for customer '. $this->data['User']['lastname'] . ', ' .
						$this->data['User']['firstname'] . ' - '. substr($this->data['User']['ssn'], -4)
					);
				}
				else {
					$data['success'] = false;
				}
				$this->set('data', $data);
				$this->render(null, null,  '/elements/ajaxreturn');
			}
			if($this->RequestHandler->isGet()) {
				$useDate = false;
				if(!empty($this->params['url']['from']) && !empty($this->params['url']['to'])) {
					$from = date('Y-m-d H:i:s', strtotime($this->params['url']['from'] . ' 00:00:01'));
					$to = date('Y-m-d H:i:s', strtotime($this->params['url']['to'] . '23:59:59'));
					$useDate = true;
				}
				$this->User->recursive = -1;
				if($this->params['url']['searchType'] == 'ssn') {
					if($useDate){
						$conditions = array(
							'RIGHT (User.ssn , 4) LIKE' => '%'.$this->params['url']['search'].'%',
							'User.role_id' => 1,
							'User.created BETWEEN ? AND ?' => array($from, $to)
						);
					}
					else {
						$conditions = array(
							'User.ssn LIKE' => '%'.$this->params['url']['search'].'%',
							'User.role_id' => 1
						);
					}

				}
				if($this->params['url']['searchType'] == 'lastname') {
					if($useDate){
						$conditions = array(
							'User.lastname LIKE' => '%'.$this->params['url']['search'].'%',
							'User.role_id' => 1,
							'User.created BETWEEN ? AND ?' => array($from, $to)
						);
					}
					else {
						$conditions = array(
							'User.lastname LIKE' => '%'.$this->params['url']['search'].'%',
							'User.role_id' => 1);
					}
				}
				$results = $this->User->find('all', array(
					'conditions' => $conditions));
				if($results) {
					$i = 0;
					foreach($results as $result) {
						$users['users'][$i]['id'] = $result['User']['id'];
						$users['users'][$i]['firstname'] = $result['User']['firstname'];
						$users['users'][$i]['lastname'] = $result['User']['lastname'];
						$users['users'][$i]['ssn'] = substr($result['User']['ssn'], -4);
						$i++;
					}
				}
				else {
					$users['users'] = array();
				}
				$this->set('data', $users);
				$this->render(null, null,  '/elements/ajaxreturn');
			}
		}
	}

	function admin_get_admin_list() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->params['form']['query'])) {
				$conditions = array( 'User.role_id' => 3, 'User.lastname LIKE' => '%'.$this->params['form']['query'].'%');
			}
			else {
				$conditions = array('User.role_id' => 3);
			}
			$this->User->recursive = -1;
			$admins = $this->User->find('all', array('conditions' => $conditions));
			if($admins) {
				$i = 0;
				foreach($admins as $admin) {
					$data['admins'][$i]['id'] = $admin['User']['id'];
					$data['admins'][$i]['name'] = $admin['User']['lastname'] . ', ' . $admin['User']['firstname'];
					$i++;
				}
				$data['success'] = true;
			}
			else {
				$data['success'] = false;
			}
			$this->set('data', $data);
			$this->render(null, null,  '/elements/ajaxreturn');

		}
	}

	function admin_get_all_admins() {
		if($this->RequestHandler->isAjax()) {
			$this->User->recursive = -1;
			$conditions = array('User.role_id >' => 2);
			if(isset($this->params['url']['query'])) {
				$conditions['User.lastname LIKE'] = '%'.$this->params['url']['query'].'%';
			}
			$admins = $this->User->find('all', array(
				'conditions' => $conditions,
				'order' => 'User.lastname ASC'));
			if($admins) {
				$i = 0;
				foreach($admins as $admin) {
					$data['admins'][$i]['id'] = $admin['User']['id'];
					$data['admins'][$i]['name'] = $admin['User']['lastname'] . ', ' . $admin['User']['firstname'];
					$i++;
				}
				$data['success'] = true;
			}
			else {
				$data['success'] = false;
			}
			$this->set('data', $data);
			$this->render(null, null,  '/elements/ajaxreturn');
		}
	}

	function admin_request_ssn_change() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->params['form']['userId']) && !empty($this->params['form']['adminId'])){
				$admin = $this->User->read(null, $this->params['form']['adminId']);
				$this->Email->from = $this->Auth->user('firstname') .' ' .
					$this->Auth->user('lastname') . '<'.$this->Auth->user('email') .'>';
				$this->Email->to = $admin['User']['email'];
				$this->Email->subject = 'SSN Change Request';
				$message = 'A Social Security Number edit has been requested by ' .
					$this->Auth->user('firstname'). ' ' . $this->Auth->user('lastname') . '.' .  "\r\n" .
					'Please edit the following customers record accordingly' . "\r\n" .
					Router::url('/admin/', true) . 'users/edit/' . $this->params['form']['userId']. "\r\n";
				if($this->Email->send($message)){
					$this->data = $this->User->read(null, $this->params['form']['userId']);
					$this->Transaction->createUserTransaction('Customer', null, null,
						'Requested SSN change for customer '. $this->data['User']['lastname'] . ', ' .
						$this->data['User']['firstname'] . ' - '. substr($this->data['User']['ssn'], -4)
					);
					$data['success'] = true;
				}
				else {
					$data['success'] = false;
				}
				$this->set('data', $data);
				$this->render(null, null, '/elements/ajaxreturn');
			}
		}
	}

	function admin_toggle_disabled($id=null, $disabled) {
		$this->_toggleDisabled($id, $disabled, 'Customer');
	}

	function admin_toggle_disabled_admin($id=null, $disabled) {
		$this->_toggleDisabled($id, $disabled, 'Administrator');
	}

	function admin_auto_complete_customer() {
		if($this->RequestHandler->isAjax()) {
			$this->User->recursive = -1;
			$query = $this->User->find('all', array(
				'conditions' => array(
					'User.role_id' => 1,
					'User.lastname' =>  $this->params['url']['lastname'],
					'User.firstname LIKE' => '%' . $this->params['url']['term'] . '%')));
			$this->_setAutoCompleteOptions($query);
			$this->render('/elements/app_controller/auto_complete_ajax');
		}
	}

	function admin_auto_complete_ssn() {
		if($this->RequestHandler->isAjax()) {
			$this->User->recursive = -1;
			$query = $this->User->find('all', array('conditions' => array('User.role_id' => 1, 'User.ssn LIKE' => '%' . $this->params['url']['term'] . '%')));
			$this->_setAutoCompleteOptions($query);
			$this->render('/elements/app_controller/auto_complete_ajax');
		}
	}

	function admin_get_customers_by_first_and_last_name() {
		if($this->RequestHandler->isAjax()) {
			$this->User->recursive = -1;
			if(isset($this->params['url']['query'])) {
				$params = explode(',', $this->params['url']['query']);
				$users = $this->User->find('all', array(
					'conditions' => array(
						'User.role_id' => 1,
						'User.lastname' => $params[0],
						'User.firstname LIKE' => '%' . $params[1] . '%')));
				$data = array();
			}
			if(isset($users)) {
				$i = 0;
				foreach($users as $user) {
					$data['users'][$i]['id'] = $user['User']['id'];
					$data['users'][$i]['firstname'] = $user['User']['firstname'];
					$data['users'][$i]['lastname'] = $user['User']['lastname'];
					$data['users'][$i]['fullname'] = $user['User']['name_last4'];
					$data['users'][$i]['fullssn'] =
						substr($user['User']['ssn'], 0, -6) . '-' .
						substr($user['User']['ssn'], 3, -4) . '-' .
						substr($user['User']['ssn'], -4);
					$i++;
				}
			}
			else {
				$data['users'] = array();
			}
			$data['success'] = true;
			$this->set(compact('data'));
			$this->render('/elements/ajaxreturn');
		}
	}

	function admin_get_customers_by_ssn() {
		if($this->RequestHandler->isAjax()) {
			$this->User->recursive = -1;
			if(isset($this->params['url']['query'])) {
				$users = $this->User->find('all', array(
					'conditions' => array(
						'User.role_id' => 1,
						'RIGHT (User.ssn , 4)' => $this->params['url']['query'])));
				$data = array();
			}
			if(isset($users)) {
				$i = 0;
				foreach($users as $user) {
					$data['users'][$i]['id'] = $user['User']['id'];
					$data['users'][$i]['ssn'] = substr($user['User']['ssn'], -4);
					$data['users'][$i]['fullname'] = $user['User']['name_last4'];
					$data['users'][$i]['lastname'] = $user['User']['lastname'];
					$data['users'][$i]['fullssn'] =
						substr($user['User']['ssn'], 0, -6) . '-' .
						substr($user['User']['ssn'], 3, -4) . '-' .
						substr($user['User']['ssn'], -4);
					$i++;
				}
			}
			else {
				$data['users'] = array();
			}
			$data['success'] = true;
			$this->set(compact('data'));
			$this->render('/elements/ajaxreturn');
		}
	}

	function _toggleDisabled($id, $disabled, $userType) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid user id', true), 'flash_failure');
			$this->redirect($this->referer());
		}
		$this->User->Behaviors->disable('Disableable');
		$user = $this->User->read(null, $id);
		$this->User->Behaviors->enable('Disableable');
		if($userType == 'Customer') {
			$user = $user['User']['lastname'] . ', ' . $user['User']['firstname'] . ' - '.
				substr($user['User']['ssn'], -4);
		}
		else {
			$user = $user['User']['lastname'] . ', ' . $user['User']['firstname'];
		}
		if($this->User->toggleDisabled($id, $disabled)) {
			if($disabled == 1){
				$this->Session->setFlash(__('User disabled successfully.', true), 'flash_success');
				$this->Transaction->createUserTransaction($userType, null, null,
					'Disabled '. strtolower($userType) . ' ' . $user);
			}
			else if($disabled == 0) {
				$this->Session->setFlash(__('User enabled successfully.', true), 'flash_success');
				$this->Transaction->createUserTransaction($userType, null, null,
					'Enabled '. strtolower($userType) . ' ' . $user);
			}
			$this->redirect($this->referer());
		}
		else {
			$this->Session->setFlash(__('An error occured.', true), 'flash_failure');
			$this->redirect($this->referer());
		}
	}


	function _setAutoCompleteOptions($query) {
		if(empty($query)) {
			$options[0] = 'No Results';
		}
		$firsts = Set::extract('/User/firstname', $query);
		$lasts = Set::extract('/User/lastname', $query);
		$ssns = Set::extract('/User/ssn', $query);
		$i = 0;
		foreach($firsts as $fisrt) {
			$options[$i] = $lasts[$i] . ', ' . $firsts[$i] . ', ' . $ssns[$i];
			$i++;
		}
		$this -> set('options', $options);
	}

	public function admin_customer_search() {
		if($this->RequestHandler->isAjax()) {
			if ($this->RequestHandler->isGet()) {
				$data['users'] = array();
				if(isset($this->params['url']['search'], $this->params['url']['searchType'])) {
					switch($this->params['url']['searchType']) {
						case 'lastname':
							$conditions['User.lastname'] = $this->params['url']['search'];
							break;
						case 'last4':
							$conditions['RIGHT (User.ssn , 4)'] = $this->params['url']['search'];
							break;
						case 'ssn':
							$conditions['User.ssn'] = $this->params['url']['search'];
							break;
					}
					$conditions['User.role_id'] = 1;
					$users = $this->User->find('all', array('conditions' => $conditions));
					if($users) {
						$i = 0;
						foreach($users as $user) {
							$data['users'][$i]['id'] = $user['User']['id'];
							$data['users'][$i]['firstname'] = $user['User']['firstname'];
							$data['users'][$i]['lastname'] = $user['User']['lastname'];
							$data['users'][$i]['email'] = $user['User']['email'];
							$data['users'][$i]['phone'] = $user['User']['phone'];
							$data['users'][$i]['last_4'] = substr($user['User']['ssn'], -4);
							$i++;
						}
					}
				}
				$data['success'] = true;
			} else {
				$user = json_decode($this->params['form']['user'], true);
				$userRecord = $this->User->findById($user['id']);
				$this->User->save(array(
					'User' => $user
				));
				$data['users'][] = $userRecord;
				$data['success'] = true;
			}

			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_get_customer_by_id() {
		if($this->RequestHandler->isAjax()) {
			$this->User->recursive = -1;
			$user = $this->User->findById($this->params['pass'][0], array('firstname', 'lastname', 'id'));
			if($user) {
				$data['user'] = $user['User'];
			}
			else {
				$data['user'] = array();
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_staff_search() {
		if($this->RequestHandler->isAjax()) {
			$data['users'] = array();
			if(isset($this->params['url']['search'], $this->params['url']['searchType'])) {
				$conditions['User.lastname'] = $this->params['url']['search'];
				// TODO: find out if this should include non role based admins
				$conditions['User.role_id >'] = 1;
				$users = $this->User->find('all', array('conditions' => $conditions));
				if($users) {
					$i = 0;
					foreach($users as $user) {
						$data['users'][$i]['id'] = $user['User']['id'];
						$data['users'][$i]['firstname'] = $user['User']['firstname'];
						$data['users'][$i]['lastname'] = $user['User']['lastname'];
						$i++;
					}
				}
			}
			$data['success'] = true;
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}
	// Auditors
	public function auditor_dashboard() {
		// check to see if there are any audits this user is associated
		// with that have started but have no expired
		$this->layout = 'ext_fullscreen';
	}

	private function sendCustomerDetailsAlert($detail, $user, $kiosk) {
		$this->loadModel('Alert');
		$data = $this->Alert->getCustomerDetailsAlerts($detail, $user, $kiosk);
		if($data) {
			$this->sendAlert($data, 'Customer Details alert');
		}
	}

	private function sendCustomerLoginAlert($user, $kiosk) {
		$this->loadModel('Alert');
		$data = $this->Alert->getCustomerLoginAlerts($user, $kiosk);
		if($data) {
			$this->sendAlert($data, 'Customer Login alert');
		}
	}

	private function sendAlert($data, $subject) {
		if($data && $subject) {
			$HttpSocket = new HttpSocket();
			$HttpSocket->post('localhost:3000/new', array('data' => $data));
			$to = '';
			foreach($data as $alert) {
				if($alert['send_email']) {
					$to .= $alert['email'] . ',';
				}
			}
			if(!empty($to)) {
				$to = trim($to, ',');
				$this->Email->to = $to;
				$this->Email->from = Configure::read('System.email');
				$this->Email->subject = $subject;
				$this->Email->send($alert['message'] . "\r\n" . $alert['url']);
			}
		}
	}

	private function loadNavigationConfig() {
		return Configure::load('navigation');
	}

	private function loadPluginConfigs() {
		$blackList = array('AclExtras', 'DebugKit');
		$plugins = App::objects('plugin');

		foreach ($plugins as $key => $value) {
			if (!in_array($value, $blackList)) {
				Configure::load(Inflector::underscore($value) . '.config');
			}
		}

		return true;
	}

	private function basicSearch($formParams, $conditions, $limit, $order) {
		$conditions = array(
			'OR' => array(
				'User.ssn LIKE' => '%' . $formParams['basic_search_term'] . '%',
				'User.lastname LIKE' => '%' . $formParams['basic_search_term'] . '%'
			)
		);

		$this->paginate = array(
			'conditions' => $conditions,
			'limit'      => $limit,
			'order'      => $order
		);

		$this->set($formParams);
	}

	private function advancedSearch($formParams, $conditions, $limit, $order) {
		if (!empty($formParams) && $formParams['search_term1'] !== '') {
			switch ($formParams['search_scope1']) {
			case 'containing':
				if ($formParams['search_by1'] === 'last4') {
					$conditionScope = 'RIGHT (User.ssn , 4) LIKE';
				} else if ($formParams['search_by1'] === 'fullssn') {
					$conditionScope = 'User.ssn LIKE';
				} else {
					$conditionScope = $formParams['search_by1'] . ' LIKE';
				}

				$conditionValue = '%' . $formParams['search_term1'] . '%';
				break;
			case 'matching exactly':
				if ($formParams['search_by1'] === 'last4') {
					$conditionScope = 'RIGHT (User.ssn , 4)';
				} else if ($formParams['search_by1'] === 'fullssn') {
					$conditionScope = 'User.ssn';
				} else {
					$conditionScope = $formParams['search_by1'];
				}

				$conditionValue = $formParams['search_term1'];
				break;
			}

			$conditions1 = array($conditionScope => $conditionValue);
			$conditions = array_merge($conditions, $conditions1);

			if (isset($formParams['search_by2']) && $formParams['search_by2'] !== '' && $formParams['search_term2'] !== '') {
				switch ($formParams['search_scope2']) {
				case 'containing':
					if ($formParams['search_by2'] === 'last4') {
						$conditionScope2 = 'RIGHT (User.ssn , 4) LIKE';
					} else if ($formParams['search_by2'] === 'fullssn') {
						$conditionScope2 = 'User.ssn LIKE';
					} else {
						$conditionScope2 = $formParams['search_by2'] . ' LIKE';
					}

					$conditionValue2 = '%' . $formParams['search_term2'] . '%';
					break;
				case 'matching exactly':
					if ($formParams['search_by2'] === 'last4') {
						$conditionScope2 = 'RIGHT (User.ssn , 4)';
					} else if ($formParams['search_by2'] === 'fullssn') {
						$conditionScope2 = 'User.ssn';
					} else {
						$conditionScope2 = $formParams['search_by2'];
					}

					$conditionValue2 = $formParams['search_term2'];
					break;
				}

				$conditions2 = array($conditionScope2 => $conditionValue2);
				$conditions = array_merge($conditions, $conditions2);
			}
			$this->set($formParams);
		}

		$this->paginate = array(
			'conditions' => $conditions,
			'limit'      => $limit,
			'order'      => $order
		);

	}

	public function forgot_password() {
		$this->User->recursive = -1;
		$title_for_layout = 'Forgot Password';

		if (!empty($this->data)) {
			$user = $this->User->findByEmail($this->data['User']['email']);

			$this->log($user, 'debug');

			if (!empty($user)) {
				$userId = $user['User']['id'];
				$userPassword = $user['User']['password'];
				$expires = round(microtime(true) + (48 * 3600)); // this request will expire after 2 days

				$requestHash = $this->generateResetRequest($userId, $userPassword, $expires);
				$domain = Configure::read('domain');
				$resetUrl = "https://{$domain}/users/reset_password/{$userId}/{$expires}/{$requestHash}";

				$userData['email'] = $user['User']['email'];
				$userData['name'] = $user['User']['firstname'];
				$userData['username'] = $user['User']['username'];

				if ($this->emailResetRequest($userData, $resetUrl)) {
					$this->Session->setFlash(__('The instructions to reset your password have been emailed to you', true), 'flash_success');
				} else {
					$this->Session->setFlash(__('We could not process your password reset request. Please try again', true), 'flash_failure');
				}
			} else {
				$this->Session->setFlash(__('No user found with that username or email address', true), 'flash_failure');
			}
		}

		$this->set(compact('title_for_layout'));
	}

	public function reset_password($userId = null, $time = null, $hash = null) {
		if (!$userId || !$time || !$hash) {
			$this->Session->setFlash(__('We could not authenticate your request for a password reset', true), 'flash_failure');
			$this->redirect(array('action' => 'forgot_password'));
		}

		$user = $this->User->findById($userId);
		if (!empty($user)) {
			$password = $user['User']['password'];
			$now = round(microtime(true));

			if ($now < $time) {
				if ($this->authenticateResetRequest($password, $this->params['pass'])) {
					if (!empty($this->data)) {
						if ($this->User->save($this->data)) {
							$this->Session->setFlash(__('Your password has been reset. Please login with your new password', true), 'flash_success');
							$this->redirect(array('action' => 'login'));
						} else {
							$this->Session->setFlash(__('Your password could not be reset. Please try again', true), 'flash_failure');
						}
					}
				} else {
					$this->Session->setFlash(__('Invalid password reset request. Please try again', true), 'flash_failure');
					$this->redirect(array('action' => 'forgot_password'));
				}
			} else {
				$this->Session->setFlash(__('Your password reset request has expired. Please send a new request', true), 'flash_failure');
				$this->redirect(array('action' => 'forgot_password'));
			}
		}
	}

	/**
	 * generateResetRequest
	 *
	 * param int $userId The ID of the user that requested the password reset
	 * param string $oldPassword The current password of the user that requested the password reset
	 * param float $expireTime The expiration time of the request (in milliseconds)
	 * return string
	 */
	private function generateResetRequest($userId, $oldPassword, $expireTime) {
		$oldPassword = Security::hash($oldPassword);
		return Security::hash($oldPassword . $expireTime . $userId, null, true);
	}

	/**
	 * emailResetRequest
	 *
	 * param array $userData An array containing keys for the users email and the users name
	 * param string $resetUrl The reset url generated by generateResetRequest
	 * return boolean
	 */
	private function emailResetRequest($userData, $resetUrl) {
		$companyName = Configure::read('Company.name');
		$systemEmail = Configure::read('System.email');

		$this->Email->to = $userData['email'];
		$this->Email->subject = 'Password Reset Request - ' . $companyName;
		$this->Email->from = "$companyName <$systemEmail>";
		$this->Email->template = 'forgot_password';
		$this->Email->sendAs = 'both';

		$this->set(compact('userData', 'resetUrl'));

		if ($this->Email->send()) {
			return true;
		}

		return false;
	}

	/**
	 * authenticateResetRequest
	 *
	 * param string $oldPassword The users current password, to use in building the reset request
	 * param array $params An array containing the users ID, the users current password, and the request Expiration
	 * return boolean
	 */
	private function authenticateResetRequest($oldPassword, $params = array()) {
		$userId = $params[0];
		$expire = $params[1];
		$hash = $params[2];

		return $this->generateResetRequest($userId, $oldPassword, $expire) === $hash;
	}
}

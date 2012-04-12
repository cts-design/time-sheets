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
		    if($this->params['action'] == 'admin_login' || $this->params['action'] == 'kiosk_self_sign_login') {
				$this->User->Behaviors->disable('Disableable');	
				$user = $this->User->find('first', array('conditions' => array(
					'username' => $this->data['User']['username'],
					'password' => Security::hash($this->data['User']['password'], null, true))));			
				if($user['User']['disabled'] == 1) {
				    $this->Session->setFlash(__('This account has been disabled.', true), 'flash_failure');
				    $this->redirect($this->referer());
				}
	    	}
		}
		$this->Auth->allowedActions = array(
			'kiosk_mini_registration',
			'admin_password_reset',
			'admin_login',
			'admin_logout',
			'kiosk_self_sign_login',
			'login',
			'registration',
			'logout',
			'kiosk_auto_logout');
		if($this->Auth->user('role_id') > 1) {
		    $this->Auth->allow(
			    'admin_auto_complete_customer',
			    'admin_auto_complete_ssn_ajax',
			    'admin_get_customers_by_first_and_last_name',
			    'admin_get_customers_by_ssn',
			    'admin_get_all_admins'
			);
		}			
		if(!empty($this->data)) {
			if(isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
				return;	
			}
			elseif(isset($this->data['User']['login_type'])) {	
				$this->User->setValidation('customerLogin');
				$this->User->set($this->data);
				if($this->User->validates()) {
				    $count = $this->User->find('count', array(
						'conditions' => array(
						    'User.username' => $this->data['User']['username'],
						    'and' => array(
							'User.password' => $this->Auth->password($this->data['User']['password'])))));		
				    if($count === 0 && isset($this->data['User']['login_type'])) {
						if($this->data['User']['login_type'] == 'kiosk') {
						    $this->redirect(array('action' => 'mini_registration',
							$this->data['User']['username'], 'kiosk' => true));
						}
						elseif($this->data['User']['login_type'] == 'website') {
						    $this->redirect(array('action' => 'registration', 'regular',
							$this->data['User']['username'], 'kiosk' => false));					
						}
						elseif($this->data['User']['login_type'] == 'child_website') {
							$this->redirect(array('action' => 'registration', 'child',
							$this->data['User']['username'], 'kiosk' => false));
						}		    
				    }
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

        $named = $this->params['named'];
        if (!empty($this->params['form'])) {
            $submittedValues = $this->params['form'];
        }

        if (empty($submittedValues)) {
            $submittedValues['search_by1'] = (isset($named['search_by1'])) ? $named['search_by1'] : '';
            $submittedValues['search_scope1'] = (isset($named['search_scope1'])) ? $named['search_scope1'] : '';
            $submittedValues['search_term1'] = (isset($named['search_term1'])) ? $named['search_term1'] : '';
            $submittedValues['search_by2'] = (isset($named['search_by2'])) ? $named['search_by2'] : '';
            $submittedValues['search_scope2'] = (isset($named['search_scope2'])) ? $named['search_scope2'] : '';
            $submittedValues['search_term2'] = (isset($named['search_term2'])) ? $named['search_term2'] : '';
        }

        // set up the default paginate options
        $conditions = array('User.role_id' => 1);
        $limit = Configure::read('Pagination.customer.limit');
        $order = array('User.lastname' => 'ASC');

        if (!empty($submittedValues) && $submittedValues['search_term1'] !== '') {
            switch ($submittedValues['search_scope1']) {
                case 'containing':
                    if ($submittedValues['search_by1'] === 'last4') {
                        $conditionScope = 'RIGHT (User.ssn , 4) LIKE';
                    } else if ($submittedValues['search_by1'] === 'fullssn') {
                        $conditionScope = 'User.ssn LIKE';
                    } else {
                        $conditionScope = $submittedValues['search_by1'] . ' LIKE';
                    }

                    $conditionValue = '%' . $submittedValues['search_term1'] . '%';
                    break;
                case 'matching exactly':
                    if ($submittedValues['search_by1'] === 'last4') {
                        $conditionScope = 'RIGHT (User.ssn , 4)';
                    } else if ($submittedValues['search_by1'] === 'fullssn') {
                        $conditionScope = 'User.ssn';
                    } else {
                        $conditionScope = $submittedValues['search_by1'];
                    }

                    $conditionValue = $submittedValues['search_term1'];
                    break;
            }

            $conditions1 = array($conditionScope => $conditionValue);
            $conditions = array_merge($conditions, $conditions1);

            if (isset($submittedValues['search_by2']) && $submittedValues['search_by2'] !== '' && $submittedValues['search_term2'] !== '') {
                switch ($submittedValues['search_scope2']) {
                    case 'containing':
                        if ($submittedValues['search_by2'] === 'last4') {
                            $conditionScope2 = 'RIGHT (User.ssn , 4) LIKE';
                        } else if ($submittedValues['search_by2'] === 'fullssn') {
                            $conditionScope2 = 'User.ssn LIKE';
                        } else {
                            $conditionScope2 = $submittedValues['search_by2'] . ' LIKE';
                        }

                        $conditionValue2 = '%' . $submittedValues['search_term2'] . '%';
                        break;
                    case 'matching exactly':
                        if ($submittedValues['search_by2'] === 'last4') {
                            $conditionScope2 = 'RIGHT (User.ssn , 4)';
                        } else if ($submittedValues['search_by2'] === 'fullssn') {
                            $conditionScope2 = 'User.ssn';
                        } else {
                            $conditionScope2 = $submittedValues['search_by2'];
                        }

                        $conditionValue2 = $submittedValues['search_term2'];
                        break;
                }

                $conditions2 = array($conditionScope2 => $conditionValue2);
                $conditions = array_merge($conditions, $conditions2);
            }

            $this->paginate = array(
                'conditions' => $conditions,
                'limit'      => $limit,
                'order'      => $order
            );

            $this->set($submittedValues);
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
			$this->User->editValidation('customer');
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
			$this->User->editValidation('customer');
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

    function edit($id=null) {
    	$this->Auth->user('id');	
    	$this->User->Behaviors->disable('Disableable');
		$this->set('title_for_layout', 'Edit Profile');
		if (!$id && empty($this->data) || $id != $this->Auth->user('id')) {
		    $this->Session->setFlash(__('Invalid profile', true), 'flash_failure');
		    $this->redirect('/');
		}
		if (!empty($this->data)) {
		    if ($this->User->save($this->data)) {
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

    function kiosk_self_sign_login() {
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
				
		if (isset($this->data['User']['login_type']) && $this->data['User']['login_type'] == 'kiosk') {
		    if ($this->Auth->user()) {
		    	$user = $this->Auth->user();
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
				$this->redirect(array('controller' => 'kiosks', 'action' => 'self_sign_confirm'));
		    }
		}
		$this->set('kioskHasSurvey', (empty($kiosk['KioskSurvey'])) ? false : true);
		$this->set('kiosk', $kiosk);
		$this->set('title_for_layout', 'Self Sign Kiosk');
		$this->layout = 'kiosk';
    }
	
	function login($type=null) {		
		$this->User->setValidation('customerLogin');
		if(isset($this->params['pass'][0], $this->params['pass'][1]) && $this->params['pass'][0] === 'programs') {
            $this->loadModel('Program');
            $program = $this->Program->findById($this->params['pass'][1]);
            if($program) {
                $this->Session->write('Auth.redirect', '/programs/' . $program['Program']['type'] . '/' . $this->params['pass'][1]);
                $this->set('title_for_layout', $program['Program']['name'] . ' Login');	
                if($program['Program']['atlas_registration_type'] === 'child') {
                    $type = 'child';
                }
            }
		}
		if($this->Auth->user()){
            $role = $this->User->Role->find('first', array(
                'fields' => array('Role.name'),
                'conditions' => array('Role.id' => $this->Auth->user('role_id'))
            ));
            $this->Session->write('Auth.User.role_name', $role['Role']['name']);

            if (preg_match('/auditor/i', $this->Auth->user('role_name'))) {
                $this->Transaction->createUserTransaction('Auditor', null, null, 'Logged into auditor dashboard');
                $this->redirect(array('action' => 'dashboard', 'auditor' => true));
            } else {
                $this->Transaction->createUserTransaction('Website', 
                    null, null, 'Logged in using website.' );
                if($this->Auth->user('email') == null || preg_match('(none|nobody|noreply)', $this->Auth->user('email'))) {
                    $this->Session->setFlash(__('Please complete your profile to continue.', true), 'flash_success');   
                    $this->redirect(array('controller' => 'users', 'action' => 'edit', $this->Auth->user('id')));
                }
                if($this->Session->read('Auth.redirect') != '') {   
                    $this->redirect($this->Session->read('Auth.redirect'));
                }
                else {
                    $this->redirect('/');
                }   
            }
		}
		if(isset($type) && $type == 'child' || 
			isset($this->data['User']['login_type']) && $this->data['User']['login_type'] == 'child_website') {
                if($program) {
                    $this->set('title_for_layout', $program['Program']['name'] . ' Child Login');
                }
                else {
                    $this->set('title_for_layout', 'Child Login');	
                }
                $this->render('child_login');
		}
        if(isset($type) && $type == 'auditor' || 
            isset($this->data['User']['login_type']) && $this->data['User']['login_type'] == 'auditor') {
            $this->set('title_for_layout', 'Auditor Login');  
            $this->render('auditor_login');
        }
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
		    $this->redirect(array('action' => 'self_sign_login', 'kiosk' => true));
		}
        if (preg_match('/auditor/i', $this->Session->read('Auth.User.role_name'))) {
            $this->Session->destroy();
            $this->redirect('/');
        }
		if ($this->Auth->user('role_id') != 1) {
		    $this->redirect(array('action' => 'login', 'admin' => true));
		}
    }
	
    function registration($type=null, $lastname=null) {
		if (!empty($this->data)) {
			$this->User->Behaviors->disable('Disableable');	
		    $this->User->create();
			if(Configure::read('Registration.ssn') == 'last4') {
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
				$last4 = substr($this->data['User']['ssn'], -4);
				$this->data['User']['password'] = Security::hash($last4, null, true);
				$this->data['User']['username'] = $this->data['User']['lastname'];
				$this->Auth->login($this->data);
				$this->Transaction->createUserTransaction('Web Site',
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
		    }
		}
		if (empty($this->data)) {
			if($lastname) {
				$this->data['User']['lastname'] = $lastname;
			}    
		}
		$title_for_layout = 'Registration';
		$states = $this->states;
		$this->set(compact('title_for_layout', 'states'));
		if(isset($type) && $type == 'child' || 
			isset($this->data['User']['registration']) && $this->data['User']['registration'] == 'child_website') {
			$this->render('child_registration');
		}		
		
    }

    function kiosk_auto_logout() {
		$this->Session->destroy();
		$this->Session->setFlash(__('You have been logged out due to inactivity.', true), 'flash_failure');
		$this->redirect(array('action' => 'self_sign_login'));   
    }

    function kiosk_mini_registration($lastname=null) {
		if (!empty($this->data)) {
			$this->User->Behaviors->disable('Disableable');
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
				$last4 = substr($this->data['User']['ssn'], -4);
				$this->data['User']['password'] = Security::hash($last4, null, true);
				$this->data['User']['username'] = $this->data['User']['lastname'];
				$this->Auth->login($this->data);
				$this->Transaction->createUserTransaction('Self Sign',
					$userId, $this->User->SelfSignLog->Kiosk->getKioskLocationId(), 'User self registered using a kiosk.');
				$this->Session->setFlash(__('Your account has been created.', true), 'flash_success');
				$this->redirect(array('controller' => 'kiosks', 'action' => 'self_sign_confirm'));
		    } 
		    else {
				$this->Session->setFlash(__('The information could not be saved. Please, try again.', true), 'flash_failure');
		    }
		}
		if (empty($this->data)) {
		    $this->data['User']['lastname'] = $lastname;
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
					$this->data['User']['search_by'] . ' LIKE' => '%' . $this->data['User']['search_term'] . '%'), 
				'limit' => Configure::read('Pagination.admin.limit'), 'order' => array('User.lastname' => 'asc'));
			if($this->Auth->user('role_id') == 2) {
				$this->paginate['conditions']['User.role_id >'] = 1;
			}			
			$data = array('users' => $this->paginate('User'), 'perms' => $filteredPerms, 'title_for_layout' => 'Administrators');
			$this->set($data);
			$this->passedArgs['search_by'] = $this->data['User']['search_by'];
			$this->passedArgs['search_term'] = $this->data['User']['search_term'];
		}
		elseif(isset($this->passedArgs['search_term'], $this->passedArgs['search_by'])) {
			$this->paginate = array(
				'conditions' => array(
					'User.role_id >' => 2, 
					$this->passedArgs['search_by'] . ' LIKE' => '%' . $this->passedArgs['search_term'] . '%'), 
				'limit' => Configure::read('Pagination.admin.limit'), 'order' => array('User.lastname' => 'asc'));
			if($this->Auth->user('role_id') == 2) {
				$this->paginate['conditions']['User.role_id >'] = 1;
			}			
			$data = array('users' => $this->paginate('User'), 'perms' => $filteredPerms, 'title_for_layout' => 'Administrators');
			$this->set($data);
		}
		else {
			$this->paginate = array(
				'conditions' => array(
					'User.role_id >' => 2
				), 
				'limit' => Configure::read('Pagination.admin.limit'), 'order' => array('User.lastname' => 'asc'));
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

		$title_for_layout = 'Administration Dashboard';
		$this->set(compact('title_for_layout'));
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
		$this->User->recursive = -1;
		$admins = $this->User->find('all', array(
			'conditions' => array('User.role_id > 2'), 
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

}

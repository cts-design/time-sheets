<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
 
class UsersController extends AppController {

    var $name = 'Users';
    var $components = array('Email');

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
		    if($this->params['action'] == 'admin_login' || $this->params['action'] == 'self_sign_login') {
				$user = $this->User->find('first', array('conditions' => array(
					'username' => $this->data['User']['username'],
					'password' => Security::hash($this->data['User']['password'], null, true))));			
				if($user['User']['status'] == 1 || $user['User']['deleted'] == 1) {
				    $this->Session->setFlash(__('Account is inactive or has been deleted', true), 'flash_failure');
				    $this->redirect($this->referer());
				}
	    	}
		}
		$this->Auth->allow('kiosk_mini_registration', 'add', 'admin_password_reset', 'build_acl', 'admin_login', 'admin_logout', 'kiosk_self_sign_login', 'login', 'registration');

		if(isset($this->data['User']['username'], $this->data['User']['password'],$this->data['User']['login_type'] ) &&
			$this->data['User']['username'] != '' && $this->data['User']['password'] != '') {
		    $count = $this->User->find('count', array(
				'conditions' => array(
				    'User.username' => $this->data['User']['username'],
				    'and' => array(
					'User.password' => $this->Auth->password($this->data['User']['password'])))));
		    if($count === 0) {
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
				else {
					$this->redirect(array('action' => 'add'));
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

    function admin_index() {
		$this->set('title_for_layout', 'Customers');
		$this->User->recursive = 0;
		if(! empty($this->data) && $this->data['User']['search_term'] != '' ) {
		    $this->paginate = array(
			'conditions' =>  array(
			    'User.role_id' => 1,
			    'User.status !=' => 1,
			    'User.deleted !=' => 1,
			    $this->data['User']['search_by'].' LIKE' => '%'.$this->data['User']['search_term'].'%' ),
			'limit' => Configure::read('Pagination.customer.limit'),
			'order' => array('User.lastname' => 'asc')
		    );
			$this->set('users', $this->paginate('User'));
			$this->passedArgs['search_by'] = $this->data['User']['search_by'];
			$this->passedArgs['search_term'] = $this->data['User']['search_term'];
		}
		elseif(isset($this->passedArgs['search_term'], $this->passedArgs['search_by'])) {	    
		    $this->paginate = array(
			'conditions' => array(
			    'User.role_id' => 1,
			    'User.status !=' => 1,
			    'User.deleted !=' => 1,
			    $this->passedArgs['search_by'].' LIKE' => '%'.$this->passedArgs['search_term'].'%' ),
			'limit' => Configure::read('Pagination.customer.limit'),
			'order' => array('User.lastname' => 'asc')
		    );
		    $this->set('users', $this->paginate('User'));	
		}
		else {
		    $this->paginate = array(
			'conditions' => array(
			    'User.role_id' => 1,
			    'User.status !=' => 1,
			    'User.deleted !=' => 1),
			 'limit' => Configure::read('Pagination.customer.limit'),
			 'order' => array('User.lastname' => 'asc')
		    );
		    $this->set('users', $this->paginate('User'));
		}	
    }

    function admin_add() {
		$this->set('title_for_layout', 'Add Customer');
		if (!empty($this->data)) {
		    $this->User->create();
		    if ($this->User->save($this->data)) {
				$this->Transaction->createUserTransaction('Customer', 
					null, null, 'Added customer '. $this->data['User']['firstname'] . 
					' ' . $this->data['User']['lastname'] . ' - ' . substr($this->data['User']['ssn'],'5'));
				$this->Session->setFlash(__('The customer has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
		    }
		    else {
				$this->Session->setFlash(__('The customer could not be saved. Please, try again.', true), 'flash_failure');
		    }
		}
		$data = array(
		    'states' => $this->states,
		    'genders' => $this->genders,
		    'statuses' => $this->statuses
		    );
		$this->set($data);
    }

    function admin_edit($id = null) {
		$this->set('title_for_layout', 'Edit Customer');
		if (!$id && empty($this->data)) {
		    $this->Session->setFlash(__('Invalid customer', true), 'flash_failure');
		    $this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
		    if ($this->User->save($this->data)) {
				$this->Transaction->createUserTransaction('Customer',
					null, null, 'Edited customer '. $this->data['User']['firstname'] . 
					' ' . $this->data['User']['lastname'] . ' - ' . substr($this->data['User']['ssn'],'5'));
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

    function admin_delete($id = null) {
		if (!$id) {
		    $this->Session->setFlash(__('Invalid id for customer', true), 'flash_failure');
		    $this->redirect(array('action' => 'index'));
		}
		$user = $this->User->read(null, $id);
		if($user['User']['role_id'] != 1) {
		    $this->Session->setFlash(__('Invalid customer', true), 'flash_failure');
		    $this->redirect($this->referer());
		}
		if ($this->User->delete($id)) {
			$this->Transaction->createUserTransaction('Customer',
				null, null, 'Deleted customer '. $this->data['User']['firstname'] . 
				' ' . $this->data['User']['lastname'] . ' - ' . substr($this->data['User']['ssn'],'5'));
		    $this->Session->setFlash(__('Customer deleted', true), 'flash_success');
		    $this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Customer was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
    }

    function kiosk_self_sign_login() {
		if (isset($this->data['User']['login_type']) && $this->data['User']['login_type'] == 'kiosk') {
		    if ($this->Auth->user()) {
			$this->Transaction->createUserTransaction('Self Sign', 
				null, $this->User->SelfSignLog->Kiosk->getKioskLocationId(), 'Logged in at self sign kiosk' );
			$this->redirect(array('controller' => 'kiosks', 'action' => 'self_sign_confirm'));
		    }
		}
		$this->set('title_for_layout', 'Self Sign Kiosk');
		$this->layout = 'kiosk';
    }
	
	function login($type=null) {
		if($this->Auth->user()){
			if($this->Session->read('Auth.redirect') != '') {
				$this->redirect($this->Session->read('Auth.redirect'));
			}
			else {
				$this->redirect('/');
			}
		}
		if(isset($type) && $type == 'child' || 
			isset($this->data['User']['login_type']) && $this->data['User']['login_type'] == 'child_website') {
			$this->render('child_login');
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
		if ($this->Auth->user('role_id') != 1) {
		    $this->redirect(array('action' => 'login', 'admin' => true));
		}
    }
	
    function registration($type=null, $lastname=null) {
		if (!empty($this->data)) {	
		    $this->User->create();
		    if ($this->User->save($this->data)) {
				$userId = $this->User->getInsertId();
				$last4 = substr($this->data['User']['ssn'], -4);
				$this->data['User']['password'] = Security::hash($last4, null, true);
				$this->data['User']['username'] = $this->data['User']['lastname'];
				$this->Auth->login($this->data);
				$this->Transaction->createUserTransaction('Web Site',
					$userId, $this->User->SelfSignLog->Kiosk->getKioskLocationId(), 'User self registered using the website.');
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
		$this->set('title_for_layout', 'Registration');
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
		$this->set('title_for_layout', 'Self Sign Kiosk');
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

	function admin_index_admin() {
		$this->User->recursive = 0;
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
					'User.status !=' => 1, 
					'User.deleted !=' => 1, 
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
					'User.status !=' => 1, 
					'User.deleted !=' => 1, 
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
					'User.role_id >' => 2, 
					'User.status !=' => 1, 
					'User.deleted !=' => 1), 
				'limit' => Configure::read('Pagination.admin.limit'), 'order' => array('User.lastname' => 'asc'));
			if($this->Auth->user('role_id') == 2) {
				$this->paginate['conditions']['User.role_id >'] = 1;
			}			
			$data = array('users' => $this->paginate('User'), 'perms' => $filteredPerms, 'title_for_layout' => 'Administrators');
			$this->set($data);
		}
	}

    function admin_add_admin() {
		$this->set('title_for_layout', 'Add Administrator');
		if (!empty($this->data)) {
		    $this->User->create();
            if ($this->data['User']['email'] != Configure::read('PrePop.email')) {
                $userEmail = $this->data['User']['email'];
                $this->set(compact('userEmail'));
            }	
		    if ($this->User->save($this->data)) {
			$message = 'Welcome to the Atlas system.' . "\r\n\r\n";
			$message .= 'Your username is: ' . substr($this->data['User']['firstname'], 0, 1).$this->data['User']['lastname'] . "\r\n\r\n";
			$message .= 'Your password is: ' . $this->data['User']['pass'] . "\r\n\r\n";
			$message .= 'You can now login at ' . Configure::read('Admin.URL');
			$this->Email->from = Configure::read('System.email');
			$this->Email->to = $this->data['User']['firstname']." ".$this->data['User']['lastname']."<".$this->data['User']['email'].">";
			$this->Email->subject = 'Welcome to Atlas.';
			$this->Email->send($message);
			$this->Transaction->createUserTransaction('Administrator',
				null, null, 'Added administrator '. $this->data['User']['firstname'] . ' ' . $this->data['User']['lastname'] );
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
		else $conditions = array("NOT" => array(array('Role.id' => array(1,2))));
		
		$data = array(
		    'roles' => $this->User->Role->find('list', array(
				'conditions' => $conditions)),
		    	'locations' => $this->User->Location->find('list')
		);
		$this->set($data);
    }

    function admin_edit_admin($id = null) {
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
		    if ($this->User->save($this->data)) {
				$this->Transaction->createUserTransaction('Administrator', null, null,
					'Edited administrator '. $this->data['User']['firstname'] . ' ' . $this->data['User']['lastname']);
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
		else $conditions = array("NOT" => array(array('Role.id' => array(1,2))));		
		$data = array(
		    'roles' => $this->User->Role->find('list', array(
			'conditions' => $conditions)),
		    'locations' => $this->User->Location->find('list')
		);
		$this->set($data);
    }

    function admin_delete_admin($id = null) {
		if (!$id) {
		    $this->Session->setFlash(__('Invalid id for admin', true), 'flash_failure');
		    $this->redirect(array('action' => 'index_admin'));
		}
		if ($this->User->delete($id)) {
			$this->Transaction->createUserTransaction('Administrator', null, null,
				'Deleted administrator '. $this->data['User']['firstname'] . ' ' . $this->data['User']['lastname']);
		    $this->Session->setFlash(__('admin deleted', true), 'flash_success');
		    $this->redirect(array('action' => 'index_admin'));
		}
		else {
		    $this->Session->setFlash(__('Admin was not deleted', true), 'flash_failure');
		    $this->redirect(array('action' => 'index_admin'));
		}
    }

    function admin_dashboard() {
		$title_for_layout = 'Administration Dashboard';
		$this->set(compact('title_for_layout'));
    }

    function admin_password_reset() {
		$this->set('title_for_layout', 'Password Reset');
		if ($this->data['User']['email'] != '') {
		    $user = $this->User->find('first', array('conditions' => array ('User.email' => $this->data['User']['email'])));
		    $this->data['User']['id'] = $user['User']['id'];
		    $this->data['User']['role_id'] = $user['User']['role_id'];
		    if ($user['User']['email'] != '') {
			$tempPassword = rand(10000,100000);
			$this->data['User']['password'] = Security::hash($tempPassword, null, true);
			unset($this->data['User']['email']);
			if ($this->User->save($this->data, array('validate' => false))) {
			    // Fire off the E-Mail
			    $message = Configure::read('Admin.URL') . "\n\n" . 'Temp Password: ' . $tempPassword ;
			    $this->Email->from = Configure::read('System.email');
			    $this->Email->to = $user['User']['firstname']." ".$user['User']['lastname']."<".$user['User']['email'].">";
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
			if($this->params['form']['xaction'] == 'update')  {
				$postData = json_decode($this->params['form']['users'], true);
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
			if($this->params['form']['xaction'] == 'read') {
				$useDate = false;
				if(!empty($this->params['form']['from']) && !empty($this->params['form']['to'])) {
					$from = date('Y-m-d H:i:s', strtotime($this->params['form']['from'] . ' 00:00:01'));
					$to = date('Y-m-d H:i:s', strtotime($this->params['form']['to'] . '23:59:59'));
					$useDate = true;					
				}
				$this->User->recursive = -1;
				if($this->params['form']['searchType'] == 'ssn') {
					if($useDate){
						$conditions = array(
							'RIGHT (User.ssn , 4) LIKE' => '%'.$this->params['form']['search'].'%', 
							'User.role_id' => 1,
							'User.created BETWEEN ? AND ?' => array($from, $to)
							);							
					}
					else {
						$conditions = array(
							'User.ssn LIKE' => '%'.$this->params['form']['search'].'%', 
							'User.role_id' => 1
							);						
					}

				}
				if($this->params['form']['searchType'] == 'lastname') {
					if($useDate){
						$conditions = array(
							'User.lastname LIKE' => '%'.$this->params['form']['search'].'%', 
							'User.role_id' => 1,
							'User.created BETWEEN ? AND ?' => array($from, $to)
							);
					}
					else {
						$conditions = array(
							'User.lastname LIKE' => '%'.$this->params['form']['search'].'%', 
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
					Configure::read('Admin.URL') . '/users/edit/' . $this->params['form']['userId']. "\r\n";					
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

}
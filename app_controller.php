<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

class AppController extends Controller {
	
    var $helpers = array('Html', 'Form', 'Session', 'Js' => array('Jquery'), 'Time', 'Crumb', 'Nav');
    var $components = array('Session', 'RequestHandler', 'Auth', 'Acl', 'Cookie', 'Transaction', 'Security');
	var $genders = array(
		'male' => 'Male',
		'female' => 'Female');
	var $statuses = array(
		0 => 'Active',
		1 => 'Not Active'
		);
	var $states = array(
		'AL'=>"Alabama",
		'AK'=>"Alaska",
		'AZ'=>"Arizona",
		'AR'=>"Arkansas",
		'CA'=>"California",
		'CO'=>"Colorado",
		'CT'=>"Connecticut",
		'DE'=>"Delaware",
		'DC'=>"District Of Columbia",
		'FL'=>"Florida",
		'GA'=>"Georgia",
		'HI'=>"Hawaii",
		'ID'=>"Idaho",
		'IL'=>"Illinois",
		'IN'=>"Indiana",
		'IA'=>"Iowa",
		'KS'=>"Kansas",
		'KY'=>"Kentucky",
		'LA'=>"Louisiana",
		'ME'=>"Maine",
		'MD'=>"Maryland",
		'MA'=>"Massachusetts",
		'MI'=>"Michigan",
		'MN'=>"Minnesota",
		'MS'=>"Mississippi",
		'MO'=>"Missouri",
		'MT'=>"Montana",
		'NE'=>"Nebraska",
		'NV'=>"Nevada",
		'NH'=>"New Hampshire",
		'NJ'=>"New Jersey",
		'NM'=>"New Mexico",
		'NY'=>"New York",
		'NC'=>"North Carolina",
		'ND'=>"North Dakota",
		'OH'=>"Ohio",
		'OK'=>"Oklahoma",
		'OR'=>"Oregon",
		'PA'=>"Pennsylvania",
		'RI'=>"Rhode Island",
		'SC'=>"South Carolina",
		'SD'=>"South Dakota",
		'TN'=>"Tennessee",
		'TX'=>"Texas",
		'UT'=>"Utah",
		'VT'=>"Vermont",
		'VA'=>"Virginia",
		'WA'=>"Washington",
		'WV'=>"West Virginia",
		'WI'=>"Wisconsin",
		'WY'=>"Wyoming");
		
    function beforeFilter() {
		parent::beforeFilter();
		
		if (!defined('CAKE_UNIT_TEST')) {
			define('CAKE_UNIT_TEST', false);
		}
		
		if ($this->RequestHandler->isAjax()) {
			Configure::write('debug', 0);
		}
		
		$this->Auth->autoRedirect = false;
		$this->Auth->authorize = 'actions';
		$this->Auth->actionPath = 'controllers/';
		//@TODO possibly change this to check specfic permissions 
		if($this->Auth->user('role_id') > 1) {
		    $this->Auth->allow(
			    'display',
			    'admin_auto_complete_first_ajax',
			    'admin_auto_complete_last_ajax',
			    'admin_auto_complete_ssn_ajax'
			    );
		}
		else {
		    $this->Auth->allow('display');
		}
		// Disable caching of pages that require authentication
		if($this->Auth->user()) {
		    $this->disableCache();
		}
		if(isset($this->params['prefix']) && ($this->params['prefix'] == 'admin' || $this->params['prefix'] == 'kiosk')){	
			$this->Security->blackHoleCallback = 'forceSSL';
		    $this->Security->requireSecure();
		}	
		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
			$this->Security->validatePost = false;
		    $this->layout = 'admin';
		    if($this->Auth->user('role_id') == 1 ) {
				$this->Session->destroy();
				$this->Session->setFlash(__('You are not authorized to access that location', true), 'flash_failure');
				$this->redirect(array('controller' => 'users', 'action' => 'self_sign_login', 'admin' => false));
		    }
		    $this->Auth->loginAction = array('admin' => true, 'controller' => 'users', 'action' => 'login');
	
		} 
		else {
		   $this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'self_sign_login');
		}
		$this->Auth->flashElement = 'flash_auth';
	        $this->loadModel('ModuleAccessControl');
	        if(isset($this->params['admin']) && $this->params['admin'] == 1 && $this->Auth->user()) {
	            if($this->ModuleAccessControl->checkPermissions($this->params['controller'])) {
	                $this->Session->setFlash(__('This module needs to be activated, please contact CTS', true), 'flash_failure');
	                $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => true));
	            }
	        }	
    }

	function forceSSL() {
		if (defined('CAKE_UNIT_TEST') && CAKE_UNIT_TEST === false) {
			$this->redirect('https://' . env('SERVER_NAME') . $this->here);
		}
	}

	function admin_auto_complete_first_ajax() {
		if($this->RequestHandler->isAjax()) {
			Configure::write('debug', 0);
			$this->loadModel('User');
			$query = $this->User->find('all', array('conditions' => array('User.role_id' => 1, 'User.firstname LIKE' => '%' . $this->params['url']['term'] . '%')));
			$this->_setAutoCompleteOptions($query);
			$this->render('/elements/app_controller/auto_complete_ajax');
		}
	}

	function admin_auto_complete_last_ajax() {
		if($this->RequestHandler->isAjax()) {
			Configure::write('debug', 0);
			$this->loadModel('User');
			$query = $this->User->find('all', array('conditions' => array('User.role_id' => 1, 'User.lastname LIKE' => '%' . $this->params['url']['term'] . '%')));
			$this->_setAutoCompleteOptions($query);
			$this->render('/elements/app_controller/auto_complete_ajax');
		}
	}

	function admin_auto_complete_ssn_ajax() {
		if($this->RequestHandler->isAjax()) {
			Configure::write('debug', 0);
			$this->loadModel('User');
			$query = $this->User->find('all', array('conditions' => array('User.role_id' => 1, 'User.ssn LIKE' => '%' . $this->params['url']['term'] . '%')));

			$this->_setAutoCompleteOptions($query);
			$this->render('/elements/app_controller/auto_complete_ajax');
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
	
}
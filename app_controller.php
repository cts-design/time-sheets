<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

class AppController extends Controller {
	
    var $helpers = array('Html', 'Form', 'Session', 'Js' => array('Jquery'), 'Time', 'Crumb', 'Nav');
    var $components = array('DebugKit.Toolbar', 'Session', 'RequestHandler', 'Auth', 'Acl', 'Cookie', 'Transaction', 'Security');
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
		    //$this->disableCache();
		}
		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
		    $this->layout = 'admin';
		   	$this->Security->blackHoleCallback = 'forceSSL';
		    $this->Security->requireSecure();
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
		$this->redirect('https://' . env('SERVER_NAME') . $this->here);
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

    // @TODO remove before production 
	function build_acl() {
		if(!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();

		$aco = &$this->Acl->Aco;
		$root = $aco->node('controllers');
		if(!$root) {
			$aco->create( array('parent_id' => null, 'model' => null, 'alias' => 'controllers'));
			$root = $aco->save();
			$root['Aco']['id'] = $aco->id;
			$log[] = 'Created Aco node for controllers';
		}
		else {
			$root = $root[0];
		}

		App::import('Core', 'File');
		$Controllers = Configure::listObjects('controller');
		$appIndex = array_search('App', $Controllers);
		if($appIndex !== false) {
			unset($Controllers[$appIndex]);
		}
		$baseMethods = get_class_methods('Controller');
		$baseMethods[] = 'build_acl';

		$Plugins = $this->_getPluginControllerNames();
		$Controllers = array_merge($Controllers, $Plugins);

		// look at each controller in app/controllers
		foreach($Controllers as $ctrlName) {
			$methods = $this->_getClassMethods($this->_getPluginControllerPath($ctrlName));

			// Do all Plugins First
			if($this->_isPlugin($ctrlName)) {
				$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
				if(!$pluginNode) {
					$aco->create( array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginName($ctrlName)));
					$pluginNode = $aco->save();
					$pluginNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginName($ctrlName) . ' Plugin';
				}
			}
			// find / make controller node
			$controllerNode = $aco->node('controllers/' . $ctrlName);
			if(!$controllerNode) {
				if($this->_isPlugin($ctrlName)) {
					$pluginNode = $aco->node('controllers/' . $this->_getPluginName($ctrlName));
					$aco->create( array('parent_id' => $pluginNode['0']['Aco']['id'], 'model' => null, 'alias' => $this->_getPluginControllerName($ctrlName)));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $this->_getPluginControllerName($ctrlName) . ' ' . $this->_getPluginName($ctrlName) . ' Plugin Controller';
				}
				else {
					$aco->create( array('parent_id' => $root['Aco']['id'], 'model' => null, 'alias' => $ctrlName));
					$controllerNode = $aco->save();
					$controllerNode['Aco']['id'] = $aco->id;
					$log[] = 'Created Aco node for ' . $ctrlName;
				}
			}
			else {
				$controllerNode = $controllerNode[0];
			}

			//clean the methods. to remove those in Controller and private actions.
			foreach($methods as $k => $method) {
				if(strpos($method, '_', 0) === 0) {
					unset($methods[$k]);
					continue ;
				}
				if(in_array($method, $baseMethods)) {
					unset($methods[$k]);
					continue ;
				}
				$methodNode = $aco->node('controllers/' . $ctrlName . '/' . $method);
				if(!$methodNode) {
					$aco->create( array('parent_id' => $controllerNode['Aco']['id'], 'model' => null, 'alias' => $method));
					$methodNode = $aco->save();
					$log[] = 'Created Aco node for ' . $method;
				}
			}
		}
		if(count($log) > 0) {
			debug($log);
		}
	}

	function _getClassMethods($ctrlName =null) {
		App::import('Controller', $ctrlName);
		if(strlen(strstr($ctrlName, '.')) > 0) {
			// plugin's controller
			$num = strpos($ctrlName, '.');
			$ctrlName = substr($ctrlName, $num + 1);
		}
		$ctrlclass = $ctrlName . 'Controller';
		$methods = get_class_methods($ctrlclass);

		// Add scaffold defaults if scaffolds are being used
		$properties = get_class_vars($ctrlclass);
		if(array_key_exists('scaffold', $properties)) {
			if($properties['scaffold'] == 'admin') {
				$methods = array_merge($methods, array('admin_add', 'admin_edit', 'admin_index', 'admin_view', 'admin_delete'));
			}
			else {
				$methods = array_merge($methods, array('add', 'edit', 'index', 'view', 'delete'));
			}
		}
		return $methods;
	}

	function _isPlugin($ctrlName =null) {
		$arr = String::tokenize($ctrlName, '/');
		if(count($arr) > 1) {
			return true;
		}
		else {
			return false;
		}
	}

	function _getPluginControllerPath($ctrlName =null) {
		$arr = String::tokenize($ctrlName, '/');
		if(count($arr) == 2) {
			return $arr[0] . '.' . $arr[1];
		}
		else {
			return $arr[0];
		}
	}

	function _getPluginName($ctrlName =null) {
		$arr = String::tokenize($ctrlName, '/');
		if(count($arr) == 2) {
			return $arr[0];
		}
		else {
			return false;
		}
	}

	function _getPluginControllerName($ctrlName =null) {
		$arr = String::tokenize($ctrlName, '/');
		if(count($arr) == 2) {
			return $arr[1];
		}
		else {
			return false;
		}
	}

/**
 * Get the names of the plugin controllers ...
 *
 * This function will get an array of the plugin controller names, and
 * also makes sure the controllers are available for us to get the
 * method names by doing an App::import for each plugin controller.
 *
 * @return array of plugin names.
 *
 */
	function _getPluginControllerNames() {
		App::import('Core', 'File', 'Folder');
		$paths = Configure::getInstance();
		$folder =& new Folder();
		$folder->cd(APP . 'plugins');

		// Get the list of plugins
		$Plugins = $folder->read();
		$Plugins = $Plugins[0];
		$arr = array();

		// Loop through the plugins
		foreach($Plugins as $pluginName) {
			// Change directory to the plugin
			$didCD = $folder->cd(APP . 'plugins'. DS . $pluginName . DS . 'controllers');
			// Get a list of the files that have a file name that ends
			// with controller.php
			$files = $folder->findRecursive('.*_controller\.php');

			// Loop through the controllers we found in the plugins directory
			foreach($files as $fileName) {
				// Get the base file name
				$file = basename($fileName);

				// Get the controller name
				$file = Inflector::camelize(substr($file, 0, strlen($file)-strlen('_controller.php')));
				if (!preg_match('/^'. Inflector::humanize($pluginName). 'App/', $file)) {
					if (!App::import('Controller', $pluginName.'.'.$file)) {
						debug('Error importing '.$file.' for plugin '.$pluginName);
					} else {
						/// Now prepend the Plugin name ...
						// This is required to allow us to fetch the method names.
						$arr[] = Inflector::humanize($pluginName) . "/" . $file;
					}
				}
			}
		}
		return $arr;
	}

}
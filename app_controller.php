<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
App::import('vendor', 'DebugKit.FireCake');
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
		if(! defined('CAKEPHP_TEST_SUITE')) {
			define('CAKEPHP_TEST_SUITE', false);
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
		
		if($this->Auth->user()) {
			// Disable caching of pages that require authentication
		    $this->disableCache();
		}
		if(isset($this->params['prefix']) && ($this->params['prefix'] == 'admin' || $this->params['prefix'] == 'kiosk')){	
			$this->Security->blackHoleCallback = 'forceSSL';
		    $this->Security->requireSecure();
		}	
		if (isset($this->params['prefix']) && $this->params['prefix'] == 'admin') {
		    $this->layout = 'admin';
		    if($this->Auth->user('role_id') == 1 ) {
				$this->Session->destroy();
				$this->Session->setFlash(__('You are not authorized to access that location', true), 'flash_failure');
				$this->redirect(array('controller' => 'users', 'action' => 'login', 'admin' => false));
		    }
		    $this->Auth->loginAction = array('admin' => true, 'controller' => 'users', 'action' => 'login');
	
		} 
		elseif(isset($this->params['prefix']) && $this->params['prefix'] == 'kiosk') {
		   $this->Auth->loginAction = array('admin' => false, 'kiosk' => true, 'controller' => 'users', 'action' => 'self_sign_login');
		}
		elseif(isset($this->params['controller'], $this->params['pass'][1]) && $this->params['controller'] == 'programs' 
			&& $this->params['pass'][1] == 'child'){
			$this->Auth->authError = "You must login to access that location.";	
			$this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'login', 'child');
		}
		else {
			$this->Auth->authError = "You must login to access that location.";	
			$this->Auth->loginAction = array('admin' => false, 'controller' => 'users', 'action' => 'login');
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
		if(!CAKEPHP_TEST_SUITE) {
			$this->redirect('https://' . env('SERVER_NAME') . $this->here);
		}		
	}
	
	function isModuleEnabled($controller){
		$this->loadModel('ModuleAccessControl');
		$Controllers = Configure::listObjects('controller');
		if(in_array($controller, $Controllers)) {
		$module = $this->ModuleAccessControl->findByName($controller);
			if($module['ModuleAccessControl']['permission'] == 1) {
				return false;
			}
			return true;			
		}
		else return false;

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
	

/**
 * Handles automatic pagination of model records.
 *
 * @param mixed $object Model to paginate (e.g: model instance, or 'Model', or 'Model.InnerModel')
 * @param mixed $scope Conditions to use while paginating
 * @param array $whitelist List of allowed options for paging
 * @return array Model query results
 * @access public
 * @link http://book.cakephp.org/view/1232/Controller-Setup
 * 
 */
	function paginate($object = null, $scope = array(), $whitelist = array()) {
		if (is_array($object)) {
			$whitelist = $scope;
			$scope = $object;
			$object = null;
		}
		$assoc = null;
		if (is_string($object)) {
			$assoc = null;
			if (strpos($object, '.')  !== false) {
				list($object, $assoc) = pluginSplit($object);
			}			
			if ($assoc && isset($this->{$object}->{$assoc})) {
				$object =& $this->{$object}->{$assoc};
			} elseif (
				$assoc && isset($this->{$this->modelClass}) &&
				isset($this->{$this->modelClass}->{$assoc}
				
			)) {
				$object =& $this->{$this->modelClass}->{$assoc};
			} elseif (isset($this->{$object})) {
				$object =& $this->{$object};
				
			} elseif (
				isset($this->{$this->modelClass}) && isset($this->{$this->modelClass}->{$object}
			)) {
				$object =& $this->{$this->modelClass}->{$object};
			}
		} elseif (empty($object) || $object === null) {
			if (isset($this->{$this->modelClass})) {
				$object =& $this->{$this->modelClass};
			} else {
				$className = null;
				$name = $this->uses[0];
				if (strpos($this->uses[0], '.') !== false) {
					list($name, $className) = explode('.', $this->uses[0]);
				}
				if ($className) {
					$object =& $this->{$className};
				} else {
					$object =& $this->{$name};
				}
			}
		}

		if (!is_object($object)) {
			trigger_error(sprintf(
				__('Controller::paginate() - can\'t find model %1$s in controller %2$sController',
					true
				), $object, $this->name
			), E_USER_WARNING);
			return array();
		}
		$options = array_merge($this->params, $this->params['url'], $this->passedArgs);

		if (isset($this->paginate[$object->alias])) {
			$defaults = $this->paginate[$object->alias];
		} else {
			$defaults = $this->paginate;
		}

		if (isset($options['show'])) {
			$options['limit'] = $options['show'];
		}

		if (isset($options['sort'])) {
			$direction = null;
			if (isset($options['direction'])) {
				$direction = strtolower($options['direction']);
			}
			if ($direction != 'asc' && $direction != 'desc') {
				$direction = 'asc';
			}
			$options['order'] = array($options['sort'] => $direction);
		}

		if (!empty($options['order']) && is_array($options['order'])) {
			$alias = $object->alias ;
			$key = $field = key($options['order']);

			if (strpos($key, '.') !== false) {
				list($alias, $field) = explode('.', $key);
			}
			//Override to allow hyphens in the field name, this allows EXT-Js pagination to work
			if (strpos($key, '-') !== false) {
				list($alias, $field) = explode('-', $key);
			}
			$value = $options['order'][$key];
			unset($options['order'][$key]);

			if ($object->hasField($field)) {
				$options['order'][$alias . '.' . $field] = $value;
			} elseif ($object->hasField($field, true)) {
				$options['order'][$field] = $value;
			} elseif (isset($object->{$alias}) && $object->{$alias}->hasField($field)) {
				$options['order'][$alias . '.' . $field] = $value;
			}
		}
		$vars = array('fields', 'order', 'limit', 'page', 'recursive');
		$keys = array_keys($options);
		$count = count($keys);

		for ($i = 0; $i < $count; $i++) {
			if (!in_array($keys[$i], $vars, true)) {
				unset($options[$keys[$i]]);
			}
			if (empty($whitelist) && ($keys[$i] === 'fields' || $keys[$i] === 'recursive')) {
				unset($options[$keys[$i]]);
			} elseif (!empty($whitelist) && !in_array($keys[$i], $whitelist)) {
				unset($options[$keys[$i]]);
			}
		}
		$conditions = $fields = $order = $limit = $page = $recursive = null;

		if (!isset($defaults['conditions'])) {
			$defaults['conditions'] = array();
		}

		$type = 'all';

		if (isset($defaults[0])) {
			$type = $defaults[0];
			unset($defaults[0]);
		}

		$options = array_merge(array('page' => 1, 'limit' => 20), $defaults, $options);
		$options['limit'] = (int) $options['limit'];
		if (empty($options['limit']) || $options['limit'] < 1) {
			$options['limit'] = 1;
		}

		extract($options);

		if (is_array($scope) && !empty($scope)) {
			$conditions = array_merge($conditions, $scope);
		} elseif (is_string($scope)) {
			$conditions = array($conditions, $scope);
		}
		if ($recursive === null) {
			$recursive = $object->recursive;
		}

		$extra = array_diff_key($defaults, compact(
			'conditions', 'fields', 'order', 'limit', 'page', 'recursive'
		));
		if ($type !== 'all') {
			$extra['type'] = $type;
		}

		if (method_exists($object, 'paginateCount')) {
			$count = $object->paginateCount($conditions, $recursive, $extra);
		} else {
			$parameters = compact('conditions');
			if ($recursive != $object->recursive) {
				$parameters['recursive'] = $recursive;
			}
			$count = $object->find('count', array_merge($parameters, $extra));
		}
		$pageCount = intval(ceil($count / $limit));

		if ($page === 'last' || $page >= $pageCount) {
			$options['page'] = $page = $pageCount;
		} elseif (intval($page) < 1) {
			$options['page'] = $page = 1;
		}
		$page = $options['page'] = (integer)$page;

		if (method_exists($object, 'paginate')) {
			$results = $object->paginate(
				$conditions, $fields, $order, $limit, $page, $recursive, $extra
			);
		} else {
			$parameters = compact('conditions', 'fields', 'order', 'limit', 'page');
			if ($recursive != $object->recursive) {
				$parameters['recursive'] = $recursive;
			}
			$results = $object->find($type, array_merge($parameters, $extra));
		}
		$paging = array(
			'page'		=> $page,
			'current'	=> count($results),
			'count'		=> $count,
			'prevPage'	=> ($page > 1),
			'nextPage'	=> ($count > ($page * $limit)),
			'pageCount'	=> $pageCount,
			'defaults'	=> array_merge(array('limit' => 20, 'step' => 1), $defaults),
			'options'	=> $options
		);
		$this->params['paging'][$object->alias] = $paging;

		if (!in_array('Paginator', $this->helpers) && !array_key_exists('Paginator', $this->helpers)) {
			$this->helpers[] = 'Paginator';
		}
		return $results;
	}	
		
    // @TODO remove before production 
	function build_acl() {
		if(!Configure::read('debug')) {
			return $this->_stop();
		}
		$log = array();
		ini_set('max_execution_time', 300);
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
<?php 

App::import('Component', 'Auth');

class AtlasAuthComponent extends AuthComponent {

/**
 * The name of an optional view element to render when an Ajax request is made
 * and the user does not have permission to access that location.
 * there will be two variables avilable in the element you set.
 * the $url that was be being accessed and the $authError
 * @var string
 * @access public
*/
	public $ajaxError = null;

/**
 * Main execution method.  Handles redirecting of invalid users, and processing
 * of login form data.
 *
 * @param object $controller A reference to the instantiating controller object
 * @return boolean
 * @access public
 */
	function startup(&$controller) {
		$isErrorOrTests = (
			strtolower($controller->name) == 'cakeerror' ||
			(strtolower($controller->name) == 'tests' && Configure::read() > 0)
		);
		if ($isErrorOrTests) {
			return true;
		}

		$methods = array_flip($controller->methods);
		$action = strtolower($controller->params['action']);
		$isMissingAction = (
			$controller->scaffold === false &&
			!isset($methods[$action])
		);

		if ($isMissingAction) {
			return true;
		}

		if (!$this->__setDefaults()) {
			return false;
		}

		//if($controller->data['User']['password'] != '')
			$this->data = $controller->data = $this->hashPasswords($controller->data);
		$url = '';

		if (isset($controller->params['url']['url'])) {
			$url = $controller->params['url']['url'];
		}
		$url = Router::normalize($url);
		$loginAction = Router::normalize($this->loginAction);

		$allowedActions = array_map('strtolower', $this->allowedActions);
		$isAllowed = (
			$this->allowedActions == array('*') ||
			in_array($action, $allowedActions)
		);

		if ($loginAction != $url && $isAllowed) {
			return true;
		}

		if ($loginAction == $url) {
			$model =& $this->getModel();
			if (empty($controller->data) || !isset($controller->data[$model->alias])) {
				if (!$this->Session->check('Auth.redirect') && !$this->loginRedirect && env('HTTP_REFERER')) {
					$this->Session->write('Auth.redirect', $controller->referer(null, true));
				}
				return false;
			}

			$isValid = !empty($controller->data[$model->alias][$this->fields['username']]) &&
				!empty($controller->data[$model->alias][$this->fields['password']]);

			if ($isValid) {
				$username = $controller->data[$model->alias][$this->fields['username']];
				$password = $controller->data[$model->alias][$this->fields['password']];

				$data = array(
					$model->alias . '.' . $this->fields['username'] => $username,
					$model->alias . '.' . $this->fields['password'] => $password
				);

				if ($this->login($data)) {
					if ($this->autoRedirect) {
						$controller->redirect($this->redirect(), null, true);
					}
					return true;
				}
			}

			$this->Session->setFlash($this->loginError, $this->flashElement, array(), 'auth');
			$controller->data[$model->alias][$this->fields['password']] = null;
			return false;
		} else {
			$user = $this->user();
			if (!$user) {
				if (!$this->RequestHandler->isAjax()) {
					$this->Session->setFlash($this->authError, $this->flashElement, array(), 'auth');
					if (!empty($controller->params['url']) && count($controller->params['url']) >= 2) {
						$query = $controller->params['url'];
						unset($query['url'], $query['ext']);
						$url .= Router::queryString($query, array());
					}
					$this->Session->write('Auth.redirect', $url);
					$controller->redirect($loginAction);
					return false;
				} elseif (!empty($this->ajaxLogin)) {
					$controller->viewPath = 'elements';
					echo $controller->render($this->ajaxLogin, $this->RequestHandler->ajaxLayout);
					$this->_stop();
					return false;
				} else {
					$controller->redirect(null, 403);
				}
			}
		}

		if (!$this->authorize) {
			return true;
		}

		extract($this->__authType());
		switch ($type) {
			case 'controller':
				$this->object =& $controller;
			break;
			case 'crud':
			case 'actions':
				if (isset($controller->Acl)) {
					$this->Acl =& $controller->Acl;
				} else {
					trigger_error(__('Could not find AclComponent. Please include Acl in Controller::$components.', true), E_USER_WARNING);
				}
			break;
			case 'model':
				if (!isset($object)) {
					$hasModel = (
						isset($controller->{$controller->modelClass}) &&
						is_object($controller->{$controller->modelClass})
					);
					$isUses = (
						!empty($controller->uses) && isset($controller->{$controller->uses[0]}) &&
						is_object($controller->{$controller->uses[0]})
					);

					if ($hasModel) {
						$object = $controller->modelClass;
					} elseif ($isUses) {
						$object = $controller->uses[0];
					}
				}
				$type = array('model' => $object);
			break;
		}

		if ($this->isAuthorized($type, null, $user)) {
			return true;
		}
	
		if ($this->RequestHandler->isAjax() && !empty($this->ajaxError)) {
			if ($url) {
				$data['url'] = $url;
			}	
			$data['authError'] = $this->authError;
			$controller->set($data);
			$controller->viewPath = 'elements';
			echo $controller->render($this->ajaxError, $this->RequestHandler->ajaxLayout);
			$this->_stop();
		}
		else{
			$this->Session->setFlash($this->authError, $this->flashElement, array(), 'auth');
			$controller->redirect($controller->referer(), null, true);
		}
		return false;
	}
}	
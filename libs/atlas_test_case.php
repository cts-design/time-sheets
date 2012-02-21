<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
require_once APP.'config'.DS.'routes.php';
 
App::import('Vendor', 'DebugKit.FireCake');

App::import('Component', 'Acl');

Mock::generatePartial('AclComponent', 'MockAclComponent', array('check'));


class AtlasTestCase extends CakeTestCase {
	// since the list of fixtures needed grows exponentially
	// and to keep the code dry, we set our fixtures here
	var $fixtures = array(
            'aco',
            'aro',
            'alert',
            'aros_aco',
            'audit',
            'bar_code_definition',
            'chairman_report',
            'deleted_document',
            'document_filing_category',
            'document_queue_category',
            'document_transaction',
            'filed_document',
            'ftp_document_scanner',
            'i18n',
            'kiosk',
			'kiosk_button',
			'kiosk_survey',
			'kiosk_survey_response',
			'kiosk_survey_response_answer',
			'kiosk_survey_question',
			'kiosks_kiosk_survey',
            'location',
            'master_kiosk_button',
            'module_access_control',
            'navigation',
            'page',
            'press_release',
            'program',
            'program_registration',
            'program_response',
            'watched_filing_cat',
            'program_instruction',
            'program_response_doc',
            'program_paper_form',
            'program_email',
            'module_access_control',
            'program_field',
            'queued_document',
            'role',
            'self_scan_category',
            'self_sign_log',
            'self_sign_log_archive',
            'setting',
            'user',
            'user_transaction'
        );
		
	var $_componentsInitialized = false;
	
	var $testController = null;	
		
	function mockAcl($Controller) {
		if (isset($Controller->Acl)) {
            $Controller->Acl = new MockAclComponent();
            $Controller->Acl->enabled = true;
            $Controller->Acl->setReturnValue('check', true);
        }		
	}			
		
    
 
    function testAction($url = '', $options = array()) {
        if (is_null($this->testController)) {
            return parent::testAction($url, $options);
        }

        $Controller = $this->testController;
 
        // reset parameters
        ClassRegistry::flush();
        $Controller->passedArgs = array();
        $Controller->params = array();
        $Controller->url = null;
        $Controller->action = null;
        $Controller->viewVars = array();
		$keys = ClassRegistry::keys();
		foreach ($keys as $key) {
			if (is_a(ClassRegistry::getObject(Inflector::camelize($key)), 'Model')) {
				ClassRegistry::getObject(Inflector::camelize($key))->create(false);
			}
		}		
        $Controller->Session->delete('Message');
 
        $default = array(
            'data' => array(),
            'method' => 'post',
            'session' => true,
            'form_data' => null
        );
        $options = array_merge($default, $options);
 
        // set up the controller based on the url
        $urlParams = Router::parse($url);
		$action = $urlParams['action'];

        if ($options['form_data']) {
            $urlParams['form'] = $options['form_data'];
        }

		$prefix = null;
		$urlParams['url']['url'] = $url;
        if (strtolower($options['method']) == 'get') {
            $urlParams['url'] = array_merge($options['data'], $urlParams['url']);
        } else {
            $Controller->data = $options['data'];
        }
		if (isset($urlParams['prefix'])) {
			$action = $urlParams['prefix'].'_'.$action;
			$prefix = $urlParams['prefix'].'/';
		}		
        $Controller->passedArgs = $urlParams['named'];
        $Controller->params = $urlParams;
        $Controller->url = $urlParams;
        $Controller->action = $prefix.$urlParams['plugin'].'/'.$urlParams['controller'].'/'.$urlParams['action'];
 
		// only initialize the components once
		if ($this->_componentsInitialized === false) {
			$this->_componentsInitialized = true;
			$Controller->Component->initialize($Controller);
		}
 
        // configure auth
        if (isset($Controller->Auth)) {
            $Controller->Auth->initialize($Controller);
            if ($options['session'] === true) {
                if (!$Controller->Session->check('Auth.User') && !$Controller->Session->check('User')) {
                    $Controller->Session->write('Auth.User', array(
                        'id' => 2, 'username' => 'dnolan', 'role_id' => 2, 'location_id' => 1));
                }
            }
        }
        // configure acl
        if (isset($Controller->Acl)) {
            $Controller->Acl = new MockAclComponent();
            $Controller->Acl->enabled = true;
            $Controller->Acl->setReturnValue('check', true);
        }
		
        $Controller->beforeFilter();

        $Controller->Component->startup($Controller);
 
        call_user_func_array(array(&$Controller, $action), $urlParams['pass']);
 
        $Controller->beforeRender();
        $Controller->Component->triggerCallback('beforeRender', $Controller);

        return $Controller->viewVars;
    }


   /**
    * Will return true if a matching flashMessage is in the Session
    *
    * @param <object> $object
    * @param <string> $flashMessage
    * @param <string> $layout
    * @param <array> $params
    */
    function assertFlashMessage(&$object, $flashMessage, $layout = 'element', $params = array()) {
        $actualFlashMessage = $object->Session->read('Message.flash');
        $expectedFlashMessage = array(
            'message' => $flashMessage,
            'element' => $layout,
            'params' => $params
        );

        if ($this->assertEqual($actualFlashMessage, $expectedFlashMessage)) {
            return true;
        }

        return false;
    }
}

?>

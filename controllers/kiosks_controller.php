<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

App::import('Core', 'HttpSocket'); 
 
class KiosksController extends AppController {

    var $name = 'Kiosks';
    var $components = array('Cookie', 'Transaction', 'Email');

    function beforeFilter() {
		parent::beforeFilter();
		if($this->params['action'] == 'kiosk_self_scan_document') {
			$this->Security->validatePost = false;
		}
		if($this->Auth->user('role_id') > 1) {
			$this->Auth->allow('admin_get_kiosk_buttons_by_location');
		}
		$this->Cookie->name = 'self_sign';
		$this->Cookie->domain = Configure::read('domain');
        $this->Auth->allow('kiosk_set_language');

        if($this->Auth->user('id') == NULL)
		{
			$this->set('user_logged_in', FALSE);
		}
		else
		{
			$this->set('user_logged_in', TRUE);
		}
	}

    function admin_index() {
		$this->Kiosk->recursive = 0;
        $this->Kiosk->KioskSurvey->recursive = 0;
		$this->Kiosk->Behaviors->attach('Containable');

		$this->paginate = array(
			'contain' => array(
				'Location',
				'KioskSurvey', 
				'KioskSurvey.KioskSurveyQuestion'
			),
			'limit' => Configure::read('Pagination.kiosk.limit'), 
			'conditions' => array('Kiosk.deleted !=' => 1)
		);

        $surveys = $this->Kiosk->KioskSurvey->find('all');
			
		$this->set('kiosks', $this->paginate('Kiosk'));
        $this->set('surveys', $surveys);
	}


	public function kiosk_survey_prompt()
	{
		$this->layout = 'kiosk';
		$this->loadModel('Setting');
		$kiosk = $this->Kiosk->isKiosk();
		$kiosk_survey_setting = $this->Setting->getSetting('Kiosk', 'Survey');
		$this->set(compact('kiosk', 'kiosk_survey_setting'));
	}

    function admin_add() {
		if(!empty($this->data))
		{
			$this->Kiosk->create();
			if($this->Kiosk->save($this->data))
			{
				$this->Transaction->createUserTransaction('Kiosk', null, null, 'Added kiosk ' . $this->data['Kiosk']['location_recognition_name']);
				$this->Session->setFlash(__('The kiosk has been saved', true), 'flash_success');
				$this->redirect( array('action' => 'index'));
			}
			else
			{
				$this->Session->setFlash(__('The kiosk could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		$locations = $this->Kiosk->Location->find('list');
		$this->set(compact('locations'));
	}

    function admin_edit($id =null) {
		$this->Kiosk->recursive = 0;

		if(!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid kiosk', true), 'flash_failure');
			$this->redirect( array('action' => 'index'));
		}
		if(!empty($this->data)) {
			if($this->Kiosk->save($this->data)) {
				$this->Transaction->createUserTransaction('Kiosk', null, null, 'Edited kiosk ' . $this->data['Kiosk']['location_recognition_name']);
				$this->Session->setFlash(__('The kiosk has been saved', true), 'flash_success');
				$this->redirect( array('action' => 'index'));
			}
			else {
				$this->Session->setFlash(__('The kiosk could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if(empty($this->data)) {
			$this->data = $this->Kiosk->read(null, $id);
		}
		$locations = $this->Kiosk->Location->find('list');
		$this->set(compact('locations'));
	}

    function admin_delete($id =null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid id for kiosk', true), 'flash_failure');
			$this->redirect( array('action' => 'index'));
		}
		if($this->Kiosk->delete($id)) {
			$kiosk = $this->Kiosk->findById($id);
			$this->Transaction->createUserTransaction('Kiosk', null, null, 'Deleted kiosk ' . $kiosk['Kiosk']['location_recognition_name']);
			$this->Session->setFlash(__('Kiosk deleted', true), 'flash_success');
			$this->redirect( array('action' => 'index'));
		}
		$this->Session->setFlash(__('Kiosk was not deleted', true), 'flash_failure');
		$this->redirect( array('action' => 'index'));
	}

	function kiosk_self_sign_confirm() {
		$fields = $this->getKioskRegistraionFields();
		$kiosk = $this->Kiosk->isKiosk();

		$this->loadModel('User');
		$this->loadModel('Setting');
		$user 				 	= $this->User->findbyId($this->Auth->user('id'));
		$kiosk_survey_setting	= $this->Setting->getSetting('Kiosk', 'Survey');
		$title_for_layout 	 	= 'Self Sign Kiosk';
		
		$this->set(compact('title_for_layout', 'fields', 'user', 'kiosk_survey_setting', 'kiosk'));
		$this->layout = 'kiosk';
	}

    function kiosk_self_sign_edit($id =null) {
		$this->Kiosk->recursive = 0;

		if(!$id && empty($this->data)) {
			$this->Session->setFlash(__('An error occured please try again.', true), 'flash_failure');
			$this->redirect( array('action' => 'self_sign_confirm'));
		}

		$this->loadModel('User');
		$this->User->recursive = 0;

		if(!empty($this->data)) {
			$this->User->setValidation('customerMinimum');
			if($this->User->save($this->data)) {
				$this->Transaction->createUserTransaction('Self Sign', $id, $this->Kiosk->getKioskLocationId(), 'Edited information');
				$this->Session->setFlash(__('The information has been saved', true), 'flash_success');
				
				$settings = Cache::read('settings');

				if(isset($settings['SelfSign']['KioskConfirmation']) && $settings['SelfSign']['KioskConfirmation'] === 'on')
				{
					$this->redirect(array('action' => 'self_sign_confirm', 'kiosk' => true));
				}
				else
				{
					$this->redirect(array('action' => 'self_sign_service_selection', 'kiosk' => true));
				}
				
			}
			else {
				$this->Session->setFlash(__('The information could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		$this->data = $this->User->read(null, $id);
		$title_for_layout = 'Self Sign Kiosk';
		$states = $this->states;
		$genders = $this->genders;
		$fields = $this->getKioskRegistraionFields();
		$this->set(compact('title_for_layout', 'states', 'fields', 'genders'));
		$this->layout = 'kiosk';
	}

    function kiosk_self_sign_service_selection($buttonId =null, $isChild =false) {
    	$this->loadModel('Setting');
    	$this->loadModel('KioskSurveyResponses');

    	$kiosk 				= $this->Kiosk->isKiosk('demo');
    	$kiosk_expiration 	= $this->Setting->findByName('SurveyExpiration', array('value'));
    	$kiosk_expiration	= json_decode($kiosk_expiration['Setting']['value'], true);

    	$kiosk_ask_once		= $this->Setting->findByName('KioskAskOnce', array('value'));
    	$kiosk_ask_once		= json_decode($kiosk_ask_once['Setting']['value'], true);
    	
    	//Format kiosk_expiration for proper strtotime format "-x days or -y months"
    	$kiosk_expiration 	= "-" . $kiosk_expiration[0] . " " . $kiosk_expiration[1];

    	$user 				= $this->Auth->user();

    	if($kiosk_ask_once[0]['value'] == 'Yes') {
    		// Query kiosk survey responses for matching user_id and survey id
	    	$kiosk_survey_response = $this->KioskSurveyResponses->find('first', array(
	    		'conditions' => array(
	    			'user_id' => $this->Auth->user('id'),
	   				'kiosk_survey_id' => $kiosk['KioskSurvey'][0]['id']
	    		),
	    		'order' => 'created DESC'
	    	));
    	} else {
    		$kiosk_survey_response = FALSE; //Setting this to false makes it think they havent responded to the survey
    	}

    	// If kiosk matches and if there is a survey attached
    	$kiosk_survey_exists = ($kiosk && count($kiosk['KioskSurvey']));
    	
    	// If the last_kiosk_login date is in the range specified in the settings then prompt them with the quiz
    	$kiosk_survey_expired = strtotime($user['User']['last_kiosk_login']) > strtotime( $kiosk_expiration );

    	$survey_valid = (!$kiosk_survey_response && $kiosk_survey_expired);

    	if($kiosk_survey_exists && $survey_valid && !$this->Session->read('prompted_for_survey'))
    	{
    		// Keep the survey from prompting on every k.s.s.s.s. action
    		$this->Session->write('prompted_for_survey', TRUE);
    		$this->redirect('/kiosk/kiosks/survey_prompt/' . $kiosk['KioskSurvey'][0]['id']);
    	}

		if(empty($buttonId)) {
			$this->Cookie->write('location', $kiosk['Kiosk']['location_id']);
			$this->Cookie->write('kioskId', $kiosk['Kiosk']['id']);
			
			if($kiosk['Kiosk']['logout_message'])
			{
				$this->Cookie->write('logout_message', $kiosk['Kiosk']['logout_message']);	
			}

			$this->Kiosk->KioskButton->recursive = -1;
			$rootButtons = $this->Kiosk->KioskButton->find('all', array(
				'conditions' => array(
					'KioskButton.parent_id' => null, 
					'KioskButton.kiosk_id' => $kiosk['Kiosk']['id'], 
					'KioskButton.status' => 0), 
				'order' => array('KioskButton.order' => 'asc')));
			$this->set('rootButtons', Set::extract('/KioskButton', $rootButtons));
		}
		$this->loadModel('MasterKioskButton');
		$this->MasterKioskButton->recursive = -1;
		$masterButtonList = $this->MasterKioskButton->find('list', array(
			'fields' => 'MasterKioskButton.name'
		));
		
		$masterButtonTagList = $this->MasterKioskButton->find('list', array(
			'fields' => array(
				'MasterKioskButton.id', 
				'MasterKioskButton.tag'
			), 
			'conditions' => array(
				'MasterKioskButton.deleted' => 0
			)
		));
			
		if($buttonId)
		{
			$message = $this->Kiosk->KioskButton->getLogoutMessage($buttonId, null, $this->Cookie->read('kioskId'));
			
			if($message)
			{
				$this->Cookie->write('logout_message', $message);
			}

			$scan_match = '(Scan Documents|scan documents|Escanear Documentos|escanear documentos)';
			if(preg_match($scan_match, $masterButtonList[$buttonId]))
			{
				$this->redirect( array('action' => 'self_scan_program_selection'));
			}

			$possibleChildren = $this->Kiosk->KioskButton->find('all', array(
				'conditions' => array(
					'KioskButton.parent_id' => $buttonId, 
					'KioskButton.kiosk_id' => $this->Cookie->read('kioskId'), 
					'KioskButton.status' => 0), 
				'order' => array('KioskButton.order' => 'asc'
				)
			));

			$tag = $masterButtonTagList[$buttonId];
			if(empty($possibleChildren) && $isChild == false)
			{
				if(preg_match('(other|Other|otro|Otro)', $masterButtonList[$buttonId])) {
					$this->Cookie->write('level.1', $buttonId);
					$this->Cookie->delete('level.2');
					$this->Cookie->delete('level.3');
					$this->redirect( array('action' => 'self_sign_other'));
				}
				else {
					$data['SelfSignLog']['location_id'] = $this->Cookie->read('location');
					$data['SelfSignLog']['user_id'] = $this->Auth->user('id');
					$data['SelfSignLog']['kiosk_id'] = $this->Cookie->read('kioskId');
					$data['SelfSignLog']['level_1'] = $buttonId;
					$this->Cookie->write('details.1', $masterButtonList[$buttonId]);
					$this->Kiosk->SelfSignLog->create();
					$this->Kiosk->SelfSignLog->save($data['SelfSignLog']);
					$data['SelfSignLog']['id'] = $this->Kiosk->SelfSignLog->getInsertId();
					$this->Kiosk->SelfSignLogArchive->create();
					$this->Kiosk->SelfSignLogArchive->save($data['SelfSignLog']);
					$this->Transaction->createUserTransaction('Self Sign');
					$this->sendSelfSignAlert($data['SelfSignLog']);
					$this->redirect( array(
						'controller' => 'users', 
						'action' => 'logout', 
						'selfSign', 
						$this->Cookie->read('logout_message'), 
						'kiosk' => false));
				}
			}
			else
			{
				$button = $this->Kiosk->KioskButton->find('first', array(
					'conditions' => array(
						'KioskButton.id' => $buttonId, 
						'KioskButton.status' => 0), 
					'order' => array('KioskButton.order' => 'asc')));
				if($button['KioskButton']['parent_id'] == null) {
					$this->Cookie->write('level.1', $buttonId);
					$this->Cookie->write('details.1', $masterButtonList[$buttonId]);
				}
				if($button['KioskButton']['parent_id'] != null) {
					$button2 = $this->Kiosk->KioskButton->find('first', array(
						'conditions' => array(
							'KioskButton.id' => $button['KioskButton']['parent_id'], 
							'KioskButton.status' => 0), 
						'order' => array('KioskButton.order' => 'asc')));
					if($button2['KioskButton']['parent_id'] == null) {
						$this->Cookie->write('level.2', $buttonId);
						$this->Cookie->write('details.2', $masterButtonList[$buttonId]);
						$count = $this->Kiosk->KioskButton->find('count', array(
							'conditions' => array(
								'KioskButton.parent_id' => $buttonId,
								'KioskButton.status' => 0), 
							'order' => array('KioskButton.order' => 'asc')));
						if($count == 0) {
							if(preg_match('(other|Other|otro|Otro)', $masterButtonList[$buttonId])) {
								$this->Cookie->delete('level.3');
								$this->redirect( array('action' => 'self_sign_other', 'level2'));
							}
							else {
								$data['SelfSignLog']['location_id'] = $this->Cookie->read('location');
								$data['SelfSignLog']['user_id'] = $this->Auth->user('id');
								$data['SelfSignLog']['kiosk_id'] = $this->Cookie->read('kioskId');
								$data['SelfSignLog']['level_1'] = $this->Cookie->read('level.1');
								$data['SelfSignLog']['level_2'] = $this->Cookie->read('level.2');								
								$this->Kiosk->SelfSignLog->create();
								$this->Kiosk->SelfSignLog->save($data['SelfSignLog']);
								$data['SelfSignLog']['id'] = $this->Kiosk->SelfSignLog->getInsertId();
								$this->Kiosk->SelfSignLogArchive->create();
								$this->Kiosk->SelfSignLogArchive->save($data['SelfSignLog']);
								$this->Transaction->createUserTransaction('Self Sign');
								$this->sendSelfSignAlert($data['SelfSignLog']);
								$this->redirect(array(
									'controller' => 'users', 
									'action' => 'logout', 'selfSign', $this->Cookie->read('logout_message'), 'kiosk' => false));
							}
						}
					}
					elseif($button2['KioskButton']['parent_id'] !== null) {
						$this->Cookie->write('level.3', $buttonId);
						$this->Cookie->write('details.3', $masterButtonList[$buttonId]);
						if(preg_match('(other|Other|otro|Otro)', $masterButtonList[$buttonId])) {
							$this->redirect( array('action' => 'self_sign_other', 'level3'));
						}
						else {
							$data['SelfSignLog']['location_id'] = $this->Cookie->read('location');
							$data['SelfSignLog']['user_id'] = $this->Auth->user('id');
							$data['SelfSignLog']['kiosk_id'] = $this->Cookie->read('kioskId');
							$data['SelfSignLog']['level_1'] = $this->Cookie->read('level.1');
							$data['SelfSignLog']['level_2'] = $this->Cookie->read('level.2');
							$data['SelfSignLog']['level_3'] = $this->Cookie->read('level.3');							
							$this->Kiosk->SelfSignLog->create();
							$this->Kiosk->SelfSignLog->save($data['SelfSignLog']);
							$data['SelfSignLog']['id'] = $this->Kiosk->SelfSignLog->getInsertId();
							$this->Kiosk->SelfSignLogArchive->create();
							$this->Kiosk->SelfSignLogArchive->save($data['SelfSignLog']);
							$this->Transaction->createUserTransaction('Self Sign');
							$this->sendSelfSignAlert($data['SelfSignLog']);
							$this->redirect( array(
								'controller' => 'users', 
								'action' => 'logout', 'selfSign', $this->Cookie->read('logout_message'), 'kiosk' => false));
						}
					}
				}
				$this->set('childButtons', Set::extract('/KioskButton', $possibleChildren));
				$button = $this->Kiosk->KioskButton->find('first', array(
					'conditions' => array(
						'KioskButton.id' => $possibleChildren[0]['KioskButton']['parent_id'], 
						'KioskButton.status' => 0), 
					'order' => array('KioskButton.order' => 'asc')));
				$referer = null;
				if($button['KioskButton']['parent_id'] == null) {
					$referer = '/kiosk/kiosks/self_sign_service_selection';
				}
				else {
					$referer = '/kiosk/kiosks/self_sign_service_selection/' . $this->Cookie->read('level.1');
				}
			}
		}
		$title_for_layout = 'Self Sign Kiosk';
		$this->set(compact('masterButtonList', 'title_for_layout', 'tag', 'referer'));
		$this->layout = 'kiosk';
	}

  	function kiosk_self_sign_other($level=null) {
		if(!empty($this->data)) {
			$data['SelfSignLog']['location_id'] = $this->Cookie->read('location');
			$data['SelfSignLog']['user_id'] = $this->Auth->user('id');
			$data['SelfSignLog']['kiosk_id'] = $this->Cookie->read('kioskId');
			$data['SelfSignLog']['other'] = $this->data['SelfSignLog']['other'];
			if($this->Cookie->read('level.1') != null) {
				$data['SelfSignLog']['level_1'] = $this->Cookie->read('level.1');
			}
			if($this->Cookie->read('level.2') != null) {
				$data['SelfSignLog']['level_2'] = $this->Cookie->read('level.2');
			}
			if($this->Cookie->read('level.3') != null) {
				$data['SelfSignLog']['level_3'] = $this->Cookie->read('level.3');
			}
			$this->Kiosk->SelfSignLog->create();
			$this->Kiosk->SelfSignLogArchive->create();
			if($this->Kiosk->SelfSignLog->save($data['SelfSignLog'])) {
				$data['SelfSignLog']['id'] = $this->Kiosk->SelfSignLog->getInsertId();
				$this->Kiosk->SelfSignLogArchive->save($data['SelfSignLog']);
				$this->Cookie->write('details.other', $this->data['SelfSignLog']['other']);
				$this->Transaction->createUserTransaction('Self Sign');
				$this->sendSelfSignAlert($data['SelfSignLog']);
				$this->redirect( array('controller' => 'users', 'action' => 'logout', 'selfSign', $this->Cookie->read('logout_message'), 'kiosk' => false));
			}
			else {
				$this->Session->setFlash(__('An error occured please try again', true), 'flash_failure');
			}
		}
		$referer = '/kiosk/kiosks/self_sign_service_selection/';
		if($level) {
			switch($level) {
				case 'level2' :
					$referer = '/kiosk/kiosks/self_sign_service_selection/' . $this->Cookie->read('level.1');
					break;
				case 'level3' :
					$referer = '/kiosk/kiosks/self_sign_service_selection/' . $this->Cookie->read('level.2');
					break;
			}			
		}
		$title_for_layout = 'Self Sign Kiosk';
		$this->set(compact('title_for_layout', 'referer'));
		$this->layout = 'kiosk';
	}

    function kiosk_self_scan_program_selection($buttonId=null) {
		$this->loadModel('SelfScanCategory');
		$this->SelfScanCategory->recursive = -1;
		if(!$buttonId) {
			$parentButtons = $this->SelfScanCategory->find('all', array('conditions' => array('SelfScanCategory.parent_id' => null)));
			$referer = '/kiosk/kiosks/self_sign_service_selection';
		}
		if($buttonId) {
			$totalChildren = $this->SelfScanCategory->childCount($buttonId);
			if($totalChildren > 0) {
				$this->set('childButtons', $this->SelfScanCategory->children($buttonId));
				$referer = '/kiosk/kiosks/self_scan_program_selection';
			}
			else {
				$this->data = $this->SelfScanCategory->read(null, $buttonId);
				$selfScanCatId = $this->data['SelfScanCategory']['id'];
				$queueCatId = $this->data['SelfScanCategory']['queue_cat_id'];
				$this->redirect( array('action' => 'self_scan_document', $selfScanCatId, $queueCatId));
			}
		}
		$this->layout = 'kiosk';
		$title_for_layout = __('Self Scan Program Selection', true);
		$this->set(compact('title_for_layout', 'parentButtons', 'referer'));
	}

    function kiosk_self_scan_document($selfScanCatId=null, $queueCatId=null) {	
		if(!empty($this->data)) {
			$id = $this->_queueScannedDocument();
			if($id) {
				$this->loadModel('SelfScanCategory');
				$this->SelfScanCategory->recursive = -1;
				$selfScanCat = $this->SelfScanCategory->findById($this->data['QueuedDocument']['self_scan_cat_id']);
				$this->sendSelfScanAlert($this->Auth->user(), $id, $this->data['QueuedDocument']['scanned_location_id']);
				$this->sendSelfScanCategoryAlert($this->Auth->user(), $selfScanCat, $id, $this->data['QueuedDocument']['scanned_location_id']);
				$this->Transaction->createUserTransaction('Self Scan', null, $this->data['QueuedDocument']['scanned_location_id'], 'Self scanned document ID ' . $id);
				$this->Session->setFlash(__('Scanned document was saved successfully.', true), 'flash_success');
				$this->autoRender = false;
				exit ;
			}
			else {
				$this->Session->setFlash(__('Unable to save document.', true), 'flash_failure');
				$this->autoRender = false;
				return 'false';
			}
		}
		$locationId = $this->Kiosk->getKioskLocationId();
		$referer = '/kiosk/kiosks/self_scan_program_selection';
		$this->set(compact('selfScanCatId', 'queueCatId', 'locationId', 'referer'));
		$this->layout = 'kiosk';
	}

    function kiosk_self_scan_another_document() {
		$this->layout = 'kiosk';
	}

    function _queueScannedDocument() {
		// get the document relative path to the inital storage folder
		$path = Configure::read('Document.storage.uploadPath');
		// check to see if the directory for the current year exists
		if(!file_exists($path . date('Y') . '/')) {
		    // if directory does not exist, create it
		    mkdir($path . date('Y'), 0777);
		}
		// add the current year to our path string
		$path .= date('Y') . '/';
		// check to see if the directory for the current month exists
		if(!file_exists($path . date('m') . '/')) {
		    // if directory does not exist, create it
		    mkdir($path . date('m'), 0777);
		}
		// add the current month to our path string
		$path .= date('m') . '/';
		// build our fancy unique filename
		$docName = date('YmdHis') . rand(0, pow(10, 7)) . '.pdf';
		$this->data['QueuedDocument']['filename'] = $docName;
		$this->data['QueuedDocument']['entry_method'] = 'Self Scan';
		if(!move_uploaded_file($this->data['QueuedDocument']['submittedfile']['tmp_name'], $path . $docName)) {
		    return false;
		}
		$this->loadModel('QueuedDocument');
		$this->QueuedDocument->create();
		if($this->QueuedDocument->save($this->data)) {
		    return $this->QueuedDocument->getLastInsertId();
		}
		else {
		    return false;
		}
    }

	function admin_get_kiosk_buttons_by_location($locationId, $parentId=NULL) {
		if($this->RequestHandler->isAjax()) {
			//TODO move this masterkiosk crap to it's own function
			$this->loadModel('MasterKioskButton');
			$this->MasterKioskButton->recursive = -1;
			$masterButtonList = $this->MasterKioskButton->find('list', array(
				'fields' => 'MasterKioskButton.name'));			
				$kiosks = $this->Kiosk->find('all', array(
					'conditions' => array(
						'Kiosk.location_id' => $locationId,
						'Kiosk.deleted' => 0),
					'fields' => array('Kiosk.id'),
					'contain' => array('KioskButton' => array(
						'conditions' => array(
							'KioskButton.parent_id' => $parentId,
							'KioskButton.status' => 0),
						'fields' => array('KioskButton.id')))));
			$data = array();
			if($kiosks && $masterButtonList) {
				$i = 0;
				foreach($kiosks as $kiosk) {
					foreach ($kiosk['KioskButton'] as $k => $v) {
						if(!in_array($kiosk['KioskButton'][$k]['id'], $data)) {
							$data['buttons'][$i]['id'] = $kiosk['KioskButton'][$k]['id'];
							if($masterButtonList[$kiosk['KioskButton'][$k]['id']] != 'Scan Documents') {
								$data['buttons'][$i]['name'] = $masterButtonList[$kiosk['KioskButton'][$k]['id']];	
							}					
							$i++;
						}
					}
				}
				$data['success'] = true;
			}
			else {
				$data['success'] = true;
				$data['buttons'] = array();
			}	
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');				
		}
	}

	function kiosk_set_language($lang) {
		if ($lang === 'en') {
			$this->Session->delete('Config.language');
		} else if ($lang === 'es') {
			$this->Session->write('Config.language', 'es-es');
		}
		
		$this->redirect($this->referer(), null, true); 
	}
	
	private function getKioskRegistraionFields() {
		$settings = Cache::read('settings');	
		return Set::extract('/field',  json_decode($settings['SelfSign']['KioskRegistration'], true));			
	}
	
	private function sendSelfSignAlert($selfSignLog) {
		$kioskName = $this->Kiosk->getKioskName($this->Cookie->read('kioskId'));
		$this->loadModel('Alert');
		$data = $this->Alert->getSelfSignAlerts($selfSignLog, $kioskName);
		if($data) {
			$HttpSocket = new HttpSocket();
			$results = $HttpSocket->post('localhost:3000/new', 
				array('data' => $data));
			$to = '';
			foreach($data as $alert) {
				if($alert['send_email']) {
					$to .= $alert['email'] . ',';
				}			
			}
			if(!empty($to)) {
				$to = trim($to, ',');
				$this->Email->to = $to;

				$this->loadModel('Setting');
				$cc = $this->Setting->getEmails();

				if(count($cc))
					$this->Email->cc = $cc;
				
				$this->Email->from = Configure::read('System.email');
				$this->Email->subject = 'Self Sign alert';
				$this->Email->send($alert['message'] . "\r\n" . $alert['url']);				
			}
		}
	}
	
	private function sendSelfScanAlert($user, $docId, $locationId) {
		$this->loadModel('Alert');
		$data = $this->Alert->getSelfScanAlerts($user, $docId, $locationId);
		if($data) {
			$HttpSocket = new HttpSocket();
			$results = $HttpSocket->post('localhost:3000/new', 
				array('data' => $data));
			$to = '';
			foreach($data as $alert) {
				if($alert['send_email']) {
					$to .= $alert['email'] . ',';
				}			
			}
			if(!empty($to)) {
				$to = trim($to, ',');
				$this->Email->to = $to;

				$this->loadModel('Setting');
				$cc = $this->Setting->getEmails();

				if(count($cc))
					$this->Email->cc = $cc;

				$this->Email->from = Configure::read('System.email');
				$this->Email->subject = 'Self Scan alert';
				$this->Email->send($alert['message'] . "\r\n" . $alert['url']);				
			}
		}
	}	

	private function sendSelfScanCategoryAlert($user, $selfScanCat, $docId, $locationId) {
		$this->loadModel('Alert');
		$data = $this->Alert->getSelfScanCategoryAlerts($user, $selfScanCat, $docId, $locationId);
		if($data) {
			$HttpSocket = new HttpSocket();
			$results = $HttpSocket->post('localhost:3000/new', 
				array('data' => $data));
			$to = '';
			foreach($data as $alert) {
				if($alert['send_email']) {
					$to .= $alert['email'] . ',';
				}			
			}
			if(!empty($to)) {
				$to = trim($to, ',');
				$this->Email->to = $to;

				$this->loadModel('Setting');
				$cc = $this->Setting->getEmails();

				if(count($cc))
					$this->Email->cc = $cc;

				$this->Email->from = Configure::read('System.email');
				$this->Email->subject = 'Self Scan Category alert';
				$this->Email->send($alert['message'] . "\r\n" . $alert['url']);				
			}
		}
	}	
}
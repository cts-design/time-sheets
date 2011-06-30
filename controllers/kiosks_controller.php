<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class KiosksController extends AppController {

    var $name = 'Kiosks';
    var $components = array('Cookie', 'Transaction');

    function beforeFilter() {
		parent::beforeFilter();
		if($this->params['action'] == 'self_scan_document') {
			$this->Security->validatePost = false;
		}
		$this->Cookie->name = 'self_sign';
		$this->Cookie->domain = Configure::read('domain'); 
	}

    function admin_index() {
		$this->Kiosk->recursive = 0;
		$this->paginate = array(
			'limit' => Configure::read('Pagination.kiosk.limit'), 
			'conditions' => array('Kiosk.deleted !=' => 1));
		$this->set('kiosks', $this->paginate('Kiosk'));
	}

    function admin_add() {
		if(!empty($this->data)) {
			$this->Kiosk->create();
			if($this->Kiosk->save($this->data)) {
				$this->Transaction->createUserTransaction('Kiosk', null, null, 'Added kiosk ' . $this->data['Kiosk']['location_recognition_name']);
				$this->Session->setFlash(__('The kiosk has been saved', true), 'flash_success');
				$this->redirect( array('action' => 'index'));
			}
			else {
				$this->Session->setFlash(__('The kiosk could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		$locations = $this->Kiosk->Location->find('list');
		$this->set(compact('locations'));
	}

    function admin_edit($id =null) {
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
		$this->set('title_for_layout', 'Self Sign Kiosk');
		$this->layout = 'kiosk';
	}

    function kiosk_self_sign_edit($id =null) {
		if(!$id && empty($this->data)) {
			$this->Session->setFlash(__('An error occured please try again.', true), 'flash_failure');
			$this->redirect( array('action' => 'self_sign_confirm'));
		}
		$this->loadModel('User');
		if(!empty($this->data)) {
			$this->User->setValidation('selfSignEdit');
			if($this->User->save($this->data)) {
				$this->Transaction->createUserTransaction('Self Sign', $id, $this->Kiosk->getKioskLocationId(), 'Edited information');
				$this->Session->setFlash(__('The information has been saved', true), 'flash_success');
				$this->redirect( array('action' => 'self_sign_service_selection'));
			}
			else {
				$this->Session->setFlash(__('The information could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if(empty($this->data)) {
			$this->data = $this->User->read(null, $id);
		}
		$this->set('title_for_layout', 'Self Sign Kiosk');
		$this->layout = 'kiosk';
	}

    function kiosk_self_sign_service_selection($buttonId =null, $isChild =false) {
		if(empty($buttonId)) {
			$oneStop = env('HTTP_USER_AGENT');
			$arrOneStop = explode('##', $oneStop);
			if(!isset($arrOneStop[1])) {
				$oneStopLocation = '';
			}
			else {
				$oneStopLocation = $arrOneStop[1];
			}
			$this->Kiosk->recursive = -1;
			$kiosk = $this->Kiosk->find('first', array(
				'conditions' => array(
					'Kiosk.location_recognition_name' => $oneStopLocation, 'Kiosk.deleted' => 0)));

			$this->Cookie->write('location', $kiosk['Kiosk']['location_id']);
			$this->Cookie->write('kioskId', $kiosk['Kiosk']['id']);
			$this->Cookie->write('logout_message', $kiosk['Kiosk']['logout_message']);
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
		$masterButtonList = $this->MasterKioskButton->find('list');
		$masterButtonTagList = $this->MasterKioskButton->find('list', array(
			'fields' => array('MasterKioskButton.id', 'MasterKioskButton.tag'), 
			'conditions' => array('MasterKioskButton.deleted' => 0)));
			
		if($buttonId) {
			$message = $this->Kiosk->KioskButton->getLogoutMessage($buttonId, null, $kiosk['Kiosk']['location_id']);
			if($message) {
				$this->Cookie->write('logout_message', $message);
			}			
			if($masterButtonList[$buttonId] === 'Scan Documents') {
				$this->redirect( array('action' => 'self_scan_program_selection'));
			}
			$possibleChildren = $this->Kiosk->KioskButton->find('all', array(
				'conditions' => array(
					'KioskButton.parent_id' => $buttonId, 
					'KioskButton.kiosk_id' => $this->Cookie->read('kioskId'), 
					'KioskButton.status' => 0), 
				'order' => array('KioskButton.order' => 'asc')));
			$tag = $masterButtonTagList[$buttonId];
			if(empty($possibleChildren) && $isChild == false) {
				if($masterButtonList[$buttonId] == 'other' || $masterButtonList[$buttonId] == 'Other') {
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

					$this->redirect( array(
						'controller' => 'users', 
						'action' => 'logout', 
						'selfSign', 
						$this->Cookie->read('logout_message'), 
						'kiosk' => false));
				}
			}
			else {
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
							if($masterButtonList[$buttonId] == 'other' || $masterButtonList[$buttonId] == 'Other') {
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
								$this->redirect(array(
									'controller' => 'users', 
									'action' => 'logout', 'selfSign', $this->Cookie->read('logout_message'), 'kiosk' => false));
							}
						}
					}
					elseif($button2['KioskButton']['parent_id'] !== null) {
						$this->Cookie->write('level.3', $buttonId);
						$this->Cookie->write('details.3', $masterButtonList[$buttonId]);
						if($masterButtonList[$buttonId] == 'other' || $masterButtonList[$buttonId] == 'Other') {
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

  	function kiosk_self_sign_other($level) {
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
				$this->redirect( array('controller' => 'users', 'action' => 'logout', 'selfSign', $this->Cookie->read('logout_message'), 'kiosk' => false));
			}
			else {
				$this->Session->setFlash(__('An error occured please try again', true), 'flash_failure');
			}
		}
		$referer = '/kiosk/kiosks/self_sign_service_selection/';
		switch($level) {
			case 'level2' :
				$referer = '/kiosk/kiosks/self_sign_service_selection/' . $this->Cookie->read('level.1');
				break;
			case 'level3' :
				$referer = '/kiosk/kiosks/self_sign_service_selection/' . $this->Cookie->read('level.2');
				break;
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
				$this->loadModel('User');
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
		$this->set(compact('selfScanCatId', 'queueCatId', 'locationId'));
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
		    mkdir($path . date('Y'), 0755);
		}
		// add the current year to our path string
		$path .= date('Y') . '/';
		// check to see if the directory for the current month exists
		if(!file_exists($path . date('m') . '/')) {
		    // if directory does not exist, create it
		    mkdir($path . date('m'), 0755);
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

}
<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

class KioskButtonsController extends AppController {

	var $name = 'KioskButtons';
	var $helpers = array('Tree');
	var $components = array('Cookie');

	function admin_index($id =null) {
		if($id == null) {
			$this->Session->setFlash(__('Invalid Kiosk Id.', true), 'flash_failure');
			$this->redirect( array('controller' => 'users',
				'action' => 'dashboard',
				'admin' => true));
		}
		else {
			$this->Cookie->write('kiosk_id', $id, false, '1 hour');
		}
		$this->set('locations', $this->KioskButton->Kiosk->Location->find('list'));
		$this->_setTreeData($id);
	}

	function admin_enable_button() {
		$data['KioskButton']['id'] = $this->params['pass'][0];
		$data['KioskButton']['kiosk_id'] = $this->Cookie->read('kiosk_id');
		$data['KioskButton']['order'] = 9999;
		$count = $this->KioskButton->find('count', array('conditions' => array('KioskButton.id' => $data['KioskButton']['id'],
			'KioskButton.kiosk_id' => $data['KioskButton']['kiosk_id'],
			'KioskButton.status' => 0)));
		if($count > 0) {
			$this->Session->setFlash(__('The kiosk button already exists for the current location.', true), 'flash_failure');
			$this->_setTreeData($data['KioskButton']['kiosk_id']);
			$this->redirect( array('action' => 'index',
				$data['KioskButton']['kiosk_id']));
		}
		$this->loadModel('MasterKioskButton');
		$masterButton = $this->MasterKioskButton->find('first', array('conditions' => array('MasterKioskButton.id' => $data['KioskButton']['id'])));
		if($masterButton['MasterKioskButton']['parent_id'] != '') {
			$data['KioskButton']['parent_id'] = $masterButton['MasterKioskButton']['parent_id'];
			$possibleParentCount = $this->KioskButton->find('count', array('conditions' => array('KioskButton.id' => $data['KioskButton']['parent_id'],
				'KioskButton.kiosk_id' => $data['KioskButton']['kiosk_id'],
				'KioskButton.status' => 0)));
			if($possibleParentCount == 0) {
				$this->Session->setFlash(__('You must add the parent button before enabling a child button.', true), 'flash_failure');
				$this->redirect( array('action' => 'index',
					$data['KioskButton']['kiosk_id']));
			}
		}
		$button = $this->KioskButton->find('first', array('conditions' => array('KioskButton.id' => $data['KioskButton']['id'],
			'KioskButton.kiosk_id' => $data['KioskButton']['kiosk_id'])));

		if($button['KioskButton']['status'] == 1) {
			$data['KioskButton']['button_id'] = $button['KioskButton']['button_id'];
			$data['KioskButton']['status'] = 0;
		}
		else {
			$this->KioskButton->create();
		}
		if($this->KioskButton->save($data)) {
			$button = $this->KioskButton->MasterKioskButton->findById($data['KioskButton']['id']);
			$this->Transaction->createUserTransaction('Kiosk', null, null, 'Enabled kiosk button ' . $button['MasterKioskButton']['name']);
			$this->Session->setFlash(__('The kiosk button has been enabled', true), 'flash_success');
			$this->redirect( array('action' => 'index',
				$data['KioskButton']['kiosk_id']));
		}
		else {
			$this->Session->setFlash(__('The kiosk button could not be enabled. Please, try again.', true), 'flash_failure');
			$this->redirect( array('action' => 'index',
				$data['KioskButton']['kiosk_id']));
		}
	}

	function admin_disable_button() {
		$data['KioskButton']['id'] = $this->params['pass'][0];
		$data['KioskButton']['kiosk_id'] = $this->Cookie->read('kiosk_id');
		$data['KioskButton']['status'] = 1;

		$button = $this->KioskButton->find('first', array('conditions' => array('KioskButton.button_id' => $data['KioskButton']['id'],
			'KioskButton.kiosk_id' => $data['KioskButton']['kiosk_id'],
			'KioskButton.status' => 0)));
		$data['KioskButton']['button_id'] = $button['KioskButton']['button_id'];
		$possibleChildrenCount = $this->KioskButton->find('count', array('conditions' => array('KioskButton.parent_id' => $button['KioskButton']['id'],
			'KioskButton.kiosk_id' => $button['KioskButton']['kiosk_id'],
			'KioskButton.status' => 0)));
		if($possibleChildrenCount > 0) {
			$this->Session->setFlash(__('You cannot disable a parent button that has enabled children.', true), 'flash_failure');
			$this->redirect( array('action' => 'index',
				$data['KioskButton']['kiosk_id']));

		}
		if($this->KioskButton->save($data)) {
			$this->Transaction->createUserTransaction('Kiosk', null, null, 'Disabled kiosk button ' . $button['MasterKioskButton']['name']);
			$this->Session->setFlash(__('The kiosk button has been disabled', true), 'flash_success');
			$this->redirect( array('action' => 'index',
				$data['KioskButton']['kiosk_id']));
		}
		else {
			$this->Session->setFlash(__('The kiosk button could not be disabled. Please, try again.', true), 'flash_failure');
			$this->redirect( array('action' => 'index',
				$data['KioskButton']['kiosk_id']));
		}

	}

	function admin_reorder_buttons_ajax() {
		$data['KioskButton'] = array();
		array_shift($this->params['url']);
		foreach($this->params['url'] as $k => $v) {
			$data['KioskButton'][$v] = array('button_id' => $v,
				'order' => $k);
		}
		$this->KioskButton->primaryKey = 'button_id';
		$this->KioskButton->saveAll($data['KioskButton']);
	}
	
	function admin_edit_logout_message($button_id=null) {
		if($this->RequestHandler->isAjax()) {
			if($button_id && $this->RequestHandler->isGet()) {
				$message = $this->KioskButton->getLogoutMessage(null, $button_id);
				if($message) {
					$data['logout_message'] = $message;
				}
				else {
					$data['logout_message'] = '';
				}
				$this->set('data', $data);
				return $this->render(null, null, '/elements/ajaxreturn');
			}
			$this->data['KioskButton']['button_id'] = $this->params['form']['id'];
			if($this->params['form']['message'] == '') {
				$this->data['KioskButton']['logout_message'] = null;
			}
			else {
				$this->data['KioskButton']['logout_message'] = $this->params['form']['message'];
			}
			if($this->KioskButton->save($this->data)){
				$data['message'] = 'Logout message was updated';

			}
			else {
				$data['message'] = 'An error occured, please try again.';
			}
			$this->set('data', $data);
			return $this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function _setTreeData($id=null) {
		$this->loadModel('MasterKioskButton');
		$this->MasterKioskButton->recursive = -1;
		$masterButtons = $this->MasterKioskButton->find('threaded', array('conditions' => array('MasterKioskButton.deleted !=' => 1),
			'order' => array('MasterKioskButton.id',
				'MasterKioskButton.id DESC')));

		$this->MasterKioskButton->recursive = 1;
		$masterButtonNames = $this->MasterKioskButton->find('list', array('conditions' => array('MasterKioskButton.deleted !=' => 1),
			'fields' => array('MasterKioskButton.name')));

		$masterButtonParentIds = $this->MasterKioskButton->find('list', array('conditions' => array('MasterKioskButton.deleted !=' => 1),
			'fields' => array('MasterKioskButton.parent_id')));

		$this->KioskButton->primaryKey = 'id';
		$locationButtons = $this->KioskButton->find('threaded', array(
			'conditions' => array(
				'KioskButton.status !=' => 1,
				'KioskButton.kiosk_id' => $id
			),
			'order' => array('KioskButton.order' => 'asc')));

		// this is needed for the translations
		// without this, the page will load blank.
		foreach ($locationButtons as $key => $value) {
			$locationButtons[$key]['MasterKioskButton']['name'] =
				$masterButtonNames[$locationButtons[$key]['MasterKioskButton']['id']];
				
			if (!empty($locationButtons[$key]['children'])) {
				foreach ($locationButtons[$key]['children'] as $k => $v) {
					$locationButtons[$key]['children'][$k]['MasterKioskButton']['name'] =
                    	$masterButtonNames[$locationButtons[$key]['children'][$k]['MasterKioskButton']['id']];

				
					if (!empty($locationButtons[$key]['children'][$k]['children'])) {
						foreach ($locationButtons[$key]['children'][$k]['children'] as $ck => $cv) {
							$locationButtons[$key]['children'][$k]['children'][$ck]['MasterKioskButton']['name'] =
		                    	$masterButtonNames[$locationButtons[$key]['children'][$k]['children'][$ck]['MasterKioskButton']['id']];	
						}				
					}
				}
			}
		}

		$vars = array('masterButtonParentIds' => $masterButtonParentIds,
			'masterButtonNames' => $masterButtonNames,
			'masterButtons' => $masterButtons,
			'locationButtons' => $locationButtons);
		$this->set($vars);
	}

}
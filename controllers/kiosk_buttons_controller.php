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

	function admin_index($id = null) {
		$this->layout = 'default_bootstrap';
		if($id == null)
		{
			$this->Session->setFlash(__('Invalid Kiosk Id.', true), 'flash_failure');
			$this->redirect( array('controller' => 'users',
				'action' => 'dashboard',
				'admin' => true));
		}
		else
		{
			$this->Cookie->write('kiosk_id', $id, false, '1 hour');
		}
		$this->set('locations', $this->KioskButton->Kiosk->Location->find('list'));
		$this->_setTreeData($id);
	}

	function admin_enable_button($masterButtonId = NULL) {
		$kiosk_id = $this->Cookie->read('kiosk_id');
		$order = 1;

		$this->loadModel('MasterKioskButton');
		
		//Get the button as a master button
		$masterButton = $this->MasterKioskButton->find('first', array(
			'conditions' => array(
				'MasterKioskButton.id' => $masterButtonId,
			)
		));

		//Check if kiosk button is already enabled
		$kioskButton = $this->KioskButton->find('first', array(
			'conditions' => array(
				'KioskButton.id' => $masterButtonId,
				'KioskButton.kiosk_id' => $kiosk_id,
				'KioskButton.status' => 1
			)
		));

		if($kioskButton)
		{
			$this->Session->setFlash('That button is already enabled at this location', 'flash_failure');
			$this->redirect('/admin/kiosk_buttons/index/' . $kiosk_id);
		}

		//Check to see if it's parent is added
		if($masterButton['MasterKioskButton']['parent_id'] != NULL)
		{
			$parentMasterButton = $this->KioskButton->find('first', array(
				'conditions' => array(
					'KioskButton.id' => $masterButton['MasterKioskButton']['parent_id']
				)
			));

			if(!$parentMasterButton)
			{
				$this->Session->setFlash('You need to add the button parent before adding this button', 'flash_failure');
				$this->redirect('/admin/kiosk_buttons/index/' . $kiosk_id);
			}
		}

		$this->KioskButton->create();
		$newButton = array(
			'KioskButton' 	=> array(
				'parent_id' => $masterButton['MasterKioskButton']['parent_id'],
				'kiosk_id' 	=> $kiosk_id,
				'id' 		=> $masterButtonId,
				'order' 	=> 9999,
				'status'	=> 1
			)
		);
		$is_saved = $this->KioskButton->save($newButton);

		if($is_saved)
		{
			$this->Transaction->createUserTransaction('Kiosk', null, null, 'Enabled kiosk button ' . $masterButton['MasterKioskButton']['name']);
			$this->Session->setFlash('The kiosk button has been enabled', 'flash_success');
		}
		else
		{
			$this->Session->setFlash('The kiosk button could not be enabled', 'flash_failure');
		}

		$this->redirect('/admin/kiosk_buttons/index/' . $kiosk_id);
	}

	public function admin_get_button() {
		$this->autoRender = false;
		$kiosk_button = $this->KioskButton->find('first', array(
			'conditions' => array(
				'button_id' => $this->params['url']['button_id']
			)
		));

		echo json_encode($kiosk_button);
	}

	public function admin_add_action()
	{
		$kiosk_id = $this->Cookie->read('kiosk_id');
		if($this->RequestHandler->isPost())
		{

			$kioskButton = $this->KioskButton->find('first', array(
				'conditions' => array(
					'button_id' => $this->data['KioskButton']['button_id']
				)
			));

			if(!$kioskButton)
			{
				$this->Session->setFlash('That Kiosk Button was not found', 'flash_failure');
			}


			$this->KioskButton->create();
			$kioskButton['KioskButton']['action'] = $this->data['KioskButton']['action'];
			$kioskButton['KioskButton']['action_meta'] = $this->data['KioskButton']['action_meta'];

			$is_saved = $this->KioskButton->save($kioskButton);

			if($is_saved)
			{
				$this->Session->setFlash('Kiosk Button action has been set', 'flash_success');
			}
			else
			{
				$this->Session->setFlash('Kiok Button action could not be set', 'flash_failure');
			}
			$this->redirect('/admin/kiosk_buttons/index/' . $kiosk_id);
		}
	}

	function admin_disable_button() {
		$data['KioskButton']['id'] = $this->params['pass'][0];
		$data['KioskButton']['kiosk_id'] = $this->Cookie->read('kiosk_id');
		$data['KioskButton']['status'] = 1;

		$button = $this->KioskButton->find('first', array(
			'conditions' => array(
				'KioskButton.button_id' => $data['KioskButton']['id'],
				'KioskButton.kiosk_id' => $data['KioskButton']['kiosk_id'],
				'KioskButton.status' => 0
			)
		));

		$data['KioskButton']['button_id'] = $button['KioskButton']['button_id'];

		$possibleChildrenCount = $this->KioskButton->find('count', array(
			'conditions' => array(
				'KioskButton.parent_id' => $button['KioskButton']['id'],
				'KioskButton.kiosk_id' => $button['KioskButton']['kiosk_id'],
				'KioskButton.status' => 0
			)
		));

		if($possibleChildrenCount > 0)
		{
			$this->Session->setFlash(__('You cannot disable a parent button that has enabled children.', true), 'flash_failure');
			$this->redirect('/admin/kiosk_buttons/index/' . $data['KioskButton']['kiosk_id']);

		}

		if($this->KioskButton->save($data))
		{
			$this->Transaction->createUserTransaction('Kiosk', null, null, 'Disabled kiosk button ' . $button['MasterKioskButton']['name']);
			$this->Session->setFlash(__('The kiosk button has been disabled', true), 'flash_success');
			$this->redirect( array('action' => 'index',
				$data['KioskButton']['kiosk_id']));
		}
		else
		{
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
				'KioskButton.status' => 1,
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
<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class MasterKioskButtonsController extends AppController {

    var $name = 'MasterKioskButtons';
    var $helpers = array('Tree');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Security->disabledFields = array('MasterKioskButton.parent_id');
		if($this->Auth->user()) {
			$this->Auth->allow('admin_get_button_path');
		}
	}

    function admin_index($id = null) {
    	$this->layout = 'default_bootstrap';
		$this->_setTreeData($id);
    }

    function admin_add() {
	if (!empty($this->data)) {
	    $parent = $this->MasterKioskButton->find('first',
		    array('conditions' => array('MasterKioskButton.id' => $this->data['MasterKioskButton']['parent_id'])));
	    $parent2 = $this->MasterKioskButton->find('first',
		    array('conditions' => array('MasterKioskButton.id' => $parent['MasterKioskButton']['parent_id'])));
	    $parent3 = $this->MasterKioskButton->find('first',
		    array('conditions' => array('MasterKioskButton.id' => $parent2['MasterKioskButton']['parent_id'])));
	    if (isset($parent3['MasterKioskButton']) && $parent3['MasterKioskButton']['parent_id'] == '') {
		$this->Session->setFlash(__('Buttons cannot be more than three levels deep', true), 'flash_failure');
		$this->redirect($this->referer());
	    }
	    if ($this->data['MasterKioskButton']['parent_id'] == '') {
		$this->data['MasterKioskButton']['parent_id'] = null;
	    }
	    $button = $this->MasterKioskButton->find('first', array(
		'conditions' => array(
		    'MasterKioskButton.parent_id' => $this->data['MasterKioskButton']['parent_id'],
		    'MasterKioskButton.name' => $this->data['MasterKioskButton']['name']),
		    'MasterKioskButton.deleted' => 1));

	    if($button)
	    {
			$this->data['MasterKioskButton']['id'] = $button['MasterKioskButton']['id'];
			$this->data['MasterKioskButton']['deleted'] = 0;
	    }
	    else
	    {
			$this->MasterKioskButton->create();
	    }

	    if ($this->MasterKioskButton->save($this->data)) {
		$this->Transaction->createUserTransaction('Kiosk', null, null,
			'Added master kiosk button ' . $this->data['MasterKioskButton']['name']);
		$this->Session->setFlash(__('The kiosk button has been saved', true), 'flash_success');
		$this->redirect($this->referer());
	    } else {
		$this->Session->setFlash(__('The kiosk button could not be saved. Please, try again.', true), 'flash_failure');
	    }
	}
	$this->_setTreeData();
	$this->render('/master_kiosk_buttons/admin_index/');
    }

    function admin_edit($id = null) {
	if (!$id && empty($this->data)) {
	    $this->Session->setFlash(__('Invalid kiosk button', true), 'flash_failure');
	    $this->redirect(array('controller' => 'kiosks', 'action' => 'index'));
	}
	if (!empty($this->data)) {
	    if ($this->MasterKioskButton->save($this->data)) {
		$this->Transaction->createUserTransaction('Kiosk', null, null,
			'Edited master kiosk button ' . $this->data['MasterKioskButton']['name']);
		$this->Session->setFlash(__('The kiosk button has been saved', true), 'flash_success');
		$this->redirect(array('action' => 'index', $this->data['MasterKioskButton']['kiosk_id']));
	    } else {
		$this->Session->setFlash(__('The kiosk button could not be saved. Please, try again.', true), 'flash_failure');
		$this->redirect(array('action' => 'index', $this->data['MasterKioskButton']['kiosk_id']));
	    }
	}
	if (empty($this->data)) {
	    $this->MasterKioskButton->recursive = 0;
 	    $this->data = $this->MasterKioskButton->read(null, $id);
	    $this->set('count', $this->MasterKioskButton->find('count', array('conditions' => array(
		'MasterKioskButton.parent_id' => $id,
		'MasterKioskButton.deleted' => 0
	    ))));
	}
    }

    function admin_delete($id = null) {
	if (!$id) {
	    $this->Session->setFlash(__('Invalid id for kiosk button', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	$count = $this->MasterKioskButton->find('count',
		array('conditions' => array(
		    'MasterKioskButton.parent_id' => $id,
		    'MasterKioskButton.deleted' => 0 )));
	if ($count > 0) {
	    $this->Session->setFlash(__('Cannot delete button that has children', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	$this->loadModel('KioskButton');
	if ($this->MasterKioskButton->delete($id) && $this->KioskButton->deleteAll(array('KioskButton.id' => $id))) {
	    $button = $this->MasterKioskButton->findById($id);
	    $this->Transaction->createUserTransaction('Kiosk', null, null,
			'Deleted master kiosk button ' . $button['MasterKioskButton']['name']);
	    $this->Session->setFlash(__('Kiosk button deleted', true), 'flash_success');
	    $this->redirect(array('action' => 'index'));
	}
	$this->Session->setFlash(__('Kiosk button was not deleted', true), 'flash_failure');
	$this->redirect(array('action' => 'index'));
    }

    function _setTreeData() {
		$this->MasterKioskButton->recursive = -1;
		$data = $this->MasterKioskButton->find('threaded', array(
			    'conditions' => array(
					'MasterKioskButton.deleted !=' => 1),
			    	'order' => array('MasterKioskButton.id', 'MasterKioskButton.id DESC')
			    ));

		$this->set('data', $data);
    }

	function admin_get_button_path($id=null) {
		if($this->RequestHandler->isAjax()) {
			$this->loadModel('MasterKioskButton');	
			$buttons = $this->MasterKioskButton->getPath($id);
			if($buttons) {
				$data['success'] = true;
				$i = 0;
				foreach($buttons as $button) {
					$data['buttons'][$i]['id'] = $button['MasterKioskButton']['id'];
					$data['buttons'][$i]['name'] = $button['MasterKioskButton']['name'];
					$i++;
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}
}

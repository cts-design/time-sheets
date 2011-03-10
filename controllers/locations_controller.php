<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

class LocationsController extends AppController {

	var $name = 'Locations';
	
	function index() {
		$this->Location->recursive = 0;
		$locations = $this->Location->find('all');
		
		$this->set(compact('locations'));
	}
	
	function beforeFilter(){
		parent::beforeFilter();
		if($this->Auth->user() && $this->Auth->user('role_id') >= 2){
			$this->Auth->allow('admin_get_location_list');
		}
	}

	function admin_index() {
		$this->Location->recursive = 0;
		$this->set('locations', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Location->create();
			if ($this->Location->save($this->data)) {
				$this->Session->setFlash(__('The location has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The location could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid location', true));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Location->save($this->data)) {
				$this->Session->setFlash(__('The location has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The location could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Location->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for location', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Location->delete($id)) {
			$this->Session->setFlash(__('Location deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Location was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}

	function admin_get_location_list() {
	    if ($this->RequestHandler->isAjax()) {
			$locations = $this->Location->find('all');
			$i = 0;
			foreach($locations as $location) {
			    $data['locations'][$i]['id'] = $location['Location']['id'];
			    $data['locations'][$i]['name'] = $location['Location']['name'];
			    $i++;
			}
			if(!empty($data)){
				$data['success'] = true;
			}
			else {
				$data['success'] = false;
			}
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');			
	    }
	}
}

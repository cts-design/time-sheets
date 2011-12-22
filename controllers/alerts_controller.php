<?php

/**
 * Alerts Controller
 *
 * @package AtlasV3
 * @author  Daniel Nolan
 * @copyright 2011 Complete Technology Solutions
 */

class AlertsController extends AppController {

	public $name = 'Alerts';
		
	public function beforeFilter() {
		parent::beforeFilter();
		if($this->Acl->check(array(
			'model' => 'User', 
			'foreign_key' => $this->Auth->user('id')), 'Alerts/admin_index', '*')) {
				$this->Auth->allow(
				'admin_toggle_email', 
				'admin_toggle_disabled', 
				'admin_get_alert_types', 
				'admin_delete');
	    }
	}
	
	public function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$alerts = $this->Alert->find('all', array(
				'conditions' => array('Alert.user_id' => $this->Auth->user('id'))));
			if($alerts) {
				$i = 0;
				$bools = array(0 => false, 1 => true);
				foreach($alerts as $alert) {
					$data['alerts'][$i] = $alert['Alert'];				
					$data['alerts'][$i]['type'] = Inflector::humanize($data['alerts'][$i]['type']);
					$data['alerts'][$i]['send_email'] = $bools[$data['alerts'][$i]['send_email']];
					$data['alerts'][$i]['disabled'] = $bools[$data['alerts'][$i]['disabled']];				
					$i++;
				}
			}
			else {
				$data['alerts'] = array();
			}
			$data['success'] = true;
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');	
		} 
	}

	public function admin_add_self_sign_alert() {
		if($this->RequestHandler->isAjax())	{
			$this->data['Alert']['name'] = $this->params['form']['name'];
			$this->data['Alert']['type'] = 'self_sign';
			$this->data['Alert']['user_id'] = $this->Auth->user('id');
			$this->data['Alert']['location_id'] = $this->params['form']['location'];
			if(isset($this->params['form']['send_email'])) {
				$this->data['Alert']['send_email'] = 1;
			}
			if(!isset($this->params['form']['level2']) && !isset($this->params['form']['level3'])) {
				$this->data['Alert']['watched_id'] = $this->params['form']['level1'];
			}
			elseif(isset($this->params['form']['level2']) && !isset($this->params['form']['level3'])) {
				$this->data['Alert']['watched_id'] = $this->params['form']['level2'];
			}
			elseif(isset($this->params['form']['level2']) && isset($this->params['form']['level3'])) {
				$this->data['Alert']['watched_id'] = $this->params['form']['level2'];
			}
			if($this->Alert->save($this->data)) {
				$id = $this->Alert->getLastInsertId();
				$data['success'] = true;
				$data['message'] = 'Alert added successfully';
				$this->Transaction->createUserTransaction(
					'Alerts', 
					$this->Auth->user('id'),
					$this->Auth->user('location_id'), 
					'Added Self Sign alert. name: ' . $this->data['Alert']['name'] . ' id: ' . $id 
				);				
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to add alert, please try again.';					
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_toggle_email() {
		if($this->RequestHandler->isAjax()) {
			if(isset($this->params['form']['id'])) {
				$this->data['Alert']['id'] = $this->params['form']['id'];
				if($this->params['form']['send_email'] === 'true') {
					$this->data['Alert']['send_email'] = 1;
				}
				else {
					$this->data['Alert']['send_email'] = 0;
				}
				if($this->Alert->save($this->data))	{
					$alert = $this->Alert->read(null, $this->params['form']['id']);
					$data['success'] = true;
					$status = 'Disabled';
					if($alert['Alert']['send_email']) {
						$status = 'Enabled';
					}
					$this->Transaction->createUserTransaction(
						'Alerts', 
						$this->Auth->user('id'),
						$this->Auth->user('location_id'), 
						$status . ' email for Self Sign alert, name: ' . $alert['Alert']['name'] . ' id: ' . $alert['Alert']['id'] 
					);						
					
				}
				else $data['success'] = false;
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}
	
	public function admin_toggle_disabled() {
		if($this->RequestHandler->isAjax()) {
			if(isset($this->params['form']['id'])) {
				$this->data['Alert']['id'] = $this->params['form']['id'];
				if($this->params['form']['disabled'] === 'true') {
					$this->data['Alert']['disabled'] = 1;
				}
				else {
					$this->data['Alert']['disabled'] = 0;
				}
				if($this->Alert->save($this->data))	{
					$alert = $this->Alert->read(null, $this->params['form']['id']);
					$data['success'] = true;
					$status = 'Disabled';
					if(!$alert['Alert']['disabled']) {
						$status = 'Enabled';
					}
					$this->Transaction->createUserTransaction(
						'Alerts', 
						$this->Auth->user('id'),
						$this->Auth->user('location_id'), 
						$status . ' Self Sign alert, name: ' . $alert['Alert']['name'] . ' id: ' . $alert['Alert']['id'] 
					);					
				}
				else $data['success'] = false;
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}		
	}
	
	public function admin_get_alert_types() {
		if($this->RequestHandler->isAjax()) {
			// if adding a new alert add action to controller, 
			// it needs to be added to the available types array
			$avaliableTypes = array(
				0 => array(
					'action' => 'Alerts/admin_add_self_sign_alert',
					'label' => 'Self Sign',
					'id' => 'selfSignAlertFormPanel'));
			$data['types'] = array();	
			foreach($avaliableTypes as $k => $v) {
				if($this->Acl->check(array(
					'model' => 'User', 
					'foreign_key' => $this->Auth->user('id')), $v['action'], '*')) {
						$data['types'][$k] = array('label' => $v['label'], 'id' => $v['id']);
			    }				
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');	
		}
	}

	public function admin_delete() {
		if($this->RequestHandler->isAjax()) {
			if(isset($this->params['form']['id'])) {
				if($this->Alert->delete($this->params['form']['id'])){
					$data['success'] = true;					
					$data['message'] = 'Alert deleted successfully';
					$this->Transaction->createUserTransaction(
						'Alerts', 
						$this->Auth->user('id'),
						$this->Auth->user('location_id'), 
						'Deleted Self Sign alert, id: ' . $this->params['form']['id']
					);					
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to delete alert at this time.';
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Invalid alert id.';				
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');	
		}
	}
}
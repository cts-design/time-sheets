<?php
class AlertsController extends AppController {

	var $name = 'Alerts';

	function admin_index() {
	}

	function admin_add_self_sign_alert() {
		if($this->RequestHandler->isAjax())	{
			//TODO add security check to make sure a admin or the logged in user is adding the alert
			ChromePhp::log($this->params);
			$this->data['Alert']['name'] = $this->params['form']['name'];
			$this->data['Alert']['type'] = 'selfSign';
			$this->data['Alert']['user_id'] = $this->params['form']['user_id'];
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
				$data['success'] = true;
				$data['message'] = 'Alert added successfully';				
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to add alert, please try again.';					
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid alert', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Alert->save($this->data)) {
				$this->Session->setFlash(__('The alert has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The alert could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Alert->read(null, $id);
		}
		$users = $this->Alert->User->find('list');
		$this->set(compact('users'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for alert', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Alert->delete($id)) {
			$this->Session->setFlash(__('Alert deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Alert was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>
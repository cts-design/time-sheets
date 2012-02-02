<?php
class EmployerVerificationsController extends AppController {

	var $name = 'EmployerVerifications';

	function beforeFilter() {
		parent::beforeFilter();
		$this->Security->validatePost = false;
		$this->Auth->allow('add', 'thank_you');
	}

	function add() {
		if (!empty($this->data)) {
			$this->EmployerVerification->create();
			if ($this->EmployerVerification->save($this->data)) {
				$this->Session->setFlash(__('The employer verification has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'thank_you'));
			} else {
				$this->Session->setFlash(__('The employer verification could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function thank_you() {}
	function admin_index() {}
		
	function admin_read() {
		$employerVerifications = $this->EmployerVerification->find('all');
        $i = 0;
        foreach ($employerVerifications as $key => $value) {
                $data['employer_verifications'][] = $value['EmployerVerification'];
                $i++;
        }
        
        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
	}
		
	function admin_destroy() {
        $id = json_decode($this->params['form']['employer_verifications'], true);
        $id = intval($id['id']);

        if ($this->EmployerVerification->delete($id)) {
                $data['success'] = true;
                $this->Transaction->createUserTransaction('EmployerVerification', null, null,
                                        'Delete employer verification id ' . $id);
        } else {
                $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
	}
}
?>
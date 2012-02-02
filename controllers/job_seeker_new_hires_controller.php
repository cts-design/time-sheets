<?php
class JobSeekerNewHiresController extends AppController {

	var $name = 'JobSeekerNewHires';

	function beforeFilter() {
		parent::beforeFilter();
		$this->Security->validatePost = false;
		$this->Auth->allow('add', 'thank_you');
	}

	function add() {
		if (!empty($this->data)) {
			$this->JobSeekerNewHire->create();
			if ($this->JobSeekerNewHire->save($this->data)) {
				$this->Session->setFlash(__('The job seeker new hire has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'thank_you'));
			} else {
				$this->Session->setFlash(__('The job seeker new hire could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function thank_you() {}
	function admin_index() {}
		
	function admin_read() {
		$jobSeekerNewHires = $this->JobSeekerNewHire->find('all');
        $i = 0;
        foreach ($jobSeekerNewHires as $key => $value) {
                $data['job_seeker_new_hires'][] = $value['JobSeekerNewHire'];
                $i++;
        }
        
        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
	}
		
	function admin_destroy() {
        $id = json_decode($this->params['form']['job_seeker_new_hires'], true);
        $id = intval($id['id']);

        if ($this->JobSeekerNewHire->delete($id)) {
                $data['success'] = true;
                $this->Transaction->createUserTransaction('JobSeekerNewHire', null, null,
                                        'Delete job seeker new hire id ' . $id);
        } else {
                $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
	}
}
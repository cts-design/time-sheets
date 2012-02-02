<?php
class JobOrderFormsController extends AppController {

	var $name = 'JobOrderForms';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Security->validatePost = false;
		$this->Auth->allow('add', 'thank_you');
	}

	function add() {
		$this->set('states', $this->states);
		if (!empty($this->data)) {
			$this->JobOrderForm->create();

			$file_upload = $this->data['JobOrderForm']['job_description'];
			$success = $this->admin_upload($file_upload);
			if ($success) {
				$this->data['JobOrderForm']['job_description'] = $success;
			}

			if ($this->JobOrderForm->save($this->data)) {
				$this->redirect(array('action' => 'thank_you'));
			} else {
				$this->Session->setFlash(__('The job order form could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function thank_you() {}
	function admin_index() {}
		
	function admin_read() {
		$jobOrderForms = $this->JobOrderForm->find('all');
        $i = 0;
        foreach ($jobOrderForms as $key => $value) {
                $data['job_order_forms'][] = $value['JobOrderForm'];
                $i++;
        }
        
        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
	}
		
	function admin_delete() {
        $id = json_decode($this->params['form']['job_order_forms'], true);
        $id = intval($id['id']);

        if ($this->JobOrderForm->delete($id)) {
                $data['success'] = true;
                $this->Transaction->createUserTransaction('JobOrderForms', null, null,
                                        'Delete job order form submission id ' . $id);
        } else {
                $data['success'] = false;
        }

        $this->set('data', $data);
        return $this->render(null, null, '/elements/ajaxreturn');
	}
	
	function admin_upload($file) {

		// get the document relative path to the inital storage folder
	    $abs_path = WWW_ROOT . 'files/public/job_order_forms/';
	    $rel_path = '/files/public/job_order_forms/';
	    $file_ext = '';
	    $filename = '';

	    switch($file['type']) {
	        case 'application/pdf':
	            $file_ext = '.pdf';
	            break;
	        case 'application/msword':
	            $file_ext = '.doc';
	            break;
	        case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
	            $file_ext = '.docx';
	            break;
	    }

	    $filename = date('YmdHis') . $file_ext;

	    // check to see if the directory exists
	    if (!is_dir($abs_path)) {
	        mkdir($abs_path);
	    }

	    if (!file_exists($abs_path . $filename)) {
	        $full_url = $abs_path . $filename;
	        $url = $rel_path . $filename;

	        if (!move_uploaded_file($file['tmp_name'], $url)) {
	         	return false;
			} else {
				return $url;
			}
	    }

		return $url;
	}
}
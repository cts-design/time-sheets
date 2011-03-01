<?php
class HotJobsController extends AppController {

	var $name = 'HotJobs';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	function index() {
		$this->HotJob->recursive = 0;
		$this->set('hotJobs', $this->paginate());
	}

	function admin_index() {
		$this->HotJob->recursive = 0;
		$this->set('hotJobs', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			if ($this->data['HotJob']['file']['error'] === 4) {
				unset($this->data['HotJob']['file']);
			} else {
				$file = $this->_uploadFile();
	            if (!$file) {
	                $this->Session->setFlash(__('The file could not be uploaded. Please, try again.', true), 'flash_failure');
	                $this->redirect(array('action' => 'index'));
	            }
	            $this->data['HotJob']['file'] = Configure::read('URL') . $file;	
			}
			$this->HotJob->create();
			
			if ($this->HotJob->save($this->data)) {
				$this->Session->setFlash(__('The hot job has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hot job could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid hot job', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->data['HotJob']['file']['error'] === 4) {
				unset($this->data['HotJob']['file']);
			}

			if ($this->HotJob->save($this->data)) {
				$this->Session->setFlash(__('The hot job has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The hot job could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->HotJob->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for hot job', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->HotJob->delete($id)) {
			$this->Session->setFlash(__('Hot job deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Hot job was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	
	function apply($jobId) {
		if (!$jobId) {
			$this->Session->setFlash(__('Invalid job id', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
	}
	
    function _uploadFile() {
        // get the document relative path to the inital storage folder
        $abs_path = WWW_ROOT . 'files/public/hot_jobs/';
        $rel_path = 'files/public/hot_jobs/';
        $file_ext = '';
        $filename = '';

        switch($this->data['HotJob']['file']['type']) {
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

            if (!move_uploaded_file($this->data['HotJob']['file']['tmp_name'], $url)) {
                return false;
            }
        }

        return $url;
    }
}
?>
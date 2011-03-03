<?php
class FeaturedEmployersController extends AppController {

	var $name = 'FeaturedEmployers';

	function index() {
		$this->FeaturedEmployer->recursive = 0;
		$this->set('featuredEmployers', $this->paginate());
	}

	function add() {
		if (!empty($this->data)) {
			$this->FeaturedEmployer->create();
			if ($this->FeaturedEmployer->save($this->data)) {
				$this->Session->setFlash(__('The featured employer has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The featured employer could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid featured employer', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->FeaturedEmployer->save($this->data)) {
				$this->Session->setFlash(__('The featured employer has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The featured employer could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->FeaturedEmployer->read(null, $id);
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for featured employer', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->FeaturedEmployer->delete($id)) {
			$this->Session->setFlash(__('Featured employer deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Featured employer was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	function admin_index() {
		$this->FeaturedEmployer->recursive = 0;
		$this->set('featuredEmployers', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			if ($this->data['FeaturedEmployer']['image']['error'] === 4) {
				unset($this->data['FeaturedEmployer']['file']);
			} else {
				$file = $this->_uploadFile();
	            if (!$file) {
	                $this->Session->setFlash(__('The image could not be uploaded. Please, try again.', true), 'flash_failure');
	                $this->redirect(array('action' => 'index'));
	            }
	            $this->data['FeaturedEmployer']['image'] = Configure::read('URL') . $file;	
			}
			$this->FeaturedEmployer->create();
			if ($this->FeaturedEmployer->save($this->data)) {
				$this->Session->setFlash(__('The featured employer has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The featured employer could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid featured employer', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->FeaturedEmployer->save($this->data)) {
				$this->Session->setFlash(__('The featured employer has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The featured employer could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->FeaturedEmployer->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for featured employer', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->FeaturedEmployer->delete($id)) {
			$this->Session->setFlash(__('Featured employer deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Featured employer was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	
	function _uploadFile() {
        // get the document relative path to the inital storage folder
        $abs_path = WWW_ROOT . 'img/public/featured_employers/';
        $rel_path = 'img/public/featured_employers/';
        $file_ext = '';
        $filename = '';

        switch($this->data['FeaturedEmployer']['image']['type']) {
            case 'image/png':
                $file_ext = '.png';
                break;
            case 'image/jpeg':
                $file_ext = '.jpg';
                break;
			case 'image/gif':
				$file_ext = '.gif';
            case 'image/bmp':
                $file_ext = '.bmp';
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
            if (!move_uploaded_file($this->data['FeaturedEmployer']['image']['tmp_name'], $url)) {
                return false;
            }
        }

        return $url;
    }	
}
?>
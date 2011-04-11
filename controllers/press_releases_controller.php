<?php
class PressReleasesController extends AppController {

	var $name = 'PressReleases';

        function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('index');
        }

	function index() {
		$this->PressRelease->recursive = 0;
		$this->set('pressReleases', $this->paginate());
	}

	function admin_index() {
		$this->PressRelease->recursive = 0;
		$this->set('pressReleases', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
                    $file = $this->_uploadPressRelease();
                    if (!$file) {
                        $this->Session->setFlash(__('The press release could not be uploaded. Please, try again.', true), 'flash_failure');
                        $this->redirect(array('action' => 'index'));
                    }
                    $this->data['PressRelease']['file'] = Configure::read('URL') . $file;
                    $this->PressRelease->create();
                    
                    if ($this->PressRelease->save($this->data)) {
                            $this->Transaction->createUserTransaction('CMS', null, null,
                                        'Created press release ID ' . $this->PressRelease->id);
                            $this->Session->setFlash(__('The press release has been saved', true), 'flash_success');
                            $this->redirect(array('action' => 'index'));
                    } else {
                            $this->Session->setFlash(__('The press release could not be saved. Please, try again.', true), 'flash_failure');
                    }
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid press release', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->PressRelease->save($this->data)) {
                                $this->Transaction->createUserTransaction('CMS', null, null,
                                        'Edited press release ID ' . $id);
				$this->Session->setFlash(__('The press release has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The press release could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->PressRelease->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for press release', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->PressRelease->delete($id)) {
                        $this->Transaction->createUserTransaction('CMS', null, null,
                                        'Deleted press release ID ' . $id);
			$this->Session->setFlash(__('Press release deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		} else {
			$this->Session->setFlash(__('Press release was not deleted', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));	
		}
	}

        function _uploadPressRelease() {
            // get the document relative path to the inital storage folder
            $abs_path = WWW_ROOT . 'files/public/press_releases/';
            $rel_path = 'files/public/press_releases/';
            $file_ext = '';
            $filename = '';
            
            switch($this->data['PressRelease']['file']['type']) {
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

                if (!move_uploaded_file($this->data['PressRelease']['file']['tmp_name'], $url)) {
                    return false;
                }
            }
            
            return $url;
        }
}
?>
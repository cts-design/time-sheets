<?php
class RfpsController extends AppController {

	var $name = 'Rfps';

	function index() {
		$this->Rfp->recursive = 0;
		$this->set('rfps', $this->paginate());
	}

	function admin_index() {
		$this->Rfp->recursive = 0;
		$this->set('rfps', $this->paginate());
	}
	
	function admin_data_delegate() {
		if($this->RequestHandler->isAjax()) {
			$params = $this->params['form'];
			
			FireCake::log($this->params);
			
			switch ($params['xaction']) {
				case 'read':
					break;
			}
	    }		
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Rfp->create();
			if ($this->Rfp->save($this->data)) {
				$this->Rfp->createUserTransaction('CMS', null, null,
                                        'Created RFP ID ' . $this->Rfp->id);
				$this->Session->setFlash(__('The rfp has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rfp could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid rfp', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->Rfp->save($this->data)) {
				$this->Rfp->createUserTransaction('CMS', null, null,
                                        'Edited RFP ID ' . $id);
				$this->Session->setFlash(__('The rfp has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The rfp could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Rfp->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for rfp', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Rfp->delete($id)) {
			$this->Rfp->createUserTransaction('CMS', null, null,
                                        'Deleted RFP ID ' . $id);
			$this->Session->setFlash(__('Rfp deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Rfp was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
	
	function _uploadFile() {
        // get the document relative path to the inital storage folder
        $abs_path = WWW_ROOT . 'files/public/rfps/';
        $rel_path = 'files/public/rfps/';
        $file_ext = '';
        $filename = '';

        switch($this->data['Rfp']['file']['type']) {
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

            if (!move_uploaded_file($this->data['Rfp']['file']['tmp_name'], $url)) {
                return false;
            }
        }

        return $url;
    }
}
?>
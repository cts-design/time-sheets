<?php
class ChairmanReportsController extends AppController {

	var $name = 'ChairmanReports';

        function beforeFilter() {
            parent::beforeFilter();
            $this->Auth->allow('index');
        }

	function index() {
		$this->ChairmanReport->recursive = 0;
		$this->set('chairmanReports', $this->paginate());
	}

	function admin_index() {
		$this->ChairmanReport->recursive = 0;
		$this->set('chairmanReports', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
                        $file = $this->_uploadChairmanReport();
                        if (!$file) {
                            $this->Session->setFlash(__('The chairman report could not be uploaded. Please, try again.', true), 'flash_failure');
                            $this->redirect(array('action' => 'index'));
                        }
                        $this->data['ChairmanReport']['file'] = Configure::read('URL') . $file;
			$this->ChairmanReport->create();
			if ($this->ChairmanReport->save($this->data)) {
				$this->Session->setFlash(__('The chairman report has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The chairman report could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid chairman report', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ChairmanReport->save($this->data)) {
				$this->Session->setFlash(__('The chairman report has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The chairman report could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ChairmanReport->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for chairman report', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ChairmanReport->delete($id)) {
			$this->Session->setFlash(__('Chairman report deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Chairman report was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}

        function _uploadChairmanReport() {
            // get the document relative path to the inital storage folder
            $abs_path = WWW_ROOT . 'files/public/chairman_reports/';
            $rel_path = 'files/public/chairman_reports/';
            $file_ext = '';
            $filename = '';

            switch($this->data['ChairmanReport']['file']['type']) {
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

                if (!move_uploaded_file($this->data['ChairmanReport']['file']['tmp_name'], $url)) {
                    return false;
                }
            }

            return $url;
        }
}
?>
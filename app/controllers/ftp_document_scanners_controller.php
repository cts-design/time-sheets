<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

class FtpDocumentScannersController extends AppController {

	var $name = 'FtpDocumentScanners';

	function admin_index() {
		$this->FtpDocumentScanner->recursive = 0;
		$this->set('ftpDocumentScanners', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->FtpDocumentScanner->create();
			if ($this->FtpDocumentScanner->save($this->data)) {
				$this->Session->setFlash(__('The ftp document scanner has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ftp document scanner could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		$locations = $this->FtpDocumentScanner->Location->find('list');
		$this->set(compact('locations'));
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid ftp document scanner', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->FtpDocumentScanner->save($this->data)) {
				$this->Session->setFlash(__('The ftp document scanner has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The ftp document scanner could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->FtpDocumentScanner->read(null, $id);
		}
		$locations = $this->FtpDocumentScanner->Location->find('list');
		$this->set(compact('locations'));
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for ftp document scanner', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->FtpDocumentScanner->delete($id)) {
			$this->Session->setFlash(__('Ftp document scanner deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Ftp document scanner was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}

<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class FiledDocumentsController extends AppController {

    var $name = 'FiledDocuments';
	
	var $components = array('RequestHandler');
	var $helpers = array('Excel');

    var $reasons = array(
		'Duplicate scan' => 'Duplicate scan',
		'Customer info missing' => 'Customer info missing',
		'Multiple customers in same scan' => 'Multiple customers in same scan',
		'Multiple programs in same scan' => 'Multiple programs in same scan',
		'Document unreadable' => 'Document unreadable',
		'Scan is incomplete' => 'Scan is incomplete',
		'Document scanned in error or not needed' => 'Document scanned in error or not needed',
		'Other' => 'Other'
	);

    function admin_index($userId=null) {
		$bool = false;
		$actButton = false;
		if(!$userId) {
		    $userId = $this->Auth->user('id');
		    $bool = true;
		    $conditions = array('FiledDocument.admin_id' => $userId);
		}
		else {
		    $actButton = true;
		    $conditions = array('FiledDocument.user_id' => $userId);
		}
		$this->FiledDocument->recursive = 0;
		$this->paginate = array(
		    'conditions' => $conditions,
		    'order' => array('FiledDocument.id' => 'desc'));
		$this->loadModel('DocumentFilingCategories');
		$filedDocuments = $this->paginate();
		if($bool) {
		    $title_for_layout = 'My Filed Documents';
		}
		else {
		    $user = $this->FiledDocument->User->read(null, $userId);
		    if(!empty($user)) {
			$title_for_layout = 'Filed Documents for ' .
				$user['User']['lastname'] . ', ' .
				$user['User']['firstname'];
		    }
		    else {
			$title_for_layout = 'Filed Documents';
		    }
		}
		$reasons = $this->reasons;
		$this->set(compact('title_for_layout', 'filedDocuments', 'actButton', 'user', 'reasons'));
	    }
	
	function admin_view($id = null) {
		if(!$id) {
		    $this->Session->setFlash(__('Invalid filed document', true), 'flash_failure');
		    $this->redirect(array('action' => 'index'));
		}
		$this->view = 'Media';
		$doc = $this->FiledDocument->read(null, $id);
		$params = array(
		    'id' => $doc['FiledDocument']['filename'],
		    'name' => str_replace('.pdf', '', $doc['FiledDocument']['filename']),
		    'extension' => 'pdf',
		    'cache' => true,
		    'path' => Configure::read('Document.storage.path') .
		    date('Y', strtotime($doc['FiledDocument']['created'])) . '/' .
		    date('m', strtotime($doc['FiledDocument']['created'])) . '/'
		);
		$this->Transaction->createUserTransaction('Storage', null, null,
			'Viewed filed document ID ' . $doc['FiledDocument']['id']);
		$this->set($params);
		return $params;
    }

    function admin_edit($id = null) {
		if(!$id) {
		    $this->Session->setFlash(__('Invalid filed document', true), 'flash_failure');
		    // @FIXME proper redirect. 			    			
		    $this->redirect(array('action' => 'index'));
		}
		if(!empty($this->data)) {
		    $user = $this->FiledDocument->User->find('first', array('conditions' => array(
				    'User.role_id' => 1,
				    'User.firstname' => $this->data['User']['firstname'],
				    'User.lastname' => $this->data['User']['lastname'],
				    'User.ssn' => $this->data['User']['ssn']
				    )));
		    $this->data['FiledDocument']['user_id'] = $user['User']['id'];
		    $this->data['FiledDocument']['last_activity_admin_id'] = $this->Auth->user('id');
		    $this->data['FiledDocument']['location_id'] = $this->Auth->user('location_id');
	
		    if($this->FiledDocument->save($this->data)) {
				$this->Transaction->createUserTransaction('Storage', null, null,
					'Edited filed document ID ' . $id . ' for ' . $user['User']['lastname'] .
					', ' . $user['User']['firstname'] . ' - ' . substr($user['User']['ssn'], 5));
				$this->Session->setFlash(__('The filed document has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index', ($this->data['FiledDocument']['edit_type'] == 'user') ? $user['User']['id'] : ''));
		    }
		    else {
				$this->Session->setFlash(__('The filed document could not be saved. Please, try again.', true), 'flash_failure');
		    }
		}
		if(empty($this->data)) {
		    $this->data = $this->FiledDocument->read(null, $id);
		    $title_for_layout = 'Editing Document for ' .
			    $this->data['User']['lastname'] . ', ' .
			    $this->data['User']['firstname'];
		}
		$cat1 = $this->_getParentDocumentFilingCats();
		$users = $this->FiledDocument->User->find('list');
		$this->set(compact('users', 'cat1', 'title_for_layout'));
    }

    function admin_delete() {
		if(!empty($this->data['FiledDocument']['id'])) {
		    $id = $this->data['FiledDocument']['id'];
		    $this->data['FiledDocument']['last_activity_admin_id'] = $this->Auth->user('id');
		    $this->data['FiledDocument']['deleted_location_id'] = $this->Auth->user('location_id');
		    $data = $this->data;
		    $this->FiledDocument->set($data);
		}
		if(!isset($id)) {
		    $this->Session->setFlash(__('Invalid id for filed document', true), 'flash_failure');
		    $this->redirect($this->referer());
		}
		if(isset($id)) {
			if($this->FiledDocument->delete($id)) {
			    $this->Transaction->createUserTransaction('Storage', null, null,
				    'Deleted filed document ID ' . $id);
			    $this->Session->setFlash(__('Filed document deleted', true), 'flash_success');
			    $this->redirect($this->referer());
			}
			else {
				$this->Session->setFlash(__('Filed document was not deleted', true), 'flash_failure');
				$this->redirect($this->referer());			
			}			
		}
    }

    function admin_upload_document($userId=null) {
		if(!$userId && empty($this->data)) {
		    $this->Session->setFlash(__('Invalid User Id.', true), 'flash_failure');
		    $this->redirect($this->referer());
		}
		if(!empty($this->data)) {
		    if($this->data['FiledDocument']['submittedfile']['type'] != 'application/pdf') {
			$this->Session->setFlash(__('Document must be a .pdf file.', true), 'flash_failure');
			$this->redirect(array('action' => 'upload_document', 'admin' => true, $this->data['User']['id']));
		    }
		    $id = $this->_uploadDocument();
		    if($id) {
			$this->Transaction->createUserTransaction('Storage', null, null,
				trim('Uploaded document ID ' . $id . ' to ' . $this->data['User']['lastname'] .
					', ' . $this->data['User']['firstname'] . ' - ' . substr($this->data['User']['ssn'], 5), ' -'));
			$this->Session->setFlash(__('The document has been filed.', true), 'flash_success');
			$this->redirect(array('action' => 'index', $this->data['User']['id']));
		    }
		    else {
			$this->Session->setFlash(__('Unable to file document.', true), 'flash_failure');
			$this->redirect(array('action' => 'index', $this->data['User']['id']));
		    }
		}
		if(empty($this->data)) {
		    $this->data = $this->FiledDocument->User->read(null, $userId);
		}
		$cat1 = $this->_getParentDocumentFilingCats();
		$title_for_layout = 'Upload Document for ' . $this->data['User']['lastname'] . ', ' . $this->data['User']['firstname'];
		$this->set(compact('user', 'cat1', 'title_for_layout'));
    }

    function _uploadDocument($entryMethod='Upload') {
		// get the document relative path to the inital storage folder
		$path = Configure::read('Document.storage.uploadPath');
		// check to see if the directory for the current year exists
		if(!file_exists($path . date('Y') . '/')) {
		    // if directory does not exist, create it
		    mkdir($path . date('Y'), 0755);
		}
		// add the current year to our path string
		$path .= date('Y') . '/';
		// check to see if the directory for the current month exists
		if(!file_exists($path . date('m') . '/')) {
		    // if directory does not exist, create it
		    mkdir($path . date('m'), 0755);
		}
		// add the current month to our path string
		$path .= date('m') . '/';
		// build our fancy unique filename
		$docName = date('YmdHis') . rand(0, pow(10, 7)) . '.pdf';
		$this->data['FiledDocument']['filename'] = $docName;
		$this->data['FiledDocument']['user_id'] = $this->data['User']['id'];
		$this->data['FiledDocument']['last_activity_admin_id'] = $this->data['FiledDocument']['admin_id'];
		$this->data['FiledDocument']['entry_method'] = $entryMethod;
		if(!move_uploaded_file($this->data['FiledDocument']['submittedfile']['tmp_name'], $path . $docName)) {
		    return false;
		}
		// save an empty record to queued documents to generate unique doc id
		$this->FiledDocument->User->QueuedDocument->create();
		$this->FiledDocument->User->QueuedDocument->save();
		$this->data['FiledDocument']['id'] = $this->FiledDocument->User->QueuedDocument->getLastInsertId();
		// delete the empty record so it does not show up in the queue
		$this->FiledDocument->User->QueuedDocument->delete($this->data['FiledDocument']['id']);
	
		if($this->FiledDocument->save($this->data)) {
		    return $this->data['FiledDocument']['id'];
		}
		else {
		    return false;
		}
    }

    function admin_scan_document($userId=null) {
		$cat1 = $this->_getParentDocumentFilingCats();
		if(!empty($this->data)) {
		    $id = $this->_uploadDocument('Desktop Scan');
		    if($id) {
			$user = $this->FiledDocument->User->read(null, $this->data['User']['id']);
			$this->Transaction->createUserTransaction('Storage', null, null,
				trim('Scanned document ID ' . $id . ' to ' . $user['User']['lastname'] .
					', ' . $user['User']['firstname'] . ' - ' . substr($user['User']['ssn'], 5), ' -'));
			$this->Session->setFlash(__('Scanned document was filed successfully.', true), 'flash_success');
			$this->autoRender = false;
			exit;
		    }
		    else {
			$this->Session->setFlash(__('Unable to save scanned document.', true), 'flash_failure');
			$this->autoRender = false;
			exit;
		    }
		}
		if(empty($this->data)) {
		    $this->data = $this->FiledDocument->User->read(null, $userId);
		    $title_for_layout = 'Scan Document for ' . $this->data['User']['lastname'] . ', ' . $this->data['User']['firstname'];
		}
		$this->set(compact('title_for_layout', 'cat1', 'sessId'));
    }

	function admin_view_all_docs(){
		if($this->RequestHandler->isAjax()){

			if(!empty($this->params['url']['filters'])) {
				$conditions = $this->_setFilters();
				if($conditions) {
					$this->paginate = array('conditions' => $conditions);	
				}
			}			
			if(isset($this->params['url']['sort'])) {
				$this->params['url']['sort'] = str_replace('-', '.', $this->params['url']['sort']);
			}			
			$query = $this->Paginate('FiledDocument');
			$data['docs'] = array();
			if(!empty($conditions)) {
				$data['totalCount'] = $this->FiledDocument->find('count', array('conditions' => $conditions));	
			}
			else{
				$data['totalCount'] = $this->FiledDocument->find('count');	
			}
			if(!empty($query)) {
				foreach($query as $k => $v) {
					$data['docs'][$k]['id'] = $v['FiledDocument']['id'];
					$data['docs'][$k]['User-lastname'] = 
						trim(ucwords($v['User']['lastname']  . ', ' . $v['User']['firstname'] . 
						' - ' . substr($v['User']['ssn'], -4)), ', - ');
					$data['docs'][$k]['Admin-lastname'] = 
						trim(ucwords($v['Admin']['lastname'] .', '. $v['Admin']['firstname']), ', ');
					$data['docs'][$k]['Location-name'] = $v['Location']['name'];
					$data['docs'][$k]['Cat1-name'] = $v['Cat1']['name'];
					$data['docs'][$k]['Cat2-name'] = $v['Cat2']['name'];
					$data['docs'][$k]['Cat3-name'] = $v['Cat3']['name'];
					$data['docs'][$k]['created'] = date('m-d-Y g:i a', strtotime($v['FiledDocument']['created']));
					$data['docs'][$k]['LastActAdmin-lastname'] = 
						trim(ucwords($v['LastActAdmin']['lastname'] . ', ' . $v['LastActAdmin']['firstname']), ', ');
					$data['docs'][$k]['view'] = '<a target="_blank" href="/admin/filed_documents/view/'.$v['FiledDocument']['id'].'">View</a>';
				}
			}
			else {
				$data['docs'] = array();
			}
			$this->set(compact('data'));			
			return $this->render(null, null, '/elements/ajaxreturn');	
		}
		$this->set('title_for_layout', 'Filed Document Archive');
	}

	function admin_report(){			
		if(isset($this->params['url']['filters'])) {
			
			$conditions = $this->_setFilters();
			if($conditions){
				$query = $this->FiledDocument->find('all', array('conditions' => $conditions));
			}
		}
		else {
			$query = $this->FiledDocument->find('all');
		}	
		$title = 'Filed Document Archive Report ' . date('m/d/Y');
		foreach($query as $k => $v) {
			$report[$k]['id'] = $v['FiledDocument']['id'];
			$report[$k]['Customer'] = 
				trim(ucwords($v['User']['lastname'] . ', ' . $v['User']['firstname'] . ' - ' . 
				substr($v['User']['ssn'], -4)), ' ,');
			$report[$k]['Location'] = $v['Location']['name'];
			$report[$k]['Filed By Admin'] = trim(ucwords($v['Admin']['lastname'] . ', '. $v['Admin']['firstname']), ' ,');
			$report[$k]['Main Cat'] = $v['Cat1']['name'];
			$report[$k]['Second Cat'] = $v['Cat2']['name'];
			$report[$k]['Third Cat'] = $v['Cat3']['name'];
			$report[$k]['Last Activity Admin'] = trim(ucwords($v['LastActAdmin']['lastname'] . ', '. $v['LastActAdmin']['firstname']), ' ,');
			$report[$k]['Created'] = date('m/d/y h:i a', strtotime($v['FiledDocument']['created']));		
		}

		if(empty($report[0])) {
		    $this->Session->setFlash('There are no results to generate a report', 'flash_failure');
		    $this->redirect(array('action' => 'view_all_docs'));
			return 'No Report Results';
		}
						
		$data = array(
		    'data' => $report,
		    'title' => $title
		);
		if (isset($this->params['requested'])) {
		 	return $data;
		} 		
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		$this->set($data);
		$this->render('/elements/excelreport');		
	}
		
	function _setFilters() {
		if(isset($this->params['url']['filters'])){
			$filters = json_decode($this->params['url']['filters'], true);
			if($filters['searchType'] == 'last4' && (!empty($filters['cusSearch']))) {
				$conditions['RIGHT (User.ssn , 4) LIKE'] = '%'.$filters['cusSearch'].'%';
			}
			if($filters['searchType'] == 'lastname' && (!empty($filters['cusSearch']))) {
				$conditions['User.lastname LIKE'] = '%'.$filters['cusSearch'].'%';
			}
			if(!empty($filters['fromDate']) && !empty($filters['toDate'])){
				$from = date('Y-m-d H:i:m', strtotime($filters['fromDate'] . '12:00 AM'));
				$to = date('Y-m-d H:i:m', strtotime($filters['toDate'] . '11:59 PM'));
				$conditions['FiledDocument.created BETWEEN ? AND ?'] = array($from, $to);
			}				
			$conditions['FiledDocument.filed_location_id'] = $filters['filed_location_id'];
			$conditions['FiledDocument.admin_id'] = $filters['admin_id'];
			$conditions['FiledDocument.cat_1'] = $filters['cat_1'];
			if(isset($filters['cat_2'])) 
				$conditions['FiledDocument.cat_2'] = $filters['cat_2'];
			if(isset($filters['cat_3'])) 
				$conditions['FiledDocument.cat_3'] = $filters['cat_3'];
			foreach($conditions as $k => $v) {
				if(empty($v)){
					unset($conditions[$k]);
				}
			}
		}
		if(!empty($conditions)) {
			return $conditions;	
		}
		else return false;				
	}

    function _getParentDocumentFilingCats() {
		$this->loadModel('DocumentFilingCategory');
		return $this->DocumentFilingCategory->find('list',
			array('conditions' => array('DocumentFilingCategory.parent_id' => null)));
    }
	
}
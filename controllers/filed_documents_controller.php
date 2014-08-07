<?php

App::import('Core', 'HttpSocket');

class FiledDocumentsController extends AppController {

	public $name = 'FiledDocuments';

	public $components = array('RequestHandler', 'Notifications', 'Email');

	public $helpers = array('Excel');

	public $reasons = array(
		'Duplicate scan' => 'Duplicate scan',
		'Customer info missing' => 'Customer info missing',
		'Multiple customers in same scan' => 'Multiple customers in same scan',
		'Multiple programs in same scan' => 'Multiple programs in same scan',
		'Document unreadable' => 'Document unreadable',
		'Scan is incomplete' => 'Scan is incomplete',
		'Document scanned in error or not needed' => 'Document scanned in error or not needed',
		'Other' => 'Other'
	);

	public function beforeFilter() {
		parent::beforeFilter();

		if ($this->Acl->check(array('model' => 'User',
			'foreign_key' => $this->Auth->user('id')),
		'FiledDocuments/admin_view_all_docs', '*')) {
			$this->Auth->allow('admin_get_all_admins', 'admin_report', 'admin_get_entry_methods');
		}

		if (preg_match('/auditor/i', $this->Session->read('Auth.User.role_name'))) {
			$this->Auth->allow('auditor_view');
		}
	}

	public function admin_index($userId=null) {
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

	public function admin_view($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid filed document', true), 'flash_failure');
			$this->redirect($this->referer());
		}
		$this->view = 'Media';
		$doc = $this->FiledDocument->findById($id);
		if($doc) {
			if($doc['Cat1']['secure']) {
				$secure_admins = json_decode($doc['Cat1']['secure_admins'], true);
			}
			elseif($doc['Cat2']['secure']) {
				$secure_admins = json_decode($doc['Cat2']['secure_admins'], true);
			}
			elseif($doc['Cat3']['secure']) {
				$secure_admins = json_decode($doc['Cat3']['secure_admins'], true);
			}
			if(isset($secure_admins) && is_array($secure_admins)) {
				if($this->Auth->user('role_id') > 3 && !in_array($this->Auth->user('id'), $secure_admins)) {
					$this->Session->setFlash(__('Not authorized to view secure documents', true), 'flash_failure');
					$this->redirect($this->referer());
				}
			}
		}
		$path = null;
		$root = substr(APP, 0, -1);
		$path1 = Configure::read('Document.storage.path') .
			substr($doc['FiledDocument']['filename'], 0, 4) . DS .
			substr($doc['FiledDocument']['filename'], 4, 2) . DS;
		$path2 = Configure::read('Document.storage.path') .
			date('Y', strtotime($doc['FiledDocument']['created'])) . DS .
			date('m', strtotime($doc['FiledDocument']['created'])) . DS;
		if(file_exists($root . $path1 .$doc['FiledDocument']['filename'])) {
			$path = $path1;
		}
		elseif(file_exists($root . $path2 . $doc['FiledDocument']['filename'])) {
			$path = $path2;
		}
		$params = array(
			'id' => $doc['FiledDocument']['filename'],
			'name' => str_replace('.pdf', '', $doc['FiledDocument']['filename']),
			'extension' => 'pdf',
			'cache' => true,
			'path' => $path
		);
		$this->Transaction->createUserTransaction('Storage', null, null,
			'Viewed filed document ID ' . $doc['FiledDocument']['id']);
		$this->set($params);
		return $params;
	}

	public function auditor_view($id = null) {
		$this->view = 'Media';
		$doc = $this->FiledDocument->findById($id);
		$params = array(
			'id' => $doc['FiledDocument']['filename'],
			'name' => str_replace('.pdf', '', $doc['FiledDocument']['filename']),
			'extension' => 'pdf',
			'cache' => true,
			'path' => Configure::read('Document.storage.path') .
			substr($doc['FiledDocument']['filename'], 0, 4) . '/' .
			substr($doc['FiledDocument']['filename'], 4, 2) . '/'
		);
		$this->set($params);
	}

	function admin_edit($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid filed document', true), 'flash_failure');
			// @FIXME proper redirect.
			$this->redirect(array('action' => 'index'));
		}
		if(!empty($this->data)) {
			$this->FiledDocument->User->recursive = -1;
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
				if($this->isModuleEnabled('Programs')) {
					$processedDoc =	$this->_processResponseDoc($this->data, $user);
					if(isset($processedDoc['status'])) {
						$this->loadModel('Program');
						$this->Program->recursive = -1;
						$program = $this->Program->findById($processedDoc['program_id']);
						$this->Notifications->sendProgramResponseStatusAlert($user, $program, $processedDoc['status']);
					}
				}
				$this->Transaction->createUserTransaction('Storage', null, null,
					'Edited filed document ID ' . $id . ' for ' . $user['User']['lastname'] .
					', ' . $user['User']['firstname'] . ' - ' . substr($user['User']['ssn'], -4));
				$this->Session->setFlash(__('The filed document has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index', (isset($this->data['FiledDocument']['edit_type']) == 'user') ? $user['User']['id'] : ''));
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
		$this->set(compact('cat1', 'title_for_layout'));
	}

	function admin_delete() {
		if(!empty($this->data['FiledDocument']['id'])) {
			$id = $this->data['FiledDocument']['id'];
			$this->data['FiledDocument']['last_activity_admin_id'] = $this->Auth->user('id');
			$this->data['FiledDocument']['deleted_location_id'] = $this->Auth->user('location_id');
			$filedDocument = $this->FiledDocument->read(null, $id);
			$this->data['FiledDocument'] = array_merge($filedDocument['FiledDocument'], $this->data['FiledDocument']);
			$this->FiledDocument->set($this->data);
		}
		if(!isset($id)) {
			$this->Session->setFlash(__('Invalid id for filed document', true), 'flash_failure');
			$this->redirect($this->referer());
		}
		if(isset($id)) {
			if($this->FiledDocument->delete($id)) {
				if($this->isModuleEnabled('Programs')) {
					$this->loadModel('ProgramResponseDoc');
					$programResponseDoc = $this->ProgramResponseDoc->find('first', array('conditions' => array('ProgramResponseDoc.doc_id' => $id)));
					if($programResponseDoc) {
						$this->data['ProgramResponseDoc']['id'] = $programResponseDoc['ProgramResponseDoc']['id'];
						$this->data['ProgramResponseDoc']['deleted'] = 1;
						$this->data['ProgramResponseDoc']['deleted_reason'] = $this->data['FiledDocument']['reason'];
						$this->ProgramResponseDoc->save($this->data);
						$user = $this->FiledDocument->User->read(null, $this->data['FiledDocument']['user_id']);
						$this->ProgramResponseDoc->processResponseDoc($this->data, $user);
					}
				}
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
					', ' . $this->data['User']['firstname'] . ' - ' . substr($this->data['User']['ssn'], -4), ' -'));
				$this->sendCusFiledDocAlert($this->data, $id);
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

	function admin_scan_document($userId=null) {
		$cat1 = $this->_getParentDocumentFilingCats();
		if(!empty($this->data)) {
			$id = $this->_uploadDocument('Desktop Scan');
			if($id) {
				$user = $this->FiledDocument->User->read(null, $this->data['User']['id']);
				$this->Transaction->createUserTransaction('Storage', null, null,
					trim('Scanned document ID ' . $id . ' to ' . $user['User']['lastname'] .
					', ' . $user['User']['firstname'] . ' - ' . substr($user['User']['ssn'], -4), ' -'));
				$this->sendCusFiledDocAlert($user, $id);
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
			$query = $this->Paginate('FiledDocument');
			$data['totalCount'] = $this->params['paging']['FiledDocument']['count'];
			$data['docs'] = array();
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
					$data['docs'][$k]['description'] = $v['FiledDocument']['description'];
					$data['docs'][$k]['created'] = date('m-d-Y g:i a', strtotime($v['FiledDocument']['created']));
					$data['docs'][$k]['filed'] = date('m-d-Y g:i a', strtotime($v['FiledDocument']['filed']));
					$data['docs'][$k]['modified'] = date('m-d-Y g:i a', strtotime($v['FiledDocument']['modified']));
					$data['docs'][$k]['LastActAdmin-lastname'] =
						trim(ucwords($v['LastActAdmin']['lastname'] . ', ' . $v['LastActAdmin']['firstname']), ', ');
					$allowed = true;
					if($v['Cat1']['secure']) {
						$allowed = in_array($this->Auth->user('id'), json_decode($v['Cat1']['secure_admins']));
					}
					if($v['Cat2']['secure']) {
						$allowed = in_array($this->Auth->user('id'), json_decode($v['Cat2']['secure_admins']));
					}
					if($v['Cat3']['secure']) {
						$allowed = in_array($this->Auth->user('id'), json_decode($v['Cat3']['secure_admins']));
					}
					if(!$allowed && $this->Auth->user('role_id') > 3) {
						$data['docs'][$k]['view'] = '<img alt="secure" src="/img/icons/lock.png" />';
					}
					else {
						$data['docs'][$k]['view'] =
							'<a target="_blank" href="/admin/filed_documents/view/'.
							$v['FiledDocument']['id'].'">View</a>';
					}
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
				$query = $this->FiledDocument->find('all', array(
					'conditions' => $conditions,
					'limit' => 20000));
			}
		}
		else {
			$query = $this->FiledDocument->find('all', array('limit' => 20000));
		}
		$title = 'Filed Document Archive Report ' . date('m/d/Y');
		if(isset($query)) {
			foreach($query as $k => $v) {
				$report[$k]['id'] = $v['FiledDocument']['id'];
				$report[$k]['First Name'] = ucwords($v['User']['firstname']);
				$report[$k]['Last Name'] = ucwords($v['User']['lastname']);
				$report[$k]['SSN'] = $v['User']['ssn'];
				$report[$k]['Location'] = $v['Location']['name'];
				$report[$k]['Filed By Admin'] = trim(ucwords($v['Admin']['lastname'] . ', '. $v['Admin']['firstname']), ' ,');
				$report[$k]['Main Cat'] = $v['Cat1']['name'];
				$report[$k]['Second Cat'] = $v['Cat2']['name'];
				$report[$k]['Third Cat'] = $v['Cat3']['name'];
				$report[$k]['Description'] = $v['FiledDocument']['description'];
				$report[$k]['Last Activity Admin'] = trim(ucwords($v['LastActAdmin']['lastname'] . ', '. $v['LastActAdmin']['firstname']), ' ,');
				$report[$k]['Created'] = date('m/d/y h:i a', strtotime($v['FiledDocument']['created']));
				$report[$k]['Filed'] = date('m/d/y h:i a', strtotime($v['FiledDocument']['filed']));
				$report[$k]['Modified'] = date('m/d/y h:i a', strtotime($v['FiledDocument']['modified']));
			}
		}
		$this->Transaction->createUserTransaction('Storage', null, null, 'Created a filed document archive Excel report');
		if(empty($report[0])) {
			$this->Session->setFlash(__('There are no results to generate a report', true), 'flash_failure');
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

	function admin_get_all_admins() {
		if($this->RequestHandler->isAjax()) {
			$this->loadModel('Role');
			$auditorRole = $this->Role->find('first', array(
				'conditions' => array(
					'Role.name' => array('Auditor', 'auditor')
				)
			));

			$this->FiledDocument->User->Behaviors->detach('Disableable');
			if(!empty($this->params['form']['query'])) {
				$conditions = array(
					'User.role_id >' => 2,
					'User.role_id <>' => $auditorRole['Role']['id'],
					'User.lastname LIKE' => '%'.$this->params['form']['query'].'%'
				);
			}
			else {
				$conditions = array(
					'User.role_id >' => 2,
					'User.role_id <>' => $auditorRole['Role']['id'],
				);
			}
			$this->FiledDocument->User->recursive = -1;
			$admins = $this->FiledDocument->User->find('all', array(
				'conditions' => $conditions,
				'order' => array('User.lastname' => 'ASC')));
			if($admins) {
				$i = 0;
				foreach($admins as $admin) {
					$data['admins'][$i]['id'] = $admin['User']['id'];
					$data['admins'][$i]['name'] = $admin['User']['lastname'] . ', ' . $admin['User']['firstname'];
					$i++;
				}
				$data['success'] = true;
			}
			else {
				$data['success'] = false;
			}
			$this->set('data', $data);
			$this->render(null, null,  '/elements/ajaxreturn');

		}
	}

	public function admin_get_entry_methods() {
		$entryMethods = $this->FiledDocument->find('all', array('fields' => array('DISTINCT entry_method')));
		$data['entry_methods'] = array();
		if($entryMethods) {
			foreach($entryMethods as $entryMethod) {
				$data['entry_methods'][]['name'] = Inflector::humanize($entryMethod['FiledDocument']['entry_method']);
			}
		}
		$data['success'] = true;
		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	function _uploadDocument($entryMethod='Upload') {
		// get the document relative path to the inital storage folder
		$path = Configure::read('Document.storage.uploadPath');
		// check to see if the directory for the current year exists
		if(!file_exists($path . date('Y') . '/')) {
			// if directory does not exist, create it
			mkdir($path . date('Y'), 0777);
		}
		// add the current year to our path string
		$path .= date('Y') . '/';
		// check to see if the directory for the current month exists
		if(!file_exists($path . date('m') . '/')) {
			// if directory does not exist, create it
			mkdir($path . date('m'), 0777);
		}
		// add the current month to our path string
		$path .= date('m') . '/';
		// build our fancy unique filename
		$docName = date('YmdHis') . rand(0, pow(10, 7)) . '.pdf';
		$this->data['FiledDocument']['filename'] = $docName;
		if(empty($this->data['User']['id'])) {
			return false;
		}
		$this->data['FiledDocument']['user_id'] = $this->data['User']['id'];
		$this->data['FiledDocument']['last_activity_admin_id'] = $this->data['FiledDocument']['admin_id'];
		$this->data['FiledDocument']['entry_method'] = $entryMethod;
		$this->data['FiledDocument']['filed'] = date('Y-m-d H:i:s');
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
			if($this->isModuleEnabled('Programs')) {
				$user = $this->FiledDocument->User->findById($this->data['User']['id']);
				$this->_processResponseDoc($this->data, $user);
			}
			return $this->data['FiledDocument']['id'];
		}
		else {
			return false;
		}
	}

	function _setFilters() {
		if(isset($this->params['url']['filters'])){
			$filters = json_decode($this->params['url']['filters'], true);

			if (isset($filters['cusSearchType1'])) {
				if ($filters['cusSearchType1'] == 'firstname' && (isset($filters['cusSearch1']))) {
					if ($filters['cusScope1'] === 'containing') {
						$conditions['User.firstname LIKE'] = '%'. $filters['cusSearch1'] . '%';
					} else {
						$conditions['User.firstname'] = $filters['cusSearch1'];
					}
				}
				if($filters['cusSearchType1'] == 'fullssn' && (isset($filters['cusSearch1']))) {
					if ($filters['cusScope1'] === 'containing') {
						$conditions['User.ssn LIKE'] = '%'.$filters['cusSearch1'].'%';
					} else {
						$conditions['User.ssn'] = $filters['cusSearch1'];
					}
				}
				if($filters['cusSearchType1'] == 'last4' && (isset($filters['cusSearch1']))) {
					if ($filters['cusScope1'] === 'containing') {
						$conditions['RIGHT (User.ssn , 4) LIKE'] = '%'.$filters['cusSearch1'].'%';
					} else {
						$conditions['RIGHT (User.ssn , 4)'] = $filters['cusSearch1'];
					}
				}
				if($filters['cusSearchType1'] == 'lastname' && (isset($filters['cusSearch1']))) {
					if ($filters['cusScope1'] === 'containing') {
						$conditions['User.lastname LIKE'] = '%'. $filters['cusSearch1'] . '%';
					} else {
						$conditions['User.lastname'] = $filters['cusSearch1'];
					}
				}
			}

			if (isset($filters['cusSearchType2'])) {
				if ($filters['cusSearchType2'] == 'firstname' && (isset($filters['cusSearch2']))) {
					if ($filters['cusScope2'] === 'containing') {
						$conditions['User.firstname LIKE'] = '%'. $filters['cusSearch2'] . '%';
					} else {
						$conditions['User.firstname'] = $filters['cusSearch2'];
					}
				}
				if($filters['cusSearchType2'] == 'fullssn' && (isset($filters['cusSearch2']))) {
					if ($filters['cusScope2'] === 'containing') {
						$conditions['User.ssn LIKE'] = '%'.$filters['cusSearch2'].'%';
					} else {
						$conditions['User.ssn'] = $filters['cusSearch2'];
					}
				}
				if($filters['cusSearchType2'] == 'last4' && (isset($filters['cusSearch2']))) {
					if ($filters['cusScope2'] === 'containing') {
						$conditions['RIGHT (User.ssn , 4) LIKE'] = '%'.$filters['cusSearch2'].'%';
					} else {
						$conditions['RIGHT (User.ssn , 4)'] = $filters['cusSearch2'];
					}
				}
				if($filters['cusSearchType2'] == 'lastname' && (isset($filters['cusSearch2']))) {
					if ($filters['cusScope2'] === 'containing') {
						$conditions['User.lastname LIKE'] = '%'. $filters['cusSearch2'] . '%';
					} else {
						$conditions['User.lastname'] = $filters['cusSearch2'];
					}
				}
			}
			if(isset($filters['fromDate'], $filters['toDate'], $filters['dateType'])){
				$from = date('Y-m-d H:i:m', strtotime($filters['fromDate'] . '12:00 AM'));
				$to = date('Y-m-d H:i:m', strtotime($filters['toDate'] . '11:59 PM'));

				$conditions['FiledDocument.' . $filters['dateType'] . ' BETWEEN ? AND ?'] = array($from, $to);
			}
			if(isset($filters['filed_location_id'])){
				$conditions['FiledDocument.filed_location_id'] = $filters['filed_location_id'];
			}
			if(isset($filters['admin_id'])){
				$conditions['FiledDocument.admin_id'] = $filters['admin_id'];
			}
			if(isset($filters['cat_1'])){
				$conditions['FiledDocument.cat_1'] = $filters['cat_1'];
			}
			if(isset($filters['entry_method'])) {
				$conditions['FiledDocument.entry_method'] = $filters['entry_method'];
			}
			if(isset($filters['cat_2']))
				$conditions['FiledDocument.cat_2'] = $filters['cat_2'];
			if(isset($filters['cat_3']))
				$conditions['FiledDocument.cat_3'] = $filters['cat_3'];
			if(isset($conditions))	 {
				foreach($conditions as $k => $v) {
					if(empty($v)){
						unset($conditions[$k]);
					}
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
			array('conditions' => array('DocumentFilingCategory.parent_id' => null, 'DocumentFilingCategory.disabled' => 0)));
    }
	
	function _processResponseDoc($data, $user) {
		$this->loadModel('ProgramResponse');							
		$processedDoc = $this->ProgramResponse->ProgramResponseDoc->processResponseDoc($data, $user);	

		if(isset($processedDoc['status'])) {
			$this->loadModel('Program');
			$this->Program->recursive = -1;
			$program = $this->Program->findById($processedDoc['program_id']);
			$this->Notifications->sendProgramResponseStatusAlert($user, $program, $processedDoc['status']);
		}
		if(isset($processedDoc['docFiledEmail'])) {
			$this->Notifications->sendProgramEmail($processedDoc['docFiledEmail'], $user);
		}
		if(isset($processedDoc['finalEmail'])) {
			$this->Notifications->sendProgramEmail($processedDoc['finalEmail'], $user);
		}
	}

	private function sendCusFiledDocAlert($user, $docId) {
		$this->loadModel('Alert');
		$data = $this->Alert->getCusFiledDocAlerts($user, $docId);
		if($data) {
			$HttpSocket = new HttpSocket();
			$results = $HttpSocket->post('localhost:3000/new',
				array('data' => $data));
			$to = '';
			foreach($data as $alert) {
				if($alert['send_email']) {
					$to .= $alert['email'] . ',';
				}
			}
			if(!empty($to)) {
				$to = trim($to, ',');
				$this->Email->to = $to;

				$this->loadModel('Setting');
				$cc = $this->Setting->getEmails();

				if(count($cc))
					$this->Email->cc = $cc;

				$this->Email->from = Configure::read('System.email');
				$this->Email->subject = 'Document filed to customer alert';
				$this->Email->send($alert['message'] . "\r\n" . $alert['url']);
			}
		}
	}
}

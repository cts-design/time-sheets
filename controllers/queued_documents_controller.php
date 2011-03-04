<?php

class QueuedDocumentsController extends AppController {

    var $name = 'QueuedDocuments';

    var $lockStatuses = array(
	0 => 'Un-Locked',
	1 => 'Locked'
    );
    //@TODO possibly move to app controller
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

    function beforeFilter() {
	parent::beforeFilter();
	$this->Cookie->name = 'docQueueSearch';
	$this->Cookie->time = 0;
	if($this->Auth->user()) {
	    if($this->Acl->check(array(
			'model' => 'Role',
			'foreign_key' => $this->Auth->user('role_id')), 'QueuedDocuments/admin_index', '*')){
		$this->Auth->allow('admin_view');
	    }
	}
    }

    function admin_index($action=null, $docId=null, $active=null) {
	$canFile = null;
	if(!empty($action) && $action == 'reset') {
	    if($this->RequestHandler->isAjax()) {
		$this->QueuedDocument->checkLocked($this->Auth->user('id'));
		exit;
	    }
	    elseif(!$this->RequestHandler->isAjax()) {
		$this->_resetFilters();
		$this->redirect(array('action' => 'index'));
	    }
	}
	if(!empty($action) && $action == 'resetInactive') {
	    $this->QueuedDocument->checkLocked($this->Auth->user('id'));
	    $this->redirect(array('controller' => 'users', 'action' => 'dashboard', 'admin' => true));
	}
	if(!empty($this->data['User']['qd']) && $this->data['User']['qd'] == 'add' ) {
	    $this->_addCustomer();
	}
	$this->QueuedDocument->recursive = 0;
	$locationId = $this->Cookie->read('location_id');
	$queuedDocId = $this->Cookie->read('queue_category_id');
	$from = $this->Cookie->read('date_from');
	$to = $this->Cookie->read('date_to');
	if(!$this->RequestHandler->isAjax()) {
	    if(!empty($this->data['QueuedDocument']) && empty($this->data['QueuedDocument']['location'])) {
		$this->Session->setFlash(__('You must select a location', true), 'flash_failure');
	    }
	}
	if(!empty($this->data['QueuedDocument'])) {
	    $this->_resetFilters();
	    if($this->data['QueuedDocument']['location'] != 'All') {
		$conditions['QueuedDocument.scanned_location_id'] = $this->data['QueuedDocument']['location'];
	    }
	    $this->Cookie->write('location_id', $this->data['QueuedDocument']['location']);
	    $locationId = $this->data['QueuedDocument']['location'];
	}

	elseif(!empty($locationId) && $locationId != 'All') {
	    $conditions['QueuedDocument.scanned_location_id'] = $locationId;
	}
	if(!empty($this->data['QueuedDocument']['program']) && !empty($this->data['QueuedDocument']['location'])) {
	    $conditions['QueuedDocument.queue_category_id'] = $this->data['QueuedDocument']['program'];
	    $this->Cookie->write('queue_category_id', $this->data['QueuedDocument']['program']);
	    $queuedDocId = $this->data['QueuedDocument']['program'];
	}
	elseif(!empty($queuedDocId)) {
	    $conditions['QueuedDocument.queue_category_id'] = $queuedDocId;
	}
	if(!empty($this->data['QueuedDocument']['date_from']) && !empty($this->data['QueuedDocument']['date_to'])
		&& !empty($this->data['QueuedDocument']['location'])) {
	    $from = date('Y-m-d H:i:m', strtotime($this->data['QueuedDocument']['date_from'] . " 12:00 am"));
	    $to = date('Y-m-d H:i:m', strtotime($this->data['QueuedDocument']['date_to'] . " 11:59 pm"));
	    $this->Cookie->write('date_from', $from);
	    $this->Cookie->write('date_to', $to);
	    $conditions['QueuedDocument.created Between ? AND ?'] = array($from, $to);
	}
	elseif(!empty($from) && !empty($to)) {
	    $conditions['QueuedDocument.created Between ? AND ?'] = array($from, $to);
	}
	if(isset($conditions)) {
	    // @TODO Move the pagianation limits to the config file
	    $this->paginate = array('limit' => 2, 'conditions' => $conditions);
	}
	else {
	    // @TODO Move the pagianation limits to the config file 
	    $this->paginate = array('limit' => 2);
	}
	if(isset($conditions) || $locationId == 'All' || !empty($docId)) {
	    $this->QueuedDocument->checkLocked($this->Auth->user('id'));
	    if(!empty($docId)) {
		$conditions['QueuedDocument.id'] = $docId;
	    }
	    $conditions['QueuedDocument.locked_status'] = 0;
	    $doc = $this->QueuedDocument->find('first',
			    array('conditions' => $conditions, 'order' => array('QueuedDocument.id' => 'asc')));
	    if(!empty($doc['QueuedDocument']['id'])) {
		if($this->QueuedDocument->lockDocument($doc['QueuedDocument']['id'], $this->Auth->user('id'))) {
		    $lockedDoc = $doc;
		    $this->Transaction->createUserTransaction('Storage', null, null , 'Locked document ID '. $doc['QueuedDocument']['id'] );
		}
	    }
	    else
		$lockedDoc = null;
	}
	else {
	    $lockedDoc = null;
	}
	$this->loadModel('DocumentFilingCategory');
	$this->DocumentFilingCategory->recursive = -1;
	if(!empty($from)) {
	    $from = date('m/d/Y', strtotime($from));
	}
	if(!empty($to)) {
	    $to = date('m/d/Y', strtotime($to));
	}
	if($this->Acl->check(array('model' => 'User', 'foreign_key' => $this->Auth->user('id')), 'QueuedDocuments/admin_file_document', '*')) {
	    if($active == null && !empty($conditions)) {
		$active = 2;
	    }
	    $canFile = true;
	}
	else {
	    if($active == null && !empty($conditions)) {
		$active = 1;
	    }
	}
	$locations = $this->QueuedDocument->Location->find('list');
	$locations['All'] = 'All Locations';

	$data = array(
	    'states' => $this->states,
	    'genders' => $this->genders,
	    'statuses' => $this->statuses,
	    'active' => $active,
	    'lockedDoc' => $lockedDoc,
	    'lockStatuses' => $this->lockStatuses,
	    'queuedDocuments' => $this->paginate(),
	    'cat1' => $this->DocumentFilingCategory->find('list',
		    array('conditions' => array('DocumentFilingCategory.parent_id' => null, 'DocumentFilingCategory.disabled' => 0))),
	    'locations' => $locations,
	    'reasons' => $this->reasons,
	    'queueCategories' => $this->QueuedDocument->DocumentQueueCategory->find('list', array('conditions')),
	    'locationId' => $locationId,
	    'queuedDocId' => $queuedDocId,
	    'from' => $from,
	    'to' => $to,
	    'canFile' => $canFile
	);
	if(!empty($lockedDoc['QueuedDocument']['user_id'])) {
	    $data['user'] = $this->QueuedDocument->User->read(null,$lockedDoc['QueuedDocument']['user_id'] );
	}
	if(!empty($lockedDoc['QueuedDocument']['self_scan_cat_id'])) {
	    $data['selfScanCat'] = $this->QueuedDocument->SelfScanCategory->read(null,$lockedDoc['QueuedDocument']['self_scan_cat_id']);
	}
	$this->set($data);
	if($this->RequestHandler->isAjax()) {
	    $this->layout = 'ajax';
	    if(!empty($this->params['named']['page'])) {
		$this->render('/elements/queued_documents/index_table');
	    }
	}
    }

    function admin_reassign_queue() {
	if(empty($this->data['QueuedDocument']['id'])) {
	    $this->Session->setFlash(__('Invalid document id. Please try again.', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	$this->data['QueuedDocument']['last_activity_admin_id'] = $this->Auth->user('id');
	if($this->QueuedDocument->save($this->data)) {
	    $queueCatList = $this->QueuedDocument->DocumentQueueCategory->find('list');
	    $queueCatId = $this->data['QueuedDocument']['queue_category_id'];
	    $this->Transaction->createUserTransaction('Storage', null, null , 
		    'Reassigned document ID '. $this->data['QueuedDocument']['id'] .
		    ' to queue ' . $queueCatList[$queueCatId]);
	    $this->Session->setFlash(__('The queued document has been re-assigned.', true), 'flash_success');
	    $this->redirect(array('action' => 'index'));
	}
	else {
	    $this->Session->setFlash(__('The queued document could not be re-assigned. Please try again.', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
    }

    function admin_view($id = null) {
	$this->view = 'Media';
	$doc = $this->QueuedDocument->read(null, $id);
	$params = array(
	    'id' => $doc['QueuedDocument']['filename'],
	    'name' => str_replace('.pdf', '', $doc['QueuedDocument']['filename']),
	    'extension' => 'pdf',
	    'path' =>  Configure::read('Document.storage.path') .
	    date('Y', strtotime($doc['QueuedDocument']['created'])) . '/' .
	    date('m', strtotime($doc['QueuedDocument']['created'])) . '/'
	);
	$this->set($params);
    }

    function admin_file_document() {
	if(empty($this->data['FiledDocument']['id'])) {
	    $this->Session->setFlash(__('Invalid document Id. Please try again.', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	$user = $this->QueuedDocument->User->find('first', array('conditions' => array(
	    'User.role_id' => 1,
	    'User.firstname' => $this->data['FiledDocument']['firstname'],
	    'User.lastname' => $this->data['FiledDocument']['lastname'],
	    'User.ssn' => $this->data['FiledDocument']['ssn'])));
	if(empty($user)) {
	    $this->Session->setFlash(__('Unable to file document. Please try again.', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	$this->data['FiledDocument']['user_id'] = $user['User']['id'];
	$this->data['FiledDocument']['last_activity_admin_id'] = $this->data['FiledDocument']['admin_id'];
	$this->data['FiledDocument']['filed_location_id'] = $this->Auth->user('location_id');
	if($this->QueuedDocument->User->FiledDocument->save($this->data)) {
	    $this->QueuedDocument->delete($this->data['FiledDocument']['id']);
	    $this->Transaction->createUserTransaction('Storage', null, null ,
		    'Filed document ID '. $this->data['FiledDocument']['id'] .
		    ' to ' . $user['User']['lastname'] . ', ' . $user['User']['firstname'] . ' - '. substr($user['User']['ssn'],'5'));
	    $this->Session->setFlash(__('The document was filed successfully', true), 'flash_success' );
	    $this->redirect(array('action' => 'index'));
	}
	else{
	    $this->Session->setFlash(__('Unable to file document. Please try again.', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
    }

    function admin_delete() {
	if(!empty($this->data['QueuedDocument']['id'])) {
	    $id = $this->data['QueuedDocument']['id'];
	    $this->data['QueuedDocument']['last_activity_admin_id'] = $this->Auth->user('id');
	    $this->data['QueuedDocument']['deleted_location_id'] = $this->Auth->user('location_id');
	}
	if(!$id) {
	    $this->Session->setFlash(__('Invalid id for queued document', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	$data = $this->data;
	$this->QueuedDocument->set($data);
	if($this->QueuedDocument->delete($id, false)) {
	    $this->Transaction->createUserTransaction('Storage', null, null ,
		    'Deleted document ID '. $id .
		    ' from the queue with reason, ' . $this->data['QueuedDocument']['reason']);
	    $this->Session->setFlash(__('Queued document deleted', true), 'flash_success');
	    $this->redirect(array('action' => 'index'));
	}
	else {
	    $this->Session->setFlash(__('Queued document was not deleted', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
    }

    function admin_desktop_scan_document(){

	if(!empty($this->data)) {
	    $id = $this->_uploadDocument('Desktop Scan');
	    if($id) {
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
	$locations = $this->QueuedDocument->Location->find('list');
	$queueCats = $this->QueuedDocument->DocumentQueueCategory->find('list');
	$title_for_layout = 'Desktop Scan Document';
	$this->set(compact('title_for_layout', 'queueCats', 'locations'));
    }

        function _uploadDocument() {
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
	$this->data['QueuedDocument']['filename'] = $docName;
	$this->data['QueuedDocument']['last_activity_admin_id'] = $this->Auth->user('id');
	$this->data['QueuedDocument']['entry_method'] = 'Desktop Scan';
	if(!move_uploaded_file($this->data['QueuedDocument']['submittedfile']['tmp_name'], $path . $docName)) {
	    return false;
	}
	if($this->QueuedDocument->save($this->data)) {
	    return $this->QueuedDocument->getLastInsertId();
	}
	else {
	    return false;
	}
    }

    function _resetFilters() {
	$this->QueuedDocument->checkLocked($this->Auth->user('id'));
	$this->Cookie->destroy();
    }

    function _addCustomer() {
	if(!empty($this->data)) {
	    $this->loadModel('User');
	    $this->User->create();
	    if($this->User->save($this->data)) {
		$this->Transaction->createUserTransaction('Customer',
			null, null, 'Added customer ' . $this->data['User']['firstname'] .
			' ' . $this->data['User']['lastname']) . ' - ' . substr($this->data['User']['ssn'],'5');
		$this->Session->setFlash(__('The customer has been saved', true), 'flash_success');
		$this->redirect(array('action' => 'index'));
	    }
	    else {
		$this->Session->setFlash(__('The customer could not be saved. Please, try again.', true), 'flash_failure');
	    }
	}
    }
}
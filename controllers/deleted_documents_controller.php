<?php

class DeletedDocumentsController extends AppController {

    var $name = 'DeletedDocuments';

    function admin_index($dateRange = null) {
	if(!empty($this->passedArgs['from_date']) && !empty($this->passedArgs['to_date'])) {
	    $fromDate = $this->passedArgs['from_date'];
	    $toDate = $this->passedArgs['to_date'];
	}
	elseif(!empty($this->data['DeletedDocument']['from_date']) && !empty($this->data['DeletedDocument']['to_date'])) {
	    $fromDate = date('Y-m-d G:i:s', strtotime($this->data['DeletedDocument']['from_date'] . '00:00:00'));
	    $toDate = date('Y-m-d G:i:s', strtotime($this->data['DeletedDocument']['to_date'] . '23:59:59'));
	    $this->passedArgs['from_date'] = $fromDate;
	    $this->passedArgs['to_date'] = $toDate;
	}
	elseif($dateRange) {
	    switch($dateRange) {
		case 'yesterday':
		    $fromDate = date('Y-m-d G:i:s', strtotime('-1 day 00:00:00'));
		    $toDate = date('Y-m-d G:i:s', strtotime("-1 day 23:59:59"));
		    break;
		case 'today':
		    $fromDate = date('Y-m-d G:i:s', strtotime('00:00:00'));
		    $toDate = date('Y-m-d G:i:s', strtotime("23:59:59"));
		    break;
		case 'last_7':
		    $fromDate = date('Y-m-d G:i:s', strtotime('-7 day 00:00:00'));
		    $toDate = date('Y-m-d G:i:s', strtotime("23:59:59"));
		    break;
		case 'last_month':
		    $month = date('m') -1;
		    $year = date('Y');
		    $timestamp = strtotime("$year-$month-01");
		    $numberOfDays = date('t', $timestamp);
		    $fromDate = date('Y-m-d G:i:s', strtotime("$year-$month-01"));
		    $toDate = date('Y-m-d G:i:s', strtotime("$year-$month-$numberOfDays 23:59:59"));
		    break;
	    }

	}
	if(isset ($fromDate, $toDate)) {
	    $conditions = array('DeletedDocument.modified BETWEEN ? AND ?' => array($fromDate, $toDate));
	    $this->paginate = array(
		'conditions' => $conditions,
		'limit' => Configure::read('Pagination.deletedDocument.limit'
	    ));
	}
	else {
	    $this->paginate = array('limit' => Configure::read('Pagination.deletedDocument.limit'));
	}
	$this->DeletedDocument->recursive = 0;
	$this->set('deletedDocuments', $this->paginate());
    }

    function admin_view($id = null) {
	if(!$id) {
	    $this->Session->setFlash(__('Invalid deleted document', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	$this->view = 'Media';
	$doc = $this->DeletedDocument->read(null, $id);
	$params = array(
	    'id' => $doc['DeletedDocument']['filename'],
	    'name' => str_replace('.pdf', '', $doc['DeletedDocument']['filename']),
	    'extension' => 'pdf',
	    'path' => Configure::read('Document.storage.path') . '/' .
	    date('Y', strtotime($doc['DeletedDocument']['created'])) . '/' .
	    date('m', strtotime($doc['DeletedDocument']['created'])) . '/'
	);
	$this->Transaction->createUserTransaction('Storage', null, null ,
		'Viewed deleted document ID '. $doc['DeletedDocument']['id']);
	$this->set($params);
    }

    function admin_restore($id = null) {
	if(!$id) {
	    $this->Session->setFlash(__('Invalid id for deleted document', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	$lastActivityAdmin = $this->Auth->user('id');
	$doc = $this->DeletedDocument->read(null, $id);
	if($doc['DeletedDocument']['cat_1'] == null && $doc['DeletedDocument']['cat_2'] == null && 
		$doc['DeletedDocument']['cat_3'] == null) {
	    $doc['QueuedDocument'] = $doc['DeletedDocument'];
	    unset($doc['DeletedDocument']);
	    unset($doc['QueuedDocument']['modified']);
	    unset($doc['QueuedDocument']['deleted_reason']);
	    $doc['QueuedDocument']['last_activity_admin_id'] = $lastActivityAdmin;
	    $this->loadModel('QueuedDocument');
	    if($this->QueuedDocument->save($doc)) {
		$this->DeletedDocument->delete($id);
	    $this->Transaction->createUserTransaction('Storage', null, null ,
		    'Restored deleted document ID '. $id. ' to the document queue.');
		$this->Session->setFlash(__('Document was restored to the document queue successfully', true), 'flash_success');
		$this->redirect(array('action' => 'index'));
	    }
	    else {
		$this->Session->setFlash(__('Unable to restore document', true), 'flash_failure');
	    }
	}
	elseif($doc['DeletedDocument']['user_id'] != null && $doc['DeletedDocument']['cat_1'] != null) {
	    $doc['FiledDocument'] = $doc['DeletedDocument'];
	    unset($doc['DeletedDocument']);
	    unset($doc['FiledDocument']['modified']);
	    unset($doc['FiledDocument']['deleted_reason']);
	    $doc['FiledDocument']['last_activity_admin_id'] = $lastActivityAdmin;
	    $this->loadModel('FiledDocument');
	    if($this->FiledDocument->save($doc)) {
		$this->DeletedDocument->delete($id);
	    $this->Transaction->createUserTransaction('Storage', null, null ,
		    'Restored deleted document ID '. $id . ' to the filed documents.');
		$this->Session->setFlash(__('Document was restored to filed documents successfully', true), 'flash_success');
		$this->redirect(array('action' => 'index'));
	    }
	    else {
		$this->Session->setFlash(__('Unable to restore document', true), 'flash_failure');
	    }
	}
    }

}


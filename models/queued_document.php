<?php
class QueuedDocument extends AppModel {
    var $name = 'QueuedDocument';

    var $belongsTo = array(
	'DocumentQueueCategory' => array(
	    'className' => 'DocumentQueueCategory',
	    'foreignKey' => 'queue_category_id'
	),
	'Location' => array(
	    'className' => 'Location',
	    'foreignKey' => 'scanned_location_id'
	),
	'User' => array(
	    'className' => 'User',
	    'foreignKey' => 'locked_by'
	),
	'LastActAdmin' => array(
		'className' => 'User',
		'foreignKey' => 'last_activity_admin_id',
		'conditions' => '',
		'fields' => '',
		'order' => ''
	),
	'SelfScanCategory' => array(
	    'className' => 'SelfScanCategory',
	    'foreignKey' => 'self_scan_cat_id'
	),
    );
	
	var $validate = array(
		'submittedfile' => array(
			'pdf' => array(
				'rule' =>'isPDF',
				'message' => 'Please supply document in .pdf format.'
			),
			'lessThen1mb' => array(
				'rule' => 'lessThen1mb',
				'message' => 'Document must not be larger then 1mb'
			)
		)
	);

    function  beforeDelete($cascade = true) {
		parent::beforeDelete($cascade);
		if(!empty($this->data['QueuedDocument'])) {
		    $adminId = $this->data['QueuedDocument']['last_activity_admin_id'];
		    $reason = $this->data['QueuedDocument']['reason'];
		    $deletedLocation = $this->data['QueuedDocument']['deleted_location_id'];
		}
		$delDoc = ClassRegistry::init('DeletedDocument');
		$this->recursive = -1;	
		$doc = $this->read(null, $this->id);
		foreach($doc as $k => $v) {
		    $this->data['DeletedDocument'] = $v;
		    if(isset($adminId, $reason, $deletedLocation)) {
				$this->data['DeletedDocument']['last_activity_admin_id'] = $adminId;
				$this->data['DeletedDocument']['deleted_reason'] = $reason;
				$this->data['DeletedDocument']['deleted_location_id'] = $deletedLocation;
		    }
		    unset($this->data['DeletedDocument']['modified']);
		    unset($this->data['QueuedDocument']);
		}
		if(!empty($adminId) && !empty($reason)) {
		   if($delDoc->save($this->data)) {
				return true;
		    }
		    else return false;
		}
		else {
		    return true;
		}
    }

    function checkLocked($userId) {
		$lockedConditions['QueuedDocument.locked_status'] = 1;
		$lockedConditions['QueuedDocument.locked_by'] = $userId;
		$userLockedDoc = $this->find('first', array('conditions' => $lockedConditions));
		if(!empty($userLockedDoc['QueuedDocument']['id'])) {
		    if (!$this->unlockDocument($userLockedDoc['QueuedDocument']['id'])) {
				return false;
		    }
		    else return true;
		}
		else return false;
    }

    function lockDocument($id=null, $userId=null) {
		if($id && $userId) {
		    $this->data['QueuedDocument']['id'] = $id;
		    $this->data['QueuedDocument']['locked_by'] = $userId;
		    $this->data['QueuedDocument']['locked_status'] = 1;
		    if($this->save($this->data)) {
				return true;
		    }
		    else
			return false;
		}
    }

    function unlockDocument($id=null) {
		if($id) {
		    $this->data['QueuedDocument']['id'] = $id;
		    $this->data['QueuedDocument']['locked_by'] = null;
		    $this->data['QueuedDocument']['locked_status'] = 0;
		    if($this->save($this->data)) {
				return true;
		    }
		    else return false;
		}
    }

	function uploadDocument($data, $entryMethod, $id){
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
		$data['QueuedDocument']['filename'] = $docName;
		if($entryMethod == 'Desktop Scan') {
			$data['QueuedDocument']['last_activity_admin_id'] = $id;		
		}
		else {
			$data['QueuedDocument']['user_id'] = $id;			
		}
		$data['QueuedDocument']['entry_method'] = $entryMethod;	

		if(!move_uploaded_file($data['QueuedDocument']['submittedfile']['tmp_name'], $path . $docName)) {
		    return false;
		}
		if($this->save($data)) {
		    return $this->getLastInsertId();
		}
		else {
		    return false;
		}
	}

	function isPDF() {
		if($this->data['QueuedDocument']['submittedfile']['type'] != 'application/pdf') {
			return false;
		}
		return true;
	}
	
	function lessThen1mb() {
		if($this->data['QueuedDocument']['submittedfile']['size'] > 1048576) {
			return false;
		}
		return true;		
	}

}
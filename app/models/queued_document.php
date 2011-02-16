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
	if (!empty($userLockedDoc['QueuedDocument']['id'])) {
	    if (!$this->unlockDocument($userLockedDoc['QueuedDocument']['id'])) {
		return false;
	    }
	    else return true;
	}
	else return false;
    }

    function lockDocument($id=null, $userId=null) {
	if ($id && $userId) {
	    $this->data['QueuedDocument']['id'] = $id;
	    $this->data['QueuedDocument']['locked_by'] = $userId;
	    $this->data['QueuedDocument']['locked_status'] = 1;
	    if ($this->save($this->data)) {
		return true;
	    }
	    else
		return false;
	}
    }

    function unlockDocument($id=null) {
	if ($id) {
	    $this->data['QueuedDocument']['id'] = $id;
	    $this->data['QueuedDocument']['locked_by'] = null;
	    $this->data['QueuedDocument']['locked_status'] = 0;
	    if ($this->save($this->data)) {
		return true;
	    }
	    else
		return false;
	}
    }

}
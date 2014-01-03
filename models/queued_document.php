<?php
class QueuedDocument extends AppModel {
    var $name = 'QueuedDocument';

    var $belongsTo = array(
		'DocumentQueueCategory' => array(
		    'className' => 'DocumentQueueCategory',
		    'foreignKey' => 'queue_category_id',
		    'fields' => 'id, name, secure'
		),
		'Location' => array(
		    'className' => 'Location',
		    'foreignKey' => 'scanned_location_id',
		    'fields' => 'id, name'
		),
		'LockedBy' => array(
		    'className' => 'User',
		    'foreignKey' => 'locked_by',
		    'fields' => 'id, firstname, lastname'
		),
		'LastActAdmin' => array(
			'className' => 'User',
			'foreignKey' => 'last_activity_admin_id',
			'fields' => 'id, firstname, lastname'
		),
		'SelfScanCategory' => array(
		    'className' => 'SelfScanCategory',
		    'foreignKey' => 'self_scan_cat_id',
		    'fields' => 'id, name'
		),
		'BarCodeDefinition' => array(
		    'className' => 'BarCodeDefinition',
		    'foreignKey' => 'bar_code_definition_id',
		    'fields' => 'id, name'
		),
		'User' => array(
		    'className' => 'User',
		    'foreignKey' => 'user_id',
		    'fields' => 'id, firstname, lastname, ssn, name_last4'
		)
    );

	var $validate = array(
		'submittedfile' => array(
			'pdf' => array(
				'rule' =>'isPDF',
				'message' => 'Please supply document in .pdf format.'
			),
			'lessThen5mb' => array(
				'rule' => 'lessThen5mb',
				'message' => 'Document must not be larger then 5mb'
			)
		)
	);

    function  beforeDelete($cascade = true) {
		parent::beforeDelete($cascade);
		if(!empty($this->data['QueuedDocument'])) {
		    $adminId = $this->data['QueuedDocument']['last_activity_admin_id'];
		    $reason = $this->data['QueuedDocument']['deleted_reason'];
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
		}
		if(!empty($adminId) && !empty($reason)) {
		   if($delDoc->save($this->data['DeletedDocument'])) {
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
		$userLockedDoc = $this->find('first', array(
			'conditions' => $lockedConditions,
			'recursive' => -1));
		if(!empty($userLockedDoc['QueuedDocument']['id'])) {
			$id = $this->unlockDocument($userLockedDoc['QueuedDocument']['id']);
		    if ($id) {
				return $id;
		    }
		    else false;
		}
		else return false;
    }

    function lockDocument($id=null, $userId=null) {
		if($id && $userId) {
			$unlockedDoc = $this->checkLocked($userId);
		    $this->data['QueuedDocument']['id'] = $id;
		    $this->data['QueuedDocument']['locked_by'] = $userId;
			$this->data['QueuedDocument']['last_activity_admin_id'] = $userId;
		    $this->data['QueuedDocument']['locked_status'] = 1;
			$doc = $this->findById($id);
			if(! $doc || $doc['QueuedDocument']['locked_status']) {
				return false;
			}
		    if($this->save($this->data)) {
		    	$data = $this->findById($id);
		    	$data['QueuedDocument']['secure'] = false;
				if($data['DocumentQueueCategory']['secure']) {
					$data['QueuedDocument']['secure'] = true;
				}
		    	if($unlockedDoc) {
					$data['unlocked'] = $unlockedDoc;
				}
				return $data;
		    }
		    else return false;
		}
		else return false;
    }

    function unlockDocument($id=null) {
		if($id) {
		    $this->data['QueuedDocument']['id'] = $id;
		    $this->data['QueuedDocument']['locked_by'] = null;
		    $this->data['QueuedDocument']['locked_status'] = 0;
		    if($this->save($this->data)) {
				return $id;
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
		$data['QueuedDocument']['filename'] = $docName;
		if($entryMethod == 'Desktop Scan') {
			$data['QueuedDocument']['last_activity_admin_id'] = $id;
		}
		else {
			$data['QueuedDocument']['user_id'] = $id;
		}
		$data['QueuedDocument']['entry_method'] = $entryMethod;

		if(!isset($data['QueuedDocument']['submittedfile']) || !move_uploaded_file($data['QueuedDocument']['submittedfile']['tmp_name'], $path . $docName)) {
		    return false;
		}
		if($this->save($data)) {
		    return $this->getLastInsertId();
		}
		else {
		    return false;
		}
	}

	function createEsignPDF($user, $db_data = NULL){
		
		//Get Document storage path
		$path = APP . 'storage';
		$save_directory = $path . DS . date('Y') . DS . date('m');

		//Recursively makes directories for upload file
		if( !is_dir($save_directory) )
		{
			mkdir($save_directory, 0777, TRUE);
		}

		$docName = date('YmdHis') . rand(0, pow(10, 7)) . '.pdf';

		//Create PDF with signature and save the PDF
		include(APP . 'vendors' . DS . 'MPDF54' . DS . 'mpdf.php');

		$content_base = APP . 'webroot' . DS . 'html';

		$html = include($content_base . DS . 'esign.php');
		$css = file_get_contents( $content_base . DS . 'esign.css');
		$mpdf = new mPDF();

		$mpdf->WriteHtml($css, 1);
		$mpdf->WriteHtml($html, 2);

		$mpdf->Output($save_directory . DS . $docName, 'F');

		$db_data['filename'] = $docName;

		$is_saved = $this->save($db_data);

		return $is_saved;
	}

	function isPDF() {
		if($this->data['QueuedDocument']['submittedfile']['type'] != 'application/pdf') {
			return false;
		}
		return true;
	}

	function lessThen5mb() {
		if($this->data['QueuedDocument']['submittedfile']['size'] > 5242880) {
			return false;
		}
		return true;
	}

}

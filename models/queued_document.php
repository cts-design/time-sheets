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

		var_dump($path);

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

	/*
	*	This is merely for demoing purposes in the tradeshow, this is not a legitimate way to save the queued document
	*/
	public function quickQueueDocument($user_id, $file){
		
		//Temporarily save the image for converting
		$image_directory = $this->temporaryImageSave($file);

		if(!file_exists($image_directory))
		{
			var_dump("The temporary image directory was not found");
		}

		$save_directory = APP . 'storage' . DS . date('Y') . DS . date('m');

		if(!is_dir($save_directory))
		{
			$this->log("(" . $save_directory . ") directory does not exist, attempting to create...");
			mkdir($save_directory, 0777, TRUE);
		}

		$docName 				= date('YmdHis') . rand(0, pow(10, 7)) . '.pdf';
		$full_save_directory 	= $save_directory . '/' . $docName;

		$system_string = '/usr/local/bin/convert -rotate "90>" ' . $image_directory . ' ' . $full_save_directory;

		$return_value = NULL;
		$output = system($system_string, $return_value);

		if(!file_exists($full_save_directory))
		{
			$this->log('The file was not converted successfully');
			return FALSE;
		}
		else
		{
			$document = array(
				'filename' => $docName,
				'queue_category_id' => 1,
				'user_id' => $user_id
			);
			$is_saved = $this->save($document);

			if($is_saved)
			{
				return TRUE;
			}
			else
			{
				$this->log('The document was not saved in the database');
				return FALSE;
			}
		}
	}

	/*
	*	This is a way to temporarily save the image that will be converted to the pdf for mobile upload
	*/
	private function temporaryImageSave($file)
	{

		$ext = pathinfo( $file['name'], PATHINFO_EXTENSION );

		$save_directory = APP . 'storage' . DS . 'image_tmp' . DS . date('Y') . DS . date('m');

		if(!is_dir($save_directory))
		{
			$this->log('Temporary image directory does not exist, trying to create');
			mkdir($save_directory, 0777, TRUE);
		}

		$docName = date('YmdHis') . rand(0, pow(10, 7)) . '.' . $ext;
		$full_save_directory = $save_directory . '/' . $docName;

		$is_uploaded = move_uploaded_file($file['tmp_name'], $full_save_directory);

		if($is_uploaded)
		{
			return $full_save_directory;
		}
		else
		{
			$this->log('The temporary image save for MobileLink failed at: ' . $full_save_directory);
			return FALSE;
		}
	}

	function isPDF() {
		if($this->data['QueuedDocument']['submittedfile']['type'] != 'application/pdf') {
			return false;
		}
		return true;
	}

/*
	function isPDFOrJpg() {
		if(
			$this->data['QueuedDocument']['submittedfile']['type'] == 'application/pdf'
			|| $this->data['QueuedDocument']['submittedfile']['type'] == 'image/jpg'
		)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
*/
	function lessThen5mb() {
		if($this->data['QueuedDocument']['submittedfile']['size'] > 5242880) {
			return false;
		}
		return true;
	}

}

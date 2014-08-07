<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

App::import('Core', 'HttpSocket');

class QueuedDocumentsController extends AppController {

    public $name = 'QueuedDocuments';
	
	public $components = array('Notifications', 'Email');

    public $lockStatuses = array(
		0 => 'Unlocked',
		1 => 'Locked'
    );
	
   public function beforeFilter() {
		parent::beforeFilter();
		$this->Security->validatePost = false;
		if($this->Auth->user()) {
		    if($this->Acl->check(array(
				'model' => 'User',
				'foreign_key' => $this->Auth->user('id')), 'QueuedDocuments/admin_index', '*')){
					$this->Auth->allow('admin_view', 'admin_lock_document', 'admin_unlock_document');
		    }
		}		
    }
	
	public function admin_index() {
		$canFile = 0;
		$canDelete = 0;
		$canReassign = 0;
		$canAddCustomer = 0;
	    if($this->Acl->check(array(
			'model' => 'User',
			'foreign_key' => $this->Auth->user('id')), 'QueuedDocuments/admin_file_document', '*')){
				$canFile = true;
	    }
	    if($this->Acl->check(array(
			'model' => 'User',
			'foreign_key' => $this->Auth->user('id')), 'QueuedDocuments/admin_delete', '*')){
				$canDelete = true;
	    }
	    if($this->Acl->check(array(
			'model' => 'User',
			'foreign_key' => $this->Auth->user('id')), 'QueuedDocuments/admin_reassign_queue', '*')){
				$canReassign = true;
	    }
	    if($this->Acl->check(array(
			'model' => 'User',
			'foreign_key' => $this->Auth->user('id')), 'Users/admin_add', '*')){
				$canAddCustomer = true;
	    }	    	    		    		
		if($this->RequestHandler->isAjax()) {
			$allowedQueueCats = $this->getAllowedQueueCats();
			if(isset($this->params['url']['sort']) && $this->params['url']['sort'] == 'locked_status') {
				if($this->params['url']['direction'] == 'ASC'){
					$this->params['url']['direction'] = 'DESC';
				}
				else{
					$this->params['url']['direction'] = 'ASC';
				}
			}
			if(isset($this->params['url']['requeued'])) {
				$docs[0] = $this->QueuedDocument->findById($this->params['url']['id']);
				$docs[0]['QueuedDocument']['requeued'] = true;
				$data['totalCount'] = 1;				
			}
			else {
				if(!isset($this->params['url']['exclude_filters'])) {
					$conditions = $this->getDocumentQueueFilters();
				}		
				if(isset($this->params['url']['doc_id'])) {
					$conditions['QueuedDocument.id'] = $this->params['url']['doc_id'];
				}
				if(isset($this->params['url']['lastname'])) {
					$conditions['User.lastname'] = $this->params['url']['lastname'];
				}
				if(isset($this->params['url']['last4'])) {
					$conditions['RIGHT (User.ssn , 4)'] = $this->params['url']['last4'];
				}
				$this->QueuedDocument->checkLocked($this->Auth->user('id'));

				if(isset($conditions)) {
					if($this->checkAutoLoad()) {				
						$conditions['QueuedDocument.locked_status'] = 0;
						$doc = $this->QueuedDocument->find('first', array(
							'order' => array('QueuedDocument.id ASC'),
							'conditions' => $conditions));

						if($doc) {
							$docs[0] = $this->QueuedDocument->lockDocument(
									       $doc['QueuedDocument']['id'], $this->Auth->user('id'));
						}	
						$data['totalCount'] = 1;					
					}
					else {
						$this->paginate = array(
							'order' => array('QueuedDocument.id ASC'),
							'conditions' => $conditions);
						
						$data['totalCount'] = 
							$this->QueuedDocument->find('count', array(
								'conditions' => $conditions,
								'recursive' => 0));					
					}	
				}
				else {
					
					if($this->checkAutoLoad()) {
						$conditions['QueuedDocument.locked_status'] = 0;
						$conditions['QueuedDocument.queue_category_id'] = $allowedQueueCats;
						$doc = $this->QueuedDocument->find('first', array('conditions' => $conditions));
						if($doc) {
							$docs[0] = $this->QueuedDocument->lockDocument(
									       $doc['QueuedDocument']['id'], $this->Auth->user('id'));
						}						
						$data['totalCount'] = 1;										
					}
					else {
						$this->paginate = array(
							'order' => array('QueuedDocument.id ASC'),
							'conditions' => array(
								'QueuedDocument.queue_category_id' => $allowedQueueCats));
						$data['totalCount'] = $this->QueuedDocument->find('count', array(
							'recursive' => -1,
							'conditions' => array(
								'QueuedDocument.queue_category_id' => $allowedQueueCats)));					
					}
				}
				if(!$this->checkAutoLoad()) {
					$docs = $this->paginate();	
				}				
			}	
			
			if(isset($docs)) {
				$i = 0;
				foreach($docs as $doc) {
					$data['docs'][$i]['id'] = $doc['QueuedDocument']['id'];
					$data['docs'][$i]['queue_cat'] = $doc['DocumentQueueCategory']['name'];
					$data['docs'][$i]['secure'] =  false;
					if($doc['DocumentQueueCategory']['secure']) {
						$data['docs'][$i]['secure'] =  true;
					}
					$data['docs'][$i]['scanned_location'] = $doc['Location']['name'];
					if(!empty($doc['LockedBy']['id'])) {
						$data['docs'][$i]['locked_by'] = 
							$doc['LockedBy']['lastname'] . ', ' . $doc['LockedBy']['firstname'];
						$data['docs'][$i]['locked_by_id'] = $doc['LockedBy']['id'];	
					}
					else {
						$data['docs'][$i]['locked_by'] = null;						
					} 
					if(!empty($doc['LastActAdmin']['id'])) {
						$data['docs'][$i]['last_activity_admin'] = 
							$doc['LastActAdmin']['lastname'] . ', ' . $doc['LastActAdmin']['firstname'];						
					}
					else {
						$data['docs'][$i]['last_activity_admin'] = null;						
					} 
					if($doc['User']['id']) {
						$data['docs'][$i]['queued_to_customer'] = $doc['User']['name_last4'];
						$data['docs'][$i]['queued_to_customer_id'] = $doc['User']['id'];
						$data['docs'][$i]['queued_to_customer_ssn'] = 
							substr($doc['User']['ssn'], 0, -6) . '-' . 
							substr($doc['User']['ssn'], 3, -4) . '-' .
							substr($doc['User']['ssn'], -4);
						$data['docs'][$i]['queued_to_customer_first'] = $doc['User']['firstname'];
						$data['docs'][$i]['queued_to_customer_last'] = $doc['User']['lastname'];
					}
					else {
						$data['docs'][$i]['queued_to_customer'] = null;
						$data['docs'][$i]['queued_to_customer_id'] = null;
						$data['docs'][$i]['queued_to_customer_ssn'] = null;
						$data['docs'][$i]['queued_to_customer_first'] = null;
						$data['docs'][$i]['queued_to_customer_last'] = null;												
					}
					if(isset($doc['QueuedDocument']['requeued'])) {
						$data['docs'][$i]['requeued'] = true;
					}					
					$data['docs'][$i]['locked_status'] = 
						$this->lockStatuses[$doc['QueuedDocument']['locked_status']];
					$data['docs'][$i]['self_scan_cat_id'] = $doc['QueuedDocument']['self_scan_cat_id'];
					$data['docs'][$i]['bar_code_definition_id'] = 
						$doc['QueuedDocument']['bar_code_definition_id'];
					$data['docs'][$i]['created'] = $doc['QueuedDocument']['created'];	
					$data['docs'][$i]['modified'] = $doc['QueuedDocument']['modified'];
					$i++;
 				}
			}
			else {
				$data['docs'] = array();
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
		if(!$this->RequestHandler->isAjax()) {
			$this->set(compact('canFile', 'canDelete', 'canReassign', 'canAddCustomer'));
		}	
		$this->layout = 'ext_fullscreen';
	}

	public function admin_lock_document() {
		if($this->RequestHandler->isAjax()) {
			$docId = null;
			if(isset($this->params['form']['doc_id'])) {
				$docId = $this->params['form']['doc_id'];
			}
			$data = $this->QueuedDocument->lockDocument($docId, $this->Auth->user('id'));
			if($data) {
				$data['admin'] = 
					$this->Auth->user('lastname') . ', ' . $this->Auth->user('firstname');		
				$data['success'] = true; 
			    $this->Transaction->createUserTransaction('Storage', null, null , 
				    'Locked Document ID ' . $data['QueuedDocument']['id']  . ' for viewing ' );				
			}
			else $data['success'] = false;
		}
		$this->set(compact('data'));
		$this->render(null, null, '/elements/ajaxreturn');
	}
	
    public function admin_reassign_queue() {
    	if($this->RequestHandler->isAjax()) {
			if(!empty($this->params['form']['id'])){
				$this->data['QueuedDocument'] = $this->params['form'];
				$this->data['QueuedDocument']['last_activity_admin_id'] = $this->Auth->user('id');
				$this->data['QueuedDocument']['locked_status'] = 0;
				$this->data['QueuedDocument']['locked_by'] = null;
				if($this->QueuedDocument->save($this->data)) {
				    $queueCatList = $this->QueuedDocument->DocumentQueueCategory->find('list');
				    $queueCatId = $this->data['QueuedDocument']['queue_category_id'];
				    $this->Transaction->createUserTransaction('Storage', null, null , 
					    'Reassigned document ID '. $this->data['QueuedDocument']['id'] .
					    ' to queue ' . $queueCatList[$queueCatId]);
				    $data['success'] = true;
				    $data['message'] = 'Document reassigned successfully.';
				}
				else {
					$data['sucess'] = false;
					$data['message'] = 'Unable to reassign document.';
				}  		
    		}
    		else {
    			$data['success'] = false;
    			$data['message'] = 'Invalid doument id';
    		}
    		$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');  
		}
    }

    public function admin_view($id = null) {
		$this->view = 'Media';
		$doc = $this->QueuedDocument->findById($id);
        $path = null;
        $root = substr(APP, 0, -1);
        $path1 = Configure::read('Document.storage.path') .
            substr($doc['QueuedDocument']['filename'], 0, 4) . DS .
            substr($doc['QueuedDocument']['filename'], 4, 2) . DS;
        $path2 = Configure::read('Document.storage.path') .
            date('Y', strtotime($doc['QueuedDocument']['created'])) . DS .
            date('m', strtotime($doc['QueuedDocument']['created'])) . DS;
        if(file_exists($root . $path1 .$doc['QueuedDocument']['filename'])) {
            $path = $path1;
        }
        elseif(file_exists($root . $path2 . $doc['QueuedDocument']['filename'])) {
            $path = $path2;
        }

		$params = array(
		    'id' => $doc['QueuedDocument']['filename'],
		    'name' => str_replace('.pdf', '', $doc['QueuedDocument']['filename']),
		    'extension' => 'pdf',
		   	'cache' => true,
		    'path' => $path);
		$this->set($params);
    }
		
	public function admin_file_document() {
		if($this->RequestHandler->isAjax()) {
			$this->data['FiledDocument'] = $this->params['form'];
			$this->data['FiledDocument']['last_activity_admin_id'] = $this->Auth->user('id');
			$this->data['FiledDocument']['admin_id'] = $this->Auth->user('id');
			$this->data['FiledDocument']['filed_location_id'] = $this->Auth->user('location_id');
			$this->QueuedDocument->recursive = 0;
			$queuedDoc = $this->QueuedDocument->findById($this->data['FiledDocument']['id']);
			$this->data['FiledDocument']['scanned_location_id'] = 
				$queuedDoc['QueuedDocument']['scanned_location_id'];
			if($queuedDoc['DocumentQueueCategory']['secure']) {
				if(isset($this->data['FiledDocument']['cat_3'])) {
					$catId = $this->data['FiledDocument']['cat_3'];
				}
				elseif(isset($this->data['FiledDocument']['cat_2'])) {
					$catId = $this->data['FiledDocument']['cat_2'];
				}
				elseif(isset($this->data['FiledDocument']['cat_1'])) {
					$catId = $this->data['FiledDocument']['cat_1'];
				}
				if(!$this->checkIfFilingCatSecure($catId)) {
					$data['success'] = false;
					$data['message'] = 'Unable to file secure document to a non-secure category.';
					$this->set(compact('data'));
					return $this->render(null, null, '/elements/ajaxreturn');									
				}				
			}				
			$this->data['FiledDocument']['entry_method'] = $queuedDoc['QueuedDocument']['entry_method'];
			$this->data['FiledDocument']['filename'] = $queuedDoc['QueuedDocument']['filename'];
			$this->data['FiledDocument']['created'] = $queuedDoc['QueuedDocument']['created'];
			$this->data['FiledDocument']['filed'] = date('Y-m-d H:i:s');				
			$this->QueuedDocument->User->recursive = -1;
			$user = $this->QueuedDocument->User->findById($this->data['FiledDocument']['user_id']);
			if($this->QueuedDocument->User->FiledDocument->save($this->data['FiledDocument'])) {
				$this->QueuedDocument->delete($this->data['FiledDocument']['id']);		
				
				$this->loadModel('ProgramResponse');							
				$processedDoc = $this->ProgramResponse->ProgramResponseDoc->processResponseDoc($this->data, $user);	
				if(isset($processedDoc['docFiledEmail'])) {
					$this->Notifications->sendProgramEmail($processedDoc['docFiledEmail'], $user);
				}				
				if(isset($processedDoc['finalEmail'])) {
					$this->Notifications->sendProgramEmail($processedDoc['finalEmail'], $user);
				}

				if(isset($processedDoc['status'])) {
					$this->loadModel('Program');
					$this->Program->recursive = -1;
					$program = $this->Program->findById($processedDoc['program_id']);
					$this->Notifications->sendProgramResponseStatusAlert($user, $program, $processedDoc['status']);
				}
		
				if(isset($this->data['FiledDocument']['requeue'])) {
					$this->data['QueuedDocument'] = $queuedDoc['QueuedDocument'];
					unset($this->data['QueuedDocument']['id']);
					$this->data['QueuedDocument']['locked_by'] = $this->Auth->user('id');
					$this->data['QueuedDocument']['last_activity_admin_id'] = $this->Auth->user('id');
					$this->data['QueuedDocument']['locked_status'] = 1;
					$this->QueuedDocument->create();
					$this->QueuedDocument->save($this->data['QueuedDocument']);
					$id = $this->QueuedDocument->getLastInsertId();
				    $this->Transaction->createUserTransaction('Storage', null, null ,
					    'Filed document ID '. $this->data['FiledDocument']['id'] .
					    ' to ' . $user['User']['lastname'] . ', ' . $user['User']['firstname'] . 
					    ' - '. substr($user['User']['ssn'], -4). '.' .
						'and re-queued document as doc Id# '.$id);
					$data['message'] = 'Document filed and re-queud successfully';
					$data['locked'] = $id;	
				}
				else {
				    $this->Transaction->createUserTransaction('Storage', null, null ,
					    'Filed document ID '. $this->data['FiledDocument']['id'] .
					    ' to ' . $user['User']['lastname'] . ', ' . $user['User']['firstname'] . 
					    ' - '. substr($user['User']['ssn'],-4));
					$data['message'] = 'Document filed successfully';	
				}
			    $this->sendCusFiledDocAlert($user, $this->data['FiledDocument']['id']);
			    $this->sendStaffFiledDocAlert($this->Auth->user(), $this->data['FiledDocument']['id'], $user);
				$data['success'] = true;				
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to file document, please try again.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}		 
	}

    public function admin_delete() {
		if(!empty($this->params['form']['id'])) {
		    $id = $this->params['form']['id'];
		    $this->data = $this->QueuedDocument->read(null, $id);
		    $this->data['QueuedDocument']['last_activity_admin_id'] = $this->Auth->user('id');
		    $this->data['QueuedDocument']['deleted_location_id'] = $this->Auth->user('location_id');
		    if(isset($this->params['form']['other'])) {
		    	$this->data['QueuedDocument']['deleted_reason'] = $this->params['form']['other'];	
		    }
		    else {
		    	$this->data['QueuedDocument']['deleted_reason'] = $this->params['form']['deleted_reason'];		
		    }
		}
		if(!$id) {
			$data['success'] = false;
			$data['message'] = 'Invalid document id.';
		}
		else {
			$data = $this->data;
			$this->QueuedDocument->set($data);
			if($this->QueuedDocument->delete($id, false)) {
			    $this->Transaction->createUserTransaction('Storage', null, null ,
				    'Deleted document ID '. $id .
				    ' from the queue with reason, ' . $this->data['QueuedDocument']['deleted_reason']);
				$data['success'] = true;
				$data['message'] = 'Document was deleted successfully.';    
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to delete document.';
			}			
		}
		$this->set(compact('data'));
		$this->render(null, null, '/elements/ajaxreturn');
    }

    public function admin_desktop_scan_document() {
		$locations = $this->QueuedDocument->Location->find('list');
		$queueCats = $this->QueuedDocument->DocumentQueueCategory->find('list');
		if(!empty($this->data)) {
		    $id = $this->QueuedDocument->uploadDocument($this->data, 'Desktop Scan', $this->Auth->User('id'));
		    if($id) {
				$this->Transaction->createUserTransaction('Storage', null, null,
					trim('Scanned document ID ' . $id . ' to ' . 'document queue under category ' .
				   	$queueCats[$this->data['QueuedDocument']['queue_cat_id']] . ' for location ' . $locations[$this->data['QueuedDocument']['location_id']]));
				$this->Session->setFlash(__('Scanned document was queued successfully.', true), 'flash_success');
				$this->autoRender = false;
				exit;
		    }
		    else {
				$this->Session->setFlash(__('Unable to save scanned document.', true), 'flash_failure');
				$this->autoRender = false;
				exit;
		    }
		}
		$title_for_layout = 'Desktop Scan Document';
		$this->set(compact('title_for_layout', 'queueCats', 'locations'));
    }

    public function admin_unlock_document() {
    	if($this->RequestHandler->isAjax()) {
    		if($this->QueuedDocument->checkLocked($this->Auth->user('id'))) {
    			$data['success'] = true;
    			$data['message'] = 'Document unlock and returned to queue.';
    		}
    		else {
    			$data['success'] = false;
    			$data['message'] = 'Unable to unlock document.';
    		}	
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');    		   	
    	}
    }
	
	private function checkAutoLoad() {
		$this->loadModel('DocumentQueueFilter');
		return $this->DocumentQueueFilter->Field('auto_load_docs', 
			array('user_id' => $this->Auth->user('id')));	
	}
	
	private function getDocumentQueueFilters() {
		$this->loadModel('DocumentQueueFilter');
		$filters = $this->DocumentQueueFilter->findByUserId($this->Auth->user('id'));
		$conditions = null;
		if($filters) {
			$locations = json_decode($filters['DocumentQueueFilter']['locations'], true);
			if(!empty($locations)) {
				$conditions['QueuedDocument.scanned_location_id'] = $locations;
			}
			$queueCats = json_decode($filters['DocumentQueueFilter']['queue_cats'], true);
			if(!empty($queueCats)) {
				$conditions['QueuedDocument.queue_category_id'] = $queueCats;
			}
			$selfScanCats = json_decode($filters['DocumentQueueFilter']['self_scan_cats'], true);
			if(!empty($selfScanCats)) {
				$conditions['QueuedDocument.self_scan_cat_id'] = $selfScanCats;
			}
			if(!empty($filters['DocumentQueueFilter']['from_date']) && 
			   !empty($filters['DocumentQueueFilter']['to_date'] )){
				    $from = date('Y-m-d H:i:m', 
				    	strtotime($filters['DocumentQueueFilter']['from_date'] . " 12:00 am"));
				    $to = date('Y-m-d H:i:m',
				    	strtotime($filters['DocumentQueueFilter']['to_date'] . " 11:59 pm"));
				    $conditions['QueuedDocument.created Between ? AND ?'] = array($from, $to); 
			}
			return $conditions;   		    
		}
		return false;		
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

	private function sendStaffFiledDocAlert($admin, $docId, $customer) {
		$this->loadModel('Alert');
		$data = $this->Alert->getStaffFiledDocAlerts($admin, $docId, $customer);
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
				$this->Email->subject = 'Staff Member Filed Document';
				$this->Email->send($alert['message'] . "\r\n" . $alert['url']);				
			}
		}
	}
	private function checkIfFilingCatSecure($catId) {
		$this->loadModel('DocumentFilingCategory');
		return $this->DocumentFilingCategory->isSecure($catId);
	}

	private function getAllowedQueueCats() {
		$this->QueuedDocument->DocumentQueueCategory->recursive -1;
	    $cats = $this->QueuedDocument->DocumentQueueCategory->find('all', array(
			'fields' => array(
				'DocumentQueueCategory.id',
				'DocumentQueueCategory.secure',
				'DocumentQueueCategory.secure_admins'
				)));
		$i = 0;
		$allowedCats = array();
		foreach($cats as $cat){
			if($this->Auth->user('role_id') > 3 && $cat['DocumentQueueCategory']['secure']) {
				$secureAdmins = json_decode($cat['DocumentQueueCategory']['secure_admins'], true);
				if($secureAdmins && !in_array($this->Auth->user('id'), $secureAdmins)) {
					continue;
				}
			}
			$allowedCats[$i] = $cat['DocumentQueueCategory']['id'];
			$i++;
		}
		return $allowedCats;	
	} 		
}

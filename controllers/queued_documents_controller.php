<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

App::import('Core', 'HttpSocket');

class QueuedDocumentsController extends AppController {

    var $name = 'QueuedDocuments';
	
	var $components = array('Notifications', 'Email');

    var $lockStatuses = array(
		0 => 'Unlocked',
		1 => 'Locked'
    );
	
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
		// TODO: remove the cookie, most likely no longer needed
		$this->Cookie->name = 'docQueueSearch';
		$this->Cookie->time = 0;
		$this->Security->validatePost = false;
		if($this->Auth->user()) {
		    if($this->Acl->check(array(
				'model' => 'Role',
				'foreign_key' => $this->Auth->user('role_id')), 'QueuedDocuments/admin_index', '*')){
			$this->Auth->allow('admin_view');
		    }
		}
    }
	
	function admin_index() {
		if($this->RequestHandler->isAjax()) {
			if(isset($this->params['url']['requeued'])) {
				$docs[0] = $this->QueuedDocument->findById($this->params['url']['id']);
				$docs[0]['QueuedDocument']['requeued'] = true;
				$data['totalCount'] = 1;				
			}
			else {
				$conditions = $this->getDocumentQueueFilters();
				$this->QueuedDocument->checkLocked($this->Auth->user('id'));
				if(isset($conditions)) {
					if($this->checkAutoLoad()) {				
						$conditions['QueuedDocument.locked_status'] = 0;
						$doc = $this->QueuedDocument->find('first', array(
							'order' => array('QueuedDocument.id ASC'),
							'conditions' => $conditions,
							'recursive' => -1));
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
							$this->QueuedDocument->find('count', array('conditions' => $conditions));					
					}	
				}
				else {
					if($this->checkAutoLoad()) {
						$conditions['QueuedDocument']['locked_status'] = 0;
						$doc = $this->QueuedDocument->find('first', array(
							'order' => array('QueuedDocument.id ASC'),
							'conditions' => $conditions,
							'recursive' => -1));
						if($doc) {
							$docs[0] = $this->QueuedDocument->lockDocument(
									       $doc['QueuedDocument']['id'], $this->Auth->user('id'));
						}						
						$data['totalCount'] = 1;										
					}
					else {
						$this->paginate = array('order' => array('QueuedDocument.id ASC'));
						$data['totalCount'] = $this->QueuedDocument->find('count');					
					}
				}
				if(!$this->checkAutoLoad()) {
					$docs = $this->paginate();	
				}				
			}			
			
			if($docs) {
				$i = 0;
				foreach($docs as $doc) {
					$data['docs'][$i]['id'] = $doc['QueuedDocument']['id'];
					$data['docs'][$i]['queue_cat'] = $doc['DocumentQueueCategory']['name'];
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
		$this->layout = 'ext_fullscreen';
	}

	function admin_lock_document() {
		if($this->RequestHandler->isAjax()) {
			$userId = $this->Auth->user('id');
			$docId = $this->params['form']['doc_id'];
			$data = $this->QueuedDocument->lockDocument($docId, $userId);
			if($data) {
				$data['admin'] = 
					$this->Auth->user('lastname') . ', ' . $this->Auth->user('firstname');		
				$data['success'] = true; 
			}
			else $data['success'] = false;
		}
		$this->set(compact('data'));
		$this->render(null, null, '/elements/ajaxreturn');
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
		$doc = $this->QueuedDocument->findById($id);
		$params = array(
		    'id' => $doc['QueuedDocument']['filename'],
		    'name' => str_replace('.pdf', '', $doc['QueuedDocument']['filename']),
		    'extension' => 'pdf',
		   	'cache' => true,
		    'path' =>  Configure::read('Document.storage.path') .
		    //TODO change this to use get the path from the file name.
		    date('Y', strtotime($doc['QueuedDocument']['created'])) . '/' .
		    date('m', strtotime($doc['QueuedDocument']['created'])) . '/'
		);
		$this->set($params);
    }
	
	//TODO: remove this if we are not going to have thumbnails anymore?
	function admin_view_thumbnail($id = null) {
		$this->view = 'Media';
		$doc = $this->QueuedDocument->read(null, $id);
		$params = array(
		    'id' => str_replace('.pdf', '.jpg', $doc['QueuedDocument']['filename']),
		    'name' => str_replace('.pdf', '', $doc['QueuedDocument']['filename']),
		    'extension' => 'jpg',
		    'download' => false,
		    'cache' => true,
		    'mimeType' => array('jpg' => 'image/jpeg'),
		    'path' =>  Configure::read('Document.jpeg.path') .
		    date('Y', strtotime($doc['QueuedDocument']['created'])) . '/' .
		    date('m', strtotime($doc['QueuedDocument']['created'])) . '/'
		);
		$this->set($params);		
	}
	
	function admin_file_document() {
		if($this->RequestHandler->isAjax()) {
			$this->data['FiledDocument'] = $this->params['form'];
			$this->data['FiledDocument']['last_activity_admin_id'] = $this->Auth->user('id');
			$this->data['FiledDocument']['admin_id'] = $this->Auth->user('id');
			$this->data['FiledDocument']['filed_location_id'] = $this->Auth->user('location_id');
			$this->QueuedDocument->recursive = -1;
			$queuedDoc = $this->QueuedDocument->findById($this->data['FiledDocument']['id']);
			$this->data['FiledDocument']['scanned_location_id'] = 
				$queuedDoc['QueuedDocument']['scanned_location_id'];
			$this->data['FiledDocument']['entry_method'] = $queuedDoc['QueuedDocument']['entry_method'];
			$this->data['FiledDocument']['filename'] = $queuedDoc['QueuedDocument']['filename'];
			$this->data['FiledDocument']['entry_method'] = $queuedDoc['QueuedDocument']['entry_method'];				
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

    function admin_desktop_scan_document() {
		if(!empty($this->data)) {
		    $id = $this->QueuedDocument->uploadDocument($this->data, 'Desktop Scan', $this->Auth->User('id'));
		    if($id) {
				$this->Transaction->createUserTransaction('Storage', null, null,
					trim('Scanned document ID ' . $id . ' to ' . $user['User']['lastname'] .
						', ' . $user['User']['firstname'] . ' - ' . substr($user['User']['ssn'], -4), ' -'));
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

    function admin_unlock_document() {
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


    function _addCustomer() {
		if(!empty($this->data)) {
		    $this->loadModel('User');
		    $this->User->create();
		    if($this->User->save($this->data)) {
				$this->Transaction->createUserTransaction('Customer',
					null, null, 'Added customer ' . $this->data['User']['firstname'] .
					' ' . $this->data['User']['lastname']) . ' - ' . substr($this->data['User']['ssn'], -4);
				$this->Session->setFlash(__('The customer has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
		    }
		    else {
				$this->Session->setFlash(
					__('The customer could not be saved. Please, try again.', true), 'flash_failure');
		    }
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
		if($filters) {
			$locations = json_decode($filters['DocumentQueueFilter']['locations'], true);
			if(!empty($locations)) {
				$conditions['QueuedDocument.scanned_location_id'] = $locations;
			}
			$queueCats = json_decode($filters['DocumentQueueFilter']['queue_cats'], true);
			if(!empty($queueCats)) {
				$conditions['QueuedDocument.queue_category_id'] = $queueCats;
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
				$this->Email->from = Configure::read('System.email');
				$this->Email->subject = 'Document filed to customer alert';
				$this->Email->send($alert['message'] . "\r\n" . $alert['url']);				
			}
		}
	}		
}
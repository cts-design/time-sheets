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
			}
			if(isset($conditions)) {
				$this->paginate = array(
					'order' => array('QueuedDocument.id ASC'),
					'conditions' => $conditions);
				$data['totalCount'] = $this->QueuedDocument->find('count', array('conditions' => $conditions));	
			}
			else {
				$this->paginate = array('order' => array('QueuedDocument.id ASC'));
				$data['totalCount'] = $this->QueuedDocument->find('count');
			}
			$docs = $this->paginate();			
			$locations = $this->QueuedDocument->Location->find('list');
			$this->QueuedDocument->User->recursive = -1;
			$queueCats = $this->QueuedDocument->DocumentQueueCategory->find('list', array(
		    	'conditions' => array('DocumentQueueCategory.deleted' => 0)));
			if($docs) {
				$i = 0;
				foreach($docs as $doc) {
					$data['docs'][$i]['id'] = $doc['QueuedDocument']['id'];
					$data['docs'][$i]['queue_cat'] = 
						$queueCats[$doc['QueuedDocument']['queue_category_id']];
					$data['docs'][$i]['scanned_location'] = 
						$locations[$doc['QueuedDocument']['scanned_location_id']];
					$lockedBy = $this->QueuedDocument->User->findById($doc['QueuedDocument']['locked_by']); 	
					if($lockedBy) {
						$data['docs'][$i]['locked_by'] = 
							$lockedBy['User']['lastname'] . ', ' . $lockedBy['User']['firstname'];
					}
					else {
						$data['docs'][$i]['locked_by'] = '';						
					}
					$lastActAdmin = 
						$this->QueuedDocument->User->findById($doc['QueuedDocument']['last_activity_admin_id']); 
					if($lastActAdmin) {
						$data['docs'][$i]['last_activity_admin'] = 
							$lastActAdmin['User']['lastname'] . ', ' . $lastActAdmin['User']['firstname'];						
					}
					else {
						$data['docs'][$i]['last_activity_admin'] = '';						
					}
					$queuedToCustomer = 
						$this->QueuedDocument->User->findById($doc['QueuedDocument']['user_id']); 
					if($queuedToCustomer) {
						$data['docs'][$i]['queued_to_customer'] = $queuedToCustomer['User']['name_last4'];
					}
					else {
						$data['docs'][$i]['queued_to_customer'] = '';						
					}					
					$data['docs'][$i]['locked_status'] = 
						$this->lockStatuses[$doc['QueuedDocument']['locked_status']];
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
	
    function admin_index_old($action=null, $docId=null, $active=null) {
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
		    $this->paginate = array('limit' => 10, 'conditions' => $conditions);
		}
		else {
		    // @TODO Move the pagianation limits to the config file 
		    $this->paginate = array('limit' => 10);
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
		    'queueCategories' => $this->QueuedDocument->DocumentQueueCategory->find('list', array(
		    	'conditions' => array('DocumentQueueCategory.deleted' => 0))),
		    'locationId' => $locationId,
		    'queuedDocId' => $queuedDocId,
		    'from' => $from,
		    'to' => $to,
		    'canFile' => $canFile
		);
		if(!empty($lockedDoc['QueuedDocument']['user_id'])) {
		    $data['user'] = $this->QueuedDocument->User->read(null,$lockedDoc['QueuedDocument']['user_id'] );
		}
		if(!empty($lockedDoc['QueuedDocument']['bar_code_definition_id'])) {
			$this->loadModel('BarCodeDefinition');
			 $definition = $this->BarCodeDefinition->findById(
				$lockedDoc['QueuedDocument']['bar_code_definition_id'], array('cat_1', 'cat_2', 'cat_3'));	
			if($definition) {
				$data['categories'] = $definition['BarCodeDefinition'];
			}		
		}		
		else if(!empty($lockedDoc['QueuedDocument']['self_scan_cat_id'])) {
		    $category = $this->QueuedDocument->SelfScanCategory->findById(
		    	$lockedDoc['QueuedDocument']['self_scan_cat_id'], array('cat_1', 'cat_2', 'cat_3'));
			if($category) {
				$data['categories'] = $category['SelfScanCategory'];
			}	
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
			$this->log($this->params, 'debug');
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
		
				if(key_exists('requeue', $this->data['FiledDocument'])) {
					$this->data['QueuedDocument']['filename'] = $this->data['FiledDocument']['filename'];
					$this->data['QueuedDocument']['locked_by'] = $this->Auth->user('id');
					$this->data['QueuedDocument']['locked_status'] = 1;
					$this->data['QueuedDocument']['entry_method'] = $this->data['FiledDocument']['entry_method'];
					$this->data['QueuedDocument']['user_id'] = $this->data['FiledDocument']['user_id'];
					$this->data['QueuedDocument']['created'] = $this->data['FiledDocument']['created'];
					$this->QueuedDocument->create();
					$this->QueuedDocument->save($this->data['QueuedDocument']);
					$id = $this->QueuedDocument->getLastInsertId();
				    $this->Transaction->createUserTransaction('Storage', null, null ,
					    'Filed document ID '. $this->data['FiledDocument']['id'] .
					    ' to ' . $user['User']['lastname'] . ', ' . $user['User']['firstname'] . 
					    ' - '. substr($user['User']['ssn'], -4). '.' .
						'and re-queued document as doc Id# '.$id);
					$data['message'] = 'Document filed and re-queud successfully';	
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
					' ' . $this->data['User']['lastname']) . ' - ' . substr($this->data['User']['ssn'], -4);
				$this->Session->setFlash(__('The customer has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
		    }
		    else {
				$this->Session->setFlash(__('The customer could not be saved. Please, try again.', true), 'flash_failure');
		    }
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
				$this->Email->from = Configure::read('System.email');
				$this->Email->subject = 'Document filed to customer alert';
				$this->Email->send($alert['message'] . "\r\n" . $alert['url']);				
			}
		}
	}		
}
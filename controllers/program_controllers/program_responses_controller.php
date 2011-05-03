<?php 


class ProgramResponsesController extends AppController {
	
	var $name = 'ProgramResponses';
	
	var	$components = array('Notifications');
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->ProgramResponse->Program->ProgramField->recursive = 0;
		if(!empty($this->params['pass'][0]) && $this->params['action'] == 'index') {
			$query = $this->ProgramResponse->Program->ProgramField->findAllByProgramId($this->params['pass'][0]); 
			$fields = Set::classicExtract($query, '{n}.ProgramField');
			foreach($fields as $k => $v) {
				if(!empty($v['validation'])) 
					$validate[$v['name']] = json_decode($v['validation'], true); 
				}
			if($query[0]['Program']['form_esign_required']) {
				$validate['form_esignature'] = array(
					'rule' => 'notempty',
					'message' => 'You must put you last name in the box.');
			}			
			$this->ProgramResponse->modifyValidate($validate);
		}
	}	
	
	function index($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect($this->referer());
		} 
		if(!empty($this->data)) {
			$response = $this->ProgramResponse->findByUserId($this->Auth->user('id'));		
			$this->data['ProgramResponse']['form_completed'] = date('m/d/y');		
			$this->data['ProgramResponse']['answers'] = json_encode($this->data['ProgramResponse']);
			$this->data['ProgramResponse']['id'] = $response['ProgramResponse']['id'];
			$this->data['ProgramResponse']['program_id'] = $id;
			if($this->ProgramResponse->save($this->data)) {
				$programEmail = $this->ProgramResponse->Program->ProgramEmail->find('first', array('conditions' => array(
					'ProgramEmail.program_id' => $id,
					'ProgramEmail.type' => 'form'
				)));
				$this->Notifications->sendProgramEmail($programEmail);
				$this->Session->setFlash(__('Saved', true), 'flash_success');
				$program = $this->ProgramResponse->Program->findById($id);
				if(strpos($program['Program']['type'], 'docs', 0)) {
					$this->redirect(array('action' => 'required_docs', $id));
				}
				else{
					$this->redirect(array('action' => 'submission_received'));
				}
			}
			else {
				$this->Session->setFlash(__('Unable to save', true), 'flash_failure');
			}			
		}
		$program = $this->ProgramResponse->Program->findById($id);
		$instructions = $program['Program']['form_instructions'];
		$title_for_layout = $program['Program']['name'] . ' Registration Form' ;
		$this->set(compact('program', 'title_for_layout', 'instructions'));	
	}
		
	function required_docs($id = null) {
		if(!$id){
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect($this->referer());
		}
		if(!empty($this->data)) {
			$this->loadModel('QueuedDocument');
			$this->data['QueuedDocument']['req_program_doc'] = 1;	
			if($this->QueuedDocument->uploadDocument($this->data, 'Program Upload', $this->Auth->user('id'))) {
				$this->Session->setFlash(__('Document uploaded successfully.', true), 'flash_success');
				$this->redirect(array('action' => 'required_docs', $id));
			}
			else {
				$this->Session->setFlash(__('Unable to upload document, please try again', true), 'flash_failure');
				$this->redirect(array('action' => 'required_docs', $id));
			}				
		}
		$program = $this->ProgramResponse->Program->findById($id);
		$data['instructions'] = $program['Program']['doc_instructions'];
		$data['title_for_layout'] = 'Required Documentation';
		$data['queueCategoryId'] = $program['Program']['queue_category_id'];
		$this->set($data);
	}

	function response_complete($id=null) {
		$title_for_layout = 'Program Certificate';
		$this->set(compact('title_for_layout'));
	}
			
	function view_cert($id=null) {
		if(!$id) {
		    $this->Session->setFlash(__('Invalid Program', true), 'flash_failure');
		    $this->redirect(array('action' => 'index'));
		}
		$programResponse = $this->ProgramResponse->find('first', array('conditions' => array(
			'ProgramResponse.user_id' => $this->Auth->user('id'),
			'ProgramResponse.program_id' => $id 
		)));
		$docId = Set::extract('/ProgramResponseDoc[cert=1]/doc_id', $programResponse);
		$this->view = 'Media';
		$this->loadModel('FiledDocument');
		$doc = $this->FiledDocument->read(null, $docId[0]);
		$params = array(
		    'id' => $doc['FiledDocument']['filename'],
		    'name' => str_replace('.pdf', '', $doc['FiledDocument']['filename']),
		    'extension' => 'pdf',
		    'cache' => true,
		    'path' => Configure::read('Document.storage.path') .
		    date('Y', strtotime($doc['FiledDocument']['created'])) . '/' .
		    date('m', strtotime($doc['FiledDocument']['created'])) . '/'
		);
		$this->set($params);
		return $params;		
	} 
	
	function admin_index($id = null) {
		if($id){
			if($this->RequestHandler->isAjax()){
				$conditions = array('ProgramResponse.program_id' => $id);
				if(!empty($this->params['url']['filter'])) {
					switch($this->params['url']['filter']) {
						case 'open':
							$conditions['ProgramResponse.complete'] = 0;
							$conditions['ProgramResponse.expired'] = 0; 
							$conditions['ProgramResponse.needs_approval'] = 0; 
							break;
						case 'closed':
							$conditions['ProgramResponse.complete'] = 1;
							break;
						case 'expired':
							$conditions['ProgramResponse.expired'] = 1;
							break;							
						case 'unapproved':
							$conditions['ProgramResponse.needs_approval'] = 1;
							break;		 
					}
				}

				$data['totalCount'] = $this->ProgramResponse->find('count', array('conditions' => $conditions));	

				$this->paginate = array('conditions' => $conditions);
				$responses =  $this->Paginate('ProgramResponse');
				if($responses) {
					$i = 0;
					foreach($responses as $response) {
						if($response['ProgramResponse']['complete'] == 1) {
							$status = 'Closed';
						}
						else {
							$status = 'Open';
						} 
	
						$data['responses'][$i] = array(
							'id' => $response['ProgramResponse']['id'],
							'User-lastname' => $response['User']['lastname'] . ', ' . 
								$response['User']['firstname'] . ' - ' . substr($response['User']['ssn'], -4),
							'created' => $response['ProgramResponse']['created'],
							'modified' => $response['ProgramResponse']['modified'],
							'status' => $status
						);
						if($this->params['url']['filter'] == 'closed'){
							$data['responses'][$i]['actions'] = 
								'<a href="/admin/program_responses/view/'. 
									$response['ProgramResponse']['id'].'">View</a>';							
						}
						elseif($this->params['url']['filter'] == 'expired'){
							$data['responses'][$i]['actions'] = 
								'<a href="/admin/program_responses/view/'. 
									$response['ProgramResponse']['id'].'">View</a> | ' . 
									'<a href="/admin/program_responses/toggle_expired/' . 
									$response['ProgramResponse']['id'] . '/0'.'" class="expire">Mark Un-Expired</a>';							
						}
						elseif($this->params['url']['filter'] == 'unapproved'){
							$data['responses'][$i]['actions'] = 
								'<a href="/admin/program_responses/view/'. 
									$response['ProgramResponse']['id'].'">View</a>';							
						}							
						else {
							$data['responses'][$i]['actions'] = 
								'<a href="/admin/program_responses/view/'. 
									$response['ProgramResponse']['id'].'">View</a> | ' .
									'<a href="/admin/program_responses/toggle_expired/' . 
									$response['ProgramResponse']['id'] . '/1'.'" class="expire">Mark Expired</a>';
						}
						$i++;		
					}				
				}
				else {
					$data['responses'] = array();
					$data['message'] = 'No results at this time.';
				}
				$data['success'] = true;
				$this->set('data', $data);
				$this->render('/elements/ajaxreturn');				
			}
			$this->ProgramResponse->Program->recursive = -1;
			$program = $this->ProgramResponse->Program->findById($id);
			if($program['Program']['approval_required'] == 1){
				$approvalPermission = $this->Acl->check(array(
					'model' => 'User', 
					'foreign_key' => $this->Auth->user('id')), 'ProgramResponses/admin_approve', '*');
				$this->set(compact('approvalPermission'));
			}			
		}	
	}

	function admin_view($id, $type=null) {
		$programResponse = $this->ProgramResponse->findById($id);	
		if($this->RequestHandler->isAjax()){			
			if($type == 'user') {
				$user = $programResponse['User'];
				$this->set(compact('user'));
 				$this->render('/elements/program_responses/user_info');
			}
			if($type == 'answers') {
				$yesNo = array('No', 'Yes');
				$data['answers'] = json_decode($programResponse['ProgramResponse']['answers'], true);
				$data['viewedMedia'] = $yesNo[$programResponse['ProgramResponse']['viewed_media']];
				if($programResponse['ProgramResponse']['answers'] != null) {
					$data['completedForm'] = 'Yes';
				}
				else $data['completedForm'] = 'No';
				$this->set($data);
				$this->render('/elements/program_responses/answers');
			}
			if($type == 'documents') {
				if(!empty($programResponse['ProgramResponseDoc'])) {
					$this->loadModel('DocumentFilingCategory');
					$filingCatList = $this->DocumentFilingCategory->find('list');
					$docs = Set::extract('/ProgramResponseDoc[paper_form<1]',  $programResponse);
					$filedForms = Set::extract('/ProgramResponseDoc[paper_form=1]',  $programResponse);
					$i = 0;
					foreach($docs as $doc) {
						$data['docs'][$i]['name'] = $filingCatList[$doc['ProgramResponseDoc']['cat_id']];
						$data['docs'][$i]['filedDate'] = $doc['ProgramResponseDoc']['created'];
						$data['docs'][$i]['link'] = '<a href="/admin/filed_documents/view/'.
							$doc['ProgramResponseDoc']['doc_id'] . '" target="_blank">View Doc</a>';
						$data['docs'][$i]['id'] = $doc['ProgramResponseDoc']['doc_id'];
						$i++;
					}							
				}
				else $data['docs'] = 'No program response documents filed for this user.';			
				$forms = $this->ProgramResponse->
					Program->ProgramPaperForm->findAllByProgramId($programResponse['Program']['id']);	
				if($forms) {
					$i = 0;	
					foreach($forms as $form) {
						if(isset($filedForms)) {
							foreach($filedForms as $filedForm) {
								if($filedForm['ProgramResponseDoc']['cat_id'] == $form['ProgramPaperForm']['cat_3']) {
									$data['forms'][$i]['link'] = 
										'<a class="generate" href="/admin/program_responses/generate_form/'. 
										$form['ProgramPaperForm']['id'] . '/' . 
										$programResponse['ProgramResponse']['id'] .'/'. 
										$filedForm['ProgramResponseDoc']['doc_id'] . '">Re-Generate</a>';
									$data['forms'][$i]['view'] = '<a href="/admin/filed_documents/view/' . 
										$filedForm['ProgramResponseDoc']['doc_id'].'" target="_blank">View Doc</a>';
									$data['forms'][$i]['doc_id'] = $filedForm['ProgramResponseDoc']['doc_id'];
									$data['forms'][$i]['filed_on'] = $filedForm['ProgramResponseDoc']['created'];	
								}
							}							
						}

						if(!isset($data['forms'][$i]['link'])) {
							$data['forms'][$i]['link'] = '<a class="generate" href="/admin/program_responses/generate_form/'. 
								$form['ProgramPaperForm']['id'] . '/' . 
								$programResponse['ProgramResponse']['id'] .'">Generate</a>';
						}
						$data['forms'][$i]['name'] = $form['ProgramPaperForm']['name'];
						$data['forms'][$i]['cat_3'] = $form['ProgramPaperForm']['cat_3'];
						$data['forms'][$i]['programResponseId'] = $programResponse['ProgramResponse']['id'];
						$data['forms'][$i]['id'] = $form['ProgramPaperForm']['id'];
						$i++;
					}					
				}							
				$this->set($data);			
				$this->render('/elements/program_responses/documents');
			}			
		}
		if($programResponse['Program']['approval_required'] && 
			$programResponse['ProgramResponse']['needs_approval'] == 1) {
				$approval = true;				
		}
		else {
			$approval = 'false';
		}
		$title_for_layout = 'Program Response';
		$this->set(compact('title_for_layout', 'approval'));
	}

	function admin_approve($programResponseId=null) {
		if($this->RequestHandler->isAjax()) {
			if(!$programResponseId) {
				$data['success'] = false;
				$data['message'] = 'Invalid program response id.';
			}
			else {
				$programResponse = $this->ProgramResponse->findById($programResponseId);
				$forms = $this->ProgramResponse->
					Program->ProgramPaperForm->findAllByProgramId($programResponse['Program']['id']);					
				if(!empty($programResponse['ProgramResponseDoc']) && !empty($forms)) {
					
					$catIds = Set::extract('/ProgramResponseDoc[paper_form=1]/cat_id', $programResponse);
					
					$formCatIds = Set::extract('/ProgramPaperForm/cat_3', $forms);
					
					if(!empty($formCatIds)) {
						$result = array_diff($formCatIds, $catIds);
						if(!empty($result)) {
							$data['success'] = false;
							$data['message'] = 'You must genertate all program forms before approving response.';
							$this->set(compact('data'));
							return $this->render(null, null, '/elements/ajaxreturn');						
						}
					}
				}				
				$this->data['ProgramResponse']['id'] = $programResponseId;
				$this->data['ProgramResponse']['needs_approval'] = 0;
				$this->data['ProgramResponse']['complete'] = 1;
				if($this->ProgramResponse->save($this->data)) {
					$data['success'] = true;
					$data['message'] = 'Program response was approved successfully.';
					$programEmail = $this->ProgramResponse->Program->ProgramEmail->find('first', 
						array('conditions' => array(
							'ProgramEmail.program_id' => $programResponse['Program']['id'],
							'ProgramEmail.type' => 'final'
					)));
					$user['User'] = $programResponse['User'];
					$this->Notifications->sendProgramEmail($programEmail, $user);					
				}
				else {
					$data['success'] = false;
					$data['message'] = 'An error occured, please try again.'; 
				}
				$this->set(compact('data'));
				$this->render(null, null, '/elements/ajaxreturn');
			}
		}	
	}

	function admin_generate_form($formId, $programResponseId, $docId=null) {
		if($this->RequestHandler->isAjax()) {
				
			$programResponse = $this->ProgramResponse->findById($programResponseId);
			
			$data = $programResponse['User'];
			
			$programPaperForm = $this->ProgramResponse->Program->ProgramPaperForm->findById($formId);	
		
			$answers = json_decode($programResponse['ProgramResponse']['answers'], true);
			
			foreach($answers as $k => $v) {
				$data[$k] = $v;
			}
						
			$data['dob'] = date('m/d/Y', strtotime($data['dob']));		
			$data['admin'] = $this->Auth->user('firstname') . ' ' . $this->Auth->user('lastname');
			$data['todays_date'] = date('m/d/Y');
			
			if($programPaperForm) {				
				$pdf = $this->_createPDF($data, $programPaperForm['ProgramPaperForm']['template']);
				if($pdf) {
					$this->loadModel('FiledDocument');
					if(!$docId) {
						$this->FiledDocument->User->QueuedDocument->create();
						$this->FiledDocument->User->QueuedDocument->save();
						$docId = $this->FiledDocument->User->QueuedDocument->getLastInsertId();
						// delete the empty record so it does not show up in the queue
						$this->FiledDocument->User->QueuedDocument->delete($docId);						
					}
					else {
						$this->data['ProgramResponseDoc']['id'] = 
						$this->ProgramResponse->ProgramResponseDoc->field('id', array(
								'ProgramResponseDoc.doc_id' => $docId,
								'ProgramResponseDoc.program_response_id' => $programResponseId));
					}
									
					$this->data['FiledDocument']['id'] = $docId;
					$this->data['FiledDocument']['filename'] = $pdf;
					$this->data['FiledDocument']['admin_id'] = $this->Auth->user('id');
					$this->data['FiledDocument']['user_id'] = $data['id'];
					$this->data['FiledDocument']['filed_location_id'] = $this->Auth->user('location_id');
					$this->data['FiledDocument']['cat_1'] = $programPaperForm['ProgramPaperForm']['cat_1'];
					$this->data['FiledDocument']['cat_2'] = $programPaperForm['ProgramPaperForm']['cat_2'];
					$this->data['FiledDocument']['cat_3'] = $programPaperForm['ProgramPaperForm']['cat_3'];
					$this->data['FiledDocument']['entry_method'] = 'Program Generated';
					$this->data['FiledDocument']['last_activity_admin_id'] = $this->Auth->user('id');					
					$this->data['ProgramResponseDoc']['cat_id'] = $programPaperForm['ProgramPaperForm']['cat_3'];
					$this->data['ProgramResponseDoc']['program_response_id'] =  $programResponseId;
					$this->data['ProgramResponseDoc']['doc_id'] = $docId;
					$this->data['ProgramResponseDoc']['paper_form'] = 1;
					if($programPaperForm['ProgramPaperForm']['cert']) {
						$this->data['ProgramResponseDoc']['cert'] = 1;
					}									
					if($this->FiledDocument->save($this->data['FiledDocument']) && 
					$this->ProgramResponse->ProgramResponseDoc->save($this->data['ProgramResponseDoc'])) {
						$data['success'] = true;
						$data['message'] = 'Form generated and filed successfully.';
					}
					else {
						$data['success'] = false;
						$data['message'] = 'Unable to file form at this time.';
						$path = Configure::read('Document.storage.uploadPath');
						$path .= substr($pdf, 0, 4) . DS;
						$path .= substr($pdf, 4, 2) . DS;
						$file = $path . $pdf;
						unlink($file);
					}			
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to create pdf form at this time.';						
				}	
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}		
	}

	function admin_toggle_expired($programResponseId, $expired) {
		if($this->RequestHandler->isAjax()) {
			$this->data['ProgramResponse']['id'] = $programResponseId;
			$this->data['ProgramResponse']['expired'] = $expired;	
			if($this->ProgramResponse->save($this->data)) {
				$data['success'] = true;
				switch($expired) {
					case 0:
						$data['message'] = 'Response marked un-expired successfully.';
						break;
					case 1:
						$data['message'] = 'Response marked expired successfully.';
						break;	
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'An error has occured, please try again.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');			
		}
	}

	function _createFDF($file,$info){
	    $data="%FDF-1.2\n%����\n1 0 obj\n<< \n/FDF << /Fields [ ";
	    foreach($info as $field => $val){
	        if(is_array($val)){
	            $data.='<</T('.$field.')/V[';
	            foreach($val as $opt)
	                $data.='('.trim($opt).')';
	            $data.=']>>';
	        }else{
	            $data.='<</T('.$field.')/V('.trim($val).')>>';
	        }
	    }
	    $data.="] \n/F (".$file.") /ID [ <".md5(time()).">\n] >>".
	        " \n>> \nendobj\ntrailer\n".
	        "<<\n/Root 1 0 R \n\n>>\n%%EOF\n";
	    return $data;
	}
	
	function _createPDF($data, $template){
		
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
		$fdfFile = date('YmdHis') . rand(0, pow(10, 7)) . '.fdf';	
		// pdf 	file named the same as the fdf file 
		$pdfFile = str_replace('.fdf', '.pdf', $fdfFile);
	   
	    // the temp location to write the fdf file to
	    $fdfDir = TMP . 'fdf';
	    
	    // need to know what file the data will go into
	    $pdfTemplate = APP . 'storage' . DS . 'program_forms' . DS . $template;
	    
	    // generate the file content
	    $fdfData = $this->_createFDF($pdfTemplate,$data);
	
	    // write the file out
	    if($fp=fopen($fdfDir.DS.$fdfFile,'w')){
	        fwrite($fp,$fdfData,strlen($fdfData));
	    }
	    fclose($fp);
		
		$pdftkCommandString = DS . 'usr' . DS . 'bin' . DS . 'pdftk ' . APP . 'storage' . DS . 'program_forms' . DS . 
			$template . ' fill_form ' . TMP . 'fdf' . DS . $fdfFile . ' output ' . $path . DS . $pdfFile . ' flatten';	
				
		passthru($pdftkCommandString, $return);
		
		if($return == 0) {
			// delete fdf if pdf was created and filed successfully 
			unlink($fdfDir . DS . $fdfFile);
			return $pdfFile;		
		}
		else return false;				
	}
	
}
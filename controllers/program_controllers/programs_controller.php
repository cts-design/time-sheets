<?php

class ProgramsController extends AppController {
	
	var $name = 'Programs';
	
	function beforeFilter() {
		parent::beforeFilter();
		$validate = array('viewed_media' => array('rule' => array('comparison', '>', 0), 'message' => 'You must agree'));	
		$this->Program->ProgramResponse->modifyValidate($validate);
	}
			
	function index($id = null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect('/');
		}
		$program = $this->Program->findById($id);
		if($program['Program']['disabled'] == 1){
			$this->Session->setFlash(__('This program is disabled', true), 'flash_failure');
			$this->redirect('/');
		}
		switch($program['Program']['type']){
			case "docs": 
				break;
			case "form":
				break;
			case "form_docs": 
				break;
			case "pdf":
				break;
			case "pdf_form":
				break;
			case "pdf_docs":
				break;
			case "pdf_form_docs":
				break;
			case "uri":
				break;
			case "uri_form":
				break;					
			case "uri_docs":
				break;				
			case "uri_form_docs":
				break;								
			case "video": 
				$element = '/programs/video';
				break;
			case "video_form":
				$element = '/programs/video';
				break;
			case "video_docs":
				$element = '/programs/video';
				break;				
			case "video_form_docs":
				$data['redirect'] = '/programs/view_media/' . $program['Program']['id'];
				$this->Session->write('step2', 'form');
				break;					
		}

		$data['title_for_layout'] = $program['Program']['name'];
		$data['program'] = $program;
		$this->set($data);
	}

	function get_started() {
		if(!empty($this->data)) {
			$this->data['ProgramResponse']['user_id'] = $this->Auth->user('id');
			if($this->Program->ProgramResponse->save($this->data)){
				$this->redirect($this->data['Program']['redirect']);
			}
		}
	}

	function view_media($id=null) {
		if(!$id) {
			$this->Session->setFlash(__('Invalid Program Id', true), 'flash_failure');
			$this->redirect('/');
		}
		if(!empty($this->data)) {
			$this->Program->ProgramResponse->create();
			$this->data['ProgramResponse']['user_id'] =	$this->Auth->user('id');
			if($this->Program->ProgramResponse->save($this->data, true)) {
				$this->Session->setFlash(__('Saved', true), 'flash_success');
				switch($this->Session->read('step2')) {
					case "form":
						$this->redirect(array('controller' => 'program_responses', 'action' => 'index', $id));
						break;
					case "docs":
						$this->redirect(array('controller' => 'program_responses', 'action' => 'required_docs', $id));
						break;
					case "complete":
						$this->redirect(array('controller' => 'program_responses', 'action' => 'submission_recieved', $id));		
						break;
				}
				$this->redirect($this->data['Program']['redirect']);
			}
			else {
				$this->Session->setFlash(__('You must check the I agree box.', true), 'flash_failure');		
			}
		}		
		$program = $this->Program->findById($id);
		$data['element'] = '/programs/video'; 
		$data['media'] = '/programs/load_media/' . $program['Program']['id'];
		$data['instructions'] = $program['Program']['media_instructions'];
		$data['title_for_layout'] = $program['Program']['name'];
		$this->set($data);		
	}

	function load_media($id=null) {
		if(!$id){
			$this->Session->setFlash(__('Invalid id', true), 'flash_failure');
			$this->redirect($this->referer());	
		} 
		$this->view = 'Media';
		$this->Program->id = $id;
		$path = $this->Program->field('media');		
		$explode = explode('.', $path);
		$params = array(
		    'id' => $path,
		    'name' => $explode[0],
		    'extension' => $explode[1],
		    'path' => Configure::read('Program.media.path')
		);
		$this->set($params);
		return $params;
    }
				
	function admin_index() {
		$title_for_layout = 'Programs';
	}
	
	
}
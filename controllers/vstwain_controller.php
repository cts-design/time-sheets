<?php

class VstwainController extends AppController
{
	public $uses = array();
	public $name = 'Vstwain';
	
	public function beforeFilter()
	{
		$this->Auth->allowedActions = array('kiosk_upload_image', 'kiosk_getPreviewImage');
	}
	
	public function kiosk_upload_image()
	{
		$this->log("Access attempt");

		if( isset($_FILES['file']) )
		{
			$file = $_FILES['file'];
			$this->log( var_export($file, true) );
			
			//Generate name for temporary file
			$user_id = ( $this->Auth->User('id') == NULL ? 0 : $this->Auth->User('id') );
			$file_name = date('YmdHis') . '_' . $user_id . '.jpg';
			$file_location = APP . 'storage' . DS . 'session_documents' . DS;
			
			//Save file as a session document
			if( move_uploaded_file($file['tmp_name'], $file_location . $file_name) )
			{
				$this->loadModel('SessionDocument');
				$this->SessionDocument->create();
				$session_document_id = $this->Session->read('session_document_id');
			
				
				$session_document = array(
					'files' => $file_location . $file_name,
					'user_id' => $user_id,
					'meta' => var_export($file, true),
				);
			
				if($session_document_id != NULL)
				{
					$session_document['id'] = $session_document_id;
					$this->SessionDocument->save($session_document);
				}
				else
				{
					$this->SessionDocument->save($session_document);
					$this->Session->write('session_document_id', $this->SessionDocument->getLastInsertId());
				}
			
				
			}
			else
			{
				$this->log("Could not save the file to the folder, that means it was also not inserted into the database");
			}
		}
		exit();
	}
	
	public function kiosk_getPreviewImage()
	{
		
	}
}
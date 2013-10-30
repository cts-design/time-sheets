<?php

class VstwainController extends AppController
{
	public $uses = array();
	public $name = 'Vstwain';
	
	public function beforeFilter()
	{
		$this->Auth->allowedActions = array(
			'kiosk_upload_image', 'kiosk_getPreviewImage', 
			'kiosk_get_last_id', 'kiosk_get_latest_preview', 'kiosk_merge_images',
			'kiosk_delete_last'
		);
	}
	
	public function kiosk_upload_image()
	{
		$this->autoRender = FALSE;
		
		$this->log("Accessing action");

		if( isset($_FILES['file']) )
		{
			$file = $_FILES['file'];
			
			$user_id = $_POST['user_id'];
			
			$this->log("Uploading image with id: " . $_POST['user_id']);
			
			$file_name = date('YmdHis') . '_' . $user_id . '.jpg';
			$file_location = APP . 'webroot' . DS . 'storage' . DS;
			
			//Save file as a session document
			if( move_uploaded_file($file['tmp_name'], $file_location . $file_name) )
			{
				$this->loadModel('PartialDocument');
				$meta = array(
					'queue_category_id' => 1,
					'scanned_location_id' => 1,
					'self_scan_cat_id' => 1
				);
				$partial_doc = array(
					'file_location' => $file_location . $file_name,
					'web_location' => DS . 'storage' . DS . $file_name,
					'meta' => json_encode($meta),
					'created' => date('Y-m-d H:i:s'),
					'expires' => date('Y-m-d H:i:s', strtotime( Configure::read('PartialDocumentSessionTimeout') )),
					'user_id' => $user_id
				);
				
				$this->PartialDocument->create();
				$is_saved = $this->PartialDocument->save($partial_doc);
				
				if( !$is_saved || $is_saved == NULL)
				{
					$this->log("Could not save data");
				}
			}
			else
			{
				$this->log("Could not save the file to the folder, that means it was also not inserted into the database");
			}
		}
		else
		{
			$this->log("file was not set inside of the _FILES global variable");
		}
	}
	
	public function kiosk_get_latest_preview()
	{
		$this->loadModel('PartialDocument');
		$this->autoRender = false;
		
		$user_id = $this->params['url']['user_id'];
		
		$image = $this->PartialDocument->find('first', array(
			'conditions' => array(
				'user_id' => $user_id,
			),
			'order' => array(
				'expires DESC'
			)
		));
		
		
		if( !$image )
		{
			$message = array(
				'success' => FALSE,
				'output' => $image
			);
		}
		else
		{
			$message = array(
				'success' => TRUE,
				'output' => $image
			);
		}
		
		echo json_encode($message);
	}
	
	public function kiosk_merge_images()
	{	
		$this->loadModel('PartialDocument');
		$this->loadModel('SelfScanCategory');
		$this->autoRender = false;
		
		$self_scan_cat_id = $this->params['url']['self_scan_cat_id'];
		$scanned_location_id = $this->params['url']['scanned_location_id'];
		
		$user_id = $this->params['url']['user_id'];
		
		$images = $this->getImages($user_id);
		
		if($images != NULL && $images != FALSE)
		{
			$data = $this->writePdfFile($images);

			$save['filename'] = $data['path'] . $data['docName'];
			$save['entry_method'] = 'Self Scan';
			$save['user_id'] = $user_id;
			$save['self_scan_cat_id'] = $self_scan_cat_id;
			$save['scanned_location_id'] = $scanned_location_id;
			
			$id = $this->savePdfDocument($save);
		
			$this->SelfScanCategory->recursive = -1;
			$selfScanCat = $this->SelfScanCategory->findById( $self_scan_cat_id );
		
			$this->sendSelfScanAlert($this->Auth->user(), $id, $scanned_location_id);
		
			$this->sendSelfScanCategoryAlert($this->Auth->user(), $selfScanCat, $id, $scanned_location_id);
		
			$this->Transaction->createUserTransaction($save['entry_method'], null, $scanned_location_id, 'Self scanned document ID ' . $id);
		
			$this->PartialDocument->deleteAll(array('user_id' => $user_id));
		
			$message = array(
				'success' => TRUE,
				'output' => 'Document saved in filesystem and database'
			);
			
			echo json_encode($message);
		}
		else
		{
			$message = array(
				'success' => FALSE,
				'output' => 'There were no pending documents'
			);
			
			echo json_encode($message);
		}
	}
	
	public function kiosk_delete_last()
	{
		$this->autoRender = false;
		if($this->RequestHandler->isAjax())
		{
			$this->loadModel('PartialDocument');
			
			$user_id = $this->params['url']['user_id'];
			
			$image = $this->PartialDocument->find('first', array(
				'conditions' => array(
					'user_id' => $user_id,
				),
				'order' => array('expires DESC')
			));
			
			if($image == NULL || !$image)
			{
				$message = array(
					'success' => FALSE,
					'output' => "Could not find last submitted image"
				);
				
				echo json_encode($message);
				return;
			}
			
			$is_deleted = unlink($image['PartialDocument']['file_location']);
			
			
			if($is_deleted)
			{
				$this->PartialDocument->query(
					"DELETE FROM partial_documents WHERE user_id = " . $user_id . " ORDER BY expires DESC LIMIT 1"
				);
				
				//Selects new last image and updates it's expiration to the last image's expiration.
				
				$second_last_image = $this->PartialDocument->find('first', array(
					'conditions' => array(
						'user_id' => $user_id,
					),
					'order' => array('expires DESC')
				));
				
				$second_last_image['PartialDocument']['expires'] = $image['PartialDocument']['expires'];
				
				$this->PartialDocument->create();
				$this->PartialDocument->save($second_last_image);
				
				$message = array(
					'success' => TRUE,
					'output' => "The image was deleted"
				);
				
				echo json_encode($message);
			}
			else
			{
				$message = array(
					'success' => FALSE,
					'output' => "Could not delete the image"
				);
				
				echo json_encode($message);
			}
		}
		else
		{
			$message = array(
				'success' => FALSE,
				'output' => "Was not an ajax call"
			);
			
			echo json_encode($message);
		}
	}

 	private function getImages($images)
	{	
		$this->loadModel('PartialDocument');
		
		$user_id = $this->params['url']['user_id'];
		
		$images = $this->PartialDocument->find('first', array(
			'conditions' => array(
				'user_id' => $user_id,
				'expires >' => date('Y-m-d H:i:s')
			),
			'order' => array(
				'expires ASC'
			)
		));
		
		if( $images )
		{
			$images = $this->PartialDocument->find('all', array(
				'conditions' => array(
					'user_id' => $user_id
				),
				'order' => array(
					'expires ASC'
				)
			));
			
			return $images;
		}
		else
		{
			$this->removeUserMedia($user_id);
			return FALSE;
		} //end else of if
	} //end function
	
	private function writePdfFile($images)
	{
		//HTML generated from looping through images
		$html 		= "";
		
		//File's new name when saved in the storage folder
		$docName 	= "";
		
		//Loaded MPDF library which we use to merge original images into the pdf
		require(APP . 'vendors' . DS . 'MPDF54' . DS . 'mpdf.php');
		$mpdf 		= new MPDF();
		$mpdf->debug= FALSE;
		
		//Path to save PDF from merged images
		$path = Configure::read('Document.storage.absolutePath') . date('Y') . DS . date('m') . DS;
		
		foreach($images as $image)
		{
			$html .= "<img src=\"" . $image['PartialDocument']['web_location'] . "\" />";
		}
		$html = '<HEAD></HEAD><BODY>' . $html . '</BODY>';
		
		ob_start();
		$mpdf->WriteHtml($html);
		
		if(!file_exists($path))
		{
		    mkdir($path, 0777, TRUE);
		}
		
		$docName = date('YmdHis') . rand(0, pow(10, 7)) . '.pdf';

		$is_saved = $mpdf->Output($path . $docName, 'F');
		ob_end_clean();
		
		return array('docName' => $docName, 'is_saved' => $is_saved, 'path' => $path);
	}
	
	private function savePDFDocument($save, $meta = NULL)
	{
		//filename, entry_method
		$this->loadModel('QueuedDocument');
		$this->QueuedDocument->create();
		$this->QueuedDocument->save($save);
		
		return $this->QueuedDocument->getLastInsertId();
	}
	
	private function removeUserMedia($user_id)
	{
		$this->loadModel('PartialDocument');
		
		$user_id = $this->params['url']['user_id'];
		
		$images = $this->PartialDocument->find('all', array(
			'conditions' => array(
				'user_id' => $user_id
			)
		));
		
		foreach($images as $image)
		{
			$is_deleted = unlink($image['PartialDocument']['file_location']);
			
			if(!$is_deleted)
			{
				$this->log("Was not able to remove file at: " . $image['PartialDocument']['file_location']);
			}
			else
			{
				$this->PartialDocument->delete($image['PartialDocument']['id']);
			}
		}
	}
	
	
	/*
	* Methods taken from kiosks_controller
	*/
	
	private function sendSelfScanAlert($user, $docId, $locationId) {
		$this->loadModel('Alert');
		$data = $this->Alert->getSelfScanAlerts($user, $docId, $locationId);
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
				$this->Email->subject = 'Self Scan alert';
				$this->Email->send($alert['message'] . "\r\n" . $alert['url']);				
			}
		}
	}	

	private function sendSelfScanCategoryAlert($user, $selfScanCat, $docId, $locationId) {
		$this->loadModel('Alert');
		$data = $this->Alert->getSelfScanCategoryAlerts($user, $selfScanCat, $docId, $locationId);
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
				$this->Email->subject = 'Self Scan Category alert';
				$this->Email->send($alert['message'] . "\r\n" . $alert['url']);				
			}
		}
	}
}
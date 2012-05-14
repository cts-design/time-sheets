<?php 
App::import('Vendor', 'wkhtmltopdf/wkhtmltopdf');

class DocumentGenerationWorkerTask extends QueueShell {
	public $uses = array('Queue.Job', 'FiledDocument');
	public $tubes = array('pdf_snapshot');
	
	public function execute() {
		while(true) {
			$this->out('Waiting for a job....');	
			$job = $this->Job->reserve(array('tube' => $this->tubes));
			if(!$job) {
				$this->log('Invalid job found. Not processing.', 'error');	
			}
			else {
				$this->out('Processing job ' . $job['Job']['id']);
				switch($job['Job']['ProgramDocument']['type']) {
					case 'snapshot':
						$processed = $this->processSnapshot($job['Job']);	
						break;
				}
				if($processed) {
					$this->out('Job ' . $job['Job']['id'] . ' processed.');
					if($this->Job->delete()) {
						$this->out('Job ' . $job['Job']['id'] . ' deleted from queue.');
					}
				}
				else {
					$this->out('Unable to process job ' .  $job['Job']['id'] . 'burying.');
					$this->Job->bury(6000);
				}
			}
		}
	}

	private function processSnapshot($data) {
		if($data){
			$html = $this->getSnapshotHtml($data);
			$path = $this->getPath();
			try {
				$pdf = new WKPDF_MULTI();
				$pdf->add_html($html);
				$pdf->set_toc($data['toc']);
				$pdf->args_add('--header-spacing', '5');
				$pdf->args_add('--header-left', $data['user']);
				$pdf->args_add('--header-center', '[date]');
				$pdf->args_add('--header-right', Configure::read('Company.name'));
				$pdf->args_add('--footer-center', 'Page: [page] of [topage]') ;
				Configure::write('debug', 0);
				$pdf->render();
				$pdfFile = date('YmdHis') . rand(0, pow(10, 7)) . '.pdf';
				$pdf->output(WKPDF_MULTI::$PDF_SAVEFILE, $path . $pdfFile);
			}
			catch(Exception $e) {
				// TODO probably just want to log this to the error log ??? 
				$this->log('WKPDF Exception (line ' . $e->getLine() .'): ' . $e->getMessage(), 'error');
				return false;
			}
			if (file_exists($path . $pdfFile)) {
				$this->FiledDocument->User->QueuedDocument->create();
				$this->FiledDocument->User->QueuedDocument->save();
				$docId = $this->FiledDocument->User->QueuedDocument->getLastInsertId();
				// delete the empty record so it does not show up in the queue
				$this->FiledDocument->User->QueuedDocument->delete($docId, false);
				$this->data['FiledDocument']['id'] = $docId;
				$this->data['FiledDocument']['user_id'] = $data['userId']; 
				$this->data['FiledDocument']['filename'] = $pdfFile;
				$this->data['FiledDocument']['cat_1'] = $data['ProgramDocument']['cat_1'];
				$this->data['FiledDocument']['cat_2'] = $data['ProgramDocument']['cat_2'];
				$this->data['FiledDocument']['cat_3'] = $data['ProgramDocument']['cat_3'];
				$this->data['FiledDocument']['entry_method'] = 'Program Generated';
				$this->data['FiledDocument']['filed'] = date('Y-m-d H:i:s');
				$this->data['ProgramResponseDoc']['program_response_id'] = $data['responseId'];
				$this->data['ProgramResponseDoc']['type'] = 'snapshot';
				$this->data['ProgramResponseDoc']['doc_id'] = $docId;
				if($this->FiledDocument->saveAll($this->data)) {
					return true;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
	}

	private function getSnapshotHtml($data) {
		$html = '';
		foreach($data['steps'] as $step) {
			$html .= '<h2>' . $step['name'] . '</h2>';
			$html .= '<ol>';
			foreach($step['answers'] as $k => $v) {
				$html .= '<li><span class="question">' . Inflector::humanize($k); 
				$html .= ':&nbsp;</span><span class="answer">' . Inflector::humanize($v) . '</span>';
			}
			$html .= '</ol>';
		}
		return $html;
	}

	private function generateForm($formId, $programResponseId, $docId=null) {
		$programResponse = $this->ProgramResponse->findById($programResponseId);
		foreach($programResponse['User'] as $k => $v) {
			if(!preg_match('[\@]', $v)) {
				$programResponse['User'][$k] = ucwords($v);
			}
		}
		$data = $programResponse['User'];
		$programPaperForm = $this->ProgramResponse->Program->ProgramPaperForm->findById($formId);
		if($programResponse['ProgramResponse']['answers']) {
			$answers = json_decode($programResponse['ProgramResponse']['answers'], true);

			foreach($answers as $k => $v) {
				if(!preg_match('[\@]', $v)) {
					$data[$k] = ucwords($v);
				}
			}
		}
		$data['masked_ssn'] = '***-**-' . substr($data['ssn'], -4);
		$data['confirmation_id'] = $programResponse['ProgramResponse']['confirmation_id'];
		$data['dob'] = date('m/d/Y', strtotime($data['dob']));
		$data['admin'] = $this->Auth->user('firstname') . ' ' . $this->Auth->user('lastname');
		$data['todays_date'] = date('m/d/Y');
		$data['form_completed'] = date('m/d/Y', strtotime($programResponse['ProgramResponse']['created']));
		$data['program_name'] = $programResponse['Program']['name'];
		if($programPaperForm) {
			$pdf = $this->_createPDF($data, $programPaperForm['ProgramPaperForm']['template']);
			if($pdf) {
				$this->loadModel('FiledDocument');
				if(!$docId) {
					$this->FiledDocument->User->QueuedDocument->create();
					$this->FiledDocument->User->QueuedDocument->save();
					$docId = $this->FiledDocument->User->QueuedDocument->getLastInsertId();
					// delete the empty record so it does not show up in the queue
					$this->FiledDocument->User->QueuedDocument->delete($docId, false);
					$genType = 'Generated';
				}
				else {
					$this->data['ProgramResponseDoc']['id'] =
					$this->ProgramResponse->ProgramResponseDoc->field('id', array(
							'ProgramResponseDoc.doc_id' => $docId,
							'ProgramResponseDoc.program_response_id' => $programResponseId));
					$genType = 'Regenerated';
				}

				$this->data['FiledDocument']['id'] = $docId;
				$this->data['FiledDocument']['created'] = date('Y-m-d H:i:s');
				$this->data['FiledDocument']['filename'] = $pdf;
				if($this->Auth->user('role_id')!= 1) {
					$this->data['FiledDocument']['admin_id'] = $this->Auth->user('id');
					$this->data['FiledDocument']['filed_location_id'] = $this->Auth->user('location_id');
					$this->data['FiledDocument']['last_activity_admin_id'] = $this->Auth->user('id');
				}
				$this->data['FiledDocument']['user_id'] = $data['id'];
				$this->data['FiledDocument']['cat_1'] = $programPaperForm['ProgramPaperForm']['cat_1'];
				$this->data['FiledDocument']['cat_2'] = $programPaperForm['ProgramPaperForm']['cat_2'];
				$this->data['FiledDocument']['cat_3'] = $programPaperForm['ProgramPaperForm']['cat_3'];
				$this->data['FiledDocument']['entry_method'] = 'Program Generated';
				$this->data['FiledDocument']['filed'] = date('Y-m-d H:i:s');
				$this->data['ProgramResponseDoc']['created'] = date('Y-m-d H:i:s');
				$this->data['ProgramResponseDoc']['cat_id'] = $programPaperForm['ProgramPaperForm']['cat_3'];
				$this->data['ProgramResponseDoc']['program_response_id'] =	$programResponseId;
				$this->data['ProgramResponseDoc']['doc_id'] = $docId;
				$this->data['ProgramResponseDoc']['paper_form'] = 1;
				if($programPaperForm['ProgramPaperForm']['cert']) {
					$this->data['ProgramResponseDoc']['cert'] = 1;
				}
				if($this->FiledDocument->save($this->data['FiledDocument']) &&
				$this->ProgramResponse->ProgramResponseDoc->save($this->data['ProgramResponseDoc'])) {
					return array($programPaperForm, $programResponse, $genType);
				}
				else {
					$path = Configure::read('Document.storage.uploadPath');
					$path .= substr($pdf, 0, 4) . DS;
					$path .= substr($pdf, 4, 2) . DS;
					$file = $path . $pdf;
					unlink($file);
					return false;
				}
			}
			else {
				return false;
			}
		}
	}

	private	function createFDF($file,$info){
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

	private function createPDF($data, $template){

		$path = $this->getPath();
		// build our fancy unique filename
		$fdfFile = date('YmdHis') . rand(0, pow(10, 7)) . '.fdf';
		// pdf	file named the same as the fdf file
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

	private function getPath() {
		// get the document relative path to the inital storage folder
		$path = Configure::read('Document.storage.uploadPath');
		// check to see if the directory for the current year exists
		if(!file_exists($path . date('Y') . DS)){
			// if directory does not exist, create it
			mkdir($path . date('Y'), 0755);
		}
		// add the current year to our path string
		$path .= date('Y') . DS;
		// check to see if the directory for the current month exists
		if(!file_exists($path . date('m') . DS)){
			// if directory does not exist, create it
			mkdir($path . date('m'), 0755);
		}
		// add the current month to our path string
		$path .= date('m') . DS;
		return $path;
	}
}

<?php 
App::import('Vendor', 'wkhtmltopdf/wkhtmltopdf');

class QueueDocumentTask extends Shell {
	public $uses = array('Queue.QueuedTask', 'FiledDocument');
	
	public function run($data) {
		if($data['ProgramDocument']['type'] === 'snapshot' || $data['ProgramDocument']['type'] === 'multi_snapshot') {
			return  $this->generateSnapshot($data);	
		}
		else {
			return $this->generateProgramDoc($data);
		}
	}

	private function generateSnapshot($data) {
		if($data){
			$html = $this->getSnapshotHtml($data);
			$path = $this->getPath();
			try {
				$pdf = new WKPDF_MULTI();
				$pdf->add_html($html);
				$pdf->set_toc($data['toc']);
				$pdf->args_add('--header-spacing', '5');
				$pdf->args_add('--header-left', $data['User']['name_last4']);
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
				$this->data['FiledDocument']['user_id'] = $data['User']['id']; 
				$this->data['FiledDocument']['filename'] = $pdfFile;
				$this->data['FiledDocument']['cat_1'] = $data['ProgramDocument']['cat_1'];
				$this->data['FiledDocument']['cat_2'] = $data['ProgramDocument']['cat_2'];
				$this->data['FiledDocument']['cat_3'] = $data['ProgramDocument']['cat_3'];
				$this->data['FiledDocument']['filed'] = date('Y-m-d H:i:s');
				$this->data['FiledDocument']['entry_method'] = 'Program Generated'; 
				$this->data['ProgramResponseDoc']['program_response_id'] = $data['ProgramResponse']['id'];
				$this->data['ProgramResponseDoc']['type'] = 'system_generated';
				$this->data['ProgramResponseDoc']['doc_id'] = $docId;
				$this->data['ProgramResponseDoc']['program_doc_id'] = $data['ProgramDocument']['id']; 
				if($data['ProgramDocument']['cat_3']) {
					$this->data['ProgramResponseDoc']['cat_id'] = $data['ProgramDocument']['cat_3'];
				}
				elseif($data['ProgramDocument']['cat_2']) {
					$this->data['ProgramResponseDoc']['cat_id'] = $data['ProgramDocument']['cat_2'];
				}
				else {
					$this->data['ProgramResponseDoc']['cat_id'] = $data['ProgramDocument']['cat_1'];
				}
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

	private function generateProgramDoc($data) {
		foreach($data['User'] as $k => $v) {
			if(!preg_match('[\@]', $v)) {
				$data['User'][$k] = ucwords($v);
			}
		}
		$pdfData = $data['User'];
		if(!empty($data['steps'])) {
			$i = 0;
			foreach($data['steps'] as $step) {
				foreach($step['answers'] as $k => $v) {
					if(!preg_match('[\@]', $v)) {
						$pdfData['steps'][$i]['answers'][$k] = ucwords($v);
					}
				}
			$i++;
			}
		}
		$pdfData['masked_ssn'] = '***-**-' . substr($data['User']['ssn'], -4);
		$pdfData['confirmation_id'] = $data['ProgramResponse']['confirmation_id'];
		$pdfData['dob'] = date('m/d/Y', strtotime($data['User']['dob']));
		if(!empty($data['Admin'])) {
			$pdfData['admin'] = $this->Auth->user('firstname') . ' ' . $this->Auth->user('lastname');
		}
		$pdfData['todays_date'] = date('m/d/Y');
		// TODO possibly look into a more specific date for the form completed date
		$pdfData['form_completed'] = date('m/d/Y', strtotime($data['ProgramResponse']['created']));
		$pdfData['program_name'] = $data['Program']['name'];
		if(!empty($data['ProgramDocument'])) {
			$pdf = $this->createPDF($pdfData, $data['ProgramDocument']['template']);
			if($pdf) {
				if(empty($data['docId'])) {
					$this->FiledDocument->User->QueuedDocument->create();
					$this->FiledDocument->User->QueuedDocument->save();
					$data['docId'] = $this->FiledDocument->User->QueuedDocument->getLastInsertId();
					// delete the empty record so it does not show up in the queue
					$this->FiledDocument->User->QueuedDocument->delete($data['docId'], false);
					$genType = 'Generated';
				}
				else {
					$this->data['ProgramResponseDoc']['id'] =
					$this->ProgramResponse->ProgramResponseDoc->field('id', array(
							'ProgramResponseDoc.doc_id' => $data['docId'],
							'ProgramResponseDoc.program_response_id' => $data['ProgramResponse']['id']));
					$genType = 'Regenerated';
				}

				$this->data['FiledDocument']['id'] = $data['docId'];
				$this->data['FiledDocument']['filename'] = $pdf;
				if(!empty($data['Admin'])) {
					$this->data['FiledDocument']['admin_id'] = $data['Admin']['id'];
					$this->data['FiledDocument']['filed_location_id'] = $data['Admin']['location_id'];
					$this->data['FiledDocument']['last_activity_admin_id'] = $data['Admin']['id'];
				}
				$this->data['FiledDocument']['user_id'] = $data['User']['id'];
				$this->data['FiledDocument']['cat_1'] = $data['ProgramDocument']['cat_1'];
				$this->data['FiledDocument']['cat_2'] = $data['ProgramDocument']['cat_2'];
				$this->data['FiledDocument']['cat_3'] = $data['ProgramDocument']['cat_3'];
				$this->data['FiledDocument']['filed'] = date('Y-m-d H:i:s');
				$this->data['FiledDocument']['entry_method'] = 'Program Generated';
				if($data['ProgramDocument']['cat_3']) {
					$this->data['ProgramResponseDoc']['cat_id'] = $data['ProgramDocument']['cat_3'];
				}
				elseif($data['ProgramDocument']['cat_2']) {
					$this->data['ProgramResponseDoc']['cat_id'] = $data['ProgramDocument']['cat_2'];
				}
				else {
					$this->data['ProgramResponseDoc']['cat_id'] = $data['ProgramDocument']['cat_1'];
				}
				$this->data['ProgramResponseDoc']['program_response_id'] = $data['ProgramResponse']['id'];
				$this->data['ProgramResponseDoc']['doc_id'] = $data['docId'];
				$this->data['ProgramResponseDoc']['type'] = 'system_generated';
				$this->data['ProgramResponseDoc']['program_doc_id'] = $data['ProgramDocument']['id']; 
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

	private	function createFDF($file,$info){
		$data="%FDF-1.2\n%����\n1 0 obj\n<< \n/FDF << /Fields [ ";
		foreach($info as $field => $val){
			if(is_array($val)){
				$data.='<</T('.$field.')/V[';
				foreach($val as $opt)
					if(!is_array($opt)) {
						$data.='('.trim($opt).')';
					}
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
		$fdfData = $this->createFDF($pdfTemplate,$data);
		// write the file out
		if($fp=fopen($fdfDir.DS.$fdfFile,'w')){
			fwrite($fp,$fdfData,strlen($fdfData));
		}
		fclose($fp);
		$pdftkCommandString = 'pdftk ' . DS . APP . 'storage' . DS . 'program_forms' . DS .
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
		$path = substr(APP, 0, -1) . Configure::read('Document.storage.path');
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

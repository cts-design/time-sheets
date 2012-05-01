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

	private function getPath() {
		// get the document relative path to the inital storage folder
		$path = substr_replace(APP, '', -1, 1) . Configure::read('Document.storage.path');
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

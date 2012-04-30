<?php 

App::import('Vendor', 'wkhtmltopdf/wkhtmltopdf');

Configure::write('debug', 2);

class DocumentGenerationWorkerTask extends QueueShell {
	public $uses = array('Queue.Job');
	public $tubes = array('pdf_snapshot');
	
	public function execute() {
		while(true) {
			$this->out('Waiting for a job....');	
			$job = $this->Job->reserve(array('tube' => $this->tubes));
			$this->log($job, 'debug');
			if(!$job) {
				$this->log('Invalid job found. Not processing.', 'error');	
			}
			else {
				$this->out('Processing job ' . $job['Job']['id']);

				$this->out(var_export($job, true));
				if($this->process($job['Job']['data'])) {
					$this->Job->delete();
				}
				else {
					$this->Job->bury(1000);
				}
			}
		}
	}

	private function process($data, $toc=false) {
		if($data){
			$html = $this->getElementHtml($data);
			$this->log($html, 'debug');
			$path = $this->getPath();
			try {
				$pdf = new WKPDF();
				$pdf->set_html($html);
				$pdf->set_toc($toc);
				$pdf->args_add('--header-spacing', '5');
				// :TODO add user info to data array
				//$pdf->args_add('--header-left', $this->Auth->user('name_last4'));
				$pdf->args_add('--header-center', '[date]');
				$pdf->args_add('--header-right', Configure::read('Company.name'));
				$pdf->args_add('--footer-center', 'Page: [page] of [topage]') ;
				Configure::write('debug', 0);
				$pdf->render();
				$pdfFile = date('YmdHis') . rand(0, pow(10, 7)) . '.pdf';
				$this->log($pdfFile, 'debug');
				$this->log($path, 'debug');
				$pdf->output(WKPDF::$PDF_SAVEFILE, $path . $pdfFile);
			}
			catch(Exception $e) {
				// TODO probably just want to log this to the error log ??? 
				$this->log('WKPDF Exception (line ' . $e->getLine() .'): ' . $e->getMessage(), 'error');
				return false;
			}
			return true;
		}
	}

	private function getElementHtml($data) {
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

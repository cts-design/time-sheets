<?php
class WorkshopCertificateGeneratorShell extends Shell {
	public $uses = array(
		'Event',
		'EventCategory',
		'FiledDocument'
	);

	public function main() {
		// Find the id of the 'workshop' category
		$workshopCategory = $this->EventCategory->find('first', array(
			'conditions' => array(
				'OR' => array(
					'EventCategory.name' => 'Workshop',
					'EventCategory.name' => 'workshop'
				)
			),
			'recursive' => -1
		));
		$workshopCategoryId = $workshopCategory['EventCategory']['id'];

		// Find yesterday's date
		$yesterday = date('Y-m-d', strtotime('-1 day'));
		$yesterdayStartOfDay = date('Y-m-d H:i:s', strtotime($yesterday));
		$yesterdayEndOfDay = date('Y-m-d H:i:s', strtotime('+23 hours 59 minutes 59 seconds', strtotime($yesterdayStartOfDay)));

		// Find yesterdays workshops
		$this->Event->Behaviors->attach('Containable');
		$workshops = $this->Event->find('all', array(
			'conditions' => array(
				'AND' => array(
					array('Event.scheduled >' => $yesterdayStartOfDay),
					array('Event.scheduled <' => $yesterdayEndOfDay),
					// array('Event.event_category_id' => $workshopCategoryId)
				)
			),
                       'contain' => array(
                                'EventCategory' => array(
                                        'conditions' => array(
                                                'EventCategory.parent_id' => $workshopCategoryId
                                        )
                                )
                        ),			
			'contain' => array(
				'EventRegistration' => array(
					'conditions' => array(
						'EventRegistration.present' => 1
					),
					'User' => array(
						'fields' => array('id', 'firstname', 'lastname')
					)
				)
			)
		));

		// Loop through each workshop and eventRegistration
		foreach ($workshops as $key => $workshop) {
			if (isset($workshop['EventRegistration']) && !empty($workshop['EventRegistration'])) {
				$data['Workshop_name'] = $workshop['Event']['name'];
				$data['Month'] = date('F, Y', strtotime($workshop['Event']['scheduled']));
				$data['day'] = date('dS', strtotime($workshop['Event']['scheduled']));

				foreach ($workshop['EventRegistration'] as $eventRegistration) {
					$data['first'] = $eventRegistration['User']['firstname'];
					$data['last'] = $eventRegistration['User']['lastname'];

					$pdf = $this->generateCertificate($data);
					$this->fileCertificate($workshop, $eventRegistration, $pdf);
				}
			}
		}

		return true;
	}

	private function generateCertificate($data) {
		return $this->createPDF($data, 'workshop_certificate.pdf');
	}

	private function fileCertificate($workshop, $eventRegistration, $pdf) {
		$this->FiledDocument->User->QueuedDocument->create();
		$this->FiledDocument->User->QueuedDocument->save();
		$docId = $this->FiledDocument->User->QueuedDocument->getLastInsertId();
		$this->FiledDocument->User->QueuedDocument->delete($docId, false);

		// file the cert
		$data['FiledDocument']['id'] = $docId;
		$data['FiledDocument']['filename'] = $pdf;
		$data['FiledDocument']['user_id'] = $eventRegistration['User']['id'];
		$data['FiledDocument']['cat_1'] = $workshop['Event']['cat_1'];
		$data['FiledDocument']['cat_2'] = $workshop['Event']['cat_2'];
		$data['FiledDocument']['cat_3'] = $workshop['Event']['cat_3'];
		$data['FiledDocument']['filed'] = date('Y-m-d H:i:s');
		$data['FiledDocument']['entry_method'] = 'Workshop generated';
		$this->FiledDocument->save($data);
	}

	private function createFDF($file, $info) {
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

	private function createPDF($data, $template) {
		$path = $this->getPath();
		// build our fancy unique filename
		$fdfFile = date('YmdHis') . rand(0, pow(10, 7)) . '.fdf';
		// pdf	file named the same as the fdf file
		$pdfFile = str_replace('.fdf', '.pdf', $fdfFile);
		// the temp location to write the fdf file to
		$fdfDir = TMP . 'fdf';
		// need to know what file the data will go into
		$pdfTemplate = APP . 'storage' . DS . 'event_forms' . DS . $template;
		// generate the file content
		$fdfData = $this->createFDF($pdfTemplate,$data);
		// write the file out
		if($fp=fopen($fdfDir.DS.$fdfFile,'w')){
			fwrite($fp,$fdfData,strlen($fdfData));
		}
		fclose($fp);
		$pdftkCommandString = 'pdftk ' . DS . APP . 'storage' . DS . 'event_forms' . DS .
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
		if(!file_exists($path . date('Y') . DS)) {
			// if directory does not exist, create it
			mkdir($path . date('Y'), 0755);
		}
		// add the current year to our path string
		$path .= date('Y') . DS;
		// check to see if the directory for the current month exists
		if(!file_exists($path . date('m') . DS)) {
			// if directory does not exist, create it
			mkdir($path . date('m'), 0755);
		}
		// add the current month to our path string
		$path .= date('m') . DS;
		return $path;
	}
}
?>

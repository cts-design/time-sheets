<?php
class BarCodeDefinition extends AppModel {
	
	public $name = 'BarCodeDefinition';

	public $displayField = 'name';
	
	public $hasMany = array(
	    'QueuedDocument' => array(
			'className' => 'QueuedDocument',
			'foreignKey' => 'bar_code_definition_id'
	    ));

	public $belongsTo = array(
		'Cat1' => array(
		    'className' => 'DocumentFilingCategory',
		    'foreignKey' => 'cat_1'
		),
		'Cat2' => array(
		    'className' => 'DocumentFilingCategory',
		    'foreignKey' => 'cat_2'
		),
		'Cat3' => array(
		    'className' => 'DocumentFilingCategory',
		    'foreignKey' => 'cat_3'
		),
		'DocumentQueueCategory' => array(
		    'className' => 'DocumentQueueCategory',
		    'foreignKey' => 'document_queue_category_id'			
		)	
	);

	public function barDecode($document) {
		if(file_exists($document)) {
			$jpg = str_replace('.pdf', '.jpg', $document);	
			// here we use pdftk to make sure document is only one page.	
			exec('/usr/bin/pdftk ' . $document . ' dump_data | grep NumberOfPages', $output, $return);
			$pages = (int) substr($output[0], 15);
			unset($output, $return);
		}
		else {
			return false;
		}
		if($pages === 1) {
			// convert pdf to jpg using imagemagick so jpg can be read by bar code binary
			$command = '/usr/bin/convert -density 300 ' . $document . ' ' . $jpg;
			exec($command, $output, $return);
			if($return === 0) {
				unset($output, $return);
				// read the barcode from jpg
				$command = '/bin/bardecode -t code39 ' . $jpg . ' -K A04B4AB79DD4AA7D297C57A0D6646D7D';   
				exec($command, $output, $return);
			}
			else {
				return false;
			}
			if(isset($output[0])) {
				$decoded = explode('-', $output[0]);
				$this->recursive = -1;
				$barCodeDef = $this->find('first', array(
					'conditions' => array('BarCodeDefinition.number' => $decoded[0]),
					'fields' => array('id', 'document_queue_category_id', 'name')));
				if($barCodeDef) {
					$return = array(
						'BarCodeDefinition' => $barCodeDef['BarCodeDefinition'], 
						'User' => array('id' => (int) $decoded[1])
					);
					unlink($jpg);
					return $return;
				}
				else {
					return false;
				}
			}
			else {
				return false;
			}
		}
		else {
			// TODO possibly add logic to deal with multiple page pdfs
			return false;
		}
	}
}

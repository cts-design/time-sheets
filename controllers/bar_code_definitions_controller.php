<?php
class BarCodeDefinitionsController extends AppController {

	public $name = 'BarCodeDefinitions';

	public function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$this->BarCodeDefinition->recursive = 0;
			$definitions = $this->Paginate('BarCodeDefinition');
			if(!empty($definitions)) {
						$this->loadModel('DocumentFilingCategory');
						$categories = $this->DocumentFilingCategory->find('list', array(
						'conditions' => array(
							'DocumentFilingCategory.disabled' => 0),
						'fields' => array('DocumentFilingCategory.id', 'DocumentFilingCategory.name')));
				$i = 0;		
				foreach($definitions as $definition) {
					$data['definitions'][$i]['id'] = intval($definition['BarCodeDefinition']['id']);
					$data['definitions'][$i]['name'] = $definition['BarCodeDefinition']['name'];	
					$data['definitions'][$i]['number'] = $definition['BarCodeDefinition']['number'];
					$data['definitions'][$i]['Cat1-name'] = $definition['Cat1']['name'];
					$data['definitions'][$i]['Cat2-name'] = $definition['Cat2']['name'];
					$data['definitions'][$i]['Cat3-name'] = $definition['Cat3']['name'];
					$data['definitions'][$i]['DocumentQueueCategory-name'] = $definition['DocumentQueueCategory']['name'];
					$i++;
				}		
			}
			else {
			 	$data['definitions'] = array();
			}
			$data['total'] = $this->BarCodeDefinition->find('count');
			$data['success'] = true;
			$this->set(compact('data'));			
			$this->render(null, null, '/elements/ajaxreturn');				
		}	
	}

	public function admin_add() {
		if($this->RequestHandler->isAjax()) {
			if (!empty($this->data)) {
				$this->data['BarCodeDefinition'] = json_decode($this->data['BarCodeDefinition'], true);
				$this->BarCodeDefinition->create();
				if ($this->BarCodeDefinition->save($this->data)) {
					$data['definitions'] = $this->getDefinition($this->BarCodeDefinition->getLastInsertId());
					$data['total'] = $this->BarCodeDefinition->find('count');
					$data['success'] = true;
					$data['message'] = 'Bar code definition added successfully.';
                    $this->Transaction->createUserTransaction('BarCodeDefinition', null, null,
                                            'Added bar code definition ID ' . 
                                            $this->BarCodeDefinition->getLastInsertId());					
				} else {
					$data['success'] = false;
					$data['message'] = 'Unable to add bar code definition.';
				}
				$this->set(compact('data'));			
				$this->render(null, null, '/elements/ajaxreturn');				
			}			
		}
	}

	public function admin_edit() {
		if($this->RequestHandler->isAjax()) {
			if (!empty($this->data)) {
				$this->data['BarCodeDefinition'] = json_decode($this->data['BarCodeDefinition'], true);
				if(isset($this->data['BarCodeDefinition']['cat_1'])) {
					if(!isset($this->data['BarCodeDefinition']['cat_2'])) {
						$this->data['BarCodeDefinition']['cat_2'] = null;
					}
					if(!isset($this->data['BarCodeDefinition']['cat_3'])) {
						$this->data['BarCodeDefinition']['cat_3'] = null;
					}						
				}
				if ($this->BarCodeDefinition->save($this->data)) {				
					$data['definitions'] = $this->getDefinition($this->data['BarCodeDefinition']['id']);
					$data['success'] = true;
					$data['message'] = 'Bar code definition updated successfully.';
                    $this->Transaction->createUserTransaction('BarCodeDefinition', null, null,
                                            'Edited bar code definition ID ' . 
                                            $this->data['BarCodeDefinition']['id']);					
				} else {
					$data['success'] = false;
					$data['message'] = 'Unable to update bar code definition.';
				}
				$this->set(compact('data'));			
				$this->render(null, null, '/elements/ajaxreturn');				
			}			
		}
	}

	public function admin_delete() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				$this->data['BarCodeDefinition'] = json_decode($this->data['BarCodeDefinition'], true);	
			}
			if ($this->data['BarCodeDefinition']['id'] == '') {
				$data['success'] = false;
			}
			elseif ($this->BarCodeDefinition->delete($this->data['BarCodeDefinition']['id'])) {
				$data['definitions'] = $this->getDefinition($this->data['BarCodeDefinition']['id']);
				$data['success'] = true;
                $this->Transaction->createUserTransaction('BarCodeDefinition', null, null,
                                        'Deleted bar code definition ID ' . 
                                        $this->data['BarCodeDefinition']['id']);				
			}
			else {
				$data['success'] = false;
			}
			$this->set(compact('data'));			
			$this->render(null, null, '/elements/ajaxreturn');			
		}
	}
	
	private function getDefinition($id) {
		$this->BarCodeDefinition->recursive = 0;
		$definition = $this->BarCodeDefinition->findById($id);
		if($definition) {
			$definition['BarCodeDefinition']['Cat1-name'] = $definition['Cat1']['name'];
			$definition['BarCodeDefinition']['Cat2-name'] = $definition['Cat2']['name'];
			$definition['BarCodeDefinition']['Cat3-name'] = $definition['Cat3']['name'];
			$definition['BarCodeDefinition']['DocumentQueueCategory-name'] = $definition['DocumentQueueCategory']['name'];
			return $definition['BarCodeDefinition'];			
		}
		else {
			return array();
		}	
	}
}
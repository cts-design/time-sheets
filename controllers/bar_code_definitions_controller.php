<?php
class BarCodeDefinitionsController extends AppController {

	var $name = 'BarCodeDefinitions';

	function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$this->BarCodeDefinition->recursive = 0;
			$definitions = $this->Paginate('BarCodeDefinition');
			FireCake::log($definitions);
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
					$data['definitions'][$i]['cat_1'] = $definition['BarCodeDefinition']['cat_1'];
					$data['definitions'][$i]['cat_2'] = $definition['BarCodeDefinition']['cat_2'];
					$data['definitions'][$i]['cat_3'] = $definition['BarCodeDefinition']['cat_3'];
					$i++;
				}		
			}
			else {
			 	$data['definitions'] = array();
			}
			$data['total'] = $this->BarCodeDefinition->paginateCount();
			$data['success'] = true;
			$this->set(compact('data'));			
			$this->render(null, null, '/elements/ajaxreturn');				
		}	
	}

	function admin_add() {
		if($this->RequestHandler->isAjax()) {
			if (!empty($this->data)) {
				$this->data['BarCodeDefinition'] = json_decode($this->data['BarCodeDefinition'], true);
				FireCake::log($this->data);
				$this->BarCodeDefinition->create();
				if ($this->BarCodeDefinition->save($this->data)) {
					//@TODO return added record
					$data['sucesss'] = true;
					$data['message'] = 'Bar code definition added successfully.';
				} else {
					$data['success'] = false;
					$data['message'] = 'Unable to add bar code definition.';
				}
				$this->set(compact('data'));			
				$this->render(null, null, '/elements/ajaxreturn');				
			}			
		}
	}

	function admin_edit() {
		if($this->RequestHandler->isAjax()) {
			if (!empty($this->data)) {
				$this->data['BarCodeDefinition'] = json_decode($this->data['BarCodeDefinition'], true);
				if(isset($this->data['BarCodeDefinition']['Cat1-name'])) {
					$this->data['BarCodeDefinition']['cat_1'] = $this->data['BarCodeDefinition']['Cat1-name'];
				}
				if(isset($this->data['BarCodeDefinition']['Cat2-name'])) {
					$this->data['BarCodeDefinition']['cat_2'] = $this->data['BarCodeDefinition']['Cat2-name'];
				}
				if(isset($this->data['BarCodeDefinition']['Cat3-name'])) {
					$this->data['BarCodeDefinition']['cat_3'] = $this->data['BarCodeDefinition']['Cat3-name'];
				}
				if ($this->BarCodeDefinition->save($this->data)) {
					$definition = $this->BarCodeDefinition->findById($this->data['BarCodeDefinition']['id']);
					$definition['BarCodeDefinition']['Cat1-name'] = $definition['Cat1']['name'];
					$definition['BarCodeDefinition']['Cat2-name'] = $definition['Cat2']['name'];
					$definition['BarCodeDefinition']['Cat3-name'] = $definition['Cat3']['name'];
					$data['definitions'] = $definition['BarCodeDefinition'];
					$data['sucesss'] = true;
					$data['message'] = 'Bar code definition updated successfully.';
				} else {
					$data['success'] = false;
					$data['message'] = 'Unable to update bar code definition.';
				}
				$this->set(compact('data'));			
				$this->render(null, null, '/elements/ajaxreturn');				
			}			
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for bar code definition', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->BarCodeDefinition->delete($id)) {
			$this->Session->setFlash(__('Bar code definition deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Bar code definition was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>
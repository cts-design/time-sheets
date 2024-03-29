<?php
class SecureCategoriesController extends AppController {

	public $name = 'SecureCategories';
	
	public $uses = array();
	

	public function admin_index() {
		
	}

	public function admin_get_secure_filing_cats() {
		if($this->RequestHandler->isAjax()) {
			$this->loadModel('DocumentFilingCategory');
			$cats = $this->DocumentFilingCategory->find('all', 
				array('conditions' => array('DocumentFilingCategory.secure' => 1)));
			$data['cats'] = array();
			if($cats) {
				$i = 0;
				foreach($cats as $cat) {
					$data['cats'][$i]['id'] = $cat['DocumentFilingCategory']['id'];
					$data['cats'][$i]['category'] = '';
					$data['cats'][$i]['secure_admins'] = json_decode($cat['DocumentFilingCategory']['secure_admins']);
					$parents = $this->DocumentFilingCategory->getpath(
						$cat['DocumentFilingCategory']['id'], $fields = array('name'));
					if($parents) {
						$ii = 1;
						foreach ($parents as $parent) {
							$data['cats'][$i]['category'] .= $parent['DocumentFilingCategory']['name'];
							if($ii < count($parents)) {
								$data['cats'][$i]['category'] .= ' - ';
							}
							$ii++;
						}
					}
					$i++;
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_get_secure_queue_cats() {
		if($this->RequestHandler->isAjax()) {
			$this->loadModel('DocumentQueueCategory');
			$cats = $this->DocumentQueueCategory->find('all', array(
				'conditions' => array('DocumentQueueCategory.secure' => 1),
				'order' => 'DocumentQueueCategory.name ASC',
				'fields' => array(
					'DocumentQueueCategory.name', 
					'DocumentQueueCategory.id', 
					'DocumentQueueCategory.secure_admins'
				)
			));
			$data['cats'] = array();
			if($cats) {
				$i = 0;
				foreach($cats as $cat) {
					$data['cats'][$i]['id'] = $cat['DocumentQueueCategory']['id'];
					$data['cats'][$i]['category'] = $cat['DocumentQueueCategory']['name'];
					$data['cats'][$i]['secure_admins'] = json_decode($cat['DocumentQueueCategory']['secure_admins']);
					$i++;
				}
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_update_filing_cat() {
		if($this->RequestHandler->isAjax()) {
			$this->loadModel('DocumentFilingCategory');
			if(!empty($this->params['form']['id'])) {
				$this->data['DocumentFilingCategory'] = $this->params['form'];		
				if($this->DocumentFilingCategory->save($this->data)) {
					$data['success'] = true;
					$data['message'] = 'Filing category updated successfully';
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to update filing category.';
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'An error has occurred';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_update_queue_cat() {
		if($this->RequestHandler->isAjax()) {
			$this->loadModel('DocumentQueueCategory');
			if(!empty($this->params['form']['id'])) {
				$this->data['DocumentQueueCategory'] = $this->params['form'];		
				if($this->DocumentQueueCategory->save($this->data)) {
					$data['success'] = true;
					$data['message'] = 'Queue category updated successfully';
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to update queue category.';
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'An error has occurred';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}	

}
<?php
class EventCategoriesController extends AppController {
	public $name = 'EventCategories';
	
	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function admin_index() {
		if($this->RequestHandler->isAjax()) {
			$this->EventCategory->recursive = 0;
			$categories = $this->EventCategory->find('threaded', array('order' => array('name' => 'asc')));
			$this->log($results, 'debug');
			$i = 0;
			foreach($categories as $category) {
				$data['categories'][$i]['id'] = $category['EventCategory']['id'];
				$data['categories'][$i]['name'] = $category['EventCategory']['name'];
				$data['categories'][$i]['parent_id'] = $category['EventCategory']['parent_id'];
				$i++;
				if(!empty($category['children'])) {
					foreach($category['children'] as $child) {
						$data['categories'][$i]['id'] = $child['EventCategory']['id'];
						$data['categories'][$i]['name'] = ' - ' . $child['EventCategory']['name'];
						$data['categories'][$i]['parent_id'] = $child['EventCategory']['parent_id'];
						$i++;
					}
				}
			}	
			if(!empty($data)){
				$data['success'] = true;
			}
			else {
				$data['success'] = false;
			}
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');			
		}		
	}

	public function admin_add()	{
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				$this->data['EventCategory'] = json_decode($this->data['EventCategory'], true);
				unset($this->data['EventCategory']['created']);
				unset($this->data['EventCategory']['modified']);
				unset($this->data['EventCategory']['parent_name']);
				$this->EventCategory->create();
				if($this->EventCategory->save($this->data)) {
                    $this->Transaction->createUserTransaction(
                        'Events',
                        $this->Auth->user('id'),
                        $this->Auth->user('location_id'),
                        'Added event category: ' . $this->data['EventCategory']['name']
                    );
					$data['success'] = true;
					$data['message'] = 'The category was saved successfully.';
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to save category, please try again.';
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to save category, please try again.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_edit()	{
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				$this->data['EventCategory'] = json_decode($this->data['EventCategory'], true);
				$category = $this->EventCategory->findById($this->data['EventCategory']['id']);
				if(!empty($category['Events'])) {
					$data['success'] = false;
					$data['message'] = 'Cannot edit category that already has events.';
				}
				else {
					if($this->EventCategory->save($this->data)) {
						$this->Transaction->createUserTransaction(
							'Events',
							$this->Auth->user('id'),
							$this->Auth->user('location_id'),
							'Edited event category: ' . $this->data['EventCategory']['name']
						);
						$data['success'] = true; 
						$data['message'] = 'The category was updated successfully.';
					}
					else {
						$data['success'] = false;
						$data['message'] = 'Unable to update category, please try again.';
					}
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to update category, please try again.';
			}
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

	public function admin_get_list() {
	    if ($this->RequestHandler->isAjax()) {
	    	$this->EventCategory->recursive = -1;			
			$categories = $this->EventCategory->find('threaded',
				array('fields' => array('EventCategory.id', 'EventCategory.name', 'EventCategory.parent_id')));
			if($this->params['url']['parent']) {
				$categories = Set::Extract('/EventCategory[parent_id]', $categories);
			}
			$i = 0;
			foreach($categories as $category) {
				$data['categories'][$i]['id'] = $category['EventCategory']['id'];
				$data['categories'][$i]['name'] = $category['EventCategory']['name'];
				$i++;
				if(!empty($category['children'])) {
					foreach($category['children'] as $child) {
						$data['categories'][$i]['id'] = $child['EventCategory']['id'];
						$data['categories'][$i]['name'] = ' - ' . $child['EventCategory']['name'];
						$data['categories'][$i]['parent_id'] = $child['EventCategory']['parent_id'];
						$i++;
					}
				}
			}
			if(!empty($data)){
				$data['success'] = true;
			}
			else {
				$data['success'] = false;
			}
			$this->set(compact('data'));
			return $this->render(null, null, '/elements/ajaxreturn');			
	    }
	

	}
}

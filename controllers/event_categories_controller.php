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

	public function admin_get_list() {
	    if ($this->RequestHandler->isAjax()) {
	    	$this->EventCategory->recursive = -1;			
			$categories = $this->EventCategory->find('threaded',
				array('fields' => array('EventCategory.id', 'EventCategory.name', 'EventCategory.parent_id')));
			$parent = false; // TODO: pass this as a param
			$this->log($this->params, 'debug');
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

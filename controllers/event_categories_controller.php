<?php
class EventCategoriesController extends AppController {
	var $name = 'EventCategories';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->EventCategory->recursive = -1;
	}

	function admin_create() {}
	function admin_read() {
		$eventCategories = $this->EventCategory->find('all');
		$data = array();
		
		if (!empty($eventCategories)) {
			$data['total_event_categories'] = count($eventCategories);
			$data['success'] = true;
			foreach ($eventCategories as $key => $value) {
				$data['event_categories'][] = $value['EventCategory'];
			}
		} else {
			$data['success'] = false;
		}
		
		$this->set('data', $data);
		return $this->render(null, null, '/elements/ajaxreturn');
	}
	function admin_update() {}
	function admin_destroy() {}

	function admin_get_all_categories() {
		$allCategories = $this->EventCategory->find('all');
		if($allCategories) {
			foreach ($allCategories as $key => $value) {
				unset($value['Events']);
				foreach ($value as $k => $v) {
					$data['eventCategories'][] = $v;
				}
			}
		}
		else {
			$data['eventCategories'] = array();
		}
		$data['success'] = true;		
		$this->set(compact('data'));
		return $this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_get_event_category_list() {
	    if ($this->RequestHandler->isAjax()) {
	    	$this->EventCategory->recursive = -1;			
			$categories = $this->EventCategory->find('all',
				array('fileds' => array('EventCategory.id', 'EventCategory.name')));
			foreach($categories as $category) {
			    $data['categories'][] = $category['EventCategory'];
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
?>

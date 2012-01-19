<?php
class DocumentQueueFiltersController extends AppController {

	var $name = 'DocumentQueueFilters';
	
	
	function admin_set_filters() {
		if($this->RequestHandler->isAjax()) {
			$this->data['DocumentQueueFilter'] = $this->params['form'];
			$this->data['DocumentQueueFilter']['user_id'] = $this->Auth->user('id');
			if(isset($this->data['DocumentQueueFilter']['from_date'])) {
				$this->data['DocumentQueueFilter']['from_date'] = 
					date('Y-m-d', strtotime($this->data['DocumentQueueFilter']['from_date']));
			}
			else $this->data['DocumentQueueFilter']['from_date'] = null;
			if(isset($this->data['DocumentQueueFilter']['to_date'])) {
				$this->data['DocumentQueueFilter']['to_date'] = 
					date('Y-m-d', strtotime($this->data['DocumentQueueFilter']['to_date']));
			}
			else $this->data['DocumentQueueFilter']['to_date'] = null;			
			if($this->DocumentQueueFilter->save($this->data)) {
				$data['success'] = true;
				$data['message'] = 'Filters set successfully.';
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to set filters.';
			}	
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}
	
	function admin_get_filters() {
		if($this->RequestHandler->isAjax()){
			$filters = $this->DocumentQueueFilter->findByUserId($this->Auth->User('id'));
			if($filters){
				$data['filters']['id'] = $filters['DocumentQueueFilter']['id'];
				if($filters['DocumentQueueFilter']['locations']) {
					$data['filters']['locations'] = 
						json_decode($filters['DocumentQueueFilter']['locations']);
				}
				if($filters['DocumentQueueFilter']['queue_cats']) {
					$data['filters']['queue_cats'] = 
						json_decode($filters['DocumentQueueFilter']['queue_cats']);
				}
				if($filters['DocumentQueueFilter']['from_date']) {
					$data['filters']['from_date'] = 
						date('m/d/Y', strtotime($filters['DocumentQueueFilter']['from_date']));						
				}
				if($filters['DocumentQueueFilter']['to_date']) {
					$data['filters']['to_date'] = 
						date('m/d/Y', strtotime($filters['DocumentQueueFilter']['to_date']));						
				}
				$data['filters']['auto_load_docs'] = $filters['DocumentQueueFilter']['auto_load_docs'];
			}
			else {
				$data['filters'] = array();
			}
			$data['success'] = true;
			$this->set(compact('data'));
			$this->render(null, null, '/elements/ajaxreturn');
		}
	}

}
?>
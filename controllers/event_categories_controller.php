<?php
class EventCategoriesController extends AppController {
	var $name = 'EventCategories';
	
	function admin_index() {
		$this->EventCategories->recursive = 0;
		$this->set('eventCategories', $this->paginate());
	}
		
	function admin_add() {
		if (!empty($this->data)) {
			$this->EventCategory->create();
			if ($this->EventCategory->save($this->data)) {
                $this->Transaction->createUserTransaction('CMS', null, null,
                                        'Created event category named ' . $this->data['EventCategory']['name']);
				$this->Session->setFlash(__('The event category has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event category could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid event category', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->EventCategory->save($this->data)) {
                                $this->Transaction->createUserTransaction('CMS', null, null,
                                        'Edited event category ID ' . $id);
				$this->Session->setFlash(__('The event category has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The event category could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->EventCategory->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for event category', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->EventCategory->delete($id)) {
                        $this->Transaction->createUserTransaction('CMS', null, null,
                                        'Deleted event category ID ' . $id);
			$this->Session->setFlash(__('Event category deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Event category was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}

	function admin_get_all_categories() {
		$allCategories = $this->EventCategory->find('all');

		foreach ($allCategories as $key => $value) {
			unset($value['Events']);
			foreach ($value as $k => $v) {
				$eventCategories['eventCategories'][] = $v;
			}
		}
		
		$this->set(compact('eventCategories'));
	}
}
?>
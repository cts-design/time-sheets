<?php
class HelpfulArticlesController extends AppController {

	var $name = 'HelpfulArticles';
	
	function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index');
	}

	function index() {
		$this->HelpfulArticle->recursive = 0;
		$this->set('helpfulArticles', $this->paginate());
	}

	function admin_index() {
		$this->HelpfulArticle->recursive = 0;
		$this->set('helpfulArticles', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->HelpfulArticle->create();
			if ($this->HelpfulArticle->save($this->data)) {
				$this->Transaction->createUserTransaction('HelpfulArticles', null, null,
                                        'Created article ID ' . $this->HelpfulArticle->id);
				$this->Session->setFlash(__('The helpful article has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The helpful article could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid helpful article', true), 'flash_failure');
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->HelpfulArticle->save($this->data)) {
				$this->Transaction->createUserTransaction('HelpfulArticles', null, null,
                                        'Edited article ID ' . $id);
				$this->Session->setFlash(__('The helpful article has been saved', true), 'flash_success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The helpful article could not be saved. Please, try again.', true), 'flash_failure');
			}
		}
		if (empty($this->data)) {
			$this->data = $this->HelpfulArticle->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for helpful article', true), 'flash_failure');
			$this->redirect(array('action'=>'index'));
		}
		if ($this->HelpfulArticle->delete($id)) {
			$this->Transaction->createUserTransaction('HelpfulArticles', null, null,
                                        'Deleted article ID ' . $id);
			$this->Session->setFlash(__('Helpful article deleted', true), 'flash_success');
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(__('Helpful article was not deleted', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	}
}
?>
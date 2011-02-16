<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class DocumentFilingCategoriesController extends AppController {

    var $name = 'DocumentFilingCategories';
    var $helpers = array('Tree');
    var $components = array('RequestHandler');

    function beforeFilter() {
	parent::beforeFilter();
	if($this->Auth->user('role_id') > 1) {
	    $this->Auth->allow('admin_get_child_cats_ajax', 'admin_getGrandChildCatsAjax');
	}
    }

    function admin_index() {
	$this->_setTreeData();
    }

    function admin_add() {
	if(!empty($this->data)) {
	    $parent = $this->DocumentFilingCategory->find('first',
			    array('conditions' => array('DocumentFilingCategory.id' => $this->data['DocumentFilingCategory']['parent_id'])));
	    $parent2 = $this->DocumentFilingCategory->find('first',
			    array('conditions' => array('DocumentFilingCategory.id' => $parent['DocumentFilingCategory']['parent_id'])));
	    $parent3 = $this->DocumentFilingCategory->find('first',
			    array('conditions' => array('DocumentFilingCategory.id' => $parent2['DocumentFilingCategory']['parent_id'])));
	    if(isset($parent3['DocumentFilingCategory']) && $parent3['DocumentFilingCategory']['parent_id'] == '') {
		$this->Session->setFlash(__('Categories cannot be more than three levels deep', true), 'flash_failure');
		$this->redirect('/admin/document_filing_categories');
	    }
	    if($this->data['DocumentFilingCategory']['parent_id'] == '') {
		$this->data['DocumentFilingCategory']['parent_id'] = null;
	    }
	    $category = $this->DocumentFilingCategory->find('first', array(
			'conditions' => array(
			    'DocumentFilingCategory.parent_id' => $this->data['DocumentFilingCategory']['parent_id'],
			    'DocumentFilingCategory.name' => $this->data['DocumentFilingCategory']['name']),
			'DocumentFilingCategory.deleted' => 1));
	    if($category) {
		$this->data['DocumentFilingCategory']['id'] = $category['DocumentFilingCategory']['id'];
		$this->data['DocumentFilingCategory']['deleted'] = 0;
	    }
	    else {
		$this->DocumentFilingCategory->create();
	    }
	    $this->data['DocumentFilingCategory']['order'] = 9999;
	    if($this->DocumentFilingCategory->save($this->data)) {
		$this->Session->setFlash(__('The category has been saved', true), 'flash_success');
		$this->redirect('/admin/document_filing_categories');
	    }
	    else {
		$this->Session->setFlash(__('The category could not be saved. Please, try again.', true), 'flash_failure');
		$this->_setTreeData();
		$this->render('/document_filing_categories/admin_index');
	    }
	}
    }

    function admin_reorder_categories_ajax() {
	$data['DocumentFilingCategory'] = array();
	array_shift($this->params['url']);
	foreach($this->params['url'] as $k => $v) {
	    $data['DocumentFilingCategory'][$v] = array('id' => $v, 'order' => $k);
	}
	$this->DocumentFilingCategory->saveAll($data['DocumentFilingCategory']);
    }

    function admin_edit($id = null) {
	if(!$id && empty($this->data)) {
	    $this->Session->setFlash(__('Invalid category', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	if(!empty($this->data)) {
	    if($this->DocumentFilingCategory->save($this->data)) {
		$this->Session->setFlash(__('The category has been saved', true), 'flash_success');
		$this->redirect(array('action' => 'index'));
	    }
	    else {
		$this->Session->setFlash(__('The category could not be saved. Please, try again.', true), 'flash_failure');
		$this->redirect(array('action' => 'index'));
	    }
	}
	if(empty($this->data)) {
	    $this->data = $this->DocumentFilingCategory->read(null, $id);
	}
    }

    function admin_delete($id = null) {
	if(!$id) {
	    $this->Session->setFlash(__('Invalid id for category', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	$count = $this->DocumentFilingCategory->find('count', array(
		    'conditions' => array(
			'DocumentFilingCategory.parent_id' => $id,
			'DocumentFilingCategory.deleted' => 0)));
	if($count > 0) {
	    $this->Session->setFlash(__('Cannot delete category that has children', true), 'flash_failure');
	    $this->redirect(array('action' => 'index'));
	}
	if($this->DocumentFilingCategory->delete($id)) {
	    $this->Session->setFlash(__('Category deleted', true), 'flash_success');
	    $this->redirect(array('action' => 'index'));
	}
	$this->Session->setFlash(__('Category was not deleted', true), 'flash_failure');
	$this->redirect(array('action' => 'index'));
    }

    function admin_get_child_cats_ajax() {
	if($this->RequestHandler->isAjax()) {
	    $options = $this->DocumentFilingCategory->find('list', array(
			'conditions' => array('DocumentFilingCategory.parent_id' => $this->params['url']['id']),
			'fields' => array('DocumentFilingCategory.id', 'DocumentFilingCategory.name')));
	    $this->set(compact('options'));
	}
    }

    function admin_getGrandChildCatsAjax() {
	if($this->RequestHandler->isAjax()) {
	    $options = $this->DocumentFilingCategory->find('list', array(
			'conditions' => array('DocumentFilingCategory.parent_id' => $this->params['url']['id'])));
	    $this->set(compact('options'));
	}
    }

    function _setTreeData() {
	$this->DocumentFilingCategory->recursive = -1;
	$data = $this->DocumentFilingCategory->find('threaded',
			array('order' => array('DocumentFilingCategory.order' => 'asc'),
			    'conditions' => array('DocumentFilingCategory.deleted' => 0)));
	$this->set('data', $data);
    }

}

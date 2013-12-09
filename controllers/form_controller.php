<?php

class FormController extends AppController
{
	var $name = 'Form';	
	var $uses = array('Question', 'Page', 'Form');

	var $layout = 'default_bootstrap';

	var $paginate = array(
		'limit' => 2
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allowedActions = array('admin_index', 'admin_page');
		$this->layout = 'default_bootstrap';
	}

	public function admin_index()
	{
		$type = ( isset($this->params['url']['type']) ? $this->params['url']['type'] : "all");

		$forms = $this->Form->find('all', array(
			'recursive' => 2
		));

		if($type != 'all')
		{
			$forms = $this->paginate('Form', array(
				'type' => $type
			));
		}
		else
		{
			$forms = $this->paginate('Form');
		}

		$form_types = $this->Form->find('all', array(
			'fields' => array('DISTINCT type'),
			'order' => array('Form.type ASC')
		));

		$title_for_layout = 'Programs';
		$this->set(compact('title_for_layout', 'forms', 'form_types'));
	}

	public function create()
	{

	}

	public function edit()
	{

	}

	public function admin_page($form_id = NULL)
	{
		if($form_id == NULL)
		{
			$this->redirect(array('controller' => 'Form', 'action' => 'admin_index'));
		}

		$pages = $this->Page->find('all', array(
			'conditions' => array(
				'form_id' => $form_id
			)
		));



		$this->set(compact('pages'));
	}


}
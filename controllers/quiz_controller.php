<?php

class QuizController extends AppController {
	public $name = 'Quiz';
	
	public $helpers = array('request');

	var $paginate = array(
		'limit' => 20,
		'order' => array('Quiz.name' => 'asc')
	);

	function admin_listing() {

		$category 	= $this->get('category');
		$name 		= $this->get('name');

		$quizzes = $this->paginate('Quiz', array(
			'Quiz.name LIKE' => '%' . $name . '%',
			'Quiz.quiz_category_id' => $category
		));
		
		$quiz_categories = $this->Quiz->QuizCategory->find('all');

		$this->set(compact('quizzes', 'quiz_categories'));
	}

	function admin_create() {
		if($this->RequestHandler->isPost())
		{
			$this->Quiz->create();
			$is_saved = $this->Quiz->save($this->data);
			
			if($is_saved)
			{
				$this->redirect(array('action' => 'admin_listing'));
			}
			else
			{
				$this->Session->flash('The Program was not saved');
				$this->redirect(array('action' => 'admin_create'));
			}
		}
		else
		{
			$quiz_categories = $this->Quiz->QuizCategory->find('all');
			$this->set(compact('quiz_categories'));
		}
	}

	function admin_edit() {

	}

	function admin_delete() {

	}

	function admin_index($quiz_id) {
		if(!$quiz_id) {
			$this->redirect(array(
				'controller' => 'quiz',
				'action' => 'admin_listing'
			));
		}
	}


	function before_filter() {
		parent::before_filter();

		if( !$this->Auth->user() ) {
			$this->redirect(array('action' => 'admin_login', 'controller' => 'user'));
		}
		else if( $this->Auth->user('role_id') > 1 )
		{
			$this->Auth->allowedActions = array(
				'admin_listing',
				'admin_index',
				'admin_create',
				'admin_delete'
			);
		}
	}
}
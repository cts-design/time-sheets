<?php

class QuizController extends AppController {
	var $name = 'Quiz';

	var $paginate = array(
		'limit' => 20,
		'order' => array('Quiz.name' => 'asc')
	);

	function admin_listing() {

		$this->Quiz->find('all');
		$quizzes = $this->paginate('Quiz');
		$this->set(compact('quizzes'));

	}

	function admin_create() {

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
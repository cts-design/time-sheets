<?php

class HtmlTableGenerator extends AppController
{
	var $filters = array();
	var $fields = array();
	var $distincts = array();
	var $model = '';
	

	var $paginate = array(
		'limit' => 25
	);

	public function __construct($model)
	{
		$this->model = $model;
		
		$this->loadModel($model);
	}
	public function format($fields, $conditions)
	{
		$model = $this->model;
	
		$this->paginate = array(
			'conditions' => $conditions,
			'fields' => $fields
		);

		$rows = $this->paginate($model);
		$data = array(
			'fields' => $this->fields,
			'model' => $this->model,
			'rows' => $rows,
			'filters' => $this->filters,
			'distincts' => $this->distincts
		);
		foreach($this->fields as $field)
		{
			$this->fields['fields'][$field] = Inflector::humanize($field);
		}
		return $data;
	}

	public function set_distincts($dists)
	{
		$this->distincts = $dists;
	}
	public function add_distinct($dist)
	{
		$this->distincts[] = $dists;
	}

	public function set_fields($fields)
	{
		$this->fields = $fields;
	}

	public function add_filter($type, $default = '')
	{
		$this->filters[$type] = $default;
	}

	public function set_filters($filters)
	{
		$new_filters = array();
		foreach($filters as $filter)
		{
			$model = $this->model;
			$this->$model->recursive = -1;
			$new_filters[$filter] = $this->$model->find('all', array(
				'fields' => 'DISTINCT ' . $model . '.' . $filter,
			));
		}
		$this->filters = $new_filters;
	}
}
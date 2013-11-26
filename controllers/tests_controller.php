<?php

class TestsController extends AppController
{
	public $uses = array();
	public $name = 'Tests';
	public function scan()
	{

	}

	public function process_scan()
	{
		$this->log("Uploading File");

		$this->log( var_export($_FILES, true) );
	}

	public function success()
	{
		echo "Congrats you uploaded the file";
	}
}
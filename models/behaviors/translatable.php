<?php 
/**
 * Translatable Behavior class
 * 
 * Adds the ability to translate records using Google Translate
 * and save them to the i18n table
 * 
 * @author	Brandon Cordell
 * @copyright 2011, Complete Technology Solutions
 * @version 0.1
 * 
 */
App::import('Core', 'HttpSocket');
App::import('Component', 'RequestHandler');

class TranslatableBehavior extends ModelBehavior {
	protected $_apiUrl 	   = 'https://ajax.googleapis.com/ajax/services/language/translate?v=1.0&';

	public $connection = null;
	public $fields     = null;
	public $language   = 'es';
	public $userIp     = '';
	
	/*
	 * Behavior setup
	 * @param Model $Model Holds a reference to the model using this behavior
	 * @param array $fields An array of model field names to be translated
	 */
	public function setup(&$Model, $fields) {
		$this->connection = new HttpSocket;
		$RequestHandler = new RequestHandlerComponent;
		$this->userIp = $RequestHandler->getClientIP();

		$this->fields[$Model->alias] = $fields;
	}
	
	/*
	 * Loops through each of the fields set when the behavior is configured
	 * and translates them into spanish. Creating an array that will save both
	 * record in the i18n table.
	 * 
	 * @param Model $model A pointer to the model that triggered this callback
	 * @return bool
	 */
	public function beforeValidate(&$Model) {
		foreach ($this->fields[$Model->alias] as $key => $fieldName) {
			if (isset($Model->data[$Model->alias][$fieldName])) {
				$Model->data[$Model->alias][$fieldName] = array(
					'en_us' => $Model->data[$Model->alias][$fieldName],
					'es_es' => $this->translate($Model->data[$Model->alias][$fieldName])
				);
			}
		}

		$Model->schema(true);
		return true;
	}
	
	/*
	 * Builds and executes a request to the Google Translate API
	 * @param array $query
	 * @return array
	 */
	protected function translate($originalText) {
		$translateUrl =
			$this->_apiUrl .
			'q=' . $originalText .
			'&langpair=en%7C' . $this->language .
			'&userip=' . $this->userIp;
		
		$response = json_decode($this->connection->get($translateUrl), true);
		
		if ($response['responseStatus'] !== 200) {
			$this->log('An error occured while using the Google Translate API', 'error');
			return false;
		}
		
		return $response['responseData']['translatedText'];
	}
}

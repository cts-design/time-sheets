<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2013
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class Page extends AppModel {
	public $name = 'Page';

	public $displayField = 'title';

	public $actsAs = array(
		'Translatable' => array(
			'title', 'content'
		),
		'AtlasTranslate' => array(
			'title', 'content'
		)
	);

	public $validate = array(
		'title' => array(
			'alphaNumericPlus' => array(
				'rule' => 'alphaNumericPlus',
				'message' => 'The title can only consist of Numbers, Letters, Hyphens(-), and Underscores(_)'
			),
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide a page title'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 100),
				'message' => 'The title must not be longer than 100 characters long',
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'A page with that title already exists, please select a unique title'
			)
		),
		'slug' => array(
			'alphaNumericPlus' => array(
				'rule' => 'alphaNumericPlus',
				'message' => 'The slug can only consist of Numbers, Letters, and Underscores(_)'
			),
			'notEmpty' => array(
				'rule' => array('notEmpty'),
				'message' => 'Please provide a valid slug'
			),
			'maxLength' => array(
				'rule' => array('maxLength', 100),
				'message' => 'The slug must not be longer than 100 characters long'
			),
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'A slug with that title already exists, please select a unique slug'
			)
		),
		'content' => array(
			'notEmptyUnlessLandingPage' => array(
				'rule' => 'notEmptyUnlessLandingPage',
				'message' => 'Please provide content for the page'
			)
		)
	);

	/**
	 * Finds only published pages
	 *
	 * @param slug string containing a page slug
	 * @return Page Object
	 */
	public function findPublishedBySlug($slug) {
		return $this->find('first', array('conditions' => array('Page.slug' => $slug, 'Page.published' => 1)));
	}

	public function notEmptyUnlessLandingPage($check) {
		$value = array_values($check);
		$value = $value[0];

		if (!$value && !$this->data['Page']['landing_page']) {
			return false;
		}

		return true;
	}

	public function alphaNumericPlus($check) {
		$key = array_keys($check);
		$key = $key[0];

		$value = array_values($check);
		$value = $value[0];

		$title_pattern = '|^[0-9a-zA-Z_-\s]*$|';
		$slug_pattern = '|^[0-9a-zA-Z_]*$|';

		switch ($key) {
			case 'title':
				return preg_match($title_pattern, $value);
			case 'slug':
				return preg_match($slug_pattern, $value);
		}
	}

}

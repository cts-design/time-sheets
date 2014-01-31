<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class Navigation extends AppModel {
	var $name = 'Navigation';
	var $displayField = 'title';
	var $actsAs = array(
		'Tree',
		'Translatable' => array(
			'title'
		),
		'AtlasTranslate' => array(
			'title'
		)
	);
	var $order = 'Navigation.lft ASC';

	/**
	 * Finds the parent node using position as the title, then retreives all children
	 *
	 * @param string $position the position on the navigation on the page. translates to the title of the parent node
	 * @return array $children returns all the children of the parent node
	 */
	function findChildrenByPosition($position) {
		$parent = $this->find('first', array('conditions' => array('I18n__title.content' => $position)));
		$children = $this->find('all', array('conditions' => array('parent_id' => $parent['Navigation']['id'])));

		foreach ($children  as $key => $value) {
			$grandChildren = $this->find('all', array('conditions' => array('parent_id' => $value['Navigation']['id'])));
			if (!empty($grandChildren)) {
				$children[$key]['Navigation']['children'] = $grandChildren;
			}
		}

		return $children;
	}
}
?>
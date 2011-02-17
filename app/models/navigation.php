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
        var $actsAs = array('Tree');
        var $order = 'Navigation.lft ASC';

        /**
         * Finds the parent node using position as the title, then retreives all children
         *
         * @param string $position the position on the navigation on the page. translates to the title of the parent node
         * @return array $children returns all the children of the parent node
         */
        function findChildrenByPosition($position) {
            $parent = $this->find('first', array('conditions' => array('title' => $position)));
            $parent_id = $parent['Navigation']['id'];
            $children = $this->find('list', array('conditions' => array('parent_id' => $parent_id),
                                                        'fields' => array('Navigation.title', 'Navigation.link')));

            return $children;
        }
}
?>
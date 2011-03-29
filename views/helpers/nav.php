<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */App::import('Vendor', 'DebugKit.FireCake');
class NavHelper extends AppHelper {

    var $helpers = array('Html');

    /**
     * Retreives the navigation links from the database and outputs the html
     *
     * @param string $position position your calling from (should match the title of a parent in the database)
	 * @param bool $dropDown if true, any grandchildren will be inserted in a nested unordered list for a dropdown menu
     * @return mixed returns the html output or false
     */
    function links($position = NULL, $dropDown = false) {
        if (!$position)
            return FALSE;

        $Navigation = ClassRegistry::init('Navigation'); // load the navigation model
        $links = $Navigation->findChildrenByPosition($position);

        $output = "<ul class=\"sf-menu\">";
        foreach ($links as $key => $value) {
        	$link = $this->Html->link($value['Navigation']['title'], $value['Navigation']['link']);

			if ($dropDown) {
				if (isset($value['Navigation']['children']) && !empty($value['Navigation']['children'])) {
					$output .= 
					"<li>{$link}
						<ul>";
					
					foreach ($value['Navigation']['children'] as $k => $v) {
						$lnk = $this->Html->link($v['Navigation']['title'], $v['Navigation']['link']);
						$output .= "<li>{$lnk}</li>";
					}
					
					$output .=
					"	</ul>
					</li>";
				} else {
					$output .= "<li>{$link}</li>";
				}
			} else {
				$output .= "<li>{$link}</li>";
			}
        }
        $output .= "</ul>";
		
		FireCake::log($output);
        return $output;
    }

}

?>
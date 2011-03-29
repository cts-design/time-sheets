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
	 * @param bool $nested if true, any grandchildren will be inserted in a nested unordered list for a dropdown menu
     * @param bool $trackCurrent if true, this function will check each link against the current page and insert a class of current to the list item
	 * @return mixed returns the html output or false
     */
    function links($position = NULL, $nested = false, $trackCurrent = false) {
        $class = null; 
			
        if (!$position)
            return FALSE;

        $Navigation = ClassRegistry::init('Navigation'); // load the navigation model
        $links = $Navigation->findChildrenByPosition($position);

        $output = "<ul class=\"sf-menu\">";
        foreach ($links as $key => $value) {
        	$link = $this->Html->link($value['Navigation']['title'], $value['Navigation']['link']);
			
			if ($trackCurrent) {
				if ($value['Navigation']['title'] == 'Homepage' || $value['Navigation']['title'] == 'Home') {
					if ($this->here == '/') {
						$class = ' class="current"';
					}
				} else if (strpos($this->here, $value['Navigation']['link']) !== false) {
					$class = ' class="current"';
				} else {
					$class = null;
				}
			} else {
				$class = null;
			}

			if ($nested) {
				if (isset($value['Navigation']['children']) && !empty($value['Navigation']['children'])) {
					$output .= 
					"<li{$class}>{$link}
						<ul>";
					
					foreach ($value['Navigation']['children'] as $k => $v) {
						$lnk = $this->Html->link($v['Navigation']['title'], $v['Navigation']['link']);
						$output .= "<li>{$lnk}</li>";
					}
					
					$output .=
					"	</ul>
					</li>";
				} else {
					$output .= "<li{$class}>{$link}</li>";
				}
			} else {
				$output .= "<li{$class}>{$link}</li>";
			}
        }
        $output .= "</ul>";
		
		FireCake::log($output);
        return $output;
    }

}

?>
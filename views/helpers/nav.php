<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
class NavHelper extends AppHelper {

    var $helpers = array('Html');

    /**
     * Retreives the navigation links from the database and outputs the html
     *
     * @param string $position position your calling from (should match the title of a parent in the database)
     * @param bool $nested if true, any grandchildren will be inserted in a nested unordered list for a dropdown menu
     * @return mixed returns the html output or false
     */
    function links($position = NULL, $nested = false) {
        if (!$position)
            return FALSE;

        $Navigation = ClassRegistry::init('Navigation'); // load the navigation model
        $links = $Navigation->findChildrenByPosition($position);

        $output = "<ul class=\"sf-menu\">";
        foreach ($links as $key => $value) {
	        $link = $this->Html->link($value['Navigation']['title'], $value['Navigation']['link']);

			if ($this->checkCurrentPage($value['Navigation']['link'])) {
				$class = ' class="current"';
			} else {
				$class = null;
			}
			
			if ($nested) {
				$lnks = array();
				if (isset($value['Navigation']['children']) && !empty($value['Navigation']['children'])) {
					foreach ($value['Navigation']['children'] as $k => $v) {
						$currentPage = $this->checkCurrentPage($v['Navigation']['link']);
						
						if ($class === null && $currentPage) {
							$class = ' class="current"';
							$cls = ' class="current"';
						} else if ($currentPage) {
							$cls = ' class="current"';
						} else {
							$cls = null;
						}
						
		                $lnks[] = "<li{$cls}>" . $this->Html->link($v['Navigation']['title'], $v['Navigation']['link']) . "</li>";
		            }
					
		            $output .= "<li{$class}>{$link}<ul>";
		            $output .= implode('', $lnks);
		            $output .="</ul></li>";
		        } else {
		        	$output .= "<li{$class}>{$link}</li>";
		        }
			} else {
				$output .= "<li{$class}>{$link}</li>";
			}
        }
        $output .= "</ul>";
        return $output;
    }
    
    function checkCurrentPage($navigationLink) {
		if ($navigationLink == '/' || $navigationLink == '/home' || $navigationLink == '/homepage') {
            if ($this->here == '/') {
           		return true;
            }
        } else if (strpos($this->here, $navigationLink) !== false) {
            return true;
        } else {
        	return false;
        }
    }
}

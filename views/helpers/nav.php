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
     * @param string $position
     * @return mixed returns the html output or false
     */
    function links($position = NULL) {
        if (!$position)
            return FALSE;

        // load the navigation model
        $Navigation = ClassRegistry::init('Navigation');

        // grab all the children is a one dimensional array
        // array keys will be link title, values will be relative urls
        $links = $Navigation->findChildrenByPosition($position);

        $output = "<ul>";
        foreach ($links as $k => $v) {
            $link = $this->Html->link($k, $v);
            $output .= "<li>{$link}</li>";
        }
        $output .= "</ul>";

        return $output;
    }

}

?>
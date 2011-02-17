<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
App::import('Vendor', 'DebugKit.FireCake');
class AtlasTestCase extends CakeTestCase {

   /**
    * Will return true if a matching flashMessage is in the Session
    *
    * @param <object> $object
    * @param <string> $flashMessage
    * @param <string> $layout
    * @param <array> $params
    */
    function assertFlashMessage(&$object, $flashMessage, $layout = 'element', $params = array()) {
        $actualFlashMessage = $object->Session->read('Message.flash');
        $expectedFlashMessage = array(
            'message' => $flashMessage,
            'element' => $layout,
            'params' => $params
        );

        if ($this->assertEqual($actualFlashMessage, $expectedFlashMessage)) {
            return true;
        }

        return false;
    }
}

?>

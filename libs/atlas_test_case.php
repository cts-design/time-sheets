<?php
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
App::import('Vendor', 'DebugKit.FireCake');
class AtlasTestCase extends CakeTestCase {
	// since the list of fixtures needed grows exponentially
	// and to keep the code dry, we set our fixtures here
	var $fixtures = array(
	    'app.aco',
	    'app.aro',
	    'app.aros_aco',
	    'chairman_report',
	    'deleted_document',
	    'document_filing_category',
	    'document_queue_category',
	    'document_transaction',
	    'featured_employer',
	    'filed_document',
	    'ftp_document_scanner',
	    'helpful_article',
	    'hot_job',
	    'kiosk',
	    'kiosk_button',
	    'in_the_news',
	    'location',
	    'master_kiosk_button',
	    'module_access_control',
	    'navigation',
	    'page',
	    'press_release',
	    'queued_document',
	    'rfp',
	    'role',
	    'self_scan_category',
	    'self_sign_log',
	    'self_sign_log_archive',
	    'survey',
	    'survey_question',
	    'user',
	    'user_transaction'
	);

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

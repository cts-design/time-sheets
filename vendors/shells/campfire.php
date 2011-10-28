<?php
App::import('Core', 'HttpSocket'); 
App::import('Component', 'Campfire');

            
class CampfireShell extends Shell {
	
	var $args;

	function main($message=null) {
		$this->Campfire =& new CampfireComponent();
		$id = $this->Campfire->getRoomId('Atlas');
		if($id && isset($this->args[0])) {
			$this->Campfire->sendMessage($id, $this->args[0]);	
		}
		
	}
}	

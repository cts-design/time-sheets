<?php

class RequestHelper extends AppHelper {
	
	public function get($parameter, $otherwise = NULL) {
		if(isset($_GET[$parameter])) {
			return $_GET[$parameter];
		}
		else if($otherwise != NULL){
			return $otherwise;
		}
		else {
			return FALSE;
		}
	}
	
}
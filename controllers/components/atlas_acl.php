<?php 

App::import('Component', 'Acl');

class AtlasAclComponent extends AclComponent {
	
	var $components = array('Auth');
	
/**
 * Pass-thru function for ACL check instance.  Check methods
 * are used to check whether or not an ARO can access an ACO
 * Overridden to allow users with role id of 1 to bypass ACL permissions check
 *
 * @param string $aro ARO The requesting object identifier.
 * @param string $aco ACO The controlled object identifier.
 * @param string $action Action (defaults to *)
 * @return boolean Success
 * @access public
 */
	
	function check($aro, $aco, $action = "*") {
		if($this->Auth->user('role_id') == 1) {
			return true;
		}
		return $this->_Instance->check($aro, $aco, $action);
	}
}
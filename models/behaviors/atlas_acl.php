<?php
App::import('Behavior', 'Acl');

class AtlasAclBehavior extends AclBehavior {

/**
 * Creates a new ARO/ACO node bound to this record
 * Overridden to prevent ARO creation if user has a role id of 1
 *
 * @param boolean $created True if this is a new record
 * @return void
 * @access public
 */
	function afterSave(&$model, $created) {
		$type = $this->__typeMaps[$this->settings[$model->name]['type']];
		$parent = $model->parentNode();
		if (!empty($parent)) {
			$parent = $this->node($model, $parent);
		}
		$data = array(
			'parent_id' => isset($parent[0][$type]['id']) ? $parent[0][$type]['id'] : null,
			'model' => $model->name,
			'foreign_key' => $model->id
		);
		if (!$created && isset($data['parent_id']) && $data['parent_id'] != 1) {
			$node = $this->node($model);
			$data['id'] = isset($node[0][$type]['id']) ? $node[0][$type]['id'] : null;
		}	
		if(isset($data['parent_id']) && $data['parent_id'] != 1 && $model->name == 'User') {
			$model->{$type}->create();
			$model->{$type}->save($data);			
		}
		elseif($model->name == 'Role') {
			$model->{$type}->create();
			$model->{$type}->save($data);			
		}

	}	
}
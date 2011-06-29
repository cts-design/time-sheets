<?php 
/**
 * Disableable Behavior class
 * 
 * Behavior for cakephp that allows disabling records instead of deleting them
 * Automaticly filters disabled records from all find queries. 
 * 
 * @author	Daniel Nolan
 * @copyright 2011, Complete Technology Solutions
 * @version 0.1
 * 
 */
class DisableableBehavior extends ModelBehavior {
	
	/*
	 * Sets conditions to exclude disabled records from find queries
	 * @param Model $model
	 * @param array $query
	 * @return array
	 */
	function beforeFind(&$model, $query) {
		$query['conditions'][$model->alias.'.disabled !='] = 1;
		return $query;
	}
	
	/*
	 * Toggles a records disabled column
	 * @param Model $model
	 * @param int $id
	 * @parma int $disabled
	 * @return bool
	 */	
	function toggleDisabled(&$model, $id, $disabled){
		$model->id = $id;
		if($model->saveField('disabled', $disabled)) {
			$model->Behaviors->disable('Disableable');	
			$data = $model->read(null);
			if($data[$model->alias]['disabled'] == $disabled) {
				return true;	
			}
		}
		return false;
	}
	
}
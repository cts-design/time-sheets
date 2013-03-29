<?php
class EcourseModuleResponseTime extends AppModel {
	public $name = 'EcourseModuleResponseTime';

	public $actsAs = array('Containable');

	public $belongsTo = array(
		'EcourseModuleResponse' => array(
			'className' => 'EcourseModuleResponse',
			'foreignKey' => 'ecourse_module_response_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);


	public function getTimeSpent($ecourseModuleResponseId, $type) {
		$this->recursive = -1;
		$query = $this->find('all', array(
			'conditions' => array(
				'ecourse_module_response_id' => $ecourseModuleResponseId,
				'type' => $type)));	
		if($query) {
			foreach($query as $time) {
				if($time['EcourseModuleResponseTime']['time_in'] && $time['EcourseModuleResponseTime']['time_out']) {
					$times[] = (strtotime($time['EcourseModuleResponseTime']['time_out']) - strtotime($time['EcourseModuleResponseTime']['time_in'])) / 60;
				}
			}
			if(!empty($times)) { 
				return round(array_sum($times));
			}
		}
		return 0;
	}
}

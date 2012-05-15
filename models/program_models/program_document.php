<?php
class ProgramDocument extends AppModel {
	var $name = 'ProgramDocument';
	var $displayField = 'name';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'Program' => array(
			'className' => 'Program',
			'foreignKey' => 'program_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ProgramStep' => array(
			'className' => 'ProgramStep',
			'foreignKey' => 'program_step_id'
		)
	);

	public function queueProgramDocs($programDocuments, $program, $data) {
		foreach($programDocuments as $doc) {
			// TODO add Admin to the payload if the doc is genertated from the admin area.
			$payload['Program'] = $program['Program'];
			$payload['ProgramResponse'] = $program['ProgramResponse'][0];
			$payload['User'] = $program['User'];
			$payload['ProgramDocument'] = $doc['ProgramDocument'];
			switch($doc['ProgramDocument']['type']) {
				case 'snapshot': 
					$payload['steps'][0] = array(
						'answers' => json_decode($data['ProgramResponseActivity'][0]['answers'], true),
						'name' => $program['currentStep']['name']);
					$payload['toc'] = false;
					break;
			}
			$options = array('priority' => 5000, 'tube' => $doc['ProgramDocument']['type']);
			ClassRegistry::init('Queue.Job')->put($payload, $options);
		}
	}
}

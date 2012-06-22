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

	public function queueProgramDocs($programDocuments, $program) {
		foreach($programDocuments as $doc) {
			// TODO add Admin to the payload if the doc is genertated from the admin area.
			$data['Program'] = $program['Program'];
			$data['ProgramResponse'] = $program['ProgramResponse'][0];
			$data['User'] = $program['User'];
			$data['ProgramDocument'] = $doc['ProgramDocument'];
			switch($doc['ProgramDocument']['type']) {
				case 'snapshot': 
					$data['steps'][0] = array(
						'answers' => json_decode($data['ProgramResponseActivity'][0]['answers'], true),
						'name' => $program['currentStep']['name']);
					$data['toc'] = false;
					break;
			}
			return ClassRegistry::init('Queue.QueuedTask')->createJob('document', $data);
		}
	}

	public function queueMultiSnapshot($programDocument, $program, $formStepAnswers) {
		$data['Program'] = $program['Program'];
		$data['ProgramResponse'] = $program['ProgramResponse'][0];
		$data['User'] = $program['User'];
		$data['ProgramDocument'] = $programDocument['ProgramDocument'];
		foreach($formStepAnswers as $k => $v) {
			$data['steps'][] = array(
				'answers' => json_decode($v['answers'], true),
				'name' => $v['name']);
		}
		$data['toc'] = false;
		return ClassRegistry::init('Queue.QueuedTask')->createJob('document', $data);
	}
}

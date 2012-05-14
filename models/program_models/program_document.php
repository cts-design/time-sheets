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

	public function processResponseDocs($programDocuments, $program, $data) {
		foreach($programDocuments as $doc) {
			switch($doc['ProgramDocument']['type']) {
				case 'snapshot': 
					$snapshot['steps'][0] = array(
						'answers' => json_decode($data['ProgramResponseActivity'][0]['answers'], true),
						'name' => $program['currentStep']['name']);
					$snapshot['programName'] = $program['Program']['name'];
					$snapshot['responseId'] = $program['ProgramResponse'][0]['id'];
					$snapshot['toc'] = false;
					$snapshot['user'] = $program['User']['name_last4'];
					$snapshot['userId'] = $program['User']['id'];
					$snapshot['ProgramDocument'] = $doc;
					break;
					$options = array('priority' => 5000, 'tube' => 'pdf_snapshot');
					ClassRegistry::init('Queue.Job')->put($snapshot, $options);
			}
		}
	}
}

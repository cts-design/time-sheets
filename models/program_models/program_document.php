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

	public function queueProgramDocs($programDocuments, $data) {
		unset($data['ProgramStep']);
		unset($data['ProgramInstruction']);
		unset($data['ProgramEmail']);
		unset($data['ProgramResponseDoc']);
		foreach($programDocuments as $doc) {
			if(!empty($data['ProgramResponse'][0])){
				$data['ProgramResponse'] = $data['ProgramResponse'][0];
			}
			$data['ProgramDocument'] = $doc['ProgramDocument'];
			if($doc['ProgramDocument']['type'] === 'snapshot') {
				$data['steps'][0] = array(
						'answers' => json_decode($data['ProgramResponse']['ProgramResponseActivity'][0]['answers'], true),
						'name' => $data['currentStep']['name']);
				$data['toc'] = false;
			}
			elseif(isset($data['ProgramResponseActivity']) &&
				$doc['ProgramDocument']['type'] === 'certificate' ||
				$doc['ProgramDocument']['type'] === 'pdf') {
					$i = 0;
					foreach($data['ProgramResponseActivity'] as $activity) {
						if($activity['type'] === 'form') {
							$data['steps'][$i]['answers'] = json_decode($activity['answers'], true);
						}
						$i++;
					}
			}
			unset($data['ProgramResponse']['ProgramResponseActivity']);
			return ClassRegistry::init('Queue.QueuedTask')->createJob('document', $data);
		}
	}

	public function queueMultiSnapshot($data, $formStepAnswers) {
		unset($data['ProgramStep']);
		unset($data['ProgramInstruction']);
		unset($data['ProgramEmail']);
		$data['ProgramResponse'] = $data['ProgramResponse'][0];
		unset($data['ProgramResponse']['ProgramResponseActivity']);
		foreach($formStepAnswers as $k => $v) {
			$data['steps'][] = array(
				'answers' => json_decode($v['answers'], true),
				'name' => $v['name']);
		}
		$data['toc'] = false;
		return ClassRegistry::init('Queue.QueuedTask')->createJob('document', $data);
	}
}

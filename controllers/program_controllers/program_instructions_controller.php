<?php
class ProgramInstructionsController extends AppController {

	var $name = 'ProgramInstructions';

	function admin_index($id=null) {
		if(!$id){
			$this->Session->setFlash(__('Invalid program id.', true), 'flash_failure');
			$this->redirect(array(
				'controller' => 'programs',
				'action' => 'index',
				'admin' => true));
		}
		if($this->RequestHandler->isAjax()) {
			$this->ProgramInstruction->recursive = -1;
			if($id) {
				$instructions = $this->ProgramInstruction->find('all', array(
					'conditions' => array(
						'ProgramInstruction.program_id' => $id
					),
					'fields' => array(
						'ProgramInstruction.id',
						'ProgramInstruction.program_id',
						'ProgramInstruction.type',
						'ProgramInstruction.text'
					)));
				if($instructions) {
					$i = 0;
					foreach ($instructions as $instruction) {
						$data['instructions'][$i] = $instruction['ProgramInstruction'];
						$data['instructions'][$i]['name'] =
							Inflector::humanize($instruction['ProgramInstruction']['type']);
						$data['instructions'][$i]['actions'] =
							sprintf('<a href="/admin/program_instructions/edit/%s">Edit</a>',
								$data['instructions'][$i]['id']);
						$i++;
					}
				}
			}
			else {
				$data['message'] = 'Invalid program id.';
				$data['success'] = false;
			}
			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}
		$title_for_layout = 'Program Instructions';
		$this->set(compact('title_for_layout'));
	}

	function admin_edit() {
		if($this->RequestHandler->isAjax()) {
			if(!empty($this->data)) {
				$this->data['ProgramInstruction'] = json_decode($this->data['ProgramInstruction'], true);
				if($this->ProgramInstruction->save($this->data)) {
					$this->data = $this->ProgramInstruction->read(null, $this->data['ProgramInstruction']['id']);
					$data['success'] = true;
					$data['message'] = 'Instructions saved sucessfully.';
					$this->Transaction->createUserTransaction('Programs', null, null,
						'Edited ' . Inflector::humanize($this->data['ProgramInstruction']['type']) .
						' instructions for ' . $this->data['Program']['name']);
				}
				else {
					$data['success'] = false;
					$data['message'] = 'Unable to save instructions.';
				}
			}
			else {
				$data['success'] = false;
				$data['message'] = 'Unable to save instructions.';
			}
			$this->set('data', $data);
			$this->render('/elements/ajaxreturn');
		}
	}

	public function admin_create_single() {
		$data['success'] = false;
		$formParams = $this->params['form'];

		$instructionData = array(
			'ProgramInstruction' => array(
				'program_id' => $formParams['program_id'],
				'program_step_id' => $formParams['program_step_id'],
				'text' => $formParams['text'],
				'type' => $formParams['type']
			)
		);

		$this->ProgramInstruction->create();
		if ($this->ProgramInstruction->save($instructionData)) {
			$data['success'] = true;
		}

		$this->set('data', $data);
		$this->render('/elements/ajaxreturn');
	}

	public function admin_create() {
			$formData = json_decode($this->params['form']['program_instructions'], true);

			foreach ($formData as $key => $value) {
				unset($formData[$key]['id'], $formData[$key]['created'], $formData[$key]['modified']);
			}

			$count = count($formData);

			if ($count > 1) {
				$this->data['ProgramInstruction'] = $formData;
				$formField = $this->ProgramInstruction->saveAll($this->data['ProgramInstruction']);
			} else {
				$this->data['ProgramInstruction'] = $formData[0];
				$formField = $this->ProgramInstruction->save($this->data);
			}

			if ($formField) {
				if ($count > 1) {
					$this->ProgramInstruction->recursive = -1;
					$newInstructions = $this->ProgramInstruction->find('all', array(
						'order' => 'ProgramInstruction.id DESC',
						'limit' => $count
					));

					foreach ($newInstructions as $k => $v) {
						$data['program_instructions'][] = $v['ProgramInstruction'];
					}
				} else {
					$formField['ProgramInstruction']['id'] = $this->ProgramInstruction->id;
					$data['program_instructions'] = $formField['ProgramInstruction'];
				}

				$data['success'] = true;
			} else {
				$data['success'] = false;
			}

			$this->set('data', $data);
			$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_read() {
		$programId = $this->params['url']['program_id'];

		$this->ProgramInstruction->recursive = -1;
		$instructions = $this->ProgramInstruction->find('all', array(
			'conditions' => array(
				'ProgramInstruction.program_id' => $programId
			)
		));

		if ($instructions) {
			$data['success'] = true;
			foreach ($instructions as $key => $value) {
				$data['program_instructions'][] = $value['ProgramInstruction'];
			}
		} else {
			$data['success'] = false;
		}

		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_update() {
		$instruction = json_decode($this->params['form']['program_instructions'], true);
		$count = count($instruction);
		$success = false;

		$this->ProgramInstruction->id = $instruction['id'];
		$this->ProgramInstruction->set($instruction);

		if ($this->ProgramInstruction->save()) {
			$success = true;
		}

		$data['success'] = $success;

		$this->set('data', $data);
		$this->render(null, null, '/elements/ajaxreturn');
	}

	public function admin_destroy() {}
}

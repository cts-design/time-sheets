<?= $this->Html->script('programs/dashboard', array('inline' => false)) ?>
<?= $this->Html->script('adobe-reader-check', array('inline' => false)) ?>
<?= (!empty($instructions) ? '<div id="instructions">' . $instructions . '</div>' : '' ) ?>

<div class="steps-container enrollment">
	<ol class="steps">
		<?php $statuses = array('incomplete','not_approved', 'pending_document_review') ?>
		<?php if(in_array($programResponse['ProgramResponse']['status'], $statuses)) : ?>
			<li class="program current incomplete">
				<div class="details">
					<h3><?= $program['Program']['name'] ?> Enrollment</h3>
				</div>
				<span class="status <?= $programResponse['ProgramResponse']['status'] ?>">
					Current Status: <?= Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
				</span>
			</li>
			<?php $k = 0; ?>
			<?php $stepNumber = 0; ?>
			<?php $stepsAfterFirstIncomplete = 0; ?>
			<?php foreach($program['ProgramStep'] as $step) : ?>
				<?php if(!$step['type']) : ?>
				<?php if ($k): ?></ol><?php endif ?>
					<li class="module">
						<div class="details">
							<h3><?= $step['name'] ?></h3>
						</div>
						<?php
							$i = 0;
							foreach ($completedSteps as $completedStep) {
								if (in_array($completedStep, $programSteps[$step['id']])) {
									$i++;
								}
							}
						?>
						<?php $stepStatus = ($i === count($programSteps[$step['id']])) ? 'complete' : 'incomplete' ?>
						<?php $redoable = ($step['redoable']) ? 'redoable' :'' ?>
						<span class="steps <?= $redoable ?> status <?= $stepStatus ?>"><?= $i ?> of <?= count($programSteps[$step['id']]) ?> steps completed</span>
						<ol>
						<?php $k++ ?>
				<?php else : ?>
							<?php $stepNumber++ ?>
							<?php $class = (in_array($step['id'], $completedSteps)) ? 'complete' : 'incomplete' ?>
							<li class="step <?= $class . ' ' . $step['type'] ?>">
								<div class="inner-container">
									<?= "Step $stepNumber:" ?>
									<?= $step['name'] ?>
									<?php if($step['type'] === 'media') : ?>
										<?php if ($step['media_type'] === 'pdf'): ?>
											<img class="ico" src="/img/icons/pdf.png" />
										<?php elseif ($step['media_type'] === 'flv'): ?>
											<img class="ico" src="/img/icons/flv.png" />
										<?php endif; ?>
										<?php $actionName = ($class === 'complete') ? 'Completed' : 'View Media' ?>
										<?php $link = $this->Html->link($actionName, array(
											'controller' => 'program_responses',
											'action' => 'media', 
											$program['Program']['id'], 
											$step['id'])) ?>
									<?php elseif($step['type'] === 'form' || $step['type'] === 'custom_form') : ?>
										<img class="ico" src="/img/icons/form.png" />
										<?php $actionName = ($class === 'complete') ? 'Completed' : 'Complete Form' ?>
										<?php $link = $this->Html->link($actionName, array(
											'controller' => 'program_responses',
											'action' => 'form',
											$program['Program']['id'],
											$step['id'])
											)
										?>
										<?php if($programResponse['ProgramResponse']['status'] === 'not_approved') : ?>
											<?php $link = $this->Html->link('Edit Form', array(
												'controller' => 'program_responses',
												'action' => 'edit_form',
												$program['Program']['id'],
												$step['id'])) ?>
										<?php endif ?>
									<?php elseif($step['type'] === 'required_docs') : ?>
										<img class="ico" src="/img/icons/doc_upload.png" />
										<?php $link = $this->Html->link('Upload Documents', array(
											'controller' => 'program_responses',
											'action' => 'upload_docs',
											$program['Program']['id'],
											$step['id'])) ?>
										<?php $link2 = $this->Html->link('Drop Off/Fax Documents', array(
											'controller' => 'program_responses',
											'action' => 'drop_off_docs',
											$program['Program']['id'],
											$step['id'])) ?>
									<?php endif ?>
									<span class="action">
										<?php echo ( isset($link) ? $link : "" ) ?>
										<?php if(isset($link2)) : ?>
											<?= $link2?>
										<?php endif ?>
										<?php if ($class === 'complete'): ?>
											<?php $completedDate = Set::extract('/ProgramResponseActivity[program_step_id=' . $step['id'] . ']/created', $programResponse) ?>
											<span class="completed-date">
												Completed: <?= date('m/d/Y', strtotime($completedDate[0])) ?>
											</span>
										<?php endif; ?>
									</span>
								</div>
							</li>
						<?php endif ?>
					<?php endforeach ?>
						</ol>
					</li>
		<?php elseif($programResponse['ProgramResponse']['status'] === 'pending_approval') : ?>
			<li class="module incomplete">
				<div class="details">
					<h3><?= $program['Program']['name'] ?> Enrollment</h3>
					<p><?= count($completedSteps) ?> of X steps completed</p>
				</div>
				<span class="status">
					<?= Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
				</span>
			</li>
		<?php elseif($programResponse['ProgramResponse']['status'] === 'complete') : ?>
			<li class="module complete">
				<div class="details">
					<h3><?= $program['Program']['name'] ?> Enrollment</h3>
				</div>
				<span class="status">
					<?= Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
				</span>
				<ol>
					<?php //TODO add a check if program has a certificate for download ?>
					<li class="step certificate">
						<div class="inner-container">
							<?= $this->Html->link(
								'View Certificate',
									array(
										'controller' => 'program_responses',
										'action' => 'view_cert',
										$program['Program']['id']
									)
								)
							?>
						</div>
					</li>
				</ol>
			</li>
		<?php endif ?>
	</ol>
</div>

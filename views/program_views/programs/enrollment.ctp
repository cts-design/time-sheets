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
					<?= Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
				</span>
			</li>
			<?php $k = 0; ?>
			<?php foreach($program['ProgramStep'] as $step) : ?>
				<?php if(!$step['type']) : ?>
				<?php if ($k): ?></ol><?php endif ?>
					<li class="module">
						<div class="details">
							<h3><?php echo $step['name'] ?></h3>
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
						<span class="steps status <?= $stepStatus ?>"><?= $i ?> of <?= count($programSteps[$step['id']]) ?> steps completed</span>
						<ol>
						<?php $k++ ?>
				<?php else : ?>
							<?php $class = (in_array($step['id'], $completedSteps)) ? 'complete' : 'incomplete' ?>
							<li class="step <?= $class . ' ' . $step['type'] ?>">
								<div class="inner-container">
									<?= $step['name'] ?>
									<?php if($step['type'] === 'media') : ?>
										<?php $link = $this->Html->link('View Media', array(
											'controller' => 'program_responses',
											'action' => 'media', 
											$program['Program']['id'], 
											$step['id'])) ?>
									<?php elseif($step['type'] === 'form') : ?>
										<?php $link = $this->Html->link('Complete Form', array(
											'controller' => 'program_responses',
											'action' => 'form',
											$program['Program']['id'],
											$step['id'])) ?>
										<?php if($programResponse['ProgramResponse']['status'] === 'not_approved') : ?>
											<?php $link = $this->Html->link('Edit Form', array(
												'controller' => 'program_responses',
												'action' => 'edit_form',
												$program['Program']['id'],
												$step['id'])) ?>
										<?php endif ?>
									<?php elseif($step['type'] === 'required_docs') : ?>
										<?php $link = $this->Html->link('Upload Documents', array(
											'controller' => 'program_responses',
											'action' => 'upload_docs',
											$program['Program']['id'],
											$step['id'])) ?>
										<?php $link2 = $this->Html->link('Drop Off Documents', array(
											'controller' => 'program_responses',
											'action' => 'drop_off_docs',
											$program['Program']['id'],
											$step['id'])) ?>
									<?php endif ?>
									<span class="action">
										<?= $link ?>
										<?php if(isset($link2)) : ?>
											<?= $link2?>
										<?php endif ?>
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
					<p><?= count($completedSteps) ?> of X steps completed</p>
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

<?= $this->Html->script('programs/dashboard', array('inline' => false)) ?>
<?= $this->Html->script('adobe-reader-check', array('inline' => false)) ?>
<?php echo (!empty($instructions) ? '<div id="instructions">' . $instructions . '</div>' : '' ) ?>

<div class="steps-container">
	<ol class="steps">
		<li class="module <?= $class = (count($completedSteps)) ? 'complete' : 'incomplete' ?>">
			<div class="details">
				<h3><?= $program['Program']['name'] ?> Registration</h3>
				<p><?= count($completedSteps) ?> of 1 steps completed</p>
			</div>
			<span class="status">
				<?php echo Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
			</span>
			<ol>
			<?php if($programResponse['ProgramResponse']['status'] === 'incomplete') : ?>
				<?php $redoable = ($step['redoable']) ? 'redoable' : '' ?>
				<li class="step incomplete <?= $redoable ?>">
					<div class="inner-container">
						<?= $program['ProgramStep'][1]['name'] ?>
						<span class="action">
							<?php echo $html->link(
								'Complete Form',
								array(
									'controller' => 'program_responses',
									'action' => 'form',
									$program['Program']['id'],
									$program['ProgramStep'][1]['id']
								)
							)
							?>
						</span>
					</div>
				</li>
			<?php endif; ?>
			<?php if(!empty($programResponse['ProgramResponseActivity'])
				&& $programResponse['ProgramResponseActivity'][0]['status'] === 'allow_edit'
				&& $programResponse['ProgramResponse']['status'] === 'not_approved') : ?>
				<li class="step complete">
					<div class="inner-container">
						<?= $program['ProgramStep'][1]['name'] ?>
						<span class="action">
							<?php echo $html->link(
								'Edit Form',
								array(
									'controller' => 'program_responses',
									'action' => 'edit_form',
									$program['Program']['id'],
									$program['ProgramStep'][1]['id']
								)
							)
							?>
						</span>
					</div>
				</li>
			<?php endif; ?>
			</ol>
		</li>
	</ol>
</div>

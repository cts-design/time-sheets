<?= $this->Html->script('programs/dashboard', array('inline' => false)) ?>
<?= $this->Html->script('adobe-reader-check', array('inline' => false)) ?>
<?= (!empty($instructions) ? '<div id="instructions">' . $instructions . '</div>' : '' ) ?>

<div class="steps-container">
	<ol class="steps">
		<?php $statuses = array('incomplete','not_approved', 'pending_document_review') ?>
		<?php if(in_array($programResponse['ProgramResponse']['status'], $statuses)) : ?>
			<li class="module incomplete">
				<div class="details">
					<h3><?= $program['Program']['name'] ?> Enrollment</h3>
					<p><?= count($completedSteps)//TODO: get total steps per module ?> of X steps completed</p>
				</div>
				<span class="status">
					<?= Inflector::humanize($programResponse['ProgramResponse']['status']) ?>
				</span>
				 <ol>
					<?php foreach($program['ProgramStep'] as $step) : ?>
						<?php if(!$step['type']) : ?>
							<li class="module"><?php echo $step['name'] ?></li>
						<?php else : ?>
							<?php $class = (in_array($step['id'], $completedSteps)) ? 'complete' : 'incomplete' ?>
							<li class="step <?= $class ?>">
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

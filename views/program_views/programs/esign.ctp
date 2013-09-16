<?= $this->Html->script('programs/dashboard', array('inline' => false)) ?>
<?= $this->Html->script('adobe-reader-check', array('inline' => false)) ?>
<?= $this->Html->script('QueryString'); ?>
<?= $this->Html->script('programs/esign') ?>
<?= $this->Html->css('programs/esign'); ?>
<?php echo (!empty($instructions) ? '<div id="instructions">' . $instructions . '</div>' : '' ) ?>

<div class="steps-container">
	<ol class="steps">
		<li class="module incomplete">
			<div class="details">
				<h3>E-Signature</h3>
				<p><?= ($programResponse['ProgramResponse']['status'] === 'incomplete') ? 0 : 1 ?> of 1 steps completed</p>
			</div>
			
			<span class="status">
				<?php 
					echo Inflector::humanize($programResponse['ProgramResponse']['status']) 
				?>
			</span>
			<?php if($programResponse['ProgramResponse']['status'] === 'pending_document_review'): ?>
				<span class="waiting">
					<?= $this->Html->image('programs/ajax-loader.gif', array('class' => 'loader')) ?>
					Waiting for E-Signature...
				</span>
			<?php endif ?>
			<ol>
			<?php if($programResponse['ProgramResponse']['status'] === 'incomplete') : ?>
				<li class="step incomplete">
					<div class="inner-container">
						E-Signature Form Download
						<span class="action">
							<?php echo $html->link(
								'Download Form',
								array(
									'controller' => 'program_responses',
									'action' => 'download_esign_form',
									$program['Program']['id']
								)
							)
							?>
						</span>
					</div>
				</li>
			<?php endif; ?>
				<?php if($programResponse['ProgramResponse']['status'] === 'not_approved' ||
				$programResponse['ProgramResponse']['status'] === 'pending_document_review' ||
				$programResponse['ProgramResponse']['status'] === 'complete') : ?>
				<li class="step complete">
					<div class="inner-container">
						E-Signature Form Download
						<span class="action">
							<?php echo $html->link(
								'Re-Download Form',
								array(
									'controller' => 'program_responses',
									'action' => 'download_esign_form',
									$program['Program']['id']
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

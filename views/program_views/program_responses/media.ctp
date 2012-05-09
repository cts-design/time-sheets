<?php if(isset($instructions)) : ?>
	<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
	<div><a id="Toggle" class="small" style="display: none"><?php __('Hide Instructions') ?></a></div>
	<div id="Instructions"><?php echo $instructions ?></div>
	<noscript>
		<div id="Instructions"><?php echo $instructions ?></div>
	</noscript>
	<br />
<?php endif ?>

<?php echo $this->element($element) ?>

<?php if ($acknowledgeMedia) : ?>
	<div id="Acknowledge">
		<?php echo $form->create('ProgramResponse', array('action' => 'media/' . $this->params['pass'][0]));?>
		<br />
		<br />
		<div>
			<?php // @TODO possibly make this dynamic so the verbiage can be changed by the admin ?>
		    <?php $label = sprintf(__("I acknowledge that I have viewed the orientation and completely understand its content.
				I futher understand that it is my responsibility to abide by the rules and requirements.
				I also understand clearly that my failure to comply with the conditions may result in the 
				loss of %s services.", true), $title_for_layout) ?>
			<?php echo $form->input('ProgramResponse.viewed_media', array(
				'type' => 'checkbox', 
				'label' => $label)) ?>			
		</div>
		<br class="clear" />	
		<div class="top-mar-20"><?php echo $form->end(__('Submit', true)) ?></div>
	</div>
<?php endif ?>

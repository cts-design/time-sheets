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
		<?php echo $form->create('Program', array('action' => 'view_media/' . $this->params['pass'][0] . '/' . 
			$this->params['pass'][1]));
		?>
		<br />
		<br />
		<div>
		    <?php $label = __("I acknowledge that I have viewed the orientation and completely understand its content.
				I futher understand that it is my responsibility to abide by the rules and requirements.
				I also understand clearly that my failure to comply with the conditions may result in the 
				loss of services.", true) ?>
			<?php echo $form->input('ProgramResponse.viewed_media', array(
				'type' => 'checkbox', 
				'label' => $label)) ?>			
			<?php echo $form->input('ProgramResponse.program_id',  array('type' => 'hidden', 'value' => $this->params['pass'][0]));?>				
		</div>
		<br class="clear" />	
		<div class="top-mar-20"><?php echo $form->end(__('Submit', true)) ?></div>
	</div>
<?php endif ?>

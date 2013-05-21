<?php if(isset($instructions)) : ?>
	<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
  <div class="show-instructions">
    <a href="#" ><?php __('Show instructions') ?></a>
  </div>
  <div id="instructions">
    <?php echo $instructions ?>
    <div class="hide-instructions">
      <a href="#"><?php __('Hide these instructions') ?></a>
    </div>
  </div>
	<noscript>
		<div id="instructions"><?php echo $instructions ?></div>
	</noscript>
	<br />
<?php endif ?>

<?php echo $this->element($element) ?>

<?php if ($acknowledgeMedia) : ?>
	<div id="Acknowledge">
		<?php echo $form->create('ProgramResponse', array('action' => 'media/' . $this->params['pass'][0] . '/' . 
			$this->params['pass'][1]));
		?>
		<br />
		<br />
		<div>
			<?php if(!empty($media_acknowledgement_text)) : ?>
				<?php $label = $media_acknowledgement_text ?>
			<?php else : ?>
				<?php $label = sprintf(__("I acknowledge that I have viewed the orientation and completely understand its content.
					I futher understand that it is my responsibility to abide by the rules and requirements.
					I also understand clearly that my failure to comply with the conditions may result in the 
					loss of %s services.", true), $title_for_layout) ?>
			<?php endif ?>
			<?php echo $form->input('ProgramResponse.viewed_media', array(
				'type' => 'checkbox', 
				'label' => $label,
				'error' => false)) ?>
			<br /><br />
			<?php echo $this->Form->error('ProgramResponse.viewed_media'); ?>
		</div>
		<br class="clear" />	
		<div class="top-mar-20"><?php echo $form->end(__('Submit', true)) ?></div>
	</div>
<?php endif ?>

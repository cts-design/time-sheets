<style>
.version-btns
{
	display:inline-block;
	padding:6px 16px;
	margin:5px;

	border-bottom:2px solid #E0E0E0;
	border-top:2px solid #E0E0E0;

	background-color:#D0D0D0;
	color:#FFF;
	font-size:12pt;
}
.version-btns:hover
{
	background-color:#C0C0C0;
	text-decoration: none;
}
.version-btns:active
{
	border: none;
}
</style>
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

	<?php if(isset($parent_media_step)): ?>
		<a class="version-btns" href="<?= $html->url('/program_responses/media/' . $this->params['pass'][0] . '/' . $parent_media_step['ProgramStep']['id'], true) ?>">
			<?= $parent_media_step['ProgramStep']['name'] ?>
		</a>
	<?php endif ?>

	<?php foreach($alternate_media as $alt): ?>
		<a class="version-btns" href="<?= $html->url('/program_responses/media/' . $this->params['pass'][0] . '/' . $alt['ProgramStep']['id'], true) ?>">
			<?= $alt['ProgramStep']['name'] ?>
		</a>
	<?php endforeach ?>

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

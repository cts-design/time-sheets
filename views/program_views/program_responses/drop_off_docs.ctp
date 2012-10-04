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
<div id="DroppingOffDocs">
	<p>
		<strong>
			<?php echo $html->link(__('I will drop my documents off.', true), 
			'/program_responses/provided_docs/' . $this->params['pass'][0] . '/' . $this->params['pass'][1] .'/drop_off_docs'); ?>
		</strong>		
	</p>
</div>

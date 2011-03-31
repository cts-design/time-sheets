<script>
	$(document).ready(function(){
		$('#toggle').show();
		$('#toggle').toggle(function(){
			$('#instructions').show();
			$('#toggle').html('Hide Instructions');
		},
		function() {
			$('#instructions').hide();
			$('#toggle').html('Show Instructions');
		}
		)
	})
</script>
<div><a id="toggle" class="small" style="display: none">View Instructions</a></div>
<p id="instructions" style="display: none"><?php echo $instructions ?></p>
<noscript>
	<p id="instructions"><?php echo $instructions ?></p>
</noscript>
<br />
<?php echo $this->element($element) ?>

<?php echo $form->create('Program', array('action' => 'view_media/' . $this->params['pass'][0])) ?>
<br />
<p>
	By checking the box below I agree that I have reviewed the instructions and/or media above.
</p>
<br />
<?php echo $form->input('ProgramResponse.viewed_media', array('type' => 'checkbox', 'label' => 'I agree')) ?>
<?php echo $form->input('ProgramResponse.program_id',  array('type' => 'hidden', 'value' => $this->params['pass'][0]));?>
<?php echo $form->end('Submit') ?>

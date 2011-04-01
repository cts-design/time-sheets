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
<a id="toggle" class="small" style="display: none">View Instructions</a>
<p id="instructions" style="display: none"><?php echo $instructions ?></p>
<noscript>
	<p id="instructions"><?php echo $instructions ?></p>
</noscript>

<?php echo $form->create('ProgramResponse', array('action' => 'required_docs/'.$this->params['pass'][0], 'type' => 'file')) ?>

<?php echo $form->file('QueuedDocument.submittedfile', array('label' => 'Document')) ?>

<?php echo $form->input('QueuedDocument.queue_category_id', array('type' => 'hidden', 'value' => $queueCategoryId)) ?>

<?php echo $form->input('program_id', array('type' => 'hidden', 'value' => $this->params['pass'][0])) ?>

<?php echo $form->end('Upload') ?>
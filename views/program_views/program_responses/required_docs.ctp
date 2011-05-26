<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
<a id="Toggle" class="small" style="display: none">Hide Instructions</a>
<div id="Instructions"><?php echo $instructions ?></div>
<noscript>
	<div id="Instructions"><?php echo $instructions ?></div>
</noscript>

<?php echo $form->create('ProgramResponse', array('action' => 'required_docs/'.$this->params['pass'][0], 'type' => 'file')) ?>

<?php echo $form->file('QueuedDocument.submittedfile', array('label' => 'Document')) ?>

<?php echo $form->input('QueuedDocument.queue_category_id', array('type' => 'hidden', 'value' => $queueCategoryId)) ?>

<?php echo $form->input('program_id', array('type' => 'hidden', 'value' => $this->params['pass'][0])) ?>

<?php echo $form->end('Upload') ?>
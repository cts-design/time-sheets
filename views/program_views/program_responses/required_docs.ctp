<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
<a id="Toggle" class="small" style="display: none">Hide Instructions</a>
<div id="Instructions"><?php echo $instructions ?></div>
<noscript>
	<div id="Instructions"><?php echo $instructions ?></div>
</noscript>
<div id="RequiredDocs">
	
	<strong>
		<p class="bot-mar-10">
			<?php echo $html->link('I am going to mail, fax, or drop of my documents.', 
				'/program_responses/provided_docs/' . $this->params['pass'][0] .'/dropping_off_docs') ?>
		</p>
	</strong>	
	<?php echo $form->create('ProgramResponse', array('action' => 'required_docs/'.$this->params['pass'][0], 'type' => 'file')) ?>
	<fieldset>
		<legend>Upload Documents</legend>
	<?php echo $form->file('QueuedDocument.submittedfile', array('label' => 'Document')) ?>
	<p class="bot-mar-10">After uploading a document you will be returned to this page so you can upload additional documents as nessesary.</p>
	<?php echo $form->error('QueuedDocument.submittedfile') ?>
	<?php echo $form->input('QueuedDocument.queue_category_id', array('type' => 'hidden', 'value' => $queueCategoryId)) ?>
	
	
	<?php echo $form->input('program_id', array('type' => 'hidden', 'value' => $this->params['pass'][0])) ?>
	
	</fieldset>
	<?php echo $form->end('Upload') ?>	
	<br />	
	<p>
		<strong>
			<?php echo $html->link('I am finished uploading my documents.', 
			'/program_responses/provided_docs/' . $this->params['pass'][0] .'/uploaded_docs'); ?>
		</strong>		
	</p>
	

</div>

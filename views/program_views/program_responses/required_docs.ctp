<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
<a id="Toggle" class="small" style="display: none"><?php __('Hide Instructions') ?></a>
<div id="Instructions"><?php echo $instructions ?></div>
<noscript>
	<div id="Instructions"><?php echo $instructions ?></div>
</noscript>
<div id="RequiredDocs">
		
	<?php echo $form->create('ProgramResponse', array('action' => 'required_docs/'.$this->params['pass'][0], 'type' => 'file')) ?>
	<fieldset>
        <legend><?php __('Upload Documents') ?></legend>
	<?php echo $form->file('QueuedDocument.submittedfile', array('label' => 'Document')) ?>
    <span><?php __('Please upload only PDF files, 1Mb max size.') ?></span>
    <p class="bot-mar-10"><?php __('After uploading a document you will be returned to this page so you can upload additional documents as nessesary.') ?></p>
	<?php echo $form->error('QueuedDocument.submittedfile') ?>
	<?php echo $form->input('QueuedDocument.queue_category_id', array('type' => 'hidden', 'value' => $queueCategoryId)) ?>
	
	
	<?php echo $form->input('program_id', array('type' => 'hidden', 'value' => $this->params['pass'][0])) ?>
	
	</fieldset>
	<?php echo $form->end(__('Upload', true)) ?>	
	<br />	
	<p>
		<strong>
			<?php echo $html->link(__('I am finished uploading my documents.', true), 
			'/program_responses/provided_docs/' . $this->params['pass'][0] .'/uploaded_docs'); ?>
		</strong>		
	</p>
	

</div>

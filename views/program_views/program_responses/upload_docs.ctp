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
<div id="UploadRequiredDocs">
		
	<?php echo $form->create('ProgramResponse', array('action' => 'upload_docs/'.$this->params['pass'][0].'/'.$this->params['pass'][1], 'type' => 'file')) ?>
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
			'/program_responses/provided_docs/' . $this->params['pass'][0] . '/' . $this->params['pass'][1] .'/uploaded_docs'); ?>
		</strong>
	</p>
</div>

<script>
$(document).ready(function(){
	var flash = '<?= $this->Session->flash() ?>';

	if(flash !== '')
	{
		Ext.Msg.alert('Upload Status', flash);
	}
});
</script>

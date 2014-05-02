<?php echo $html->script('program_responses/toggle_instructions', array('inline' => false)) ?>
<?php echo $this->Html->script('program_responses/upload_docs', array('inline' => FALSE));?>

<?= $this->Html->css('programs/program_responses/upload_docs') ?>
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
	
	<?php if(!$is_kiosk): ?>
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
	<?php else: ?>

		<div class="left">

			<p class="step">1. Place the document you want to scan on the scanner bed.</p>
			<button class="scan">Scan</button>

			<p class="step">2. If you need to rescan the document then rescan</p>
			<button class="rescan">Rescan</button>

			<p class="step">3. If the document in the preview looks correct, hit Save</p>
			<button class="save">Save</button>
		</div>
		<div id="scan-container">
		</div>

	<?php endif ?>

	<br clear="all" />
	<p>
		<strong>
			<?php echo $html->link(__('I am finished uploading my documents.', true), 
			'/program_responses/provided_docs/' . $this->params['pass'][0] . '/' . $this->params['pass'][1] .'/uploaded_docs'); ?>
		</strong>
	</p>
</div>

<div id="dialog">

	<p>
	</p>

</div>
<script src="/js/scan.js"></script>
<script>
var upload_success = '<?= $this->Session->flash('upload_success') ?>';
var upload_error = '<?= $this->Session->flash('upload_error') ?>';
$(document).ready(function(){

	$('#scan-container').KioskScan({
		$scan			: $('.scan'),
		$rescan 		: $('.rescan'),
		$save 			: $('.save'),
		company			: 'ATLAS',
		location_id		: '<?= isset($locationId) ? $locationId : '' ?>',
		self_scan_cat_id: '<?= isset($selfScanCatId) ? $selfScanCatId : '' ?>',
		queue_cat_id 	: '<?= isset($queueCatId) ? $queueCatId : '' ?>',
		user_id			: '<?= $session->read('Auth.User.id') != NULL ? $session->read('Auth.User.id') : '' ?>',
		php_session_id	: '<?= $this->Session->id() ?>'
	});

	if(upload_success != '')
	{
		$('#dialog').attr('title', 'Successfully Uploaded');
		$('#dialog').find('p').text(upload_success);
		//Declare dialog
	    $('#dialog').dialog({
			modal: true,
			draggable : false,
			resizable : false,
			buttons : {
				"Upload another document" : function() {
					$(this).dialog('close');
				}
			}
		});
	}
	else if(upload_error != '')
	{
		$('#dialog').attr('title', 'Upload Issue');
		$('#dialog').find('p').text(upload_error);
		//Declare dialog
	    $('#dialog').dialog({
			modal: true,
			draggable : false,
			resizable : false,
			buttons : {				
				Ok : function() {
					$(this).dialog('close');
				}
			}
		});
	}
    
});
</script>

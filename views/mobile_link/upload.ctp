<div id="upload" class="centered">
	<h3>Upload Documents</h3>

	<p class="error">
		<?= $this->Session->flash('flash_error') ?>
	</p>
	<ol class="instructions">
		<li>
			Click the Choose File button.
		</li>
		<li>
			Take a photo of your document.
		</li>
		<li>
			Click Upload Document to complete.
		</li>
	</ol>

	<p type="text" id="fake-file"></p>

	<?= $this->Form->create(array('type' => 'file', 'url' => '/mobile_link/upload/' . $mobile_link_id)) ?>
        <div class="file-mock">
        	<?= $this->Form->file('submittedfile', array(
			'class' => 'file-upload',
    		'label' => "Please Enter Your Mobile Phone Number", 
    		'accept' => 'image/*;capture=camera'
    		))
        ?>

        <button class="slate">Capture Image from Phone</button>

        <button type="submit" class="sky submit" style="margin-top:20px">Upload</button>
        </div>
	<?= $this->Form->end() ?> 
</div>
<script>
$(document).ready(function(){

	var fake_file = $('#fake-file');
	$('.file-upload').change(function(){
		fake_file.text( $(this).val() );
	});
});
</script>
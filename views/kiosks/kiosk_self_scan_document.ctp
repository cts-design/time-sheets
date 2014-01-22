<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<?php echo $this->Html->script('jquery.validate', array('inline' => false)) ?>
<?php echo $this->Html->script('jquery.idleTimer', array('inline' => false)) ?>
<?php echo $this->Html->script('kiosks/self_scan_document', array('inline' => false)) ?>

<div id="SelfScanDoument" >
	<h1><?php printf(__('Scan document for %s, %s', true), $this->Session->read('Auth.User.lastname'), $this->Session->read('Auth.User.firstname')) ?></h1>
    <?php echo $this->Session->flash(); ?>
    <div id="idletimeout">
	<?php __('You are about to be logged out of the system in <span>1</span>&nbsp;seconds due to inactivity.') ?>
	<a href="#" id="idletimeout-resume"><?php __('Click here to continue using this page') ?></a>
    </div>
    <div id="scanForm" class="left">
	<h3><?php __('1. Place document in scanner.') ?></h3>
	<br />
	<h3>2. <input class="scan" name="scan"  type="button" value="Scan"/></h3>
	<br />
	<h3><?php __('3. Verify document.') ?></h3>
	<br />
	<h3><?php __('4. If necessary') ?> <input class="re-scan" name="rescan"  type="button" value="Re-Scan"/></h3>
	<?php
		echo $this->Form->create('QueuedDocument', array( 'action' => 'kiosk_scan_document',  'enctype' => 'multipart/form-data', 'name' => 'myForm'));

		echo $this->Form->input('location_id', array('type' => 'hidden'));
		echo $this->Form->input('User.id', array('type' => 'hidden'));
		echo '<br class="clear" />';
		echo $this->Form->end();
	    ?>
	<h3>4. <input class="save" name="save"  type="button" value="Save"/></h3>
	<br />
	<div class="top-mar-20"> 
		<?php if($referer != null) :?>
			<?php echo $this->Html->link(__('Go Back', true), $referer, array('class' => 'self-sign-kiosk-link')); ?>
		<?php endif ?> 		
	</div>
    </div>
    <div id="scanWrapper" class="left left-mar-20">
	<object 
		width="400"
		height="450"
		classid="CLSID:74F4F118-91E6-4AFC-B8D2-04066781F239"
		id="Eztwain"
		name="Eztwain"
		codebase="/files/scanning/eztwainx.cab"
		standby="Loading EZTwainX">
		<param name="Licensee" value="Complete Technology Solutions">
		<param name="LicenseKey" value="-840147303">
		<param name="AppTitle" value="<?php echo Configure::read('Company.name')?> Self Scan">
		<param name="BackColor" value="0xcc8844">
	</object>
    </div>
    <script type="text/vbscript">
	Dim Eztwain
	set Eztwain = document.getElementById("Eztwain")
	Sub save_OnClick
	    stopTimeout()
	    Eztwain.UploadExtraField "data[QueuedDocument][scanned_location_id]", <?php echo $locationId . "\r\n"?>
	    Eztwain.UploadExtraField "data[QueuedDocument][self_scan_cat_id]", <?php echo $selfScanCatId . "\r\n"?>
	    Eztwain.UploadExtraField "data[QueuedDocument][queue_category_id]", <?php echo $queueCatId . "\r\n"?>
	    Eztwain.UploadExtraField "data[QueuedDocument][user_id]", <?php echo $session->read('Auth.User.id') . "\r\n"?>
	    Eztwain.UploadAddCookie ("PHPSESSID=<?php echo $this->Session->id()?>")
	    if not Eztwain.UploadToURL("<?php echo $html->url('/', true)?>kiosk/kiosks/self_scan_document", "file.pdf", "data[QueuedDocument][submittedfile]") then
		newURL = "<?php echo $html->url('/', true)?>kiosk/kiosks/self_scan_document/<?php echo $selfScanCatId . '/' . $queueCatId . '/' ?>"
		location.href = newURL
	    else
		newURL = "<?php echo $html->url('/', true)?>kiosk/kiosks/self_scan_another_document/"
		location.href = newURL
	    end if
	End Sub
	Sub scan_OnClick
	stopTimeout()
	Eztwain.ScanWithUI = false
	    Eztwain.ScanType = 0
	    Eztwain.ScanDPI = 300
	    Eztwain.AcquireSingleImage
	    Eztwain.Deskew
	    disableScan()
	    enableReScan()	    
	End Sub
	Sub rescan_OnClick
	    stopTimeout()
	    Eztwain.ScanWithUI = false
	    Eztwain.ScanType = 0
	    Eztwain.ScanDPI = 300
	    Eztwain.AcquireSingleImage
	    Eztwain.Deskew
	End Sub
    </script>

</div>

<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<?php

$url_pieces = parse_url( Router::url('/', true) );
var_dump($url_pieces['host']);

?>
<?php echo $this->Html->script('jquery.validate', array('inline' => false)) ?>
<?php echo $this->Html->script('queued_documents/desktop_scan_document' ,array('inline' => false)) ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
<?php echo $crumb->getHtml(__('Desktop Scan Document', true), null, 'unique'); ?>
</div>
<div id="QueuedDocumentScanDoument">
    <div id="scanForm" class="left">
    <h3>1. <?php __('Place document(s) in scanner.') ?></h3>
	<h3>2. <input class="scan" name="scan"  type="button" value="Scan"/></h3>
    <h3>3. <?php __('Verify document. If necessary') ?> <input class="re-scan" name="rescan"  type="button" value="Re-Scan"/></h3>
    <h3>4. <?php __('Select Queue Category & Location') ?> </h3>
	<div class="formErrors"></div>
	<?php
		echo $this->Form->create('QueuedDocument', array( 'action' => 'desktop_scan_document',  'enctype' => 'multipart/form-data', 'name' => 'myForm'));
		echo $this->Form->input('queue_cat_id', array('type' => 'select', 'empty' => 'Select Category', 'class' => 'required'));
		echo $this->Form->input('location_id', array('type' => 'select', 'empty' => 'Select Location', 'class' => 'required'));
		echo '<br class="clear" />';
		echo $this->Form->end();
	    ?>
	<h3>5. <input class="save" name="save"  type="button" value="Save"/></h3>
    </div>
    <div id="scanWrapper" class="right">
	<object align="center"
		width="600"
		height="700"
		classid="CLSID:74F4F118-91E6-4AFC-B8D2-04066781F239"
		id="Eztwain"
		name="Eztwain"
		codebase="/files/scanning/eztwainx.cab"
		standby="Loading EZTwainX">
		<param name="Licensee" value="Complete Technology Solutions">
		<param name="LicenseKey" value="-840147303">
		<param name="AppTitle" value="<?php echo Configure::read('Company.name')?> Desktop Scanning">
		<param name="BackColor" value="0xcc8844">
	</object>
    </div>
    <script type="text/vbscript">
	Dim Eztwain
	set Eztwain = document.getElementById("Eztwain")
	Sub save_OnClick
	    if not isValid() Then exit Sub
	    Eztwain.UploadExtraField "data[QueuedDocument][queue_category_id]", getQueueCat()
	    Eztwain.UploadExtraField "data[QueuedDocument][scanned_location_id]", getLocationId()
	    Eztwain.UploadAddCookie ("PHPSESSID=<?php echo $this->Session->id()?>")
	    if not Eztwain.UploadToURL("http://<?= $url_pieces['host'] ?>/admin/queued_documents/desktop_scan_document", "file.pdf", "data[QueuedDocument][submittedfile]") then
		newURL = "<?php echo $html->url('/admin/queued_documents/desktop_scan_document', true)?>"
		location.href = newURL
	    else
		newURL = "<?php echo $html->url('/admin/queued_documents/desktop_scan_document/', true)?>"
		location.href = newURL
	    end if
	End Sub
	Sub scan_OnClick
	    Eztwain.ScanWithUI = false
	    Eztwain.ScanType = 0
	    Eztwain.ScanDPI = 300
	    Eztwain.AcquireMultipage()
	    Eztwain.Deskew
	    Eztwain.AutoContrast
	    disableScan()
	    enableReScan()
	End Sub
	Sub rescan_OnClick
	    Eztwain.ScanWithUI = false
	    Eztwain.ScanType = 0
	    Eztwain.ScanDPI = 300
	    Eztwain.AcquireMultipage()
	    Eztwain.Deskew
	    Eztwain.AutoContrast
	End Sub
    </script>

</div>

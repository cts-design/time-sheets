<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<?php echo $this->Html->script('jquery.validate', array('inline' => false)) ?>
<?php echo $this->Html->script('filed_documents/scan_document' ,array('inline' => false)) ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
<?php echo $crumb->getHtml(__('Scan Document', true), null, 'unique'); ?>
</div>
<div id="FiledDocumentScanDoument">
    <div id="scanForm" class="left">
    <h3>1. <?php __('Place document in scanner.') ?></h3>
	<h3>2. <input class="scan" name="scan"  type="button" value="Scan"/></h3>
    <h3>3. <?php __('Verify document. If necessary') ?> <input class="re-scan" name="rescan"  type="button" value="Re-Scan"/></h3>
    <h3>4. <?php __('Select Filing Categories') ?> </h3>
	<div class="formErrors"></div>
	<?php
		echo $this->Form->create('FiledDocument', array( 'action' => 'scan_document',  'enctype' => 'multipart/form-data', 'name' => 'myForm'));
		echo $this->Form->input('cat_1',
			array('type' => 'select', 'label' => false, 'empty' => 'Select Main Cat', 'options' => $cat1, 'class' => 'required'));
		echo $this->Form->input('cat_2', array('type' => 'select', 'label' => false, 'empty' => 'Select 2nd Cat'));
		echo $this->Form->input('cat_3', array('type' => 'select', 'label' => false, 'empty' => 'Select 3rd Cat'));
		echo $this->Form->input('description', array('label' => 'Other'));
		echo $this->Form->input('admin_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
		echo $this->Form->input('location_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.location_id')));
		echo $this->Form->input('User.id', array('type' => 'hidden'));
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
	    Eztwain.UploadExtraField "data[FiledDocument][cat_1]", getCat1()
	    Eztwain.UploadExtraField "data[FiledDocument][cat_2]", getCat2()
	    Eztwain.UploadExtraField "data[FiledDocument][cat_3]", getCat3()
	    Eztwain.UploadExtraField "data[FiledDocument][description]", getDescription()
	    Eztwain.UploadExtraField "data[FiledDocument][admin_id]", getAdminId()
	    Eztwain.UploadExtraField "data[FiledDocument][filed_location_id]", getLocationId()
	    Eztwain.UploadExtraField "data[User][id]", getUserId()
	    Eztwain.UploadAddCookie ("PHPSESSID=<?php echo $this->Session->id()?>")
	    if not Eztwain.UploadToURL("<?php echo $html->url('/admin/filed_documents/scan_document', true)?>", "file.pdf", "data[FiledDocument][submittedfile]") then
		newURL = "<?php echo $html->url('/admin/filed_documents/scan_document/', true)?>" & getUserId()
		location.href = newURL
	    else
		newURL = "<?php echo $html->url('/admin/filed_documents/index/', true)?>" & getUserId()
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

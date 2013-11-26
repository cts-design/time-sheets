<style>
#SelfScan
{
	overflow:hidden;
}
.actions
{
	float:left;
	width:auto;
	max-width:500px;
}
.preview
{
	float:right;
	width:400px;
	
	overflow:hidden;
}
.preview img
{
	float:right;
	
	width:400px;
	height:450px;
}
.btn-button
{
	padding:10px 30px;
	font-size:16pt;
	font-weight:bolder;
	border:1px solid #222;
	display:inline-block;
}
.btn-color-button
{
	background-color:#FFB522;
	color:#222;
}
.btn-color-button:hover
{
	color:#111;
	background-color:#F17024;
}
.btn-color-button:active
{
	background-color:#F18221;
}

.btn-off
{
	background-color:#FFDE9C;
	color:#AAA;
}
.btn-off:hover
{
	background-color:#FFDE9C;
	color:#AAA;
}

</style>
<div id="SelfScan" class="self-scan-wrapper">
	<h1>
		<?php printf(__('Scan Documents for %s, %s?', true), $this->Session->read('Auth.User.lastname'), $this->Session->read('Auth.User.firstname')) ?>
	</h1>
	<div class="actions">
		<p>Options</p>

		<p>1. <?php __('Place image in scanner then ') ?></p>
		<button name="scan" class="btn-button scan btn-color-button"><?php __('Scan') ?></button>
		
		<p>2. <?php __('If you need to rescan ') ?></p>
		<button name="rescan" class="btn-button rescan btn-off" disabled="disabled"><?php __('Re-Scan') ?></button>
		
		<p>3. <?php __('If you want to add more pages')?></p>
		<button name="add-page" class="btn-button add-page btn-off" disabled="disabled"><?php __('Add Page') ?></button>
		
		<p>4. <?php __('If you are all done ') ?></p>
		<button name="finish" class="btn-button finish btn-color-button"><?php __('Finish') ?></button>
		
		<p style="font-size:13pt"><?php __('Currently on page: ' . (count($partial_files) + 1)) ?></p>
	
	</div>
	
	<div class="preview">
		<object id="VSTwain1" width="1" height="1"
			classid="CLSID:1169E0CD-9E76-11D7-B1D8-FB63945DE96D"
			codebase="">
		</object>
		<img src="" name="previewImage" id="preview" width="350" height="270" alt="<?php __('Waiting on Scan') ?>" />
	</div>
</div>
<?= $this->Html->script('jquery') ?>
<script type="text/javascript">
$(document).ready(function(){
	$(".scan").click(function(){
		ScanImage();
	});
	$(".add-page").click(addPage);
	
	$(".rescan").click(rescan);
	
	$(".finish").click(finish);
	
	var previewImagePath = "";
	
	function finish()
	{
		//VSTwain1.StopDevice();
		
		
		$.ajax({
			url : "<?= $html->url('/kiosk/vstwain/merge_images', true) ?>",
			type : 'GET',
			dataType : 'json',
			data : { self_scan_cat_id : '<?= $self_scan_cat_id ?>', queue_cat_id : '<?= $queue_cat_id ?>', user_id : '<?= $user_id ?>' },
			success : function(response)
			{
				if(response.success)
					window.location.href = "<?= $html->url('/kiosk/kiosks/self_sign_service_selection', true) ?>";
				else
					alert(response.output);
			},
			error : function(response, error)
			{
				alert(error);
			}
		});
	}
	
	function addPage()
	{
		VSTwain1.StopDevice();
		window.location.href = "<?= $html->url('/kiosk/kiosks/session_document_save/' . $self_scan_cat_id . '/' . $queue_cat_id, true) ?>";
	}
});

var imagePoller = {};

$(window).unload(stopDevice);

function stopDevice()
{
	VSTwain1.StopDevice();
}

function getImagePreview()
{
	$.ajax({
		url : "<?= $html->url('/kiosk/vstwain/get_latest_preview', true) ?>",
		type : 'GET',
		dataType : 'json',
		data : { user_id : '<?= $user_id ?>' },
		success : function(image)
		{
			if(image.success)
			{
				var preview = document.getElementById('preview');
				
				preview.src = "<?= $html->url('/', true) ?>" + image.output.PartialDocument.web_location;
				
				imagePoller = window.clearInterval(imagePoller);
			}
		}
	});
}

function ScanImage()
{
	disableGui()
	VSTwain1.StartDevice();
	
	VSTwain1.MaxImages = 1;
	VSTwain1.AutoCleanBuffer = 1;

	VSTwain1.ShowUI = 0;

	VSTwain1.DisableAfterAcquire = 1;
		  
	VSTwain1.OpenDataSource();
	VSTwain1.PixelType = 0;   // 0-bw, 1-gray, 2-rgb (ActiveX does not allow to upload RGB images in evaluation version)
	VSTwain1.UnitOfMeasure = 0;
	VSTwain1.Resolution = 200;
	  
	VSTwain1.Acquire();
}

function rescan()
{
	disableGui();
	$.ajax({
		url : '<?= $html->url('/kiosk/vstwain/delete_last', true) ?>',
		type : 'GET',
		dataType : 'json',
		data : { user_id : '<?= $user_id ?>' },
		success : function(result)
		{	
			if(result.success)
			{
				ScanImage();
			}
			else
			{
				alert(result.output);
			}
		}
	});
}

function upload(callback)
{
	var url = "http://" + location.hostname + '/kiosk/vstwain/upload_image';
	VSTwain1.SetHttpServerParams(url, "", 5);

	if(VSTwain1.ErrorCode != 0)
	{
		alert(VSTwain1.ErrorString);
	}

	VSTwain1.SetHttpFormField('user_id', '<?= $user_id ?>');
	if( VSTwain1.SaveImageToHttp(0,'file','file.jpg') == 0 )
	{	
		alert(VSTwain1.ErrorString);
	}
	else
	{
		imagePoller = setInterval(callback, 3000);
	}
}

function enableGui()
{
	var rescan = $(".rescan");
	var addPage = $(".add-page");
	var finish = $(".finish");
	
	rescan.removeAttr('disabled');
	rescan.removeClass('btn-off');
	rescan.addClass('btn-color-button');
	
	addPage.removeAttr('disabled');
	addPage.removeClass('btn-off');
	addPage.addClass('btn-color-button');
	
	finish.removeAttr('disabled');
	finish.removeClass('btn-off');
	finish.addClass('btn-color-button');
}

function disableGui()
{
	var scan = $(".scan");
	var rescan = $(".rescan");
	var addPage = $(".add-page");
	var finish = $(".finish");
	
	scan.attr('disabled', 'disabled');
	scan.removeClass('btn-color-button');
	scan.addClass('btn-off');
	
	rescan.attr('disabled', 'disabled');
	rescan.removeClass('btn-color-button');
	rescan.addClass('btn-off');
	
	rescan.attr('disabled', 'disabled');
	addPage.removeClass('btn-color-button');
	addPage.addClass('btn-off');
	
	rescan.attr('disabled', 'disabled');
	finish.removeClass('btn-color-button');
	finish.addClass('btn-off');
}
</script>
<script language="Javascript" type="text/javascript" event="PostScan(flag)" for="VSTwain1">

		var rescan = $(".rescan");
		var addPage = $(".add-page");
		var finish = $(".finish");
		if(flag == 0)
		{
			upload( getImagePreview );
			rescan.removeAttr('disabled');
			rescan.removeClass('btn-off');
			rescan.addClass('btn-color-button');
			
			addPage.removeAttr('disabled');
			addPage.removeClass('btn-off');
			addPage.addClass('btn-color-button');
			
			finish.removeAttr('disabled');
			finish.removeClass('btn-off');
			finish.addClass('btn-color-button');
		}
</script>
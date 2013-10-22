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
	color:#222;
	
	background-color:#FFB522;
	border:1px solid #222;
	
	display:inline-block;
}
.btn-button:hover
{
	color:#111;
	background-color:#F17024;
}
.btn-button:active
{
	background-color:#F18221;
}
</style>
<div id="SelfScan" class="self-scan-wrapper">
	<h1>
		<?php printf(__('Scan Documents for %s, %s?', true), $this->Session->read('Auth.User.lastname'), $this->Session->read('Auth.User.firstname')) ?>
	</h1>
	<div class="actions">
		<p>Options</p>

		<p>1. <?php __('Place image in scanner then ') ?></p>
		<button name="scan" class="btn-button scan"><?php __('Scan') ?></button>
		
		<p>2. <?php __('If you need to rescan ') ?></p>
		<button name="rescan" class="btn-button rescan"><?php __('Re-Scan') ?></button>
		
		<p>3. <?php __('If you want to add more pages')?></p>
		<button name="add-page" class="btn-button add-page"><?php __('Add Page') ?></button>
		
		<p>4. <?php __('If you are all done ') ?></p>
		<button name="finish" class="btn-button finish"><?php __('Finish') ?></button>
	
	</div>
	
	<div class="preview">
		<object id="VSTwain1" width="1" height="1"
			classid="CLSID:1169E0CD-9E76-11D7-B1D8-FB63945DE96D"
			codebase="">
		</object>
		<img src="" name="previewImage" id="previewImage" width="350" height="270" alt="<?php __('Waiting on Scan') ?>" />
	</div>
</div>
<?= $this->Html->script('jquery') ?>
<script type="text/javascript">
$(document).ready(function(){
	$(".scan").click(function(){
		ScanImage();
	});
	$(".add-page").click(upload);
	
	//$(".finish").click(getLastImage);
	
	var previewImagePath = "";
	
	function ScanImage()
	{
		VSTwain1.StartDevice();
		
		//VSTwain1.IsLoggingEnabled = 1;
		//VSTwain1.LogFilePath = "c:\\vstwain.log";
		
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

	function applyChanges()
	{
	  var field = document.getElementById("previewImage");
	  field.src = "";
	  field.src = previewImagePath;
	  field.width = 400;
	  field.height = 450;
	}

	function upload()
	{
		var url = "<?= $html->url('/kiosk/kiosks/sessiondocumentsave', true) ?>";
		VSTwain1.SetHttpServerParams(url, "", 5);

		if(VSTwain1.ErrorCode != 0)
		{
			alert(VSTwain1.ErrorString);
		}

		if( VSTwain1.SaveImageToHttp(0,'file','file.jpg') == 0 )
		{
			alert(VSTwain1.ErrorString);
		}
		else
		{
			window.location.href = "<?= $html->url('/kiosk/kiosks/session_document_save') ?>";
		}
	}
	
	/*
	function getLastImage()
	{
		$.ajax({
			url : "<?= $html->url('/kiosk/kiosks/get_last_image', true) ?>",
			type : 'GET',
			dataType : 'json',
			success : function(data)
			{
				console.log(data);
			},
			error : function(data, error)
			{
				console.log(error);
				console.log(data);
			}
		});
	}
	*/
});
</script>
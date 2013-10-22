<center>
<object id="VSTwain1" width="1" height="1"
	  classid="CLSID:1169E0CD-9E76-11D7-B1D8-FB63945DE96D"
	  codebase="">
  </object>

<img src="" name="previewImage" id="previewImage" width="350" height="270" alt="Scanned image" />

</center>

<button class="scan">
Scan Image
</button>

<br />

<button class="upload">
	Upload
</button>

<?= $this->Html->script('jquery') ?>
<script type="text/javascript">
$(document).ready(function(){
	$(".scan").click(ScanImage);
	$(".upload").click(upload);
	function ScanImage()
	{
		VSTwain1.StartDevice();
		
		//VSTwain1.IsLoggingEnabled = 1
		//VSTwain1.LogFilePath = "d:\\vstwain.log"
		
		VSTwain1.MaxImages = 1;
		VSTwain1.AutoCleanBuffer = 1;

		VSTwain1.ShowUI = 0;

		VSTwain1.DisableAfterAcquire = 1;
			  
		VSTwain1.OpenDataSource();
		VSTwain1.PixelType = 1;   // 0-bw, 1-gray, 2-rgb (ActiveX does not allow to upload RGB images in evaluation version)
		VSTwain1.UnitOfMeasure = 0;
		VSTwain1.Resolution = 200;
		  
		VSTwain1.Acquire();
	}

	function applyChanges()
	{
	  var field = document.getElementById("previewImage");
	  field.src = "";
	  field.src = previewImagePath;
	  field.width = 350;
	  field.height = 270;
	}

	function upload()
	{
		var url = "http://" + location.hostname + '/tests/process_scan';
		VSTwain1.SetHttpServerParams(url, "", 5);

		if(VSTwain1.ErrorCode != 0)
		{
			alert(VSTwain1.ErrorString);
		}

		if( VSTwain1.SaveImageToHttp(0,'file','file.png') == 0 )
		{
			alert(VSTwain1.ErrorString);
		}
		else
		{
			
			window.location.href = "<?= $html->url('/tests/scan', true) ?>";
		}
	}
});
</script>
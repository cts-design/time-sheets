<html>
<head>
	<?= $this->Html->css('bootstrap_yeti.min') ?>
	<?= $this->Html->script('http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js') ?>
	<?= $this->Html->script('bootstrap.min') ?>

	<?= $this->Html->css('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/themes/south-street/jquery-ui.css') ?>
	<?= $this->Html->script('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js') ?>

	<link rel="stylesheet" href="/js/jqueryui_signature/jquery.signature.css" />

	<script type="text/javascript" src="/js/jqueryui_signature/jquery.signature.min.js"></script>

	<?= $this->Html->script('pdfobject_min.js') ?>

	<!--[if IE]> 
	<script type="text/javascript" src="js/excanvas.js"></script>
	<?= $this->Html->script('jqueryui_signature/excanvas') ?>
	<![endif]-->

	<style>
	html,body 
	{
		background-color:#1875BB;
	}
	#sig
	{
		width:400px;
		height:100px;
		content:"";
		float:right;
	}
	.content
	{
		background-color:#FFF;
		padding-top:10px;
		padding-bottom:10px;

		margin-bottom:10px;
	}
	</style>
</head>
<body>
	<div class="container centerize">
		<div class="row" style="margin-top:10px">
			<div class="col-lg-10 col-lg-offset-1 content">
				<div id="pdf" style="height:400px"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-5 col-lg-offset-1 content" style="height:320px">
				<h3 align="center">Signature Required</h3>

				<p class="desc">
					Please sign here to verify that you have read this document in full
				</p>

				<div id="sig"></div>

				<div class="btn-group pull-right">
					<button class="btn btn-primary btn-sm" name="done">Done</button>
					<button class="btn btn-info btn-sm" id="reset">Reset</button>
				</div>

			</div>
			<div class="col-lg-5 content" style="height:320px">
				<h3 align="center">Server-side Signature</h3>
				<p class="desc">
					Your saved signature will appear here
				</p>
				<img src="" id="post-save-image"/>
			</div>
		</div>

		<div class="row" style="display:none">
			<div class="col-lg-8 col-lg-offset-2 content">

				<p>This is the json generated from what I made</p>
				<?= $this->Form->create('', array('id' => 'sign', 'url' => '/test/esign_document')) ?>
				<textarea id="lines" name="lines" style="width:100%;height:200px" readonly></textarea>
				<?= $this->Form->end() ?>
			</div>
		</div>
	</div>
<script type="text/javascript">
$(document).ready(function(){
$("#sig").signature({syncField: '#lines'});

$("button[name=done]").click(function(){
	$.ajax({
		url : '/test/esign_document',
		type : 'POST',
		dataType : 'json',
		data : { lines : $("#lines").val() },
		success : function(response){
			if(response.success)
			{
				console.log(response.output);
				var image = '/' + response.output;
				$("#post-save-image").attr('src', image);
			}
		},
		error : function(response, error){
			console.log(response);
			console.log(error);
		}
	});
});

$("#reset").click(function(){
	$("#sig").signature('clear');
	$("#lines").val('');
});
});

$(window).load(function(){

	var params = {
		url : '/storage/tbwa_w_fields.pdf',
		pdfOpenParams : {
			navpanes : 0,
			view : 'fitW'
		}
	};

	var params2 = {
		url :'/storage/tbwa_w_fields.pdf',
		pdfOpenParams : {
			view : 'fitH'
		}
	};

	var pdf = new PDFObject(params).embed('pdf');
	var pdf2 = new PDFObject(params).embed('pdf2');

});
</script>
</body>
</html>
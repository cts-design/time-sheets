<div id="dialog">
	<p></p>
</div>
<script>
$('#dialog').attr('title', 'Successfully Uploaded');
$('#dialog').find('p').text('The document was uploaded successfully, what would you like to do?');
//Declare dialog
$('#dialog').dialog({
	modal: true,
	draggable : false,
	resizable : false,
	closeOnEscape: false,
	width : 500,
	height: 300,
	open: function(event, ui) { $(".ui-dialog-titlebar-close").hide(); },
	buttons : {
		"Upload another document" : function() {
			window.location.href = "/program_responses/upload_docs/" + '<?= $this->params['pass'][0] . '/' . $this->params['pass'][1] ?>';
		},
		"Finish" : function() {
			window.location.href = "/users/dashboard";
		}
	}
});
</script>
Ext.onReady(function(){
	Ext.QuickTips.init();
	var editor = new Ext.form.HtmlEditor({
		width: 600,
		height: 600
	});
	console.log(editor);
	editor.applyToMarkup('ProgramInstructionText');
});	
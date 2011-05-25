Ext.onReady(function(){
	Ext.QuickTips.init();
	
	var editForm = new Ext.form.FormPanel({
		renderTo: 'editForm',
		title: 'Edit Program Instructions',
		height: 300,
		width: 515,
		layout: 'fit',
		frame: true,
		items: [{
			id: 'htmlEditor',
			name: 'data[ProgramInstruction][text]',
			xtype: 'htmleditor'
		}],
		buttons: [{
			text: 'Submit',
			handler: function() {
				var form = editForm.getForm();
				var vals = form.getFieldValues();
				form.submit({
					url: window.location,
					success: function(form, action){
						Ext.Msg.alert('Success', action.result.message);
					},
					failure: function(form, action) {
						Ext.Msg.alert('Failure', action.result.message);
					}
				});
			}
		}]
	});
});	
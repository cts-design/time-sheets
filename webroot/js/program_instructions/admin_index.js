/**
 * @author dnolan
 */
Ext.onReady(function(){  
	var instructionId = null;
	
	Ext.define('ProgramInstruction', {
		extend: 'Ext.data.Model',
		fields: ['id', 'program_id', 'name', 'type', 'actions', 'text']
	});
	
	var instructionStore = Ext.create('Ext.data.Store', {
		model: 'ProgramInstruction',
		proxy: {
			api:{
				update: '/admin/program_instructions/edit/'
			},
			type: 'ajax',
			url: '/admin/program_instructions/index/' + programId,
			reader: {
				type: 'json',
				root: 'instructions'
			},
			writer: {
				encode: true,
				root: 'data[ProgramInstruction]',
				writeAllFields: false
			}	
		},
		autoLoad: true,
		autoDestroy: true	
	});
	
	var instructionGrid = Ext.create('Ext.grid.Panel', {
		store: instructionStore,
		height: 210,
		forceFit: true,
		frame: true,
		collapsible: true,
		title: 'Program Instructions',
		region: 'north',
		columns: [{
			text: 'Name',
			dataIndex: 'name',
			width: 250,
			hideable: false,
			sortable: false,
			menuDisabled: true
		}],
		selType: 'rowmodel'
	});
	
	if(Ext.isIE) {
		var editor = Ext.create('Ext.form.TextArea', {
			width: 600,
			region: 'center',
			value: 'Please select a row in the grid above to edit instructions.'	
		});		
	}
	else {
		var editor = Ext.create('Ext.form.HtmlEditor', {
			width: 600,
			region: 'center',
			value: 'Please select a row in the grid above to edit instructions.'	
		});	
	}	
	
	var instructionPanel = Ext.create('Ext.panel.Panel', {
		frame: true,
		renderTo: 'instructions',
		width: 600,
		height: 600,
		layout: 'border',
		items: [
			instructionGrid,
			editor		
		],
		fbar: [{	
			text: 'Save',
			disabled: true,
			id: 'save',
			icon:  '/img/icons/save.png',
			handler: function() {
				Ext.Msg.wait('Please wait', 'Status');
				var record = instructionGrid.getSelectionModel().getLastSelected();
				record.set('text', editor.getValue());
				if(record.dirty) {
					instructionStore.sync();	
				}
				else {
					Ext.Msg.alert('Failure', 'Data has not changed.');
				}						
				instructionStore.on('write', function(store, operation, eOpts){
					var obj = Ext.JSON.decode(operation.response.responseText);
		        	if(obj.success) { 	
						Ext.Msg.alert('Success', obj.message);					        		
		        	}
		        	else {
		        		Ext.Msg.alert('Failure', obj.message);
		        	}					
				});		
			}
		}]
	});
	
	instructionGrid.getSelectionModel().on('select', function(rm, record, index, eOpts) {
		if(!record.data.text) {
			record.data.text = ''
		}
		instructionId = record.data.id;
		editor.setValue(record.data.text);
		Ext.getCmp('save').enable();
	});
});
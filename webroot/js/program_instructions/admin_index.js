/**
 * @author dnolan
 */

var instructionId = null;

var instructionStore = new Ext.data.JsonStore({
	url: '/admin/program_instructions/index/' + programId,
	autoLoad: true,
	autoDestroy: true,
	root: 'instructions',
	idProperty: 'id',
	fields: ['id', 'program_id', 'name', 'type', 'actions', 'text']
});

var instructionGrid = new Ext.grid.GridPanel({
	store: instructionStore,
	height: 210,
	frame: true,
	collapsible: true,
	title: 'Program Instructions',
	region: 'north',
	columns: [{
		header: 'Name',
		dataIndex: 'name',
		width: 250,
		hideable: false,
		sortable: false,
		menuDisabled: true
	}],
	viewConfig: {
		forceFit: true,
		scrollOffset: 0
	},
	sm: new Ext.grid.RowSelectionModel({singleSelect: true})
});

var editor = new Ext.form.HtmlEditor({
	width: 600,
	region: 'center',
	bodyStyle: {
		background: '#ffffff',
		padding: '7px'
	},
	html: 'Please select a row to see instruction text.'		
});

var instructionPanel = new Ext.Panel({
	frame: true,
	width: 600,
	height: 600,
	layout: 'border',
	items: [
		instructionGrid,
		editor		
	],
	fbar: [{	
		text: 'Save',
		handler: function() {
			Ext.Ajax.request({
			   url: '/admin/program_instructions/edit/' + instructionId,
		        success: function(response, opts){			        	
		        	var obj = Ext.decode(response.responseText);
		        	if(obj.success) {
		        		instructionStore.load();   	
						Ext.Msg.alert('Success', obj.message);					        		
		        	}
		        	else {
		        		opts.failure();
		        	}		            
		        },
		        failure: function(response, opts){
		        	var obj = Ext.decode(response.responseText);
		            Ext.Msg.alert('Error', obj.message);
		        },
			   params: { 
				'data[ProgramInstruction][id]': instructionId, 
				'data[ProgramInstruction][text]': editor.getValue()
			   }
			});			
		}
	}]
});

instructionGrid.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	if(!r.data.text) {
		r.data.text = ''
	}
	instructionId = r.data.id;
	editor.setValue(r.data.text);
});
Ext.onReady(function(){  	
	instructionPanel.render('instructions');
});
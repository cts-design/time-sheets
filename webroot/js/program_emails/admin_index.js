/**
 * @author dnolan
 */

var emailId, rowIndex = null;

var contextMenu = new Ext.menu.Menu({
  items: [{
    text: 'Enable',
    id: 'cmEnable',
    icon:  '/img/icons/add.png',
    handler: function() {
    	var record = emailGrid.store.getAt(rowIndex);
    	toggleDisabled(record.data.id, 0);	
    }
  },{
    text: 'Disable',
    id: 'cmDisable',
    icon:  '/img/icons/delete.png',
    handler: function() {
    	var record = emailGrid.store.getAt(rowIndex);
    	toggleDisabled(record.data.id, 1);	
    }  	
  }]
});

var emailStore = new Ext.data.JsonStore({
	url: '/admin/program_emails/index/' + programId,
	autoLoad: true,
	autoDestroy: true,
	root: 'emails',
	idProperty: 'id',
	fields: [
		'id',
		'program_id',
		'cat_id',
		'to',
		'from',
		'subject',
		'body',
		'type',
		'name',
		'disabled',
		'created',
		'modified'
	]
});

var emailGrid = new Ext.grid.GridPanel({
	store: emailStore,
	height: 210,
	frame: true,
	collapsible: false,
	title: 'Program Emails',
	region: 'north',
	columns: [{
		header: 'Name',
		dataIndex: 'name',
		width: 250,
		hideable: false,
		sortable: false,
		menuDisabled: true
	},{
		header: 'Disabled',
		dataIndex: 'disabled',
		width: 50,
		hideable: false,
		sortable: false,
		menuDisabled: true		
	}],
	viewConfig: {
		forceFit: true,
		scrollOffset: 15
	},
	sm: new Ext.grid.RowSelectionModel({singleSelect: true}),
	listeners: {
		cellcontextmenu: function(grid, index, columnIndex, event) {
			event.stopEvent();
	    	var record = this.store.getAt(index);
	    	switch(record.data.disabled) {
	    		case 'Yes': {
	    			Ext.getCmp('cmEnable').show();
	    			Ext.getCmp('cmDisable').hide();
	    			break;
	    		}
	    		case 'No': {
	    			Ext.getCmp('cmEnable').hide();
	    			Ext.getCmp('cmDisable').show();
	    			break;
	    		}
	    	}		
     		contextMenu.showAt(event.xy);
     		rowIndex = index;
		}		
	}
});

var editor = new Ext.form.HtmlEditor({
	width: 600,
	minSize: 300,
	maxSize: 500,
	height: 300,
	region: 'south',
	bodyStyle: {
		background: '#ffffff',
		padding: '7px'
	},
	html: 'Please select a row to see email body.'		
});

var formPanel = new Ext.FormPanel({
	frame: true,
	labelWidth: 50,
	layout: 'form',
	defaultType: 'textfield',
	region: 'center',
	items: [{
		fieldLabel: 'Subject',
		name: 'subject',
		value: 'Please select a row to see email subject.',
		id: 'subject',
		width: 550
	}]
});

var emailPanel = new Ext.Panel({
	frame: true,
	width: 600,
	height: 600,
	defaults: {
		split: true
	},
	layout: 'border',
	items: [
		emailGrid,
		formPanel,
		editor		
	],
	fbar: [{	
		text: 'Save',
		disabled: true,
		icon: '/img/icons/save.png',
		id: 'save',
		handler: function() {
			Ext.Msg.wait('Please wait', 'Status');
			Ext.Ajax.request({
			   url: '/admin/program_emails/edit/' + emailId,
		        success: function(response, opts){			        	
		        	var obj = Ext.decode(response.responseText);
		        	if(obj.success) {
		        		emailStore.load();   	
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
				'data[ProgramEmail][id]': emailId, 
				'data[ProgramEmail][body]': editor.getValue(),
				'data[ProgramEmail][subject]': Ext.getCmp('subject').getValue()
			   }
			});			
		}
	}]
});

emailGrid.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	if(!r.data.body) {
		r.data.body = '';
	}
	if(!r.data.subject) {
		r.data.subject = '';
	}
	
	emailId = r.data.id;
	editor.setValue(r.data.body);
	Ext.getCmp('subject').setValue(r.data.subject);
	Ext.getCmp('save').enable();
});

function toggleDisabled(id, disabled) {
    Ext.Ajax.request({
        url: '/admin/program_emails/toggle_disabled/' + id + '/' + disabled,
        success: function(response, opts){  
        	var obj = Ext.decode(response.responseText);
        	if(obj.success) {
        		emailStore.load();   	
				Ext.Msg.alert('Success', obj.message);					        		
        	}
        	else {
        		opts.failure();
        	}
        },
        failure: function(response){
            Ext.Msg.alert('Error', 'An error has occured, please try again.');
        }
    });	
}

Ext.onReady(function(){  	
	emailPanel.render('emails');
});
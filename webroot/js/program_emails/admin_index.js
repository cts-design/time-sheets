/**
 * @author dnolan
 */

Ext.onReady(function(){ 
	
	var emailId, rowIndex = null;
	
	Ext.QuickTips.init();

	var contextMenu = Ext.create('Ext.menu.Menu', {
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
		
	Ext.define('ProgramEmail', {
		extend: 'Ext.data.Model',
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
	
	var emailStore = Ext.create('Ext.data.Store', {
		model: 'ProgramEmail',
		proxy: {
			type: 'ajax',
			url: '/admin/program_emails/index/' + programId,
			reader: {
				type: 'json',
				root: 'emails'
			}
		},	
		autoLoad: true,
		autoDestroy: true
	});
	
	var emailGrid = Ext.create('Ext.grid.Panel', {
		store: emailStore,
		forceFit: true,
		height: 210,
		frame: true,
		collapsible: false,
		title: 'Program Emails',
		region: 'north',
		columns: [{
			text: 'Name',
			dataIndex: 'name',
			width: 250,
			hideable: false,
			sortable: false,
			menuDisabled: true
		},{
			text: 'Disabled',
			dataIndex: 'disabled',
			width: 50,
			hideable: false,
			sortable: false,
			menuDisabled: true		
		}],
		selType: 'rowmodel',
		listeners: {
			itemcontextmenu: function(gridView, record, item, index, e, eOpts) {
	            e.stopEvent();  
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
	     		contextMenu.showAt(e.getXY());
	     		rowIndex = index;
			}		
		}
	});
	

		var editor = Ext.create('Ext.form.HtmlEditor', {
			region: 'south',
			height: 300,
			frame: true,
			value: 'Please select a row to see the email body.'	
		});		

		
	var formPanel = Ext.create('Ext.form.Panel', {
		frame: true,
		fieldDefaults: {
			labelWidth: 50	
		},
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
	
	var emailPanel = Ext.create('Ext.panel.Panel', {
		frame: true,
		width: 600,
		height: 600,
		renderTo: 'emails',
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
				// TODO Make this save through the store.
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
	
	emailGrid.getSelectionModel().on('select', function(rm, record, index, eOpts) {
		if(!record.data.body) {
			record.data.body = '';
		}
		if(!record.data.subject) {
			record.data.subject = '';
		}	
		emailId = record.data.id;
		editor.setValue(record.data.body);
		Ext.getCmp('subject').setValue(record.data.subject);
		Ext.getCmp('save').enable();
	});
	
	// TODO Make this update through the store;
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
});
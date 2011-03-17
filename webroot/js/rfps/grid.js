/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
var alphaSpace = /^[-_0-9a-zA-Z ]+$/i;
Ext.apply(Ext.form.VTypes, {
	alphaspace: function(val, field) {
		return alphaSpace.test(val);
	},
	alphaspaceText: 'This field should only contain letters, numbers, spaces, or -_',
	alphaspaceMask: alphaSpace	
});

var rfpForm = new Ext.form.FormPanel({
	fileUpload: true,
    frame:true,
    labelWidth: 75,
    height: 300,
    title: 'Add RFP',
    id: 'rfpform',
    collapsible: true,
    items: [{
    	layout: 'column',
    	items: [{
    		columnWidth: 0.5,
    		layout: 'form',
    		items: [{
    			xtype: 'textfield',
	          	fieldLabel: 'Title',
	          	name: 'title',
	        	allowBlank: false,
	        	width: 200,
	        	anchor: '95%',
	        	vtype: 'alphaspace'
    		},{
    			xtype: 'textfield',
	            fieldLabel: 'Byline',
	            name: 'byline',
	            allowBlank: false,
	            width: 200,
	            anchor: '95%',
	            vtype: 'alphaspace'
    		},{
    			xtype: 'textfield',
    			fieldLabel: 'Contact Email',
    			name: 'contact_email',
    			allowBlank: false,
    			width: 200,
    			anchor: '95%',
    			vtype: 'email'
    		}]
	    },{
	    	columnWidth: 0.5,
	    	layout: 'form',
	    	items: [{
	        	fieldLabel: 'Deadline',
	        	name: 'deadline',
	        	xtype: 'datefield',
	        	width: 200,
	        	anchor: '98%'
	    	},{
	        	fieldLabel: 'Expires',
	        	name: 'expires',
	        	xtype: 'datefield',
	        	width: 200,
	        	anchor: '98%'	    		
	    	},{
	    		fieldLabel: 'File',
	    		name: 'file',
	    		xtype: 'fileuploadfield',
	    		emptyText: 'Please select a document to upload',
	    		width: 200,
	    		anchor: '98%'
	    	}]
        }]
     },{
     	xtype: 'htmleditor',
		enableFont: false,
		allowBlank: false,
		fieldLabel: 'Description',
		name: 'description',
		width: 300,
		anchor: '99%'
    }],
    buttons: [{
        text: 'Save',
        id: 'saveButton',
        handler: function(){
			var f = rfpForm.getForm();
			if (f.isValid()) {
				var vals = f.getValues();
				f.submit({
					url: '/admin/rfps/upload',
					waitMsg: 'uploading',
					success: function(form,action) {

						f.reset();
						var Rfp = new grid.store.recordType({
						 	title: vals.title,
						 	byline: vals.byline,
						 	description: vals.description,
						 	deadline: vals.deadline,
						 	expires: vals.expires,
						 	contact_email: vals.contact_email,
						 	file: action.result.url
						});
						store.add(Rfp);

					},
					failure: function(form, action) {
						Ext.msg.Alert('Error', 'Your file could not be uploaded, please try again.');
					}
				});
			}
        }
    },{
    	text: 'Update',
    	id: 'updateButton',
    	disabled: true,
    	handler: function() {
    		var f = rfpForm.getForm();
    		if (f.isValid()) {
    			rec = grid.getSelectionModel().getSelected();
    			f.updateRecord(rec);
    		}
    	}
    },{
    	text: 'Cancel',
    	id: 'cancelButton',
    	handler: function() {
    		Ext.getCmp('updateButton').disable();
     		Ext.getCmp('saveButton').enable();
    		rfpForm.getForm().reset();
    	}
    }]
});

var proxy = new Ext.data.HttpProxy({
	api: {
		create:  { url: '/admin/rfps/create',  method: 'POST' },
		read:    { url: '/admin/rfps/read',    method: 'GET'  },
		update:  { url: '/admin/rfps/update',  method: 'POST' },
		destroy: { url: '/admin/rfps/destroy', method: 'POST' }
	},
	listeners: {
		write: function() {
			rfpForm.getForm().reset();
    		Ext.getCmp('updateButton').disable();
     		Ext.getCmp('saveButton').enable();
		}
	}
});

var fields = Ext.data.Record.create([
	{ name: 'id' },
	{ name: 'title' },
	{ name: 'byline' },
	{ name: 'description' },
	{ name: 'deadline' },
	{ name: 'expires' },
	{ name: 'contact_email' },
	{ name: 'file' }	
]);

var reader = new Ext.data.JsonReader({
    totalProperty: 'total',
    successProperty: 'success',
    idProperty: 'id',
    root: 'rfps',
    messageProperty: 'message'
}, fields);

var writer = new Ext.data.JsonWriter({
	encoded: true,
	writeAllFields: false
});

var store = new Ext.data.Store({
	storeId: 'store',
	proxy: proxy,
	reader: reader,
	writer: writer,
	autoSave: true
});

store.load();

var gridView = new Ext.grid.GridView({
	forceFit: true	
});

var colModel = new Ext.grid.ColumnModel([
	{ header: 'id', width: 50, dataIndex: 'id' },
	{ header: 'Title', width: 180, sortable: true, dataIndex: 'title' },
	{ header: 'Byline', width: 180, sortable: true, dataIndex: 'byline' },
	{ header: 'Description', width: 200, sortable: true, dataIndex: 'description' },
	{ header: 'Deadline', width: 125, sortable: true, dataIndex: 'deadline' },
	{ header: 'Expires', width: 125, sortable: true, dataIndex: 'expires' },
	{ header: 'Contact Email', width: 200, sortable: true, dataIndex: 'contact_email' },
	{ header: 'File', width: 100, sortable: true, dataIndex: 'file' },
	{
		header: 'Actions',
		xtype: 'actioncolumn',
		width: 60,
		items: [{
			icon: '/img/icons/delete.png',
			tooltip: 'Remove RFP',
			handler: function(grid, rowIndex, colIndex) {
				var rec = store.getAt(rowIndex);
				Ext.Msg.show({
					title: 'Remove RFP?',
					msg: 'Are you sure you want to remove the RFP?',
					buttons: {
						yes: 'Yes, remove the RFP',
						no: 'No, don\'t remove the RFP'
					},
					animEl: 'elId',
					icon: Ext.MessageBox.QUESTION,
					fn: function(btn) {
						if (btn == 'yes') {
							store.remove(rec);
						}
					}
				});
			}
		}]
	}
]);

var selModel = new Ext.grid.RowSelectionModel({
     singleSelect : true,
     listeners: {
     	rowselect: function(sm, row, rec) {
     		Ext.getCmp('updateButton').enable();
     		Ext.getCmp('saveButton').disable();
     		var f = rfpForm.getForm();
     		f.loadRecord(rec);
     	}
     }
});

var grid = new Ext.grid.GridPanel({
	store: store,
	colModel: colModel,
	sm: selModel,
	view: gridView,
	height: 300,
	frame: true
});

Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = '/img/ext/default/s.gif';
	Ext.QuickTips.init();
	
	rfpForm.render('form-div');
	grid.render('panel-div');
});
/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
var selectedRecord;

var alphaSpace = /^[-_0-9a-zA-Z ]+$/i;
Ext.apply(Ext.form.VTypes, {
	alphaspace: function(val, field) {
		return alphaSpace.test(val);
	},
	alphaspaceText: 'This field should only contain letters, numbers, spaces, or -_',
	alphaspaceMask: alphaSpace	
});

var rfpForm = Ext.create('Ext.form.Panel', {
    frame:true,
		fieldDefaults: {
			labelWidth: 75
		},
    height: 300,
    title: 'Add RFP',
    id: 'rfpform',
    collapsible: true,
    items: [{
    	layout: 'column',
			xtype: 'container',
    	items: [{
    		columnWidth: 0.5,
    		layout: 'anchor',
				xtype: 'container',
    		items: [{
    			xtype: 'textfield',
	        fieldLabel: 'Title',
	        name: 'title',
        	allowBlank: false,
        	width: 200,
        	anchor: '95%'
        	//vtype: 'alphaspace'
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
	    	layout: 'anchor',
				xtype: 'container',
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
	    		xtype: 'filefield',
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
					waitMsg: 'Uploading',
					success: function(form,action) {
						f.reset();						
						Ext.data.StoreManager.lookup('rfpStore').add({
						 	title: vals.title,
						 	byline: vals.byline,
						 	description: vals.description,
						 	deadline: vals.deadline,
						 	expires: vals.expires,
						 	contact_email: vals.contact_email,
						 	file: action.result.url
						});

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
    	hidden: true,
    	handler: function() {
    		var f = this.up('form').getForm();
    		if (f.isValid()) {
					rec = grid.getSelectionModel().selected.items[0];
    			// rec = grid.getSelectionModel().getSelected();
    			rec.beginEdit();
					rec.set(f.getValues());
					rec.endEdit();
					f.reset();
					Ext.getCmp('updateButton').hide();
					Ext.getCmp('saveButton')
    		}
    	}
    },{
    	text: 'Cancel',
    	id: 'cancelButton',
    	handler: function() {
    		Ext.getCmp('updateButton').hide();
     		Ext.getCmp('saveButton').show();
    		this.up('form').getForm().reset();
    	}
    }]
});

Ext.define('Rfp', {
	extend: 'Ext.data.Model',
	fields: [
		{ name: 'id', type: 'int' },
		'title',
		'byline',
		'description',
		'deadline',
		'expires',
		'contact_email',
		'file'
	]
});

Ext.create('Ext.data.Store', {
	model: 'Rfp',
	id: 'rfpStore',
	proxy: {
		type: 'ajax',
		reader: {
			type: 'json',
			root: 'rfps'
		},
		writer: {
			type: 'json',
			root: 'rfps',
			encode: true,
			writeAllFields: true
		},
		api: {
			create:  '/admin/rfps/create',
			read: 	 '/admin/rfps/read',
			update:  '/admin/rfps/update',
			destroy: '/admin/rfps/destroy'
		}
	},
	autoLoad: true,
	autoSync: true,
	listeners: {
		datachanged: function (store, opts) {
			Ext.getCmp('updateButton').hide();
			Ext.getCmp('saveButton').show();
			rfpForm.getForm().reset();
		}
	}
});

var toolbar = Ext.create('Ext.Toolbar', {
	items: [{
		id: 'deleteButton',
		text: 'Delete',
		icon: '/img/icons/delete.png',
		disabled: true,
		handler: function () {
				var store = Ext.data.StoreManager.lookup('rfpStore'),
					rec = store.getAt(selectedRecord);

				Ext.Msg.show({
					title: 'Remove RFP?',
					msg: 'Are you sure you want to remove the RFP?',
					buttons: Ext.Msg.YESNOCANCEL,
					animEl: 'elId',
					icon: Ext.MessageBox.QUESTION,
					fn: function(btn) {
						if (btn == 'yes') {
							store.remove(rec);
							this.disable();
							rfpForm.getForm().reset();
						}
					},
					scope: this
				});
		}
	}]
});

var grid = new Ext.grid.GridPanel({
	tbar: toolbar,
	store: Ext.data.StoreManager.lookup('rfpStore'),
	columns: [
		{ text: 'id', width: 50, dataIndex: 'id', hidden: true },
		{ text: 'Title', width: 100, sortable: true, dataIndex: 'title' },
		{ text: 'Byline', width: 100, sortable: true, dataIndex: 'byline' },
		{ 
			text: 'Description', 
			width: 100, 
			sortable: true, 
			dataIndex: 'description',
			flex: 1
		},
		{ text: 'Deadline', width: 70, sortable: true, dataIndex: 'deadline' },
		{ text: 'Expires', width: 70, sortable: true, dataIndex: 'expires' },
		{ text: 'Contact Email', width: 200, sortable: true, dataIndex: 'contact_email' },
		{ 
			text: 'File', 
			width: 40, 
			sortable: true, 
			dataIndex: 'file',
			renderer: function (value) {
        if (value) {
          return Ext.String.format('<a href="http://{0}/{1}" target="_blank"><img src="/img/icons/file.png" /></a>', window.location.host, value);
        } else {
          return '';
        }
			}	
		}
	],
	height: 300,
	frame: true,
	listeners: {
		itemclick: {
			fn: function (view, rec, item, index, e, opts) {
				var form = rfpForm,
					deleteButton = Ext.getCmp('deleteButton');
				
				Ext.getCmp('saveButton').hide();
				Ext.getCmp('updateButton').show();
				selectedRecord = index;
				form.loadRecord(rec);
				deleteButton.enable()
			}
		}
	}
});

Ext.onReady(function() {

	Ext.BLANK_IMAGE_URL = '/img/ext/default/s.gif';
	Ext.QuickTips.init();

	rfpForm.render('form-div');
	grid.render('panel-div');
});

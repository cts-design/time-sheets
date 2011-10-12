/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

Ext.Compat.showErrors = true;

var selectedRecord;

var alphaSpace = /^[-_0-9a-zA-Z ]+$/i;
Ext.apply(Ext.form.VTypes, {
	alphaspace: function(val, field) {
		return alphaSpace.test(val);
	},
	alphaspaceText: 'This field should only contain letters, numbers, spaces, or -_',
	alphaspaceMask: alphaSpace	
});

var hotjobform = Ext.create('Ext.form.Panel', {
    frame:true,
		fieldDefaults: {
			labelWidth: 75
		},
    height: 350,
    title: 'Add Hot Job',
    id: 'hotjobform',
    collapsible: true,
    items: [{
    	layout: 'column',
			xtype: 'container',
    	items: [{
    		columnWidth: 0.5,
    		layout: 'anchor',
				xtype: 'container',
    		items: [{
					id: 'employerField',
					xtype: 'textfield',
					fieldLabel: 'Employer',
					name: 'employer',
					allowBlank: false,
					width: 200,
					anchor: '95%',
					vtype: 'alphaspace'
				}, {
					xtype: 'checkbox',
					id: 'notSpecified',
					labelSeparator: '',
					hideLabel: true,
					boxLabel: '&nbsp;Not Specified',
					fieldLabel: '&nbsp;Not Specified',
					margin: {
						bottom: 10,
						left: 80
					},
					listeners: {
						change: function (cb, newValue, oldValue) {
							var employer = Ext.getCmp('employerField');
							
							if (newValue) {
								employer.setValue('Not Specified');
							} else {
								employer.setValue('').clearInvalid();
							}
						}
					}
				}, {
    			xtype: 'textfield',
	        fieldLabel: 'Title',
	        name: 'title',
        	allowBlank: false,
        	width: 200,
        	anchor: '95%'
    		},{
    			xtype: 'textfield',
    			fieldLabel: 'Contact',
    			name: 'contact',
    			allowBlank: false,
    			width: 200,
    			anchor: '95%'
    		}]
	    },{
	    	columnWidth: 0.5,
	    	layout: 'anchor',
				xtype: 'container',
	    	items: [{
					xtype: 'textfield',
					fieldLabel: 'Location',
					name: 'location',
					allowBlank: false,
					width: 200,
					anchor: '98%',
					vtype: 'alphaspace'
				}, {
					fieldLabel: 'Url',
        	name: 'url',
        	xtype: 'textfield',
        	width: 200,
        	anchor: '98%'
				}, {
					xtype: 'textfield',
	        	fieldLabel: 'Reference #',
	        	name: 'reference_number',
	        	width: 200,
	        	anchor: '98%',
						vtype: 'alphaspace'
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
		height: 150,
		anchor: '99%'
    }],
    buttons: [{
        text: 'Save',
        id: 'saveButton',
        handler: function(){
			var f = hotjobform.getForm();
			if (f.isValid()) {
				var vals = f.getValues();
				f.submit({
					url: '/admin/hot_jobs/upload',
					waitMsg: 'uploading',
					success: function(form,action) {
						f.reset();						
						Ext.data.StoreManager.lookup('hotJobStore').add({
						 	employer: vals.employer,
						 	title: vals.title,
						 	description: vals.description,
						 	contact: vals.contact,
						 	location: vals.location,
						 	contact: vals.contact,
							reference_number: vals.reference_number,
							url: vals.url,
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
					this.up('form').getForm().reset();
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
    }],
		listeners: {
			
		}
});

Ext.define('HotJob', {
	extend: 'Ext.data.Model',
	fields: [
		{ name: 'id', type: 'int' },
		'employer',
		'title',
		'contact',
		'description',
		'location',
		'url',
		'reference_number',
		'file'
	]
});

Ext.create('Ext.data.Store', {
	model: 'HotJob',
	id: 'hotJobStore',
	proxy: {
		type: 'ajax',
		reader: {
			type: 'json',
			root: 'hot_jobs'
		},
		writer: {
			type: 'json',
			root: 'hot_jobs',
			encode: true,
			writeAllFields: true
		},
		api: {
			create:  '/admin/hot_jobs/create',
			read: 	 '/admin/hot_jobs/read',
			update:  '/admin/hot_jobs/update',
			destroy: '/admin/hot_jobs/destroy'
		}
	},
	autoLoad: true,
	autoSync: true,
	listeners: {
		datachanged: function (store, opts) {
			Ext.getCmp('updateButton').hide();
			Ext.getCmp('saveButton').show();
			hotjobform.getForm().reset();
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
				var store = Ext.data.StoreManager.lookup('hotJobStore'),
					rec = store.getAt(selectedRecord);

				Ext.Msg.show({
					title: 'Remove Hot Job?',
					msg: 'Are you sure you want to remove the Hot Job?',
					buttons: Ext.Msg.YESNOCANCEL,
					animEl: 'elId',
					icon: Ext.MessageBox.QUESTION,
					fn: function(btn) {
						if (btn == 'yes') {
							store.remove(rec);
							this.disable();
							hotjobform.getForm().reset();
						}
					},
					scope: this
				});
		}
	}]
});

var grid = new Ext.grid.GridPanel({
	tbar: toolbar,
	store: Ext.data.StoreManager.lookup('hotJobStore'),
	columns: [
		{ text: 'id', width: 50, dataIndex: 'id', hidden: true },
		{ text: 'Employer', width: 100, sortable: true, dataIndex: 'employer' },
		{ text: 'Title', width: 100, sortable: true, dataIndex: 'title' },
		{ 
			text: 'Description', 
			width: 100, 
			sortable: true, 
			dataIndex: 'description',
			flex: 1
		},
		{ text: 'Location', width: 70, sortable: true, dataIndex: 'location' },
		{ 
			text: 'Url', 
			width: 70, 
			sortable: true, 
			dataIndex: 'url',
			renderer: function (value) {
				var url;
				
				if (value) {
					if (value.substring(0,4) == 'http') {
						url = value;
					} else {
						url = 'http://' + value;
					}
					
					return Ext.String.format('<a href="{0}" target="_blank">{1}</a>', url, value);
				} else {
					return '';
				}
			}
		},
		{ text: 'Contact', width: 200, sortable: true, dataIndex: 'contact' },
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
				var form = hotjobform,
					deleteButton = Ext.getCmp('deleteButton');
				
				Ext.getCmp('saveButton').hide();
				Ext.getCmp('updateButton').show();
				selectedRecord = index;
				form.loadRecord(rec);
				if (rec.data.employer === 'Not Specified') {
					Ext.getCmp('notSpecified').setValue(true);
				} else {
					Ext.getCmp('notSpecified').setValue(false);
				}
				deleteButton.enable()
			}
		}
	}
});

Ext.onReady(function() {

	Ext.BLANK_IMAGE_URL = '/img/ext/default/s.gif';
	Ext.QuickTips.init();

	hotjobform.render('form-div');
	grid.render('panel-div');
});
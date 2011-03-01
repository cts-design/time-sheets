/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

var proxy = new Ext.data.HttpProxy({
	url: '/admin/rfps/data_delegate'
});

var reader = new Ext.data.JsonReader({
    root: 'rfps',
    successProperty: "success",
    fields: ['id', 'title', 'byline', 'description', 'deadline', 'expires', 'contact_email', 'file']
});

var writer = new Ext.data.JsonWriter({
	encode: true	
});

var store = new Ext.data.JsonStore({
	autoLoad: true,
	storeId: 'rfp',
	root: 'rfps',
	proxy: proxy,
	reader: reader,
	writer: writer,
	listeners: {
		save: function() {

		}
	}
});

var rfpForm = new Ext.form.FormPanel({
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
	        	vtype: 'alphanum'
    		},{
    			xtype: 'textfield',
	            fieldLabel: 'Byline',
	            name: 'byline',
	            allowBlank: false,
	            width: 200,
	            anchor: '95%',
	            vtype: 'alphanum'
    		},{
    			xtype: 'textfield',
    			fieldLabel: 'Contact Email',
    			name: 'contactEmail',
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
	        	fieldLabel: 'Expiration',
	        	name: 'expiration',
	        	xtype: 'datefield',
	        	width: 200,
	        	anchor: '98%'	    		
	    	},{
	    		fieldLabel: 'File',
	    		name: 'file',
	    		xtype: 'fileuploadfield',
	    		width: 200,
	    		anchor: '98%'
	    	}]
        }]
     },{
     	xtype: 'htmleditor',
		enableFont: false,
		fieldLabel: 'Description',
		name: 'description',
		width: 300,
		anchor: '99%'
    }],
    buttons: [{
        text: 'Save',
        handler: function(){

        }
    }]
});

var grid = new Ext.grid.GridPanel({
	store: store,
	height: 300,
	frame: true,
	columns: [{
		id: 'title',
		header: 'Title',
		dataIndex: 'title',
	},{
		header: 'Byline',
		dataIndex: 'byline',
	 	editor: new Ext.form.TextField({}),
	},{
		header: 'Deadline',
		dataIndex: 'deadline',
	},{
		header: 'Expires',
		dataIndex: 'expires'
	}],
	viewConfig: {
		forceFit: true,
		emptyText: 'No records found.'
	},
	bbar: pagingToolbar
});

var pagingToolbar = new Ext.PagingToolbar({
	store: store,
	displayInfo: true,
	pageSize: 10
});

Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = '/img/ext/default/s.gif';
	Ext.QuickTips.init();
	
	rfpForm.render('form-div');
	grid.render('panel-div');
});
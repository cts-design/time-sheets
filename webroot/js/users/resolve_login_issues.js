/**
 * @author dnolan
 */

var vals = null;

var searchForm = Ext.create('Ext.form.Panel',{
    frame:true,
    title: 'Search Form',
    width: 325,
    id: 'search-form',
    collapsible: true,
    defaultType: 'textfield',
    fieldDefaults: {
    	labelWidth: 75
    },
    items: [{
        	fieldLabel: 'Search Type',
        	xtype: 'radiogroup',
        	allowBlank: false,
        	width: 300,
        	items: [{
        		boxLabel: 'Last Name',
        		name: 'searchType',
        		inputValue: 'lastname',
        	},{
 	           	boxLabel: 'Last 4 SSN',
        		name: 'searchType', 
        		inputValue: 'ssn',	
        	}]    	
        },{
            fieldLabel: 'Search',
            name: 'search',
            allowBlank: false,
            width: 300
        },{
        	fieldLabel: 'From',
        	name: 'from',
        	xtype: 'datefield'
        },{
        	fieldLabel: 'To',
        	name: 'to',
        	xtype: 'datefield'
        }
    ],
    buttons: [{
        text: 'Search',
        handler: function(){
			var f = searchForm.getForm();
        	if(f.isValid()) {
            	vals = f.getValues();
				store.proxy.extraParams = {
	            			search: vals.search,
	            			searchType: vals.searchType,
	            			from: vals.from,
	            			to: vals.to
            	}
				store.load();            	        		
        	}
        }
    }]
});

	
var cellEditing = Ext.create('Ext.grid.plugin.CellEditing', {
    clicksToEdit: 2
});	

Ext.define('User', {
	extend: 'Ext.data.Model',
	fields: ['id', 'firstname', 'lastname', 'ssn']	
});
 
var store = Ext.create('Ext.data.Store', {
	storeId: 'user',
	autoSync: true,
	model: 'User',
	proxy: {
	    url: '/admin/users/resolve_login_issues',
	    model: 'User',
	    type: 'ajax',
	    noCache: false,
	    reader: {
	    	type: 'json',
	    	root: 'users'
	    },
	    writer: {
	    	type: 'json',
	    	writeAllFields: false,
	    	root: 'user',
	    	encode: true
	    }			
	},
	listeners : {
		write:  function(store, operation, eOps) {
			store.load();
			var res = Ext.JSON.decode(operation.response.responseText);
			if(res.success == true) {
				Ext.Msg.alert('Success', 'Changes saved successfully.');
			}
			else {
				Ext.Msg.alert('Error', 'Unable to save record please try again.');
			}				
		}
	}		
});

Ext.define('Admin', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name']
});

var adminStore = Ext.create('Ext.data.Store', {
	proxy: {
		type: 'ajax',
		url: '/admin/users/get_admin_list',
		reader: {
			type: 'json',
			root: 'admins'
		}
	},	
	storeId: 'adminStore',
	model: 'Admin'
});		   	

var combo = Ext.create('Ext.form.ComboBox', {
    typeAhead: true,
    queryMode: 'remote',
    store: adminStore,
    valueField: 'id',
    displayField: 'name'
});

var emailButton = Ext.create('Ext.Button', {
	text: 'Request SSN Change',
	icon: '/img/icons/email_go.png',
	handler: function(){
		var sm = grid.getSelectionModel();
		if(sm.hasSelection()) {
			var row = sm.getSelection();
			var adminId = combo.getValue();
			if(!adminId) {
				Ext.Msg.alert('Error','Please select a admin to email from the dropdown');
				return false;
			}		
			Ext.Ajax.request({
				url: '/admin/users/request_ssn_change',
				params: {
					userId: row[0].data.id,
					adminId: adminId
				},
				scope: this,
				success: function(response, options) {
					var o = {};
					try {o = Ext.decode(response.responseText);}
					catch(e) {
						Ext.Msg.alert('Error','Unable to send email at this time.');
						return;
					}
					if(o.success !== true) {
						Ext.Msg.alert('Error','Unable to send email at this time.');
					}
					else {
						Ext.Msg.alert('Success','Email was sent successfully.');
					}
				},
				failure: function() {
					Ext.Msg.alert('Error','Unable to send email at this time.');
				}
			});						
		}
		else {
			Ext.Msg.alert('Error','Please select a customer from the table');
		}
	}
});
 
var tb = Ext.create('Ext.Toolbar', {
	width: 300,
	items: [combo, emailButton]
});
  	
var grid = Ext.create('Ext.grid.GridPanel', {
	store: store,
	height: 300,
	title: 'Customers',
	width: 325,
	frame: true,
    selModel: {
        selType: 'rowmodel'
    },		
	plugins: [cellEditing],
	columns: [{
		id: 'firstname',
		header: 'First Name',
		dataIndex: 'firstname',
		sortable: true

	},{
		header: 'Last Name',
		dataIndex: 'lastname',
	 	editor: {
	 		xtype: 'textfield'
	 	},
	 	sortable: true

	},{
		header: 'SSN Last 4',
		dataIndex: 'ssn',
		sortable: true

	}],
	tbar: tb,
	viewConfig: {
		forceFit: true,
		emptyText: 'No records found.'
	}
});

Ext.onReady(function(){
	Ext.QuickTips.init();
	searchForm.render('search');
	grid.render('grid');	
});
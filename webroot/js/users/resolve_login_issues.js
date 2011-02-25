/**
 * @author dnolan
 */

var vals = null;
Ext.onReady(function(){
	Ext.QuickTips.init();
    var searchForm = new Ext.FormPanel({
        frame:true,
        labelWidth: 75,
        title: 'Search Form',
        width: 325,
        id: 'search-form',
        collapsible: true,
        defaultType: 'textfield',
        items: [{
            	fieldLabel: 'Search Type',
            	xtype: 'radiogroup',
            	allowBlank: false,
            	width: 200,
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
                width: 200
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
	            	store.reload({
	            		params: {
	            			search: vals.search,
	            			searchType: vals.searchType,
	            			from: vals.from,
	            			to: vals.to
	            		}
	            	});	            		
            	}

            }
        }]
	});
	searchForm.render('search');

    var editor = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    
	var proxy = new Ext.data.HttpProxy({
	    url: '/admin/users/resolve_login_issues',
	    	success: function(response, options){ 
	    		if(options.params.xaction == 'update') {
						var o = {};
						try {o = Ext.decode(response.responseText);}
						catch(e) {
							Ext.Msg.alert('Error','Unable to save customer info please try again.');
							return;
						}
						if(o.success !== true) {
							Ext.Msg.alert('Error','Unable to save customer info please try again.');
						}    			
	    		}
	    	},
	    	listeners : {
				write: function(store, action, result, res, rs) {
					if(res.success == true) {
						Ext.Msg.alert('Success', 'Changes saved successfully.');
					}
					else {
						Ext.Msg.alert('Error', 'Unable to save record please try again.');
					}				
				}		    		
	    	}
	});
     
    var reader = new Ext.data.JsonReader({
	    root: 'users',
	    idProperty: 'id',
	    successProperty: "success",
	    fields: ['id', 'firstname', 'lastname', 'ssn']	    	
    });
			
	var writer = new Ext.data.JsonWriter({
	    encode: true
	});

	var store = new Ext.data.Store({
		storeId: 'user',
		root: 'users',
		proxy: proxy,
		reader: reader,
		writer: writer,
		listeners: {
			save: function() {
				store.reload({
            		params: {
            			search: vals.search,
            			searchType: vals.searchType
            		}
            	});
			}
		}
	}); 
	
	var adminStore = new Ext.data.JsonStore({
		url: '/admin/users/get_admin_list',
		storeId: 'adminStore',
		root: 'admins',
		idProperty: 'id',
		fields: ['id', 'name']
	});		   	
    
	var combo = new Ext.form.ComboBox({
	    typeAhead: true,
	    triggerAction: 'all',
	    mode: 'remote',
	    store: adminStore,
	    valueField: 'id',
	    displayField: 'name'
	});
	
	var emailButton = new Ext.Button({
		text: 'Request SSN Change',
		icon: '/img/icons/email_go.png',
		handler: function(){
			if(grid.getSelectionModel().hasSelection()) {
				var row = grid.getSelectionModel().getSelected();
				var adminId = combo.getValue()
				if(adminId == '') {
					Ext.Msg.alert('Error','Please select a admin to email from the dropdown');
					return false;
				}		
				Ext.Ajax.request({
					url: '/admin/users/request_ssn_change',
					params: {
						userId: row.id,
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
	
    
    var tb = new Ext.Toolbar({
    	width: 300,
    	items: [combo, emailButton]
    });
      	
	var grid = new Ext.grid.GridPanel({
		store: store,
		height: 300,
		title: 'Customers',
		width: 325,
		frame: true,
		plugins: [editor],
		columns: [{
			id: 'firstname',
			header: 'First Name',
			dataIndex: 'firstname',

		},{
			header: 'Last Name',
			dataIndex: 'lastname',
		 	editor: new Ext.form.TextField({}),

		},{
			header: 'SSN Last 4',
			dataIndex: 'ssn',

		}],
		tbar: tb,
		viewConfig: {
			forceFit: true,
			emptyText: 'No records found.'
		}
	});
	
	grid.render('grid');	
});
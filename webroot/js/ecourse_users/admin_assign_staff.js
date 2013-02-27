/**
 * @author dnolan
 */

Ext.onReady(function(){
	Ext.QuickTips.init();

	var vals = null;
	
	var searchForm = Ext.create('Ext.form.Panel',{
    frame:true,
    title: 'Search Form',
    width: 425,
    id: 'search-form',
    collapsible: true,
    defaultType: 'textfield',
    renderTo: 'search',
    fieldDefaults: {
      labelWidth: 75
    },
    items: [{
      fieldLabel: 'Search Type',
      xtype: 'radiogroup',
      allowBlank: false,
      width: 325,
      items: [{
        boxLabel: 'Last Name',
        name: 'searchType',
        inputValue: 'lastname',
        checked: true
      }]    	
    },{
      fieldLabel: 'Search',
      name: 'search',
      allowBlank: false,
      width: 315,
      listeners: {
        afterrender: function(field) {
          field.focus(false, 10);
        }
      }
    }],
	    buttons: [{
        text: 'Search',
        handler: function(){
          var f = searchForm.getForm();
          if(f.isValid()) {
            vals = f.getValues();
            Ext.data.StoreManager.lookup('users').load({
              params: {
                search: vals.search,
                searchType: vals.searchType
              }
            });            	        		
          }
        }
	    }]
	});
	
	
	Ext.define('User', {
		extend: 'Ext.data.Model',
		fields: ['id', 'firstname', 'lastname']	
	});
	 
  Ext.create('Ext.data.Store', {
		storeId: 'users',
		autoSync: true,
		model: 'User',
		proxy: {
		    url: '/admin/users/staff_search',
		    model: 'User',
		    type: 'ajax',
		    noCache: false,
		    reader: {
		    	type: 'json',
		    	root: 'users'
		    }
		},
	});
	
	  	
	var grid = Ext.create('Ext.grid.Panel', {
		store: 'users',
		forceFit: true,
		height: 300,
		title: 'Staff',
		width: 425,
		frame: true,
		renderTo: 'grid',
		columns: [{
			id: 'firstname',
			text: 'First Name',
			dataIndex: 'firstname',
			sortable: true
	
		},{
			text: 'Last Name',
			dataIndex: 'lastname',
		 	editor: {
		 		xtype: 'textfield'
		 	},
		 	sortable: true
	
		},{
      text: 'Actions',
      xtype: 'actioncolumn',
      items: [{
        icon: '/img/icons/user_add.png',
        handler: function(grid, rowIndex, colIndex) {
          var rec = grid.getStore().getAt(rowIndex);

          Ext.Ajax.request({
            url: '/admin/ecourse_users/assign_staff',
            params: {
              user_id: rec.get('id'),
              ecourse_id: ecourseId 
            },
            success: function(response) {
              var txt = Ext.JSON.decode(response.responseText);
              Ext.MessageBox.alert('Success', txt.message);
            },
            failure: function() {
              Ext.MessageBox.alert('Failure', 'Unable to assign user, please try again.');
            }
          });
        }
      }]
    }],
		viewConfig: {
			emptyText: 'No records found.'
		}
	});	

});

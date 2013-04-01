Ext.define('EventRegistration', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id'},
        {name: 'user_id'},
        {name: 'firstname'},
        {name: 'lastname'},
        {name: 'last4'},
        {name: 'registered'},
        {name: 'present', type: 'boolean'}
     ]
});

Ext.create('Ext.data.Store', {
  model: 'EventRegistration',
  storeId: 'eventRegistrations',
  proxy: {
    type: 'ajax',
    extraParams: {
      'data[Event][id]': eventId
    },
    api: {
      update: '/admin/event_registrations/edit',
      read: '/admin/event_registrations/index/'+eventId,
      destroy: '/admin/event_registrations/delete'
    },
    reader: {
      type: 'json',
      root: 'registrations'
    },
    writer: {
      root: 'data[EventRegistration]',
      encode: true,
      writeAllFields: true
    }
  },
  autoLoad: true,
  listeners: {
    write: function(store, operation, eOpts) {
      var sb = Ext.getCmp('status-bar');
      var responseTxt = Ext.JSON.decode(operation.response.responseText);
      var iconCls = 'x-status-error';
      if(responseTxt.success) {
        iconCls = 'x-status-valid'; 
      }
      sb.setstatus({
        text: responsetxt.message,
        iconCls: iconCls,
        clear: {
          anim: false
        }
      });
    },
    beforesync: function(options, eOpts) {
      var sb = Ext.getCmp('status-bar');
      if(options.destroy !== undefined) {
        sb.showBusy({text: 'Deleting records....'});
      }
      else {
        sb.showBusy({text: 'Updating attendance....'});
      }
    }
  }
});

Ext.create('Ext.form.Panel',{
  frame:true,
  title: 'Search Form',
  width: 425,
  id: 'searchForm',
  collapsible: true,
  defaultType: 'textfield',
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
    },{
      boxLabel: 'Last 4 SSN',
      name: 'searchType', 
      inputValue: 'last4'	
    },{
      boxLabel: 'Full SSN',
      name: 'searchType',
      inputValue: 'ssn'
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
        var f = Ext.getCmp('searchForm').getForm();
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
  fields: ['id', 'firstname', 'lastname', 'last_4']	
});
Ext.create('Ext.data.Store', {
  storeId: 'users',
  autoSync: true,
  model: 'User',
  proxy: {
      url: '/admin/users/customer_search',
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
      store.load({
        params: {
              search: vals.search,
              searchType: vals.searchType,
              from: vals.from,
              to: vals.to						
        }
      });
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

Ext.create('Ext.grid.Panel', {
  store: 'users',
  forceFit: true,
  id: 'cusGrid',
  height: 300,
  title: 'Customers',
  width: 425,
  frame: true,
  selModel: {
      selType: 'rowmodel'
  },		
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
    text: 'SSN Last 4',
    dataIndex: 'last_4',
    sortable: true

  },{
    text: 'Actions',
    xtype: 'actioncolumn',
    items: [{
      icon: '/img/icons/user_add.png',
      handler: function(grid, rowIndex, colIndex) {
        var rec = grid.getStore().getAt(rowIndex);
        Ext.Ajax.request({
          url: '/admin/event_registrations/register_customer',
          params: {
            user_id: rec.get('id'),
            event_id: eventId 
          },
          success: function(response) {
            var txt = Ext.JSON.decode(response.responseText);
            Ext.MessageBox.alert('Success', txt.message);
            Ext.data.StoreManager.lookup('eventRegistrations').load();
          },
          failure: function() {
            Ext.MessageBox.alert('Failure', 'Unable to register customer, please try again.');
          }
        });
      }
    }]
  }],
  viewConfig: {
    emptyText: 'No records found.'
  }
});

var sm = Ext.create('Ext.selection.CheckboxModel');

Ext.create('Ext.grid.Panel', {
  store: 'eventRegistrations',
  selModel: sm,
  id: 'registrations',
  bbar: Ext.create('Ext.ux.StatusBar', {
    id: 'status-bar',
    defaultText: 'Ready',
    defaultIconCls: 'default-icon',
    text: 'Ready'
  }),
  dockedItems: [{
    xtype: 'toolbar',
    items:[{
      text: 'Mark Present',
      iconCls: 'icon_user_add',
      handler: function() {
        var store = Ext.data.StoreManager.lookup('eventRegistrations');
        Ext.Array.each(sm.selected.items, function(record) {
          record.set('present', 1);
        });
        store.sync();
        sm.deselectAll();
      }
    },{
      text: 'Mark Absent',
      iconCls: 'icon_user_delete',
      handler: function() {
        var store = Ext.data.StoreManager.lookup('eventRegistrations');
        Ext.Array.each(sm.selected.items, function(record) {
          record.set('present', 0);
        });
        store.sync();
        sm.deselectAll();
      }
    },{
      text: 'Delete',
      iconCls: 'icon_delete',
      handler: function() {
        Ext.MessageBox.confirm('Confim delete', 'Are you sure you want to delete these registrations?', function() {
          var store = Ext.data.StoreManager.lookup('eventRegistrations');
          store.remove(sm.selected.items);
          store.sync();
          sm.deselectAll();
        });
      }
    }]       

  }],
  columns: [
    {text: "First Name", dataIndex: 'firstname', flex: 1},
    {text: "Last Name", dataIndex: 'lastname', flex: 1},
    {text: "Last 4 SSN", dataIndex: 'last4'},
    {text: "Registered On", dataIndex: 'registered', xtype: 'datecolumn', format: 'm/d/y'},
    {text: "Present", dataIndex: 'present', xtype: 'booleancolumn', trueText: 'Yes', falseText: 'No'}
  ],
  columnLines: true,
  width: 525,
  height: 420,
  frame: true,
  title: 'Registered Users',
  margin: '0 0 20 0',
  iconCls: 'icon_group',
});

Ext.create('Ext.panel.Panel', {
  id: 'register',
  border: 0,
  frame: false,
  items: ['searchForm', 'cusGrid']
});

Ext.onReady(function() {
  Ext.QuickTips.init();

  Ext.create('Ext.panel.Panel', {
    layout: 'hbox',
    border: 0,
    frame: false,
    items: ['registrations', 'register'],
    renderTo: 'event'
  });

});

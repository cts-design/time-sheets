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
      sb.setStatus({
        text: responseTxt.message,
        iconCls: iconCls,
        clear: {
          anim: false
        }
      });
    },
    beforesync: function() {
      var sb = Ext.getCmp('status-bar');
      sb.showBusy({text: 'Updating attendance....'});
    }
  }
});

Ext.onReady(function() {

    var sm = Ext.create('Ext.selection.CheckboxModel');
    Ext.create('Ext.grid.Panel', {
        store: 'eventRegistrations',
        selModel: sm,
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
              var store = Ext.data.StoreManager.lookup('eventRegistrations');
              store.remove(sm.selected.items);
              store.sync();
              sm.deselectAll();
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
        width: 600,
        height: 400,
        frame: true,
        title: 'Registered Users',
        margin: '0 0 20 0',
        iconCls: 'icon_group',
        renderTo: 'event'
    });
});

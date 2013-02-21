Ext.define('EcoureUser', {
    extend: 'Ext.data.Model',
    fields: [
        {name: 'id'},
        {name: 'firstname'},
        {name: 'lastname'},
        {name: 'last4'}
     ]
});

Ext.create('Ext.data.Store', {
  model: 'EcoureUser',
  storeId: 'ecourseUsersStore',
  proxy: {
    type: 'ajax',
    extraParams: {
      'id': ecourseId
    },
    api: {
      read: '/admin/ecourse_users/index/'+ecourseId,
      destroy: '/admin/ecourse_users/delete'
    },
    reader: {
      type: 'json',
      root: 'assignments'
    },
    writer: {
      root: 'data[EcourseUser]',
      encode: true,
      writeAllFields: false 
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
    beforesync: function(options, eOpts) {
      var sb = Ext.getCmp('status-bar');
      if(options.destroy !== undefined) {
        sb.showBusy({text: 'Deleting records....'});
      }
      else {
        sb.showBusy({text: 'Updating assignments....'});
      }
    }
  }
});

Ext.onReady(function() {

    var sm = Ext.create('Ext.selection.CheckboxModel');
    Ext.create('Ext.grid.Panel', {
        store: 'ecourseUsersStore',
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
            text: 'Delete',
            iconCls: 'icon_delete',
            handler: function() {
              Ext.MessageBox.confirm('Confim delete', 'Are you sure you want to delete these registrations?', function(button) {
                if(button === 'yes') {
                  var store = Ext.data.StoreManager.lookup('ecourseUsersStore');
                  store.remove(sm.selected.items);
                  store.sync();
                }
                sm.deselectAll();
              });
            }
          }]       
      
        }],
        columns: [
            {text: "id", dataIndex: 'id', hidden: true},
            {text: "First Name", dataIndex: 'firstname', flex: 1},
            {text: "Last Name", dataIndex: 'lastname', flex: 1},
            {text: "Last 4 SSN", dataIndex: 'last4'},
        ],
        columnLines: true,
        width: 600,
        height: 400,
        frame: true,
        title: 'Assigned Users',
        margin: '0 0 20 0',
        iconCls: 'icon_group',
        renderTo: 'grid'
    });
});

var dt = new Date();
	

Ext.define('EventCategory', {
  extend: 'Ext.data.Model',
  fields: [
    {name: 'id'},
    {name: 'name'},
    {name: 'parent_name'},
    {name: 'parent_id'},
    {name: 'created', type: 'date', dateFormat: 'n/j h:ia'},
    {name: 'modified', type: 'date', dateFormat: 'n/j h:ia'}
  ]
});

Ext.create('Ext.data.Store', {
  model: 'EventCategory',
  storeId: 'eventCategoryListStore',
  proxy: {
    type: 'ajax',
    extraParams: {parent: true},
    url: '/admin/event_categories/get_list',
    reader: {
      type: 'json',
      root: 'categories'
    },
    limitParam: undefined,
    pageParam: undefined,
    startParam: undefined				
  },
  autoLoad: true
});

Ext.create('Ext.data.Store', {
  model: 'EventCategory',
  storeId: 'eventCategoriesStore',
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/event_categories/add/',
      read: '/admin/event_categories',
      update: '/admin/event_categories/edit/',
      destroy: '/admin/event_categories/delete/'
    },
    reader: {
      type: 'json',
      root: 'categories'
    },
    writer: {
      root: 'data[EventCategory]',
      encode: true,
      writeAllFields: false,
    }
  },
  remoteSort: false,
  autoLoad: true,
  listeners: {
    write: function(store, operation, eOpts) {
      var sb = Ext.getCmp('status-bar');
      var responseTxt = Ext.JSON.decode(operation.response.responseText);
      if(!responseTxt.success || !operation.success ) {
        var msg = null;
        switch(operation.action) {
          case 'destroy' :
            msg = 'Unable to delete event.';
            break;
          case 'create' :
            msg = 'Unable to create event.';
            break;
          case 'update' :
            msg = 'Unable to update event.';
            break;
        }
        sb.setStatus({
          text: msg,
          iconCls: 'x-status-error',
          clear: {
            anim: false
          }
        });
      }
      if(responseTxt.success) {
        sb.setStatus({
          text: responseTxt.message,
          iconCls: 'x-status-valid',
          clear: {
            anim: false
          }
        });
        var formPanel = Ext.getCmp('eventsForm');
        if(operation.action === 'create' || operation.action === 'update') {
          formPanel.getForm().reset();
          Ext.getCmp('cat2Name').disable();
          Ext.getCmp('cat3Name').disable();
          store.load();
        }
        if(operation.action === 'destroy') {
          formPanel.getForm().reset();
          Ext.getCmp('cat2Name').disable();
          Ext.getCmp('cat3Name').disable();
          store.load();
        }
      }
    },
    beforesync: function(){
      var sb = Ext.getCmp('status-bar');
      sb.showBusy({text: 'Saving.....'});
    }
  }
});

Ext.create('Ext.form.Panel', {
  id: 'eventCategoriesForm',
  frame: true,
  bodyPadding: 5,
  width: 950,
  height: 550,
  layout: 'column',
  fieldDefaults: {
    labelAlign: 'left',
    msgTarget: 'side'
  },
  items: [{
    columnWidth: 0.7,
    xtype: 'gridpanel',
    height: 500,
    id: 'eventCategoriesGrid',
    store: Ext.data.StoreManager.lookup('eventCategoriesStore'),
    scroll: false,
    viewConfig: {
      style: {
        overflow: 'auto',
        overflowX: 'hidden'
      },
      listeners: {
        itemcontextmenu: function(view, rec, node, index, e){
          e.stopEvent();
          Ext.getCmp('contextMenu').showAt(e.getXY());
        }
      }
    },
    bbar: Ext.create('Ext.ux.StatusBar', {
      id: 'status-bar',
      defaultText: 'Ready',
      defaultIconCls: 'default-icon',
      text: 'Ready'
    }),
    title:'Event Categories',
    columns: [{
      text: 'id',
      dataIndex: 'id',
      hidden: true
    },{
      text: 'Name',
      dataIndex: 'name',
      flex: 1
    }],
    tbar: [{xtype: 'tbfill'},{
      xtype: 'button',
      text: 'New Event Category',
      icon: '/img/icons/date_add.png',
      handler: function() {
        this.up('form').getForm().reset();
        this.up('grid').getSelectionModel().deselectAll();
      }
    }]
  }, {
  columnWidth: 0.3,
  margin: '0 0 0 10',
  padding: 10,
  xtype: 'fieldset',
  frame: false,
  listeners: {
    beforeadd: function(fieldset, component, index, eOpts) {
      if(component.allowBlank !== undefined) {
        if(!component.allowBlank) {
          component.labelSeparator += '<span style="color: red; padding-left: 2px;">*</span>';
        }
      }
    }
  },
  title:'Add / Edit Form',
  defaults: {
    width: 245,
    labelWidth: 70
  },
  defaultType: 'textfield',
  items: [{
    name: 'id',
    xtype: 'hidden'
  },{
    fieldLabel: 'Name',
    name: 'name',
    allowBlank: false,
    maxLength: 100,
    enforceMaxLength: true,
  },{
    fieldLabel: 'Make Child Category',
    xtype: 'checkbox',
    submitValue: false,
    listeners: {
      change: function(field, newValue, oldValue, eOpts) {
        if(newValue) {
          this.nextSibling().enable();
        }
        else {
          this.nextSibling().disable();
        }
      }
    }
  },{
    fieldLabel: 'Parent Category',
    name: 'parent_id',
    xtype: 'combo',
    emptyText: 'Please Select',
    editable: false,
    disabled: true,
    valueField: 'id',
    displayField: 'name',
    store: Ext.data.StoreManager.lookup('eventCategoryListStore')
  }]
  }],
  buttons: [{
    text: 'Save',
    formBind: true,
    handler: function() {
      var form = this.up('form').getForm();
      var vals = form.getValues();
      if(form.isValid()) {
        var event;
        var store = Ext.data.StoreManager.lookup('eventsStore');
        if(vals.id !== '') {
          event = store.getById(vals.id);
          event.beginEdit();
          event.set(vals);
          event.endEdit();
        }
        else {
          event = Ext.create('Event', form.getValues());
          store.add(event);
        }
        store.sync();
      }
    }
  }]
});

Ext.onReady(function(){
  Ext.getCmp('eventCategoriesForm').render('eventCategories');
});


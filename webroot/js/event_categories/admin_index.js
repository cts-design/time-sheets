Ext.define('EventCategory', {
  extend: 'Ext.data.Model',
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
      writeAllFields: false
    }
  },
  fields: [
    {name: 'id'},
    {name: 'name'},
    {name: 'parent_name'},
    {name: 'parent_id'},
    {name: 'make_child'},
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

  remoteSort: false,
  autoLoad: true,
  listeners: {
    beforesync: function(){
      var sb = Ext.getCmp('status-bar');
      sb.showBusy({text: 'Saving.....'});
    }
  }
});

Ext.create('Ext.menu.Menu', {
  id: 'contextMenu',
  title: 'Event Actions',
  bodyPadding: 5,
  items:[{
    xtype: 'button',
    text: 'Edit Category',
    icon: '/img/icons/date_edit.png',
    handler: function() {
      var formPanel = Ext.getCmp('eventCategoriesForm'),
      grid = formPanel.down('grid'),
      form = formPanel.getForm(),
      record = grid.getSelectionModel().getLastSelected();
      if(record.data.parent_id) {
        record.data.name = record.data.name.substr(3);
        record.data.make_child = true;
      }
      form.loadRecord(record);
   }
  },{
    xtype: 'button',
    text: 'Delete Category',
    icon: '/img/icons/date_delete.png',
    handler: function() {
      var formPanel = Ext.getCmp('eventCategoriesForm'),
      record = formPanel.down('grid').getSelectionModel().getLastSelected();
      record.destroy({
        success: function(rec, op) {
          responseTxt = Ext.JSON.decode(op.response.responseText);
          processResponse(responseTxt.message, 'x-status-valid');
        },
        failure: function(rec, op) {
          msg = op.request.proxy.reader.jsonData.message;
          processResponse(msg, 'x-status-error');
        }
      });
    }
  }]
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
    listeners: {
      itemcontextmenu: function(view, rec, node, index, e){
        e.stopEvent();
        Ext.getCmp('contextMenu').showAt(e.getXY());
      },
      itemdblclick: function(grid, record, item, index, e, eOpts) {
        var formPanel = this.up('form');
        var form = formPanel.getForm();
        if(record.data.parent_id) {
          record.data.name = record.data.name.substr(3);
          record.data.make_child = true;
        }
        form.loadRecord(record);
      }
    },
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
    name: 'make_child',
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
        var category;
        var store = Ext.data.StoreManager.lookup('eventCategoriesStore');
        if(vals.id !== '') {
          category = store.getById(vals.id);
          category.beginEdit();
          category.set(vals);
          category.endEdit();
        }
        else {
          category = Ext.create('EventCategory', form.getValues());
        }
        category.save({
          success: function(rec, op) {
            responseTxt = Ext.JSON.decode(op.response.responseText);
            processResponse(responseTxt.message, 'x-status-valid');
          },
          failure: function(rec, op) {
            msg = op.request.proxy.reader.jsonData.message;
            processResponse(msg, 'x-status-error');
          },
          callback: function() {
            store.load();
          }
        });
      }
    }
  }]
});

var processResponse = function(msg, icon) {
  var sb = Ext.getCmp('status-bar');
  sb.setStatus({
    text: msg,
    iconCls: icon,
    clear: {
      anim: false
    }
  });
  Ext.getCmp('eventCategoriesForm').getForm().reset();
};

Ext.onReady(function(){
  Ext.getCmp('eventCategoriesForm').render('eventCategories');
});


var dt = new Date();

Ext.define('Event', {
  extend: 'Ext.data.Model',
  fields: [
    {name: 'id'},
    {name: 'event_category_id'},
    {name: 'category'},
    {name: 'name'},
    {name: 'description'},
    {name: 'location'},
    {name: 'location_id'},
    {name: 'other_location'},
    {name: 'url'},
    {name: 'address'},
    {name: 'scheduled', type: 'date', dateFormat: 'Y-m-d H:i:s'},
    {name: 'allow_registrations'},
	  {name: 'private'},
    {name: 'seats_available'},
    {name: 'duration'},
    {name: 'event_registration_count'},
    {name: 'cat_1'},
    {name: 'cat_2'},
    {name: 'cat_3'},
    {name: 'created', type: 'date', dateFormat: 'n/j h:ia'},
    {name: 'modified', type: 'date', dateFormat: 'n/j h:ia'}
  ]
});

Ext.create('Ext.data.Store', {
  model: 'Event',
  storeId: 'eventsStore',
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/events/add/',
      read: '/admin/events',
      update: '/admin/events/edit/',
      destroy: '/admin/events/delete/'
    },
    reader: {
      type: 'json',
      root: 'events'
    },
    writer: {
      root: 'data[Event]',
      encode: true,
      writeAllFields: false
    },
    directionParam: 'direction',
    simpleSortMode: true
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

Ext.define('DocumentFilingCategory', {
  extend: 'Ext.data.Model',
  fields: [{name: 'id', type: 'int'}, {name: 'name'}, {name: 'secure'}, {
    name : 'img',
    convert: function(value, record){
      var img = null;
      var secure = record.get('secure');
      if(secure) {
        img = '<img src="/img/icons/lock.png" />&nbsp';
      }
      return img;
    }
  }]
});

var catProxy = Ext.create('Ext.data.proxy.Ajax', {
  url: '/admin/document_filing_categories/get_cats',
  reader: {
    type: 'json',
    root: 'cats'
  },
  extraParams: {
    parentId: 'parent'
  }
});

Ext.create('Ext.data.Store', {
  storeId: 'cat1Store',
  model: 'DocumentFilingCategory',
  proxy: catProxy,
  autoLoad: true
});

Ext.create('Ext.data.Store', {
  storeId: 'cat2Store',
  model: 'DocumentFilingCategory',
  proxy: catProxy,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      if(records[0]) {
        Ext.getCmp('cat2Name').enable();
      }
    }
  }
});

Ext.create('Ext.data.Store', {
  storeId: 'cat3Store',
  model: 'DocumentFilingCategory',
  proxy: catProxy,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      if(records[0]) {
        Ext.getCmp('cat3Name').enable();
      }
    }
  }
});
 
Ext.define('Location', {
  extend: 'Ext.data.Model',
  fields: ['id', 'name']
});
	
Ext.create('Ext.data.Store', {
  model: 'Location',
  storeId: 'locationsStore',
  proxy: {
    type: 'ajax',
    url: '/admin/locations/get_location_list',
    reader: {
      type: 'json',
      root: 'locations'
    },
    limitParam: undefined,
    pageParam: undefined,
    startParam: undefined				
  },
  autoLoad: true,
  listeners: {
    load: function(store, records, successful, operation, eOpts) {
      store.add({id: "0", name: 'Other'});
    }
  }
});

Ext.define('EventCategory', {
  extend: 'Ext.data.Model',
  fields: ['id', 'parent_id', 'name']
});
	
Ext.create('Ext.data.Store', {
  model: 'EventCategory',
  storeId: 'eventCategoriesStore',
  proxy: {
    type: 'ajax',
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

Ext.create('Ext.menu.Menu', {
  id: 'contextMenu',
  title: 'Event Actions',
  bodyPadding: 5,
  items:[{
    xtype: 'button',
    text: 'Event Registrations',
    icon: '/img/icons/date_go.png',
    handler: function() {
      var formPanel = Ext.getCmp('eventsForm'),
      grid = formPanel.down('grid'),
      record = grid.getSelectionModel().getLastSelected();
      window.location.replace('/admin/event_registrations/index/'+record.data.id); 
    }
  },{
    xtype: 'button',
    text: 'Duplicate Event',
    icon: '/img/icons/date_copy.png',
    handler: function() {
      var formPanel = Ext.getCmp('eventsForm'),
      record = formPanel.down('grid').getSelectionModel().getLastSelected();
      record.data.id = undefined;
      formPanel.loadRecord(record);
    }
  },{
    xtype: 'button',
    text: 'Edit Event',
    icon: '/img/icons/date_edit.png',
    handler: function() {
      var formPanel = Ext.getCmp('eventsForm'),
      grid = formPanel.down('grid'),
      form = formPanel.getForm(),
      record = grid.getSelectionModel().getLastSelected();
      Ext.getCmp('cat2Name').disable();
      Ext.getCmp('cat3Name').disable();
      if (record) {
        if(record.data.registered > 0) {
          form.reset();
          Ext.MessageBox.alert('Error', 'Cannot edit an even that already has registrants.');
        }
        else {
          form.loadRecord(record);
          var otherLocation = formPanel.down('fieldset').getComponent('otherLocation'), 
          address = formPanel.down('fieldset').getComponent('address');
          if(record.data.location === 'Other') {
            otherLocation.enable();
            address.enable();
          }
          else {
            otherLocation.disable();
            address.disable();
          }
        }
      }
      form.clearInvalid();
   }
  },{
    xtype: 'button',
    text: 'Delete Event',
    icon: '/img/icons/date_delete.png',
    handler: function() {
      var formPanel = Ext.getCmp('eventsForm'),
      record = formPanel.down('grid').getSelectionModel().getLastSelected();
      store = Ext.data.StoreManager.lookup('eventsStore');
      if(record.data.registered > 0) {
        Ext.MessageBox.alert('Error', 'Cannot delete an event that already has registrants.');
      }
      else {
        store.remove(record);
        store.sync();
      }
    }
  }]
});

Ext.create('Ext.form.Panel', {
  id: 'eventsForm',
  frame: true,
  bodyPadding: 5,
  width: 950,
  height: 610, //550
  layout: 'column',
  fieldDefaults: {
    labelAlign: 'left',
    msgTarget: 'side'
  },
  items: [{
    columnWidth: 0.7,
    xtype: 'gridpanel',
    height: 555, //500
    id: 'eventsGrid',
    store: Ext.data.StoreManager.lookup('eventsStore'),
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
    title:'Events',
    columns: [{
      text: 'id',
      dataIndex: 'id',
      hidden: true
    },{
      text: 'Name',
      dataIndex: 'name',
      flex: 1
    },{
      text: 'Location',
      dataIndex: 'location',
      flex: 1
    },{
      text: 'Category',
      dataIndex: 'category'
    },{
      text: 'Scheduled',
      dataIndex: 'scheduled',
			format: 'm/d/y g:i a',
			xtype: 'datecolumn',
      width: 120
    },{
      text: 'Registered',
      dataIndex: 'event_registration_count',
      width: 70
    }],
    tbar: [{xtype: 'tbfill'},{
      xtype: 'button',
      text: 'New Event',
      icon: '/img/icons/date_add.png',
      handler: function() {
        this.up('form').getForm().reset();
        Ext.getCmp('cat2Name').disable();
        Ext.getCmp('cat3Name').disable();
        this.up('grid').getSelectionModel().deselectAll();
      }
    },],
    listeners: {
      //TODO decide if this should stay or not
      itemdblclick: function(grid, record, item, index, e, eOpts) {
        var formPanel = this.up('form');
        var form = formPanel.getForm();
        Ext.getCmp('cat2Name').disable();
        Ext.getCmp('cat3Name').disable();
        if (record) {
          if(record.data.registered > 0) {
            form.reset();
            Ext.MessageBox.alert('Error', 'Cannot edit an even that already has registrants.');
          }
          else {
            this.up('form').getForm().loadRecord(record);
            var otherLocation = formPanel.down('fieldset').getComponent('otherLocation'), 
            address = formPanel.down('fieldset').getComponent('address');
            if(record.data.location === 'Other') {
              otherLocation.enable();
              address.enable();
            }
            else {
              otherLocation.disable();
              address.disable();
            }
          }
        }
        this.up('form').getForm().clearInvalid();
      }
    }
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
  trackResetOnLoad: true,
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
    enforceMaxLength: true
  },{
    fieldLabel: 'Description',
    name: 'description',
    xtype: 'textarea',
    allowBlank: false
  },{
    fieldLabel: 'Category',
    name: 'event_category_id',
    xtype: 'combo',
    emptyText: 'Please Select',
    editable: false,
    displayField: 'name',
    valueField: 'id',
    store: Ext.data.StoreManager.lookup('eventCategoriesStore'),
    queryMode: 'local',
    allowBlank: false,
    listeners: {
      change: function(combo, newValue, oldValue, eOpts) {
        var re = /workshop/i,
        value;
        if(this.valueModels[0] !== undefined && this.valueModels[0].data.parent_id) {
          var parent = this.store.getById(this.valueModels[0].data.parent_id);
          if(parent) {
            value = parent.raw.name;
          }
        }
        else {
         value = this.rawValue;
        }
        if(re.test(value)) {
          Ext.getCmp('cat1Name').enable();
        }
        else { 
          Ext.getCmp('cat1Name').disable();
        }
      }
    }
  },{
    fieldLabel: 'Location',
    name: 'location_id',
    xtype: 'combo',
    editable: false,
    emptyText: 'Please Select',
    displayField: 'name',
    valueField: 'id',
    store: Ext.data.StoreManager.lookup('locationsStore'),
    queryMode: 'local',
    allowBlank: false,
    listeners: {
      change: function(combo, newValue, oldValue, eOpts) {
        var otherLocation = combo.nextSibling(),
        form = combo.up('form'),
        record = form.getRecord();
        otherLocation.reset();
        otherLocation.disable();
        otherLocation.nextSibling().reset();
        otherLocation.nextSibling().disable();
        if(newValue === "0") {
          if(record !== undefined) {
            otherLocation.setValue(record.data.other_location);
            otherLocation.nextSibling().setValue(record.data.address);
          }
          otherLocation.enable();
          otherLocation.nextSibling().enable();
        }
      }
    }
  },{
    fieldLabel: 'Other Location',
    itemId: 'otherLocation',
    name: 'other_location',
    disabled: true,
    allowBlank: false 
  },{
    fieldLabel: 'Address',
    xtype: 'textarea',
    itemId: 'address',
    name: 'address',
    disabled: true,
    allowBlank: false
  },{
    fieldLabel: 'URL',
    name: 'url',
    vtype: 'url'
  },{
	  fieldLabel: 'Make Private',
	  name: 'private',
	  xtype: 'checkbox'
  },{
    fieldLabel: 'Allow Registrations',
    name: 'allow_registrations',
    xtype: 'checkbox',
    listeners: {
      change: function(checkbox, newValue, oldValue, eOpts) {
        if(newValue) {
          this.nextSibling().enable();
        }
        else {
          this.nextSibling().disable();
        }
      }
    }
  },{
    fieldLabel: 'Seats',
    name: 'seats_available',
    xtype: 'numberfield',
    disabled: true,
    width: 120,
    minValue: 1,
    maxValue: 100,
    allowBlank: false
  },{
    fieldLabel: 'Scheduled',
    xtype: 'xdatetime',
    name: 'scheduled',
    allowBlank: false,
    dateConfig: {
      minValue: dt,
      submitFormat: 'y-m-d',
      format: 'm/d/y'
    },
    timeConfig: {
      minValue: '7:00 am',
      maxValue: '10:00 pm',
      submitFormat: 'H:i:s',
      format: 'g:i a'
    }
  },{
    fieldLabel: 'Duration in hours',
    xtype: 'numberfield',
    name: 'duration',
    minValue: 1,
    step: .25,
    allowBlank: false,
    maxValue: 8,
    width: 150
  },{
    fieldLabel: 'Cat 1',
    disabled: true,
    name: 'cat_1',
    id: 'cat1Name',
    store: Ext.data.StoreManager.lookup('cat1Store'),
    displayField: 'name',
    valueField: 'id',
    emptyText: 'Please Select',
    listConfig: {
      getInnerTpl: function() {
        return '<div>{img}{name}</div>';
      }
    },
    queryMode: 'local',
    xtype: 'combo',
    value: null,
    editable: false,
    allowBlank: false,
    listeners: {
      select: function(combo, records, Eopts) {
        if(records[0]) {
          Ext.getCmp('cat2Name').disable();
          Ext.getCmp('cat2Name').reset();
          Ext.getCmp('cat3Name').disable();
          Ext.getCmp('cat3Name').reset();
          Ext.data.StoreManager.lookup('cat2Store').load({params: {parentId: records[0].data.id}});
        }
      }
    }
  },{
    fieldLabel: 'Cat 2',
    name: 'cat_2',
    id: 'cat2Name',
    xtype: 'combo',
    disabled: true,
    emptyText: 'Please Select',
    store: Ext.data.StoreManager.lookup('cat2Store'),
    displayField: 'name',
    valueField: 'id',
    queryMode: 'local',
    editable: false,
    value: null,
    listConfig: {
      getInnerTpl: function() {
        return '<div>{img}{name}</div>';
      }
    },
    allowBlank: false,
    listeners: {
      select: function(combo, records, Eopts) {
        if(records[0]) {
          Ext.getCmp('cat3Name').disable();
          Ext.getCmp('cat3Name').reset();
          Ext.data.StoreManager.lookup('cat3Store').load({params: {parentId: records[0].data.id}});
        }
      }
    }
  },{
    fieldLabel: 'Cat 3',
    name: 'cat_3',
    id: 'cat3Name',
    xtype: 'combo',
    store: Ext.data.StoreManager.lookup('cat3Store'),
    emptyText: 'Please Select',
    disabled: true,
    displayField: 'name',
    valueField: 'id',
    queryMode: 'local',
    editable: false,
    value: null,
    listConfig: {
      getInnerTpl: function() {
        return '<div>{img}{name}</div>';
      }
    },
    allowBlank: false
    }]
  }],
  buttons: [{
    text: 'Save',
    formBind: true,
    handler: function() {
      var form = this.up('form').getForm();

      var vals = form.getValues(false, false, false, true);
      if(form.isValid()) {
        var event;
        var store = Ext.data.StoreManager.lookup('eventsStore');
        if(vals.id !== '') {
          event = store.getById(vals.id);
          event.beginEdit();
          vals['private'] = (vals['private'] ? 1 : 0);
          event.set(vals);
          event.endEdit();
        }
        else {
          event = Ext.create('Event', vals);
          store.add(event);
        }
       store.sync();
      }
    }
  }]
});

Ext.onReady(function(){
  Ext.getCmp('eventsForm').render('events');
});


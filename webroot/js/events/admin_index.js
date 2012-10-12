var dt = new Date();

Ext.define('Event', {
  extend: 'Ext.data.Model',
  fields: [
    {name: 'id'},
    {name: 'event_category_id'},
    {name: 'category'},
    {name: 'name'},
    {name: 'description'},
    {name: 'location', serverKey: 'location_id'},
    {name: 'other_location'},
    {name: 'url'},
    {name: 'address'},
    {name: 'scheduled', type: 'date', dateFormat: 'Y-m-d H:i:s'},
    {name: 'seats_available'},
    {name: 'duration'},
    {name: 'registered'},
    {name: 'attended'},
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
      writeAllFields: false,
      nameProperty: 'serverKey'
    },
    directionParam: 'direction',
    simpleSortMode: true
  },
  remoteSort: false,
  autoLoad: true,
  listeners: {
    write: function(store, operation, eOpts) {
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
        Ext.MessageBox.alert('Status', msg);
      }
      if(responseTxt.success) {
        Ext.MessageBox.alert('Status', responseTxt.message);
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
      Ext.MessageBox.wait('Please Wait......');
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
    url: '/admin/locations/get_locations_with_address',
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
      store.add({id: 0, name: 'Other'});
    }
  }
});

Ext.define('EventCategory', {
  extend: 'Ext.data.Model',
  fields: ['id', 'name']
});
	
Ext.create('Ext.data.Store', {
  model: 'EventCategory',
  storeId: 'eventCategoriesStore',
  proxy: {
    type: 'ajax',
    url: '/admin/event_categories/get_all_categories',
    reader: {
      type: 'json',
      root: 'eventCategories'
    },
    limitParam: undefined,
    pageParam: undefined,
    startParam: undefined				
  },
  autoLoad: true
});

Ext.create('Ext.form.Panel', {
  id: 'eventsForm',
  frame: true,
  bodyPadding: 5,
  width: 950,
  height: 550,
  layout: 'hbox',
  fieldDefaults: {
    labelAlign: 'left',
    msgTarget: 'side'
  },
  items: [{
    flex: 2,
    xtype: 'gridpanel',
    id: 'eventsGrid',
    store: Ext.data.StoreManager.lookup('eventsStore'),
    title:'Events',
    columns: [{
      text: 'id',
      dataIndex: 'id',
      hidden: true
    },{
      text: 'Name',
      dataIndex: 'name',
    },{
      text: 'Location',
      dataIndex: 'location',
    },{
      text: 'Category',
      dataIndex: 'category',
    },{
      text: 'Scheduled',
      dataIndex: 'scheduled',
			format: 'm/d/y g:i a',
			xtype: 'datecolumn',
    },{
      text: 'Registered',
      dataIndex: 'registered',
    },{
      text: 'Attended',
      dataIndex: 'attended',
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
    },{
      xtype: 'button',
      text: 'Duplicate Event',
      icon: '/img/icons/date_copy.png',
      handler: function() {
        var selected = this.up('grid').getSelectionModel().getLastSelected();
        selected.data.id = undefined;
        this.up('form').loadRecord(selected);
      }
    },{
      xtype: 'button',
      text: 'Delete Event',
      icon: '/img/icons/date_delete.png',
      handler: function() {
        var selected = this.up('grid').getSelectionModel().getLastSelected(),
        store = Ext.data.StoreManager.lookup('eventsStore');
        store.remove(selected);
        store.sync();
      }
    }],
    listeners: {
      itemdblclick: function(grid, record, item, index, e, eOpts) {
        var formPanel = this.up('form');
        var form = formPanel.getForm();
        Ext.getCmp('cat2Name').disable();
        Ext.getCmp('cat3Name').disable();
        if (record) {
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
        this.up('form').getForm().clearInvalid();
      }
    }
  }, {
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
    allowBlank: false
  },{
    fieldLabel: 'Location',
    name: 'location',
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
        if(newValue === 0) {
          if(record.data.address !== undefined) {
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
    fieldLabel: 'Seats',
    name: 'seats_available',
    xtype: 'numberfield',
    width: 120,
    minValue: 1,
    maxValue: 100,
    allowBlank: false
  },{
    fieldLabel: 'Scheduled',
    xtype: 'datetimefield',
    name: 'scheduled',
    allowBlank: false,
    timeFormat: 'g:i a',
    dateFormat: 'm/d/Y',
    dateConfig: {
      minValue: dt,
      submitFormat: 'Y-m-d'
    },
    timeConfig: {
      minValue: '7:00 am',
      maxValue: '10:00 pm',
      submitFormat: 'H:i:s'
    }
  },{
    fieldLabel: 'Duration in hours',
    xtype: 'numberfield',
    name: 'duration',
    minValue: 1,
    allowBlank: false,
    maxValue: 8,
    width: 110
  },{
    fieldLabel: 'Cat 1',
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
  }],
});

Ext.onReady(function(){
  Ext.getCmp('eventsForm').render('events');
});


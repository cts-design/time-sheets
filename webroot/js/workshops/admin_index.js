Ext.define('Workshop', {
  extend: 'Ext.data.Model',
  fields: [
    {name: 'id'},
    {name: 'name'},
    {name: 'description'},
    {name: 'location'},
    {name: 'scheduled', type: 'date', dateFormat: 'n/j h:ia'},
    {name: 'registered'},
    {name: 'seats_available'},
    {name: 'attended'},
    {name: 'Cat1-name', serverKey: 'cat_1'},
    {name: 'Cat2-name', serverKey: 'cat_2'},
    {name: 'Cat3-name', serverKey: 'cat_3'},
    {name: 'created', type: 'date', dateFormat: 'n/j h:ia'},
    {name: 'modified', type: 'date', dateFormat: 'n/j h:ia'}
  ]
});

Ext.create('Ext.data.Store', {
  model: 'Workshop',
  storeId: 'workshopsStore',
  pageSize: 10,
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/workshops/add/',
      read: '/admin/workshops',
      update: '/admin/workshops/edit/',
      destroy: '/admin/workshops/delete/'
    },
    reader: {
      type: 'json',
      root: 'workshops'
    },
    writer: {
      root: 'data[Workshop]',
      encode: true,
      writeAllFields: false,
      nameProperty: 'serverKey'
    },
    directionParam: 'direction',
    simpleSortMode: true
  },
  remoteSort: true,
  autoLoad: true,
  listeners: {
    write: function(store, operation, eOpts) {
      var responseTxt = Ext.JSON.decode(operation.response.responseText);
      if(!responseTxt.success || !operation.success ) {
        var msg = null;
        switch(operation.action) {
          case 'destroy' :
            msg = 'Unable to delete workshop.';
            break;
          case 'create' :
            msg = 'Unable to create workshop.';
            break;
          case 'update' :
            msg = 'Unable to update workshop.';
            break;
        }
        Ext.MessageBox.alert('Status', msg);
      }
      if(responseTxt.success) {
        Ext.MessageBox.hide();
        if(operation.action === 'create' || operation.action === 'update') {
          gridForm.getForm().reset();
          Ext.getCmp('cat2Name').disable();
          Ext.getCmp('cat3Name').disable();
          store.load();
        }
        if(operation.action === 'destroy') {
          gridForm.getForm().reset();
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

Ext.create('Ext.form.Panel', {
  id: 'workshopsForm',
  frame: true,
  bodyPadding: 5,
  width: 950,
  layout: 'column',
  fieldDefaults: {
    labelAlign: 'left',
    msgTarget: 'side'
  },
  items: [{
    columnWidth: 0.7,
    xtype: 'gridpanel',
    id: 'workshopsGrid',
    store: Ext.data.StoreManager.lookup('workshopsStore'),
    height: 315,
    title:'Workshops',
    columns: [{
      text: 'id',
      dataIndex: 'id',
      hidden: true
    },{
      text: 'Name',
      dataIndex: 'name'
    },{
      text: 'Location',
      dataIndex: 'location',
      minLength: 5
    },{
      text: 'Scheduled',
      dataIndex: 'scheduled',
      flex: 1
    },{
      text: 'Seats Available',
      dataIndex: 'seats_available',
      flex: 1
    },{
      text: 'Registered',
      dataIndex: 'registered',
      flex: 1
    },{
      text: 'Attended',
      dataIndex: 'attended',
      flex: 1
    }],
    tbar: [{xtype: 'tbfill'},{
      xtype: 'button',
      text: 'New Workshop',
      icon: '/img/icons/add.png',
      handler: function() {
        this.up('form').getForm().reset();
        Ext.getCmp('cat2Name').disable();
        Ext.getCmp('cat3Name').disable();
        this.up('grid').getSelectionModel().deselectAll();
      }
    },{
      xtype: 'button',
      text: 'Duplicate Workshop',
      icon: '',
      handler: function() {
        Ext.MessageBox.confirm('Confirm', 'This does not work yet', function(id){
        });
      }
    }],
    dockedItems: [{
      xtype: 'pagingtoolbar',
      store: Ext.data.StoreManager.lookup('workshopsStore'),
      dock: 'bottom',
      displayInfo: true
    }],
    listeners: {
      selectionchange: function(model, records) {
        Ext.getCmp('cat2Name').disable();
        Ext.getCmp('cat3Name').disable();
        if (records[0]) {
          var vals = {
            name: records[0].data.name,
            number: records[0].data.number
          };
          this.up('form').getForm().loadRecord(records[0]);
        }
        this.up('form').getForm().clearInvalid();
      }
    }
  }, {
  columnWidth: 0.3,
  margin: '0 0 0 10',
  padding: 10,
  xtype: 'fieldset',
  frame: true,
  title:'Add / Edit Form',
  defaults: {
    width: 245,
    labelWidth: 50
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
    xtype: 'textarea'
  },{
    fieldLabel: 'Location'
  },{
    fieldLabel: 'Seats'
  },{
    fieldLabel: 'Scheduled',
    xtype: 'xdatetime'
  },{
    fieldLabel: 'Cat 1',
    name: 'Cat1-name',
    id: 'cat1Name',
    store: Ext.data.StoreManager.lookup('cat1Store'),
    displayField: 'name',
    valueField: 'id',
    listConfig: {
      getInnerTpl: function() {
        return '<div>{img}{name}</div>';
      }
    },
    queryMode: 'local',
    xtype: 'combo',
    value: null,
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
    name: 'Cat2-name',
    id: 'cat2Name',
    xtype: 'combo',
    disabled: true,
    store: Ext.data.StoreManager.lookup('cat2Store'),
    displayField: 'name',
    valueField: 'id',
    queryMode: 'local',
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
    name: 'Cat3-name',
    id: 'cat3Name',
    xtype: 'combo',
    store: Ext.data.StoreManager.lookup('cat3Store'),
    disabled: true,
    displayField: 'name',
    valueField: 'id',
    queryMode: 'local',
    value: null,
    listConfig: {
      getInnerTpl: function() {
        return '<div>{img}{name}</div>';
      }
    },
    allowBlank: false
    },{
      xtype: 'filefield',
      allowBlank: true,
      fieldLabel: 'Media Upload',
      id: 'mediaUploadField',
      name: 'media'
    }]
  }],
  buttons: [{
    text: 'Save',
    formBind: true,
    handler: function() {
      var form = this.up('form').getForm();
      var vals = form.getValues();
      if(form.isValid()) {
        var workshop;
        if(vals.id !== '') {
          workshop = store.getById(parseInt(vals.id, 10));
          workshop.beginEdit();
          workshop.set(vals);
          workshop.endEdit();
        }
        else {
          workshop = Ext.create('Workshop', form.getValues());
          store.add(workshop);
        }
        store.sync();
      }
    }
  }],
});

Ext.onReady(function(){
  Ext.getCmp('workshopsForm').render('workshops');
});


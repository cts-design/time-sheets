var loaded;
var itemsPerPage = 20;
Ext.state.Manager.setProvider(new Ext.state.CookieProvider({
    expires: new Date(new Date().getTime()+(1000*60*60*24*365)) // 1 year
}));

Ext.define('EcourseResponse', {
  extend: 'Ext.data.Model',
  fields : ['id', 'User-lastname', {
    name : 'created',
    type : 'date',
    dateFormat : 'Y-m-d H:i:s'
  }, {
    name : 'modified',
    type : 'date',
    dateFormat : 'Y-m-d H:i:s'
  }, 'actions']
});

var ecourseResponseProxy = Ext.create('Ext.data.proxy.Ajax', {
  url : '/admin/ecourse_responses/index/' + ecourseId,
  reader: {
    type: 'json',
    root : 'responses',
    totalProperty: 'totalCount'
  },
  extraParams : {
    status: 'incomplete',
    fromDate : '',
    toDate : '',
    id : '',
    searchType : '',
    search : ''
  },
  directionParam : 'direction',
  simpleSortMode: true
});

Ext.create('Ext.data.Store', {
  id: 'ecourseResponseStore',
  model: 'EcourseResponse',
  proxy: ecourseResponseProxy,
  pageSize: itemsPerPage,
  remoteSort: true,
  listeners: {
    load: function() {
      Ext.state.Manager.set('ecourseResponseGrid'+ecourseId, {currentPage: this.currentPage});
    }
  }
});

Ext.define('Atlas.grid.EcourseResponsePanel', {
  extend: 'Ext.grid.Panel',
  forceFit : true,
  height : 300,
  frame : false,
  store: 'ecourseResponseStore',
  columns: [{
    text : 'Id',
    dataIndex : 'id',
    width : 30,
    sortable : true
  }, {
    text : 'Customer',
    dataIndex : 'User-lastname',
    width : 150,
    sortable : true
  }, {
    text : 'Created',
    dataIndex : 'created',
    xtype : 'datecolumn',
    format : 'm/d/Y g:i a',
    sortable : true
  }, {
    text : 'Modified',
    dataIndex : 'modified',
    xtype : 'datecolumn',
    format : 'm/d/Y g:i a',
    sortable : true
  }, {
    text : 'Actions',
    dataIndex : 'actions',
    width: 150
  }],
  viewConfig : {
    deferEmptyText: false,
    loadMask: true,
    emptyText : 'No responses at this time.'
  },
  selType: 'rowmodel',
  listeners: {
    select: function(sm, record, index, eOpts) {
      responseId = record.data.id;
    },
    itemcontextmenu: function(view, rec, node, index, e){
      e.stopEvent();
      Ext.getCmp('contextMenu').showAt(e.getXY());
      responseId = rec.data.id;
    }
  }
});

Ext.create('Ext.menu.Menu', {
  id: 'contextMenu',
  title: 'Response Actions',
  bodyPadding: 5,
  items:[{
    xtype: 'button',
    text: 'Reset Response',
    iconCls: 'icon_arrow_redo',
    handler: function() {
      Ext.Ajax.request({
        url: '/admin/ecourse_responses/reset',
        params: {
          id: responseId
        },
        success: function(response) {
          var text = Ext.JSON.decode(response.responseText);
          var title = 'Success'; 
          if(!text.success) {
            title = 'Failure';
          }
          Ext.Msg.alert(title, text.message);
        }
      });
    }
  }]
});


Ext.create('Atlas.grid.EcourseResponsePanel', {
  title : 'Incomplete',
  id: 'incompleteResponseGrid',
  bbar: Ext.create('Ext.PagingToolbar', {
    store: 'ecourseResponseStore',
    displayInfo: true,
    displayMsg: 'Displaying responses {0} - {1} of {2}',
    emptyMsg: "No responses to display"
  })
});

Ext.create('Atlas.grid.EcourseResponsePanel', {
  title : 'Completed',
  id: 'completedResponseGrid',
  columns: [{
    text : 'Id',
    dataIndex : 'id',
    width : 30,
    sortable : true
  }, {
    text : 'Customer',
    dataIndex : 'User-lastname',
    width : 150,
    sortable : true
  }, {
    text : 'Created',
    dataIndex : 'created',
    xtype : 'datecolumn',
    format : 'm/d/Y g:i a',
    sortable : true
  }, {
    text : 'Modified',
    dataIndex : 'modified',
    xtype : 'datecolumn',
    format : 'm/d/Y g:i a',
    sortable : true
  }, {
    text : 'Actions',
    dataIndex : 'actions',
    width: 150
  }],
  bbar: Ext.create('Ext.PagingToolbar', {
    store: 'ecourseResponseStore',
    displayInfo: true,
    displayMsg: 'Displaying responses {0} - {1} of {2}',
    emptyMsg: "No responses to display"
  })
});

Ext.create('Ext.tab.Panel', {
  id: 'ecourseResponseTabs',
  region : 'center',
  width : 800,
  activeTab : 0,
  frame : true,
  stateful: true,
  stateId: 'ecourseResponseTabs' + ecourseId,
  stateEvents: ['tabchange'],
  items : [
    'incompleteResponseGrid',
    'completedResponseGrid',
  ],
  getState: function() {
    return { activeTab: this.getActiveTab().id };
  },
  applyState: function(state) {
    if(state.activeTab !== undefined) {
      this.setActiveTab(state.activeTab);
    }
  },
  listeners: {
    tabchange: function(tabPanel, newCard, oldCard, eOpts) {
      switch (newCard.title) {
        case 'Incomplete':
          ecourseResponseProxy.extraParams.status= 'incomplete';
          break;
        case 'Completed':
          ecourseResponseProxy.extraParams.status= 'completed';
          break;
      }
      newCard.getStore().loadPage(1, {start: 0, limit: itemsPerPage}); //was 10
    },
    beforeadd: function(container, component, index) {
      if(this.items.length === 5) {
        return false;
      }
    }
  }
});

Ext.create('Ext.Toolbar', {
  id: 'dateSearchToolbar',
  width: 275,
  items: [{
    text: 'Today',
    handler: function() {
      var dt = new Date();
      Ext.getCmp('fromDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
      Ext.getCmp('toDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
    }
  }, {
    xtype: 'tbseparator'
  }, {
    text: 'Yesterday',
    handler: function() {
      var dt = new Date();
      dt.setDate(dt.getDate() - 1);
      Ext.getCmp('fromDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
      Ext.getCmp('toDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
    }
  }, {
    xtype: 'tbseparator'
  }, {
    text: 'Last Week',
    handler: function() {
      var dt = new Date();
      dt.setDate(dt.getDate() - (parseInt(Ext.Date.format(dt, 'N'), 10) + 6));
      Ext.getCmp('fromDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
      dt.setDate(dt.getDate() + 6);
      Ext.getCmp('toDate').setValue(Ext.Date.format(dt, 'm/d/Y'));
    }
  }, {
    xtype: 'tbseparator'
  }, {
    text: 'Last Month',
    handler: function() {
      var now = new Date(),
        firstDayPrevMonth = new Date(now.getFullYear(), now.getMonth() - 1, 1),
        lastDayPrevMonth = Ext.Date.getLastDateOfMonth(firstDayPrevMonth);
      Ext.getCmp('fromDate').setValue(Ext.Date.format(firstDayPrevMonth, 'm/d/Y'));
      Ext.getCmp('toDate').setValue(Ext.Date.format(lastDayPrevMonth, 'm/d/Y'));
    }
  }]
});

Ext.define('SearchType', {
  extend: 'Ext.data.Model',
  fields : ['type', 'label']
});
  
Ext.create('Ext.data.Store', {
  id: 'searchTypeStore',
  model: 'SearchType',
  data : [
    {type: 'firstname', label: 'First Name'},
    {type: 'lastname', label : 'Last Name'},
    {type: 'last4', label: 'Last 4 SSN'},
    {type: 'fullssn', label: 'Full SSN'}
  ]
});

Ext.create('Ext.form.Panel', {
  frame : true,
  fieldDefaults:{
    labelWidth: 50
  },
  collapsible : true,
  height : 190,
  region : 'north',
  title : 'Filters',
  id : 'ecourseResponseSearch',
  items : [{
    layout : 'column',
    xtype: 'container',
    bodyStyle: 'margin: 0 0 0 7px',
    items : [{
      layout : 'anchor',
      columnWidth : 0.333,
      height : 120,
      frame : true,
      bodyStyle : 'padding: 0 10px',
      title : 'Dates',
      items: [{
        xtype: 'datefield',
        id: 'fromDate',
        fieldLabel: 'From',
        name: 'fromDate',
        width: 200
      }, {
        xtype: 'datefield',
        fieldLabel: 'To',
        name: 'toDate',
        id: 'toDate',
        width: 200
      }, 'dateSearchToolbar']
    }, {
      layout : 'anchor',
      columnWidth : 0.333,
      height : 120,
      frame : true,
      bodyStyle : 'padding: 0 10px',
      title : 'Response',
      items : [{
        xtype : 'textfield',
        fieldLabel : 'Id',
        name: 'id',
        width : 200
      }]
    },{
      layout : 'anchor',
      columnWidth : 0.333,
      height : 120,
      frame : true,
      bodyStyle : 'padding: 0 10px',
      title : 'Customer',
      items : [{
        xtype : 'combo',
        fieldLabel : 'Search Type',
        name : 'searchType',
        store : 'searchTypeStore',
        queryMode: 'local',
        valueField: 'type',
        displayField: 'label',
        width: 250
      },
      {
        xtype : 'textfield',
        fieldLabel : 'Search',
        name : 'search',
        width : 250
      }]
    }]
  }],
  fbar : [{
    text : 'Search',
    id : 'docSearch',
    icon : '/img/icons/find.png',
    handler : function() {
      var f = Ext.getCmp('ecourseResponseSearch').getForm(), vals = f.getValues();
      Ext.iterate(vals, function (key, value){
        ecourseResponseProxy.extraParams[key] = value;
      });
      Ext.getCmp('ecourseResponseTabs').getActiveTab().getStore().loadPage(1, {start: 0, limit: itemsPerPage}); //was 10
    }
  }, {
    text : 'Reset',
    icon : '/img/icons/arrow_redo.png',
    handler : function() {
      var f = Ext.getCmp('ecourseResponseSearch').getForm();
      f.reset();
      ecourseResponseProxy.extraParams.id = undefined;
      ecourseResponseProxy.extraParams.search = undefined;
      ecourseResponseProxy.extraParams.toDate = undefined;
      ecourseResponseProxy.extraParams.fromDate = undefined;
      Ext.getCmp('ecourseResponseTabs').getActiveTab().getStore().loadPage(1, {start: 0, limit: itemsPerPage}); //was 10
    }
  }]
});

Ext.onReady(function() {

	Ext.QuickTips.init();
		
	Ext.create('Ext.panel.Panel', {
		frame : true,
		renderTo: 'ecourseResponseTabs',
		title: ecourseName,
		width : 950,
		height : 600,
		layout : 'border',
		items : ['ecourseResponseTabs', 'ecourseResponseSearch']
	});

	var state = Ext.state.Manager.get('ecourseResponseGrid'+ecourseId),
		page = 1;
	if(state) {
		page = state.currentPage;
	}
	if(!loaded) {
	Ext.getCmp('ecourseResponseTabs').getActiveTab().getStore().loadPage(page, {start: 0, limit: itemsPerPage}); //was 10
		loaded = 1;
	}
});

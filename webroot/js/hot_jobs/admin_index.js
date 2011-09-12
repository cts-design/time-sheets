/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

var hotJobsPanel = {
  init: function() {
    this.initData();
    this.initGrid();

  },
  initData: function() {
    "use strict";

    var hotJobProxy = new Ext.data.HttpProxy({
      api: {
        create:  { url: '/admin/hot_jobs/create',  method: 'POST' },
        read:    { url: '/admin/hot_jobs/read',    method: 'GET'  },
        update:  { url: '/admin/hot_jobs/update',  method: 'POST' },
        destroy: { url: '/admin/hot_jobs/destroy', method: 'POST' }
      }
    }),

      hotJobFields = Ext.data.Record.create([
        { name: 'id', type: 'int' },
        'employer',
        'title',
        'description',
        'location',
        'contact',
        'url',
        'reference_number',
        'file',
        { name: 'created', type: 'date', dateFormat: 'Y-m-d H:i:s' },
        { name: 'modified', type: 'data', dateFormat: 'Y-m-d H:i:s' }
      ]),

      hotJobReader = new Ext.data.JsonReader({
        messageProperty: 'message',
        root: 'hot_jobs'
      }, hotJobFields),

      hotJobWriter = new Ext.data.JsonWriter();

    this.hotJobStore = new Ext.data.Store({
      storeId: 'hotJobStore',
      proxy: hotJobProxy,
      reader: hotJobReader,
      writer: hotJobWriter,
      autoSave: true
    });
  },
  initPanel: function() {
    "use strict";

    var hotJobGridView = new Ext.grid.GridView({ forceFit: true }),

      hotJobColModel = new Ext.grid.ColumnModel([
        { header: 'Id', sortable: false, dataIndex: 'id', hidden: true },
        { header: 'Employer', sortable: true, dataIndex: 'employer' },
        { header: 'Title', sortable: true, dataIndex: 'title' },
        { header: 'Description', sortable: false, dataIndex: 'description' },
        { header: 'Location', sortable: true, dataIndex: 'location' },
        { header: 'Contact', sortable: false, dataIndex: 'contact' },
        { header: 'Url', sortable: false, dataIndex: 'url' },
        { header: 'Reference Number', sortable: false, dataIndex: 'reference_number' },
        { header: 'File', sortable: false, dataIndex: 'file' },
        {
          header: 'Created',
          sortable: true,
          dataIndex: 'created',
          renderer: Ext.util.Format.dateRenderer()
        }
      ]),

      hotJobSelModel = new Ext.grid.RowSelectionModel({
        singleSelect: true
      }),

      hotJobGridToolbar = new Ext.Toolbar({
        items: [{
          text: 'New Hot Job',
          icon: '/img/icons/add.png',
          scope: this,
          handler: function() {
            alert('add hot job');
          }
        }, {
          text: 'Delete Hot Job',
          icon: '/img/icons/delete.png',
          disabled: true,
          scope: this,
          handler: function() {
            alert('delete hot job');
          }
        }]
      });

    this.hotJobGrid = new Ext.grid.GridPanel({
      tbar: hotJobGridToolbar,
      store: this.hotJobStore,
      cm: hotJobColModel,
      sm: hotJobSelModel,
      view: hotJobGridView,
      height: 175,
      frame: false,
      loadMask: true
    });
  }
};

Ext.onReady(function() {
  "use strict";

  Ext.QuickTips.init();
  hotJobsPanel.init();
});

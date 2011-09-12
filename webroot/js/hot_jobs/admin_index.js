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

  }
};

Ext.onReady(function() {
  "use strict";

  Ext.QuickTips.init();
  hotJobsPanel.init();
});

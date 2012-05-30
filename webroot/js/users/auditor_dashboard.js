// TODO remove jslint comments
/*jslint devel:true, maxerr: 150, indent: 2*/
/*global Ext,PDFObject,window*/
var AuditDashboard;
AuditDashboard = {
  auditStore: null,
  filedDocumentsStore: null,
  userStore: null,
  userTransactionStore: null,
  selectedAudit: null,
  selectedCustomer: null,
  timeout: null,
  viewport: null,

  init: function () {
    "use strict";

    Ext.define('Audit', {
      id: 'Audit',
      extend: 'Ext.data.Model',
      fields: [{
        name: 'id',
        type: 'int'
      }, 'name', {
        name: 'start_date',
        type: 'date',
        dateFormat: 'Y-m-d'
      }, {
        name: 'end_date',
        type: 'date',
        dateFormat: 'Y-m-d'
      }],
      proxy: {
        type: 'ajax',
        api: {
          read: '/admin/audits/read'
        },
        reader: {
          type: 'json',
          root: 'audits'
        }
      }
    });

    this.auditStore = Ext.create('Ext.data.Store', {
      storeId: 'AuditStore',
      autoLoad: true,
      model: 'Audit'
    });

    Ext.define('User', {
      id: 'User',
      extend: 'Ext.data.Model',
      fields: [{
        name: 'id',
        type: 'int'
      }, 'ssn', 'firstname', 'lastname'],
      proxy: {
        type: 'ajax',
        url: '/admin/audits/users',
        reader: {
          type: 'json',
          root: 'users'
        }
      }
    });

    this.userStore = Ext.create('Ext.data.Store', {
      storeId: 'UserStore',
      model: 'User'
    });

    Ext.define('FiledDocument', {
      id: 'FiledDocument',
      extend: 'Ext.data.Model',
      fields: [{
        name: 'id',
        type: 'int'
      }, 'cat_1', 'cat_2', 'cat_3', 'secure', 'secure_viewable', 'description', {
        name: 'created',
        type: 'date',
        dateFormat: 'Y-m-d H:i:s'
      }],
      proxy: {
        type: 'ajax',
        url: '/admin/audits/filed_docs',
        reader: {
          type: 'json',
          root: 'filed_docs'
        }
      }
    });

    this.filedDocumentStore = Ext.create('Ext.data.Store', {
      storeId: 'FiledDocumentStore',
      model: 'FiledDocument'
    });

    Ext.define('UserTransaction', {
      id: 'UserTransaction',
      extend: 'Ext.data.Model',
      fields: [{
        name: 'id',
        type: 'int'
      }, 'location', 'module', 'details', {
        name: 'created',
        type: 'date',
        dateFormat: 'Y-m-d H:i:s'
      }],
      proxy: {
        type: 'ajax',
        url: '/admin/audits/user_transactions',
        reader: {
          type: 'json',
          root: 'user_transactions'
        }
      }
    });

    this.userTransactionStore = Ext.create('Ext.data.Store', {
      storeId: 'UserTransactionStore',
      model: 'UserTransaction'
    });

    this.buildInterface();
  },

  buildInterface: function () {
    "use strict";

    this.viewport = Ext.create('Ext.container.Viewport', {
      layout: 'border',
      items: [{
        xtype: 'panel',
        layout: {
          align: 'stretch',
          type: 'vbox'
        },
        region: 'center',
        items: [{
          xtype: 'tabpanel',
          activeTab: 0,
          collapsible: true,
          height: 210,
          title: 'Customer Documents & Activities',
          items: [{
            xtype: 'gridpanel',
            store: 'FiledDocumentStore',
            title: 'Documents',
            columns: [{
              dataIndex: 'id',
              hidden: true,
              text: 'Id'
            }, {
              dataIndex: 'secure',
              text: '',
              renderer: function (value, meta, rec) {
                if (value && !rec.data.secure_viewable) {
                  return '<img src="/img/icons/lock.png" />';
                } else if (value && rec.data.secure_viewable) {
                  return '<img src="/img/icons/lock_open.png" />';
                }

                return '';
              },
              width: 50
            }, {
              dataIndex: 'cat_1',
              flex: 1,
              text: 'Category 1'
            }, {
              dataIndex: 'cat_2',
              flex: 1,
              text: 'Category 2'
            }, {
              dataIndex: 'cat_3',
              flex: 1,
              text: 'Category 3'
            }, {
              dataIndex: 'description',
              flex: 1,
              text: 'Notes/Other'
            }, {
              xtype: 'datecolumn',
              dataIndex: 'created',
              hidden: true,
              text: 'Date'
            }],
            listeners: {
              itemclick: {
                fn: this.documentGridItemClicked,
                scope: this
              }
            },
            viewConfig: {}
          }, {
            xtype: 'gridpanel',
            store: 'UserTransactionStore',
            title: 'Activities',
            columns: [{
              dataIndex: 'id',
              hidden: true,
              text: 'Id'
            }, {
              dataIndex: 'location',
              flex: 1,
              text: 'Location'
            }, {
              dataIndex: 'module',
              flex: 1,
              text: 'Module'
            }, {
              dataIndex: 'details',
              flex: 1,
              text: 'Details'
            }, {
              xtype: 'datecolumn',
              dataIndex: 'created',
              text: 'Date'
            }],
            viewConfig: {}
          }]
        }, {
          id: 'customerDocumentView',
          collapsible: true,
          flex: 1,
          height: 400,
          layout: 'fit',
          title: 'Document View',
          items: [{
            xtype: 'component',
            html: '<p>No document currently loaded</p>',
            id: 'customerDocumentPDF',
            layout: 'fit'
          }]
        }]
      }, {
        xtype: 'panel',
        layout: 'accordion',
        region: 'west',
        width: 300,
        items: [{
          xtype: 'gridpanel',
          collapsible: true,
          height: 150,
          id: 'auditPanel',
          store: 'AuditStore',
          title: 'Active Audits',
          columns: [{
            xtype: 'gridcolumn',
            dataIndex: 'id',
            hidden: true,
            text: 'Id'
          }, {
            dataIndex: 'name',
            flex: 1,
            text: 'Audit Name'
          }, {
            xtype: 'datecolumn',
            dataIndex: 'start_date',
            text: 'Start Date'
          }, {
            xtype: 'datecolumn',
            dataIndex: 'end_date',
            text: 'End Date'
          }],
          listeners: {
            itemclick: {
              fn: this.auditGridItemClicked,
              scope: this
            }
          }
        }, {
          xtype: 'gridpanel',
          id: 'customerPanel',
          store: 'UserStore',
          title: 'Customers',
          columns: [{
            dataIndex: 'id',
            hidden: true,
            text: 'Id'
          }, {
            dataIndex: 'ssn',
            text: 'SSN'
          }, {
            dataIndex: 'lastname',
            flex: 1,
            text: 'Last Name'
          }, {
            dataIndex: 'firstname',
            flex: 1,
            text: 'First Name'
          }],
          viewConfig: {},
          listeners: {
            itemclick: {
              fn: this.customerGridItemClicked,
              scope: this
            }
          }
        }]
      }]
    });

    this.startTimeoutDelay();
  },

  auditGridItemClicked: function (view, rec) {
    "use strict";
    var auditPanel = Ext.getCmp('auditPanel');

    this.selectedAudit = rec;

    this.userStore.load({
      params: {
        audit_id: rec.data.id
      }
    });

    auditPanel.collapse();
  },

  customerGridItemClicked: function (view, rec) {
    "use strict";

    this.selectedCustomer = rec;

    this.filedDocumentStore.load({
      params: {
        customer_id: rec.data.id
      }
    });

    this.userTransactionStore.load({
      params: {
        customer_id: rec.data.id
      }
    });
  },

  documentGridItemClicked: function (view, rec) {
    "use strict";

    if (rec.data.secure && !rec.data.secure_viewable) {
      Ext.Msg.alert('Secure Document', 'You do not have permissions to view this document');
      return;
    }

    if ((rec.data.secure && rec.data.secure_viewable) || !rec.data.secure) {
      this.embedDocument(rec.data.id);
    }
  },

  embedDocument: function (id) {
    "use strict";
    var pdf,
      loadMask = new Ext.LoadMask(Ext.getBody(), { msg: 'Loading Document...' });

    loadMask.show();

    pdf = new PDFObject({
      url: '/auditor/filed_documents/view/' + id,
      pdfOpenParams: {
        scrollbars: '1',
        toolbar: '1',
        statusbar: '1',
        messages: '0',
        navpanes: '0',
        view: 'FitH'
      }
    }).embed('customerDocumentPDF');

    loadMask.hide();
  },

  startTimeoutDelay: function () {
    "use strict";

    if (!this.timeout) {
      this.timeout = new Ext.util.DelayedTask(function () {
        window.location = '/users/logout';
      });

      this.timeout.delay(300000);
    }

    Ext.getBody().on('mousemove', function () {
      this.timeout.delay(300000);
    }, this);
  }
};


Ext.onReady(AuditDashboard.init, AuditDashboard);

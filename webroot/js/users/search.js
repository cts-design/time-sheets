/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

var searchPanel = {
  init: function() {
    var windowUrl = window.location.pathname.replace(/\/page\:\d+/g, '');

    this.form = new Ext.FormPanel({
      id: 'searchFormPanel',
      renderTo: 'searchForm',
      title: 'Search',
      frame: true,
      collapsible: true,
      standardSubmit: true,
      url: windowUrl,
      bodyStyle: 'padding: 5px',
      defaults: {
      },
      items: [{
        layout: 'column',
        defaults: {
          columnWidth: 0.33,
          layout: 'form',
          border: false,
          bodyStyle: 'padding: 0 18px'
        },
        items: [{
          defaults: {
            anchor: '100%',
            hideLabel: true
          },
          items: [{
            html: 'Search for:'
          },{
            xtype: 'combo',
            mode: 'local',
            id: 'SearchBy1',
            name: 'search_by1',
            hiddenName: 'search_by1',
            store: [['firstname', 'First Name'], ['lastname', 'Last Name'], ['fullssn', 'Full SSN'], ['last4', 'Last 4 SSN']],
            triggerAction: 'all',
            allowBlank: false,
            listeners: {
              select: function(combo, record, index) {
                var searchTerm1 = Ext.getCmp('SearchTerm1'),
                searchFullSsn1  = Ext.getCmp('SearchFullSsn1'),
                searchLast41    = Ext.getCmp('SearchLast41');

                if (record.data.field1 === 'firstname' || record.data.field1 === 'lastname') {
                  searchTerm1.enable().show()
                  searchFullSsn1.disable().hide();
                  searchLast41.disable().hide();
                }
                if (record.data.field1 === 'fullssn') {
                  searchTerm1.disable().hide()
                  searchFullSsn1.enable().show();
                  searchLast41.disable().hide();
                }
                if (record.data.field1 === 'last4') {
                  searchTerm1.disable().hide()
                  searchFullSsn1.disable().hide();
                  searchLast41.enable().show();
                }
              }
            }
          },{
            xtype: 'combo',
            mode: 'local',
            id: 'SearchBy2',
            name: 'search_by2',
            hiddenName: 'search_by2',
            store: [['firstname', 'First Name'], ['lastname', 'Last Name'], ['fullssn', 'Full SSN'], ['last4', 'Last 4 SSN']],
            triggerAction: 'all',
            listeners: {
              select: function(combo, record, index) {
                var searchTerm2 = Ext.getCmp('SearchTerm2'),
                searchFullSsn2  = Ext.getCmp('SearchFullSsn2'),
                searchLast42    = Ext.getCmp('SearchLast42'),
                whereIs2        = Ext.getCmp('SearchScope2');

                if (record.data.field1 === 'firstname' || record.data.field1 === 'lastname') {
                  searchTerm2.enable().show().allowBlank = false;
                  searchFullSsn2.disable().hide();
                  searchLast42.disable().hide();
                }
                if (record.data.field1 === 'fullssn') {
                  searchTerm2.disable().hide();
                  searchFullSsn2.enable().show().allowBlank = false;
                  searchLast42.disable().hide();
                }
                if (record.data.field1 === 'last4') {
                  searchTerm2.disable().hide();
                  searchFullSsn2.disable().hide();
                  searchLast42.enable().show().allowBlank = false;
                }

                whereIs2.allowBlank = false;
              },
              change: function(combo, newValue, oldValue) {
                var searchTerm2 = Ext.getCmp('SearchTerm2'),
                searchFullSsn2  = Ext.getCmp('SearchFullSsn2'),
                searchLast42    = Ext.getCmp('SearchLast42'),
                whereIs2        = Ext.getCmp('SearchScope2');

                searchTerm2.allowBlank = true;
                searchFullSsn2.allowBlank = true;
                searchLast42.allowBlank = true;
                whereIs2.allowBlank = true;
              }
            }
          }]
        },{
          defaults: {
            anchor: '100%',
            hideLabel: true
          },
          items: [{
            html: 'Where is:'
          },{
            xtype: 'combo',
            store: ['containing','matching exactly'],
            id: 'SearchScope1',
            name: 'search_scope1',
            triggerAction: 'all',
            allowBlank: false,
            listeners: {
              select: function(combo, record, index) {
                if (record.data.field1 === 'containing') {
                  Ext.getCmp('SearchFullSsn1').minLength = 3;
                  Ext.getCmp('SearchLast41').minLength = 1;
                }
                if (record.data.field1 === 'matching exactly') {
                  Ext.getCmp('SearchFullSsn1').minLength = 9;
                  Ext.getCmp('SearchLast41').minLength = 4;
                }
              }
            }
          },{
            xtype: 'combo',
            store: ['containing','matching exactly'],
            id: 'SearchScope2',
            name: 'search_scope2',
            triggerAction: 'all',
            listeners: {
              select: function(combo, record, index) {
                if (record.data.field1 === 'containing') {
                  Ext.getCmp('SearchFullSsn2').minLength = 3;
                  Ext.getCmp('SearchLast42').minLength = 1;
                }
                if (record.data.field1 === 'matching exactly') {
                  Ext.getCmp('SearchFullSsn2').minLength = 9;
                  Ext.getCmp('SearchLast42').minLength = 4;
                }
              }
            }
          }]
        },{
          defaults: {
            anchor: '100%',
            hideLabel: true
          },
          items: [{
            html: 'Search term:'
          },{
            xtype: 'textfield',
            id: 'SearchTerm1',
            name: 'search_term1',
            allowBlank: false
          },{
            xtype: 'textfield',
            id: 'SearchFullSsn1',
            name: 'search_term1',
            hidden: true,
            disabled: true,
            allowBlank: false,
            maxLength: 9
          },{
            xtype: 'textfield',
            id: 'SearchLast41',
            name: 'search_term1',
            hidden: true,
            disabled: true,
            allowBlank: false,
            maxLength: 4
          },{
            xtype: 'textfield',
            id: 'SearchTerm2',
            name: 'search_term2'
          },{
            xtype: 'textfield',
            id: 'SearchFullSsn2',
            name: 'search_term2',
            hidden: true,
            maxLength: 9,
            disabled: true
          },{
            xtype: 'textfield',
            id: 'SearchLast42',
            name: 'search_term2',
            hidden: true,
            maxLength: 4,
            disabled: true
          }]
        }]
      }],
      buttons: [{
        icon:  '/img/icons/find.png',
        text: 'Search',
        handler: function() {
          var fp = Ext.getCmp('searchFormPanel'),
          form = fp.getForm();

          if (form.isValid()) {
            form.submit();
          }
        }
      },{
        text: 'Reset',
        icon:  '/img/icons/arrow_redo.png',
        handler: function() {
          var fp = Ext.getCmp('searchFormPanel'),
          form = fp.getForm();

          form.reset();
        }
      }],
      listeners: {
        afterrender: function (panel) {
          console.log(panel);
          var form = panel.getForm();

          form.setValues({
            SearchBy1: search_by1,
            SearchBy2: search_by2,
            SearchScope1: search_scope1,
            SearchScope2: search_scope2,
            SearchTerm1: search_term1,
            SearchTerm2: search_term2
          });

          form.clearInvalid();
        }
      }
    });
  }
};

Ext.onReady(function() {
  Ext.QuickTips.init();
  searchPanel.init();
});

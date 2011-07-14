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
            allowBlank: false
          },{
            xtype: 'combo',
            mode: 'local',
            id: 'SearchBy2',
            name: 'search_by2',
            hiddenName: 'search_by2',
            store: [['firstname', 'First Name'], ['lastname', 'Last Name'], ['fullssn', 'Full SSN'], ['last4', 'Last 4 SSN']],
            triggerAction: 'all'
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
            allowBlank: false
          },{
            xtype: 'combo',
            store: ['containing','matching exactly'],
            id: 'SearchScope2',
            name: 'search_scope2',
            triggerAction: 'all'
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
            id: 'SearchTerm2',
            name: 'search_term2'
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
  searchPanel.init();
});

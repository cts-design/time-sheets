/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

var searchPanel = {
  init: function() {
    this.form = new Ext.FormPanel({
      id: 'searchFormPanel',
      renderTo: 'searchForm',
      title: 'Search',
      frame: true,
      collapsible: true,
      standardSubmit: true,
      url: window.location.pathname,
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
            xtype: 'combo',
            store: ['First Name', 'Last Name', 'SSN'],
            name: 'search_by1'
          },{
            xtype: 'combo',
            store: ['First Name', 'Last Name', 'SSN'],
            name: 'search_by2'
          }]
        },{
          defaults: {
            anchor: '100%',
            hideLabel: true
          },
          items: [{
            xtype: 'combo',
            store: ['containing','matching exactly'],
            name: 'search_scope1'
          },{
            xtype: 'combo',
            store: ['containing','matching exactly'],
            name: 'search_scope2'
          }]
        },{
          defaults: {
            anchor: '100%',
            hideLabel: true
          },
          items: [{
            xtype: 'textfield',
            name: 'search_term1'
          },{
            xtype: 'textfield',
            name: 'search_term2'
          }]
        }]
      }],
      buttons: [{
        text: 'Search',
        handler: function() {
          var fp = Ext.getCmp('searchFormPanel'),
          form = fp.getForm();

          form.submit();
        }
      }]
    });
  }
};

Ext.onReady(function() {
  searchPanel.init();
});

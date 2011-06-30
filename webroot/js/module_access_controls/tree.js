/*
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */

moduleAccessControl = {
  init: function() {
    var loadingMask = new Ext.LoadMask('moduleAccessControlPanel', {
      msg: 'Loading...'
    });

    this.moduleAccessControlPanel = new Ext.tree.TreePanel({
      renderTo: 'moduleAccessControlPanel',
      title: 'Module Access Control',
      height: 300,
      useArrows: true,
      autoScroll: true,
      animate: true,
      enableDD: false,
      containerScroll: true,
      rootVisible: false,
      frame: true,
      dataUrl: '/admin/module_access_controls/read',
      root: {
        nodeType: 'async'
      },

      listeners: {
        beforeload: function() {
          loadingMask.show();
        },
        load: function() {
          loadingMask.hide();
        },
        checkchange: function(node, checked) {
          if (checked) {
            // send activated
            Ext.Ajax.request({
              url: '/admin/module_access_controls/update',
              method: 'POST',
              params: {
                module: node.text,
                state: 0
              },
              failure: function(response, opts) {
                Ext.Msg.alert('Error', 'Request failed with status: ' + response.status);
              }
            });
          } else {
            // send deactivated
            Ext.Ajax.request({
              url: '/admin/module_access_controls/update',
              method: 'POST',
              params: {
                module: node.text,
                state: 1
              },
              failure: function(response, opts) {
                Ext.Msg.alert('Error', 'Request failed with status: ' + response.status);
              }

            });
          }
        }
      }
    });
  }
};

Ext.onReady(function() {
  moduleAccessControl.init();
});

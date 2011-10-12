/*
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */

moduleAccessControl = {
  init: function() {
    var macStore;
    
    Ext.define('ModuleAccessControl', {
    	extend: 'Ext.data.Model',
    	fields: [ 'text' ]
    });
    
    macStore = Ext.create('Ext.data.TreeStore', {
    	model: 'ModuleAccessControl',
    	root: {
    		text: 'Modules',
    		expanded: true
    	},
    	proxy: {
    		type: 'ajax',
    		url: '/admin/module_access_controls/read'
    	}
    });

    this.moduleAccessControlPanel = Ext.create('Ext.tree.Panel', {
      renderTo: 'moduleAccessControlPanel',
      title: 'Module Access Control',
      store: macStore,
      height: 550,
      autoScroll: true,
      rootVisible: false,
      frame: true,

      listeners: {
        checkchange: function(node, checked) {
          if (checked) {
            // send activated
            Ext.Ajax.request({
              url: '/admin/module_access_controls/update',
              method: 'POST',
              params: {
                module: node.data.text,
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
                module: node.data.text,
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

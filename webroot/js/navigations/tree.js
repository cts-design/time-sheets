/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
if (typeof console == "undefined") {
    window.console = {
        log: function () {}
    };
}

Ext.define('BCToolkit.window.GrowlNotification', {
  extend: 'Ext.window.Window',
  requires: ['Ext.window.Window'],
  alias: 'widget.growlnotification',

  title: 'Notification',
  icon: false,

  hide: function() {},

  initComponent: function() {
    var me = this;

    me.callParent(arguments);
    me.addEvents(
      /**
       * @event click
       * Fires after the notification has been clicked
       * @param {BCToolkit.window.GrowlNotification} this
       */
      'click',

      /**
       * @event close
       * Fires after the notification has been closed
       * @param {BCToolkit.window.GrowlNotification} this
       */
       'close'
    );
  }
});

Ext.onReady(function() {
	Ext.Compat.showErrors = true;
	Ext.QuickTips.init();
	Ext.BLANK_IMAGE_URL = "/img/ext/default/s.gif";
	
    var win; // holds the Ext.Window object
    var getNodesUrl = '/admin/navigations/get_nodes/',
    addNodeUrl = '/admin/navigations/add_node/',
    reorderUrl = '/admin/navigations/reorder/',
    reparentUrl = '/admin/navigations/reparent/',
    deleteNodeUrl = '/admin/navigations/delete_node/';
    // track what nodes are moved and send to server to save
    var oldPosition = null,
    oldNextSibling = null;
    
    Ext.define('Page', {
    	extend: 'Ext.data.Model',
    	fields: [ 'title', 'slug' ]
    });
    
    var pageStore = Ext.create('Ext.data.Store', {
    	model: 'Page',
    	storeId: 'pageStore',
    	proxy: {
    		type: 'ajax',
    		reader: {
    			type:'json',
    			root: 'pages'
    		},
    		url: '/admin/pages/get_short_list'
    	},
    	autoLoad: true
    });
	
	var cmsCombo = new Ext.form.ComboBox({
		store: pageStore,
		queryMode: 'local',
		name: 'cmscombo',
		triggerAction: 'all',
		typeAhead: true,
	    valueField: 'title',
	    displayField: 'title',
	    fieldLabel: 'Use an Existing CMS Page',
	    disabledClass: 'test',
	    listeners: {
	    	select: function(combo, record, index) {
	    		var f = formPanel.getForm();
	    		f.setValues({
	    			name: record[0].data.title,
	    			link: '/pages/' + record[0].data.slug
	    		});
	    	}
	    }
	});
    
	// form to place on the window
	var formPanel = new Ext.FormPanel({
		defaultType: 'field',
		fieldDefaults: {
			labelWidth: 150,
      bodyStyle: 'margin: 10px'
		},
		autoScroll: true,
		id: 'formpanel',
		frame: false,
		items: [
		cmsCombo,
		{
			xtype: 'textfield',
			fieldLabel: 'Name',
			name: 'name',
			allowBlank: false
		},{
			xtype: 'textfield',
			fieldLabel: 'URL',
			name: 'link',
			allowBlank: false,
		}],
		buttons: [{
			id: 'savebtn',
			text: 'Save',
			handler: function(b, e) {
				var submitUrl = '/admin/navigations/create',
				  f = formPanel.getForm(),
				  vals = f.getValues(),
          root = tree.getRootNode(),
          selectedNode = tree.getSelectionModel().getSelection(),
          parentId,
          parent;

				if (f.isValid()) {
          if (selectedNode.length === 0 || selectedNode[0].data.root) {
            parent = root.childNodes[0];
          } else if (selectedNode[0].data.leaf && selectedNode[0].data.depth > 2) {
              Ext.Msg.alert('Cannot add link', 'Links cannot be nested that deep');
          } else {
            parent = selectedNode[0];
          }

          parentId = parent.data.id;
	
					f.submit({
						url: submitUrl,
						params: {
							parentId: parentId
						},
						success: function(form, action) {
							if (action.result.success !== true) {
								action.options.failure();
							} else {
								var newNode = new Ext.tree.TreeNode({
									id: action.result.navigation.id, 
									text: action.result.navigation.title,
									linkUrl: action.result.navigation.link,
									leaf: true
								});
	
								if (!parent.expanded) {
									parent.expand();
								}
								
								if (parent.isLeaf) {
									parent.leaf = false;
									parent.attributes.leaf = false;
								}
								
								parent.appendChild(newNode);
							}
							
							win.hide();
							formPanel.getForm().setValues({
								cmscombo: '',
								name: '',
								link: ''
							});
						},
						failure: function(form, action) {
							switch (action.failureType) {
								case Ext.form.Action.CLIENT_INVALID:
									Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid keys');
									break;
								case Ext.form.Action.CONNECT_FAILURE:
									Ext.Msg.alert('Failure', 'Ajax Communication Failed');
									break;
								case Ext.form.Action.SERVER_INVALID:
									Ext.Msg.alert('Failure', action.result.msg);
									break;
							}
						}
					});
				}
			}
		},{
			id: 'updatebtn',
			text: 'Update',
			hidden: true,
			handler: function(b, e) {
				var submitUrl = '/admin/navigations/update';
				var f = formPanel.getForm();
				var vals = f.getValues();
				
				var selectedNode = tree.selModel.selNode;
				var nodeId = selectedNode.id;

				console.log(selectedNode);
		

				f.submit({
					url: submitUrl,
					params: {
						id: nodeId
					},
					success: function(form, action) {
						//console.log(action);
					
						if (action.result.success !== true) {
							action.options.failure();
						} else {
							selectedNode.setText(action.result.navigation.title);
							selectedNode.attributes.linkUrl = action.result.navigation.link;
						}
						
						win.hide();
						formPanel.getForm().setValues({
							cmscombo: '',
							name: '',
							link: ''
						});
					},
					failure: function(form, action) {
						switch (action.failureType) {
							case Ext.form.Action.CLIENT_INVALID:
								Ext.Msg.alert('Failure', 'Form fields may not be submitted with invalid keys');
								break;
							case Ext.form.Action.CONNECT_FAILURE:
								Ext.Msg.alert('Failure', 'Ajax Communication Failed');
								break;
							case Ext.form.Action.SERVER_INVALID:
								Ext.Msg.alert('Failure', action.result.msg);
								break;
						}
					}
				});
			}			
		},{
			text: 'Cancel',
			handler: function(b, e) {
				win.hide();
				formPanel.getForm().setValues({
					cmscombo: '',
					name: '',
					link: ''
				});
			}			
		}],
		buttonAlign: 'right'
	});

    var addLinkButton = new Ext.Button({
        text: 'Add Link',
        handler: function() {        	
    		win = new Ext.Window({
    			title: 'Add Link',
    			width: 350,
    			modal: true,
    			closeAction: 'hide',
        		items: [formPanel],
        		listeners: {
        			beforehide: function(c) {
        				var f = formPanel.getForm();
		        		f.setValues({
							cmscombo: '',
							name: '',
							link: ''
						});
						f.clearInvalid();
        			}
        		}
        	});

        	win.show();
        }
    });
    
    var editLinkButton = new Ext.Button({
        text: 'Edit Link',
        disabled: true,
        handler: function() {
        	var f = formPanel.getForm();
        	var selectedNode = tree.selModel.selNode;
        	
        	if (selectedNode || selectedNode.parentNode || !selectedNode.parentNode.isRoot) {
                f.setValues({
                	name: selectedNode.attributes.text,
                	link: selectedNode.attributes.linkUrl
                });
                
                Ext.getCmp('savebtn').hide();
                Ext.getCmp('updatebtn').show();
                
	    		win = new Ext.Window({
	    			title: 'Edit Link',
	    			width: 350,
	    			modal: true,
	    			closeAction: 'hide',
	        		items: [formPanel],
		        	listeners: {
	        			beforehide: function(c) {
	        				var f = formPanel.getForm();
			        		f.setValues({
								cmscombo: '',
								name: '',
								link: ''
							});
							f.clearInvalid();
							
							Ext.getCmp('savebtn').show();
            				Ext.getCmp('updatebtn').hide();
	        			}
	        		}
	        	});
	
	        	win.show();
            }
        }
    });

    var removeLinkButton = new Ext.Button({
        text: 'Remove Link',
        disabled: true,
        handler: function() {
            var selectedNode = tree.selModel.selNode;

            if (!selectedNode) {
                Ext.MessageBox.show({
                            title: 'Error',
                            msg: 'Please select a link to delete',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.ERROR
                });
            } else if (!selectedNode.parentNode || selectedNode.parentNode.isRoot == true) {
                Ext.MessageBox.show({
                            title: 'Error',
                            msg: 'Cannot delete link positions',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.ERROR
                });
            } else {
                tree.disable();
                
                var params = {'id':selectedNode.id};

                Ext.Ajax.request({
                    url:deleteNodeUrl,
                    params:params,
                    success:function(response, request) {
                        if (response.responseText.charAt(0) != 1){
                            request.failure();
                        } else {
                        	var parent = selectedNode.parentNode;
                            selectedNode.destroy();
                            
                            if (!parent.hasChildNodes()) {
                            	parent.leaf = false;
                            	parent.attributes.leaf = false;
                            }
                            
                            tree.enable();
                            editLinkButton.disable();
                            removeLinkButton.disable();
                        }
                    },
                    failure:function() {
                        tree.suspendEvents();
                        tree.resumeEvents();
                        tree.enable();

                        Ext.MessageBox.show({
                            title: 'Error',
                            msg: 'Unable to delete your link. Please try again.',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.MessageBox.ERROR
                        });
                    }

                });
            }
        }
    });

    Ext.define('Navigation', {
    	extend: 'Ext.data.Model',
    	fields: [ 'text', 'linkUrl' ]
    });
    
    Ext.create('Ext.data.TreeStore', {
    	model: 'Navigation',
    	storeId: 'navigationStore',
    	proxy: {
    		type: 'ajax',
    		reader: {
    			type: 'json'
    		},
    		url: getNodesUrl
    	},
    	root: {
    		text: 'Navigation Positions',
    		expanded: true
    	},
    	autoLoad: true,
			listeners: {
				load: function() {
					tree.expandPath('/1');
				}
			}
    })
    
    var tree = Ext.create('Ext.tree.TreePanel', {
        title: 'Site Navigation',
        tools: [{
          id: 'expandTool',
          type: 'expand',
          tooltip: 'Expand all nodes',
          handler: function(event, toolEl, panel) {
            tree.expandAll();
            this.hide();
            tree.down('#collapseTool').show();
          }
        }, {
          id: 'collapseTool',
          type: 'collapse',
          tooltip: 'Collapse all nodes',
          hidden: true,
          handler: function(event, toolEl, panel) {
            tree.collapseAll();
            this.hide();
            tree.down('#expandTool').show();
          }
        }, {
          type: 'gear',
          tooltip: 'Navigation Settings',
          handler: function(event, toolEl, panel) {}
        }],
				id: 'treePanel',
        renderTo: 'tree-div',
        store: Ext.data.StoreManager.lookup('navigationStore'),
        autoScroll: true,
        animate: true,
        height: 500,
        containerScroll: true,
        rootVisible: true,
        tbar: new Ext.Toolbar({
        	items: [
        	{
        		icon: '/img/icons/expand.png',
        		id: 'expandall',
        		handler: function() {
        			tree.expandAll();
        			Ext.getCmp('expandall').setVisible(false);
        			Ext.getCmp('collapseall').setVisible(true);
        		}
        	},
        	{
        		icon: '/img/icons/collapse.png',
        		id: 'collapseall',
        		hidden: true,
        		handler: function() {
        			tree.collapseAll();
        			Ext.getCmp('collapseall').setVisible(false);
        			Ext.getCmp('expandall').setVisible(true);
        		}
        	},
        		addLinkButton,
        		editLinkButton,
				removeLinkButton
        	]
        }),
        listeners: {
        	itemclick: function(view, rec, item, index, e, opts) {
				var black_list = ['Navigation Positions', 'Top', 'Left',
					'Employers Middle', 'Career Seekers Middle', 'Programs Middle'];
				
				// check if the node can be deleted
				if (Ext.Array.indexOf(black_list, rec.data.text) === -1) {
					editLinkButton.enable();
					removeLinkButton.enable();
				} else {
					editLinkButton.disable();
					removeLinkButton.disable();
				}
        	},
        	startdrag: function(tree, node, event) {
        		oldPosition = node.parentNode.indexOf(node);
        		oldNextSibling = node.nextSibling;
        	},
        	movenode: function(tree, node, oldParent, newParent, position) {
		        if (oldParent == newParent){
		            var url = reorderUrl;
		            var params = {'node':node.id, 'delta':(position-oldPosition)};
		        } else {
		            var url = reparentUrl;
		            var params = {'node':node.id, 'parent':newParent.id, 'position':position};
		        }
		
		        // we disable tree interaction until we've heard a response from the server
		        // this prevents concurrent requests which could yield unusual results
		        tree.disable();
		
		        Ext.Ajax.request({
		            url:url,
		            params:params,
		            success:function(response, request) {
		
		                // if the first char of our response is zero, then we fail the operation,
		                // otherwise we re-enable the tree
		
		                if (response.responseText.charAt(0) != 1){
		                    request.failure();
		                } else {
		                    tree.enable();
		                }
		            },
		            failure:function() {
		                // we move the node back to where it was beforehand and
		                // we suspendEvents() so that we don't get stuck in a possible infinite loop
		                tree.suspendEvents();
		                oldParent.appendChild(node);
		                if (oldNextSibling){
		                    oldParent.insertBefore(node, oldNextSibling);
		                }
		
		                tree.resumeEvents();
		                tree.enable();
		
		                Ext.MessageBox.show({
		                    title: 'Error',
		                    msg: 'Unable to save your changes',
		                    buttons: Ext.MessageBox.OK,
		                    icon: Ext.MessageBox.ERROR
		                });
		            }
		        });
        	}
        }
    });

});

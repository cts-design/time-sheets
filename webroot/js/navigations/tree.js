/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
Ext.onReady(function() {
	Ext.QuickTips.init();
	Ext.BLANK_IMAGE_URL = "/img/ext/default/s.gif";
	
    var win; // holds the Ext.Window object
    var getNodesUrl = '/admin/navigations/get_nodes/',
    addNodeUrl = '/admin/navigations/add_node/',
    reorderUrl = '/admin/navigations/reorder/',
    reparentUrl = '/admin/navigations/reparent/',
    renameNodeUrl = '/admin/navigations/rename_node/',
    deleteNodeUrl = '/admin/navigations/delete_node/';
    // track what nodes are moved and send to server to save
    var oldPosition = null,
    oldNextSibling = null;
    
	// form to place on the window
	var formPanel = new Ext.FormPanel({
		defaultType: 'field',
		autoScroll: true,
		id: 'formpanel',
		frame: true,
		items: [{
			xtype: 'textfield',
			fieldLabel: 'Name',
			name: 'name'
		},{
			xtype: 'textfield',
			fieldLabel: 'URL',
			name: 'url'
		}],
		buttons: [{
			text: 'Save',
			handler: function(b, e) {
				
			}
		},{
			text: 'Cancel',
			handler: function(b, e) {
				win.close();
			}			
		}],
		buttonAlign: 'right'
	});

    var addLinkButton = new Ext.Button({
        text: 'Add Link',
        handler: function() {
			var selectedNode = tree.selModel.selNode;
			var parentId;
            
            if (!selectedNode || !selectedNode.parentNode || selectedNode.parentNode.isRoot == true) {
                parentId = tree.root.firstChild.attributes.id;
            } else {
                parentId = selectedNode.parentNode.attributes.id;
            }
            
            //console.log(parentId);
            
    		win = new Ext.Window({
    			title: 'Add Link',
    			modal: true,
    			closeAction: 'hide',
        		items: [formPanel]
        	});

        	win.show();
        	
        	
            // 

            // 
            // Ext.MessageBox.prompt('New Link', 'Please enter the name of the new link', function(btn, text) {
            //     if (btn == 'cancel' || text == '') {
            //         return;
            //     }
            // 
            //     var newNodeName = text;
            //     Ext.MessageBox.prompt('New Link', 'Please enter the url of the ' + newNodeName + ' link', function(b,t) {
            //         if (b == 'cancel' || t == '') {
            //             return;
            //         }
            // 
            //         var newNodeUrl = t;
            //         tree.disable();
            //         var params = {'title':newNodeName, 'link':newNodeUrl, 'parent_id':parentId};
            // 
            //         Ext.Ajax.request({
            //             url:addNodeUrl,
            //             params:params,
            //             success:function(response, request) {
            //                 if (response.responseText.charAt(0) == 0){
            //                    request.failure();
            //                 } else {
            //                     //console.log(response);
            // 
            //                     var newNode = new Ext.tree.TreeNode({id: response, text: newNodeName, leaf: true});
            //                     if (!selectedNode || !selectedNode.parentNode || selectedNode.parentNode.isRoot == true) {
            //                         tree.root.firstChild.appendChild(newNode);
            //                     } else {
            //                         selectedNode.parentNode.appendChild(newNode);
            //                     }
            //                     tree.enable();
            //                 }
            //             },
            //             failure:function() {
            //                 // we move the node back to where it was beforehand and
            //                 // we suspendEvents() so that we don't get stuck in a possible infinite loop
            //                 tree.suspendEvents();
            //                 tree.resumeEvents();
            //                 tree.enable();
            // 
            //                 Ext.MessageBox.show({
            //                     title: 'Error',
            //                     msg: 'Unable to save your changes',
            //                     buttons: Ext.MessageBox.OK,
            //                     icon: Ext.MessageBox.ERROR
            //                 });
            //             }
            //         });
            //     });
            // });
        }
    });
    
    var editLinkButton = new Ext.Button({
        text: 'Edit Link',
        disabled: true,
        handler: function() {
        	var f = formPanel.getForm();
        	var selectedNode = tree.selModel.selNode;
        	
        	if (selectedNode || selectedNode.parentNode || !selectedNode.parentNode.isRoot) {
                f.findField('name').setValue(selectedNode.attributes.text);
                f.findField('url').setValue(selectedNode.attributes.linkUrl);
                
                if (!win) {
		    		win = new Ext.Window({
		    			title: 'Edit Link',
		    			modal: true,
		    			closeAction: 'hide',
		        		items: [formPanel]
		        	});
                };
	
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
                            selectedNode.destroy();
                            tree.enable();
                            removeLinkButton.disable();
                        }
                    },
                    failure:function() {

                        // we move the node back to where it was beforehand and
                        // we suspendEvents() so that we don't get stuck in a possible infinite loop

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

    var Tree = Ext.tree;
    var tree = new Tree.TreePanel({
        title: 'Site Navigation',
        renderTo: 'tree-div',
        autoScroll: true,
        animate: true,
        enableDD: true,
        containerScroll: true,
        rootVisible: true,
        root: new Tree.AsyncTreeNode({
	        editable: false,
	        text: 'Navigation Positions',
	        draggable: false,
	        id: 'root'
    	}),
    	loader: new Ext.tree.TreeLoader({
            dataUrl: getNodesUrl
        }),
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
        	click: function(node, e) {
        		if (!node.parentNode || node.parentNode.isRoot == true) {
        			editLinkButton.disable();
        			removeLinkButton.disable();
        		} else {
        			editLinkButton.enable();
        			removeLinkButton.enable();
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

    tree.root.expand();

    var tree_editor = new Ext.tree.TreeEditor(tree, {/* fieldconfig here */ }, {
        allowBlank:false,
        blankText:'A name is required',
        selectOnFocus:true,
        listeners: {
        	complete: function(treeEditor, newValue, oldValue) {
		        if (newValue === oldValue) {
		            return false;
		        }
		
		        tree.disable();
		        var nodeId = treeEditor.editNode.attributes.id;
		        var params = {'id': nodeId, 'title':newValue};
		
		        Ext.Ajax.request({
		            url:renameNodeUrl,
		            params:params,
		            success:function(response, request) {
		                if (response.responseText.charAt(0) != 1){
		                    request.failure();
		                } else {
		                    tree.enable();
		                }
		            },
		            failure:function() {
		                tree.suspendEvents();
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
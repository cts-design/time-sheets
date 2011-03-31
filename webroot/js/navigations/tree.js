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

Ext.onReady(function() {
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
    
    var cmsStore = new Ext.data.JsonStore({
    	autoLoad: true,
		url: '/admin/pages/get_short_list',
		storeId: 'cmsStore',
		root: 'pages',
		idProperty: 'title',
		fields: ['title', 'slug']
	});
	
	var cmsCombo = new Ext.form.ComboBox({
		store: cmsStore,
		mode: 'local',
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
	    			name: record.data.title,
	    			link: '/pages/' + record.data.slug
	    		});
	    	}
	    }
	});
    
	// form to place on the window
	var formPanel = new Ext.FormPanel({
		defaultType: 'field',
		labelWidth: 150,
		autoScroll: true,
		id: 'formpanel',
		frame: true,
		items: [
		cmsCombo,
		{
			xtype: 'textfield',
			fieldLabel: 'Name',
			name: 'name'
		},{
			xtype: 'textfield',
			fieldLabel: 'URL',
			name: 'link'
		}],
		buttons: [{
			text: 'Save',
			handler: function(b, e) {
				var submitUrl = (win.title == 'Edit Link' ? '/admin/navigations/update' : '/admin/navigations/create');
				var f = formPanel.getForm();
				var vals = f.getValues();
				
				var selectedNode = tree.selModel.selNode;
				var parentId, parent;

	            if (!selectedNode || !selectedNode.parentNode) {
	            	parent = tree.root.firstChild;
	            } else if (selectedNode.leaf) {
	            	// check to see if we're nesting too deep
	            	if (selectedNode.parentNode.parentNode.parentNode && selectedNode.parentNode.parentNode.parentNode.isRoot) {
	            		Ext.Msg.alert('Cannot add link', 'You may only nest one link deep');
	            		return;
	            	} else {
	            		parent = selectedNode;
	            	}
	            } else {
	            	parent = selectedNode;
	            }
	            
	            parentId = parent.id;

				f.submit({
					url: submitUrl,
					params: {
						parentId: parentId
					},
					success: function(form, action) {
						//console.log(action);
					
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
                            selectedNode.destroy();
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
});
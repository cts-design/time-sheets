/**
 * @author Brandon Cordell
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */

Ext.override(Ext.menu.KeyNav, {
    constructor: function(menu) {
        var me = this;
        me.menu = menu;
        me.callParent([menu.el, {
            down: me.down,
            enter: me.enter,
            esc: me.escape,
            left: me.left,
            right: me.right,
            //space: me.enter,
            tab: me.tab,
            up: me.up
        }]);
    }
});

Ext.onReady(function() {
	Ext.QuickTips.init();
	Ext.BLANK_IMAGE_URL = "/img/ext/default/s.gif";
	
		var selectedRecord = null;
    var win; // holds the Ext.Window object
    var getNodesUrl = '/admin/navigations/read',
    addNodeUrl = '/admin/navigations/add_node',
    reorderUrl = '/admin/navigations/reorder',
    reparentUrl = '/admin/navigations/reparent',
    deleteNodeUrl = '/admin/navigations/delete_node';
    // track what nodes are moved and send to server to save
    var oldPosition = null,
    oldNextSibling = null;

		var cp = Ext.create('Ext.state.CookieProvider', {
		    expires: new Date(new Date().getTime()+(1000*60*60*24*30)), //30 days	
		})

		Ext.state.Manager.setProvider(cp);
		
    Ext.define('Page', {
    	extend: 'Ext.data.Model',
    	fields: [ 'title', 'slug' ]
    });
		
    var pageStore = Ext.create('Ext.data.Store', {
    	model: 'Page',
    	storeId: 'pageStore',
    	proxy: {
    		type: 'ajax',
				api: {
					read: '/admin/pages/get_short_list'
				},
    		reader: {
    			type:'json',
    			root: 'pages'
    		}
    	},
			autoLoad: true
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
    		api: {
					read: getNodesUrl,
					update: '/admin/navigations/update'
				}
    	},
    	root: {
    		text: 'Navigation Positions',
    		expanded: true
    	},
    	autoLoad: true
    });

		var cmsCombo = Ext.create('Ext.form.field.ComboBox', {
			store: pageStore,
			queryMode: 'local',
			name: 'cmscombo',
			triggerAction: 'all',
			typeAhead: true,
		    valueField: 'title',
		    displayField: 'title',
		    fieldLabel: 'CMS Page',
		    disabledClass: 'test',
		    listeners: {
		    	select: function(combo, record, index) {
		    		var f = this.up('form').getForm();
		    		f.setValues({
		    			name: record[0].data.title,
		    			link: '/pages/' + record[0].data.slug
		    		});
		    	}
		    }
		});
		
		var addForm = Ext.create('Ext.form.Panel', {
			width: 300,
			frame: true,
			items: [{
				xtype: 'combo',
				store: Ext.data.StoreManager.lookup('pageStore'),
				queryMode: 'remote',
				name: 'cmscombo',
				triggerAction: 'all',
				typeAhead: true,
		    valueField: 'title',
		    displayField: 'title',
		    fieldLabel: 'CMS Page',
		    listeners: {
		    	select: function(combo, record, index) {
		    		var f = this.up('form').getForm();
		    		f.setValues({
		    			name: record[0].data.title,
		    			link: '/pages/' + record[0].data.slug
		    		});
		    	}
		    }
			}, {
				xtype: 'textfield',
				fieldLabel: 'Link Name',
				name: "name",
				allowBlank: false
			}, {	
				xtype: 'textfield',
				fieldLabel: 'Link URL',
				name: "link",
				allowBlank: false
			}],
			buttons: [{
				text: 'Save',
				formBind: true,
				disabled: true,
				handler: function()	{
					Ext.MessageBox.wait('Please Wait..', 'Status');
					if(addForm.getForm().isValid()) {

						var vals = addForm.getForm().getValues();
						var parent = tree.getSelectionModel().getLastSelected();	
						if(parent == null) {
							Ext.MessageBox.alert('Error', 'Please select a parent to add link to.');
							return false;
						}

						Ext.Ajax.request({
							url: '/admin/navigations/create',
							params: {
								parentId: parent.data.id,
								name: vals.name,
								link: vals.link,
								parentPath: parent.getPath()
							},
							scope: this,
							success: function(response, options) {
								var o = {};
								try {
									o = Ext.decode(response.responseText);
								} catch(e) {
									Ext.Msg.alert('Error','Unable to save category, please try again.');
									return;
								}
								if(o.success !== true) {
									Ext.Msg.alert('Error', o.message);
								} else {					
									Ext.data.StoreManager.lookup('navigationStore').load();													
									tree.on('load', function() {
										tree.expandPath(o.node);
										tree.selectPath(o.node);
										Ext.Msg.alert('Success', o.message);								
									}, this, {
										delay: 100,
										single: true
									});
								}
							},
							failure: function() {
								Ext.Msg.alert('Error','Unable to save category, please try again.');
							}
						});
					}
				}
			}]
		});	

		var editForm = Ext.create('Ext.form.Panel', {
			width: 300,
			frame: true,
			items: [{
				xtype: 'combo',
				store: Ext.data.StoreManager.lookup('pageStore'),
				queryMode: 'remote',
				name: 'cmscombo',
				triggerAction: 'all',
				typeAhead: true,
		    valueField: 'title',
		    displayField: 'title',
		    fieldLabel: 'CMS Page',
		    listeners: {
		    	select: function(combo, record, index) {
		    		var f = this.up('form').getForm();
		    		f.setValues({
		    			name: record[0].data.title,
		    			link: '/pages/' + record[0].data.slug
		    		});
		    	}
		    }
			}, {
				xtype: 'textfield',
				fieldLabel: 'Link Name',
				name: "name",
				allowBlank: false
			}, {	
				xtype: 'textfield',
				fieldLabel: 'Link URL',
				name: "link",
				allowBlank: false
			}],
			buttons: [{
				text: 'Save',
				formBind: true,
				disabled: true,
				handler: function()	{
					Ext.MessageBox.wait('Please Wait..', 'Status');
					var selected = tree.getSelectionModel().getLastSelected();			
					var form = this.up('form').getForm();					
					var id =  selected.internalId;
		      if (form.isValid()) {
						var vals = form.getValues();
						selected.beginEdit();
						selected.set('name', vals.name);
						selected.set('link', vals.link);
						selected.endEdit();
						Ext.data.StoreManager.lookup('navigationStore').sync();
					}
				}
			}]
		});

		var contextMenu	 = Ext.create('Ext.menu.Menu', {
		    items: [{
		    	text: 'Add Link',
		    	id: 'addLink',
		    	icon : '/img/icons/add.png',
		    	menu: {
		    		plain: true,
		    		items: [addForm]
		    	}
		    },		
		    {
		    	text: 'Edit Link',
		    	id: 'editLink',
		    	icon : '/img/icons/application_form_edit.png',
					menu: {
						plain: true,
						items: [editForm]
					},
					listeners: {
						show: function () {
							console.log('show')
						},
						render: function () {
							console.log('render')
						},
						activate: function () {
							console.log('activated!');
							
							if (selectedRecord) {
								console.log(selectedRecord);
								editForm.loadRecord(selectedRecord);
								editForm.doLayout();
							}
						}
					}
		    }, {
					text: 'Delete Link',
					id: 'deleteLink',
					icon: '/img/icons/delete.png',
					handler: function () {
						var selected = tree.getSelectionModel().getLastSelected();
						
						Ext.data.StoreManager.lookup('navigationStore').remove(selected);
						Ext.data.StoreManager.lookup('navigationStore').sync();
					}
				}]
		});
		
		var settingsWindow = Ext.create('Ext.window.Window', {
			closeAction: 'hide',
			id: 'settingsWindow',
			items: [{
				xtype: 'button',
				text: 'Repair Navigation Tree',
				x: 30,
				y: 10,
				handler: function () {
					Ext.Ajax.request({
						url: '/admin/Navigations/repair',
						success: function (response) {
							Ext.Msg.alert('Success', 'Navigation tree succesfully repaired');
						}
					});
				}
			}],
			height: 100,
			layout: 'absolute',
			title: 'Tools',
			width: 100
		});
    
    var tree = Ext.create('Ext.tree.TreePanel', {
				id: 'navigationTree',
        title: 'Site Navigation',
				stateful: true,
				stateId: 'navTree',
				stateEvents: ['itemexpand', 'itemcollapse'],
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
					id: 'settingsTool',
          type: 'gear',
          tooltip: 'Navigation Settings',
					scope: this,
					hidden: (userRoleId=2) ? false : true,
          handler: function(event, toolEl, panel) {
						settingsWindow.show();
					}
        }],
				id: 'treePanel',
        renderTo: 'tree-div',
        store: Ext.data.StoreManager.lookup('navigationStore'),
        autoScroll: true,
        animate: true,
        height: 500,
        containerScroll: true,
        rootVisible: true,
		   	viewConfig: {
			    plugins: {
				    ptype: 'treeviewdragdrop'
			    },
					listeners : {
					  itemcontextmenu: function(view, rec, node, index, e) {
						  e.stopEvent();
						  contextMenu.showAt(e.getXY());
						  return false;
					  }              				
					}            
		    },
        listeners: {
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

		tree.on('beforeitemmove', function(ni, oldParent, newParent, index, eOpts) {
			oldPosition = ni.parentNode.indexOf(ni);
			oldNextSibling = ni.nextSibling;
		});

		tree.on('itemmove', function(ni, oldParent, newParent, index, eOpts) {

			if (oldParent == newParent) {
				var url = reorderUrl;
				var params = {'node':ni.data.id, 'delta':(index-oldPosition)};
			} else {
				var url = reparentUrl;
				var params = {'node':ni.data.id, 'parent':newParent.data.id, 'position':index};
			}

			// we disable tree interaction until we've heard a response from the server
			// this prevents concurrent requests which could yield unusual results

			tree.disable();

			Ext.Ajax.request({
				url:url,
				params:params,

				success: function(response, request) {
					var o = {};
					try {
						o = Ext.decode(response.responseText);
					} catch(e) {
						Ext.Msg.alert('Error','Unable to move category, please try again.');
						return;
					}
					if(o.success == true) {
						tree.enable();
					} else {
						request.failure();
					}
				},
				failure: function() {
					// we move the node back to where it was beforehand and
					// we suspendEvents() so that we don't get stuck in a possible infinite loop

					tree.suspendEvents();
					oldParent.appendChild(ni);
					if (oldNextSibling) {
						oldParent.insertBefore(ni, oldNextSibling);
					}

					tree.resumeEvents();
					tree.enable();

					Ext.MessageBox.alert('Error', 'Changes could not be saved');
				}
			});

		});

		tree.getView().on('beforeitemcontextmenu', function(view, record, item, index, e, eOpts){
			var black_list = ['Navigation Positions', 'Top', 'Left', 'Employers Middle', 'Career Seekers Middle', 'Programs Middle'];
	
			// check if the node can be deleted
			if (Ext.Array.indexOf(black_list, record.data.text) === -1) {
				Ext.getCmp('deleteLink').enable();
				Ext.getCmp('editLink').enable();
				selectedRecord = record;
			} else {
				Ext.getCmp('deleteLink').disable();
				Ext.getCmp('editLink').disable(); 
			}
			
			console.log(record);
			console.log(editForm);
		});
});

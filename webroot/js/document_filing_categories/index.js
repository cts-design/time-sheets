/*
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */



Ext.onReady( function() {
	Ext.QuickTips.init();

	var getnodesUrl = '/admin/document_filing_categories',
		reorderUrl = '/admin/document_filing_categories/reorder_categories',
		reparentUrl = '/admin/document_filing_categories/reparent_categories';
	
	
	var cp = Ext.create('Ext.state.CookieProvider', {
		expires: new Date(new Date().getTime()+(1000*60*60*24*30)) //30 Days
	});
	
	Ext.state.Manager.setProvider(cp);
	
	var addForm = Ext.create('Ext.form.Panel', {
		width: 300,
		frame: true,
		items: [{
			xtype: 'textfield',
			fieldStyle: 'margin-left:10px',
			fieldLabel: 'Category Name',
			name: "text",
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
					if(parent === null) {
						Ext.MessageBox.alert('Error', 'Please seleact a parent category to add category to.');
						return false;
					}
					//TODO Send this through the tree store. Should be fixed in next build of EXTJS
					/*
					parent.appendChild({
						text: 'Test5',
						leaf: false
					});
					store.sync();
					*/
					
					Ext.Ajax.request({
						url: '/admin/document_filing_categories/add',
						params: {
							parentId: parent.data.id,
							catName: vals.text,
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
								store.load();
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
			xtype: 'textfield',
			fieldStyle: 'margin-left:10px',
			fieldLabel: 'Category Name',
			name: "text",
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
					selected.set({text: vals.text});
					selected.endEdit();
					store.sync();
				}
			}
		}]
	});
	
	var contextMenu	= Ext.create('Ext.menu.Menu', {
		items: [{
			text: 'Add Category',
			id: 'addCat',
			icon : '/img/icons/add.png',
			menu: {
        enableKeyNav: false,
				plain: true,
				items: [addForm]
			}
		},
		{
			text: 'Edit Category',
			id: 'editCat',
			icon : '/img/icons/application_form_edit.png',
			menu: {
				plain: true,
        enableKeyNav: false,
				items: [editForm]
			}
		},{
			text: 'Disable Category',
			id: 'disableCat',
			hidden: true,
			icon : '/img/icons/delete.png',
			handler: function(){
				var node = tree.getSelectionModel().getLastSelected();
				toggleNodeDisabled(node, 1);
			}
		},{
			text: 'Enable Category',
			id: 'enableCat',
			hidden: true,
			icon : '/img/icons/accept.png',
			handler: function(){
				var node = tree.getSelectionModel().getLastSelected();
				toggleNodeDisabled(node, 0);
			}
		},{
			text: 'Secure',
			id: 'secureCat',
			icon : '/img/icons/lock_add.png',
			hidden: true,
			handler: function() {
				var node = tree.getSelectionModel().getLastSelected();
				toggleNodeSecure(node, 1);
			}
		},{
			text: 'Un-Secure',
			id: 'unsecureCat',
			icon : '/img/icons/lock_delete.png',
			hidden: true,
			handler: function() {
				var node = tree.getSelectionModel().getLastSelected();
				toggleNodeSecure(node, 0);
			}
		}],
		listeners: {
			hide: function() {
				Ext.getCmp('enableCat').hide();
				Ext.getCmp('disableCat').hide();
			}
		}
	});
	
	Ext.define('DocumentFilingCategory', {
		extend: 'Ext.data.Model',
		fields: [
			{name: 'id', type: 'string' },
			{name: 'text', type: 'string'},
			{name: 'disabled', type: 'boolean'},
			{name: 'cls', type: 'string'},
			{name: 'leaf', type: 'boolean'},
			{name: 'secure', type: 'boolean'}
		]
	});
	
    var store = Ext.create('Ext.data.TreeStore', {
		model: 'DocumentFilingCategory',
        proxy: {
			api:{
				create: '/admin/document_filing_categories/add',
				update: '/admin/document_filing_categories/edit'
				
			},
			url: '/admin/document_filing_categories/',
            type: 'ajax',
            simpleSort: true,
			writer: {
				type: 'json',
				writeAllFields: true,
				root: 'category',
				encode: true
			}
        },
        root: {
			id: 'source',
			expanded: true,
			text: 'Document Filing Catgories',
			draggable: false
        },
        listeners: {
			write: function(store, operation, eOpts) {
				if(operation.action == 'update'){
					if(operation.success){
						Ext.MessageBox.alert('Status', 'Category updated successfully.');
						editForm.getForm().reset();
					}
					else {
						Ext.MessageBox.alert('Status', 'Unable to update category, please try again.');
						editForm.getForm().reset();
					}
				}
			}
        }
    });
	
	var tree = Ext.create('Ext.tree.Panel', {
		id: 'docCatTree',
		renderTo: 'documentFilingCategoryTree',
		store: store,
		useArrows: true,
		title: 'Document Filing Categories',
		frame: true,
		width: 325,
		height: 600,
		stateful: true,
		stateId: 'docCatTree',
		plugins: [{
                ptype: 'nodedisabled',
                preventSelection: false
        }],
		stateEvents: ['itemexpand', 'itemcollapse'],

		selType: 'treemodel',
        viewConfig: {
            plugins: {
                ptype: 'treeviewdragdrop'
            },
			listeners : {
				itemcontextmenu: function(view, rec, node, index, e) {
				e.stopEvent();

				if(rec.data.secure) {
					console.log(rec);
					Ext.getCmp('unsecureCat').show();
					Ext.getCmp('secureCat').hide();
				}
				else {
					Ext.getCmp('secureCat').show();
					Ext.getCmp('unsecureCat').hide();
				}
				contextMenu.showAt(e.getXY());
				return false;
				}
			}
        },
		getState: function () {
			var nodes = [];
			var lastnode;
			this.getRootNode().eachChild( function (child) {
				//function to store state of tree recursively
				
				var storeTreeState = function (node, expandedNodes) {
					if (node.isExpanded() && node.childNodes.length > 0) {
						expandedNodes.push(node.getPath());
						node.eachChild( function (child) {
							storeTreeState(child, expandedNodes);
						});
					}
					
				};
				storeTreeState(child, nodes);
			});
			return { expandedNodes: nodes };
		},
		applyState: function (state) {
			var that = this;
			this.on('load', function () {
				var nodes = state.expandedNodes;
				
				for (var i = 0; i < nodes.length; i++) {
					if (typeof nodes[i] != 'undefined') {
						that.expandPath(nodes[i]);
					}
				}
			}, this, {delay: 10, single: true});
		},
		animate: true,
		containerScroll: true,
		border: false
	});
	
	var oldPosition = null,
		oldNextSibling = null;
	
	tree.on('beforeitemmove', function(ni, oldParent, newParent, index, eOpts) {
		oldPosition = ni.parentNode.indexOf(ni);
		oldNextSibling = ni.nextSibling;
	});
	
	tree.on('itemmove', function(ni, oldParent, newParent, index, eOpts) {
		var url, params;
		if (oldParent == newParent) {
			url = reorderUrl;
			params = {'node':ni.data.id, 'delta':(index-oldPosition)};
		} else {
			url = reparentUrl;
			params = {'node':ni.data.id, 'parent':newParent.data.id, 'position':index};
		}
	
		// we disable tree interaction until we've heard a response from the server
		// this prevents concurrent requests which could yield unusual results
	
		tree.disable();
	
		Ext.Ajax.request({
			url:url,
			params: params,
	
			success: function(response, request) {
				var o = {};
				try {
					o = Ext.decode(response.responseText);
				} catch(e) {
					Ext.Msg.alert('Error','Unable to move category, please try again.');
					return;
				}
				if(o.success === true) {
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
	
	var toggleNodeDisabled = function(node, status) {
		Ext.MessageBox.wait('Please Wait..', 'Status');
		Ext.Ajax.request({
			url: '/admin/document_filing_categories/toggle_disabled',
			params: {
				'data[DocumentFilingCategory][id]': node.data.id,
				'data[DocumentFilingCategory][disabled]' : status
			},
			scope: this,
			success: function(response, options) {
				var o = {};
				try {
					o = Ext.decode(response.responseText);
				} catch(e) {
					Ext.Msg.alert('Error','Unable to update category status, please try again.');
					return;
				}
				if(o.success !== true) {
					Ext.Msg.alert('Error', o.message);
					tree.enable();
				} else {
					Ext.Msg.alert('Success', o.message);
						store.load();
						tree.enable();
				}
			},
			failure: function() {
				Ext.Msg.alert('Error','Unable to update category status, please try again.');
			}
		});
	};

	var toggleNodeSecure = function(node, secure) {
		Ext.MessageBox.wait('Please Wait..', 'Status');
		Ext.Ajax.request({
			url: '/admin/document_filing_categories/toggle_secure',
			params: {
				'data[DocumentFilingCategory][id]': node.data.id,
				'data[DocumentFilingCategory][secure]' : secure
			},
			scope: this,
			success: function(response, options) {
				var o = {};
				try {
					o = Ext.decode(response.responseText);
				} catch(e) {
					Ext.Msg.alert('Error','Unable to update category secure status, please try again.');
					return;
				}
				if(o.success !== true) {
					Ext.Msg.alert('Error', o.message);
					tree.enable();
				} else {
					Ext.Msg.alert('Success', o.message);
						store.load();
						tree.enable();
				}
			},
			failure: function() {
				Ext.Msg.alert('Error','Unable to update category secure status, please try again.');
			}
		});
	};
	
	tree.getView().on('beforeitemcontextmenu', function(view, record, item, index, e, eOpts){
		if(record.data.disabled) {
			Ext.getCmp('enableCat').show();
		}
		else {
			Ext.getCmp('disableCat').show();
		}
		if(record.data.id == 'source') {
			Ext.getCmp('editCat').hide();
			Ext.getCmp('disableCat').hide();
		}
		else {
			Ext.getCmp('editCat').show();
		}
		if(record.data.depth == 3) {
			Ext.getCmp('addCat').hide();
		}
		else {
			Ext.getCmp('addCat').show();
		}
	});
	
});

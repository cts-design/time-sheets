Ext.define('SecureFilingCategory', {
	extend: 'Ext.data.Model',
	fields: ['id', 'category', 'secure_admins']
});

Ext.create('Ext.data.Store', {
	storeId: 'SecureFilingCategories',
	model: 'SecureFilingCategory',
	proxy: {
		type: 'ajax',
		url: '/admin/secure_categories/get_secure_filing_cats',
		reader: {
			type: 'json',
			root: 'cats'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	},
	autoLoad: true
});

Ext.define('SecureQueueCategory', {
	extend: 'Ext.data.Model',
	fields: ['id', 'category', 'secure_admins']
});

Ext.create('Ext.data.Store', {
	storeId: 'SecureQueueCategories',
	model: 'SecureQueueCategory',
	proxy: {
		type: 'ajax',
		url: '/admin/secure_categories/get_secure_queue_cats',
		reader: {
			type: 'json',
			root: 'cats'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	},
	autoLoad: true
});

Ext.define('Admin', {
	extend: "Ext.data.Model",
	fields: ['id', 'name']
});

Ext.create('Ext.data.Store', {
	storeId: 'Admins',
	model: 'Admin',
	proxy: {
		type: 'ajax',
		url: '/admin/users/get_all_admins',
		reader: {
			type: 'json',
			root: 'admins'
		},
		limitParam: undefined,
		pageParam: undefined,
		startParam: undefined
	},
	autoLoad: true
});

Ext.define('Atlas.grid.SecureCategoriesPanel', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.securecategoriesgridpanel',
	columns: [{
		text: 'Id',
		dataIndex: 'id',
		hidden: true
	},{
		text: 'Category',
		dataIndex: 'category',
		flex: 1
	}]
});

var catInfoTplMarkup = [
		'<p style="margin-bottom: 20px;"> Choose a category above view/add/remove allowed admins.</p>',
		'<p>Category: <span style="margin-left: 10px;">{category}</span></p>'
	],
	catTpl = Ext.create('Ext.Template', catInfoTplMarkup);

Ext.define('Atlas.form.SecureCategoryPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.securecategoryformpanel',
	items: [{
		xtype: 'hidden',
		name: 'id'
	},{
		xtype: 'panel',
		itemId: 'catInfo',
		border: 0,
		height: 50,
		data: {},
		tpl: catTpl,
		margin: '0 0 10 0'
	},{
		xtype: 'boxselect',
		itemId: 'adminSelect',
		encodeSubmitValue: true,
		name: 'secure_admins',
		store: 'Admins',
		displayField: 'name',
		valueField: 'id',
		queryMode: 'local',
		width: 450,
		fieldLabel: 'Allowed Admins'
	}],
	buttonAlign: 'left',
	buttons: [{
		text: 'Update',
		handler: function() {
			this.up('form').getForm().submit({
				waitMsg: 'Please Wait',
				waitTitle: 'Updating',
				success: function(form, action) {
					Ext.Msg.alert('Success', action.result.message);
					if(action.form.url == "/admin/secure_categories/update_filing_cat") {
						Ext.data.StoreManager.lookup('SecureFilingCategories').load();
					}
					if(action.form.url == "/admin/secure_categories/update_queue_cat") {
						Ext.data.StoreManager.lookup('SecureQueueCategories').load();
					}
				},
				failure: function(form, action) {
					Ext.Msg.alert('Failure', action.result.message);
				}
			});
		}
	}]
});

Ext.onReady(function(){

	Ext.create('Ext.Panel', {
		width: 950,
		height: 500,
		border: 0,
		frame: true,
		renderTo: 'secureCategories',
		layout: {
			type: 'hbox',
			align: 'stretch'
		},
		items: [{
			xtype: 'panel',
			layout: 'vbox',
			align: 'left',
			flex: 1,
			items: [{
				xtype: 'securecategoriesgridpanel',
				store: 'SecureFilingCategories',
				title: 'Filing Categories',
				flex: 1,
				width: 475,
				listeners: {
					selectionchange: function(selectionModel, selected) {
						if(selected.length) {
							var secureFilingCategoryForm = Ext.getCmp('secureFilingCategoryForm'),
								catInfo = secureFilingCategoryForm.getComponent('catInfo');
							catTpl.overwrite(catInfo.body, selected[0].data);
							secureFilingCategoryForm.loadRecord(selected[0]);
						}
					}
				}
			},{
				xtype: 'securecategoryformpanel',
				id: 'secureFilingCategoryForm',
				url: '/admin/secure_categories/update_filing_cat',
				width: 475,
				flex: 2,
				bodyPadding: 10,
				border: 0
			}]
		},{
			xtype: 'panel',
			layout: 'vbox',
			align: 'right',
			flex: 1,
			items: [{
				xtype: 'securecategoriesgridpanel',
				store: 'SecureQueueCategories',
				title: 'Queue Categories',
				flex: 1,
				width: 475,
				listeners: {
					selectionchange: function(selectionModel, selected) {
						if(selected.length) {
							var secureQueueCategoryForm = Ext.getCmp('secureQueueCategoryForm'),
								catInfo = secureQueueCategoryForm.getComponent('catInfo');
							catTpl.overwrite(catInfo.body, selected[0].data);
							secureQueueCategoryForm.loadRecord(selected[0]);
						}
					}
				}
			},{
				xtype: 'securecategoryformpanel',
				id: 'secureQueueCategoryForm',
				url: '/admin/secure_categories/update_queue_cat',
				width: 475,
				flex: 2,
				border: 0,
				bodyPadding: 10
			}]
		}]
	});
});
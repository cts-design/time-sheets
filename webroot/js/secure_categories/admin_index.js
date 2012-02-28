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

Ext.define('Atlas.grid.SecureFilingCategoriesPanel', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.securefilingcategoriesgridpanel',
	store: 'SecureFilingCategories',
	columns: [{
		text: 'Id',
		dataIndex: 'id',
		hidden: true
	},{
		text: 'Category',
		dataIndex: 'category',
		flex: 1
	}],
	listeners: {
		selectionchange: function(selectionModel, selected) {
			if(selected.length) {
				var filingCatInfo = Ext.getCmp('filingCatInfo');
				filingCatTpl.overwrite(filingCatInfo.body, selected[0].data);
				Ext.getCmp('secureFilingCategory').loadRecord(selected[0]);
			}
		}
	}
});

var catInfoTplMarkup = [
	'<p style="margin-bottom: 20px;"> Choose a category above view/add/remove allowed admins.</p>',
	'<p>Category: <span style="margin-left: 10px;">{category}</span></p>'
];
var filingCatTpl = Ext.create('Ext.Template', catInfoTplMarkup);

Ext.define('Atlas.form.SecureFilingCategoryPanel', {
	extend: 'Ext.form.Panel',
	alias: 'widget.securefilingcategoryformpanel',
	items: [{
		xtype: 'hidden',
		name: 'id'
	},{
		xtype: 'panel',
		id: 'filingCatInfo',
		border: 0,
		height: 50,
		data: {},
		tpl: filingCatTpl,
		margin: '0 0 10 0'
	},{
		xtype: 'boxselect',
		id: 'adminSelect',
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
					Ext.data.StoreManager.lookup('SecureFilingCategories').load();
				},
				failure: function(form, action) {
					Ext.Msg.alert('Failure', action.result.message);
				}
			});
		}
	}]
});


Ext.define('SecureQueueCategory', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name', 'users']
});

Ext.define('Atlas.grid.SecureQueueCategoriesPanel', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.securequeuecategoriesgridpanel',
	columns: [{
		text: 'Id',
		dataIndex: 'id'
	},{
		text: 'Name',
		dataIndex: 'name'
	}]
});

Ext.onReady(function(){

	Ext.create('Ext.Panel', {
		width: 950,
		height: 500,
		border: 0,
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
				xtype: 'securefilingcategoriesgridpanel',
				title: 'Filing Categories',
				flex: 1,
				width: 475
			},{
				xtype: 'securefilingcategoryformpanel',
				id: 'secureFilingCategory',
				url: '/admin/secure_categories/update_filing_cat',
				width: 475,
				flex: 2,
				border: 0,
				padding: 10
			}]
		},{
			xtype: 'securequeuecategoriesgridpanel',
			align: 'right',
			flex: 1,
			title: 'Queue Categories'
		}]
	});
});
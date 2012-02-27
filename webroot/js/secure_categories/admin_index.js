Ext.define('SecureFilingCategory', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name', 'users']
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

Ext.define('Atlas.grid.SecureFilingCategoriesPanel', {
	extend: 'Ext.grid.Panel',
	alias: 'widget.securefilingcategoriesgridpanel',
	store: 'SecureFilingCategories',
	columns: [{
		text: 'Id',
		dataIndex: 'id'
	},{
		text: 'Name',
		dataIndex: 'name'
	}],
	listeners: {
		selectionchange: function(selectionModel, selected) {
			if(selected.length) {
				var filingCatInfo = Ext.getCmp('filingCatInfo');
				filingCatTpl.overwrite(filingCatInfo.body, selected[0].data);
			}
		}
	}
});

var catInfoTplMarkup = '<p><strong>Id:</strong> {id} / <strong>Category Name:</strong> {name} </p>';
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
		data: {},
		tpl: filingCatTpl,
		margin: '0 0 10 0'
	},{
		xtype: 'boxselect',
		name: 'users',
		fieldLabel: 'Allowed Admins'
	}],
	buttonAlign: 'left',
	buttons: [{
		text: 'Update'
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
		height: 400,
		title: "Secure Categories",
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
			border: 0,
			items: [{
				xtype: 'securefilingcategoriesgridpanel',
				title: 'Filing Categories',
				flex: 2,
				width: 475
			},{
				xtype: 'securefilingcategoryformpanel',
				width: 475,
				flex: 1,
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
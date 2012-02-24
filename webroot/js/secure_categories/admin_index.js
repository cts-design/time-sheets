
Ext.define('SecureFilingCategory', {
	extend: 'Ext.data.Model',
	fields: ['id', 'name', 'users']
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
			flex: 1,
			html: 'Filing Categories'
		},{
			xtype: 'panel',
			flex: 1,
			html: 'Queue Categories'
		}]
	});
});
	

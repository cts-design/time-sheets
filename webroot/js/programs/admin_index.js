/**
 * @author dnolan
 */

Ext.onReady(function(){  
	
	Ext.QuickTips.init();
	
	Ext.define('Program', {
		extend: 'Ext.data.Model',
		idProperty: 'id',
		fields: ['id', 'name', 'actions']
	});
	
	var programStore = Ext.create('Ext.data.Store', {	
		storeId: 'programStore',
		autoLoad: true,
		model: 'Program',
		proxy: {
			type: 'ajax',
			url: '/admin/programs/index',
			reader: {
				type: 'json',
				root: 'programs'
			}
		}	
	});
	
	var programGrid = Ext.create('Ext.grid.Panel', {
		store: programStore,
		renderTo: 'programGrid',
		height: 300,
		title: 'Programs',
		width: 600,
		frame: true,
		columns: [{
			id: 'id',
			text: 'Id',
			dataIndex: 'id',
			width: 50
	
		},{
			text: 'Program Name',
			dataIndex: 'name',
			width: 220
		},{
			text: 'Actions',
			dataIndex: 'actions',
			width: 300
		}]
	});
});
/**
 * @author dnolan
 */
 

var programStore = new Ext.data.JsonStore({
	url: '/admin/programs/index',
	storeId: 'programStore',
	root: 'programs',
	idProperty: 'id',
	fields: ['id', 'name', 'actions']
});
programStore.load(); 
var programGrid = new Ext.grid.GridPanel({
	store: programStore,
	height: 300,
	title: 'Programs',
	width: 600,
	frame: true,
	columns: [{
		id: 'id',
		header: 'Id',
		dataIndex: 'id',
		width: 50

	},{
		header: 'Program Name',
		dataIndex: 'name',
		width: 220
	},{
		header: 'Actions',
		dataIndex: 'actions',
		width: 300
	}]
});

Ext.onReady(function(){ 
	programGrid.render('programGrid');
});

	
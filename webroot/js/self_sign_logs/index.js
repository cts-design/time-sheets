/* 
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 */

var selfSignLogsProxy = new Ext.data.HttpProxy({
	method: 'GET',
	prettyUrls: true,
	url: '/admin/self_sign_logs/'
});

var selfSignLogsReader = new Ext.data.JsonReader({
	idProperty: 'id',
	root: 'logs',
	totalProperty: 'results',
	fields: ['id', 'status', 'visitor', 'admin', 'created', 'location', 'service']
})

var selfSignLogsStore = new Ext.data.GroupingStore({
	reader: selfSignLogsReader,
	proxy:	selfSignLogsProxy,
	storeId: 'SelfSignLogsStore',
	groupField: 'status',
	groupDir: 'DESC'
});

var selfSignLogsGrid = new Ext.grid.GridPanel({
	store: selfSignLogsStore,
	height: 500,
	frame: true,
	loadMask: true,
	columns: [{
		header: 'Id',
		dataIndex: 'id',
		sortable: true,
		hidden: true	
	},{
		header: 'Status',
		dataIndex: 'status',
		sortable: true,
		width: 40
	},{
		header: 'Visitor',
		dataIndex: 'visitor',
		sortable: true,
		width: 75,
	},{
		header: 'Service',
		dataIndex: 'service',
		width: 280
	},{
		header: 'Last Act. Admin',
		dataIndex: 'admin',
		sortable: true,
		width: 75
	},{
		header: 'Location',
		dataIndex: 'location',
		sortable: true,
		width: 75
	},{
		header: 'Date',
		dataIndex: 'created',
		sortable: true,
		width: 75		
	}],
	view: new Ext.grid.GroupingView({
		forceFit: true,
		groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Items" : "Item"]})',
		startCollapsed: true,
		hideGroupedColumn: true,
		deferEmptyText: false,
		emptyText: '<div class="x-grid-empty">No records at this time.</div>'	
	})
	
})

Ext.onReady(function(){
	Ext.QuickTips.init();
	selfSignLogsGrid.render('SelfSignLogs');
	selfSignLogsStore.load();
	//setInterval('selfSignLogsStore.load()', 10000);	
	selfSignLogsStore.addListener('load', function(){
		var view = selfSignLogsGrid.getView();
		view.toggleGroup('ext-gen12-gp-status-Open', true);
	})
});
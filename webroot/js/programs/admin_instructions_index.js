/**
 * @author dnolan
 */
Ext.onReady(function(){  
	var instructions = [
		[
			'Program General Instructions', 
			'<a href="/admin/programs/edit_instructions/' + programId + '/general">Edit</a>'
		],
		[
			'Program Media Instructions', 
			'<a href="/admin/programs/edit_instructions/' + programId + '/media">Edit</a>'
		],
		[
			'Program Form Instructions', 
			'<a href="/admin/programs/edit_instructions/' + programId + '/form">Edit</a>'
		],
		[
			'Program Document Instructions', 
			'<a href="/admin/programs/edit_instructions/' + programId + '/document">Edit</a>'
		]
	];
	
	var instructionStore = new Ext.data.ArrayStore({
		autoDestroy: true,
		storeId: 'instructionStore',
		index: 0,
		fields: ['name', 'actions'],
		data: instructions
	});
	
	var instructionGrid = new Ext.grid.GridPanel({
		store: instructionStore,
		height: 150,
		title: 'Program Instructions',
		width: 375,
		frame: true,
		columns: [{
			header: 'Name',
			dataIndex: 'name',
			width: 250
		},{
			header: 'Actions',
			dataIndex: 'actions'
		}],
		viewConfig: {
			forceFit: true,
			scrollOffset: 0
		}
	});


	instructionGrid.render('instructionGrid');
});

	
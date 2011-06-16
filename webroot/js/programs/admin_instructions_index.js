/**
 * @author dnolan
 */
Ext.onReady(function(){  
	var instructions = [
		[
			'Main Instructions', 
			'<a href="/admin/programs/edit_instructions/1">Edit</a>'
		],
		[
			'Media Instructions', 
			'<a href="/admin/programs/edit_instructions/2">Edit</a>'
		],
		[
			'Form Instructions', 
			'<a href="/admin/programs/edit_instructions/3">Edit</a>'
		],
		[
			'Required Document Instructions', 
			'<a href="/admin/programs/edit_instructions/4">Edit</a>'
		],
		[
			'Uploaded Document Instructions', 
			'<a href="/admin/programs/edit_instructions/6">Edit</a>'
		],
		[
			'Dropping Off Document Instructions', 
			'<a href="/admin/programs/edit_instructions/7">Edit</a>'
		],					
		[
			'E-Sign Instructions', 
			'<a href="/admin/programs/edit_instructions/5">Edit</a>'
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
		height: 210,
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

	
/**
 * @author dnolan
 */

Ext.onReady(function(){
	var programResponsePanel = new Ext.Panel({
		title: 'Program Response',
		renderTo: 'programResponsePanel',
		layout: 'accordion',
		items: [{
			title: 'Customer Info',
			autoLoad: '/admin/program_responses/view/1/user',
			autoHeight: true	
		},{
			title: 'Program Response',
			autoLoad: '/admin/program_responses/view/1/answers',
			autoHeight: true
		},{
			title: 'Documents'
		}]
	});
});
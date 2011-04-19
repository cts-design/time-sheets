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
			autoLoad: '/admin/program_responses/view/'+programResponseId+'/user',
			autoHeight: true	
		},{
			title: 'Program Response',
			autoLoad: '/admin/program_responses/view/'+programResponseId+'/answers',
			autoHeight: true
		},{
			title: 'Documents',
			autoLoad: '/admin/program_responses/view/'+programResponseId+'/documents',
			autoHeight: true			
		}]
	});
});
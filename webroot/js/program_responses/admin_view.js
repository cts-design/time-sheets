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
			autoHeight: true,
			listeners: {
				beforeexpand: updateResponse = function() {
					this.getUpdater().update({
						url: '/admin/program_responses/view/'+programResponseId+'/answers'
					});
					this.removeListener('beforeexpand', updateResponse);
				}
			}				
		},{
			title: 'Documents',
			autoHeight: true,
			listeners: {
				beforeexpand: updateDoc = function() {
					this.getUpdater().update({
						url: '/admin/program_responses/view/'+programResponseId+'/documents'
					});
					this.getUpdater().on('update', function(){
						Ext.get('programPaperForms').on('click', function(e, t){
							t = Ext.get(t);
							if(t.hasClass('generate')) {
								console.log(t);
								var url = '/admin/program_responses/generate_form/'+t.id+'/'+programResponseId; 
								console.log(url);
								Ext.Ajax.request({
									url: url,
									success: function(response, opts) {
										var obj = Ext.decode(response.responseText);
										Ext.Msg.alert('Status', obj.message);
									},
									failure: function() {
										
									}
								})
							}
						}) 
					});
					this.removeListener('beforeexpand', updateDoc);
				}
			}			
		}]
	});

		
	
});
/**
 * @author dnolan
 */

Ext.onReady(function(){
	Ext.QuickTips.init()
	
	var hideProgress = new Ext.util.DelayedTask(function(){
	    progress.hide();
	});
	
	var tb = new Ext.Toolbar({
		hidden: true,
		buttonAlign: 'right',
		items: [{
			text: 'Approve',
			icon: '/img/icons/accept.png',
			handler: function() {
				Ext.Msg.wait('Please wait', 'Status');
				Ext.Ajax.request({
					url: '/admin/program_responses/approve/'+programResponseId,
					success: function(response, opts) {
						var obj = Ext.decode(response.responseText);
						if(obj.success) {
							tb.hide();
							Ext.Msg.alert('Status', obj.message);						
						}
						else {
							opts.failure(response, opts, obj); 						
						}
					},
					failure: function(repsonse, opts, obj) {
						var msg = '';
						if(obj.message) {
							msg = obj.message
						}
						else {
							msg = "An error has occurd";
						}
						Ext.Msg.alert('Status', msg);
					}
				})
			}
		}]
	})
	
	if(requiresApproval) {
		tb.show();
	}
		
	var programResponsePanel = new Ext.Panel({
		title: 'Program Response',
		renderTo: 'programResponsePanel',
		layout: 'accordion',
		tbar: tb,
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
			id: 'documents',
			autoHeight: true,
			listeners: {
				beforeexpand: updateDoc = function() {
					this.getUpdater().update({
						url: '/admin/program_responses/view/'+programResponseId+'/documents'
					});
					this.getUpdater().on('update', function(){
						Ext.get('programPaperForms').on('click', function(e, t){						
							t = Ext.get(t);
							if(t.hasClass('generate') || t.hasClass('regenerate')) {
								e.preventDefault();
								Ext.Msg.progress('Status', 'Generating Form');					
								Ext.Ajax.request({
									url: t.getAttribute('href'),
									success: function(response, opts) {
										var obj = Ext.decode(response.responseText);
										if(obj.success) {
											progress = Ext.Msg.updateProgress(1, 'Complete', obj.message);
											hideProgress.delay(2000);
											Ext.getCmp('documents').getUpdater().update({
												url: '/admin/program_responses/view/'+programResponseId+'/documents'
											});											
										}
										else {
											opts.failure(response, opts);
										}
									},
									failure: function(response, opts) {
										var obj = Ext.decode(response.responseText);
										if(!obj.success) {
											Ext.Msg.alert('Status', obj.message);
										}
										else {
											Ext.Msg.alert('Status', 'An error has occured');
										}
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
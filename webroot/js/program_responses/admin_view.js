/**
 * @author dnolan
 */

var hideProgress = new Ext.util.DelayedTask(function(){
    progress.hide();
});

var approvalForm = new Ext.FormPanel({
    labelWidth: 90, // label settings here cascade unless overridden
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    width: 250,
    height: 180,
    labelAlign: 'top',
    defaults: {width: 230},
    defaultType: 'textarea',
    items: [{
       	fieldLabel: 'Not approved email comment',
       	name: 'email_comment'
       },{
       	fieldLabel : 'Reset customer program response form',
       	xtype: 'checkbox',
       	name: 'reset_form'
       }
    ],
    buttons: [{
        text: 'Not Approved',
        icon: '/img/icons/delete.png',
        handler: function() {
        	menu.hide();
       		Ext.MessageBox.wait();
			approvalForm.getForm().doAction('submit', {
				url: '/admin/program_responses/not_approved',
				params: {
					id: programResponseId
				},
				waitMsg : 'Please wait...',
				waitTitle: 'Status',
				success: function(form, action) {
					var obj = Ext.decode(action.response.responseText);
					if(obj.success) {
						tb.hide();				
						Ext.Msg.alert('Status', obj.message);						
					}
					else {
						opts.failure(response, opts, obj); 						
					}
				},
				failure: function(form, action, obj) {
					var msg = '';
					if(obj.message) {
						msg = obj.message
					}
					else {
						msg = "An error has occurred";
					}
					Ext.Msg.alert('Status', msg);
				}
			});
        }
    }]
});

var menu = new Ext.menu.Menu({
    layout: 'menu',
	items: [
		approvalForm
	]
});	

var tb = new Ext.Toolbar({
	hidden: true,
	buttonAlign: 'center',
	items: [{
		text: 'Approved',
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
						msg = "An error has occurred";
					}
					Ext.Msg.alert('Status', msg);
				}
			});
		}
	},{
		text: 'Not Approved',
		icon: '/img/icons/delete.png',
		id: 'notApproved',
		menu: menu
	}]
});



var programResponsePanel = new Ext.Panel({
	title: 'Program Response',
	width: 950,
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
					Ext.get('ProgramPaperForms').on('click', function(e, t){						
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
	
if(requiresApproval) {
	tb.show();
}
Ext.onReady(function(){
	Ext.QuickTips.init();
	programResponsePanel.render('ProgramResponsePanel');	
});
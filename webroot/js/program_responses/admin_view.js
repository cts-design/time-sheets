/**
 * @author dnolan
 */


// Kludge to fix not being able to type spaces in context menu text fields
Ext.override(Ext.menu.KeyNav, {
  constructor: function(menu) {
    var me = this;
    me.menu = menu;
    me.callParent([menu.el, {
      down: me.down,
      enter: me.enter,
      esc: me.escape,
      left: me.left,
      right: me.right,
      //space: me.enter,
      tab: me.tab,
      up: me.up
    }]);
  }
});

Ext.define('ProgramFormActivity', {
  extend: 'Ext.data.Model',
  fields: ['id', 'name']
});

Ext.create('Ext.data.Store', {
  model: 'ProgramFormActivity',
  storeId: 'programFormActivites',
  proxy: {
    type: 'ajax',
    url: '/admin/program_responses/get_form_activities/'+programResponseId,
    reader: {
      type: 'json',
      root: 'activities'
    }
  }
});

Ext.define('PendingApprovalProgramResponse', {
  extend: 'Ext.data.Model',
  fields: ['id']
});

Ext.create('Ext.data.Store', {
  model: 'PendingApprovalProgramResponse',
  storeId: 'pendingApprovalResponses',
  autoLoad: true,
  proxy: {
    type: 'ajax',
    url: '/admin/program_responses/get_pending_approval_responses/'+programId,
    reader: {
      type: 'json',
      root: 'responses'
    }
  }
});

Ext.onReady(function(){
  Ext.QuickTips.init();

  var hideProgress = new Ext.util.DelayedTask(function(){
    progress.hide();
  });

  var approvalForm = Ext.create('Ext.form.Panel', {
    fieldDefaults: {
      labelWidth: 90,
      labelAlign: 'top',
      width: 430
    },
    frame:true,
    bodyStyle:'padding:5px 5px 0',
    width: 450,
    height: 250,
    defaultType: 'textarea',
    items: [{
      fieldLabel: 'Not approved email comment',
      name: 'email_comment'
     },{
      fieldLabel : 'Allow customer to edit the selected form(s)',
      xtype: 'boxselect',
      store: 'programFormActivites',
      name: 'reset_form',
      displayField: 'name',
      valueField: 'id',
      emptyText: 'Please select'
    }],
    buttons: [{
      text: 'Not Approved',
      icon: '/img/icons/delete.png',
      handler: function() {
        menu.hide();
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
              Ext.getCmp('approved').hide();
              Ext.getCmp('notApproved').hide();
              Ext.Msg.alert('Status', obj.message);
            }
            else {
              opts.failure(response, opts, obj);
            }
          },
          failure: function(form, action, obj) {
            var msg = '';
            if(obj.message) {
              msg = obj.message;
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

  var menu = Ext.create('Ext.menu.Menu', {
    layout: 'menu',
    items: [
      approvalForm
    ]
  });

  var programResponsePanel = Ext.create('Ext.panel.Panel', {
    title: progName + ' - Program Response',
    renderTo: 'ProgramResponsePanel',
    width: 950,
    height: 650,
    dockedItems: [{
      xtype: 'toolbar',
      dock: 'top',
      height: 25,
      id: 'tB',
      items: [{
        text: 'Approved',
        id: 'approved',
        hidden: true,
        icon: '/img/icons/accept.png',
        handler: function() {
          Ext.Msg.wait('Please wait', 'Status');
          Ext.Ajax.request({
            url: '/admin/program_responses/approve/'+programResponseId,
            success: function(response, opts) {
              var obj = Ext.decode(response.responseText);
              if(obj.success) {
                Ext.getCmp('approved').hide();
                Ext.getCmp('notApproved').hide();
                Ext.Msg.alert('Status', obj.message);
              }
              else {
                opts.failure(response, opts, obj);
              }
            },
            failure: function(repsonse, opts, obj) {
              var msg = '';
              if(obj.message) {
                msg = obj.message;
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
        id: 'notApproved',
        hidden: true,
        icon: '/img/icons/delete.png',
        menu: menu
      }]
    }],
    layout: 'border',
    items: [{
      layout: 'hbox',
      height: 300,
      region: 'center',
      items: [{
        title: 'Customer Info',
        flex: 1,
        height: 300,
        autoScroll: true,
        autoLoad: '/admin/program_responses/view/'+programResponseId+'/user'
      },{
        title: 'Documents',
        id: 'documents',
        autoScroll: true,
        height: 300,
        flex: 1,
        autoLoad: '/admin/program_responses/view/'+programResponseId+'/documents',
        listeners: {
          beforeexpand: updateDoc = function() {
            this.getLoader().load();
            this.getLoader().on('load', function(){
              Ext.get('ProgramPaperForms').on('click', function(e, t){
                t = Ext.get(t);
                if(t.hasCls('generate') || t.hasCls('regenerate')) {
                  e.preventDefault();
                  Ext.Msg.progress('Status', 'Generating Form');
                  Ext.Ajax.request({
                    url: t.getAttribute('href'),
                    success: function(response, opts) {
                      var obj = Ext.decode(response.responseText);
                      if(obj.success) {
                        progress = Ext.Msg.updateProgress(1, 'Complete', obj.message);
                        hideProgress.delay(2000);
                        Ext.getCmp('documents').getLoader().load();
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
                  });
                }
              });
            });
            this.removeListener('beforeexpand', updateDoc);
          }
        }
      }]
      },{
        title: 'Program Response',
        autoScroll: true,
        region: 'south',
        height: 300,
        autoLoad: '/admin/program_responses/view/'+programResponseId+'/answers',
        listeners: {
          beforeexpand: updateResponse = function() {
            this.getLoader().load();
            this.removeListener('beforeexpand', updateResponse);
          }
        }
    }]
  });
  if(requiresApproval) {
    Ext.getCmp('approved').show();
    Ext.getCmp('notApproved').show();
  }
});

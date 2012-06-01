Ext.define('Audit', {
  id: 'Audit',
  extend: 'Ext.data.Model',
  fields: [{
    name: 'id',
    type: 'int'
  }, 'name', {
    name: 'start_date',
    type: 'date',
    dateFormat: 'Y-m-d'
  }, {
    name: 'end_date',
    type: 'date',
    dateFormat: 'Y-m-d'
  }, {
    name: 'number_of_customers',
    type: 'int'
  }, {
    name: 'number_of_auditors',
    type: 'int'
  }, 'customers', {
    name: 'show_date_column',
    type: 'int'
  }, {
    name: 'disabled',
    type: 'int'
  }, {
    name: 'created',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }],
  hasMany: { model: 'User', name: 'users' }
});

Ext.define('User', {
  id: 'User',
  extend: 'Ext.data.Model',
  fields: [{
    name: 'id',
    type: 'int'
  }, 'ssn']
});

Ext.create('Ext.data.Store', {
  autoLoad: true,
  autoSync: true,
  model: 'Audit',
  storeId: 'AuditStore',
  proxy: {
    type: 'ajax',
    api: {
      create: '/admin/audits/create',
      read: '/admin/audits/read',
      update: '/admin/audits/update',
      destroy: '/admin/audits/destroy'
    },
    reader: { type: 'json', root: 'audits' },
    writer: { type: 'json', encode: true, root: 'audits', writeAllFields: false }
  }
});

Ext.create('Ext.data.Store', {
  model: 'User',
  storeId: 'UserStore'
});

Ext.onReady(function () {
    Ext.create('Ext.form.Panel', {
      renderTo: 'audit-form',
      bodyPadding: 10,
      collapsed: true,
      collapsible: true,
      height: 505,
      id: 'formPanel',
      title: 'Audit Form',
      trackResetOnLoad: true,
      width: 950,
      items: [{
        xtype: 'fieldset',
        height: 138,
        padding: 15,
        width: 928,
        layout: {
          type: 'column'
        },
        title: 'Audit Fields',
        items: [{
          xtype: 'textfield',
          allowBlank: false,
          fieldLabel: 'Audit Name',
          margin: '0 100 0 40',
          name: 'name',
          width: 350
        }, {
          xtype: 'datefield',
          allowBlank: false,
          fieldLabel: 'Start Date',
          name: 'start_date',
          width: 350
        }, {
          xtype: 'slider',
          id: 'auditorSlider',
          fieldLabel: 'Number of Auditors',
          labelPad: 0,
          labelWidth: 150,
          margin: '5 100 0 40',
          maxValue: 50,
          minValue: 1,
          name: 'number_of_auditors',
          value: 1,
          width: 350
        }, {
          xtype: 'datefield',
          allowBlank: false,
          fieldLabel: 'End Date',
          name: 'end_date',
          width: 350
        }, {
          xtype: 'checkbox',
          fieldLabel: 'Show Date Column',
          id: 'showDateColumn',
          inputValue: 1,
          labelPad: 0,
          labelWidth: 150,
          margin: '5 100 0 40',
          name: 'show_date_column',
          uncheckedValue: 0,
          width: 350
        }]
      }, {
        xtype: 'tabpanel',
        height: 263,
        margin: '0 0 18',
        width: 928,
        activeTab: 0,
        items: [{
          xtype: 'panel',
          height: 220,
          padding: '10 20 20',
          width: 905,
          title: 'Paste List of Customers',
          items: [{
            xtype: 'textareafield',
            id: 'customersField',
            allowBlank: false,
            fieldLabel: 'Paste In a List of Customers (One Per Line)',
            height: 205,
            labelAlign: 'top',
            name: 'customers',
            width: 886
          }]
        }]
      }],
      buttons: [{
        disabled: true,
        formBind: true,
        margin: '8 0 0',
        text: 'Save Audit',
        handler: function () {
          var form = Ext.getCmp('formPanel').getForm(),
            formValues = form.getValues(),
            dirty = form.isDirty(),
            start_date = new Date(formValues.start_date),
            end_date = new Date(formValues.end_date),
            today = new Date(),
            customers = formValues.customers.split('\n'),
            gridPanel = Ext.getCmp('gridPanel'),
            selectedRecord = gridPanel.getSelectionModel().getSelection()[0],
            auditStore = Ext.data.StoreManager.lookup('AuditStore');

          formValues.created = Ext.Date.format(today, 'Y-m-d H:i:s');
          formValues.start_date = Ext.Date.format(start_date, 'Y-m-d');
          formValues.end_date = Ext.Date.format(end_date, 'Y-m-d');

          console.log(selectedRecord);

          if (form.isValid()) {
            if (selectedRecord) {
              selectedRecord.set(formValues);
              gridPanel.getStore().sync();
            } else {
              gridPanel.getStore().add(formValues);
            }
          }

          task = new Ext.util.DelayedTask(function () {
            gridPanel.getStore().load();
          });
          task.delay(250);
          form.reset();
        }
      }]
    });

    Ext.create('Ext.grid.Panel', {
      renderTo: 'audits',
      collapsible: true,
      height: 250,
      id: 'gridPanel',
      store: 'AuditStore',
      title: 'Audits',
      width: 950,
      viewConfig: {
        emptyText: 'There are no audits in the system'
      },
      columns: [{
        dataIndex: 'id',
        hidden: true,
        text: 'Id',
        width: 56
      }, {
        xtype: 'gridcolumn',
        dataIndex: 'name',
        flex: 1,
        text: 'Audit Name',
        width: 250
      }, {
        dataIndex: 'number_of_customers',
        text: 'Customers'
      }, {
        dataIndex: 'number_of_auditors',
        text: 'Auditors'
      }, {
        dataIndex: 'show_date_column',
        text: 'Show Date Column',
        renderer: function (value) {
          if (value) {
            return 'Yes';
          } else {
            return 'No';
          }
        }
      }, {
        xtype: 'datecolumn',
        dataIndex: 'start_date',
        flex: 1,
        text: 'Start Date',
        width: 150
      }, {
        xtype: 'datecolumn',
        dataIndex: 'end_date',
        text: 'End Date',
        width: 150
      }, {
        xtype: 'datecolumn',
        dataIndex: 'created',
        text: 'Created',
        width: 150
      }],
      dockedItems: [{
        xtype: 'toolbar',
        dock: 'top',
        items: [{
          icon: '/img/icons/add.png',
          id: 'newAuditBtn',
          text: 'New Audit',
          handler: function () {
            var selModel = Ext.getCmp('gridPanel').getSelectionModel(),
              editAuditBtn = Ext.getCmp('editAuditBtn'),
              deleteAuditBtn = Ext.getCmp('deleteAuditBtn'),
              excelDownloadBtn = Ext.getCmp('excelDownloadBtn'),
              formPanel = Ext.getCmp('formPanel'),
              form = formPanel.getForm(),
              slider = Ext.getCmp('auditorSlider'),
              sliderValue = slider.originalValue,
              dirtyFields = form.getValues(false, true);

            if (formPanel.collapsed) {
              formPanel.expand(true);
            }

            if (Ext.Object.getSize(dirtyFields) !== 0) {
              Ext.MessageBox.show({
                title: 'Are you sure?',
                msg: 'The audit form has not been saved, would you like to continue anyway?',
                buttons: Ext.MessageBox.YESNO,
                icon: Ext.MessageBox.WARNING,
                scope: this,
                fn: function (btn) {

                  if (btn === 'yes') {

                    form.getFields().each(function (field) {
                      field.originalValue = undefined;
                      if (field.name === 'number_of_auditors') {
                        field.originalValue = 1;
                      }
                    });

                    form.reset().clearInvalid();
                  }
                }
              });
            } else {
              form.getFields().each(function (field) {
                field.originalValue = undefined;
                if (field.name === 'number_of_auditors') {
                  field.originalValue = 1;
                }
              });

              form.reset().clearInvalid();
            }

            deleteAuditBtn.disable();
            editAuditBtn.disable();
            excelDownloadBtn.disable();
            slider.enable();

            selModel.deselectAll();
            this.selectedRecord = null;
          },
        }, {
          disabled: true,
          icon: '/img/icons/edit.png',
          id: 'editAuditBtn',
          text: 'Edit Audit',
          handler: function () {
            var slider = Ext.getCmp('auditorSlider'),
              customersField = Ext.getCmp('customersField'),
              showDate = Ext.getCmp('showDateColumn'),
              formPanel = Ext.getCmp('formPanel'),
              form = formPanel.getForm(),
              selectedRecord = Ext.getCmp('gridPanel').getSelectionModel().getSelection()[0],
              customers;

            if (formPanel.collapsed) {
              formPanel.expand(true);
            }

            formPanel.loadRecord(selectedRecord);

            if (selectedRecord.data.show_date_column) {
              showDate.setValue(true);
            }

            customers = '';
            selectedRecord.users().each(function (user) {
              var formattedString = user.get('ssn') + "\n";
              customers += formattedString;
            });

            customersField.setValue(customers);
            slider.disable();
          }
        }, {
          disabled: true,
          icon: '/img/icons/delete.png',
          id: 'deleteAuditBtn',
          text: 'Delete Audit',
          handler: function () {
            var gridPanel = Ext.getCmp('gridPanel'),
              selectedRecord = gridPanel.getSelectionModel().getSelection()[0],
              form = Ext.getCmp('formPanel').getForm();

            selectedRecord.set('disabled', 1);
            gridPanel.getStore().sync();

            var task = new Ext.util.DelayedTask(function () {
              gridPanel.getStore().load();
            });

            task.delay(250);
            form.reset();
            gridPanel.getSelectionModel().deselectAll();
          }
        }, {
          xtype: 'splitbutton',
          disabled: true,
          icon: '/img/icons/excel.png',
          id: 'excelDownloadBtn',
          text: 'Export...',
          menu: new Ext.menu.Menu({
            items: [{
              icon: '/img/icons/excel.png',
              id: 'auditorListBtn',
              text: 'Auditor List',
              handler: function () {
                var gridPanel = Ext.getCmp('gridPanel'),
                  selectedRecord = gridPanel.getSelectionModel().getSelection()[0],
                  auditId = selectedRecord.data.id;

                window.location = '/admin/audits/view/' + auditId + '/auditors';
              }
            }, {
              icon: '/img/icons/excel.png',
              id: 'customerListBtn',
              text: 'Customer List',
              handler: function () {
                var gridPanel = Ext.getCmp('gridPanel'),
                  selectedRecord = gridPanel.getSelectionModel().getSelection()[0],
                  auditId = selectedRecord.data.id;

                window.location = '/admin/audits/view/' + auditId + '/customers';
              }
            }]
          })
        }]
      }, {
        xtype: 'pagingtoolbar',
        displayInfo: true,
        dock: 'bottom',
        store: Ext.data.StoreManager.lookup('AuditStore')
      }],
      listeners: {
        itemclick: function () {
          var editAuditBtn = Ext.getCmp('editAuditBtn'),
            excelDownloadBtn = Ext.getCmp('excelDownloadBtn'),
            deleteAuditBtn = Ext.getCmp('deleteAuditBtn'),
            slider = Ext.getCmp('auditorSlider'),
            customersField = Ext.getCmp('customersField'),
            showDate = Ext.getCmp('showDateColumn'),
            customers,
            selectedRecord = Ext.getCmp('gridPanel').getSelectionModel().getSelection()[0],
            formPanel = Ext.getCmp('formPanel'),
            form = formPanel.getForm();

          editAuditBtn.enable();
          excelDownloadBtn.enable();
          deleteAuditBtn.enable();

          if (formPanel.collapsed) {
            formPanel.expand(true);
          }

          formPanel.loadRecord(selectedRecord);

          if (selectedRecord.data.show_date_column) {
            showDate.setValue(true);
          }

          customers = '';
          selectedRecord.users().each(function (user) {
            var formattedString = user.get('ssn') + "\n";
            customers += formattedString;
          });

          customersField.setValue(customers);
          slider.disable();
        }
      }
    });
});

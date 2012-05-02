var Programs;

Programs = {
  init: function() {
    this.programStore = Ext.create('Atlas.store.ProgramStore');
    this.programStore.load();
  },

  onReady: function() {
    this.init();

    this.programGrid = Ext.widget('atlasprogramgrid', {
      renderTo: 'programGrid'
    });

    this.newProgram = Ext.create('Ext.panel.Panel', {
      id: 'newProgramPanel',
      renderTo: 'programForm',
      layout: 'card',
      items: [{
        xtype: 'atlasprogramform'
      }, {
        xtype: 'form',
        items: [{
          xtype: 'textfield',
          fieldLabel: 'Test'
        }]
      }],
      dockedItems: [{
        xtype: 'toolbar',
        dock: 'bottom',
        layout: {
          pack: 'end',
          type: 'hbox'
        },
        items: [{
          xtype: 'buttongroup',
          frame: false,
          width: 93,
          columns: 2,
          items: [{
            xtype: 'button',
            id: 'move-prev',
            text: '« Prev',
            handler: function(btn) {
              this.navigate('prev');
            },
            scope: this
          }, {
            xtype: 'button',
            id: 'move-next',
            text: 'Next »',
            handler: function(btn) {
              this.navigate('next');
            },
            scope: this
          }]
        }]
      }]
    });
  },

  navigate: function(direction) {
    var layout = this.newProgram.getLayout();
    layout[direction]();
    Ext.getCmp('move-prev').setDisabled(!layout.getPrev());
    Ext.getCmp('move-next').setDisabled(!layout.getNext());
  }
};

Ext.onReady(Programs.onReady, Programs);

Ext.define('Atlas.view.programs.TabPanel', {
  extend: 'Ext.tab.Panel',
  alias: 'widget.atlas.tabpanel',
  layout: 'atlas.card',
  panelCount: 1,

  defaults: {
    border: false,
    bodyPadding: 18
  },

  prevBtnText: '« Back',
  nextBtnText: 'Save &amp; Continue »',
  lastBtnText: 'Finish',

  initComponent: function () {
    var me = this;

    Ext.applyIf(me, {
      dockedItems: [{
        xtype: 'statusbar',
        busyText: 'Saving...',
        defaultText: '',
        dock: 'bottom',
        id: 'statusbar',
        items: [{
          xtype: 'progressbar',
          id: 'progressbar',
          text: '',
          width: 100
        }, '->' , {
          xtype: 'button',
          id: 'next',
          text: this.nextBtnText,
          handler: function (btn) {
            var me = this,
              layout = me.getLayout(),
              activeItem = layout.getActiveItem();

            if (typeof activeItem.process === 'function') {
              activeItem.process();
            }
          },
          scope: this
        }, {
          xtype: 'button',
          icon: '/img/icons/accept.png',
          iconAlign: 'right',
          id: 'last',
          hidden: true,
          text: this.lastBtnText,
          handler: function (btn) {
            this.finish();
          },
          scope: this
        }]
      }]
    });

    me.callParent(arguments);
  },

  navigate: function (direction) {
    var me = this,
      layout = me.getLayout(),
      activeItem = layout.getActiveItem();

    if (layout.isPaneValid()) {
      layout[direction]();

      // Modify the toolbar buttons based on state
      Ext.getCmp('previous').setDisabled(!layout.getPrev());
      if (!layout.getNext()) {
        Ext.getCmp('next').setDisabled(true).setVisible(false);
        Ext.getCmp('last').setDisabled(false).setVisible(true);
      } else {
        Ext.getCmp('last').setDisabled(true).setVisible(false);
        Ext.getCmp('next').setDisabled(false).setVisible(true);
      }
    }
  },

  finish: function () {
    console.log('COMPLETE');
  }
});

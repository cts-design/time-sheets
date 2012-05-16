Ext.define('Atlas.layout.container.Card', {
  extend: 'Ext.layout.container.Card',
  alias: 'layout.atlas.card',

  configureItem: function () {
    var me = this;
    me.callParent(arguments);
  },

  isPaneValid: function () {
    var isValid = true,
      activePane = this.getActiveItem();

    if (typeof activePane.form === 'object') {
      Ext.each(activePane.items.items, function (item) {
        if (item.xtype === 'fieldset' || item.xtype === 'fieldcontainer') {
          Ext.each(item.items.items, function (i) {
            if (i.isFormField && !i.isValid()) {
              i.markInvalid('Invalid Field');
              isValid = false;
            }
          });
        } else if (item.isFormField && !item.isValid()) {
          item.markInvalid();
          isValid = false;
        }
      });
    }

    return isValid;
  }
});

Ext.define('Atlas.chart.TotalUnduplicatedIndividuals', {
  extend: 'Ext.chart.Chart',
	alias: 'widget.totalunduplicated',

  height: 300,
  width: 900,
  animate: true,
	shadow: true,
	legend: {
		position: 'right'
	},
  // insetPadding: 20,

  initComponent: function() {
    var me = this;
    me.callParent(arguments);
  }
});

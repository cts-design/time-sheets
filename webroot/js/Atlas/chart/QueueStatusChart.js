Ext.define('Atlas.chart.QueueStatusChart', {
  extend: 'Ext.chart.Chart',
	alias: 'widget.queuestatus',

  height: 276,
  width: 829,
  animate: true,
	shadow: true,
  insetPadding: 20,

  initComponent: function() {
    var me = this;

    Ext.applyIf(me, {
			axes: [{
				type: 'Numeric',
				fields: ['total'],
				position: 'left',
				title: 'Total Unduplicated Individuals',
        minimum: 0,
        grid: true,
        label: {
          renderer: Ext.util.Format.numberRenderer('0,0')
        }
			}, {
				type: 'Category',
				title: 'Day of the Week',
				position: 'bottom',
				fields: ['day']
			}],
			series: [{
				type: 'column',
        axis: 'left',
        highlight: true,
        tips: {
          trackMouse: true,
          width: 140,
          height: 28,
          renderer: function (storeItem, item) {
            this.setTitle(storeItem.get('day') + ': ' + storeItem.get('total'));
          }
        },
        label: {
          display: 'insideEnd',
          'text-anchor': 'middle',
          field: 'total',
          renderer: Ext.util.Format.numberRenderer('0'),
          orientation: 'vertical',
          color: '#333'
        },
				xField: 'day',
				yField: 'total'
			}]
    });

    me.callParent(arguments);
  }
});

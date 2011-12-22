Ext.define('Atlas.view.ui.ReportsPanel', {
  extend: 'Ext.panel.Panel',
	alias: 'widget.atlas-reports',

  frame: true,
  height: 541,
  width: 831,
  layout: {
    type: 'border'
  },
  title: 'Reports',

  initComponent: function() {
    var me = this;

    Ext.applyIf(me, {
      items: [
        {
          xtype: 'form',
          height: 200,
          layout: {
            type: 'auto'
          },
          bodyPadding: '5px 10px',
          collapsible: true,
          title: 'Filters',
          margins: '0 0 10px',
          region: 'north',
          items: [
            {
              xtype: 'combobox',
              fieldLabel: 'Update Interval'
            },
            {
              xtype: 'fieldset',
              height: 104,
              width: 800,
              layout: {
                type: 'column'
              },
              title: 'Filter By',
              items: [
                {
                  xtype: 'combobox',
                  margin: '0 75px 0 0',
                  width: 350,
                  fieldLabel: 'Location(s)'
                },
                {
                  xtype: 'combobox',
                  width: 350,
                  fieldLabel: 'Program(s)'
                },
                {
                  xtype: 'combobox',
                  margin: '0 75px 0 0',
                  width: 350,
                  fieldLabel: 'Admin(s)'
                },
                {
                  xtype: 'combobox',
                  width: 350,
                  fieldLabel: 'Admin(s)'
                },
                {
                  xtype: 'datefield',
                  margin: '0 8px 0 0',
                  width: 220,
                  fieldLabel: 'From'
                },
                {
                  xtype: 'timefield',
                  margin: '0 75px 0 0',
                  width: 122
                },
                {
                  xtype: 'datefield',
                  margin: '0 8px 0 0',
                  width: 220,
                  fieldLabel: 'To'
                },
                {
                  xtype: 'timefield',
                  width: 122
                }
              ]
            }
          ]
        },
        {
          xtype: 'tabpanel',
          activeTab: 2,
          region: 'center',
          items: [
            {
              xtype: 'panel',
              title: 'Overall Traffic',
              items: [
                {
                  xtype: 'chart',
                  height: 276,
                  width: 829,
                  animate: true,
                  insetPadding: 20,
                  axes: [
                    {
                      type: 'Category',
                      fields: [
                        'x'
                      ],
                      position: 'bottom',
                      title: 'Category Axis'
                    },
                    {
                      type: 'Numeric',
                      fields: [
                        'y'
                      ],
                      position: 'left',
                      title: 'Numeric Axis'
                    }
                  ],
                  series: [
                    {
                      type: 'line',
                      xField: 'x',
                      yField: [
                        'y'
                      ],
                      fill: true,
                      smooth: 0
                    }
                  ]
                }
              ]
            },
            {
              xtype: 'panel',
              title: 'Queue Status',
              items: [
                {
                  xtype: 'chart',
                  height: 276,
                  width: 774,
                  animate: true,
                  insetPadding: 20,
                  series: [
                    {
                      type: 'pie',
                      label: {
                        field: 'x',
                        display: 'rotate',
                        contrast: true,
                        font: '12px Arial'
                      },
                      showInLegend: true,
                      angleField: 'y'
                    }
                  ]
                }
              ]
            }
          ]
        }
      ]
    });

    me.callParent(arguments);
  }
});
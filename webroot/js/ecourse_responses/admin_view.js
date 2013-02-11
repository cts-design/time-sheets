/**
 * @author dnolan
 */
Ext.define('EcourseResponse', {
    extend: 'Ext.data.Model',
    fields: ['id', 'module', 'pass_fail', 'time_on_quiz', 'time_on_media', 'total_time', {
        name: 'score',
        type: 'int'
    }]
});

Ext.create('Ext.data.Store', {
  model: 'EcourseResponse',
  storeId: 'ecourseProgramResponsesStore',
  groupField: 'module',
  autoLoad: true,
  proxy: {
    type: 'ajax',
    url: '/admin/ecourse_responses/view/'+ecourseId,
    reader: {
      type: 'json',
      root: 'response'
    }
  }
});

Ext.create('Ext.grid.Panel', {
    height: 240,
    id: 'modulesGrid',
    store: 'ecourseProgramResponsesStore',
    viewConfig: {
        getRowClass: function(record, rowIndex, rp, ds){
            if(record.data.pass_fail === 'Pass'){
                return 'correct-row'; 
            } 
            return 'incorrect-row';
        }
    }, 
    features: [{
        groupHeaderTpl: 'Module: {name}',
        ftype: 'groupingsummary'
    }],
    columns: [{
        dataIndex: 'id',
        text: 'Attempt ID#',
        summaryType: 'count',
        summaryRenderer: function(value){
            return Ext.String.format('{0} attempt{1}', value, value !== 1 ? 's' : '');
        }
    }, {
        dataIndex: 'score',
        xtype: 'templatecolumn',
        tpl: '{score}%',
        text: 'Score',
        summaryType: 'average',
        summaryRenderer: function(value) {
          return 'average ' + value + '%';
        }
    },{
      dataIndex: 'pass_fail',
      text: 'Pass/Fail',
      summaryType: 'max'
    },{
      dataIndex: 'time_on_media',
      text: 'Time on Media',
      xtype: 'templatecolumn',
      tpl: '{time_on_media} mins',
      summaryType: 'sum',
      summaryRenderer: function(value) {
        return 'total ' + value + ' mins';
      }
    },{
      dataIndex: 'time_on_quiz',
      text: 'Time on Quiz',
      xtype: 'templatecolumn',
      tpl: '{time_on_quiz} mins',
      summaryType: 'sum',
      summaryRenderer: function(value) {
        return 'total ' + value + ' mins';
      }
    },{
      dataIndex: 'total_time',
      text: 'Total Time',
      xtype: 'templatecolumn',
      tpl: '{total_time} mins',
      summaryType: 'sum',
      summaryRenderer: function(value) {
        return 'total ' + value + ' mins';
      }
    }]
});

Ext.onReady(function(){
  Ext.QuickTips.init();

  Ext.create('Ext.panel.Panel', {
    title: ecourseName + ' - Results for ' + userName,
    renderTo: 'EcourseResponsePanel',
    width: 950,
    height: 500,
    items: ['modulesGrid']
  });
});

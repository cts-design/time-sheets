Ext.define('Atlas.model.ProgramResponseDoc', {
  extend: 'Ext.data.Model',
  fields: [{
    name: 'id',
    type: 'int'
  }, {
    name: 'cat_id',
    type: 'int'
  }, {
    name: 'program_response_id',
    type: 'int'
  }, {
    name: 'doc_id',
    type: 'int'
  }, {
    name: 'type',
    type: 'string'
  }, {
    name: 'deleted',
    type: 'boolean'
  }, {
    name: 'deleted_reason',
    type: 'string'
  }, {
    name: 'rejected_reason',
    type: 'string'
  }, {
    name: 'created',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }, {
    name: 'modified',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }],
  associations: [{
    type: 'belongsTo',
    model: 'ProgramResponse',
    name: 'program_responses'
  }, {
    type: 'belongsTo',
    model: 'FiledDocument',
    name: 'filed_documents'
  }]
});

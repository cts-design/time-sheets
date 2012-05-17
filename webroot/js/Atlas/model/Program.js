Ext.define('Atlas.model.Program', {
  extend: 'Ext.data.Model',
  fields: [{
    name: 'id',
    type: 'int'
  }, {
    name: 'name',
    type: 'string'
  }, {
    name: 'type',
    type: 'string'
  }, {
    name: 'atlas_registration_type',
    type: 'string'
  }, {
    name: 'disabled',
    type: 'boolean'
  }, {
    name: 'queue_category_id',
    type: 'int'
  }, {
    name: 'approval_required',
    type: 'boolean'
  }, {
    name: 'form_esign_required',
    type: 'boolean'
  }, {
    name: 'confirmation_id_length',
    type: 'int'
  }, {
    name: 'response_expires_in',
    type: 'int'
  }, {
    name: 'created',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }, {
    name: 'modified',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }, {
    name: 'expires',
    type: 'date',
    dateFormat: 'Y-m-d H:i:s'
  }]
});

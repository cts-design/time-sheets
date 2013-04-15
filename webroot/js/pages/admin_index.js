Ext.onReady(function () {
  Ext.define('Page', {
    extend: 'Ext.data.Model',
    fields: [
      { name: 'id', type: 'int' },
      'title',
      'slug',
      { name: 'published', type: 'int' },
      { name: 'authentication_required', type: 'int' },
      { name: 'locked', type: 'int' }
    ]
  });

  Ext.create('Ext.data.Store', {
    storeId: 'PagesStore',
    autoLoad: true,
    model: 'Page',
    proxy: {
      type: 'ajax',
      api: {
        read: '/admin/pages/index'
      },
      reader: {
        type: 'json',
        root: 'pages'
      }
    }
  });

  Ext.create('Ext.ux.LiveSearchGridPanel', {
    id: 'gridPanel',
    renderTo: 'pages-index',
    store: 'PagesStore',
    title: 'Pages',
    height: 400,
    columns: [{
      text: 'Id',
      align: 'center',
      dataIndex: 'id',
      hidden: true,
      width: 50
    }, {
      text: 'Title',
      dataIndex: 'title',
      flex: 1
    }, {
      text: 'Slug',
      dataIndex: 'slug',
      flex: 1
    }, {
      text: 'Published',
      align: 'center',
      dataIndex: 'published',
      width: 60,
      renderer: function (value) {
        switch (value) {
          case 1:
            return "Yes";
            break;

          case 0:
            return "No";
            break;
        }
      }
    }, {
      text: 'Locked',
      align: 'center',
      dataIndex: 'locked',
      width: 50,
      renderer: function (value) {
        switch (value) {
          case 1:
            return "Yes";
            break;

          case 0:
            return "No";
            break;
        }
      }
    }, {
      text: 'Authentication Required',
      align: 'center',
      dataIndex: 'authentication_required',
      width: 150,
      renderer: function (value) {
        switch (value) {
          case 1:
            return "Yes";
            break;

          case 0:
            return "No";
            break;
        }
      }
    }],
    listeners: {
      render: function () {
        var toolbar = Ext.getCmp('gridPanel').getDockedItems('toolbar[dock="top"]')[0];
        toolbar.insert(0, {
          icon: '/img/icons/add.png',
          text: 'New Page',
          handler: function () {
            window.location = '/admin/pages/add';
          }
        });
        toolbar.insert(1, '->');
      }
    }
  });
});

<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php
    // @TODO add these to the head of the layout when we integrate ExtJS throughout the project
    $this->Html->script('ext/adapter/ext/ext-base-debug', array('inline' => FALSE));
    $this->Html->script('ext-all-debug', array('inline' => FALSE));
	$this->Html->script('ext/ux/RowEditor', array('inline' => FALSE));
?>
<script type="text/javascript">
	var vals = null;
	Ext.onReady(function(){
		Ext.QuickTips.init();
	    var searchForm = new Ext.FormPanel({
	        frame:true,
	        labelWidth: 75,
	        title: 'Search Form',
	        width: 300,
	        id: 'search-form',
	        collapsible: true,
	        defaultType: 'textfield',
	        items: [{
	            	fieldLabel: 'Search Type',
	            	xtype: 'radiogroup',
	            	items: [{
	            		boxLabel: 'Last Name',
	            		name: 'searchType',
	            		inputValue: 'lastname',
	            	},{
	     	           	boxLabel: 'Last 4 SSN',
	            		name: 'searchType', 
	            		inputValue: 'ssn',	
	            	}]    	
	            },	             
	        	{
	                fieldLabel: 'Search',
	                name: 'search',
	                width: 200
	            }
	        ],
	        buttons: [{
	            text: 'Search',
	            handler: function(){
	            	vals = Ext.getCmp('search-form').getForm().getValues();
	            	console.log(vals);
	            	store.load({
	            		params: {
	            			search: vals.search,
	            			searchType: vals.searchType
	            		}
	            	});
	            }
	        }]
    	});
    	searchForm.render('search');

	    var editor = new Ext.ux.grid.RowEditor({
	        saveText: 'Update'
	    });
	    
		var proxy = new Ext.data.HttpProxy({
		    url: '/admin/users/resolve_login_issues'
		});
	     
	    var reader = new Ext.data.JsonReader({
		    root: 'users',
		    idProperty: 'id',
		    successProperty: "success",
		    fields: ['id', 'firstname', 'lastname', 'ssn']	    	
	    });
				
		var writer = new Ext.data.JsonWriter({
		    encode: true
		});
	
		var store = new Ext.data.Store({
			storeId: 'user',
			root: 'users',
			proxy: proxy,
			reader: reader,
			writer: writer,
			listeners: {
				save: function() {
					store.reload({
	            		params: {
	            			search: vals.search,
	            			searchType: vals.searchType
	            		}
	            	});
	            	Ext.Msg.alert('Status', 'Changes saved successfully.');
				}
			}
		}); 
		
		var adminStore = new Ext.data.JsonStore({
			url: '/admin/users/get_admin_list',
			storeId: 'adminStore',
			root: 'admins',
			idProperty: 'id',
			fields: ['id', 'name']
		});		   	
        
		var combo = new Ext.form.ComboBox({
		    typeAhead: true,
		    triggerAction: 'all',
		    mode: 'remote',
		    store: adminStore,
		    valueField: 'id',
		    displayField: 'name'
		});
	    
        var tb = new Ext.Toolbar({
        	width: 300,
        	items: [combo,{
				text: 'Request SSN Change',
				icon: '/img/icons/email_go.png',
				handler: function(){
					var row = grid.getSelectionModel().getSelected();
					var adminId = combo.getValue()		
					Ext.Ajax.request({
						url: '/admin/users/request_ssn_change',
						params: {
							userId: row.id,
							adminId: adminId
						}
					});
				}
        	}]
        });
          	
    	var grid = new Ext.grid.GridPanel({
    		store: store,
    		height: 300,
    		title: 'Customers',
    		width: 325,
    		frame: true,
    		plugins: [editor],
    		columns: [{
    			id: 'firstname',
    			header: 'First Name',
    			dataIndex: 'firstname',
    			width: 100
    		},{
    			header: 'Last Name',
    			dataIndex: 'lastname',
    		 	editor: new Ext.form.TextField({}),
    		 	width: 100
    		},{
    			header: 'SSN Last 4',
    			dataIndex: 'ssn',
    			width: 80			
    		}],
    		tbar: tb
    	});
    	
    	grid.render('grid');	
	});
</script>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Resolve Login Issues', null, 'unique') ; ?>
</div>
<div id="search"></div>
<br />
<div id="grid"></div>
<div class="admin">
    <div class="actions ui-widget-header">
		<ul></ul>
	</div>
	<div>
		<?php echo $this->Form->create('User', array('action' => 'resolve_login_issues')); ?>
		
	</div>
</div>
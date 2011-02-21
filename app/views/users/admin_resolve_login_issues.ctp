<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
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
	Ext.onReady(function(){
		Ext.QuickTips.init();
	    var searchForm = new Ext.FormPanel({
	        labelWidth: 75, // label settings here cascade unless overridden
	        url:'/admin/users/resolve_login_issues',
	        frame:true,
	        title: 'Search Form',
	        bodyStyle:'padding:5px 5px 0',
	        width: 350,
	        id: 'search-form',
	        collapsible: true,
	        defaults: {width: 230},
	        defaultType: 'textfield',
	
	        items: [{
	                fieldLabel: 'Last Name',
	                name: 'lastname'
	            },{
	                fieldLabel: 'Last 4 SSN',
	                name: 'ssn'
	            }
	        ],
	        buttons: [{
	            text: 'Search',
	            handler: function(){
	            	var vals = Ext.getCmp('search-form').getForm().getValues();
	            	store.reload({
	            		params: {
	            			lastname: vals.lastname,
	            			ssn: vals.ssn
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
			writer: writer
		});    	
        store.load()  	
    	var grid = new Ext.grid.GridPanel({
    		store: store,
    		height: 300,
    		width: 350,
    		frame: true,
    		plugins: [editor],
    		columns: [{
    			id: 'firstname',
    			header: 'First Name',
    			dataIndex: 'firstname'
    		},{
    			header: 'Last Name',
    			dataIndex: 'lastname',
    		 	editor: new Ext.form.TextField({})
    		},{
    			header: 'SSN Last 4',
    			dataIndex: 'ssn'
    		}]
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
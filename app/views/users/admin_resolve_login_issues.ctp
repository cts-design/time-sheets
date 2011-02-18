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
?>
<script type="text/javascript">
	Ext.onReady(function(){
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
                name: 'data[User][last]'
            },{
                fieldLabel: 'Last 4 SSN',
                name: 'data[User][ssn]'
            }
        ],
        buttons: [{
            text: 'Search',
            handler: function(){
            	Ext.getCmp('search-form').getForm().submit();
            }
        }]
    });

    searchForm.render('search');

	});
	
	

</script>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Resolve Login Issues', null, 'unique') ; ?>
</div>
<div id="search"></div>
<div class="admin">
	
    <div class="actions ui-widget-header">
		<ul></ul>
	</div>
	<div>
		<?php echo $this->Form->create('User', array('action' => 'resolve_login_issues')); ?>
		
	</div>
</div>
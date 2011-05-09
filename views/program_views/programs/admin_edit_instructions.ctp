<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $html->script('programs/admin_edit_instructions', array('inline' => FALSE));?>

<?php $html->scriptStart(array('inline' => false)); ?>
	Ext.onReady(function(){
		<?php if(isset($instructions)) : ?>
			var value = '<?php echo $instructions; ?>';
			Ext.getCmp('htmlEditor').setRawValue(value.toString()); 
		<?php endif ?>
	});
<?php $html->scriptEnd() ?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Edit Program Instructions', null, 'unique') ; ?>
</div>

<div id="editForm"></div>
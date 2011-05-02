<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->script('programs/admin_edit_instructions', array('inline' => FALSE));?>

<script type="text/javascript">
Ext.onReady(function(){
	<?php if(isset($instructions)) : ?>
		var value = '<?php echo $instructions; ?>';
		Ext.getCmp('htmlEditor').setRawValue(value.toString()); 
	<?php endif ?>
});
</script>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Edit Program Instructions', null, 'unique') ; ?>
</div>

<div id="editForm"></div>
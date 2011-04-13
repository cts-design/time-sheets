<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<script type="text/javascript">
	var progId = <?php echo $this->params['pass'][0]?>;
	var approvalPermission = false;
	<?php if(isset($approvalPermission)) : ?>
		var approvalPermission = true;
	<?php endif ?>
</script>
<?php echo $this->Html->script('program_responses/admin_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Program Responses', null, 'unique');?>
</div>
<div id="programResponseTabs"></div>
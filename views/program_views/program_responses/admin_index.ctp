<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php $this->Html->scriptStart(array('inline' => false)); ?>
	var progId = <?php echo $this->params['pass'][0]?>;
	var progName = '<?php echo $programName ?>';
	var approvalPermission = false;
	<?php if($approvalPermission) : ?>
		approvalPermission = <?php echo $approvalPermission ?>;
	<?php endif ?>
<?php $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('program_responses/admin_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Program Responses', true), null, 'unique');?>
</div>
<div id="programResponseTabs"></div>

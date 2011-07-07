<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<style>
	#SelfSignReassign {
		margin-bottom: 20px;
	}
</style>
<?php echo $this->Html->css('ext/superboxselect', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/SuperBoxSelect', array('inline' => false)); ?>
<?php echo $this->Html->script('self_sign_logs/index', array('inline' => false)); ?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Self Sign Queue', null, 'unique'); ?>
</div>
<div>
	<div id="SelfSignSearch"></div>
	<div id="SelfSignReassign"></div>
    <div id="SelfSignLogs"></div>
</div>

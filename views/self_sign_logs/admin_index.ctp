<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2011
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<?php echo $this->Html->css('/js/ext/resources/css/BoxSelect', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/BoxSelect', array('inline' => false)); ?>
<?php echo $this->Html->script('self_sign_logs/index', array('inline' => false)); ?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Self Sign Queue', true), null, 'unique'); ?>
</div>
<div>
	<div id="SelfSignSearch"></div>
    <div id="SelfSignLogs"></div>
</div>

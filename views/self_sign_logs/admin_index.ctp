<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>

<?php echo $this->Html->script('self_sign_logs/index', array('inline' => false)); ?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Self Sign Queue', null, 'unique'); ?>
</div>
<div>
    <div id="SelfSignLogs"></div>
</div>

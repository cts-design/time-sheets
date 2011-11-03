<?php echo $this->Html->css('/js/ext/resources/css/BoxSelect', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/BoxSelect', array('inline' => false)); ?>
<?php echo $this->Html->script('settings/admin_index', array('inline' => false)); ?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Settings', null, 'unique'); ?>
</div>

<div id="settingsTabs"></div>

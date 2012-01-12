<style type="text/css">
	body { overflow: hidden !important; }
</style>
<?php echo $this->Html->script('date', array('inline' => false)) ?>
<?php echo $this->Html->script('ext/ux/StatusBar', array('inline' => false)) ?>
<?php echo $this->Html->script('Atlas/chart/TotalUnduplicatedIndividuals', array('inline' => false)) ?>
<?php echo $this->Html->css('/js/ext/resources/css/BoxSelect', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/BoxSelect', array('inline' => false)) ?>
<?php echo $this->Html->script('reports/admin_index', array('inline' => false)) ?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Reports', null, 'unique'); ?>
</div>
<div id="reports-container"></div>
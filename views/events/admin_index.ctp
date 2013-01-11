<?php echo $this->Html->script('ext/ux/DateTimeField', array('inline' => FALSE));?>
<?php echo $this->Html->css('ext/statusbar', null, array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/StatusBar', array('inline' => false)) ?>
<?php echo $this->Html->script('events/admin_index', array('inline' => FALSE));?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Events', true), null, 'unique') ; ?>
</div>
<div id="events"></div>

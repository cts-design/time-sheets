<?php echo $this->Html->script('ext/ux/DateTimeField', array('inline' => FALSE));?>
<?php echo $this->Html->script('workshops/admin_index', array('inline' => FALSE));?>
<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Workshops', true), null, 'unique') ; ?>
</div>
<div id="workshops"></div>

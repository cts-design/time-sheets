<?php echo $this->Html->scriptStart(array('inline' => false)) ?>
  var roleId = <?php echo $roleId ?>;
<?php echo $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('programs/admin_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Programs', true), null, 'unique') ; ?>
</div>

<div id="programGrid"></div>

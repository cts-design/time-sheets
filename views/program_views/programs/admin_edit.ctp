<?php echo $this->Html->scriptStart(array('inline' => false)) ?>
  var ProgramId = <?php echo $id ?>
<?php echo $this->Html->scriptEnd() ?>
<?php echo $this->Html->script("programs/admin_edit_{$program_type}", array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Edit ' . Inflector::humanize($program_type), true), null, 'unique') ; ?>
</div>

<div id="programGrid"></div>

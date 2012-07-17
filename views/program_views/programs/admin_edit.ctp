<?php echo $this->Html->scriptStart(array('inline' => false)) ?>
  var ProgramId = <?php echo $id ?>;
  var ProgramName = '<?php echo $programName ?>';
  var RoleId = '<?php echo $roleId ?>';
<?php echo $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('ext/ux/StatusBar', array('inline' => false));?>
<?php echo $this->Html->script("programs/admin_edit_{$programType}", array('inline' => false));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Edit ' . Inflector::humanize($programType), true), null, 'unique') ; ?>
</div>

<div id="editPanel"></div>

<?php echo $this->Html->scriptStart(array('inline' => false)) ?>
  var ecourse = <?= json_encode($ecourse['Ecourse']) ?>;
<?php echo $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('ecourse_modules/admin_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Ecourse Modules', true), null, 'unique') ; ?>
</div>

<div id="ecourseModulesGrid"></div>
<div id="ecourseModuleForm"></div>

<?php echo $this->Html->scriptStart(array('inline' => false)) ?>
  var ecourse_module = <?= json_encode($ecourse_module['EcourseModule']) ?>;
<?php echo $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('ecourse_module_questions/admin_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Ecourse Module Questions', true), null, 'unique') ; ?>
</div>

<div id="ecourseModuleQuestionsForm"></div>

<?php echo $this->Html->scriptStart(array('inline' => false)) ?>
  var Ecourse = <?php echo json_encode($ecourse['Ecourse']) ?>;
<?php echo $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('ecourses/admin_update', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Edit ' . ucwords($ecourse['Ecourse']['name']) . ' Ecourse', true), null, 'unique') ; ?>
</div>

<div id="editEcourseForm"></div>

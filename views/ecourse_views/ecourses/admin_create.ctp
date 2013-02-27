<?php echo $this->Html->scriptStart(array('inline' => false)) ?>
  var ecourseType = '<?php echo $type ?>';
<?php echo $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('ecourses/admin_create', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('New ' . ucwords($type) . ' Ecourse', true), null, 'unique') ; ?>
</div>

<div id="newEcourseForm"></div>

<?php echo $this->Html->script('ecourses/admin_index', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Ecourses', true), null, 'unique') ; ?>
</div>

<div id="ecoursesGrid"></div>

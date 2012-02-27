<?php echo $this->Html->css('/js/ext/resources/css/BoxSelect', 'stylesheet', array('inline' => false)); ?>
<?php echo $this->Html->script('ext/ux/BoxSelect', array('inline' => false)); ?>
<?php echo $this->Html->script('secure_categories/admin_index.js', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Secure Categories', true), null, 'unique') ; ?>
</div>

<div id="secureCategories"></div>
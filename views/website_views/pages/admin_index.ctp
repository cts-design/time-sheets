<?php $this->Html->script('ext/ux/StatusBar.js', array('inline' => false)) ?>
<?php $this->Html->script('ext/ux/LiveSearchGridPanel.js', array('inline' => false)) ?>
<?php $this->Html->script('pages/admin_index.js', array('inline' => false)) ?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Pages', true), null, 'unique'); ?>
</div>

<div id="pages-index"></div>

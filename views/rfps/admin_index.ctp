<?php echo $this->Html->script('rfps/grid', array('inline' => FALSE)) ?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('Rfps', true), null, 'unique'); ?></div>
<div class="rfps">
	<div id="form-div"></div>
	<div id="panel-div"></div>
</div>

<?php echo $this->Html->script('ext/ux/FileUploadField', array('inline' => FALSE)) ?>
<?php echo $this->Html->script('rfps/grid', array('inline' => FALSE)) ?>

<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Rfps', null, 'unique'); ?></div>
<div class="rfps">
	<div id="form-div"></div>
	<div id="panel-div"></div>
</div>

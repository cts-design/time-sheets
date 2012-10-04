<?php echo $this->Html->scriptStart(array('inline' => FALSE)) ?>
	var AtlasInstallationName = '<?= Configure::read('Company.name') ?>';
<?php echo $this->Html->scriptEnd() ?>
<?php echo $this->Html->script('ext/ux/StatusBar', array('inline' => FALSE));?>
<?php echo $this->Html->script('programs/admin_create_esign', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('New Esign Program', true), null, 'unique') ; ?>
</div>

<div id="registrationForm"></div>

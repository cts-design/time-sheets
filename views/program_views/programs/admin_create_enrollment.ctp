<?php echo $this->Html->script('ext/ux/StatusBar', array('inline' => FALSE));?>
<?php echo $this->Html->script('programs/admin_create_enrollment', array('inline' => FALSE));?>

<div id="crumbWrapper">
    <span><?php __('You are here') ?> > </span>
    <?php echo $crumb->getHtml(__('New Enrollment Program', true), null, 'unique') ; ?>
</div>

<div id="registrationForm"></div>

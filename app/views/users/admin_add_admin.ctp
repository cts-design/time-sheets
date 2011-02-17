<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php
if (!isset($userEmail)) {
    $userEmail = Configure::read('PrePop.email');
}
?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Add Admin', null, 'unique') ; ?>
</div>
<div class="admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
    <div class="admins-add form">
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
	    <legend><?php __('Add Admin'); ?></legend>
	    <?php echo $this->Form->input('firstname', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
		)); ?>
	    <?php echo  '<br class="clear"/>' ?>
	    <?php echo $this->Form->input('lastname', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
		)); ?>
	    <?php echo  '<br class="clear"/>' ?>
	    <?php echo $this->Form->input('pass', array(
		    'label' => __('Password', TRUE),
		    'type' => 'password',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
		)); ?>
	    <?php echo  '<br class="clear"/>' ?>
	    <?php echo $this->Form->input('email', array(
		    'value' => $userEmail,
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
		)); ?>
	    <?php echo  '<br class="clear"/>' ?>
	    <?php echo $this->Form->input('phone', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
		)); ?>
	    <?php echo  '<br class="clear"/>' ?>
	    <?php echo $this->Form->input('location_id', array(
		    'type' => 'select',
		    'empty' => 'Select a location',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
		)); ?>
	    <?php echo  '<br class="clear"/>' ?>
	    <?php echo $this->Form->input('role_id', array(
		    'type' => 'select',
		    'empty' => 'Select a role',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
		)); ?>
	    <?php echo $this->Form->hidden('admin_registration', array('value' => 'admin')); ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
    </div>
</div>
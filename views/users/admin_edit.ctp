<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<div id="crumbWrapper">
    <span>You are here > </span>
    <?php echo $crumb->getHtml('Edit Customer', null, 'unique') ; ?>
</div>
<div class="admin">
    <div class="actions ui-widget-header">
	<ul></ul>
    </div>
    <div class="users form">
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
	    <legend><?php __('Edit Customer'); ?></legend>
	    <?php
	    echo $this->Form->input('id');
	    echo $this->Form->input('role_id', array('type' => 'hidden'));
	    echo $this->Form->input('firstname',array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('lastname', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('middle_initial', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('ssn', array(
		    'label' => __('SSN', true),
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('ssn_confirm', array(
		    'value' => $this->data['User']['ssn'],
		    'label' => __('SSN Confirm', true),
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('address_1', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('address_2', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('city', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('state', array(
		    'type' => 'select',
		    'empty' => 'Select State',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('zip', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('phone', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('alt_phone', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('gender', array(
		    'empty' => 'Select Gender',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('dob',array(
		    'type' => 'text',
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear" />' ;
	    echo $this->Form->input('email', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    ?>
	</fieldset>
	<?php echo $this->Form->end(__('Submit', true)); ?>
    </div>
</div>
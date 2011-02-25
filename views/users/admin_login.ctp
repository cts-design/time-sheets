<?php

/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<div class="admin">
    <br />
    <div class="form">
	<?php
	    echo $form->create('User');
	    echo $form->input('username', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear"/>';
	    echo $form->input('password', array(
		    'before' => '<p class="left">',
		    'between' => '</p><p class="left">',
		    'after' => '</p>'
	    ));
	    echo '<br class="clear"/>';
	    echo '<span id="forgotPass">' . $this->Html->link( 'Forgot Password?', array('action' => 'password_reset')) . '</span>';
	    echo $form->hidden('admin_login', array('value' => 'admin'));
	    echo $form->end('Login');
	  ?>
    </div>
</div>
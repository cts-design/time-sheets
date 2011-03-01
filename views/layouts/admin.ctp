<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<?php echo $this->Html->charset(); ?>
	<title>
	    <?php __('ATLAS V3'); ?>
	    <?php echo $title_for_layout; ?>
	</title>
	<script type="text/javascript">
	    domain = "<?php echo Configure::read('domain')?>"
	   </script>
	<?php
	echo $this->Html->meta('favicon.ico', '/favicon.ico', array('type' => 'icon'));

	echo $this->Html->css('reset');

	echo $this->Html->css('ui-darkness/jquery-ui-1.8.7.custom');

    echo $this->Html->css('ext/ext-all');
	
	echo $this->Html->css('ext/RowEditor');
	
	echo $this->Html->css('ext/atlas');

	echo $this->Html->css('admin');
	
	echo $this->Html->script('ext/adapter/ext/ext-base-debug');
	
	echo $this->Html->script('ext-all-debug');

	echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js');
	
	echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.7/jquery-ui.min.js');
	
	echo $this->Html->script('jquery.backstretch.min');

	echo $this->Html->script('layouts/admin');

	echo $scripts_for_layout;
	?>
    </head>
    <body>
	<div id="container" style="display: none">
	    <div id="header">
		<div id="logo" class="left">
		    <?php echo $this->Html->link($this->Html->image('/img/admin/admin_header_logo.jpg'),
				array('controller' => 'users',
					'action' => 'dashboard',
					'admin' => true), array('escape' => false));
			    ?>
		</div>
		<div id="logoLogout" class="right">
		    <?php echo $this->Html->image('atlas_logo_100.jpg') ?>
		    <br />
		   <p>
		   <?php
			if ($session->read('Auth.User'))
			    echo '<strong>Logged in as: ' . $session->read('Auth.User.firstname') . ' ' .
				$session->read('Auth.User.lastname') . '</strong> | ' .
				$this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout', 'admin' => true));
			?>
		   </p>
		</div>
	    </div>
	    <div id="content">
		<h1 class="left"><?php echo $title_for_layout; ?></h1>
		<?php echo $this->Session->flash(); ?>
		<?php echo $session->flash('auth'); ?>
		<br class="clear"/>
		<?php echo $content_for_layout; ?>
	    </div>
	    <div  id="footer">
		<p>
		    <?php echo Configure::read('Company.name')?> is an equal opportunity employer/program.
		    Auxiliary aids and services are available upon request to individuals with disabilities.
		    All voice telephone numbers listed on this website may be reached by persons using TTY/TDD
		    equipment via the Florida Relay Service at 711.
		    <br />
		    ATLAS is a trademark of Complete Technology Solutions
		    Copyright &copy; <?php echo date('Y')?> - Complete Technology Solutions.
		    All Rights Reserved.
		</p>
	    </div>
	</div>
	<?php echo $this->Js->writeBuffer(); ?>
	<script type="text/javascript">
		$(document).ready(function(){
		    $("#container").show();
		    $('.message').fadeOut(10000);
		})
	   </script>
    </body>
</html>
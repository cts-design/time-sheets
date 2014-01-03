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

	echo $this->Html->css('ui-redmond/jquery-ui-1.8.10.custom');

	echo $this->Html->css('/js/ext/resources/css/ext-all');

	echo $this->Html->css('admin');

	echo $this->Html->css('font-awesome.min');

	?>

	<!--[if IE 7]>
		<?php echo $this->Html->css('font-awesome-ie7.min') ?>
	<![endif]-->

	<?php

	echo $this->Html->script('/js/ext/bootstrap');

	echo $this->Html->script('atlas');

	echo $this->Html->script('jquery1102.min');
	
	echo $this->Html->script('https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js');

	echo $this->Html->script('layouts/admin'); 
	?>

  <?php
	echo $scripts_for_layout;
	?>
    </head>
    <body class="admin-layout">
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
			if ($session->read('Auth.User')) {
                printf(__('<strong>Logged in as: %s %s</strong> | ', true), $session->read('Auth.User.firstname'), $session->read('Auth.User.lastname'));
				echo $this->Html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout', 'admin' => true));
			}
			?>
		   </p>
		</div>
	    </div>
	    <div id="content">
	    <div>
		<h1 class="left"><?php echo $title_for_layout; ?></h1>
		<?php echo $this->Session->flash(); ?>
		<?php echo $session->flash('auth'); ?>
		<br class="clear"/>
		<?php echo $content_for_layout; ?>
	    </div>
	    </div>
	    <div  id="footer">
	    <div>
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

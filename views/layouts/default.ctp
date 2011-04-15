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
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.2.0/build/cssreset/reset-min.css" />
	<?php
	echo $this->Html->meta('icon');

	echo $this->Html->css('ui-darkness/jquery-ui-1.8.5.custom');

	echo $this->Html->css('style');

	echo $this->Html->script('jquery');

	echo $this->Html->script('jquery-ui-1.8.5.custom.min');

	echo $scripts_for_layout;
	?>
	<?php echo $this->Html->scriptBlock(
		"$(document).ready(function(){
		$('.message').fadeOut(10000);
		if($('.actions ul').text() == '') {
		    $('div.actions').hide();
		}
	    });"
	    )?>
    </head>
    <body>
	<div id="container">
	    <div id="header">
		<div id="logo" class="left">
		    <?php echo $this->Html->link($this->Html->image('/img/admin/admin_header_logo.jpg'),
				array('controller' => 'pages',
					'action' => 'display',
					'admin' => false,
					'home'), array('escape' => false));
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
            <div id="navigation"><?php echo $this->Nav->links('Top') ?></div>
	    <div id="content">
		<h1 class="left"><?php echo $title_for_layout; ?></h1>
		<?php echo $this->Session->flash(); ?>
		<?php echo $session->flash('auth'); ?>
		<br class="clear"/>
		<?php echo $content_for_layout; ?>
	    </div>
            <div id="bottom_navigation"><?php echo $this->Nav->links('Bottom') ?></div>
	    <div  id="footer">
		<p>
		    <?php echo Configure::read('Company.name')?> is an equal opportunity employer/program.
		    Auxiliary aids and services are available upon request to individuals with disabilities.
		    All voice telephone numbers listed on this website may be reached by persons using TTY/TDD
		    equipment via the Florida Relay Service at 711.
		    Copyright &copy; <?php echo date('Y')?> - <?php echo Configure::read('Company.name')?>.
		    All Rights Reserved. Developed & Hosted by Complete Technology Solutions
		</p>
	    </div>
	</div>
	<?php echo $this->Js->writeBuffer(); ?>

    </body>
</html>
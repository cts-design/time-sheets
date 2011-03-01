<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<?php echo $this->Html->charset(); ?>
	<title>
	    <?php echo Configure::read('Company.name').' - ' ?>
	    <?php echo $title_for_layout; ?>
	</title>
	<?php
	    echo $this->Html->meta('icon');
		
		echo $this->Html->css('reset');
		
	    echo $this->Html->css('custom-theme/jquery-ui-1.8.5.custom');

	    echo $this->Html->css('kiosk');

	    echo $this->Html->script('jquery');

	    echo $this->Html->script('jquery.validate');

	    echo $this->Html->script('jquery.dPassword');

	    echo $this->Html->script('jquery-ui-1.8.5.custom.min');

	    echo $this->Html->script('jquery.idleTimeout');

	    echo $scripts_for_layout;
	   ?>
	<?php
	    $exclude = array('self_sign_login', 'self_scan_document');
	    if (!in_array($this->params['action'], $exclude)) {
		echo $this->Html->scriptBlock(
			"$(document).ready(function(){
		      $(document).idleTimeout({
			  inactivity: 30000,
			  noconfirm: 10000,
			  sessionAlive: false,
			  logout_url: '/',
			  redirect_url: '/users/auto_logout'
			})
		});");
	    }
	    echo $this->Html->scriptBlock(
		    "$(document).ready(function(){
		    $('input:submit, a').button();
		    $('form:first *:input[type!=hidden]:first').focus();
		    $('.message').fadeOut(10000);
		});")
	?>
    </head>
    <body>
	<div id="container">
	    <div id="header">		
		<?php echo $this->Html->image('/img/kiosk/kiosk_header.jpg');?>
	    </div>
	    <div id="content">
		<?php echo $content_for_layout; ?>
	    </div>
	    <div id="footer">
		<span id="copyright" class="left"><?php echo Configure::read('Company.name')?> is an equal opportunity employer/program.
		      Auxiliary aids and services are available upon request to individuals with disabilities.
		      <br />
		      All voice telephone numbers listed on this website may be reached by persons using TTY/TDD
		      equipment via the Florida Relay Service at 711.
		      <br />
		      ATLAS is a trademark of Complete Technology Solutions
		      Copyright &copy; <?php echo date('Y')?> - Complete Technology Solutions.
		      All Rights Reserved.</span>
		      <?php echo $this->Html->image('employ_florida_logo.jpg', array('class' => 'right'))?>
	    </div>
	</div>
	<?php echo $this->Js->writeBuffer(); ?>
	<br />
    </body>
</html>
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
		$settings = Cache::read('settings');
		$timeOut = json_decode($settings['SelfSign']['KioskTimeOut'], true);
		$exclude = array('kiosk_self_scan_document', 'question', 'kiosk_id_card_login');
		/*
		if(Configure::read('Kiosk.login_type') == 'id_card') {
			$exclude = array('kiosk_self_scan_document', 'question', 'kiosk_id_card_login');
		}
		else {
			$exclude = array('kiosk_self_scan_document', 'question');
		}
		*/


		// this is for when the user is asked to find themself if they use an id_card_login
		// it will allow for the timeout to occur if they leave this page for any reason
		if(isset($this->params['url']['btn']))
		{
			if($this->params['url']['btn'] == true)
				$exclude[] = 'kiosk_self_sign_login';
		}
		else
		{
			$exclude[] = 'kiosk_self_sign_login';
		}

		$exclude[] = 'kiosk_mini_registration';

	    if (!in_array($this->params['action'], $exclude)) {
		echo $this->Html->scriptBlock(
			"$(document).ready(function(){
		      $(document).idleTimeout({
			  inactivity: ".$timeOut[0]['value'].",
			  noconfirm: ".$timeOut[1]['value'].",
			  sessionAlive: false,
			  logout_url: '/',
			  redirect_url: '/users/logout/kiosk'
			})
		});");
	    }
	    echo $this->Html->scriptBlock(
		    '$(document).ready(function(){
		    $("input:submit, a").button();
            $("a.translate-button").button("destroy");
            $(".self-sign-survey-button").button();
            $(".survey-button a").button("destroy");
		    $("form:first *:input[type!=hidden]:first").focus();
		    $(".message").fadeOut(10000);
			$("#assistanceDialog").dialog({
				modal:true,
				autoOpen: false,
				title: "Assistance",
				draggable: false,
				resizable: false,
				buttons: {Close: function() {$(this).dialog("close");}}
			});
			$("#assistance").click(function(){
				$("#assistanceDialog").dialog("open");
			});
		});')
	?>

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-51687253-1', 'careersourcetampabay.com');
	  ga('send', 'pageview');

	</script>
    </head>
    <body>
	<div id="container">
	    <div id="header">		
		<?php $actions = array('kiosk_self_sign_login', 'kiosk_id_card_login') ?>
		<?php echo $this->Html->image('/img/kiosk/kiosk_header.jpg');?>
	    </div>
	    <div id="content">
		<?php echo $content_for_layout; ?>
        </div>
        <?php if (in_array($this->params['action'], $actions)): ?>
		<div style="margin: 10px 0 0" id="speakspanish">
			<p style="font-family: Arial, 'sans-serif'; font-size: 16px; text-align: center;">
	    	<?php if (!$session->read('Config.language')): ?>
	    		<a class="translate-button" href="/kiosk/kiosks/set_language/es">Español</a>
	    	<?php else: ?>
	    		<a class="translate-button" href="/kiosk/kiosks/set_language/en">English</a>
            <?php endif ?>
			<?php if(!empty($kiosk['Kiosk']['assistance_message'])) : ?>
				<?php echo $this->Html->link('I need assistance', '#', array('id' => 'assistance', 'class' => 'translate-button')) ?>	
				<div id="assistanceDialog">
				<p><?php echo $kiosk['Kiosk']['assistance_message']?></p>
				</div>
			<?php endif ?>
			</p>
        </div>
        <?php endif; ?>
		<div id="footer">
	        <p id="copyright" class="left">
		        <?php printf(__('%s is an equal opportunity employer/program. Auxiliary aids and services are available upon request to individuals with disabilities.', true), Configure::read('Company.name')) ?>
				<br />
		        <?php __('All voice telephone numbers listed on this website may be reached by persons using TTY/TDD equipment via the Florida Relay Service at 711.') ?>
				<br />
		        <?php printf(__('ATLAS is a trademark of Complete Technology Solutions Copyright &copy; %s - Complete Technology Solutions. All Rights Reserved.</span>', true), date('Y')) ?>
			</p>
			<?php echo $this->Html->image('employ_florida_logo.jpg', array('class' => 'right'))?>
	    </div>
	</div>
	<?php echo $this->Js->writeBuffer(); ?>
	<br />
    </body>
</html>

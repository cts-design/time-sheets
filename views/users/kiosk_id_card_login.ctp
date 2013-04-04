<?php
/**
 * @author Daniel Nolan
 * @copyright Complete Technology Solutions 2010
 * @link http://ctsfla.com
 * @package ATLAS V3
 */
?>
<?php echo $this->Html->scriptStart() ?>
	$(document).ready(function(){
		$('#UserSelfSignLoginForm').submit(function(){
			$('.self-sign-kiosk-button').button("disable");
			return true; 
		});
		$("#UserIdCard").blur(function(){this.focus()});
	});
<?php echo $this->Html->scriptEnd() ?>
<div class="id-login-wrapper">
	<h1>
		<?php printf(__('Welcome to %s. Please please swipe your %s ID.', true),
			Configure::read('Company.name'), Configure::read('Company.state')) ?>
	</h1>
    <div id="errorMessage"></div>
    <?php echo $this->Session->flash(); ?>
		<div style="margin-left: 300px; width: 600px">
				<div style="float: left">
					<?php echo $this->Html->image('kiosk/id_sample.jpg') ?>
					<p style="margin: 25px 0 0 12px">
						<a href="/kiosk/users/self_sign_login">Login with SSN</a>
					</p>
				</div>
				<div style="float: right">
					<?php echo $this->Html->image('kiosk/swipe.jpg') ?>
				</div>
		</div>
    <?php
	    echo $this->Session->flash('auth');
	    echo $form->create('User', array('action' => 'id_card_login', 'kiosk' => true));
	    echo $form->input('User.id_card', array(
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p><br class="clear"/>',
			'label' => false,
			'style' => 'position: absolute; left: -9999px;'
			));
	    echo $form->hidden('User.login_type', array('value' => 'kiosk'));
	    echo $form->end(array('label' => __('Login', true), 'style' => 'visibility: hidden'));
    ?>
    <?php if ($kioskHasSurvey): ?>
    	<div class="survey-button">
		<a href="/kiosk/survey/<?php echo $kiosk['KioskSurvey'][0]['id'] ?>">Take Survey</a>
    	</div>
    <?php endif ?>
</div>

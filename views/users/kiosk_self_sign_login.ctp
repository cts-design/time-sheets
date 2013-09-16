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
	});
<?php echo $this->Html->scriptEnd() ?>
<div class="self-sign-wrapper">
    <h1><?php printf(__('Welcome to %s. Please sign in here.', true), Configure::read('Company.name')) ?></h1>
    <div id="errorMessage"></div>
    <?php echo $this->Session->flash(); ?>
    <?php
	    echo $this->Session->flash('auth');
	    echo $form->create('User', array('action' => 'self_sign_login'));
	    echo $form->input('User.username', array(
			'label' => __('Last Name', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p><br class="clear"/>'));
	    echo $form->input('User.password', array(
			'label' => __('9 Digit SSN', true),
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p><br class="clear"/>',
			'maxlength' => 9));
	    echo $form->hidden('User.login_type', array('value' => 'kiosk'));
	    echo $form->end(array('label' => __('Login', true), 'class' => 'self-sign-kiosk-button'));
    ?>
    <?php
    if(isset($kiosk['Kiosk']) && isset($kiosk['Kiosk']['default_sign_in']))
    {
    	if($kiosk['Kiosk']['default_sign_in'] == 'id_card'){
    		?>
    		<p style="margin: 10px 0 10px 300px">
				<a class="translate-button" href="/kiosk/users/id_card_login">I'd prefer to login with License or ID</a>
			</p>
    		<?php
    	}
    }
    ?>
    <?php if ($kioskHasSurvey): ?>
    	<div class="survey-button">
		<a href="/kiosk/survey/<?php echo $kiosk['KioskSurvey'][0]['id'] ?>">Take Survey</a>
    	</div>
    <?php endif ?>
</div>

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
	<h1>
		<?php printf(__('Welcome to %s. Please please swipe your %s Id Card or Drivers License.', true),
			Configure::read('Company.name'), Configure::read('Company.state')) ?>
	</h1>
    <div id="errorMessage"></div>
    <?php echo $this->Session->flash(); ?>
    <?php
	    echo $this->Session->flash('auth');
	    echo $form->create('User', array('action' => 'id_card_login', 'kiosk' => true));
	    echo $form->input('User.id_card', array(
			'before' => '<p class="left">',
			'between' => '</p><p class="left">',
			'after' => '</p><br class="clear"/>'
			));
	    echo $form->hidden('User.login_type', array('value' => 'kiosk'));
	    echo $form->end(array('label' => __('Login', true), 'class' => 'self-sign-kiosk-button'));
    ?>
    <?php if ($kioskHasSurvey): ?>
    	<div class="survey-button">
		<a href="/kiosk/survey/<?php echo $kiosk['KioskSurvey'][0]['id'] ?>">Take Survey</a>
    	</div>
    <?php endif ?>
</div>

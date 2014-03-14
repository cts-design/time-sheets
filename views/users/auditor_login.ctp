<div id="AuditorLoginForm">
  <br />  
  <fieldset>
    <legend>Login</legend>
    <?php
        echo $form->create('User', array('url' => '/users/login/auditor'));
        echo $form->input('username', array(
          'label' => 'Auditor Username', 
          'between' => '<br />',
        'after' => '<br />'));
        echo '<br class="clear"/>';
        echo $form->input('password', array(
          'label' => __('Auditor Password', true),
          'between' => '<br />',
          'after' => '<br />'
        ));
      echo $form->hidden('User.login_type', array('value' => 'auditor', ));
        echo '<br class="clear"/>';
        
      ?>
    </fieldset>
    <br />
   <?php echo $form->end(__('Login', true)); ?>
</div>

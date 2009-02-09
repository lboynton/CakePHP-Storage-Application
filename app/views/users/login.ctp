<h2>OpenID Enabled Login</h2>
<p>Enter your OpenID below to login using OpenID.</p>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<fieldset class="fieldset1">
<?php echo $form->create('User', array('type' => 'post', 'action' => 'login')); ?>
<?php echo $form->input('username', array('label' => 'OpenID URL', 'id' => 'openIdLogin', 'maxLength' => 255)); ?>
<?php echo $form->input('access_code'); ?>
<?php echo $form->input('password', array('type' => 'hidden')); ?>
<?php echo $form->end('Login'); ?>
</fieldset>
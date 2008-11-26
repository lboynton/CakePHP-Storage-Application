<h2>OpenID Enabled Login</h2>
<p>Enter your OpenID below to login using OpenID.</p>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<fieldset class="fieldset1">
<?php echo $form->create('User', array('type' => 'post', 'action' => 'openid')); ?>
<?php echo $form->input('open_id', array('label' => 'OpenID identity URL', 'id' => 'openIdLogin')); ?>
<?php echo $form->end('Login'); ?>
</fieldset>
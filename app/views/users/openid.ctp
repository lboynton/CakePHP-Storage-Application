<?php if($session->check('Message.flash')) $session->flash(); ?>
<fieldset class="fieldset1">
<?php echo $form->create('User', array('type' => 'post', 'action' => 'openid')); ?>
<?php echo $form->input('OpenidUrl.openid', array('label' => 'OpenID identity URL', 'id' => 'openIdLogin')); ?>
<?php echo $form->end('Login'); ?>
</fieldset>
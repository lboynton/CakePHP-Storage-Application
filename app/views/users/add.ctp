<h2>Register</h2>
<p><strong>Note:</strong> Required fields are indicated by an asterisk.</p>
<?php $session->flash(); ?>
<?php echo $form->create(); ?>
<?php echo $form->input('realName'); ?>
<?php echo $form->input('username'); ?>
<?php echo $form->input('password'); ?>
<?php echo $form->input('confirmPassword', array('type' => 'password')); ?>
<?php echo $form->input('email'); ?>
<?php echo $form->end('Register'); ?>
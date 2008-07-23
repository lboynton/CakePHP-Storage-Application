<h2>Login</h2>
<p>You will need an account before you can login, this can be created by visiting the <?php echo $html->link('register', '/users/register'); ?> page.</p>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<?php if($session->check('Message.auth')) $session->flash('auth'); ?>
<?php echo $form->create('User', array('action' => 'login'));?>
	<?php echo $form->input('username', array('value' => $defaultUsername)); ?>
	<?php echo $form->input('password'); ?>
    <?php echo $form->submit('Login', array('id' => 'UserLogin')); ?>
<?php echo $form->end(); ?>
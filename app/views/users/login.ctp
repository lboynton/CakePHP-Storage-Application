<h2>Login</h2>
<?php $session->flash(); ?>
<?php echo $form->create('User', array('action' => 'login'));?>
	<?php echo $form->input('username', array('value', $session->read('username'))); ?>
	<?php echo $form->input('password'); ?>
	<?php echo $form->submit('Login'); ?>
<?php echo $form->end(); ?>
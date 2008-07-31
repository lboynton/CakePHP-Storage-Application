<h2>Administrator login</h2>
<p>Please enter your username and password below to login.</p>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<?php if($session->check('Message.auth')) $session->flash('auth'); ?>
<?php echo $form->create('Admin', array('action' => 'login'));?>
	<?php echo $form->input('username'); ?>
	<?php echo $form->input('password'); ?>
    <?php echo $form->submit('Login'); ?>
<?php echo $form->end(); ?>
<div class="message"><strong>Note:</strong> This is the administrator login, if you are not an administrator you want the <?php echo $html->link('normal login', '/users/login'); ?>.</div>
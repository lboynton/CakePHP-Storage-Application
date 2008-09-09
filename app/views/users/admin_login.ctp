<h2>Login</h2>
<p>Please enter your username and password below to login.</p>
<fieldset class="fieldset1">
	<?php echo $form->create('User', array('action' => 'admin_login'));?>
        <?php if($session->check('Message.flash')) $session->flash(); ?>
        <?php if($session->check('Message.auth')) $session->flash('auth'); ?>
        <?php echo $form->input('username'); ?>
        <?php echo $form->input('password'); ?>
        <?php echo $form->submit('Login'); ?>
    <?php echo $form->end(); ?>
</fieldset>
<p>Don't have an account yet? Simply visit the <?php echo $html->link('register', '/users/register'); ?> page to create an account.</p>
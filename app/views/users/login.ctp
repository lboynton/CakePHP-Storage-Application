<h2>Login</h2>
<p>Please enter your username and password below to login.</p>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<?php if($session->check('Message.auth')) $session->flash('auth'); ?>
<fieldset class="fieldset1">
	<?php echo $form->create('User', array('action' => 'login'));?>
        <?php echo $form->input('username', array('value' => $defaultUsername)); ?>
        <?php echo $form->input('password'); ?>
		<div class="submit">
			<?php echo $form->submit('Login', array('id' => 'UserLogin', 'div' => false)); ?>
			<?php echo $html->link('Forgot password?', '/users/forgot_password'); ?>
		</div>
    <?php echo $form->end(); ?>
</fieldset>
<p>Don't have an account yet? Visit the <?php echo $html->link('register', '/users/register'); ?> page to create an account.</p>
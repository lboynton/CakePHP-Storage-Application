<h2>Forgot your password?</h2>
<p>Enter your email address below and we will send you an email with a link to where you can reset your password.</p>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<?php if($showForm): ?>
	<fieldset class="fieldset1">
		<?php echo $form->create(null, array('action' => 'forgot_password')); ?>
			<?php echo $form->input('email'); ?>
		<?php echo $form->end('Submit'); ?>
	</fieldset>
<?php endif; ?>
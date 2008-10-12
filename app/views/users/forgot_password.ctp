<h2>Forgot your password?</h2>
<p>Enter your email address below and we will send you an email with a link to a location where you can reset your password.</p>
<fieldset class="fieldset1">
	<?php if($session->check('Message.flash')) $session->flash(); ?>
	<?php if($showForm): ?>
		<?php echo $form->create(null, array('action' => 'forgot_password')); ?>
			<?php echo $form->input('email'); ?>
		<?php echo $form->end('Submit'); ?>
	<?php endif; ?>
</fieldset>
<h2>Reset Password</h2>
<p>Please enter a new password below.</p>

<fieldset class="fieldset1">
<?php echo $form->create(array('action' => 'reset_password/' . $ticket)); ?>
	<?php $session->flash(); ?>
    <?php echo $form->input('new_password', array('type' => 'password')); ?>
    <p id="UserNewPasswordHelp" class="help">Should be a minimum of 6 characters</p> 
    <?php echo $form->input('confirm_password', array('type' => 'password')); ?>
    <p id="UserConfirmPasswordHelp" class="help">To verify you have entered your password correctly</p> 
<?php echo $form->end('Change'); ?>
</fieldset>
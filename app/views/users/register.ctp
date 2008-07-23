<h2>Register</h2>
<?php $session->flash(); ?>
<?php $session->flash('auth'); ?>
<?php echo $form->create(array('action' => 'register')); ?>
	<?php echo $form->input('real_name'); ?>
    <p id="UserRealNameHelp" class="help">This is optional</p> 
    
    <?php echo $form->input('username'); ?>
    <p id="UserUsernameHelp" class="help">You will need this to login</p> 
    
    <?php echo $form->input('new_password', array('type' => 'password')); ?>
    <p id="UserNewPasswordHelp" class="help">Should be a minimum of 6 characters</p> 
    
    <?php echo $form->input('confirm_password', array('type' => 'password')); ?>
    <p id="UserConfirmPasswordHelp" class="help">To verify you have entered your password correctly</p> 
    
    <?php echo $form->input('email'); ?>
    <p id="UserEmailHelp" class="help">Finally, your email. This should be valid.</p> 
<?php echo $form->end('Register'); ?>

<p><strong>Note:</strong> If a field has an asterisk, it is required that you enter something.</p>
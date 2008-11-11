<h2>Your account</h2>

<?php if ($session->read("Auth.User.real_name") != ""): ?>
<p>Hi <?php echo $session->read("Auth.User.real_name"); ?>. Use this page to view and make changes to your account.</p>
<?php else: ?>
<p>Hi <?php echo $session->read("Auth.User.username"); ?>. Use this page to view and make changes to your account.</p>
<?php endif; ?>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<h3>Account statistics</h3>
<dl>
    <dt>Files backed up</dt>
    <dd><?php echo $backupCount; ?></dd>
    <dt>Last backup</dt>
    <dd>
		<?php 
		if(isset($lastBackup[0]['Backup']['created'])) 
			echo $time->niceShort($lastBackup[0]['Backup']['created']); 
		else echo "No backups yet";
		?>
    </dd>
    <dt>Usage</dt>
    <dd><?php echo $number->toReadableSize($backupSum[0][0]['size']); ?></dd>
	<dt>Quota</dt>
    <dd><?php echo $number->toReadableSize($session->read("Auth.User.quota")); ?></dd>
    <dt>Usage percentage</dt>
    <dd><?php echo $percentage->chart(@($backupSum[0][0]['size'] / $session->read("Auth.User.quota") * 100), true); ?></dd>
</dl>

<h3>Account details</h3>
<fieldset class="fieldset2">
<?php echo $form->create(null, array('action' => 'index')); ?>
	<?php echo $form->input('real_name', array('value' => $session->read("Auth.User.real_name"))); ?>
    <?php echo $form->input('email', array('value' => $session->read("Auth.User.email"))); ?>
    <?php echo $form->input('action', array('value' => 'updateDetails', 'type' => 'hidden')); ?>
<?php echo $form->end('Change'); ?>
</fieldset>

<h3>Change password</h3>
<fieldset class="fieldset2">
<?php echo $form->create(null, array('action' => 'index')); ?>
	<?php echo $form->input('old_password', array('type' => 'password')); ?>
    <?php echo $form->input('new_password', array('type' => 'password')); ?>
    <?php echo $form->input('confirm_password', array('type' => 'password')); ?>
    <?php echo $form->input('action', array('value' => 'changePassword', 'type' => 'hidden')); ?>
<?php echo $form->end('Change'); ?>
</fieldset>

<h3>Empty storage</h3>
<p>Check the box below and click the 'Delete' button to delete all the files and folders from your file storage.</p>
<?php echo $form->create('Backup', array('action' => 'deleteAll')); ?>
    <?php echo $form->input('deleteAll', array('options' => array('yes', 'no'), 'type' => 'checkbox', 'label' => 'Delete all files and folders')); ?>
    <?php echo $form->submit('Delete', array('id' => 'DeleteButton')); ?>
<?php echo $form->end(); ?>

<?php echo $javascript->event('window', 'load', '$(\'DeleteButton\').disable()'); ?>
<?php echo $javascript->event('BackupDeleteAll', 'click', '$(\'DeleteButton\').toggleDisable()'); ?>
<h2>Your account summary</h2>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<?php if ($session->read("Auth.User.real_name") != ""): ?>
<p>Here is your account status, <?php echo $session->read("Auth.User.real_name"); ?>.</p>
<?php else: ?>
<p>Here is your account status, <?php echo $session->read("Auth.User.username"); ?>.</p>
<?php endif; ?>
<dl>
<!--	<dt>Quota</dt>
    <dd></dd>
    <dt>Usage percentage</dt>
    <dd></dd> -->
    <dt>Files backed up</dt>
    <dd><?php echo $backupCount; ?></dd>
    <dt>Usage</dt>
    <dd><?php echo $number->toReadableSize($backupSum[0][0]['size']); ?></dd>
    <dt>Last backup</dt>
    <dd>
		<?php 
		if(isset($lastBackup[0]['Backup']['created'])) 
		echo $time->niceShort($lastBackup[0]['Backup']['created']); 
		else echo "No backups yet";
		?>
    </dd>
</dl>
<?php echo $form->create('Backup', array('action' => 'deleteAll', 'id' => 'deleteAllFiles')); ?>
	<?php echo $form->checkbox('deleteAll'); ?>Delete all files and folders<?php echo $form->submit('Delete', array('id' => 'DeleteButton')); ?>
<?php echo $form->end(); ?>
<?php //echo date_default_timezone_get(); ?>

<?php echo $javascript->event('window', 'load', '$(\'DeleteButton\').toggleDisable()'); ?>
<?php echo $javascript->event('BackupDeleteAll', 'click', '$(\'DeleteButton\').toggleDisable()'); ?>
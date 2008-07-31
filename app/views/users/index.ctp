<h2>Your account summary</h2>
<?php if ($session->read("Auth.User.real_name") != ""): ?>
<p>Here is your account status, <?php echo $session->read("Auth.User.real_name"); ?>.</p>
<?php else: ?>
<p>Here is your account status, <?php echo $session->read("Auth.User.username"); ?>.</p>
<?php endif; ?>
<dl>
	<dt>Quota</dt>
    <dd></dd>
    <dt>Usage</dt>
    <dd></dd>
    <dt>Usage percentage</dt>
    <dd></dd>
    <dt>Files backed up</dt>
    <dd><?php echo $backupCount; ?></dd>
    <dt>Last backup</dt>
    <dd><?php echo $time->niceShort($lastBackup[0]['Backup']['created']); ?></dd>
</dl>
<?php //echo date_default_timezone_get(); ?>
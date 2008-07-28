<backups>
<?php foreach($backups as $backup): ?>
<backup>
	<file><?php echo $backup['Backup']['path']; ?>\<?php echo $backup['Backup']['name'] ?></file>
    <hash><?php echo $backup['Backup']['hash']; ?></hash>
</backup>
<?php endforeach; ?>
</backups>
<h2>Restore</h2>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<?php if($backups): ?>
<table>
<?php echo $html->tableHeaders(array('Name','Size','Date','Actions'));?>
<?php foreach($backups as $backup): ?>
	<?php echo $html->tableCells(array(
		$backup['Backup']['name'],
		$number->toReadableSize($backup['Backup']['size']),
		$time->niceShort($backup['Backup']['created']),
		$html->link('View', '/backups/view/' . $backup['Backup']['id']) . " " .
		$html->link('Download', '/backups/download/' . $backup['Backup']['id']) . " " .
		$html->link('Delete', '/backups/delete/' . $backup['Backup']['id'], null, 'Are you sure you want to delete this file?')));
	?>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>You have not backed up any files.</p>
<?php endif; ?>
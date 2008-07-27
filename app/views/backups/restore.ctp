<h2>Restore</h2>
<?php if($backups): ?>
<table>
<?php echo $html->tableHeaders(array('Name','Size','Type','Date','View','Download'));?>
<?php foreach($backups as $backup): ?>
	<?php echo $html->tableCells(array(
		$backup['Backup']['name'],
		$number->toReadableSize($backup['Backup']['size']),
		$backup['Backup']['type'],
		$time->niceShort($backup['Backup']['created']),
		$html->link('View', '/backups/view/' . $backup['Backup']['id']),
		$html->link('Download', '/backups/download/' . $backup['Backup']['id'])));
	?>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>You have not backed up any files.</p>
<?php endif; ?>
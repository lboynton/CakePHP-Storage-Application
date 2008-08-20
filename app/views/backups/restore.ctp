<?php /* pass sorting args to paginator functions */ $paginator->options(array('url' => $this->passedArgs, 'update' => 'content', 'indicator' => 'loadingIndicator')); ?>
<h2>Restore </h2>
<div id="subMenu">
	<?php echo $paginator->prev('&laquo; Previous page', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
	<?php echo $paginator->next('Next page &raquo;', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
</div>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<?php if($backups): ?>
<table>
	<tr>
    	<th><?php echo $paginator->sort('Name', 'name'); ?></th>
        <th><?php echo $paginator->sort('Size', 'size'); ?></th>
        <th><?php echo $paginator->sort('Date', 'date'); ?></th>
        <th>Actions</th>
    </tr>
<?php foreach($backups as $backup): ?>
	<tr>
		<td><?php echo $backup['Backup']['name'] ?></td>
		<td><?php echo $number->toReadableSize($backup['Backup']['size']) ?></td>
		<td><?php echo $time->niceShort($backup['Backup']['created']) ?></td>
		<td><?php echo $html->link('View', '/backups/view/' . $backup['Backup']['id']) ?> <?php echo $html->link('Download', '/backups/download/' . $backup['Backup']['id']) ?> <?php echo $html->link('Delete', '/backups/delete/' . $backup['Backup']['id'], null, 'Are you sure you want to delete this file?'); ?>        </td>
    </tr>
<?php endforeach; ?>
</table>
<?php else: ?>
<p>You have not backed up any files.</p>
<?php endif; ?>
<div id="pagination">
    <span class="box"><?php echo $paginator->counter(array('format' => 'Page %page% of %pages%')); ?>&nbsp;</span>
    <span class="box">Go to page:&nbsp;<?php echo $paginator->numbers(array('separator' => '')); ?></span>
</div>
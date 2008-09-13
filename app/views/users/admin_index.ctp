<h2>Users</h2>
<div id="subMenu">
	<?php echo $paginator->prev('&laquo; Previous page', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
	<?php echo $paginator->next('Next page &raquo;', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
</div>
<table>
    <tr>
    	<th>Name</th><th>Username</th><th>Quota</th>
    </tr>
    <?php if(isset($users)): ?>
    	<?php foreach($users as $user): ?>
        <tr>
        	<td><?php echo $user['User']['real_name'] ?></td>
            <td><?php echo $user['User']['username'] ?></td>
            <td><?php echo $number->toReadableSize($user['User']['quota']); ?></td>
        </tr>
        <?php endforeach; ?>
    <?php else: ?>
    <tr>
        <td colspan="3">There are no users to view.</td>
    </tr>
    <?php endif; ?>
</table>
<div id="pagination">
    <span class="box"><?php echo $paginator->counter(array('format' => 'Page %page% of %pages%, %count% users found, showing %start%-%end%.')); ?>&nbsp;</span>
    <span class="box">Go to page:&nbsp;<?php echo $paginator->numbers(array('separator' => '')); ?></span>
</div>
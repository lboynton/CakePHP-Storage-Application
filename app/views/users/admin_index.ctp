<h2>Users</h2>
<div id="subMenu">
	<?php echo $paginator->prev('&laquo; Previous page', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
	<?php echo $paginator->next('Next page &raquo;', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
</div>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<p>To view a user, and to change a user's quota or administrator status, click on the user's username.
<table>
    <tr>
    	<th></th><th>Name</th><th>Username</th><th>Quota</th><th>Registered</th><th>Last login</th>
    </tr>
    <?php if(isset($users)): ?>
    	<?php foreach($users as $user): ?>
        <tr>
			<td><?php echo $userDetails->icon($user['User']['admin']); ?></td>
        	<td><?php echo $user['User']['real_name'] ?></td>
            <td><?php echo $html->link($user['User']['username'], '/admin/users/view/' . $user['User']['id']) ?></td>
            <td><?php echo $number->toReadableSize($user['User']['quota']); ?></td>
			<td><?php echo $time->niceShort($user['User']['created']); ?></td>
			<td><?php echo $time->niceShort($user['User']['last_login']); ?></td>
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
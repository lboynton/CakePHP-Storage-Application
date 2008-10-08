<?php $paginator->options(array('update'=>'UserPaging', 'url'=> $url, 'indicator' => 'loadingIndicator')); ?> 

<fieldset class="compact full">
<?php echo $form->create('User', array('action' => 'perform_action')); ?>
	<table>
		<tr>
			<th class="checkbox"><?php echo $form->checkbox('selectAllTop', array('class' => 'controller actionBox')); ?></th>
			<th class="icon"><?php echo $paginator->sort($html->image('user_gray.png'), 'admin', array('escape' => false)); ?></th>
			<th><?php echo $paginator->sort('Name', 'real_name'); ?></th>
			<th><?php echo $paginator->sort('Username', 'username'); ?></th>
			<th><?php echo $paginator->sort('Quota', 'quota'); ?></th>
			<th><?php echo $paginator->sort('Registered', 'created'); ?></th>
			<th><?php echo $paginator->sort('Last login', 'last_login'); ?></th>
			<th class="checkbox"><?php echo $paginator->sort('Disabled', 'disabled'); ?></th>
		</tr>
		<?php if(isset($users) && !empty($users)): ?>
			<?php foreach($users as $i => $user): ?>
			<tr<?php echo ($i % 2 == 0) ? " class='altrow'" : "" ?>>
				<td class="checkbox"><?php echo $form->checkbox('User.ids.'.$user['User']['id'], array('class' => 'actionBox')); ?></td>
				<td class="icon"><?php echo $userDetails->icon($user['User']['admin'], '/admin/users/view/' . $user['User']['id']); ?></td>
				<td><?php echo $html->link($user['User']['real_name'], '/admin/users/view/' . $user['User']['id']) ?></td>
				<td><?php echo $html->link($user['User']['username'], '/admin/users/view/' . $user['User']['id']) ?></td>
				<td><?php echo $number->toReadableSize($user['User']['quota']); ?></td>
				<td><?php echo $time->niceShort($user['User']['created']); ?></td>
				<td><?php echo $time->niceShort($user['User']['last_login']); ?></td>
				<td class="checkbox"><?php echo $form->checkbox('User.disable_ids.'.$user['User']['id'], array('checked' => (boolean)$user['User']['disabled'])); ?></td>
			</tr>
			<?php endforeach; ?>
			<tr<?php echo ($i % 2 != 0) ? " class='altrow'" : "" ?> id="tableFooter">
				<td class="checkbox"><?php echo $form->checkbox('selectAllBottom', array('class' => 'controller actionBox')); ?></td>
				<td id="actions" colspan="6">
					Perform action on selected users:
					<?php echo $form->input('action', array('type' => 'radio', 'options' => array('quota' => 'Change account quotas', 'delete' => 'Delete user accounts'), 'value' => 'quota', 'legend' => false, 'div' => false)); ?>
					<?php echo $form->submit('Go'); ?>
				</td>
				<td>
					<?php echo $form->submit('Save', array('name' => 'data[User][action]')); ?>
				</td>
			</tr>
		<?php else: ?>
		<tr>
			<td colspan="8">There are no users to view.</td>
		</tr>
		<?php endif; ?>
	</table>
<?php echo $form->end(); ?>
</fieldset>

<div id="pagination">
    <span class="box"><?php echo $paginator->counter(array('format' => 'Page %page% of %pages%, %count% users found, showing %start%-%end%.')); ?>&nbsp;</span>
    <span class="box">Go to page:&nbsp;<?php echo $paginator->numbers(array('separator' => '')); ?></span>
	<?php echo $paginator->prev('&laquo; Previous page', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
	<?php echo $paginator->next('Next page &raquo;', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
</div>
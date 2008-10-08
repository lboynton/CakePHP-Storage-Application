<?php $paginator->options(array('url' => $url, 'update' => 'content', 'indicator' => 'loadingIndicator')); ?>

<h2>Users</h2>
<div id="subMenu">
	<?php echo $paginator->prev('&laquo; Previous page', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
	<?php echo $paginator->next('Next page &raquo;', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
</div>

<?php if($session->check('Message.flash')) $session->flash(); ?>
<p>To view a user, and to change a user's quota or administrator status, click on the user's username or icon.</p>

<fieldset class="fieldset2">
<?php echo $form->create('User', array('action' => 'admin_index')); ?>
	<?php echo $form->input('query', array('label' => 'Search terms', 'after' => ' ' . $form->submit('Filter'))); ?>
	<div id="advancedOptions">
		<?php echo $form->input('field', array('label' => 'Search for', 'options' => array('real_name' => 'Real name', 'username' => 'Username', 'email' => 'Email address'), 'selected' => $field)); ?>
		<?php echo $form->input('admin', array('label' => 'Users who are', 'options' => array('' => 'Administrators or normal users', '1' => 'Administrators', '0' => 'Normal users'))); ?>
		<?php //echo $form->input('created', array('label' => 'Registered date', 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => 2008, 'maxYear' => date('Y'), 'empty' => '---')); ?>
		<?php echo $form->input('disabled', array('label' => 'Accounts which are', 'options' => array('' => 'Enabled or disabled', '0' => 'Enabled', '1' => 'Disabled'))); ?>
		<?php echo $form->input('show', array('selected' => $show, 'after' => ' results', 'options' => array(10 => 10, 25 => 25, 50 => 50, 75 => 75, 100 => 100, 200 => 200, 500 => 500))); ?>
	</div>
	<div id="advancedControl" style="display:none">
		<?php echo $form->label('advanced', 'Advanced options', array('class' => 'input')); ?>
		<?php echo $form->input('advanced', array('type' => 'checkbox', 'label' => false)); ?>
	</div>
<?php echo $form->end(); ?>
</fieldset>

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
</div>

<?php echo $javascript->event('window', 'load', '$(\'advancedControl\').show()'); ?>
<?php if($advanced != 1) echo $javascript->event('window', 'load', '$(\'advancedOptions\').hide()'); ?>
<?php echo $javascript->event('UserAdvanced', 'click', 'Effect.toggle(\'advancedOptions\', \'blind\')'); ?>
<?php echo $javascript->event('UserSelectAllTop', 'click', 'toggleCheckboxes(\'UserSelectAllTop\', \'actionBox\');'); ?>
<?php echo $javascript->event('UserSelectAllBottom', 'click', 'toggleCheckboxes(\'UserSelectAllBottom\', \'actionBox\');'); ?>
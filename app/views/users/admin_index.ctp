<h2>Users</h2>
<p>Individual user settings can be applied by selecting the user you wish to make changes to.</p>

<?php if($session->check('Message.flash')) $session->flash(); ?>

<fieldset class="fieldset2">
<?php echo $form->create('User', array('action' => 'admin_index')); ?>
	<?php echo $form->input('query', array('label' => 'Search terms')); ?>
	<div id="advancedOptions">
		<?php echo $form->input('field', array('label' => 'Search for', 'options' => array('real_name' => 'Real name', 'username' => 'Username', 'email' => 'Email address'), 'selected' => $field)); ?>
		<?php echo $form->input('admin', array('label' => 'Users who are', 'options' => array('' => 'Administrators or normal users', '1' => 'Administrators', '0' => 'Normal users'))); ?>
		<?php //echo $form->input('created', array('label' => 'Registered date', 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => 2008, 'maxYear' => date('Y'), 'empty' => '---')); ?>
		<?php echo $form->input('disabled', array('label' => 'Accounts which are', 'options' => array('' => 'Enabled or disabled', '0' => 'Enabled', '1' => 'Disabled'))); ?>
		<?php echo $form->input('show', array('label' => 'I want to see', 'selected' => $show, 'after' => ' results', 'options' => array(10 => 10, 25 => 25, 50 => 50, 75 => 75, 100 => 100, 200 => 200, 500 => 500))); ?>
	</div>
	<div id="advancedControl" style="display:none">
		<?php echo $form->label('advanced', 'Advanced options', array('class' => 'input')); ?>
		<?php echo $form->input('advanced', array('type' => 'checkbox', 'label' => false)); ?>
		<div class="clear"></div>
	</div>
	<?php echo $form->submit('Filter'); ?>
<?php echo $form->end(); ?>
</fieldset>

<div id="UserPaging">
    <?php echo $this->renderElement('users/paging'); ?>
</div>

<?php echo $javascript->event('document', 'dom:loaded', '$(\'advancedControl\').show()'); ?>
<?php if(!(boolean)$advanced) echo $javascript->event('document', 'dom:loaded', '$(\'advancedOptions\').hide()'); ?>
<?php echo $javascript->event('UserAdvanced', 'click', 'Effect.toggle(\'advancedOptions\', \'blind\')'); ?>
<?php echo $javascript->event('UserSelectAllTop', 'click', 'toggleCheckboxes(\'UserSelectAllTop\', \'actionBox\');'); ?>
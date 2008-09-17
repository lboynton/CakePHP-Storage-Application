<h2>View User</h2>
<?php if($session->check('Message.flash')) $session->flash(); ?>

<h3>User details</h3>
<dl>
	<dt>Username</dt><dd><?php echo $user['User']['username']; ?></dd>
	<dt>Real name</dt><dd><?php echo $user['User']['real_name']; ?></dd>
	<dt>Email</dt><dd><?php echo $html->link($user['User']['email'], 'mailto:' . $user['User']['email']); ?></dd>
	<dt>Registered</dt><dd><?php echo $time->niceShort($user['User']['created']); ?></dd>
	<dt>Last login</dt><dd><?php echo $time->niceShort($user['User']['last_login']); ?></dd>
	<dt>User level</dt><dd><?php echo $userDetails->userLevel($user['User']['admin']); ?></dd>
</dl>

<h3>Storage statistics</h3>
<dl>
    <dt>Files in storage</dt><dd><?php echo $backupCount; ?></dd>
	<dt>Quota</dt><dd><?php echo $number->toReadableSize($user['User']['quota']); ?></dd>
	<dt>Usage</dt><dd><?php echo $number->toReadableSize($backupSum[0][0]['size']); ?></dd>
    <dt>Usage percentage</dt><dd><?php echo $percentage->chart(@($backupSum[0][0]['size'] / $user['User']['quota'] * 100), true); ?></dd>
</dl>

<h3>User quota</h3>
<p>The quota limits the amount of storage the selected user can use. <strong>Note:</strong> This will not take effect while the user is logged in.</p>
<?php echo $form->create(null, array('action' => 'view/' . $user['User']['id'], 'class' => 'fieldset2')); ?>
<?php echo $form->input('quota', array('value' => $quota, 'after' => ' ' . $form->input('unit', array('options' => array('b' => 'Bytes', 'kb' => 'Kilobytes', 'mb' => 'Megabytes', 'gb' => 'Gigabytes'), 'value' => 'mb', 'div' => false, 'label' => false)))); ?>
<?php echo $form->end('Update'); ?>

<h3>User level</h3>
<p>Make this user an administrator. Administrators can view all other users, and make changes to those users' quota and administrator status.</p>
<?php echo $form->create(null, array('action' => 'user_level/' . $user['User']['id'], 'class' => 'fieldset2')); ?>
<?php echo $form->label('admin', 'Administrator', array('class' => 'input')); ?>
<?php echo $form->input('admin', array('label' => false, 'checked' => (boolean)$user['User']['admin'], 'class' => 'input checkbox')); ?>
<?php echo $form->end('Update'); ?>
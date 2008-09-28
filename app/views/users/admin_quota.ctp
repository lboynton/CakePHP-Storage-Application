<h2>Edit quotas</h2>
<p>The quota limits the amount of storage the selected users can use. <strong>Note:</strong> This will not take effect while the users are logged in.</p>
<fieldset class="fieldset1">
	<?php echo $form->create(null, array('action' => 'admin_quota')); ?>
		<?php echo $form->input('quota', array('after' => ' ' . $form->input('unit', array('options' => array('b' => 'Bytes', 'kb' => 'Kilobytes', 'mb' => 'Megabytes', 'gb' => 'Gigabytes'), 'value' => 'mb', 'div' => false, 'label' => false)))); ?>
	<?php echo $form->end('Update'); ?>
</fieldset>
<p><?php echo $html->link('Cancel', '/admin/users'); ?></p>
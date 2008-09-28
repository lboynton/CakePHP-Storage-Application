<h2>Settings</h2>
<?php if($session->check('Message.flash')) $session->flash(); ?>
<p>Change site parameters here.</p>
<?php echo $form->create(null, array('action' => 'admin_index', 'class' => 'fieldset2')); ?>
	<?php echo $form->input('default_quota', array('label' => 'Default user quota', 'value' => $quota['value'], 'after' => ' ' . $form->input('unit', array('options' => array('b' => 'Bytes', 'kb' => 'Kilobytes', 'mb' => 'Megabytes', 'gb' => 'Gigabytes'), 'value' => $quota['shortUnit'], 'div' => false, 'label' => false)))); ?>
	<p id="SiteParameterDefaultQuotaHelp" class="help">The default quota will be applied to all newly registered user accounts.</p> 
	
	<?php echo $form->input('upload_limit'); ?>
<?php echo $form->end('Update'); ?>
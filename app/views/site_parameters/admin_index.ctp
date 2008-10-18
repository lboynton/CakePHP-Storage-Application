<h2>Settings</h2>
<fieldset class="fieldset1">
<?php if($session->check('Message.flash')) $session->flash(); ?>	
	<?php echo $form->create(null, array('action' => 'admin_index')); ?>
		<?php echo $form->input('default_quota', array('label' => 'Default user quota', 'value' => $quota['value'], 'after' => ' ' . $form->input('unit', array('options' => array('b' => 'Bytes', 'kb' => 'Kilobytes', 'mb' => 'Megabytes', 'gb' => 'Gigabytes'), 'value' => $quota['shortUnit'], 'div' => false, 'label' => false)))); ?>
		<p id="SiteParameterDefaultQuotaHelp" class="help">The default quota will be applied to all newly registered user accounts.</p> 
		
		<?php echo $form->input('upload_limit', array('value' => $upload_limit, 'after' => ' megabytes')); ?>
		<p id="SiteParameterUploadLimitHelp" class="help">The upload size limit shoud correspond with the POST, upload and memory settings of PHP.</p> 
	<?php echo $form->end('Update'); ?>
</fieldset>
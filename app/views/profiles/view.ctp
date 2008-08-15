<h2><?php echo $profile['Profile']['name']?> Profile</h2>
<p>Here are the settings for this profile:</p>
<dl>
	<dt>Database</dt><dd><?php echo $profile['Profile']['database']?></dd>
	<dt>Server</dt><dd><?php echo $profile['Profile']['server']?></dd>
	<dt>Port</dt><dd><?php echo $profile['Profile']['port']?></dd>
    <dt>Username</dt><dd><?php echo $profile['Profile']['username']?> </dd>
    <dt>Prefix</dt><dd><?php echo $profile['Profile']['prefix']?> </dd>
    <dt>Connected</dt><dd>No</dd>
</dl>
<div class="message"><p>Click below to change to this profile configuration.</p></div>
<?php echo $form->create('Profile', array('action' => 'change')); ?>
<?php echo $form->end('Use this profile'); ?>
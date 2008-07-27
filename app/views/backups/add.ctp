<h2>Backup</h2>
<?php //echo $form->create('Backup', array('enctype' => 'multipart/form-data') ); ?>
<?php //echo $form->file('File[]'); ?>
<?php //echo $form->file('File[]'); ?>
<?php //echo $form->file('File[]'); ?>
<?php //echo $form->end('Backup'); ?>
<applet mayscript="true" id="backupApplet" codebase="/" archive="UploadApplet.jar" code="uploadapplet.UploadApplet.class" width="100%" height="400">
	<param name="postUrl" value="http://backup/backups/add" />
    <param name="redirectUrl" value="http://backup/backups/restore" />
</applet>
<a href="#" onclick="document.getElementById('backupApplet').height = parseInt(document.getElementById('backupApplet').height) - 100;">Reduce</a>
<a href="#" onclick="document.getElementById('backupApplet').height = parseInt(document.getElementById('backupApplet').height) + 100;">Expand</a>
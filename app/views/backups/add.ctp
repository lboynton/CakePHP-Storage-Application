<h2>Backup</h2>
<?php echo $form->create('Backup', array('enctype' => 'multipart/form-data') ); ?>
<?php echo $form->input('File[]', array('type' => 'file')); ?>
<?php echo $form->end('Backup'); ?>
<applet mayscript="true" id="backupApplet" codebase="/" archive="UploadApplet.jar" code="uploadapplet.UploadApplet.class" width="100%" height="400"></applet>
<a href="#" onclick="document.getElementById('backupApplet').height = parseInt(document.getElementById('backupApplet').height) - 100;">Reduce</a>
<a href="#" onclick="document.getElementById('backupApplet').height = parseInt(document.getElementById('backupApplet').height) + 100;">Expand</a>
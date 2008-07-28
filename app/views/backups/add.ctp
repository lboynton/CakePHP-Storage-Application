<h2>Backup</h2>
<?php //echo $form->create('Backup', array('enctype' => 'multipart/form-data') ); ?>
<?php //echo $form->file('File[]'); ?>
<?php //echo $form->file('File[]'); ?>
<?php //echo $form->file('File[]'); ?>
<?php //echo $form->end('Backup'); ?>
<applet mayscript="true" id="backupApplet" codebase="/" archive="UploadApplet.jar,xstream-1.3.jar,xpp3_min-1.1.4c.jar" code="uploadapplet.UploadApplet.class" width="100%" height="400">
	<param name="postUrl" value="<?php echo "http://" . $_SERVER["HTTP_HOST"] . $_SERVER['REQUEST_URI'];  ?>" />
    <param name="redirectUrl" value="http://backup/backups/restore" />
</applet>
<!-- <applet codebase="/" archive="UploadApplet.jar,xstream-1.3.jar,xpp3_min-1.1.4c.jar" code="uploadapplet.TestApplet.class"></applet> -->
<a href="#" onclick="document.getElementById('backupApplet').height = parseInt(document.getElementById('backupApplet').height) - 100;">Reduce</a>
<a href="#" onclick="document.getElementById('backupApplet').height = parseInt(document.getElementById('backupApplet').height) + 100;">Expand</a>
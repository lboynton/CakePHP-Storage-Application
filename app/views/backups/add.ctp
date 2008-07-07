<h2>Backup</h2>
<?php echo $form->create('Backup', array('enctype' => 'multipart/form-data') ); ?>
<?php echo $form->input('File', array('type' => 'file')); ?>
<?php echo $form->end('Backup'); ?>
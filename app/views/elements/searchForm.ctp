<?php echo $form->create('backups', array('type' => 'get', 'action' => 'search', 'class' => 'search')); ?>
<?php echo $form->input('search', array('name' => 'query')); ?>
<?php echo $form->input('folder', array('type' => 'hidden', 'value' => 'all')); ?>
<?php echo $form->end('Search'); ?>
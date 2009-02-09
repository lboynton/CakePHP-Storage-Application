<?php echo $form->create(null, array('url' => array('controller' => 'backups', 'action' => 'search', 'admin' => false), 'type' => 'get', 'class' => 'search')); ?>
    <?php echo $form->input('search', array('name' => 'query')); ?>
    <?php echo $ajax->autoComplete('Backup.name', '/backups/autoComplete')?>
    <?php echo $form->input('folder', array('type' => 'hidden', 'value' => 'all', 'id' => false)); ?>
<?php echo $form->end('Search'); ?>
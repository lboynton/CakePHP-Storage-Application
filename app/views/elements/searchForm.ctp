<?php echo $form->create(null, array('url' => array('controller' => 'backups', 'action' => 'search', 'admin' => false), 'type' => 'get', 'class' => 'search')); ?>
    <div class="text"><?php echo $ajax->autoComplete('data[Backup][name]', '/backups/autoComplete'); ?></div>
    <?php echo $form->input('folder', array('type' => 'hidden', 'value' => 'all', 'id' => false)); ?>
<?php echo $form->end('Search'); ?>

<?php if($ajax->isAjax()): // if ajax show the new name ?>
	<?php echo $file['Backup']['name']; ?>
<?php else: // else show the rename form ?>
    <h2>Rename file</h2>
    <?php echo $form->create(null, array('url' => '/backups/rename/' . $this->params['pass'][0])); ?>
    <?php echo $form->input('Name', array('value' => $file['Backup']['name'])); ?>
    <?php echo $form->end('Rename'); ?>
<?php endif; ?>
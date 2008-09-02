<?php if($ajax->isAjax()): // if ajax show the new name ?>
<?php echo $folder['BackupFolder']['name']; ?>
<?php else: // else show the rename form ?>
    <h2>Rename folder</h2>
    <p>Please enter the new name for the folder.</p>
    <fieldset>
		<?php echo $form->create(null, array('url' => '/backup_folders/rename/' . $this->params['pass'][0])); ?>
        	<?php if($session->check('Message.flash')) $session->flash(); ?>
        	<?php echo $form->input('name', array('value' => $folder['BackupFolder']['name'])); ?>
        <?php echo $form->end('Rename'); ?>
    </fieldset>
<?php endif; ?>
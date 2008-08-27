<?php if($ajax->isAjax()): // if ajax show the new name ?>
<?php echo $file['Backup']['name']; ?>
<?php else: // else show the rename form ?>
    <h2>Rename file</h2>
    <p>Please enter the new name for the file.</p>
    <fieldset>
		<?php echo $form->create(null, array('url' => '/backups/rename/' . $this->params['pass'][0])); ?>
        	<?php if($session->check('Message.flash')) $session->flash(); ?>
        	<?php echo $form->input('Name', array('value' => $file['Backup']['name'])); ?>
        <?php echo $form->end('Rename'); ?>
    </fieldset>
<?php endif; ?>
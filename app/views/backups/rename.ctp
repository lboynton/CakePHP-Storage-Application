<?php if($ajax->isAjax()): // if ajax show the new name ?>
fef
<?php echo $file['Backup']['name']; ?>
<?php else: // else show the rename form ?>
    <h2>Rename file</h2>
    <p>Please enter the new name for the file.</p>
	<?php if($session->check('Message.flash')) $session->flash(); ?>
    <fieldset class="fieldset1">
		<?php echo $form->create(null, array('url' => '/backups/rename/' . $this->params['pass'][0])); ?>
        	<?php echo $form->input('name', array('value' => $file['Backup']['name'])); ?>
        <?php echo $form->end('Rename'); ?>
    </fieldset>
<?php endif; ?>
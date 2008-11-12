<h2>File management</h2>

<?php if($session->check('Message.flash')) $session->flash(); ?>

<div id="column">
	<?php if(!isset($view) || $view != "all"): ?>
		<h5><a href="javascript:;" id="uploadHelpControl" class="helpControl"></a>Upload File/Archive </h5>
		<fieldset class="compact">
			<?php echo $form->create('Backup', array('class' => 'compact', 'enctype' => 'multipart/form-data')); ?>
				<?php echo $form->input('file', array('type' => 'file', 'label' => 'Select file (max: ' . $number->toReadableSize($upload_limit) . ')')); ?>
				<?php echo $form->hidden('parent_id', array('value' => $folder_id, 'id' => null)); ?>
			<?php echo $form->end('Upload'); ?>
			<div class="box" id="uploadHelp" style="display:none">
				<strong>Help:</strong> Select browse to select a file to upload. Add your files and folders to a ZIP archive to upload multiple files at once. When you've selected a file, select the 'Upload' button to start the upload process.
			</div>
		</fieldset>
	
		<h5><a href="javascript:;" id="addFolderHelpControl" class="helpControl"></a>Add Folder</h5>
		<fieldset class="compact">
			<?php echo $form->create('Backup', array('class' => 'compact', 'action' => 'add_folder')); ?>
				<?php echo $form->input('name', array('label' => 'Name')); ?>
				<?php echo $form->hidden('parent_id', array('value' => $folder_id, 'id' => null)); ?>
			<?php echo $form->end('Add'); ?>
			<div class="box" id="addFolderHelp" style="display:none">
				<strong>Help:</strong> Enter the name for the new folder above, and click the 'Add' button to add the new folder. The folder will be created in the currently displayed folder.
			</div>
		</fieldset>
	<?php endif; ?>
</div>

<div id="main">
	<?php echo $this->renderElement('backups/paging'); ?>
</div>

<?php if(!isset($view) || $view != "all") echo $javascript->event('uploadHelpControl', 'click', 'Effect.toggle(\'uploadHelp\', \'blind\')'); ?>
<?php if(!isset($view) || $view != "all") echo $javascript->event('addFolderHelpControl', 'click', 'Effect.toggle(\'addFolderHelp\', \'blind\')'); ?>
<?php echo $javascript->event('BackupSelectAllTop', 'click', 'toggleCheckboxes(\'BackupSelectAllTop\');'); ?>
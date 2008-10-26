<?php /* pass sorting args to paginator functions */ $paginator->options(array('url' => $this->passedArgs, 'update' => 'content', 'indicator' => 'loadingIndicator')); ?>

<div class="pagination">
	<?php echo $paginator->prev('<span>&laquo;</span> Previous page', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
	<?php echo $paginator->next('Next page <span>&raquo;</span>', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
</div>

<h2>File management</h2>

<div class="pagination">
	Page: <?php echo $paginator->numbers(array('separator' => '')); ?>
</div>

<p>Folder: 
<?php if($query == ""): ?>	
	<?php echo $html->link('Storage', '/backups'); ?>
    <?php if(isset($path)): ?>
        <?php foreach($path as $folder): ?>
        &raquo; <?php echo $html->link($folder['Backup']['name'], '/backups/index/view:' . $folder['Backup']['id']); ?>
        <?php endforeach; ?>
	<?php endif; ?>
<?php else: ?>
	Showing files in all folders
<?php endif; ?>
</p>

<?php if($session->check('Message.flash')) $session->flash(); ?>
<div id="column">
	
	<h5><?php if($query != ""): echo $html->link('(Reset)', '/backups', array('class' => 'reset')); endif; ?>Search</h5>
	<fieldset class="compact">
		
		<?php echo $form->create(array('action' => 'index', 'type' => 'get', 'class' => 'compact')); ?> 
			<?php echo $form->input('Search', array('name' => 'query', 'value' => $query)); ?>
			<?php echo $form->input('show', array($form->submit('Search'), 'options' => array(10 => 10, 25 => 25, 50 => 50, 75 => 75, 100 => 100), 'selected' => 25, 'after' => ' results')); ?>
			<?php echo $form->input('view', array('label' => 'Look', 'options' => array('all' => 'In all folders', $folder_id => 'Only in this folder'), 'selected' => $folder_id)); ?>
			<?php echo $form->submit('Search'); ?>
		<?php echo $form->end(); ?>
	</fieldset>
	
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

	<?php echo $paginator->counter(array('format' => 'Page %page% of %pages%, %count% files found, showing %start%-%end%.')); ?>
</div>

<div id="main">
<?php echo $form->create('Backup', array('action' => 'perform_action')); ?>
	<table>
        <tr>
            <th class="checkbox"><?php echo $form->checkbox('selectAllTop', array('class' => 'controller')); ?></th>
            <th class="type"></th>
            <th class="name"><?php echo $paginator->sort('Name', 'name'); ?></th>
            <th><?php echo $paginator->sort('Size', 'size'); ?></th>
			<?php if(!empty($query)): ?><th>Folder</th><?php endif; ?>
            <th><?php echo $paginator->sort('Created', 'created'); ?></th>
            <th><?php echo $paginator->sort('Modified', 'modified'); ?></th>
            <noscript><th>Actions</th></noscript>
        </tr>
<?php if($backups): ?>
    <?php foreach($backups as $i => $backup): ?>
        <tr<?php echo ($i % 2 == 0) ? " class='altrow'" : "" ?>>
            <td class="checkbox"><?php echo $form->checkbox('Backup.ids.'.$backup['Backup']['id']); ?></td>
            <td class="type"><?php echo $file->icon($backup['Backup']['type'], '/backups/index/view:' . $backup['Backup']['id'], $backup['Backup']['name']); ?></td>
            <td class="name"><p id="<?php echo 'fileRename' . $backup['Backup']['id'] ?>"><?php echo $backup['Backup']['name']; ?></p></td>
            <td><?php if($backup['Backup']['type'] != 'folder') echo $number->toReadableSize($backup['Backup']['size']); ?></td>
			<?php if(!empty($query)): ?><td><?php echo $html->link($backup['Backup']['folder_name'], '/backups/index/view:' . $backup['Backup']['parent_id']); ?></td><?php endif; ?>
            <td><?php echo $time->niceShort($backup['Backup']['created']); ?></td>
            <td><?php echo $time->niceShort($backup['Backup']['modified']); ?></td>
            <noscript><td><?php echo $html->link('Rename', '/backups/rename/' . $backup['Backup']['id']) ?></td></noscript>
            <?php echo $ajax->editor('fileRename' . $backup['Backup']['id'], '/backups/rename/' . $backup['Backup']['id'], array('callback' => "return 'data[Backup][name]=' + escape(value)")); ?>
        </tr>
    <?php endforeach; ?>
        <tr<?php echo ($i % 2 != 0) ? " class='altrow'" : "" ?> id="tableFooter">
            <td id="actions" colspan="7">
            	Perform action on selected items:
                <?php echo $form->input('action', array('type' => 'radio', 'options' => array('download' => 'Download', 'delete' => 'Delete', 'move' => 'Move to'), 'value' => 'download', 'legend' => false, 'div' => false)); ?>
                <?php if(empty($folders)): ?>
					<?php echo $form->input('nofolders', array('options' => array('No folders exist'), 'div' => false, 'label' => 'folder')); ?>
					<?php echo $javascript->event('BackupNofolders', 'focus', '$(\'BackupActionMove\').checked = true;'); ?>
				<?php else: ?>
					<?php echo $form->input('folder', array('options' => $folders, 'div' => false, 'label' => 'folder', 'escape' => false)); ?>
					<?php echo $javascript->event('BackupFolder', 'focus', '$(\'BackupActionMove\').checked = true;'); ?>
				<?php endif; ?>
                <?php echo $form->submit('Go'); ?>
            </td>
        </tr>
<?php else: ?>
        <tr>
            <td colspan="7"><p>There are no files or folders to display.</p></td>
        </tr>
<?php endif; ?>
	</table>
<?php echo $form->end(); ?>
</div>

<?php echo $javascript->event('uploadHelpControl', 'click', 'Effect.toggle(\'uploadHelp\', \'blind\')'); ?>
<?php echo $javascript->event('addFolderHelpControl', 'click', 'Effect.toggle(\'addFolderHelp\', \'blind\')'); ?>
<?php echo $javascript->event('BackupSelectAllTop', 'click', 'toggleCheckboxes(\'BackupSelectAllTop\');'); ?>
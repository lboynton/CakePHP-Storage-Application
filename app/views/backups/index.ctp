<?php /* pass sorting args to paginator functions */ $paginator->options(array('url' => $this->passedArgs, 'update' => 'content', 'indicator' => 'loadingIndicator')); ?>
<h2>File management</h2>

<div id="subMenu">
	<?php echo $paginator->prev('&laquo; Previous page', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
	<?php echo $paginator->next('Next page &raquo;', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
</div>

<?php if($session->check('Message.flash')) $session->flash(); ?>

<p>Upload files here. They can be uploaded individually or as part of a zip archive. Click the icon adjacent to the file or folder name to view it. To rename files or folders, simply click on the name.</p>

<fieldset class="compact">
	<?php if($query != ""): ?>
		<?php echo $html->link('Reset search', '/backups', array('class' => 'reset')); ?>
    <?php endif; ?>
	<?php echo $form->create(array('action' => 'index', 'type' => 'get', 'class' => 'compact')); ?> 
        <?php echo $form->input('Search', array('name' => 'query', 'value' => $query)); ?>
        <?php echo $form->input('Show', array('name' => 'show', 'after' => ' results ' . $form->submit('Search'), 'options' => array(10 => 10, 25 => 25, 50 => 50, 75 => 75, 100 => 100), 'selected' => 25)); ?>
		<?php echo $form->input('view', array('type' => 'checkbox', 'value' => $folder_id,'label' => 'Search in current folder')); ?>
    <?php echo $form->end(); ?>
</fieldset>

<fieldset class="compact">
    <?php echo $form->create('Backup', array('class' => 'compact', 'enctype' => 'multipart/form-data')); ?>
        <?php echo $form->input('file', array('type' => 'file', 'label' => 'Upload file (max: ' . $upload_limit . 'MB)')); ?>
        <?php echo $form->hidden('parent_id', array('value' => $folder_id, 'id' => null)); ?>
    <?php echo $form->end('Upload'); ?>
</fieldset>

<fieldset class="compact">
	<?php echo $form->create('Backup', array('class' => 'compact', 'action' => 'add_folder')); ?>
        <?php echo $form->input('name', array('label' => 'Create folder')); ?>
        <?php echo $form->hidden('parent_id', array('value' => $folder_id, 'id' => null)); ?>
    <?php echo $form->end('Add'); ?>
</fieldset>
<div style="clear:both;"></div><p>&nbsp;</p>
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

<fieldset class="compact full">
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
        	<td class="checkbox"><?php echo $form->checkbox('selectAllBottom', array('class' => 'controller')); ?></td>
            <td id="actions" colspan="6">
            	Perform action on selected items:
                <?php echo $form->input('action', array('type' => 'radio', 'options' => array('download' => 'Download', 'delete' => 'Delete', 'move' => 'Move to'), 'value' => 'download', 'legend' => false, 'div' => false)); ?>
                <?php echo $form->input('folder', array('options' => $folders, 'div' => false, 'label' => 'folder', 'escape' => false)); ?>
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
</fieldset>
<div id="pagination">
    <span class="box"><?php echo $paginator->counter(array('format' => 'Page %page% of %pages%, %count% files found, showing %start%-%end%.')); ?>&nbsp;</span>
    <span class="box">Go to page:&nbsp;<?php echo $paginator->numbers(array('separator' => '')); ?></span>
</div>

<?php echo $javascript->event('BackupSelectAllTop', 'click', 'toggleCheckboxes(\'BackupSelectAllTop\');'); ?>
<?php echo $javascript->event('BackupSelectAllBottom', 'click', 'toggleCheckboxes(\'BackupSelectAllBottom\');'); ?>
<?php echo $javascript->event('BackupFolder', 'focus', '$(\'BackupActionMove\').checked = true;'); ?>
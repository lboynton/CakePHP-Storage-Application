<?php /* pass sorting args to paginator functions */ $paginator->options(array('url' => $this->passedArgs, 'update' => 'content', 'indicator' => 'loadingIndicator')); ?>
<h2>File management</h2>

<div id="subMenu">
	<?php echo $paginator->prev('&laquo; Previous page', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
	<?php echo $paginator->next('Next page &raquo;', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
</div>

<?php if($session->check('Message.flash')) $session->flash(); ?>

<fieldset class="compact">
	<?php if($query != ""): ?>
		<?php echo $html->link('Reset', '/backups', array('class' => 'reset')); ?>
    <?php endif; ?>
	<?php echo $form->create(array('action' => 'index', 'type' => 'get', 'class' => 'compact')); ?> 
        <?php echo $form->input('Search', array('name' => 'query', 'value' => $query)); ?>
        <?php echo $form->input('Show', array('name' => 'show', 'after' => ' results ' . $form->submit('Filter'), 'options' => array(10 => 10, 25 => 25, 50 => 50, 75 => 75, 100 => 100))); ?>
    <?php echo $form->end(); ?>
</fieldset>

<fieldset class="compact">
    <?php echo $form->create('Backup', array('class' => 'compact', 'enctype' => 'multipart/form-data')); ?>
        <?php echo $form->input('file', array('type' => 'file', 'label' => 'Upload file')); ?>
        <?php echo $form->input('backup_folder_id', array('label' => 'Folder', 'options' => $directoriesList, 'after' => $form->submit('Upload'))); ?>
    <?php echo $form->end(); ?>
</fieldset>

<fieldset class="compact">
	<?php echo $form->create('BackupFolder', array('class' => 'compact')); ?>
        <?php echo $form->input('name', array('label' => 'Create folder')); ?>
        <?php echo $form->input('parent_id', array('label' => 'Folder', 'options' => $directoriesList, 'after' => $form->submit('Add'))); ?>
    <?php echo $form->end(); ?>
</fieldset>
<div style="clear:both;"></div>
<p>Folder: <?php echo $html->link('filestorage', '/backups'); ?>
<?php if(isset($path)): ?>
    <?php foreach($path as $folder): ?>
    &raquo; <?php echo $html->link($folder['BackupFolder']['name'], '/backups/index/folder:' . $folder['BackupFolder']['id']); ?>
    <?php endforeach; ?>
<?php endif; ?>
</p>

<?php echo $form->create('Backup', array('action' => 'perform_action')); ?>
	<table>
        <tr>
            <th class="checkbox"><?php echo $form->checkbox('selectAllTop', array('class' => 'controller JSRequired')); ?></th>
            <th class="type"></th>
            <th class="name"><?php echo $paginator->sort('Name', 'name'); ?></th>
            <th><?php echo $paginator->sort('Size', 'size'); ?></th>
            <th><?php echo $paginator->sort('Created', 'created'); ?></th>
            <th><?php echo $paginator->sort('Modified', 'modified'); ?></th>
            <th>Actions</th>
        </tr>
<?php if($directories): ?>
    <?php foreach($directories as $directory): ?>
    	<tr>
        	<td class="checkbox"><?php echo $form->checkbox('BackupFolder.ids.'.$directory['BackupFolder']['id']); ?></td>
            <td class="type"><?php echo $file->icon('directory'); ?></td>
            <td class="name"><p id="<?php echo 'folderRename' . $directory['BackupFolder']['id'] ?>"><?php echo $directory['BackupFolder']['name']; ?></p></td>
            <td></td>
            <td><?php echo $time->niceShort($directory['BackupFolder']['created']); ?></td>
            <td><?php echo $time->niceShort($directory['BackupFolder']['modified']); ?></td>
            <td><?php echo $html->link('View', '/backups/index/folder:' . $directory['BackupFolder']['id']); ?> <noscript>| <?php echo $html->link('Rename', '/backup_folders/rename/' . $directory['BackupFolder']['id']) ?></noscript></td>
            <?php echo $ajax->editor('folderRename' . $directory['BackupFolder']['id'], '/backup_folders/rename/' . $directory['BackupFolder']['id'], array('callback' => "return 'data[BackupFolder][name]=' + escape(value)")); ?>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
<?php if($backups): ?>
    <?php foreach($backups as $backup): ?>
        <tr>
            <td class="checkbox"><?php echo $form->checkbox('Backup.ids.'.$backup['Backup']['id']); ?></td>
            <td class="type"><?php echo $file->icon($backup['Backup']['type']); ?></td>
            <td class="name"><p id="<?php echo 'fileRename' . $backup['Backup']['id'] ?>"><?php echo $backup['Backup']['name']; ?></p></td>
            <td><?php echo $number->toReadableSize($backup['Backup']['size']); ?></td>
            <td><?php echo $time->niceShort($backup['Backup']['created']); ?></td>
            <td><?php echo $time->niceShort($backup['Backup']['modified']); ?></td>
            <td><?php echo $html->link('View', '/backups/view/' . $backup['Backup']['id']) ?> <noscript>| <?php echo $html->link('Rename', '/backups/rename/' . $backup['Backup']['id']) ?></noscript></td>
            <?php echo $ajax->editor('fileRename' . $backup['Backup']['id'], '/backups/rename/' . $backup['Backup']['id'], array('callback' => "return 'data[Backup][name]=' + escape(value)")); ?>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
<?php if(!($directories) && !($backups)): ?>
        <tr>
            <td colspan="7"><p>There are no files to display.</p></td>
        </tr>
<?php else: ?>
        <tr id="tableFooter">
        	<td class="checkbox"><?php echo $form->checkbox('selectAllBottom', array('class' => 'controller JSRequired')); ?></td>
            <td id="actions" colspan="7">
            	Perform action on selected items:
                <?php echo $form->input('action', array('type' => 'radio', 'options' => array('download' => 'Download', 'delete' => 'Delete'), 'value' => 'download', 'legend' => false)); ?>
                <?php echo $form->submit('Go'); ?>
            </td>
        </tr>
<?php endif; ?>
	</table>
<?php echo $form->end(); ?>

<div id="pagination">
    <span class="box"><?php echo $paginator->counter(array('format' => 'Page %page% of %pages%, %count% files found, showing %start%-%end%.')); ?>&nbsp;</span>
    <span class="box">Go to page:&nbsp;<?php echo $paginator->numbers(array('separator' => '')); ?></span>
</div>

<?php echo $javascript->event('BackupSelectAllTop', 'click', 'toggleCheckboxes(\'BackupSelectAllTop\');'); ?>
<?php echo $javascript->event('BackupSelectAllBottom', 'click', 'toggleCheckboxes(\'BackupSelectAllBottom\');'); ?>
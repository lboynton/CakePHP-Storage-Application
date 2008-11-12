<?php $paginator->options(array('url' => $this->passedArgs, 'update' => 'content', 'indicator' => 'loadingIndicator')); ?>

<p id="folder">Folder: 
<?php if(!isset($query)): ?>	
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
            <td class="type"><?php echo $file->icon($backup['Backup']['type'], '/backups/index/view:' . $backup['Backup']['id'], Sanitize::html($backup['Backup']['name'])); ?></td>
            <td class="name"><p id="<?php echo 'fileRename' . $backup['Backup']['id'] ?>"><?php echo Sanitize::html($backup['Backup']['name']); ?></p></td>
            <td><?php if($backup['Backup']['type'] != 'folder') echo $number->toReadableSize($backup['Backup']['size']); ?></td>
			<?php if(!empty($query)): ?><td><?php echo $html->link($backup['Backup']['folder_name'], '/backups/index/view:' . $backup['Backup']['parent_id']); ?></td><?php endif; ?>
            <td><?php echo $time->niceShort($backup['Backup']['created']); ?></td>
            <td><?php echo $time->niceShort($backup['Backup']['modified']); ?></td>
            <noscript><td><?php echo $html->link('Rename', '/backups/rename/' . $backup['Backup']['id']) ?></td></noscript>
            <?php echo $ajax->editor('fileRename' . $backup['Backup']['id'], '/backups/rename/' . $backup['Backup']['id'], array('callback' => "return 'data[Backup][name]=' + escape(value)")); ?>
        </tr>
    <?php endforeach; ?>
    <?php if(!isset($query)): ?>
        <tr<?php echo ($i % 2 != 0) ? " class='altrow'" : "" ?> id="tableFooter">
            <td id="actions" colspan="7">
            	Perform action on selected items:
                <?php echo $form->input('action', array('type' => 'radio', 'options' => array('download' => 'Download', 'delete' => 'Delete', 'move' => 'Move to'), 'value' => 'download', 'legend' => false, 'div' => false)); ?>
                <?php if(empty($folders)): ?>
					<?php echo $form->input('nofolders', array('options' => array('No folders exist'), 'div' => false, 'label' => 'folder')); ?>
					<?php echo $javascript->event('BackupNofolders', 'focus', '$(\'BackupActionMove\').checked = true;'); ?>
				<?php else: ?>
					<?php echo $form->input('folder', array('options' => $folders, 'div' => false, 'label' => 'folder')); ?>
					<?php echo $javascript->event('BackupFolder', 'focus', '$(\'BackupActionMove\').checked = true;'); ?>
				<?php endif; ?>
                <?php echo $form->submit('Go'); ?>
            </td>
        </tr>
     <?php endif; ?>
<?php else: ?>
        <tr>
            <td colspan="7"><p>There are no files or folders to display.</p></td>
        </tr>
<?php endif; ?>
	</table>
<?php echo $form->end(); ?>

<div id="pagination">
	<?php echo $paginator->prev('<span>&laquo;</span> Previous page', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
    <?php echo $paginator->numbers(array('separator' => '')); ?>
	<?php echo $paginator->next('Next page <span>&raquo;</span>', array('escape' => false), null, array('class' => 'disabled', 'escape' => false)); ?>
    <div><?php echo $paginator->counter(array('format' => 'Page %page% of %pages%, %count% files found, showing %start%-%end%.')); ?></div>
</div>
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
        <?php echo $form->input('Show', array('name' => 'show', 'after' => ' results', 'options' => array(10 => 10, 25 => 25, 50 => 50, 75 => 75, 100 => 100))); ?>
        <?php echo $form->submit('Filter'); ?>
    <?php echo $form->end(); ?>
</fieldset>

<fieldset class="compact">
    <?php echo $form->create('Backup', array('class' => 'compact', 'enctype' => 'multipart/form-data')); ?>
        <?php echo $form->input('file', array('type' => 'file', 'label' => 'Upload file')); ?>
        <?php echo $form->input('path', array('options' => $directoriesList, 'id' => null)); ?>
        <?php echo $form->submit('Upload'); ?>
    <?php echo $form->end(); ?>
</fieldset>

<fieldset class="compact">
	<?php echo $form->create(array('action' => 'add_folder', 'class' => 'compact')); ?>
        <?php echo $form->input('name', array('label' => 'Create folder', 'id' => null)); ?>
        <?php echo $form->input('path', array('options' => $directoriesList)); ?>
        <?php echo $form->submit('Add'); ?>
    <?php echo $form->end(); ?>
</fieldset>
<div style="clear:both;"></div>

<?php print_r($this->validationErrors) ?>

<?php if($backups): ?>
<?php echo $form->create('Backup', array('action' => 'test')); ?>
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
    <?php foreach($backups as $backup): ?>
        <tr>
            <td class="checkbox"><?php echo $form->checkbox('Backup.ids.'.$backup['Backup']['id']); ?></td>
            <td class="type"><?php echo $file->icon($backup['Backup']['type']); ?></td>
            <td class="name"><p id="<?php echo 'fileRename' . $backup['Backup']['id'] ?>"><?php echo $backup['Backup']['name']; ?></p></td>
            <td><?php echo $number->toReadableSize($backup['Backup']['size']); ?></td>
            <td><?php echo $time->niceShort($backup['Backup']['created']); ?></td>
            <td><?php echo $time->niceShort($backup['Backup']['modified']); ?></td>
            <td><?php echo $html->link('View', '/backups/view/' . $backup['Backup']['id']) ?> <noscript><?php echo $html->link('Rename', '/backups/rename/' . $backup['Backup']['id']) ?> <?php echo $html->link('Download', '/backups/download/' . $backup['Backup']['id']) ?> <?php echo $html->link('Delete', '/backups/delete/' . $backup['Backup']['id'], null, 'Are you sure you want to delete this file?'); ?></noscript></td>
            <?php echo $ajax->editor('fileRename' . $backup['Backup']['id'], '/backups/rename/' . $backup['Backup']['id'], array('callback' => "return 'data[Backup][name]=' + escape(value)")); ?>
        </tr>
    <?php endforeach; ?>
        <tr id="tableFooter">
        	<td class="checkbox"><?php echo $form->checkbox('selectAllBottom', array('class' => 'controller JSRequired')); ?></td>
            <td id="actions" colspan="6">
            	Perform action on selected items:
                <?php echo $form->input('action', array('type' => 'radio', 'options' => array('download' => 'Download', 'delete' => 'Delete'), 'value' => 'download', 'legend' => false)); ?>
                <?php echo $form->submit('Go'); ?>
            </td>
        </tr>
	</table>
<?php echo $form->end(); ?>
<?php else: ?>
	<p>There are no files to display.</p>
<?php endif; ?>

<div id="pagination">
    <span class="box"><?php echo $paginator->counter(array('format' => 'Page %page% of %pages%, %count% files found, showing %start%-%end%.')); ?>&nbsp;</span>
    <span class="box">Go to page:&nbsp;<?php echo $paginator->numbers(array('separator' => '')); ?></span>
</div>

<?php echo $javascript->event('BackupSelectAllTop', 'click', 'toggleCheckboxes(\'BackupSelectAllTop\');'); ?>
<?php echo $javascript->event('BackupSelectAllBottom', 'click', 'toggleCheckboxes(\'BackupSelectAllBottom\');'); ?>
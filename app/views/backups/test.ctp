<h2>Files</h2>
<?php echo $javascript->link('file_tree', false); ?>
<?php echo $form->create(null, array('action' => 'test')); ?>
<div id="tree-div" style="height:400px;"></div>
<?php echo $form->end('Submit'); ?>
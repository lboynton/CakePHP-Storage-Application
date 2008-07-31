<h2>Add profile</h2>
<?php echo $form->create('Profile'); ?>
	<?php echo $form->input('name'); ?>
    <?php echo $form->input('database'); ?>
    <?php echo $form->input('server'); ?>
    <?php echo $form->input('port'); ?>
    <?php echo $form->input('username'); ?>
    <?php echo $form->input('password'); ?>
    <?php echo $form->input('prefix'); ?>
<?php echo $form->end('Add profile'); ?>
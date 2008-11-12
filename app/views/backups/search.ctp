<h2>Search</h2>

<?php if($session->check('Message.flash')) $session->flash(); ?>

<div id="column">
	<h5>Search Options</h5>
	<fieldset class="compact">
		<?php echo $form->create(array('action' => 'search', 'type' => 'get', 'class' => 'compact')); ?> 
			<?php echo $form->input('Search', array('name' => 'query', 'value' => $query)); ?>
			<?php echo $form->input('show', array($form->submit('Search'), 'options' => array(10 => 10, 25 => 25, 50 => 50, 75 => 75, 100 => 100), 'selected' => 25, 'after' => ' results')); ?>
			<?php echo $form->input('folder', array('options' => $folders, 'selected' => $folder)); ?>
			<?php echo $form->submit('Search'); ?>
		<?php echo $form->end(); ?>
	</fieldset>
</div>

<div id="main"><?php if(isset($backups)) echo $this->renderElement('backups/paging'); ?></div>
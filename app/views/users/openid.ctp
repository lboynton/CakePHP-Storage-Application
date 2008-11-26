<?php
if (isset($message))
{
    echo '<p class="error">'.$message.'</p>';
}
?>
<fieldset class="fieldset1">
<?php echo $form->create('User', array('type' => 'post', 'action' => 'openid')); ?>
<?php echo $form->input('OpenidUrl.openid', array('label' => 'OpenID identity')); ?>
<?php echo $form->end('Login'); ?>
</fieldset>
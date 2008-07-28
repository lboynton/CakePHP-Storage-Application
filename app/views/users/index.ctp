<?php if ($session->read("Auth.User.real_name") != ""): ?>
<h2>Welcome, <?php echo $session->read("Auth.User.real_name"); ?>!</h2>
<?php else: ?>
<h2>Welcome</h2>
<?php endif; ?>
<p>You've successfully logged in.</p>
<?php //echo date_default_timezone_get(); ?>
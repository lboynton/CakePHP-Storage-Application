<div class="message" id="messageBox">
	<p class="success"><?php echo $content_for_layout ?></p>
    <a href="#" id="closeBox">Close</a>
</div>
<?php echo $javascript->event('closeBox', 'click', "$('messageBox').fade(); return false;"); ?>
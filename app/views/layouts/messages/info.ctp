<div class="message" id="messageBox">
	<p class="info"><?php echo $content_for_layout ?></p>
    <a href="#" id="closeBox" class="JSRequired">Close</a>
</div>
<?php echo $javascript->event('closeBox', 'click', "$('messageBox').fade(); return false;"); ?>
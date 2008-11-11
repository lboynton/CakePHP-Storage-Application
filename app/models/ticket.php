<?php
class Ticket extends AppModel
{
    var $name = 'Ticket';

	var $validate = array
	(
		'data' => 'isUnique' 
	);
}
?>
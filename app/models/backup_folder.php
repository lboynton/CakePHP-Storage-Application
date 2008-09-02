<?php
class BackupFolder extends AppModel 
{
	var $name = 'BackupFolder';
	var $actsAs = array('Tree');
	var $belongsTo = 'User'; 
	var $hasMany = 'Backup';
	var $validate = array 
	(
		'name' => array
		(
			'empty' => array
			(
				'rule' => array('custom', '/\S+/'),
				'message' => 'Please enter a name for the folder',
				'required' => true
			)
		)
	);
}
?>

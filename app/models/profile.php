<?php
class Profile extends AppModel 
{
	var $useDbConfig = 'admin';
	var $validate = array
	(
		'name' => array
		(			
			'rule' => array('minLength', '1'),
			'required' => true,
		),
		'database' => array
		(
			'rule' => array('minLength', '1'),
			'required' => true,
		),
		'server' => array
		(
			'rule' => array('minLength', '1'),
			'required' => true,
		),
		'port' => array
		(
			'rule' => 'numeric',
			'required' => false,
			'allowEmpty' => true,
			'message' => 'Port must be numerical',
		),
		'username' => array
		(
			'rule' => array('minLength', '1'),
			'required' => true,
		),
		'password' => array
		(
			'rule' => array('minLength', '1'),
			'required' => true,
		)
	);
}
?>
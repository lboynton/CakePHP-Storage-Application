<?php
class Admin extends AppModel 
{
	var $useDbConfig = 'admin';
	var $useTable = 'users';
	var $validate = array
	(
	    'username' => array
		(
            'unique' => array
			(
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'This username has already been taken, sorry!'
            ),
            'empty' => array
			(
				'rule' => array('minLength', '1'),
				'required' => true,
				'message' => 'Please enter your username'
            )
        ),
		'password' => array
		(
			'required' => true,
			'message' => 'Please enter your password'
		)
	);
}
?>
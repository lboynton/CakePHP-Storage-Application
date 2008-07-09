<?php 
class User extends AppModel
{
    var $name = 'User';
	var $hasMany = array
	(
        'Backup' => array
		(
            'className'     => 'Backup',
            'foreignKey'    => 'user_id',
        )
    );  
	var $validate = array
	(
		'email' => array
		(			
			'rule' => 'email',
			'required' => true,
			'message' => 'Please enter a valid email address'
		),
        'username' => array
		(
            'unique' => array
			(
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'This username has already been taken'
            ),
            'empty' => array
			(
				'rule' => array('minLength', '1'),
				'message' => 'Please enter your username'
            )
        ),
        'password' => array
		(
            'rule' => array('minLength', '6'),
            'message' => 'Password must be at least 6 characters long',
			'required' => true
        ),
		'confirmPassword' => array
		(
            'rule' => array('minLength', '6'),
            'message' => ' ',
			'required' => true
        )
	);
    
    function validateLogin($data)
    {
        $user = $this->find(array('username' => $data['username'], 'password' => md5($data['password'])), array('id', 'username'));
		
        if(empty($user) == false)
		{
            return $user['User'];
		}
		
        return false;
    }
}
?>
<?php 
class User extends AppModel
{
    var $name = 'User';
    var $actsAs = array('MultipleValidatable');
    var $hasMany = 'Backup';

    // default validation rules
    var $validate = array
    (
        'email' => array
        (
            'valid' => array
            (
                'rule' => 'email',
                'required' => true,
                'message' => 'Please enter a valid email address',
            ),
            'unique' => array
            (
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'This email address has already been used',
            )
        ),
        'username' => array
        (
            'unique' => array
            (
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'This username has already been taken, sorry!',
            ),
            'alphanumeric' => array
            (
                'rule' => 'alphaNumeric',
                'required' => true,
                'message' => 'Username must be alphanumeric',
            ),
            'empty' => array
            (
                'rule' => array('custom', '/\S+/'),
                'required' => true,
                'message' => 'Please enter your username',
            )
        ),
        'new_password' => array
        (
            'rule' => array('custom', '/\S+/'),
            'message' => 'Password must be at least 6 characters long',
            'required' => true,
        ),
        'confirm_password' => array
        (
            'empty' => array
            (
                'rule' => array('minLength', '6'),
                'message' => 'Password must be at least 6 characters long',
                'required' => true,
            ),
            'identical' => array
            (
                'rule' => array('identicalFieldValues', 'new_password'),
                'message' => 'Passwords do not match',
                'required' => true,
            )
        ),
        'password' => array // required when logging in
        (
            'required' => true,
            'message' => 'Please enter your password',
            'rule' => array('custom', '/\S+/'),
            'on' => 'update'
        )
    );

    var $validateLogin = array
    (
        'username' => array
        (
            'rule' => array('custom', '/\S+/'),
            'message' => 'Please enter a valid OpenID',
            'required' => true
        ),
        'access_code' => array
        (
            'rule' => array('inList', array('8UdE9aph')),
            'message' => 'Please enter a valid access code',
            'required' => true
        ),
    );

    // validation set for editing user profile
    var $validateEditDetails = array
    (
        'email' => array
        (
            'valid' => array
            (
                'rule' => 'email',
                'required' => true,
                'message' => 'Please enter a valid email address',
            ),
            'unique' => array
            (
                'rule' => 'isUnique',
                'required' => true,
                'message' => 'This email address has already been used',
            )
        )
    );

    // validation set for the password reset form
    var $validateForgotPassword = array
    (
        'email' => array
        (
            'unique' => array
            (
                'rule' => 'checkEmailExists',
                'required' => true,
                'message' => 'Could not find this email address',
            ),
            'valid' => array
            (
                'rule' => 'email',
                'required' => true,
                'message' => 'Please enter a valid email address',
            )
        )
    );

    // validation set for the password change form
    var $validateResetPassword = array
    (
        'new_password' => array
        (
            'rule' => array('minLength', '6'),
            'message' => 'Password must be at least 6 characters long',
            'required' => true,
        ),
        'confirm_password' => array
        (
            'empty' => array
            (
                'rule' => array('minLength', '6'),
                'message' => 'Password must be at least 6 characters long',
                'required' => true,
            ),
            'identical' => array
            (
                'rule' => array('identicalFieldValues', 'new_password'),
                'message' => 'Passwords do not match',
                'required' => true,
            )
        )
    );

    // validation set for changing the password
    var $validateChangePassword = array
    (
        'old_password' => array
        (
            'rule' => 'matchOldPassword',
            'required' => true,
            'message' => 'Does not match your old password'
        ),
        'new_password' => array
        (
            'rule' => array('minLength', '6'),
            'message' => 'Password must be at least 6 characters long',
            'required' => true
        ),
        'confirm_password' => array
        (
            'empty' => array
            (
                'rule' => array('minLength', '6'),
                'message' => 'Password must be at least 6 characters long',
                'required' => true,
            ),
            'identical' => array
            (
                'rule' => array('identicalFieldValues', 'new_password'),
                'message' => 'Passwords do not match',
                'required' => true,
            )
        ),
    );

    // validation rules for admin edit user
    var $validateAdminUserView = array
    (
        'quota' => array
        (
            'rule' => 'numeric',
            'message' => 'Quota must be a numerical value',
            'allowEmpty' => false,
            'required' => true
        ),
        'unit' => array
        (
            'rule' => array('inList', array('b', 'kb', 'mb', 'gb')),
            'message' => 'Unsupported unit specified',
            'required' => true
        )
    );

    // validation rules for admin user level change
    var $validateAdminUserLevel = array
    (
        'admin' => array
        (
            'rule' => array('inList', array('0', '1')),
            'message' => 'Incorrect value supplied',
            'required' => true
        )
    );

    // validation set for checkboxes in the admin section
    var $validateAdminCheckboxes = array
    (
        'ids' => array
        (
            'rule' => array('inList', array('0', '1')),
            'message' => 'Incorrect value supplied',
            'required' => true
        )
    );

    /**
     * Overridden to support OpenID
     * @param <type> $conditions
     * @param <type> $fields
     * @param <type> $order
     * @param <type> $recursive
     * @return <type>
     */
    public function find($conditions = null, $fields = array(), $order = null, $recursive = null)
    {
        if (is_array($conditions) && isset($conditions['`User`.`id`']))
        {
            $response = $conditions['`User`.`id`'];

            if ($response->status == Auth_OpenID_SUCCESS)
            {
                $sregResponse = Auth_OpenID_SRegResponse::fromSuccessResponse($response);
                $sreg = $sregResponse->contents();

                $user['User']['openid'] = $response->identity_url;

                if (@$sreg['email']) 
                {
                    $user['User']['email'] = $sreg['email'];
                }
                if (@$sreg['fullname'])
                {
                    $user['User']['real_name'] = $sreg['fullname'];
                }

                return $user;
            }

            return array();
        }

        if (is_array($conditions) && isset($conditions['User.username']) && isset($conditions['User.password']))
        {
            return array();
        }

        return parent::find($conditions, $fields, $order, $recursive);
    }

    public function getUser($findUser)
    {
        // see if user has been registered
        $this->recursive = -1;

        if($user = $this->findByUsername($findUser['User']['openid']))
        {
            //die(var_dump($user));
            return $user;
        }
        else
        {
            $siteParameters = new SiteParameter();

            $this->create();

            // create user with defaults
            $user = array
            (
                'User' => array
                (
                    'username' => $findUser['User']['openid'],
                    'real_name' => $findUser['User']['real_name'],
                    'email' => $findUser['User']['email'],
                    'quota' => $siteParameters->getParam('default_quota')
                )
            );

            // try to store the data
            if($this->save($user, false, array('real_name', 'email', 'username', 'quota')))
            {
                $user['User']['id'] = $this->getLastInsertID();

                return $user;
            }
            else
            {
                die("ugh");
            }
        }
    }

    public function openIdIsRegistered()
    {
        return (bool) $this->findByOpenId($response->identity_url);
    }

    /**
     * Called after validation, before data is stored in the database
     */
    public function beforeSave()
    {
        // hash the password before storing a new user in the database
        if(isset($this->data['User']['new_password']))
        {
            $this->data['User']['password'] = AuthComponent::password($this->data['User']['new_password']);
        }

        // return true, otherwise save will return false
        return true;
    }

    /**
     * Used for validating two form fields are identical
     * @return True if both are identical, false otherwise
     */
    public function identicalFieldValues($field=array(), $compare_field=null)
    {
        return($this->data[$this->name][$compare_field] === array_shift($field));
    }

    /**
     * Checks if the user entered their old password correctly
     */
    public function matchOldPassword($data)
    {
        $user = $this->find('first', array('conditions' => array('id' => $this->id), 'recursive' => -1));

        return AuthComponent::password($this->data['User']['old_password']) == $user['User']['password'];
    }

    /**
     * Method to check if the logged in user's account has been disabled
     * @return True if disabled, false if not
     */
    public function isAccountDisabled($id)
    {
        $this->recursive = -1;

        $user = $this->findById($id, array('fields' => 'disabled'));

        return (boolean) $user['User']['disabled'];
    }

    /**
     * Checks if the given email address is present in the users table
     * @param data The email address
     * @return True if it exists, false otherwise
     */
    public function checkEmailExists($data)
    {
        return !$this->isUnique(array('email' => $data));
    }

    private function isValidOpenId($openId)
    {
        
    }
}
?>

<?php

App::uses('AppModel', 'Model');
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
App::uses('AuthComponent', 'Controller/Component');
App::uses('ComponentCollection', 'Controller');

class User extends AppModel {
    public $validate = array(
        'name' => array(
            'notBlank' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter your name.',
            ),
            'length' => array(
                'rule' => array('lengthBetween', 5, 20),
                'message' => 'Your name must be between 5 and 20 characters long.',
            )
        ),
        'email' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter your email.',
            ),
            'valid' => array(
                'rule' => 'email',
                'message' => 'Please provide a valid email address.'
            ),
            'unique' => array(
                'rule' => 'isUnique',
                'message' => 'This email address is already in use.'
            )
        ),
        'old_password' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Please enter your old password'
            ),
            'valid' => array(
                'rule' => array('verifyPassword'),
                'message' => 'Old password is incorrect, please try again',
            )
        ),
        'password' => array(
            'rule' => 'notBlank',
            'message' => 'Please enter your password',
        ),
        'password_confirmation' => array(
            'rule' => array('confirmed'),
            'message' => 'Password does not match',
        ),
        'birthdate' => array(
            'rule' => 'date',
            'message' => 'Please select a date',
            'on' => 'update'
        ),
        'gender' => array(
            'rule' => 'notBlank',
            'message' => 'Please select a gender',
            'on' => 'update'
        ),
        'hubby' => array(
            'rule' => 'notBlank',
            'message' => 'Please enter your hubbies',
            'on' => 'update'
        ),
        'image' => array(
            'rule' => array('extension', array('jpg', 'png', 'gif')),
            'message' => 'Please upload an image with an extension of (jpg, png, gif)'
        )
    );

    public function confirmed($check) {
        return $check['password_confirmation'] === $this->data[$this->alias]['password'];
    }

    public function verifyPassword($check) {
        $passwordHasher = new BlowfishPasswordHasher();
        $collection = new ComponentCollection();
        $auth = new AuthComponent($collection);
        $user = $this->find('first', array(
            'conditions' => array('User.id' => $auth->user('id')),
            'fields' => array('User.password')
        ));
        if (!$user) { return false; }
        return $passwordHasher->check($check['old_password'], $user['User']['password']);
    }

    public function beforeSave($options = array()) {
        if (isset($this->data[$this->alias]['password'])) {
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data[$this->alias]['password'] = $passwordHasher->hash(
                $this->data[$this->alias]['password']
            );
        }
    }
}
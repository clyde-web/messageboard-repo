<?php

App::uses('AppModel', 'Model');

class Room extends AppModel {
    public $validate = array(
        'receiver_id' => array(
            'notEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Please select a user',
                'allowEmpty' => false,
                'required' => true
            ),
            'valid' => array(
                'rule' => 'numeric',
                'message' => 'Invalid type of user'
            )
        ),
        'message' => array(
            'rule' => 'notBlank',
            'message' => 'Please enter your message to send'
        )
    );
}
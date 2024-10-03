<?php

App::uses('AppModel', 'Model');

class Message extends AppModel {
    public $validate = array(
        'message' => array(
            'rule' => 'notBlank',
            'message' => 'Please enter your message'
        ),
    );
}
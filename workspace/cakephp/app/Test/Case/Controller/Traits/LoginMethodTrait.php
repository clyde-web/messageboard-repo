<?php

trait LoginMethodTrait {

    public function testLoginNotPost() {
        $request = new \mock\CakeRequest();
        $controller = new \mock\NativeCamp\UsersController();
        $controller->request = $request;
        $request->getMockController()->is = function($method) {
            return false;
        };
        $controller->login();
        $this->assert('the request method is get')->boolean($controller->request->is('post'))->isFalse();
    }

    public function testLoginFailed() {
        $user = new \mock\User();
        $auth = new \mock\AuthComponent();
        $request = new \mock\CakeRequest();
        $response = new \mock\CakeResponse();
        $flash = new \mock\FlashComponent();
        $controller = new \mock\NativeCamp\UsersController();

        $controller->User = $user;
        $controller->Auth = $auth;
        $controller->request = $request;
        $controller->response = $response;
        $controller->Flash = $flash;

        $controller->request->data = array(
            'User' => array(
                'email' => 'fdc.clyde@gmail.com',
                'password' => 'password'
            )
        );

        $response->getMockController()->statusCode = function($code) {
            return $code;
        };
        $request->getMockController()->is = function($method) {
            return strtolower($method) === 'post';
        };
        $auth->getMockController()->login = function() {
            return false;
        };
        $flash->getMockController()->error = function($msg) {
            return $msg;
        };

        $controller->login();

        $this->assert('this login failed')->boolean($auth->login())->isFalse();
    }

    public function testLoginSuccess() {
        $user = new \mock\User();
        $auth = new \mock\AuthComponent();
        $request = new \mock\CakeRequest();
        $response = new \mock\CakeResponse();
        $controller = new \mock\NativeCamp\UsersController();

        $controller->User = $user;
        $controller->Auth = $auth;
        $controller->request = $request;
        $controller->response = $response;

        $controller->request->data = array(
            'User' => array(
                'email' => 'fdc.clyde@gmail.com',
                'password' => 'password'
            )
        );

        $response->getMockController()->statusCode = function($code) {
            return $code;
        };
        $request->getMockController()->clientIp = function() {
            return '127.0.0.1';
        };
        $request->getMockController()->is = function($method) {
            return strtolower($method) === 'post';
        };
        $auth->getMockController()->login = function() {
            return true;
        };
        $auth->getMockController()->user = function($key) {
            return 1;
        };
        $user->getMockController()->saveField = function($field, $value) {
            return true;
        };
        $auth->getMockController()->redirectUrl = function() {
            return '/users/logout';
        };
        $controller->getMockController()->redirect = function($url) {
            return $url;
        };
       
        $controller->login();

        $this->assert('this login will succeed')
            ->boolean($auth->login())->isTrue()
            ->integer($user->id)->isEqualTo(1);
    }

    public function testLoginSuccessRedirect() {
        $user = new \mock\User();
        $auth = new \mock\AuthComponent();
        $request = new \mock\CakeRequest();
        $response = new \mock\CakeResponse();
        $controller = new \mock\NativeCamp\UsersController();

        $controller->User = $user;
        $controller->Auth = $auth;
        $controller->request = $request;
        $controller->response = $response;

        $controller->request->data = array(
            'User' => array(
                'email' => 'fdc.clyde@gmail.com',
                'password' => 'password'
            )
        );

        $response->getMockController()->statusCode = function($code) {
            return $code;
        };
        $request->getMockController()->is = function($method) {
            return strtolower($method) === 'post';
        };
        $request->getMockController()->clientIp = function() {
            return '127.0.0.1';
        };
        $auth->getMockController()->login = function() {
            return true;
        };
        $auth->getMockController()->user = function($key) {
            return 1;
        };
        $user->getMockController()->saveField = function($field, $value) {
            return true;
        };
        $auth->getMockController()->redirectUrl = function() {
            return '/messages/index';
        };
        $controller->getMockController()->redirect = function($url) {
            return $url;
        };
       
        $controller->login();

        $this->assert('this login will succeed')
            ->boolean($auth->login())->isTrue()
            ->integer($user->id)->isEqualTo(1);
    }

}
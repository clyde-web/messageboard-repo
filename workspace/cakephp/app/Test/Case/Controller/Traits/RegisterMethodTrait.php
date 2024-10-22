<?php

trait RegisterMethodTrait {
    public function testRegisterGetRequest() {
        $controller = new \mock\NativeCamp\UsersController();
        $request = new \mock\CakeRequest();

        $controller->request = $request;

        $request->getMockController()->is = function($method) {
            return false;
        };

        $controller->register();

        $this->assert('It should be a GET request')
        ->boolean($controller->request->is('post'))->isFalse();
        $this->assert('Title should be set for GET request')
        ->string($controller->viewVars['title_for_layout'])->isEqualTo('Registration | MB');
    }

    public function testRegisterValidationFailed() {
        $controller = new \mock\NativeCamp\UsersController();
        $request = new \mock\CakeRequest();
        $user = new \mock\User();
        $response = new \mock\CakeResponse();
        $auth = new \mock\AuthComponent();
        $session = new \mock\SessionComponent();

        $controller->request = $request;
        $controller->User = $user;
        $controller->response = $response;
        $controller->Auth = $auth;
        $controller->Session = $session;

        $controller->request->data = array(
            'User' => array(
                'name' => 'Clyde C. Arellano',
                'email' => 'fdc.clyde@gmail.com',
                'password' => 'pass',
                'password_confirmation' => 'pass'
            )
        );
        $request->getMockController()->is = function($method) {
            return true;
        };
        $user->getMockController()->validates = function() {
            return false;
        };
        $user->validationErrors = array(
            'email' => 'The email is already taken'
        );
        $controller->register();

        $this->assert('It should be a POST request')
        ->boolean($controller->request->is('post'))->isTrue();
        $this->assert('Validation should fail and return errors')
        ->boolean($controller->User->validates())->isFalse();
    }

    public function testRegistrationSuccess() {
        $controller = new \mock\NativeCamp\UsersController();
        $request = new \mock\CakeRequest();
        $user = new \mock\User();
        $response = new \mock\CakeResponse();
        $auth = new \mock\AuthComponent();
        $session = new \mock\SessionComponent();

        $controller->request = $request;
        $controller->User = $user;
        $controller->response = $response;
        $controller->Auth = $auth;
        $controller->Session = $session;

        $controller->request->data = array(
            'User' => array(
                'name' => 'Clyde C. Arellano',
                'email' => 'fdc.clyde@gmail.com',
                'password' => 'pass',
                'password_confirmation' => 'pass'
            )
        );
        $request->getMockController()->is = function($method) {
            return true;
        };
        $user->getMockController()->validates = function() {
            return true;
        };
        $request->getMockController()->clientIp = function() {
            return '127.0.0.1';
        };
        $user->getMockController()->save = function() {
            return true;
        };
        $user->getMockController()->findById = function($id) {
            return array('User' => array('id' => 1, 'email' => 'test@example.com'));
        };
        $auth->getMockController()->login = function($user) {
            return true;
        };
        $auth->getMockController()->user = function($key) {
            return 1;
        };

        $controller->register();

        $this->assert('It should be a POST request')
            ->boolean($controller->request->is('post'))->isTrue()
            ->mock($request)->call('is')->withArguments('post')->once();
            $this->assert('User should be validated and saved')
            ->boolean($controller->User->validates())->isTrue()
            ->mock($user)->call('validates')->once()
            ->boolean($controller->User->save($controller->request->data))->isTrue();

    }
}
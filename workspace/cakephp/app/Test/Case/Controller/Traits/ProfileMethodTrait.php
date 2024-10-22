<?php

trait ProfileMethodTrait {
    public function testProfileWithParameters() {
        $controller = new \mock\NativeCamp\UsersController();
        $user = new \mock\User();
        $auth = new \mock\AuthComponent();

        $controller->User = $user;
        $controller->Auth = $auth;

        $auth->getMockController()->user = function($key) {
            return 1;
        };
        $user->getMockController()->find = function($key, $options) {
            return array(
                'User' => array(
                    'id' => 2,
                    'name' => 'Clyde C. Arellano',
                    'email' => 'fdc.clyde@gmail.com',
                    'gender' => 'male',
                    'birthdate' => '09/16/1999',
                    'hubby' => null,
                    'created_at' => 'today',
                    'last_login_time' => 'today'
                )
            );
        };

        $controller->profile(2);

        $this->assert('not authenticated user')
        ->boolean($controller->viewVars['canUpdate'])->isFalse();
    }

    public function testProfileWithoutParameters() {
        $controller = new \mock\NativeCamp\UsersController();
        $user = new \mock\User();
        $auth = new \mock\AuthComponent();

        $controller->User = $user;
        $controller->Auth = $auth;

        $auth->getMockController()->user = function($key) {
            return 1;
        };
        $user->getMockController()->find = function($key, $options) {
            return array(
                'User' => array(
                    'id' => 1,
                    'name' => 'Clyde C. Arellano',
                    'email' => 'fdc.clyde@gmail.com',
                    'gender' => 'male',
                    'birthdate' => '09/16/1999',
                    'hubby' => null,
                    'created_at' => 'today',
                    'last_login_time' => 'today'
                )
            );
        };

        $controller->profile();

        $this->assert('authenticated user')
        ->boolean($controller->viewVars['canUpdate'])->isTrue();
    }

    public function testProfileWithInvalidUser() {
        $controller = new \mock\NativeCamp\UsersController();
        $user = new \mock\User();
        $auth = new \mock\AuthComponent();

        $controller->User = $user;
        $controller->Auth = $auth;

        $auth->getMockController()->user = function($key) {
            return 1;
        };
        $user->getMockController()->find = function($key, $options) {
            return null;
        };

        $this->assert('invalid user')
        ->exception(function() use ($controller) {
            $controller->profile(1000);
        })->isInstanceOf('\NotFoundException');
    }
}
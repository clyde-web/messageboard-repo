<?php

namespace NativeCamp\Test;

require __DIR__ . '/../../../../app/webroot/atoum.php';
require __DIR__ . '/../../../../app/Controller/UsersController.php';
require_once __DIR__ . '/Traits/LoginMethodTrait.php';
require_once __DIR__ . '/Traits/WelcomeMethodTrait.php';
require_once __DIR__ . '/Traits/RegisterMethodTrait.php';
require_once __DIR__ . '/Traits/ProfileMethodTrait.php';

use atoum\atoum;

/**
 * @namespace \Test 
 */ 
class UsersController extends atoum\test {
    use \LoginMethodTrait, \WelcomeMethodTrait, \RegisterMethodTrait;
    use \ProfileMethodTrait;

    public function testLogoutSuccess() {
        $controller = new \mock\NativeCamp\UsersController();
        $auth = new \mock\AuthComponent();
        
        $controller->Auth = $auth;

        $controller->getMockController()->redirect = function($url) {
            return $url;
        };
        $auth->getMockController()->logout = function() {
            return '/users/login';
        };

        $controller->logout();

        $this->assert('This should be logout')
        ->string($controller->redirect($auth->logout()))
        ->isEqualTo('/users/login');
    }
}
<?php

trait WelcomeMethodTrait {
    public function testHasWelcomeSession() {
        $controller = new \mock\NativeCamp\UsersController();
        $session = new \mock\SessionComponent();

        $controller->Session = $session;

        $session->getMockController()->read = function($key) {
            return strtolower($key) === 'welcome';
        };
        $session->getMockController()->delete = function($key) {
            return true;
        };

        $controller->welcome();

        $this->assert('the session Welcome is available')
        ->boolean($session->read('Welcome'))->isTrue()
        ->mock($session)->call('read')->withArguments('Welcome')->once()
        ->boolean($session->delete('Welcome'))->isTrue()
        ->mock($session)->call('delete')->withArguments('Welcome')->once()
        ->string($controller->viewVars['title_for_layout'])
        ->isEqualTo('Welcome to Message Board');
    }

    public function testDoesNotHaveWelcomeSession() {
        $controller = new \mock\NativeCamp\UsersController();
        $response = new \mock\CakeResponse();
        $session = new \mock\SessionComponent();

        $controller->Session = $session;
        $controller->response = $response;

        $session->getMockController()->read = function($key) {
            return false;
        };
        $controller->getMockController()->redirect = function($url) {
            return $url;
        };

        $controller->welcome();

        $this->assert('the session Welcome is not available')
        ->boolean($session->read('Welcome'))->isFalse()
        ->array($controller->redirect(array('controller' => 'messages', 'action' => 'index')))
        ->isEqualTo(array('controller' => 'messages', 'action' => 'index'));
    }
}
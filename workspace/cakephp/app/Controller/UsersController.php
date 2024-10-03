<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $components = array('Image');

    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->User->id = $this->Auth->user('id');
                $this->User->saveField('last_login_time', date('Y-m-d H:i:s'));
                $this->User->saveField('modified_ip', $this->request->clientIp());
                $redirect = $this->Auth->redirectUrl() === '/users/logout' ? $this->redirect(array('controller' => 'messages', 'action' => 'index')) : $this->Auth->redirectUrl();
                return $this->redirect($redirect);
            }
            $this->Flash->error(__('Invalid username or password, please try again'));
        }
        $this->set('title_for_layout', 'Login | MB');
    }

    public function welcome() {
        if (!$this->Session->read('Welcome')) {
            return $this->redirect(array('controller' => 'messages', 'action' => 'index'));
        }
        $this->Session->delete('Welcome');
        $this->set('title_for_layout', 'Welcome to Message Board');
    }

    public function register() {
        if ($this->request->is('ajax')) {
            $this->User->create();
            $this->User->set($this->request->data);
            if ($this->User->validates()) {
                $this->request->data['User']['created_ip'] = $this->request->clientIp();
                if ($this->User->save($this->request->data)) {
                    $user = $this->User->findById($this->User->getLastInsertId());
                    if ($this->Auth->login($user['User'])) {
                        $this->User->id = $this->Auth->user('id');
                        $this->User->saveField('last_login_time', date('Y-m-d H:i:s'));
                        $this->User->saveField('modified_ip', $this->request->clientIp());
                        $this->Session->write('Welcome', true);
                        $response = array('status' => 200, 'action' => Router::url(array('action' => 'welcome')));
                    }
                }
            } else {
                $response = array('status' => 404, 'errors' => $this->User->validationErrors);
            }
            $this->autoRender = false;
            $this->response->type('json');
            $this->response->body(json_encode($response));
            return $this->response;
        }
        $this->set('title_for_layout', 'Registration | MB');
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function profile($id = null) {
        if (!$id) {
            $userId = $this->Auth->user('id');
        } else {
            $userId = $id;
        }
        $user = $this->User->find('first', array(
            'conditions' => array('User.id' => $userId),
            'fields' => array(
                'User.id', 
                'User.name',
                'User.email',
                'User.gender',
                'User.birthdate',
                'User.hubby',
                'User.created_at',
                'User.last_login_time'
            )
        ));
        if (!$user) {
            throw new NotFoundException(__('Invalid User.'));
        }
        $this->set('canUpdate', ($this->Auth->user('id') === $userId));
        $this->set('user', $user);
        $this->set('title_for_layout', $user['User']['name'] . ' Profile | MB');
    }

    public function edit() {
        if ($this->request->is(array('post', 'put'))) {
           $this->User->id = $this->Auth->user('id');
           if (!empty($this->request->data['User']['image']['tmp_name'])) {
                $file = $this->request->data['User']['image'];
                $fileExtensions = pathinfo($file['name'], PATHINFO_EXTENSION);
                if (in_array(strtolower($fileExtensions), array('jpg', 'png', 'gif'))) {
                    $root = WWW_ROOT . 'img/profiles/';
                    if (!file_exists($root)) {
                        mkdir($root, 0777, true);
                    }
                    $destination = WWW_ROOT . 'img/profiles/' . $this->User->id . '.png';
                    if (file_exists($destination)) {
                        unlink($destination);
                    }
                    move_uploaded_file($file['tmp_name'], $destination);
                } else {
                    $this->User->invalidate('image', 'Please upload an image with an extension of (jpg, png, gif)');
                }
           }
           if ($this->User->validates()) {
                $this->User->saveField('modified_ip', $this->request->clientIp());
                if ($this->User->save($this->request->data)) {
                    $this->Flash->success('Profile has been successfully updated');
                    return $this->redirect(array('action' => 'profile'));
                }
           }
        }
        $user = $this->User->find('first', array(
            'conditions' => array('User.id' => $this->Auth->user('id')),
            'fields' => array(
                'User.id', 
                'User.name',
                'User.email', 
                'User.birthdate', 
                'User.gender',
                'User.hubby'
            )
        ));
        unset($this->request->data);
        $this->set('user', $user);
        $this->set('title_for_layout', 'Update Account | MB');
    }

    public function password() {
        if ($this->request->is(array('post', 'put'))) {
            $this->User->id = $this->Auth->user('id');
            if ($this->User->validates()) {
                $this->User->saveField('modified_ip', $this->request->clientIp());
                if ($this->User->save($this->request->data)) {
                    $this->Flash->success('Password has been successfully changed');
                    return $this->redirect(array('action' => 'profile'));
                }
            }
        }
        $this->set('title_for_layout', 'Change Password | MB');
    }

    public function search() {
        $this->autoRender = false;
        $query = $this->request->query('search');
        if ($query) {
            $users = $this->User->find('all', array(
                'conditions' => array(
                    'User.name LIKE' => "%$query%",
                    'User.id !=' => $this->Auth->user('id')
                ),
                'fields' => array(
                    'User.id',
                    'User.name'
                ),
                'limit' => 10
            ));
        }
        $filteredUsers = array();
        if (!empty($users)) {
            foreach($users as $user) {
                $filteredUsers[] = array(
                    'id' => $user['User']['id'],
                    'name' => $user['User']['name'],
                    'image' => $this->Image->getProfile($user['User']['id'])
                );
            }
        }
        echo json_encode(array('data' => $filteredUsers));
    }
}
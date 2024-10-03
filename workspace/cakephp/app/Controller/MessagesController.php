<?php
App::uses('AppController', 'Controller');

class MessagesController extends AppController {
    public $uses = array(
        'Room',
        'Message'
    );

    public $components = array('Image');

    public function index() {
        if ($this->request->is('ajax')) {
            $limit = 10;
            $page = ($this->request->query('page')) ? (int)$this->request->query('page') : 1 ;
            $offset = ($page - 1) * $limit;

            $totalRooms = $this->Room->find('count', array(
                'conditions' => array(
                    'OR' => array(
                        'Room.sender_id' => $this->Auth->user('id'),
                        'Room.receiver_id' => $this->Auth->user('id')
                    )
                )
            ));

            $hasMore = ($totalRooms > ($offset + $limit));

            $rooms = $this->Room->find('all', array(
                'conditions' => array(
                    'OR' => array(
                        'Room.sender_id' => $this->Auth->user('id'),
                        'Room.receiver_id' => $this->Auth->user('id')
                    )
                ),
                'joins' => array(
                    array(
                        'table' => 'messages',
                        'alias' => 'Message',
                        'type' => 'LEFT',
                        'conditions' => array(
                            'Message.room_id = Room.id',
                            'Message.created_at = (SELECT MAX(m.created_at) FROM messages m WHERE m.room_id = Room.id)'
                        )
                    )
                ),
                'fields' => array(
                    'Room.id',
                    'Room.sender_id',
                    'Room.receiver_id',
                    'Room.created_at',
                    'Message.message',
                    'Message.created_at'
                ),
                'order' => array(
                    'Room.created_at DESC'
                ),
                'limit' => $limit,
                'offset' => $offset
            ));

            $roomsAjax = array();
            foreach($rooms as $room) {
                if ($room['Room']['sender_id'] === $this->Auth->user('id')) {
                    $other = $room['Room']['receiver_id'];
                } else {
                    $other = $room['Room']['sender_id'];
                }
                $roomsAjax[] = array(
                    'id' => $room['Room']['id'],
                    'profile' => Router::url(array('controller' => 'users', 'action' => 'profile', $other)),
                    'action' => Router::url(array('controller' => 'messages', 'action' => 'view', 'id' => $room['Room']['id'])),
                    'message' => $room['Message']['message'],
                    'created_at' => date('F j, Y h:i', strtotime($room['Room']['created_at'])),
                    'image' => $this->Image->getProfile($other),
                    'canDelete' => ($room['Room']['sender_id'] === $this->Auth->user('id'))
                );
            }
            $response = array(
                'hasMore' => $hasMore,
                'rooms' => $roomsAjax
            );
            $this->autoRender = false;
            $this->response->type('json');
            $this->response->body(json_encode($response));
            return $response;
        }

        $this->set('title_for_layout', 'Message Lists | MB');
    }

    public function create() {
        if ($this->request->is('post')) {
            $this->Room->create();
            $this->Room->set($this->request->data);
            if ($this->Room->validates()) {
                $message = $this->request->data['Room']['message'];
                unset($this->request->data['Room']['message']);
                $checkRoom = $this->Room->find('first', array(
                    'conditions' => array(
                        'OR' => array(
                            array('Room.sender_id' => $this->Auth->user('id'), 'Room.receiver_id' => $this->request->data['Room']['receiver_id']),
                            array('Room.sender_id' => $this->request->data['Room']['receiver_id'], 'Room.receiver_id' => $this->Auth->user('id')),
                        )
                    ),
                    'fields' => array('Room.id')    
                ));
                if ($checkRoom) {
                    $roomId = $checkRoom['Room']['id'];
                    $this->Message->create();
                    $form = array(
                        'Message' => array(
                            'room_id' => $roomId,
                            'sender_id' => $this->Auth->user('id'),
                            'message' => $message,
                            'created_ip' => $this->request->clientIp()
                        )
                    );
                    if ($this->Message->save($form)) {
                        $this->Flash->success(__('Message Sent'));
                        return $this->redirect(array('action' => 'index'));
                    }
                }
                $this->request->data['Room']['sender_id'] = $this->Auth->user('id');
                $this->request->data['Room']['created_ip'] = $this->request->clientIp();
                if ($this->Room->save($this->request->data)) {
                    $roomId = $this->Room->id;
                    $this->Message->create();
                    $form = array(
                        'Message' => array(
                            'room_id' => $roomId,
                            'sender_id' => $this->Auth->user('id'),
                            'message' => $message,
                            'created_ip' => $this->request->clientIp()
                        )
                    );
                    if ($this->Message->save($form)) {
                        $this->Flash->success(__('Message Sent'));
                        return $this->redirect(array('action' => 'index'));
                    }
                }
            }
        }
        $this->set('title_for_layout', 'New Message | MB');
    }

    public function view($id = null) {
        $limit = 10;
        $page = ($this->request->is('ajax') && $this->request->query('page')) ? (int) $this->request->query('page') : 1 ;
        $offset = ($page - 1) * $limit;
        $room = $this->Room->find('first', array(
            'joins' => array(
                array(
                    'table' => 'users',
                    'alias' => 'Sender',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Room.sender_id = Sender.id'
                    )
                    ),
                array(
                    'table' => 'users',
                    'alias' => 'Receiver',
                    'type' => 'INNER',
                    'conditions' => array(
                        'Room.receiver_id = Receiver.id'
                    )
                )
            ),
            'conditions' => array(
                'OR' => array(
                    'Room.sender_id' => $this->Auth->user('id'),
                    'Room.receiver_id' => $this->Auth->user('id')
                ),
                'AND' => array(
                    'Room.id' => $id
                )
            ),
            'fields' => array(
                'Room.id',
                'Room.sender_id',
                'Room.receiver_id',
                'Sender.name',
                'Receiver.name',
            )
        ));
        if (!$room) {
            return $this->redirect(array('action' => 'index'));
        }

        $totalMessages = $this->Message->find('count', array(
            'conditions' => array(
                'Message.room_id' => $room['Room']['id']
            )
        ));
        $hasMore = ($totalMessages > ($offset + $limit));
        $messages = $this->Message->find('all', array(
            'conditions' => array(
                'Message.room_id' => $room['Room']['id']
            ),
            'order' => array(
                'Message.created_at DESC'
            ),
            'limit' => $limit,
            'offset' => $offset
        ));
        
        if ($this->request->is('ajax')) {
            $this->autoRender = false;
            $messagesAjax = array();
            foreach($messages as $message) {
                $messagesAjax[] = array(
                    'id' => $message['Message']['id'],
                    'profile' => Router::url(array('controller' => 'users', 'action' => 'profile', $message['Message']['sender_id'])),
                    'image' => $this->Image->getProfile($message['Message']['sender_id']),
                    'message' => $message['Message']['message'],
                    'seeMore' => (strlen($message['Message']['message']) > 242),
                    'canDelete' => ($message['Message']['sender_id'] === $this->Auth->user('id')),
                    'class' => ($message['Message']['sender_id'] === $this->Auth->user('id')) ? 'flex-row-reverse': null,
                    'created_at' => date('F j, Y h:i', strtotime($message['Message']['created_at']))
                );
            }
            $response = array(
                'hasMore' => $hasMore,
                'messages' => $messagesAjax
            );
            $this->response->type('json');
            $this->response->body(json_encode($response));
            return $response;
        }
    
        $this->set('room', $room);
        $this->set('title_for_layout', 'Message Details | MB');
    }

    public function delete() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $id = (int)$this->request->data['id'];
            $owner = $this->Room->find('first', array(
                'conditions' => array(
                    'Room.sender_id' => $this->Auth->user('id'),
                    'Room.id' => $id
                )
            ));

            if ($owner) {
                if ($this->Room->delete($id)) {
                    $response = array('status' => 204, 'message' => 'Message has been deleted');
                }
            } else {
                $response = array('status' => 404, 'message' => 'Oopss, Something went wrong!');
            }
            
            $this->response->type('json');
            $this->response->body(json_encode($response));
            return $response;
        }
    }

    public function sendMessage() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $this->Message->create();
            $this->Message->set($this->request->data);
            if($this->Message->validates()) {
                $this->request->data['Message']['sender_id'] = $this->Auth->user('id');
                $this->request->data['Message']['created_ip'] = $this->request->clientIp();
                if ($this->Message->save($this->request->data)) {
                    $message = $this->Message->findById($this->Message->id);
                    $data = array(
                        'id' => $message['Message']['id'],
                        'profile' => Router::url(array('controller' => 'users', 'action' => 'profile', $this->Auth->user('id'))),
                        'image' => $this->Image->getProfile($this->Auth->user('id')),
                        'message' => $message['Message']['message'],
                        'seeMore' => (strlen($message['Message']['message']) > 242),
                        'created_at' => date('F j, Y h:i', strtotime($message['Message']['created_at']))
                    );
                    $response = array('status' => 200, 'data' => $data, 'message' => 'Message Sent');
                }
            } else {
                $response = array('status' => 404, 'errors' => $this->Message->validationErrors);
            }
            $this->response->type('json');
            $this->response->body(json_encode($response));
            return $this->response;
        }
    }

    public function deleteMessage() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $id = (int)$this->request->data['id'];
            $owner = $this->Message->find('first', array(
                'conditions' => array(
                    'Message.sender_id' => $this->Auth->user('id'),
                    'Message.id' => $id
                )
            ));
            if ($owner) {
                if ($this->Message->delete($id)) {
                    $response = array('status' => 204, 'message' => 'Message has been deleted');
                }
            } else {
                $response = array('status' => 404, 'message' => 'Oopss, Something went wrong!');
            }
            
            $this->response->type('json');
            $this->response->body(json_encode($response));
            return $response;
        }
    }

    public function searchMessage() {
        $this->autoRender = false;
        if ($this->request->is('ajax')) {
            $search = ($this->request->query('search')) ? $this->request->query('search') : null;

            if (!empty($search)) {
                $rooms = $this->Room->find('all', array(
                    'conditions' => array(
                        'OR' => array(
                            'Room.sender_id' => $this->Auth->user('id'),
                            'Room.receiver_id' => $this->Auth->user('id'),
                        ),
                        'AND' => array(
                            'OR' => array(
                                'Sender.name LIKE' => '%' . $search . '%',
                                'Receiver.name LIKE' => '%' . $search . '%', 
                                'Message.message LIKE' => '%' . $search . '%',
                            ),
                        )
                    ),
                    'fields' => array(
                        'DISTINCT Room.id',
                        'Room.sender_id',
                        'Room.receiver_id',
                        'Room.created_at',
                        'Sender.name',
                        'Receiver.name',
                        'Message.message',
                    ),
                    'joins' => array(
                        array(
                            'table' => 'users',
                            'alias' => 'Sender',
                            'type' => 'INNER',
                            'conditions' => array('Room.sender_id = Sender.id'),
                        ),
                        array(
                            'table' => 'users',
                            'alias' => 'Receiver',
                            'type' => 'INNER',
                            'conditions' => array('Room.receiver_id = Receiver.id'),
                        ),
                        array(
                            'table' => 'messages',
                            'alias' => 'Message',
                            'type' => 'LEFT',
                            'conditions' => array('Room.id = Message.room_id'),
                        ),
                    ),
                    'group' => array(
                        'Room.id',
                        'Room.sender_id',
                        'Room.receiver_id',
                        'Room.created_at',
                        'Sender.name',
                        'Receiver.name',
                        'Message.message'
                    ),
                    'order' => array(
                        'Room.created_at DESC'
                    )
                ));
            }

            $roomsAjax = array();
            foreach($rooms as $room) {
                if ($room['Room']['sender_id'] === $this->Auth->user('id')) {
                    $other = $room['Room']['receiver_id'];
                } else {
                    $other = $room['Room']['sender_id'];
                }
                $roomsAjax[] = array(
                    'id' => $room['Room']['id'],
                    'profile' => Router::url(array('controller' => 'users', 'action' => 'profile', $other)),
                    'action' => Router::url(array('controller' => 'messages', 'action' => 'view', 'id' => $room['Room']['id'])),
                    'message' => $room['Message']['message'],
                    'created_at' => date('F j, Y h:i', strtotime($room['Room']['created_at'])),
                    'image' => $this->Image->getProfile($other),
                    'canDelete' => ($room['Room']['sender_id'] === $this->Auth->user('id'))
                );
            }
            $response = array(
                'rooms' => $roomsAjax
            );
            $this->response->type('json');
            $this->response->body(json_encode($response));
            return $response;
        }

    }
}
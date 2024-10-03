<?php

App::uses('Component', 'Controller');

class ImageComponent extends Component {
    public function getProfile($id) {
        $path = WWW_ROOT . 'img/profiles/' . $id . '.png';
        $file = '/cakephp/img/profiles/' . $id . '.png';
        if (!file_exists($path)) {
            $file = '/cakephp/img/default.jpg';
        }
        return $file;
    }
}
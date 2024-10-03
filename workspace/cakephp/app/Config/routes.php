<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
 
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	Router::connect('/messages', array('controller' => 'messages', 'action' => 'index'));
	Router::connect('/messages/create', array('controller' => 'messages', 'action' => 'create'));
	Router::connect('messages/view/:id', array('controller' => 'messages', 'action' => 'view'));
	Router::connect('messages/delete', array('controller' => 'messages', 'action' => 'delete'));
	Router::connect('messages/sendMessage', array('controller' => 'messages', 'action' => 'sendMessage'));
	Router::connect('messages/deleteMessage', array('controller' => 'messages', 'action' => 'deleteMessage'));
	Router::connect('messages/searchMessage', array('controller' => 'messages', 'action' => 'searchMessage'));
	Router::connect('/users/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/users/register', array('controller' => 'users', 'action' => 'register'));
	Router::connect('/users/logout', array('controller' => 'users', 'action' => 'logout'));
	Router::connect('/users/profile/*', array('controller' => 'users', 'action' => 'profile'));
	Router::connect('/users/edit', array('controller' => 'users', 'action' => 'edit'));
	Router::connect('/users/change-password', array('controller' => 'users', 'action' => 'password'));
	Router::connect('/users/search', array('controller' => 'users', 'action' => 'search'));
/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
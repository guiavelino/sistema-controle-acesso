<?php

namespace App;

use MF\Init\Bootstrap;

class Route extends Bootstrap {

	protected function initRoutes() {

		$routes['home'] = array(
			'route' => '/',
			'controller' => 'IndexController',
			'action' => 'index'
		);
		$routes['sign_up'] = array(
			'route' => '/sign_up',
			'controller' => 'IndexController',
			'action' => 'signUp'
		);
		$routes['register'] = array(
			'route' => '/register',
			'controller' => 'IndexController',
			'action' => 'register'
		);
		$routes['authenticate'] = array(
			'route' => '/authenticate',
			'controller' => 'AuthController',
			'action' => 'authenticate'
		);
		$routes['sign_out'] = array(
			'route' => '/sign_out',
			'controller' => 'AuthController',
			'action' => 'signOut'
		);
		$routes['dashboard'] = array(
			'route' => '/dashboard',
			'controller' => 'DashboardController',
			'action' => 'dashboard'
		);
		$routes['orders'] = array(
			'route' => '/orders',
			'controller' => 'OrdersController',
			'action' => 'orders'
		);
		$routes['register_orders'] = array(
			'route' => '/register_orders',
			'controller' => 'OrdersController',
			'action' => 'registerOrders'
		);
		$routes['edit_orders'] = array(
			'route' => '/edit_orders',
			'controller' => 'OrdersController',
			'action' => 'editOrders'
		);
		$routes['update_orders'] = array(
			'route' => '/update_orders',
			'controller' => 'OrdersController',
			'action' => 'updateOrders'
		);
		$routes['remove_orders'] = array(
			'route' => '/remove_orders',
			'controller' => 'OrdersController',
			'action' => 'removeOrders'
		);
		$routes['delete_orders'] = array(
			'route' => '/delete_orders',
			'controller' => 'OrdersController',
			'action' => 'deleteOrders'
		);

		$this->setRoutes($routes);
	}

}

?>
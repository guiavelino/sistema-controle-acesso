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
		$routes['export_orders'] = array(
			'route' => '/export_orders',
			'controller' => 'OrdersController',
			'action' => 'exportOrders'
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
		

		
		$routes['residents'] = array(
			'route' => '/residents',
			'controller' => 'ResidentsController',
			'action' => 'residents'
		);
		$routes['export_residents'] = array(
			'route' => '/export_residents',
			'controller' => 'ResidentsController',
			'action' => 'exportResidents'
		);
		$routes['register_residents'] = array(
			'route' => '/register_residents',
			'controller' => 'ResidentsController',
			'action' => 'registerResidents'
		);
		$routes['edit_residents'] = array(
			'route' => '/edit_residents',
			'controller' => 'ResidentsController',
			'action' => 'editResidents'
		);
		$routes['update_residents'] = array(
			'route' => '/update_residents',
			'controller' => 'ResidentsController',
			'action' => 'updateResidents'
		);
		$routes['remove_residents'] = array(
			'route' => '/remove_residents',
			'controller' => 'ResidentsController',
			'action' => 'removeResidents'
		);
		$routes['delete_residents'] = array(
			'route' => '/delete_residents',
			'controller' => 'ResidentsController',
			'action' => 'deleteResidents'
		);

		$this->setRoutes($routes);
	}

}

?>
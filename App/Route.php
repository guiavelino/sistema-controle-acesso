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
		$routes['forgot_password'] = array(
			'route' => '/forgot_password',
			'controller' => 'IndexController',
			'action' => 'forgotPassword'
		);
		$routes['update_password'] = array(
			'route' => '/update_password',
			'controller' => 'IndexController',
			'action' => 'updatePassword'
		);
		$routes['my_profile'] = array(
			'route' => '/my_profile',
			'controller' => 'UserProfileController',
			'action' => 'myProfile'
		);
		$routes['update_profile'] = array(
			'route' => '/update_profile',
			'controller' => 'UserProfileController',
			'action' => 'updateProfile'
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
		$routes['register_order'] = array(
			'route' => '/register_order',
			'controller' => 'OrdersController',
			'action' => 'registerOrder'
		);
		$routes['edit_order'] = array(
			'route' => '/edit_order',
			'controller' => 'OrdersController',
			'action' => 'editOrder'
		);
		$routes['update_order'] = array(
			'route' => '/update_order',
			'controller' => 'OrdersController',
			'action' => 'updateOrder'
		);
		$routes['remove_order'] = array(
			'route' => '/remove_order',
			'controller' => 'OrdersController',
			'action' => 'removeOrder'
		);
		$routes['delete_order'] = array(
			'route' => '/delete_order',
			'controller' => 'OrdersController',
			'action' => 'deleteOrder'
		);
		$routes['confirm_receipt'] = array(
			'route' => '/confirm_receipt',
			'controller' => 'OrdersController',
			'action' => 'confirmReceipt'
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
		$routes['register_resident'] = array(
			'route' => '/register_resident',
			'controller' => 'ResidentsController',
			'action' => 'registerResident'
		);
		$routes['edit_resident'] = array(
			'route' => '/edit_resident',
			'controller' => 'ResidentsController',
			'action' => 'editResident'
		);
		$routes['update_resident'] = array(
			'route' => '/update_resident',
			'controller' => 'ResidentsController',
			'action' => 'updateResident'
		);
		$routes['remove_resident'] = array(
			'route' => '/remove_resident',
			'controller' => 'ResidentsController',
			'action' => 'removeResident'
		);
		$routes['delete_resident'] = array(
			'route' => '/delete_resident',
			'controller' => 'ResidentsController',
			'action' => 'deleteResident'
		);



		$routes['visitors'] = array(
			'route' => '/visitors',
			'controller' => 'VisitorsController',
			'action' => 'visitors'
		);
		$routes['export_visitors'] = array(
			'route' => '/export_visitors',
			'controller' => 'VisitorsController',
			'action' => 'exportVisitors'
		);
		$routes['register_visitor'] = array(
			'route' => '/register_visitor',
			'controller' => 'VisitorsController',
			'action' => 'registerVisitor'
		);
		$routes['register_entry_visitor'] = array(
			'route' => '/register_entry_visitor',
			'controller' => 'VisitorsController',
			'action' => 'registerEntry'
		);
		$routes['register_exit_visitor'] = array(
			'route' => '/register_exit_visitor',
			'controller' => 'VisitorsController',
			'action' => 'registerExit'
		);
		$routes['edit_visitor'] = array(
			'route' => '/edit_visitor',
			'controller' => 'VisitorsController',
			'action' => 'editVisitor'
		);
		$routes['update_visitor'] = array(
			'route' => '/update_visitor',
			'controller' => 'VisitorsController',
			'action' => 'updateVisitor'
		);
		$routes['edit_visitor_entry'] = array(
			'route' => '/edit_visitor_entry',
			'controller' => 'VisitorsController',
			'action' => 'editVisitorEntry'
		);
		$routes['update_visitor_entry'] = array(
			'route' => '/update_visitor_entry',
			'controller' => 'VisitorsController',
			'action' => 'updateVisitorEntry'
		);
		$routes['remove_visitor'] = array(
			'route' => '/remove_visitor',
			'controller' => 'VisitorsController',
			'action' => 'removeVisitor'
		);
		$routes['delete_visitor'] = array(
			'route' => '/delete_visitor',
			'controller' => 'VisitorsController',
			'action' => 'deleteVisitor'
		);
		$routes['remove_visitor_entry'] = array(
			'route' => '/remove_visitor_entry',
			'controller' => 'VisitorsController',
			'action' => 'removeVisitorEntry'
		);
		$routes['delete_visitor_entry'] = array(
			'route' => '/delete_visitor_entry',
			'controller' => 'VisitorsController',
			'action' => 'deleteVisitorEntry'
		);



		$routes['service_providers'] = array(
			'route' => '/service_providers',
			'controller' => 'ServiceProvidersController',
			'action' => 'serviceProviders'
		);
		$routes['export_service_providers'] = array(
			'route' => '/export_service_providers',
			'controller' => 'ServiceProvidersController',
			'action' => 'exportServiceProviders'
		);
		$routes['register_service_provider'] = array(
			'route' => '/register_service_provider',
			'controller' => 'ServiceProvidersController',
			'action' => 'registerServiceProvider'
		);
		$routes['register_entry_service_provider'] = array(
			'route' => '/register_entry_service_provider',
			'controller' => 'ServiceProvidersController',
			'action' => 'registerEntry'
		);
		$routes['register_exit_service_provider'] = array(
			'route' => '/register_exit_service_provider',
			'controller' => 'ServiceProvidersController',
			'action' => 'registerExit'
		);
		$routes['edit_service_provider'] = array(
			'route' => '/edit_service_provider',
			'controller' => 'ServiceProvidersController',
			'action' => 'editServiceProvider'
		);
		$routes['update_service_provider'] = array(
			'route' => '/update_service_provider',
			'controller' => 'ServiceProvidersController',
			'action' => 'updateServiceProvider'
		);
		$routes['edit_service_provider_entry'] = array(
			'route' => '/edit_service_provider_entry',
			'controller' => 'ServiceProvidersController',
			'action' => 'editServiceProviderEntry'
		);
		$routes['update_service_provider_entry'] = array(
			'route' => '/update_service_provider_entry',
			'controller' => 'ServiceProvidersController',
			'action' => 'updateServiceProviderEntry'
		);
		$routes['remove_service_provider'] = array(
			'route' => '/remove_service_provider',
			'controller' => 'ServiceProvidersController',
			'action' => 'removeServiceProvider'
		);
		$routes['delete_service_provider'] = array(
			'route' => '/delete_service_provider',
			'controller' => 'ServiceProvidersController',
			'action' => 'deleteServiceProvider'
		);
		$routes['remove_service_provider_entry'] = array(
			'route' => '/remove_service_provider_entry',
			'controller' => 'ServiceProvidersController',
			'action' => 'removeServiceProviderEntry'
		);
		$routes['delete_service_provider_entry'] = array(
			'route' => '/delete_service_provider_entry',
			'controller' => 'ServiceProvidersController',
			'action' => 'deleteServiceProviderEntry'
		);


		
		$routes['leisure_areas'] = array(
			'route' => '/leisure_areas',
			'controller' => 'LeisureAreasController',
			'action' => 'leisureAreas'
		);
		$routes['register_event'] = array(
			'route' => '/register_event',
			'controller' => 'LeisureAreasController',
			'action' => 'registerEvent'
		);
		$routes['view_event'] = array(
			'route' => '/view_event',
			'controller' => 'LeisureAreasController',
			'action' => 'event'
		);
		$routes['update_event'] = array(
			'route' => '/update_event',
			'controller' => 'LeisureAreasController',
			'action' => 'updateEvent'
		);
		$routes['delete_event'] = array(
			'route' => '/delete_event',
			'controller' => 'LeisureAreasController',
			'action' => 'deleteEvent'
		);
		$routes['confirm_payment'] = array(
			'route' => '/confirm_payment',
			'controller' => 'LeisureAreasController',
			'action' => 'confirmPayment'
		);

		$this->setRoutes($routes);
	}

}

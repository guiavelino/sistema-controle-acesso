<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class DashboardController extends Action {

    public function validateAuthentication(){
        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == null){
            header('Location: /?login=erro');
        }
    }

	public function dashboard(){
        $this->validateAuthentication();
        if($_SESSION['nivel_acesso'] == 'administrador'){
            $encomendas = Container::getModel('Orders');
            $encomendas->data_cadastro = date('Y-m-d');
            foreach($encomendas->getAllOrders() as $e){
                $this->view->total_encomendas = $e[0];
            }

            $visitantes = Container::getModel('Visitors');
            $visitantes->data_cadastro = date('Y-m-d');
            foreach($visitantes->getAllVisitors() as $e){
                $this->view->total_visitantes = $e[0];
            }

            $prestadores_servicos = Container::getModel('ServiceProviders');
            $prestadores_servicos->data_cadastro = date('Y-m-d');
            foreach($prestadores_servicos->getAllServiceProviders() as $e){
                $this->view->total_prestadores_servicos = $e[0];
            }
            
            $this->render('dashboard_admin');
        }
        else{
            $this->render('dashboard_user');
        }  
    }

}


?>
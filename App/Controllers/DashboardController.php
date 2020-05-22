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

            foreach($encomendas->getAllOrdersByDay() as $e){
                $this->view->total_encomendas_por_dia = $e;
            }
            foreach($encomendas->getAllOrdersByMonth() as $e){
                $this->view->total_encomendas_por_mes = $e;
            }

            foreach($encomendas->getAllOrdersByJanuary() as $e){
                $this->view->total_encomendas_janeiro = $e;
            }
            foreach($encomendas->getAllOrdersByFebruary() as $e){
                $this->view->total_encomendas_fevereiro = $e;
            }
            foreach($encomendas->getAllOrdersByMarch() as $e){
                $this->view->total_encomendas_marco = $e;
            }
            foreach($encomendas->getAllOrdersByApril() as $e){
                $this->view->total_encomendas_abril = $e;
            }
            foreach($encomendas->getAllOrdersByMay() as $e){
                $this->view->total_encomendas_maio = $e;
            }
            foreach($encomendas->getAllOrdersByJune() as $e){
                $this->view->total_encomendas_junho = $e;
            }
            foreach($encomendas->getAllOrdersByJuly() as $e){
                $this->view->total_encomendas_julho = $e;
            }
            foreach($encomendas->getAllOrdersByAugust() as $e){
                $this->view->total_encomendas_agosto = $e;
            }
            foreach($encomendas->getAllOrdersBySeptember() as $e){
                $this->view->total_encomendas_setembro = $e;
            }
            foreach($encomendas->getAllOrdersByOctober() as $e){
                $this->view->total_encomendas_outubro = $e;
            }
            foreach($encomendas->getAllOrdersByNovember() as $e){
                $this->view->total_encomendas_novembro = $e;
            }
            foreach($encomendas->getAllOrdersByDecember() as $e){
                $this->view->total_encomendas_dezembro = $e;
            }

            $visitantes = Container::getModel('Visitors');
            $visitantes->data_cadastro = date('Y-m-d');
            foreach($visitantes->getAllVisitorsByDay() as $e){
                $this->view->total_visitantes_por_dia = $e;
            }
            foreach($visitantes->getAllVisitorsByMonth() as $e){
                $this->view->total_visitantes_por_mes = $e;
            }

            $prestadores_servicos = Container::getModel('ServiceProviders');
            $prestadores_servicos->data_cadastro = date('Y-m-d');
            foreach($prestadores_servicos->getAllServiceProvidersByDay() as $e){
                $this->view->total_prestadores_servicos_por_dia = $e;
            }
            foreach($prestadores_servicos->getAllServiceProvidersByMonth() as $e){
                $this->view->total_prestadores_servicos_por_mes = $e;
            }

            $this->render('dashboard_admin');
        }
        else{
            $this->render('dashboard_user');
        }  
    }

}


?>
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
            $visitantes = Container::getModel('Visitors');
            $prestadores_servicos = Container::getModel('ServiceProviders');
            
            $encomendas->data_atual = date('Y-m-d');
            $visitantes->data_atual = date('Y-m-d');
            $prestadores_servicos->data_atual = date('Y-m-d');

            // Exibindo regsitros por dia
            $this->view->total_encomendas_por_dia = $encomendas->getAllOrdersByDay()['encomendas_por_dia'];
            $this->view->total_visitantes_por_dia = $visitantes->getAllVisitorsByDay()['visitantes_por_dia'];
            $this->view->total_prestadores_servicos_por_dia = $prestadores_servicos->getAllServiceProvidersByDay()['prestadores_servicos_por_dia'];
            
            // Exibindo regsitros por mês
            $this->view->total_encomendas_por_mes = $encomendas->getAllOrdersByMonth()['encomendas_por_mes'];    
            $this->view->total_visitantes_por_mes = $visitantes->getAllVisitorsByMonth()['visitantes_por_mes'];
            $this->view->total_prestadores_servicos_por_mes = $prestadores_servicos->getAllServiceProvidersByMonth()['prestadores_servicos_por_mes'];
           
            // Exibindo regsitros por ano
            for($i =0; $i < 12; $i++){          
                $this->view->total_encomendas_por_ano[$i] =  isset($encomendas->getAllOrdersByYear()[$i]) ? $encomendas->getAllOrdersByYear()[$i] : 0 ;
                $this->view->total_visitantes_por_ano[$i] = isset($visitantes->getAllVisitorsByYear()[$i]) ? $visitantes->getAllVisitorsByYear()[$i] : 0;
                $this->view->total_prestadores_servicos_por_ano[$i] = isset($prestadores_servicos->getAllServiceProvidersByYear()[$i]) ?  $prestadores_servicos->getAllServiceProvidersByYear()[$i] : 0;  
            }
            
            $this->render('dashboard_admin');
        }
        else{
            $this->render('dashboard_user');
        }  
    }

}


?>
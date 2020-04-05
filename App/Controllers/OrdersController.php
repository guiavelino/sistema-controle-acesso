<?php

namespace App\Controllers;

use App\Models\Users;
use MF\Controller\Action;
use MF\Model\Container;

class OrdersController extends Action {

    public function validateAuthentication(){
        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == null){
            header('Location: /?login=erro');
        }
    }

	public function orders(){
        $this->validateAuthentication();

        if($_SESSION['nivel_acesso'] == 'administrador'){
            $this->render('orders_admin');
        }
        else{
            $encomendas = Container::getModel('Orders');
            $this->view->encomendas = $encomendas->getAll();
            $this->render('orders_user');
        }  
    }

    public function registerOrders(){
        $encomendas = Container::getModel('Orders');
		$encomendas->empresa = $_POST['empresa'];
		$encomendas->apartamento = $_POST['apartamento'];
		$encomendas->bloco = $_POST['bloco'];
        $encomendas->registerOrder();

        header('Location: /orders');
    }

    public function editOrders(){
        if(isset($_POST['id_encomenda'])){
            $this->render('edit_orders');
        }else{
            header('Location: /orders');
        }
    }

    public function updateOrders(){
        if(isset($_POST['id_encomenda'])){
            $encomendas = Container::getModel('Orders');
            $encomendas->empresa = $_POST['empresa'];
            $encomendas->apartamento = $_POST['apartamento'];
            $encomendas->bloco = $_POST['bloco'];
            $encomendas->id_encomenda = $_POST['id_encomenda'];
            $encomendas->updateOrder();
        }
        header('Location: /orders');
    }

    public function removeOrders(){
        if(isset($_POST['id_encomenda'])){
            $this->render('remove_orders');
        }else{
            header('Location: /orders');
        }
    }

    public function deleteOrders(){
        if(isset($_POST['id_encomenda'])){
            $encomendas = Container::getModel('Orders');
            $encomendas->id_encomenda = $_POST['id_encomenda'];
            $encomendas->deleteOrder();
        }
        header('Location: /orders');
    }


}

?>
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

    public function exportOrders(){
        $encomendas = Container::getModel('Orders');

        // Definindo o nome do arquivo que será exportado
		$arquivo = 'relacao_encomendas.xls';
		
		// Criando uma tabela HTML com o formato da planilha
		$html = '';
		$html .= '<table border="1">';
		$html .= '<tr>';
		$html .= '<td colspan="5" style="text-align:center; background-color:#1EA39C; color:#FFFFFF"><h2 style="margin:0">Encomendas</h2></td>';
		$html .= '</tr>';
		$html .= '<tr>';
		$html .= '<td style="text-align:center; background-color:#F7F7F7"><h4 style="margin:0">Empresa</h4></td>';
		$html .= '<td style="text-align:center; background-color:#F7F7F7"><h4 style="margin:0">Apartamento</h4></td>';
		$html .= '<td style="text-align:center; background-color:#F7F7F7"><h4 style="margin:0">Bloco</h4></td>';
        $html .= '<td style="text-align:center; background-color:#F7F7F7"><h4 style="margin:0">Data da entrega</h4></td>';
        $html .= '<td style="text-align:center; background-color:#F7F7F7"><h4 style="margin:0">Hora da entrega</h4></td>';
		$html .= '</tr>';
        foreach($encomendas->getAll() as $encomendas){
            $html .= '<tr style="text-align:center">';
			$html .= '<td>'.$encomendas["empresa"].'</td>';
			$html .= '<td>'.$encomendas["apartamento"].'</td>';
			$html .= '<td>'.$encomendas["bloco"].'</td>';
            $html .= '<td>'.date('d/m/Y', strtotime($encomendas['data_entrega'])).'</td>';
            $html .= '<td>'.date('H:i', strtotime($encomendas['data_entrega'])).'</td>';
			$html .= '</tr>';
        }
        
		// Configurações header para forçar o download
		header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
		header ("Cache-Control: no-cache, must-revalidate");
		header ("Pragma: no-cache");
		header ("Content-type: application/x-msexcel");
		header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
		header ("Content-Description: PHP Generated Data" );
		// Envia o conteúdo do arquivo
		echo $html;
		exit;
    }

}

?>
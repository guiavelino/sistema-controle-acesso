<?php

namespace App\Controllers;

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
            $encomendas = Container::getModel('Orders');   
            $this->view->encomendas = $encomendas->getAllOrdersRegisters();
            $this->render('orders_admin');
        }
        else{
            $encomendas = Container::getModel('Orders');
            $this->view->encomendas = $encomendas->getAllOrdersRegisters();
            $this->render('orders_user');
        }  
    }

    public function registerOrder(){
        $this->validateAuthentication();
        if($_POST['empresa'] != '' &&  $_POST['apartamento'] != '' &&  $_POST['bloco'] != ''){
            $encomendas = Container::getModel('Orders');
            $encomendas->empresa = $_POST['empresa'];
            $encomendas->apartamento = $_POST['apartamento'];
            $encomendas->bloco = $_POST['bloco'];
            $encomendas->registerOrder();
            echo "<script>alert('Encomenda cadastrada com sucesso!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para realizar o cadastro!')</script>";
        }
        echo "<script> location.href = '/orders' </script>";
    }

    public function editOrder(){
        $this->validateAuthentication();
        if(isset($_POST['id_encomenda'])){
            $this->render('edit_order');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/orders' </script>";
        }
    }

    public function updateOrder(){
        $this->validateAuthentication();
        if($_POST['empresa'] != '' && $_POST['apartamento'] != '' && $_POST['bloco'] != '' && $_POST['id_encomenda'] != ''){
            $encomendas = Container::getModel('Orders');
            $encomendas->empresa = $_POST['empresa'];
            $encomendas->apartamento = $_POST['apartamento'];
            $encomendas->bloco = $_POST['bloco'];
            $encomendas->id_encomenda = $_POST['id_encomenda'];
            $encomendas->updateOrder();
            echo "<script>alert('Dados atualizados com sucesso!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para atualizar o registro!')</script>";
        }
        echo "<script> location.href = '/orders' </script>";
    }

    public function removeOrder(){
        $this->validateAuthentication();
        if(isset($_POST['id_encomenda'])){
            $this->render('remove_order');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/orders' </script>";
        }
    }

    public function deleteOrder(){
        $this->validateAuthentication();
        if(isset($_POST['id_encomenda'])){
            $encomendas = Container::getModel('Orders');
            $encomendas->id_encomenda = $_POST['id_encomenda'];
            $encomendas->deleteOrder();
            echo "<script>alert('Registro excluído com sucesso!')</script>";
        }
        echo "<script> location.href = '/orders' </script>";
    }

    public function exportOrders(){
        $this->validateAuthentication();
        $encomendas = Container::getModel('Orders');

        $arquivo = '';

        // Formatando estilo da tabela
        $style_first_header = "height: 60px; font-size:22px; text-align:center; background-color:#1EA39C; color:#FFFFFF; display:table-cell; vertical-align:middle;";
        $style_second_header_name = "height: 45px; width: 300px; text-align:center; background-color:#F7F7F7; display:table-cell; vertical-align:middle;";
        $style_second_header = "height: 45px; width: 200px; text-align:center; background-color:#F7F7F7; display:table-cell; vertical-align:middle;";
        $style_titile_header = "font-size:20px";
        $style_content = "height:32px; text-align:center; font-size:20;  display:table-cell; vertical-align:middle";

        if($_POST['periodo'] == 'Todo o período' && $_POST['data-inicio'] == '' && $_POST['data-fim'] == ''){
             // Definindo o nome do arquivo que será exportado
		    $arquivo = 'relacao_encomendas.xls';

            // Criando uma tabela HTML com o formato da planilha
            $html = '';
            $html .= '<meta charset="utf-8"/>';
            $html .= '<table border="1">';
            $html .= "<tr>";
            $html .= "<td colspan='4' style='$style_first_header'><h2>Relação de encomendas</h2></td>";
            $html .= "</tr>";
            $html .= '<tr>';
            $html .= "<td style='$style_second_header_name'><h4 style='$style_titile_header'>Empresa</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Apartamento</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Bloco</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Data de entrega</h4></td>";
            $html .= '</tr>';
            foreach($encomendas->getAllOrdersRegisters() as $e){
                $html .= "<tr style='$style_content'>";
                $html .= '<td>'.$e["empresa"].'</td>';
                $html .= '<td>'.$e["apartamento"].'</td>';
                $html .= '<td>'.$e["bloco"].'</td>';
                $html .= '<td>'.date('d/m/Y H:i', strtotime($e['data_entrega'])).'</td>';
                $html .= '</tr>';
            }
        }  
        if($_POST['periodo'] == 'Personalizado' && strlen($_POST['data-inicio']) == 10 && strlen($_POST['data-fim']) == 10){
            // Definindo o nome do arquivo que será exportado
            $arquivo = 'relacao_encomendas.xls';

            $encomendas->data_inicio = $_POST['data-inicio'];
            $encomendas->data_fim = $_POST['data-fim'];

            $data_inicio = date('d/m/Y', strtotime($_POST['data-inicio']));
            $data_fim = date('d/m/Y', strtotime($_POST['data-fim']));

           // Criando uma tabela HTML com o formato da planilha
            $html = '';
            $html .= '<meta charset="utf-8"/>';
            $html .= '<table border="1">';
            $html .= "<tr>";
            $html .= "<td colspan='4' style='$style_first_header'><h2>Relação de encomendas - $data_inicio até $data_fim</h2></td>";
            $html .= "</tr>";
            $html .= '<tr>';
            $html .= "<td style='$style_second_header_name'><h4 style='$style_titile_header'>Empresa</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Apartamento</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Bloco</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Data de entrega</h4></td>";
            $html .= '</tr>';
            foreach($encomendas->getAllRegistersFilter() as $e){
                $html .= "<tr style='$style_content'>";
                $html .= '<td>'.$e["empresa"].'</td>';
                $html .= '<td>'.$e["apartamento"].'</td>';
                $html .= '<td>'.$e["bloco"].'</td>';
                $html .= '<td>'.date('d/m/Y H:i', strtotime($e['data_entrega'])).'</td>';
                $html .= '</tr>';
            }
       }  
       else if($_POST['periodo'] == 'Personalizado' && (strlen($_POST['data-inicio']) != 10 || strlen($_POST['data-fim']) != 10)){
        echo "<script>
            alert('Digite uma data válida para exportar os registros!');
            location.href = '/orders'
        </script>";
        } 
        else{
            echo "<script>
                alert('Erro ao exportar registros, tente novamente mais tarde!');
                location.href = '/orders'
            </script>";
        }

		if(strlen($arquivo) > 3){
            // // Configurações header para forçar o download
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
}
?>
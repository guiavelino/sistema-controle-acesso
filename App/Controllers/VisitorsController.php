<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class VisitorsController extends Action {

    public function validateAuthentication(){
        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == null){
            header('Location: /?login=erro');
        }
    }

	public function visitors(){
        $this->validateAuthentication();
        if($_SESSION['nivel_acesso'] == 'administrador'){
            $visitantes = Container::getModel('Visitors');
            
            $this->view->visitantes = $visitantes->getAll();
            $this->view->total_visitantes_presentes = $visitantes->getAllNumberVisitorsPresents()['visitantes_presentes'];
            $this->view->visitantes_presentes = $visitantes->getAllVisitorsPresents();

            $this->render('visitors_admin');
        }
        else{
            $visitantes = Container::getModel('Visitors');
            $this->view->visitantes = $visitantes->getAll();
            $this->render('visitors_user');
        }  
    }

    public function registerVisitors(){
        $this->validateAuthentication();
        if($_POST['nome'] != '' && strlen($_POST['cpf']) == 14 && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $visitantes = Container::getModel('Visitors');
            $moradores = Container::getModel('Residents');
            
            //Tratando duplicidade de CPF 
            foreach($moradores->getAll() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    echo "<script>alert('Erro ao realizar cadastro, um morador ja possui este CPF!')</script>";
                    echo "<script> location.href = '/visitors' </script>";
                    exit;
                }
            }

            $visitantes->nome = $_POST['nome'];
            $visitantes->cpf = $_POST['cpf'];
            $visitantes->apartamento = $_POST['apartamento'];
            $visitantes->bloco = $_POST['bloco'];
            $visitantes->registerVisitor();
            echo "<script>alert('Visitante cadastrado com sucesso!')</script>";
        }
        else if(strlen($_POST['cpf']) != 14){
            echo "<script>alert('Digite um CPF válido para realizar o cadastro!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para realizar o cadastro!')</script>";
        }
        echo "<script> location.href = '/visitors' </script>";
    }

    public function registerExit(){
        $this->validateAuthentication();
        if(isset($_POST['id_visitante'])){
            $visitantes = Container::getModel('Visitors');
            $visitantes->id_visitante = $_POST['id_visitante'];
            $visitantes->data_saida = date('Y-m-d H:i:s');
            $visitantes->registerExit();
            echo "<script>alert('Saída registrada!')</script>";
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
        }
        echo "<script> location.href = '/visitors' </script>";
    }

    public function editVisitors(){
        $this->validateAuthentication();
        if(isset($_POST['id_visitante'])){
            $this->render('edit_visitors');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/visitors' </script>";
        }
    }

    public function updateVisitors(){
        $this->validateAuthentication();
        if($_POST['nome'] != '' && strlen($_POST['cpf']) == 14 && $_POST['apartamento'] != '' && $_POST['bloco'] != '' && isset($_POST['id_visitante'])){
            $visitantes = Container::getModel('Visitors');
            $moradores = Container::getModel('Residents');
    
            foreach($moradores->getAll() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    echo "<script>alert('Erro ao atualizar registro, um morador ja possui este CPF!')</script>";
                    echo "<script> location.href = '/visitors' </script>";
                    exit;
                }
            }

            $visitantes->nome = $_POST['nome'];
            $visitantes->cpf = $_POST['cpf'];
            $visitantes->apartamento = $_POST['apartamento'];
            $visitantes->bloco = $_POST['bloco'];
            $visitantes->id_visitante = $_POST['id_visitante'];
            $visitantes->updateVisitor();
            echo "<script>alert('Registro atualizado com sucesso!')</script>";
        }
        else if(strlen($_POST['cpf']) != 14){
            echo "<script>alert('Digite um CPF válido para atualizar o registro!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para atualizar o registro!')</script>";
        }
        echo "<script> location.href = '/visitors' </script>";
    }

    public function removeVisitors(){
        $this->validateAuthentication();
        if(isset($_POST['id_visitante'])){
            $this->render('remove_visitors');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/visitors' </script>";
        }
    }

    public function deleteVisitors(){
        $this->validateAuthentication();
        if(isset($_POST['id_visitante'])){
            $visitantes = Container::getModel('Visitors');
            $visitantes->id_visitante = $_POST['id_visitante'];
            $visitantes->deleteVisitor();
            echo "<script>alert('Registro excluído com sucesso!')</script>";
        }
        echo "<script> location.href = '/visitors' </script>";
    }

    public function exportVisitors(){
        $this->validateAuthentication();
        $visitantes = Container::getModel('Visitors');

        // Definindo o nome do arquivo que será exportado
		$arquivo = 'relacao_visitantes.xls';
        
        // Formatando estilo da tabela
        $style_first_header = "height: 60px; text-align:center; background-color:#1EA39C; color:#FFFFFF; display:table-cell; vertical-align:middle;";
        $style_second_header_name = "height: 45px; width: 300px; text-align:center; background-color:#F7F7F7; display:table-cell; vertical-align:middle;";
        $style_second_header = "height: 45px; width: 200px; text-align:center; background-color:#F7F7F7; display:table-cell; vertical-align:middle;";
        $style_titile_header = "font-size:22px";
        $style_content = "height:32px; text-align:center; font-size:20;  display:table-cell; vertical-align:middle";

		// Criando uma tabela HTML com o formato da planilha
        $html = '';
        $html .= '<meta charset="utf-8"/>';
		$html .= '<table border="1">';
		$html .= "<tr>";
		$html .= "<td colspan='6' style='$style_first_header'><h2>Visitantes</h2></td>";
		$html .= "</tr>";
		$html .= '<tr>';
		$html .= "<td style='$style_second_header_name'><h4 style='$style_titile_header'>Nome</h4></td>";
		$html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>CPF</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Apartamento</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Bloco</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Entrada</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Saída</h4></td>";
		$html .= '</tr>';
        foreach($visitantes->getAll() as $visitantes){
            $html .= "<tr style='$style_content'>";
			$html .= '<td>'.$visitantes["nome"].'</td>';
			$html .= '<td>'.$visitantes["cpf"].'</td>';
            $html .= '<td>'.$visitantes['apartamento'].'</td>';
            $html .= '<td>'.$visitantes['bloco'].'</td>';
            $html .= '<td>'.date('d/m/Y H:i', strtotime($visitantes['data_entrada'])).'</td>';
            $html .= '<td>'.date('d/m/Y H:i', strtotime($visitantes['data_saida'])).'</td>';
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
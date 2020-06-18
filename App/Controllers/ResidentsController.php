<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class ResidentsController extends Action {

    public function validateAuthentication(){
        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == null){
            header('Location: /?login=erro');
        }
    }

	public function residents(){
        $this->validateAuthentication();
        if($_SESSION['nivel_acesso'] == 'administrador'){
            $moradores = Container::getModel('Residents');
            $this->view->moradores = $moradores->getAllResidentsRegisters();
            $this->render('residents_admin');
        }
        else{
            $moradores = Container::getModel('Residents');
            $this->view->moradores = $moradores->getAllResidentsRegisters();
            $this->render('residents_user');
        }  
    }

    public function registerResident(){
        $this->validateAuthentication();
        if($_POST['nome'] != '' && strlen($_POST['cpf']) == 14 && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $moradores = Container::getModel('Residents');
            $prestadores_servicos = Container::getModel('ServiceProviders');
            $visitantes = Container::getModel('Visitors');
            
            //Tratando duplicidade de CPF 
            foreach($moradores->getAllResidentsRegisters() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    echo "<script>alert('Esse morador ja foi cadastrado!')</script>";
                    echo "<script> location.href = '/residents' </script>";
                    exit;
                }
            }  
            foreach($visitantes->getAllVisitorsRegisters() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    echo "<script>alert('Erro ao realizar cadastro, um visitante ja possui este CPF!')</script>";
                    echo "<script> location.href = '/residents' </script>";
                    exit;
                }
            }

            $moradores->nome = $_POST['nome'];
            $moradores->cpf = $_POST['cpf'];
            $moradores->telefone = $_POST['telefone'] != '' ? $_POST['telefone'] : '';
            $moradores->apartamento = $_POST['apartamento'];
            $moradores->bloco = $_POST['bloco'];
            $moradores->registerResident();
            echo "<script>alert('Morador cadastrado com sucesso!')</script>";
        }
        else if(strlen($_POST['cpf']) != 14){
            echo "<script>alert('Digite um CPF válido para realizar o cadastro!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para realizar o cadastro!')</script>";
        }
        echo "<script> location.href = '/residents' </script>";
    }

    public function editResident(){
        $this->validateAuthentication();
        if(isset($_POST['id_morador'])){
            $this->render('edit_resident');
        }else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/residents' </script>";
        }
    }

    public function updateResident(){
        $this->validateAuthentication();
        if($_POST['nome'] != '' && strlen($_POST['cpf']) == 14 && $_POST['apartamento'] != '' && $_POST['bloco'] != '' && $_POST['id_morador'] != ''){
            $moradores = Container::getModel('Residents');
            $visitantes = Container::getModel('Visitors');

            foreach($moradores->getAllResidentsRegisters() as $e){
                if($_POST['cpf'] == $e['cpf'] && $_POST['id_morador'] != $e['id_morador']){
                    echo "<script>alert('Erro ao atualizar cadastro, um morador ja possui este CPF!')</script>";
                    echo "<script> location.href = '/residents' </script>";
                    exit;
                }
            }
            foreach($visitantes->getAllVisitorsRegisters() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    echo "<script>alert('Erro ao atualizar cadastro, um visitante ja possui este CPF!')</script>";
                    echo "<script> location.href = '/residents' </script>";
                    exit;
                }
            }

            $moradores->nome = $_POST['nome'];
            $moradores->cpf = $_POST['cpf'];
            $moradores->telefone = $_POST['telefone'] != '' ? $_POST['telefone'] : '';
            $moradores->apartamento = $_POST['apartamento'];
            $moradores->bloco = $_POST['bloco'];
            $moradores->id_morador = $_POST['id_morador'];
            $moradores->updateResident();
            echo "<script>alert('Dados atualizados com sucesso!')</script>";
        }
        else if(strlen($_POST['cpf']) != 14){
            echo "<script>alert('Digite um CPF válido para atualizar o cadastro!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para atualizar o cadastro!')</script>";
        }
        echo "<script> location.href = '/residents' </script>";
    }

    public function removeResident(){
        $this->validateAuthentication();
        if(isset($_POST['id_morador'])){
            $this->render('remove_resident');
        }else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/residents' </script>";
        }
    }

    public function deleteResident(){
        $this->validateAuthentication();
        if(isset($_POST['id_morador'])){
            $moradores = Container::getModel('Residents');
            $moradores->id_morador = $_POST['id_morador'];
            $moradores->deleteResident();
            echo "<script>alert('Cadastro excluído com sucesso!')</script>";
        }
        echo "<script> location.href = '/residents' </script>";
    }

    public function exportResidents(){
        $this->validateAuthentication();
        $moradores = Container::getModel('Residents');

        // Definindo o nome do arquivo que será exportado
		$arquivo = 'relacao_moradores.xls';
        
        // Formatando estilo da tabela
        $style_first_header = "height: 60px; font-size:22px; text-align:center; background-color:#1EA39C; color:#FFFFFF; display:table-cell; vertical-align:middle;";
        $style_second_header_name = "height: 45px; width: 300px; text-align:center; background-color:#F7F7F7; display:table-cell; vertical-align:middle;";
        $style_second_header = "height: 45px; width: 200px; text-align:center; background-color:#F7F7F7; display:table-cell; vertical-align:middle;";
        $style_titile_header = "font-size:20px";
        $style_content = "height:32px; text-align:center; font-size:20;  display:table-cell; vertical-align:middle";

		// Criando uma tabela HTML com o formato da planilha
        $html = '';
        $html .= '<meta charset="utf-8"/>';
		$html .= '<table border="1">';
		$html .= "<tr>";
		$html .= "<td colspan='5' style='$style_first_header'><h2>Relação de moradores</h2></td>";
		$html .= "</tr>";
		$html .= '<tr>';
		$html .= "<td style='$style_second_header_name'><h4 style='$style_titile_header'>Nome</h4></td>";
		$html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>CPF</h4></td>";
		$html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Telefone</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Apartamento</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Bloco</h4></td>";
		$html .= '</tr>';
        foreach($moradores->getAllResidentsRegisters() as $moradores){
            $html .= "<tr style='$style_content'>";
			$html .= '<td>'.$moradores["nome"].'</td>';
			$html .= '<td>'.$moradores["cpf"].'</td>';
			$html .= '<td>'.$moradores["telefone"].'</td>';
            $html .= '<td>'.$moradores['apartamento'].'</td>';
            $html .= '<td>'.$moradores['bloco'].'</td>';
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
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
            $this->render('residents_admin');
        }
        else{
            $moradores = Container::getModel('Residents');
            $this->view->moradores = $moradores->getAll();
            $this->render('residents_user');
        }  
    }

    public function registerResidents(){
        if($_POST['nome'] != '' &&  $_POST['cpf'] != '' && $_POST['telefone'] != '' && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            
            $moradores = Container::getModel('Residents');
            
            //Tratando duplicidade de CPF - Solução funcional, porém redundante. 
            foreach($moradores->getAll() as $a){
                if($_POST['cpf'] == $a['cpf']){
                    echo "<script>alert('Erro ao realizar cadastro, um morador ja possui este CPF!')</script>";
                    echo "<script> location.href = '/residents' </script>";
                    exit;
                }
            }
            foreach($moradores->getAllV() as $b){
                if($_POST['cpf'] == $b['cpf']){
                    echo "<script>alert('Erro ao realizar cadastro, um visitante ja possui este CPF!')</script>";
                    echo "<script> location.href = '/residents' </script>";
                    exit;
                }
            }
            foreach($moradores->getAllP() as $c){
                if($_POST['cpf'] == $c['cpf']){
                    echo "<script>alert('Erro ao realizar cadastro, um prestador de serviço ja possui este CPF!')</script>";
                    echo "<script> location.href = '/residents' </script>";
                    exit;
                }
            }

            $moradores->nome = $_POST['nome'];
            $moradores->cpf = $_POST['cpf'];
            $moradores->telefone = $_POST['telefone'];
            $moradores->apartamento = $_POST['apartamento'];
            $moradores->bloco = $_POST['bloco'];
            $moradores->registerResident();
            echo "<script>alert('Cadastro realizado com sucesso!')</script>";
        }else{
            echo "<script>alert('Preencha todos os campos para realizar o cadastro!')</script>";
        }
        echo "<script> location.href = '/residents' </script>";
    }

    public function editResidents(){
        if(isset($_POST['id_morador'])){
            $this->render('edit_residents');
        }else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/residents' </script>";
        }
    }

    public function updateResidents(){
        if(isset($_POST['id_morador'])){
            $moradores = Container::getModel('Residents');

            foreach($moradores->getAllV() as $b){
                if($_POST['cpf'] == $b['cpf']){
                    echo "<script>alert('Erro ao realizar atualização, um visitante ja possui este CPF!')</script>";
                    echo "<script> location.href = '/residents' </script>";
                    exit;
                }
            }
            foreach($moradores->getAllP() as $c){
                if($_POST['cpf'] == $c['cpf']){
                    echo "<script>alert('Erro ao realizar atualização, um prestador de serviço ja possui este CPF!')</script>";
                    echo "<script> location.href = '/residents' </script>";
                    exit;
                }
            }

            $moradores->nome = $_POST['nome'];
            $moradores->cpf = $_POST['cpf'];
            $moradores->telefone = $_POST['telefone'];
            $moradores->apartamento = $_POST['apartamento'];
            $moradores->bloco = $_POST['bloco'];
            $moradores->id_morador = $_POST['id_morador'];
            $moradores->updateResident();
            echo "<script>alert('Registro atualizado com sucesso!')</script>";
        }
        echo "<script> location.href = '/residents' </script>";
    }

    public function removeResidents(){
        if(isset($_POST['id_morador'])){
            $this->render('remove_residents');
        }else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/residents' </script>";
        }
    }

    public function deleteResidents(){
        if(isset($_POST['id_morador'])){
            $moradores = Container::getModel('Residents');
            $moradores->id_morador = $_POST['id_morador'];
            $moradores->deleteResident();
        }
        header('Location: /residents');
    }

    public function exportResidents(){
        $this->validateAuthentication();
        $moradores = Container::getModel('Residents');

        // Definindo o nome do arquivo que será exportado
		$arquivo = 'relacao_moradores.xls';
        
        // Formatando estilo da tabela
        $style_first_header = "height: 60px; text-align:center; background-color:#1EA39C; color:#FFFFFF; display:table-cell; vertical-align:middle;";
        $style_second_header = "height: 45px; width: 300px; text-align:center; background-color:#F7F7F7; display:table-cell; vertical-align:middle;";
        $style_titile_header = "font-size:22px";
        $style_content = "height:32px; text-align:center; font-size:20;  display:table-cell; vertical-align:middle";

		// Criando uma tabela HTML com o formato da planilha
		$html = '';
		$html .= '<table border="1">';
		$html .= "<tr>";
		$html .= "<td colspan='5' style='$style_first_header'><h2>Moradores</h2></td>";
		$html .= "</tr>";
		$html .= '<tr>';
		$html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Nome</h4></td>";
		$html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>CPF</h4></td>";
		$html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Telefone</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Apartamento</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Bloco</h4></td>";
		$html .= '</tr>';
        foreach($moradores->getAll() as $moradores){
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
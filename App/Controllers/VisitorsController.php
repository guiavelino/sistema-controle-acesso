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
        if(strlen($_POST['cpf']) == 14 && strlen($_POST['rg']) >= 8){
            echo "<script>alert('Selecione apenas um documento para realizar o cadastro!')</script>";
        }
        else if(strlen($_POST['cpf']) == 14 && $_POST['nome'] != ''){
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
            foreach($visitantes->getAllVisitorsRegisters() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    echo "<script>alert('Esse visitante ja foi cadastrado, realize o registro e libere a entrada!')</script>";
                    echo "<script> location.href = '/visitors' </script>";
                    exit;
                }
            }

            $visitantes->nome = $_POST['nome'];
            $visitantes->cpf = $_POST['cpf'];
            $visitantes->rg = 'NA';
            $visitantes->uf = 'NA';

            $visitantes->registerVisitor();
            echo "<script>alert('Visitante cadastrado com sucesso, realize o registro e libere a entrada!')</script>";
        }
        else if(strlen($_POST['rg']) >= 8 && $_POST['nome'] != '' && strlen($_POST['uf']) == 2){ // O número minímo de digitos em um RG são 6, mas como a string e enviada com uma máscara aplicada esse valor passa a ser 8 
            $visitantes = Container::getModel('Visitors');
            $moradores = Container::getModel('Residents');

            //Tratando duplicidade de RG
           foreach($visitantes->getAllVisitorsRegisters() as $e){
                if($_POST['rg'] == $e['rg'] && $_POST['uf'] == $e['uf']){ 
                    echo "<script>alert('Esse visitante ja foi cadastrado, realize o registro e libere a entrada!')</script>";
                    echo "<script> location.href = '/visitors' </script>";
                    exit;
                }
            }

            $visitantes->nome = $_POST['nome'];
            $visitantes->rg = $_POST['rg'];
            $visitantes->uf = $_POST['uf'];
            $visitantes->cpf = md5(date('Y-m-d H:i')); // A coluna CPF no banco de dados utiliza o atributo UNIQUE, por isso um valor vazio não pode ser atribuído

            $visitantes->registerVisitor();
            echo "<script>alert('Visitante cadastrado com sucesso, realize o registro e libere a entrada!')</script>";
        }
        else if(strlen($_POST['cpf']) != 14 && $_POST['rg'] == ''){
            echo "<script>alert('Digite um CPF válido para realizar o cadastro!')</script>";
        }
        else if(strlen($_POST['rg']) < 8 && $_POST['cpf'] == ''){ 
            echo "<script>alert('Digite um RG válido para realizar o cadastro!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para realizar o cadastro!')</script>";
        }
        echo "<script> location.href = '/visitors' </script>";
    }

    public function registerEntry(){
        $this->validateAuthentication();
        if(strlen($_POST['cpf']) == 14 && strlen($_POST['rg']) >= 8){
            echo "<script>alert('Selecione apenas um documento para realizar o cadastro!')</script>";
        }
        else if(strlen($_POST['cpf']) == 14 && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $visitantes = Container::getModel('Visitors');

            $visitantes->cpf = $_POST['cpf'];

            if(isset($visitantes->selectDocumentByCPF()['id_visitante'])){ //Verifica se o visitante possui cadastro no sistema
                $visitantes->nome = $visitantes->selectDocumentByCPF()['nome'];
                $visitantes->documento = $visitantes->selectDocumentByCPF()['cpf'];
                $visitantes->apartamento = $_POST['apartamento'];
                $visitantes->bloco = $_POST['bloco'];
                $visitantes->fk_id_visitante = $visitantes->selectDocumentByCPF()['id_visitante'];

                if($visitantes->getAllVisitorsPresentsForCondition()){ //Verifica se o visitante está com a saída em aberto
                    echo "<script>alert('Esse visitante está presente no condomínio, para realizar o registro de entrada, primeiro é necessário registrar a saída')</script>";
                }
                else{
                    $visitantes->registerEntry();
                    echo "<script>alert('Entrada registrada com sucesso!')</script>";
                }
            }
            else{
                echo "<script>alert('Para realizar o registro de entrada, é necessário que o visitante esteja cadastrado no sistema!')</script>";
            }
        }
        else if(strlen($_POST['rg']) >= 8 && strlen($_POST['uf']) == 2 && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $visitantes = Container::getModel('Visitors');

            $visitantes->rg = $_POST['rg'];
            $visitantes->uf = $_POST['uf'];

            if(isset($visitantes->selectDocumentByRgAndUf()['id_visitante'])){ //Verifica se o visitante possui cadastro no sistema
                $visitantes->nome = $visitantes->selectDocumentByRgAndUf()['nome'];
                $visitantes->documento = $visitantes->selectDocumentByRgAndUf()['rg'];
                $visitantes->apartamento = $_POST['apartamento'];
                $visitantes->bloco = $_POST['bloco'];
                $visitantes->fk_id_visitante = $visitantes->selectDocumentByRgAndUf()['id_visitante'];

                if($visitantes->getAllVisitorsPresentsForCondition()){ //Verifica se o visitante está com a saída em aberto
                    echo "<script>alert('Esse visitante está presente no condomínio, para realizar o registro de entrada, primeiro é necessário registrar a saída')</script>";
                }
                else{
                    $visitantes->registerEntry();
                    echo "<script>alert('Entrada registrada com sucesso!')</script>";
                }
            }
            else{
                echo "<script>alert('Para realizar o registro de entrada, é necessário que o visitante esteja cadastrado no sistema!')</script>";
            }
        }
        else if(strlen($_POST['cpf']) != 14 && $_POST['rg'] == ''){
            echo "<script>alert('Digite um CPF válido para realizar o registro!')</script>";
        }
        else if(strlen($_POST['rg']) < 8 && $_POST['cpf'] == ''){ 
            echo "<script>alert('Digite um RG válido para realizar o registro!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para realizar o registro!')</script>";
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
        if(isset($_POST['fk_id_visitante'])){
            $visitantes = Container::getModel('Visitors');

            $visitantes->fk_id_visitante = $_POST['fk_id_visitante'];

            foreach($visitantes->getAllVisitorsRelations() as $e){
                $this->view->nome = $e['nome'];
                $this->view->uf = $e['uf'];
                $this->view->documento = $e['documento'];
            }
            $this->render('edit_visitors');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/visitors' </script>";
        }
    }

    public function updateVisitors(){
        $this->validateAuthentication();
        if($_POST['nome'] != '' && strlen($_POST['cpf']) == 14 && $_POST['apartamento'] != '' && $_POST['bloco'] != '' && $_POST['id_visitante'] != '' && $_POST['fk_id_visitante'] != ''){
            $visitantes = Container::getModel('Visitors');
            $moradores = Container::getModel('Residents');

            //Tratando duplicidade de CPF
            foreach($moradores->getAll() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    echo "<script>alert('Erro ao atualizar registro, um morador ja possui este CPF!')</script>";
                    echo "<script> location.href = '/visitors' </script>";
                    exit;
                }
            }

            $visitantes->id_visitante = $_POST['id_visitante'];
            $visitantes->fk_id_visitante = $_POST['fk_id_visitante'];
            $visitantes->nome = $_POST['nome'];
            $visitantes->cpf = $_POST['cpf'];
            $visitantes->rg = 'NA';
            $visitantes->uf = 'NA';
            $visitantes->documento = $_POST['cpf'];
            $visitantes->apartamento = $_POST['apartamento'];
            $visitantes->bloco = $_POST['bloco'];

            if($visitantes->updateVisitor()){ //Atualização de cadastro
                $visitantes->updateVisitorRegister(); //Atualização de registro único
                echo "<script>alert('Dados atualizados com sucesso!')</script>";
            }
            else{
                echo "<script>alert('Erro ao atualizar registro, um visitante ja possui este CPF!')</script>";
            }
        }
        else if($_POST['nome'] != '' && strlen($_POST['uf']) == 2 && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $visitantes = Container::getModel('Visitors');

            $visitantes->id_visitante = $_POST['id_visitante'];
            $visitantes->fk_id_visitante = $_POST['fk_id_visitante'];
            $visitantes->nome = $_POST['nome'];
            $visitantes->cpf = md5(date('Y-m-d H:i'));
            $visitantes->rg = $_POST['rg'];
            $visitantes->uf = $_POST['uf'];
            $visitantes->documento = $_POST['rg'];
            $visitantes->apartamento = $_POST['apartamento'];
            $visitantes->bloco = $_POST['bloco'];

            // Validando RG de acordo com o estado
            $valida_rg = false;
            if((($_POST['uf'] == "AC" || $_POST['uf'] == "AM" || $_POST['uf'] == "RO" || $_POST['uf'] == "RR" || $_POST['uf'] == "TO") && strlen($_POST['rg']) == 8) || (($_POST['uf'] == "AL" || $_POST['uf'] == "DF" || $_POST['uf'] == "ES" || $_POST['uf'] == "GO" || $_POST['uf'] == "MS" || $_POST['uf'] == "PB" || $_POST['uf'] == "SE" || $_POST['uf'] == "PI" || $_POST['uf'] == "RN") && strlen($_POST['rg']) == 9) || ($_POST['uf'] == "PE" && strlen($_POST['rg']) == 10) || (($_POST['uf'] == "MT" || $_POST['uf'] == "PR" || $_POST['uf'] == "SC") && strlen($_POST['rg']) == 11) || (($_POST['uf'] == "RJ" || $_POST['uf'] == "MA" || $_POST['uf'] == "SP" || $_POST['uf'] == "PA") && strlen($_POST['rg']) == 12) || ($_POST['uf'] == "BA" && strlen($_POST['rg']) == 13) || ($_POST['uf'] == "RS"  && strlen($_POST['rg']) == 14)){
                $valida_rg = true;
            }
            
            if($valida_rg){
                if($visitantes->updateVisitor()){ //Atualização de cadastro
                    $visitantes->updateVisitorRegister(); //Atualização de registro único
                    echo "<script>alert('Dados atualizados com sucesso!')</script>";
                }
                else{
                    echo "<script>alert('Erro ao atualizar registro, um visitante ja possui este RG!')</script>";
                }
            }
            else {
                echo "<script>alert('Digite um RG válido para atualizar o registro!')</script>";
            }
        }
        else if(strlen($_POST['cpf']) != 14 && $_POST['rg'] == ''){
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
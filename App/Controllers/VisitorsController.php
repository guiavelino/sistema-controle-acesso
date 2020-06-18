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
            
            $this->view->registros_entrada = $visitantes->getAllRegistersEntry();
            $this->view->visitantes_cadastrados = $visitantes->getAllVisitorsRegisters();
            $this->view->total_visitantes_presentes = $visitantes->getAllNumberVisitorsPresents()['visitantes_presentes'];
            $this->view->visitantes_presentes = $visitantes->getAllVisitorsPresents();

            $this->render('visitors_admin');
        }
        else{
            $visitantes = Container::getModel('Visitors');
            $this->view->registros_entrada = $visitantes->getAllRegistersEntry();
            $this->view->visitantes_cadastrados = $visitantes->getAllVisitorsRegisters();
            $this->view->total_visitantes_presentes = $visitantes->getAllNumberVisitorsPresents()['visitantes_presentes'];
            $this->view->visitantes_presentes = $visitantes->getAllVisitorsPresents();
            
            $this->render('visitors_user');
        }  
    }

    public function registerVisitor(){
        $this->validateAuthentication();
        if(strlen($_POST['cpf']) == 14 && strlen($_POST['rg']) > 0){
            echo "<script>alert('Selecione apenas um documento para realizar o cadastro!')</script>";
        }
        else if(strlen($_POST['cpf']) == 14 && $_POST['nome'] != ''){
            $visitantes = Container::getModel('Visitors');
            $moradores = Container::getModel('Residents');

            //Tratando duplicidade de CPF
            foreach($moradores->getAllResidentsRegisters() as $e){
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
            $visitantes->rg = '--';
            $visitantes->uf = '--';

            $visitantes->registerVisitor();
            echo "<script>alert('Visitante cadastrado com sucesso, realize o registro e libere a entrada!')</script>";
        }
        else if(strlen($_POST['rg']) > 0 && $_POST['nome'] != '' && strlen($_POST['uf']) == 2){
            $visitantes = Container::getModel('Visitors');

            //Tratando duplicidade de RG
            foreach($visitantes->getAllVisitorsRegisters() as $e){
                if($_POST['rg'] == $e['rg'] && $_POST['uf'] == $e['uf']){ 
                    echo "<script>alert('Esse visitante ja foi cadastrado, realize o registro e libere a entrada!')</script>";
                    echo "<script> location.href = '/visitors' </script>";
                    exit;
                }
            }

            $visitantes->nome = $_POST['nome'];
            $visitantes->cpf = md5(date('Y-m-d H:i:s')); // A coluna CPF no banco de dados utiliza o atributo UNIQUE, por isso um valor vazio não pode ser atribuído
            $visitantes->rg = $_POST['rg'];
            $visitantes->uf = $_POST['uf'];

            // Validando RG de acordo com o estado
            $valida_rg = true;
            // if((($_POST['uf'] == "AC" || $_POST['uf'] == "AM" || $_POST['uf'] == "RO" || $_POST['uf'] == "RR" || $_POST['uf'] == "TO") && strlen($_POST['rg']) == 8) || (($_POST['uf'] == "AL" || $_POST['uf'] == "DF" || $_POST['uf'] == "ES" || $_POST['uf'] == "GO" || $_POST['uf'] == "MS" || $_POST['uf'] == "PB" || $_POST['uf'] == "SE" || $_POST['uf'] == "PI" || $_POST['uf'] == "RN") && strlen($_POST['rg']) == 9) || ($_POST['uf'] == "PE" && strlen($_POST['rg']) == 10) || (($_POST['uf'] == "MT" || $_POST['uf'] == "PR" || $_POST['uf'] == "SC") && strlen($_POST['rg']) == 11) || (($_POST['uf'] == "RJ" || $_POST['uf'] == "MA" || $_POST['uf'] == "SP" || $_POST['uf'] == "PA") && strlen($_POST['rg']) == 12) || ($_POST['uf'] == "BA" && strlen($_POST['rg']) == 13) || ($_POST['uf'] == "RS"  && strlen($_POST['rg']) == 14)){
            //     $valida_rg = true;
            // }

            if($valida_rg){
                $visitantes->registerVisitor();
                echo "<script>alert('Visitante cadastrado com sucesso, realize o registro e libere a entrada!')</script>";
            }
            else {
                echo "<script>alert('Digite um RG válido para realizar o cadastro!')</script>";
            }
        }
        else if(strlen($_POST['cpf']) != 14 && $_POST['rg'] == ''){
            echo "<script>alert('Digite um CPF válido para realizar o cadastro!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para realizar o cadastro!')</script>";
        }
        echo "<script> location.href = '/visitors' </script>";
    }

    public function registerEntry(){
        $this->validateAuthentication();
        if(strlen($_POST['cpf']) == 14 && strlen($_POST['rg']) > 0){
            echo "<script>alert('Selecione apenas um documento para realizar o cadastro!')</script>";
        }
        else if(strlen($_POST['cpf']) == 14 && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $visitantes = Container::getModel('Visitors');
            $visitantes->cpf = $_POST['cpf'];
            
            if(isset($visitantes->selectDocumentByCpfRgAndUF()['id_visitante'])){ //Verifica se o visitante possui cadastro no sistema
                $visitantes->nome = $visitantes->selectDocumentByCpfRgAndUF()['nome'];
                $visitantes->cpf_rg = $visitantes->selectDocumentByCpfRgAndUF()['cpf'];
                $visitantes->uf = $visitantes->selectDocumentByCpfRgAndUF()['uf'];
                $visitantes->apartamento = $_POST['apartamento'];
                $visitantes->bloco = $_POST['bloco'];
                $visitantes->fk_id_visitante = $visitantes->selectDocumentByCpfRgAndUF()['id_visitante'];

                if($visitantes->getAllVisitorsPresentsForCondition()){ //Verifica se o visitante está com a saída em aberto
                    echo "<script>alert('Esse visitante está presente no condomínio, para realizar o registro de entrada, primeiro é necessário registrar a saída!')</script>";
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
        else if(strlen($_POST['rg']) > 0 && strlen($_POST['uf']) == 2 && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $visitantes = Container::getModel('Visitors');
            $visitantes->rg = $_POST['rg'];
            $visitantes->uf = $_POST['uf'];

            // Validando RG de acordo com o estado
            $valida_rg = true;
            // if((($_POST['uf'] == "AC" || $_POST['uf'] == "AM" || $_POST['uf'] == "RO" || $_POST['uf'] == "RR" || $_POST['uf'] == "TO") && strlen($_POST['rg']) == 8) || (($_POST['uf'] == "AL" || $_POST['uf'] == "DF" || $_POST['uf'] == "ES" || $_POST['uf'] == "GO" || $_POST['uf'] == "MS" || $_POST['uf'] == "PB" || $_POST['uf'] == "SE" || $_POST['uf'] == "PI" || $_POST['uf'] == "RN") && strlen($_POST['rg']) == 9) || ($_POST['uf'] == "PE" && strlen($_POST['rg']) == 10) || (($_POST['uf'] == "MT" || $_POST['uf'] == "PR" || $_POST['uf'] == "SC") && strlen($_POST['rg']) == 11) || (($_POST['uf'] == "RJ" || $_POST['uf'] == "MA" || $_POST['uf'] == "SP" || $_POST['uf'] == "PA") && strlen($_POST['rg']) == 12) || ($_POST['uf'] == "BA" && strlen($_POST['rg']) == 13) || ($_POST['uf'] == "RS"  && strlen($_POST['rg']) == 14)){
            //     $valida_rg = true;
            // }

            if($valida_rg){
                if(isset($visitantes->selectDocumentByCpfRgAndUF()['id_visitante'])){ //Verifica se o visitante possui cadastro no sistema
                    $visitantes->nome = $visitantes->selectDocumentByCpfRgAndUF()['nome'];
                    $visitantes->cpf_rg = $visitantes->selectDocumentByCpfRgAndUF()['rg'];
                    $visitantes->apartamento = $_POST['apartamento'];
                    $visitantes->bloco = $_POST['bloco'];
                    $visitantes->fk_id_visitante = $visitantes->selectDocumentByCpfRgAndUF()['id_visitante'];
    
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
            else {
                echo "<script>alert('Digite um RG válido para realizar o registro!')</script>";
            }
        }
        else if(strlen($_POST['cpf']) != 14 && $_POST['rg'] == ''){
            echo "<script>alert('Digite um CPF válido para realizar o registro!')</script>";
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

    // Visitantes cadastrados
    public function editVisitor(){
        $this->validateAuthentication();
        if(isset($_POST['id_visitante'])){
            $this->render('edit_visitor');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/visitors' </script>";
        }
    }

    public function updateVisitor(){
        $this->validateAuthentication();
        if($_POST['nome'] != '' && strlen($_POST['cpf']) == 14 && $_POST['id_visitante'] != ''){
            $visitantes = Container::getModel('Visitors');
            $moradores = Container::getModel('Residents');

            //Tratando duplicidade de CPF
            foreach($moradores->getAllResidentsRegisters() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    echo "<script>alert('Erro ao atualizar cadastro, um morador ja possui este CPF!')</script>";
                    echo "<script> location.href = '/visitors' </script>";
                    exit;
                }
            }

            $visitantes->id_visitante = $_POST['id_visitante'];
            $visitantes->nome = $_POST['nome'];
            $visitantes->cpf = $_POST['cpf'];
            $visitantes->rg = '--';
            $visitantes->uf = '--';
            $visitantes->cpf_rg = $_POST['cpf'];

            if($visitantes->updateVisitor()){ 
                echo "<script>alert('Dados atualizados com sucesso!')</script>";
            }
            else{
                echo "<script>alert('Erro ao atualizar cadastro, um visitante ja possui este CPF!')</script>";
            }
        }
        else if($_POST['nome'] != '' && strlen($_POST['rg']) > 0 && strlen($_POST['uf']) == 2 && $_POST['id_visitante'] != ''){
            $visitantes = Container::getModel('Visitors');

            //Tratando duplicidade de RG
            foreach($visitantes->getAllVisitorsRegisters() as $e){
                if($_POST['rg'] == $e['rg'] && $_POST['uf'] == $e['uf'] && $_POST['id_visitante'] != $e['id_visitante']){ 
                    echo "<script>alert('Erro ao atualizar cadastro, um visitante ja possui este RG!')</script>";
                    echo "<script> location.href = '/visitors' </script>";
                    exit;
                }
            }

            $visitantes->id_visitante = $_POST['id_visitante'];
            $visitantes->nome = $_POST['nome'];
            $visitantes->cpf = md5(date('Y-m-d H:i'));
            $visitantes->rg = $_POST['rg'];
            $visitantes->uf = $_POST['uf'];
            $visitantes->cpf_rg = $_POST['rg'];

            // Validando RG de acordo com o estado
            $valida_rg = true;
            // if((($_POST['uf'] == "AC" || $_POST['uf'] == "AM" || $_POST['uf'] == "RO" || $_POST['uf'] == "RR" || $_POST['uf'] == "TO") && strlen($_POST['rg']) == 8) || (($_POST['uf'] == "AL" || $_POST['uf'] == "DF" || $_POST['uf'] == "ES" || $_POST['uf'] == "GO" || $_POST['uf'] == "MS" || $_POST['uf'] == "PB" || $_POST['uf'] == "SE" || $_POST['uf'] == "PI" || $_POST['uf'] == "RN") && strlen($_POST['rg']) == 9) || ($_POST['uf'] == "PE" && strlen($_POST['rg']) == 10) || (($_POST['uf'] == "MT" || $_POST['uf'] == "PR" || $_POST['uf'] == "SC") && strlen($_POST['rg']) == 11) || (($_POST['uf'] == "RJ" || $_POST['uf'] == "MA" || $_POST['uf'] == "SP" || $_POST['uf'] == "PA") && strlen($_POST['rg']) == 12) || ($_POST['uf'] == "BA" && strlen($_POST['rg']) == 13) || ($_POST['uf'] == "RS"  && strlen($_POST['rg']) == 14)){
            //     $valida_rg = true;
            // }
            
            if($valida_rg){
                $visitantes->updateVisitor();
                echo "<script>alert('Dados atualizados com sucesso!')</script>";
            }
            else {
                echo "<script>alert('Digite um RG válido para atualizar o cadastro!')</script>";
            }
        }
        else if(strlen($_POST['cpf']) != 14 && $_POST['rg'] == ''){
            echo "<script>alert('Digite um CPF válido para atualizar o cadastro!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para atualizar o cadastro!')</script>";
        }
        echo "<script> location.href = '/visitors' </script>";
    }

    // Registros de entrada
    public function editVisitorEntry(){
        $this->validateAuthentication();
        if(isset($_POST['id_visitante'])){
            $this->render('edit_visitor_entry');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/visitors' </script>";
        }
    }

    public function updateVisitorEntry(){
        $this->validateAuthentication();
        if($_POST['id_visitante'] != '' && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $visitantes = Container::getModel('Visitors');

            $visitantes->id_visitante = $_POST['id_visitante'];
            $visitantes->apartamento = $_POST['apartamento'];
            $visitantes->bloco = $_POST['bloco'];

            $visitantes->updateVisitorEntry();
            echo "<script>alert('Dados atualizados com sucesso!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para atualizar o registro!')</script>";
        }
        echo "<script> location.href = '/visitors' </script>";
    }

    // Visitantes cadastrados
    public function removeVisitor(){
        $this->validateAuthentication();
        if(isset($_POST['id_visitante'])){
            $this->render('remove_visitor');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/visitors' </script>";
        }
    }

    public function deleteVisitor(){
        $this->validateAuthentication();
        if(isset($_POST['id_visitante'])){
            $visitantes = Container::getModel('Visitors');
            $visitantes->id_visitante = $_POST['id_visitante'];
            $visitantes->deleteVisitor();
            echo "<script>alert('Cadastro excluído com sucesso!')</script>";
        }
        echo "<script> location.href = '/visitors' </script>";
    }

    // Registros de  entrada
    public function removeVisitorEntry(){
        $this->validateAuthentication();
        if(isset($_POST['id_visitante'])){
            $this->render('remove_visitor_entry');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/visitors' </script>";
        }
    }

    public function deleteVisitorEntry(){
        $this->validateAuthentication();
        if(isset($_POST['id_visitante'])){
            $visitantes = Container::getModel('Visitors');
            $visitantes->id_visitante = $_POST['id_visitante'];
            $visitantes->deleteVisitorEntry();
            echo "<script>alert('Registro excluído com sucesso!')</script>";
        }
        echo "<script> location.href = '/visitors' </script>";
    }

    public function exportVisitors(){
        $this->validateAuthentication();
        $visitantes = Container::getModel('Visitors');
        $arquivo = '';

        // Formatando estilo da tabela
        $style_first_header = "height: 60px; font-size:22px; text-align:center; background-color:#1EA39C; color:#FFFFFF; display:table-cell; vertical-align:middle;";
        $style_second_header_name = "height: 45px; width: 300px; text-align:center; background-color:#F7F7F7; display:table-cell; vertical-align:middle;";
        $style_second_header = "height: 45px; width: 200px; text-align:center; background-color:#F7F7F7; display:table-cell; vertical-align:middle;";
        $style_titile_header = "font-size:20px";
        $style_content = "height:32px; text-align:center; font-size:20;  display:table-cell; vertical-align:middle";

        if($_POST['categoria'] == 'Cadastros' && $_POST['periodo'] == 'Todo o período' && $_POST['data-inicio'] == '' && $_POST['data-fim'] == ''){
            // Definindo o nome do arquivo que será exportado
            $arquivo = 'relacao_visitantes_cadastrados.xls';

            // Criando uma tabela HTML com o formato da planilha
            $html = '';
            $html .= '<meta charset="utf-8"/>';
            $html .= '<table border="1">';
            $html .= "<tr>";
            $html .= "<td colspan='4' style='$style_first_header'><h2>Relação de visitantes cadastrados</h2></td>";
            $html .= "</tr>";
            $html .= '<tr>';
            $html .= "<td style='$style_second_header_name'><h4 style='$style_titile_header'>Nome</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>CPF</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>RG</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>UF</h4></td>";
            $html .= '</tr>';
            foreach($visitantes->getAllVisitorsRegisters() as $e){
                if(strpos($e["cpf"], '.')){
                    $cpf = $e["cpf"];
                }
                else{
                    $cpf = '--';
                }
                $html .= "<tr style='$style_content'>";
                $html .= '<td>'.$e["nome"].'</td>';
                $html .= '<td>'.$cpf.'</td>';
                $html .= '<td>'.$e["rg"].'</td>';
                $html .= '<td>'.$e["uf"].'</td>';
                $html .= '</tr>';
            }
        }  
        else if($_POST['categoria'] == 'Registros' && $_POST['periodo'] == 'Todo o período' && $_POST['data-inicio'] == '' && $_POST['data-fim'] == ''){
            // Definindo o nome do arquivo que será exportado
            $arquivo = 'relacao_entrada_visitantes.xls';

            // Criando uma tabela HTML com o formato da planilha
            $html = '';
            $html .= '<meta charset="utf-8"/>';
            $html .= '<table border="1">';
            $html .= "<tr>";
            $html .= "<td colspan='7' style='$style_first_header'><h2>Registros de entrada de visitantes</h2></td>";
            $html .= "</tr>";
            $html .= '<tr>';
            $html .= "<td style='$style_second_header_name'><h4 style='$style_titile_header'>Nome</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>CPF / RG</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>UF</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Apartamento</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Bloco</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Entrada</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Saída</h4></td>";
            $html .= '</tr>';
            foreach($visitantes->getAllRegistersEntry() as $e){
                if (!isset($e['data_saida'])) {
                    $data_saida = 'Saída em aberto';
                } else {
                    $data_saida = date('d/m/Y H:i', strtotime($e['data_saida']));
                }

                $html .= "<tr style='$style_content'>";
                $html .= '<td>'.$e["nome"].'</td>';
                $html .= '<td>'.$e["cpf_rg"].'</td>';
                $html .= '<td>'.$e["uf"].'</td>';
                $html .= '<td>'.$e['apartamento'].'</td>';
                $html .= '<td>'.$e['bloco'].'</td>';
                $html .= '<td>'.date('d/m/Y H:i', strtotime($e['data_entrada'])).'</td>';
                $html .= '<td>'.$data_saida.'</td>';
                $html .= '</tr>';
            }
        } 
        else if($_POST['categoria'] == 'Registros' && $_POST['periodo'] == 'Personalizado' && strlen($_POST['data-inicio']) == 10 && strlen($_POST['data-fim']) == 10){
            // Definindo o nome do arquivo que será exportado
            $arquivo = 'relacao_entrada_visitantes.xls';

            $visitantes->data_inicio = $_POST['data-inicio'];
            $visitantes->data_fim = $_POST['data-fim'];

            $data_inicio = date('d/m/Y', strtotime($_POST['data-inicio']));
            $data_fim = date('d/m/Y', strtotime($_POST['data-fim']));

            // Criando uma tabela HTML com o formato da planilha
            $html = '';
            $html .= '<meta charset="utf-8"/>';
            $html .= '<table border="1">';
            $html .= "<tr>";
            $html .= "<td colspan='7' style='$style_first_header'><h2>Registros de entrada de visitantes - $data_inicio até $data_fim</h2></td>";
            $html .= "</tr>";
            $html .= '<tr>';
            $html .= "<td style='$style_second_header_name'><h4 style='$style_titile_header'>Nome</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>CPF / RG</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>UF</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Apartamento</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Bloco</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Entrada</h4></td>";
            $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Saída</h4></td>";
            $html .= '</tr>';
            foreach($visitantes->getAllRegistersEntryFilter() as $e){
                if (!isset($e['data_saida'])) {
                    $data_saida = 'Saída em aberto';
                } else {
                    $data_saida = date('d/m/Y H:i', strtotime($e['data_saida']));
                }

                $html .= "<tr style='$style_content'>";
                $html .= '<td>'.$e["nome"].'</td>';
                $html .= '<td>'.$e["cpf_rg"].'</td>';
                $html .= '<td>'.$e["uf"].'</td>';
                $html .= '<td>'.$e['apartamento'].'</td>';
                $html .= '<td>'.$e['bloco'].'</td>';
                $html .= '<td>'.date('d/m/Y H:i', strtotime($e['data_entrada'])).'</td>';
                $html .= '<td>'.$data_saida.'</td>';
                $html .= '</tr>';
            }
        } 
        else if($_POST['categoria'] == 'Registros' && $_POST['periodo'] == 'Personalizado' && (strlen($_POST['data-inicio']) != 10 || strlen($_POST['data-fim']) != 10)){
            echo "<script>
                alert('Digite uma data válida para exportar os registros!');
                location.href = '/visitors'
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
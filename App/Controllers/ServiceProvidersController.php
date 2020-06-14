<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class ServiceProvidersController extends Action {

    public function validateAuthentication(){
        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == null){
            header('Location: /?login=erro');
        }
    }

	public function serviceProviders(){
        $this->validateAuthentication();
        if($_SESSION['nivel_acesso'] == 'administrador'){
            $prestadores_servicos = Container::getModel('ServiceProviders');
            
            $this->view->registros_entrada = $prestadores_servicos->getAllRegistersEntry();
            $this->view->prestadores_servicos_cadastrados = $prestadores_servicos->getAllServiceProvidersRegisters();
            $this->view->total_prestadores_servicos_presentes = $prestadores_servicos->getAllNumberServiceProvidersPresents()['prestadores_servicos_presentes'];
            $this->view->prestadores_servicos_presentes = $prestadores_servicos->getAllServiceProvidersPresents();

            $this->render('service_providers_admin');
        }
        else{
            $prestadores_servicos = Container::getModel('ServiceProviders');
            $this->view->registros_entrada = $prestadores_servicos->getAllRegistersEntry();
            $this->render('service_providers_user');
        }  
    }

    public function registerServiceProvider(){
        $this->validateAuthentication();
        if(strlen($_POST['cpf']) == 14 && strlen($_POST['rg']) > 0){
            echo "<script>alert('Selecione apenas um documento para realizar o cadastro!')</script>";
        }
        else if(strlen($_POST['cpf']) == 14 && $_POST['nome'] != ''){
            $prestadores_servicos = Container::getModel('ServiceProviders');
            
            foreach($prestadores_servicos->getAllServiceProvidersRegisters() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    echo "<script>alert('Esse prestador de serviço ja foi cadastrado, realize o registro e libere a entrada!')</script>";
                    echo "<script> location.href = '/service_providers' </script>";
                    exit;
                }
            }
            
            $prestadores_servicos->nome = $_POST['nome'];
            $prestadores_servicos->cpf = $_POST['cpf'];
            $prestadores_servicos->rg = '--';
            $prestadores_servicos->uf = '--';
            $prestadores_servicos->registerServiceProvider();
            echo "<script>alert('Prestador de serviço cadastrado com sucesso, realize o registro e libere a entrada!')</script>";
        }
        else if(strlen($_POST['rg']) > 0 && $_POST['nome'] != '' && strlen($_POST['uf']) == 2){
            $prestadores_servicos = Container::getModel('ServiceProviders');

            //Tratando duplicidade de RG
            foreach($prestadores_servicos->getAllServiceProvidersRegisters() as $e){
                if($_POST['rg'] == $e['rg'] && $_POST['uf'] == $e['uf']){ 
                    echo "<script>alert('Esse prestador de serviço ja foi cadastrado, realize o registro e libere a entrada!')</script>";
                    echo "<script> location.href = '/service_providers' </script>";
                    exit;
                }
            }

            $prestadores_servicos->nome = $_POST['nome'];
            $prestadores_servicos->cpf = md5(date('Y-m-d H:i:s')); // A coluna CPF no banco de dados utiliza o atributo UNIQUE, por isso um valor vazio não pode ser atribuído
            $prestadores_servicos->rg = $_POST['rg'];
            $prestadores_servicos->uf = $_POST['uf'];

            // Validando RG de acordo com o estado
            $valida_rg = true;
            // if((($_POST['uf'] == "AC" || $_POST['uf'] == "AM" || $_POST['uf'] == "RO" || $_POST['uf'] == "RR" || $_POST['uf'] == "TO") && strlen($_POST['rg']) == 8) || (($_POST['uf'] == "AL" || $_POST['uf'] == "DF" || $_POST['uf'] == "ES" || $_POST['uf'] == "GO" || $_POST['uf'] == "MS" || $_POST['uf'] == "PB" || $_POST['uf'] == "SE" || $_POST['uf'] == "PI" || $_POST['uf'] == "RN") && strlen($_POST['rg']) == 9) || ($_POST['uf'] == "PE" && strlen($_POST['rg']) == 10) || (($_POST['uf'] == "MT" || $_POST['uf'] == "PR" || $_POST['uf'] == "SC") && strlen($_POST['rg']) == 11) || (($_POST['uf'] == "RJ" || $_POST['uf'] == "MA" || $_POST['uf'] == "SP" || $_POST['uf'] == "PA") && strlen($_POST['rg']) == 12) || ($_POST['uf'] == "BA" && strlen($_POST['rg']) == 13) || ($_POST['uf'] == "RS"  && strlen($_POST['rg']) == 14)){
            //     $valida_rg = true;
            // }

            if($valida_rg){
                $prestadores_servicos->registerServiceProvider();
                echo "<script>alert('Prestador de serviço cadastrado com sucesso, realize o registro e libere a entrada!')</script>";
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
        echo "<script> location.href = '/service_providers' </script>";
    }

    public function registerEntry(){
        $this->validateAuthentication();
        if(strlen($_POST['cpf']) == 14 && strlen($_POST['rg']) > 0){
            echo "<script>alert('Selecione apenas um documento para realizar o cadastro!')</script>";
        }
        else if(strlen($_POST['cpf']) == 14 && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $prestadores_servicos = Container::getModel('ServiceProviders');
            $prestadores_servicos->cpf = $_POST['cpf'];
            
            if(isset($prestadores_servicos->selectDocumentByCpfRgAndUF()['id_prestador_servico'])){ 
                $prestadores_servicos->nome = $prestadores_servicos->selectDocumentByCpfRgAndUF()['nome'];
                $prestadores_servicos->cpf_rg = $prestadores_servicos->selectDocumentByCpfRgAndUF()['cpf'];
                $prestadores_servicos->uf = $prestadores_servicos->selectDocumentByCpfRgAndUF()['uf'];
                $prestadores_servicos->apartamento = $_POST['apartamento'];
                $prestadores_servicos->bloco = $_POST['bloco'];
                $prestadores_servicos->fk_id_prestador_servico = $prestadores_servicos->selectDocumentByCpfRgAndUF()['id_prestador_servico'];

                if($prestadores_servicos->getAllServiceProvidersPresentsForCondition()){
                    echo "<script>alert('Esse prestador de serviço está presente no condomínio, para realizar o registro de entrada, primeiro é necessário registrar a saída!')</script>";
                }
                else{
                    $prestadores_servicos->registerEntry();
                    echo "<script>alert('Entrada registrada com sucesso!')</script>";
                }
            }
            else{
                echo "<script>alert('Para realizar o registro de entrada, é necessário que o prestador de serviço esteja cadastrado no sistema!')</script>";
            }
        }
        else if(strlen($_POST['rg']) > 0 && strlen($_POST['uf']) == 2 && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $prestadores_servicos = Container::getModel('ServiceProviders');
            $prestadores_servicos->rg = $_POST['rg'];
            $prestadores_servicos->uf = $_POST['uf'];

            // Validando RG de acordo com o estado
            $valida_rg = true;
            // if((($_POST['uf'] == "AC" || $_POST['uf'] == "AM" || $_POST['uf'] == "RO" || $_POST['uf'] == "RR" || $_POST['uf'] == "TO") && strlen($_POST['rg']) == 8) || (($_POST['uf'] == "AL" || $_POST['uf'] == "DF" || $_POST['uf'] == "ES" || $_POST['uf'] == "GO" || $_POST['uf'] == "MS" || $_POST['uf'] == "PB" || $_POST['uf'] == "SE" || $_POST['uf'] == "PI" || $_POST['uf'] == "RN") && strlen($_POST['rg']) == 9) || ($_POST['uf'] == "PE" && strlen($_POST['rg']) == 10) || (($_POST['uf'] == "MT" || $_POST['uf'] == "PR" || $_POST['uf'] == "SC") && strlen($_POST['rg']) == 11) || (($_POST['uf'] == "RJ" || $_POST['uf'] == "MA" || $_POST['uf'] == "SP" || $_POST['uf'] == "PA") && strlen($_POST['rg']) == 12) || ($_POST['uf'] == "BA" && strlen($_POST['rg']) == 13) || ($_POST['uf'] == "RS"  && strlen($_POST['rg']) == 14)){
            //     $valida_rg = true;
            // }

            if($valida_rg){
                if(isset($prestadores_servicos->selectDocumentByCpfRgAndUF()['id_prestador_servico'])){ 
                    $prestadores_servicos->nome = $prestadores_servicos->selectDocumentByCpfRgAndUF()['nome'];
                    $prestadores_servicos->cpf_rg = $prestadores_servicos->selectDocumentByCpfRgAndUF()['rg'];
                    $prestadores_servicos->apartamento = $_POST['apartamento'];
                    $prestadores_servicos->bloco = $_POST['bloco'];
                    $prestadores_servicos->fk_id_prestador_servico = $prestadores_servicos->selectDocumentByCpfRgAndUF()['id_prestador_servico'];
    
                    if($prestadores_servicos->getAllServiceProvidersPresentsForCondition()){ 
                        echo "<script>alert('Esse prestador de serviço está presente no condomínio, para realizar o registro de entrada, primeiro é necessário registrar a saída!')</script>";
                    }
                    else{
                        $prestadores_servicos->registerEntry();
                        echo "<script>alert('Entrada registrada com sucesso!')</script>";
                    }
                }
                else{
                    echo "<script>alert('Para realizar o registro de entrada, é necessário que o prestador de serviço esteja cadastrado no sistema!')</script>";
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
        echo "<script> location.href = '/service_providers' </script>";
    }

    public function registerExit(){
        $this->validateAuthentication();
        if(isset($_POST['id_prestador_servico'])){
            $prestadores_servicos = Container::getModel('ServiceProviders');
            $prestadores_servicos->id_prestador_servico = $_POST['id_prestador_servico'];
            $prestadores_servicos->data_saida = date('Y-m-d H:i:s');
            $prestadores_servicos->registerExit();
            echo "<script>alert('Saída registrada!')</script>";
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
        }
        echo "<script> location.href = '/service_providers' </script>";
    }

    public function editServiceProvider(){
        $this->validateAuthentication();
        if(isset($_POST['id_prestador_servico'])){
            $this->render('edit_service_provider');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/service_providers' </script>";
        }
    }

    public function updateServiceProvider(){
        $this->validateAuthentication();
        if($_POST['nome'] != '' && strlen($_POST['cpf']) == 14 && $_POST['id_prestador_servico'] != ''){
            $prestadores_servicos = Container::getModel('ServiceProviders');

            $prestadores_servicos->id_prestador_servico = $_POST['id_prestador_servico'];
            $prestadores_servicos->nome = $_POST['nome'];
            $prestadores_servicos->cpf = $_POST['cpf'];
            $prestadores_servicos->rg = '--';
            $prestadores_servicos->uf = '--';
            $prestadores_servicos->cpf_rg = $_POST['cpf'];

            if($prestadores_servicos->updateServiceProvider()){ 
                echo "<script>alert('Dados atualizados com sucesso!')</script>";
            }
            else{
                echo "<script>alert('Erro ao atualizar cadastro, um prestador de serviço ja possui este CPF!')</script>";
            }
        }
        else if($_POST['nome'] != '' && strlen($_POST['rg']) > 0 && strlen($_POST['uf']) == 2 && $_POST['id_prestador_servico'] != ''){
            $prestadores_servicos = Container::getModel('ServiceProviders');

            //Tratando duplicidade de RG
            foreach($prestadores_servicos->getAllServiceProvidersRegisters() as $e){
                if($_POST['rg'] == $e['rg'] && $_POST['uf'] == $e['uf'] && $_POST['id_prestador_servico'] != $e['id_prestador_servico']){ 
                    echo "<script>alert('Erro ao atualizar cadastro, um prestador de serviço ja possui este RG!')</script>";
                    echo "<script> location.href = '/service_providers' </script>";
                    exit;
                }
            }

            $prestadores_servicos->id_prestador_servico = $_POST['id_prestador_servico'];
            $prestadores_servicos->nome = $_POST['nome'];
            $prestadores_servicos->cpf = md5(date('Y-m-d H:i'));
            $prestadores_servicos->rg = $_POST['rg'];
            $prestadores_servicos->uf = $_POST['uf'];
            $prestadores_servicos->cpf_rg = $_POST['rg'];

            // Validando RG de acordo com o estado
            $valida_rg = true;
            // if((($_POST['uf'] == "AC" || $_POST['uf'] == "AM" || $_POST['uf'] == "RO" || $_POST['uf'] == "RR" || $_POST['uf'] == "TO") && strlen($_POST['rg']) == 8) || (($_POST['uf'] == "AL" || $_POST['uf'] == "DF" || $_POST['uf'] == "ES" || $_POST['uf'] == "GO" || $_POST['uf'] == "MS" || $_POST['uf'] == "PB" || $_POST['uf'] == "SE" || $_POST['uf'] == "PI" || $_POST['uf'] == "RN") && strlen($_POST['rg']) == 9) || ($_POST['uf'] == "PE" && strlen($_POST['rg']) == 10) || (($_POST['uf'] == "MT" || $_POST['uf'] == "PR" || $_POST['uf'] == "SC") && strlen($_POST['rg']) == 11) || (($_POST['uf'] == "RJ" || $_POST['uf'] == "MA" || $_POST['uf'] == "SP" || $_POST['uf'] == "PA") && strlen($_POST['rg']) == 12) || ($_POST['uf'] == "BA" && strlen($_POST['rg']) == 13) || ($_POST['uf'] == "RS"  && strlen($_POST['rg']) == 14)){
            //     $valida_rg = true;
            // }
            
            if($valida_rg){
                $prestadores_servicos->updateServiceProvider();
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
        echo "<script> location.href = '/service_providers' </script>";
    }

    public function editServiceProviderEntry(){
        $this->validateAuthentication();
        if(isset($_POST['id_prestador_servico'])){
            $this->render('edit_service_provider_entry');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/service_providers' </script>";
        }
    }

    public function updateServiceProviderEntry(){
        $this->validateAuthentication();
        if($_POST['id_prestador_servico'] != '' && $_POST['apartamento'] != '' && $_POST['bloco'] != ''){
            $prestadores_servicos = Container::getModel('ServiceProviders');

            $prestadores_servicos->id_prestador_servico = $_POST['id_prestador_servico'];
            $prestadores_servicos->apartamento = $_POST['apartamento'];
            $prestadores_servicos->bloco = $_POST['bloco'];

            $prestadores_servicos->updateServiceProviderEntry();
            echo "<script>alert('Dados atualizados com sucesso!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para atualizar o registro!')</script>";
        }
        echo "<script> location.href = '/service_providers' </script>";
    }

    public function removeServiceProvider(){
        $this->validateAuthentication();
        if(isset($_POST['id_prestador_servico'])){
            $this->render('remove_service_provider');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/service_providers' </script>";
        }
    }

    public function deleteServiceProvider(){
        $this->validateAuthentication();
        if(isset($_POST['id_prestador_servico'])){
            $prestadores_servicos = Container::getModel('ServiceProviders');
            $prestadores_servicos->id_prestador_servico = $_POST['id_prestador_servico'];
            $prestadores_servicos->deleteServiceProvider();
            echo "<script>alert('Cadastro excluído com sucesso!')</script>";
        }
        echo "<script> location.href = '/service_providers' </script>";
    }

    public function removeServiceProviderEntry(){
        $this->validateAuthentication();
        if(isset($_POST['id_prestador_servico'])){
            $this->render('remove_service_provider_entry');
        }
        else{
            echo "<script>alert('Selecione um registro para continuar!')</script>";
            echo "<script> location.href = '/service_providers' </script>";
        }
    }

    public function deleteServiceProviderEntry(){
        $this->validateAuthentication();
        if(isset($_POST['id_prestador_servico'])){
            $prestadores_servicos = Container::getModel('ServiceProviders');
            $prestadores_servicos->id_prestador_servico = $_POST['id_prestador_servico'];
            $prestadores_servicos->deleteServiceProviderEntry();
            echo "<script>alert('Registro excluído com sucesso!')</script>";
        }
        echo "<script> location.href = '/service_providers' </script>";
    }

    public function exportServiceProviders(){
        $this->validateAuthentication();
        $prestadores_servicos = Container::getModel('ServiceProviders');

        // Definindo o nome do arquivo que será exportado
		$arquivo = 'relacao_prestadores_servicos.xls';
        
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
		$html .= "<td colspan='6' style='$style_first_header'><h2>Prestadores de serviços</h2></td>";
		$html .= "</tr>";
		$html .= '<tr>';
		$html .= "<td style='$style_second_header_name'><h4 style='$style_titile_header'>Nome</h4></td>";
		$html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>CPF</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Apartamento</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Bloco</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Entrada</h4></td>";
        $html .= "<td style='$style_second_header'><h4 style='$style_titile_header'>Saída</h4></td>";
		$html .= '</tr>';
        foreach($prestadores_servicos->getAll() as $prestadores_servicos){
            $html .= "<tr style='$style_content'>";
			$html .= '<td>'.$prestadores_servicos["nome"].'</td>';
			$html .= '<td>'.$prestadores_servicos["cpf"].'</td>';
            $html .= '<td>'.$prestadores_servicos['apartamento'].'</td>';
            $html .= '<td>'.$prestadores_servicos['bloco'].'</td>';
            $html .= '<td>'.date('d/m/Y H:i', strtotime($prestadores_servicos['data_entrada'])).'</td>';
            $html .= '<td>'.date('d/m/Y H:i', strtotime($prestadores_servicos['data_saida'])).'</td>';
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
<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class LeisureAreasController extends Action {

    public function validateAuthentication(){
        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == null){
            header('Location: /?login=erro');
        }
    }

	public function leisureAreas(){
        $this->validateAuthentication();
        if($_SESSION['nivel_acesso'] == 'administrador'){           
            $this->render('leisure_areas');
        }
        else{
            echo "<script>
                alert('Essa página é restrita para administradores!');
                location.href = '/dashboard'
            </script>";
        }  
    } 

    public function leisureAreasUser(){
        $this->validateAuthentication();
        if($_SESSION['nivel_acesso'] != 'administrador'){   
            $events = Container::getModel('Events');
            $this->view->event =  $events->getAllEvents();        
            $this->render('leisure_areas_user');
        }
        else{
            echo "<script>
                alert('Essa página é apenas para usuários!');
                location.href = '/dashboard'
            </script>";
        }  
    } 

    public function registerEvent(){
        $this->validateAuthentication();
        if(strlen($_POST['cpf']) == 14 && $_POST['titulo_evento'] != '' && $_POST['inicio_evento'] != '' && $_POST['fim_evento'] != ''){
            $moradores = Container::getModel('Residents');

            $morador_existente = false;
            $fk_id_morador = false;

            foreach($moradores->getAllResidentsRegisters() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    $morador_existente = true;
                    $fk_id_morador = $e['id_morador'];
                }
            }

            if($morador_existente && $fk_id_morador != false){
                $eventos = Container::getModel('Events');
                $eventos->fk_id_morador = $fk_id_morador;
                $eventos->cpf = $_POST['cpf'];
                $eventos->titulo_evento = $_POST['titulo_evento'];
                $inicio_evento = str_replace('/', '-', $_POST['inicio_evento']);
                $eventos->inicio_evento = date("Y-m-d H:i:s", strtotime($inicio_evento));
                $fim_evento = str_replace('/', '-', $_POST['fim_evento']);
                $eventos->fim_evento = date("Y-m-d H:i:s", strtotime($fim_evento));
                $eventos->cor = "#436EEE"; //Azul
                $eventos->registerEvent();
                echo "<script>alert('Evento agendado com sucesso!')</script>";
            }
            else{
                echo "<script>alert('Apenas moradores podem agendar eventos!')</script>";
            }
        }
        else if(strlen($_POST['cpf']) != 14){
            echo "<script>alert('Digite um CPF válido para realizar o agendamento!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para realizar o agendamento!')</script>";
        }
        echo "<script>location.href = '/leisure_areas'</script>";
    }

    public function event(){
        $this->validateAuthentication();
        $eventos = Container::getModel('Events');
        $eventos->id_evento = $_GET['id_evento'];

        foreach($eventos->getAllEventsRegisters() as $e){
            $eventos->fk_id_morador = $e['fk_id_morador'];
        }

        $this->view->eventos = $eventos->getAllEventAndResidentData();
        $this->render('view_event');
    }

    public function deleteEvent(){
        $this->validateAuthentication();
        if(isset($_POST['id_evento'])){
            $eventos = Container::getModel('Events');
            $eventos->id_evento = $_POST['id_evento'];
            $eventos->deleteEvent(); 
            echo "<script>alert('Evento excluído com sucesso!')</script>";
        }
        echo "<script> location.href = '/leisure_areas' </script>";
    }

    public function updateEvent(){
        $this->validateAuthentication();
        if($_POST['id_evento'] != '' && strlen($_POST['cpf']) == 14 && $_POST['titulo_evento'] != '' && $_POST['inicio_evento'] != '' && $_POST['fim_evento'] != ''){
            $moradores = Container::getModel('Residents');

            $morador_existente = false;
            $fk_id_morador = false;

            foreach($moradores->getAllResidentsRegisters() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    $morador_existente = true;
                    $fk_id_morador = $e['id_morador'];
                }
            }

            if($morador_existente && $fk_id_morador != false){
                $eventos = Container::getModel('Events');
                $eventos->id_evento = $_POST['id_evento'];
                $eventos->fk_id_morador = $fk_id_morador;
                $eventos->cpf = $_POST['cpf'];
                $eventos->titulo_evento = $_POST['titulo_evento'];
                $inicio_evento = str_replace('/', '-', $_POST['inicio_evento']);
                $eventos->inicio_evento = date("Y-m-d H:i:s", strtotime($inicio_evento));
                $fim_evento = str_replace('/', '-', $_POST['fim_evento']);
                $eventos->fim_evento = date("Y-m-d H:i:s", strtotime($fim_evento));
                $eventos->updateEvent();
                echo "<script>alert('Evento atualizado com sucesso!')</script>";
            }
            else{
                echo "<script>alert('Apenas moradores podem agendar eventos!')</script>";
            }
        }
        else if(strlen($_POST['cpf']) != 14){
            echo "<script>alert('Digite um CPF válido para atualizar o agendamento!')</script>";
        }
        else{
            echo "<script>alert('Preencha todos os campos para atualizar o agendamento!')</script>";
        }
        echo "<script>location.href = '/view_event?id_evento={$_POST['id_evento']}'</script>";
    }

    public function confirmPayment(){
        $this->validateAuthentication();
        if($_POST['id_evento'] != ''){
            $eventos = Container::getModel('Events');
            $eventos->id_evento = $_POST['id_evento'];
            $eventos->status_pagamento = 'Realizado';
            $eventos->confirmPayment();

            echo "<script>
                alert('Pagamento confirmado!');
                location.href = '/view_event?id_evento={$_POST['id_evento']}';
            </script>";
        }
        else{
            echo "<script>
                location.href = '/leisure_areas'
                alert('Erro ao confirmar pagamento, tente novamente!');
            </script>";
        }
    }
}

?>


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

    public function registerEvent(){
        $this->validateAuthentication();
        if($_POST['cpf'] != '' && strlen($_POST['cpf']) == 14 && $_POST['titulo_evento'] != '' && $_POST['inicio_evento'] != '' && $_POST['fim_evento'] != ''){
            $moradores = Container::getModel('Residents');

            $morador_existente = false;

            foreach($moradores->getAll() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    $morador_existente = true;
                }
            }

            if($morador_existente){
                $eventos = Container::getModel('Events');
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

    public function Events(){
        $this->validateAuthentication();
        $eventos = Container::getModel('Events');
        $eventos->id_evento = $_GET['id_evento'];

        foreach($eventos->getAll() as $e){
            $eventos->cpf = $e['cpf'];
        }

        $this->view->eventos = $eventos->getAllData();

        $this->render('view_event');
    }

    public function deleteEvents(){
        if(isset($_POST['id_evento'])){
            $eventos = Container::getModel('Events');
            $eventos->id_evento = $_POST['id_evento'];
            $eventos->deleteEvent(); 
            echo "<script>alert('Evento excluído com sucesso!')</script>";
        }
        echo "<script> location.href = '/leisure_areas' </script>";
    }

    public function updateEvents(){
        $this->validateAuthentication();
        if($_POST['id_evento'] != '' && $_POST['cpf'] != '' && strlen($_POST['cpf']) == 14 && $_POST['titulo_evento'] != '' && $_POST['inicio_evento'] != '' && $_POST['fim_evento'] != ''){
           
            $moradores = Container::getModel('Residents');

            $morador_existente = false;

            foreach($moradores->getAll() as $e){
                if($_POST['cpf'] == $e['cpf']){
                    $morador_existente = true;
                }
            }

            if($morador_existente){
                $eventos = Container::getModel('Events');
                $eventos->id_evento = $_POST['id_evento'];
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
}

?>


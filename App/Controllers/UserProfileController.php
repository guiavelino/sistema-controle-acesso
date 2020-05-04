<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

class UserProfileController extends Action {

    public function validateAuthentication(){
        session_start();
        if(!isset($_SESSION['id']) || $_SESSION['id'] == null){
            header('Location: /?login=erro');
        }
    }

	public function myProfile(){
        $this->validateAuthentication();    
        
        // Formatando exibição do campo Data de nascimento 
        $data_nascimento = str_replace("/", "-", $_SESSION['data_nascimento']);
        $_SESSION['data_nascimento'] = date('d/m/Y', strtotime($data_nascimento));

        $this->render('my_profile');
    }

    public function updateProfile(){
        $usuario = Container::getModel('Users');
        
        $coluna = '';
        $valor = '';

        if (isset($_POST['nome']) && strlen($_POST['nome']) >= 3){
            $coluna = 'nome';
            $valor = $_POST['nome'];
        }
        else if(isset($_POST['data_nascimento']) && strlen($_POST['data_nascimento']) == 10){
            $coluna = 'data_nascimento';
            $data_nascimento = str_replace("/", "-", $_POST['data_nascimento']);
            $valor = date('Y-m-d', strtotime($data_nascimento));
        }
        else if(isset($_POST['genero']) && $_POST['genero'] != ''){
            $coluna = 'genero';
            $valor = $_POST['genero'];
        }
        else if(isset($_POST['telefone']) && strlen($_POST['telefone']) >= 14){
            $coluna = 'telefone';
            $valor = $_POST['telefone'];
        }
        else if(isset($_FILES['imagem']) && $_FILES['imagem'] != ''){
            $coluna = 'imagem_usuario';
            $valor = $_FILES['imagem']['name'];
            move_uploaded_file($_FILES['imagem']['tmp_name'], "img/users/$valor");
        }
        else{
            echo "<script>alert('Erro ao atualizar dados!')</script>";
        }

        if($coluna != '' && $valor != ''){
            $usuario->updateProfile($coluna, $valor, $_POST['id_usuario']);
            echo "<script>alert('Dados atualizados com sucesso!')</script>";
        }        
        
        echo "<script>location.href = '/my_profile' </script>";
    }

}

?>
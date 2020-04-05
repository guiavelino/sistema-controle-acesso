<?php

namespace App\Controllers;

use App\Models\Users;
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {

	public function authenticate(){
        $usuario = Container::getModel('Users');
        $usuario->email = $_POST['email'];
        $usuario->senha = md5($_POST['senha']);

        $usuario->authenticate();
        
        if($usuario->id != null){
            session_start();
            $_SESSION['id'] = $usuario->id;
            $_SESSION['nome'] = $usuario->nome;
            $_SESSION['email'] = $usuario->email;
            $_SESSION['nivel_acesso'] = $usuario->nivel_acesso;
       
            header('Location: /dashboard');
        }
        else{
            header('Location: /?login=erro');
        }
    }

    public function signOut(){
        session_start();
        session_destroy();
        header('Location: /');
    }

}


?>
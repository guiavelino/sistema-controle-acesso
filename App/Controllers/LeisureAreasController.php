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
}

?>
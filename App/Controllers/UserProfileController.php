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

        $this->render('my_profile');
    }

}

?>
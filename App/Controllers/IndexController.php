<?php

namespace App\Controllers;

use App\Models\Users;
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {
		$this->render('index');
	}

	public function signUp() {
		$this->view->erroCadastro = false;
		$this->view->usuarioExistente = false;
		$this->view->usuario = [
			'nome' => '',
			'email' => '',
			'senha' => '',
			'confirmar_senha' => ''
		];

		$this->render('sign_up');
	}

	public function register() {
		$usuario = Container::getModel('Users');
		$usuario->nome = $_POST['nome'];
		$usuario->email = $_POST['email'];
		$usuario->senha = md5($_POST['senha']);
		$usuario->nivel_acesso = isset($_POST['user-admin']) ? $_POST['user-admin'] : '';

		if($usuario->validateRegistration() && $usuario->getUserByEmail() && $_POST['senha'] == $_POST['confirmar_senha']){
			$usuario->registerUser();
			$this->render('registration_success');
		}
		else{
			$this->view->usuario = [
				'nome' => $_POST['nome'],
				'email' => $_POST['email'],
				'senha' => $_POST['senha'],
				'confirmar_senha' => $_POST['confirmar_senha']
			];

			if(!$usuario->validateRegistration()){
				//Existem dados que não foram preenchidos
				$this->view->erroCadastroDados = 1;
			}
			else if($_POST['senha'] != $_POST['confirmar_senha']){
				//As senhas não correspondem
				$this->view->erroCadastroDados = 2;
			}
			else{
				//O e-mail ja foi cadastrado
				$this->view->erroCadastroDados = 3;
			}

			$this->render('sign_up');
		}
	}

}


?>
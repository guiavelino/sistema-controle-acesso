<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Users extends Model{

    private $id;
    private $nome;
    private $email;
    private $senha;
    private $nivel_acesso;

    public function __get($att){
        return $this->$att;
    }

    public function __set($att, $value){
        return $this->$att = $value;
    }

    public function validateRegistration(){
        if(strlen($this->nome) < 3 || strlen($this->email) < 3 || strlen($this->senha) < 3 || empty($this->nivel_acesso)){
            return false;
        }
        return true;
    }

    public function getUserByEmail(){
        $stmt = $this->db->prepare("SELECT * FROM usuarios where email = :email");
        $stmt->bindValue(":email", $this->email);
        $stmt->execute();

        //O usuário ja foi cadastrado
        if($stmt->rowCount() > 0){
            return false;
        }
        return true;
    }

    public function registerUser(){
        $stmt = $this->db->prepare("INSERT INTO usuarios(nome, email, senha, nivel_acesso) values(:nome, :email, :senha, :nivel_acesso)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(":senha", $this->senha);
        $stmt->bindValue(":nivel_acesso", $this->nivel_acesso);
        $stmt->execute();
    }

    public function authenticate(){
        $stmt = $this->db->prepare("SELECT * FROM usuarios where email = :email AND senha = :senha");
        $stmt->bindValue(":email", $this->email);
        $stmt->bindValue(":senha", $this->senha);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if(!empty($usuario['id_usuario'])){
            $this->id = $usuario['id_usuario'];
            $this->nome = $usuario['nome'];
            $this->email = $usuario['email'];
            $this->senha = $usuario['senha'];
            $this->nivel_acesso = $usuario['nivel_acesso']; 
        }
    }
}


?>
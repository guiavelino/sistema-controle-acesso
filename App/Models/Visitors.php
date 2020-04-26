<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Visitors extends Model{

    private $id_visitante;
    private $nome;
    private $cpf;
    private $telefone;
    private $apartamento;
    private $bloco;

    public function __get($att){
        return $this->$att;
    }

    public function __set($att, $value){
        return $this->$att = $value;
    }

    public function registerVisitor(){
        $stmt = $this->db->prepare("INSERT INTO visitantes(nome, cpf, telefone, apartamento, bloco) values(:nome, :cpf, :telefone, :apartamento, :bloco)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":telefone", $this->telefone);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->execute();
    }

    public function updateVisitor(){
        $stmt = $this->db->prepare("UPDATE visitantes SET nome = :nome, cpf = :cpf, telefone = :telefone, apartamento = :apartamento, bloco = :bloco where id_visitante = :id_visitante");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":telefone", $this->telefone);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->bindValue(":id_visitante", $this->id_visitante);
        $stmt->execute();
    }

    public function deleteVisitor(){
        $stmt = $this->db->prepare("DELETE from visitantes where id_visitante = :id_visitante");
        $stmt->bindValue(":id_visitante", $this->id_visitante);
        $stmt->execute();
    }

    public function getAll(){
        $stmt = $this->db->prepare("SELECT * FROM visitantes");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllM(){
        $stmt = $this->db->prepare("SELECT * FROM moradores");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllP(){
        $stmt = $this->db->prepare("SELECT * FROM prestadores_servicos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>
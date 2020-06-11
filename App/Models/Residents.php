<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Residents extends Model{

    private $id_morador;
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

    public function registerResident(){
        $stmt = $this->db->prepare("INSERT INTO moradores(nome, cpf, telefone, apartamento, bloco) values(:nome, :cpf, :telefone, :apartamento, :bloco)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":telefone", $this->telefone);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->execute();
    }

    public function updateResident(){
        $stmt = $this->db->prepare("UPDATE moradores SET nome = :nome, cpf = :cpf, telefone = :telefone, apartamento = :apartamento, bloco = :bloco where id_morador = :id_morador");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":telefone", $this->telefone);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->bindValue(":id_morador", $this->id_morador);
        $stmt->execute();
    }

    public function deleteResident(){
        $stmt = $this->db->prepare("DELETE from moradores where id_morador = :id_morador");
        $stmt->bindValue(":id_morador", $this->id_morador);
        $stmt->execute();
    }

    public function getAllResidentsRegisters(){
        $stmt = $this->db->prepare("SELECT * FROM moradores");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>
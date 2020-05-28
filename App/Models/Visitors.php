<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Visitors extends Model{

    private $id_visitante;
    private $nome;
    private $cpf;
    private $apartamento;
    private $bloco;
    private $data_saida;
    private $data_atual;

    public function __get($att){
        return $this->$att;
    }

    public function __set($att, $value){
        return $this->$att = $value;
    }

    public function registerVisitor(){
        $stmt = $this->db->prepare("INSERT INTO visitantes(nome, cpf, apartamento, bloco) values(:nome, :cpf, :apartamento, :bloco)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->execute();
    }

    public function registerExit(){
        $stmt = $this->db->prepare("UPDATE visitantes SET data_saida = :data_saida where id_visitante = :id_visitante");
        $stmt->bindValue(":data_saida", $this->data_saida);
        $stmt->bindValue(":id_visitante", $this->id_visitante);
        $stmt->execute();
    }

    public function updateVisitor(){
        $stmt = $this->db->prepare("UPDATE visitantes SET nome = :nome, cpf = :cpf, apartamento = :apartamento, bloco = :bloco where id_visitante = :id_visitante");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
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

    public function getAllVisitorsByDay(){
        $stmt = $this->db->prepare("SELECT count(*) as visitantes_por_dia FROM visitantes where Date(data_entrada) = :data_atual");
        $stmt->bindValue(":data_atual", $this->data_atual);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllVisitorsByMonth(){
        $stmt = $this->db->prepare("SELECT count(*) as visitantes_por_mes FROM visitantes WHERE MONTH(data_entrada) = MONTH(:data_atual) AND YEAR(data_entrada) = YEAR(:data_atual)");
        $stmt->bindValue(":data_atual", $this->data_atual);
        $stmt->execute();
        return $stmt->fetch();
    }
}


?>
<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Orders extends Model{

    private $id_encomenda;
    private $empresa;
    private $apartamento;
    private $bloco;

    public function __get($att){
        return $this->$att;
    }

    public function __set($att, $value){
        return $this->$att = $value;
    }

    public function registerOrder(){
        $stmt = $this->db->prepare("INSERT INTO encomendas(empresa, apartamento, bloco) values(:empresa, :apartamento, :bloco)");
        $stmt->bindValue(":empresa", $this->empresa);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->execute();

        return $this;
    }

    public function updateOrder(){
        $stmt = $this->db->prepare("UPDATE encomendas SET empresa = :empresa, apartamento = :apartamento, bloco = :bloco where id_encomenda = :id_encomenda");
        $stmt->bindValue(":empresa", $this->empresa);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->bindValue(":id_encomenda", $this->id_encomenda);
        $stmt->execute();
    }

    public function deleteOrder(){
        $stmt = $this->db->prepare("DELETE from encomendas where id_encomenda = :id_encomenda");
        $stmt->bindValue(":id_encomenda", $this->id_encomenda);
        $stmt->execute();
    }

    public function getAll(){
        $stmt = $this->db->prepare("SELECT * FROM encomendas");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


?>
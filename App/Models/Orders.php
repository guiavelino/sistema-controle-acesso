<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Orders extends Model{

    private $id_encomenda;
    private $empresa;
    private $apartamento;
    private $bloco;
    private $data_cadastro;

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

    public function getAllOrdersByDay(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_dia FROM encomendas where data_cadastro = :data_cadastro");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByMonth(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_mes FROM encomendas WHERE MONTH(data_cadastro) = MONTH(:data_cadastro) AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByJanuary(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 01 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByFebruary(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 02 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByMarch(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 03 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByApril(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 04 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByMay(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 05 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByJune(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 06 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByJuly(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 07 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByAugust(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 08 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersBySeptember(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 09 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByOctober(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 10 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByNovember(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 11 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByDecember(){
        $stmt = $this->db->prepare("SELECT count(*) as total_encomendas_por_janeiro FROM encomendas WHERE MONTH(data_cadastro) = 12 AND YEAR(data_cadastro) = YEAR(:data_cadastro)");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }
}

?>
<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Orders extends Model{

    private $id_encomenda;
    private $empresa;
    private $apartamento;
    private $bloco;
    private $data_atual;

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

    public function getAllOrdersRegisters(){
        $stmt = $this->db->prepare("SELECT * FROM encomendas");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllOrdersByDay(){
        $stmt = $this->db->prepare("SELECT count(*) as encomendas_por_dia FROM encomendas where Date(data_entrega) = :data_atual");
        $stmt->bindValue(":data_atual", $this->data_atual);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByMonth(){
        $stmt = $this->db->prepare("SELECT count(*) as encomendas_por_mes FROM encomendas WHERE MONTH(data_entrega) = MONTH(:data_atual) AND YEAR(data_entrega) = YEAR(:data_atual)");
        $stmt->bindValue(":data_atual", $this->data_atual);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllOrdersByYear(){
        $stmt = $this->db->prepare("SELECT 
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 01 AND YEAR(data_entrega) = YEAR(:data_atual)) as janeiro,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 02 AND YEAR(data_entrega) = YEAR(:data_atual)) as fevereiro,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 03 AND YEAR(data_entrega) = YEAR(:data_atual)) as marco,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 04 AND YEAR(data_entrega) = YEAR(:data_atual)) as abril,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 05 AND YEAR(data_entrega) = YEAR(:data_atual)) as maio,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 06 AND YEAR(data_entrega) = YEAR(:data_atual)) as junho,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 07 AND YEAR(data_entrega) = YEAR(:data_atual)) as julho,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 08 AND YEAR(data_entrega) = YEAR(:data_atual)) as agosto,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 09 AND YEAR(data_entrega) = YEAR(:data_atual)) as setembro,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 10 AND YEAR(data_entrega) = YEAR(:data_atual)) as outubro,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 11 AND YEAR(data_entrega) = YEAR(:data_atual)) as novembro,
                    (select count(*) FROM encomendas WHERE MONTH(data_entrega) = 12 AND YEAR(data_entrega) = YEAR(:data_atual)) as dezembro
                    from encomendas
                ");
        $stmt->bindValue(":data_atual", $this->data_atual);
        $stmt->execute();
        return $stmt->fetch();
    }
}

?>
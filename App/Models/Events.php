<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Events extends Model{

    private $id_evento;
    private $fk_id_morador;
    private $cpf;
    private $titulo_evento;
    private $inicio_evento;
    private $fim_evento;
    private $cor;

    public function __get($att){
        return $this->$att;
    }

    public function __set($att, $value){
        return $this->$att = $value;
    }

    public function registerEvent(){
        $stmt = $this->db->prepare("INSERT INTO eventos(cpf, titulo_evento, inicio_evento, fim_evento, cor, fk_id_morador) values(:cpf, :titulo_evento, :inicio_evento, :fim_evento, :cor, :fk_id_morador)");
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":titulo_evento", $this->titulo_evento);
        $stmt->bindValue(":inicio_evento", $this->inicio_evento);
        $stmt->bindValue(":fim_evento", $this->fim_evento);
        $stmt->bindValue(":cor", $this->cor);
        $stmt->bindValue(":fk_id_morador", $this->fk_id_morador);
        $stmt->execute();
    }

    public function updateEvent(){
        $stmt = $this->db->prepare("UPDATE eventos SET cpf = :cpf, titulo_evento = :titulo_evento, inicio_evento = :inicio_evento, fim_evento = :fim_evento, fk_id_morador = :fk_id_morador where id_evento = :id_evento");
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":titulo_evento", $this->titulo_evento);
        $stmt->bindValue(":inicio_evento", $this->inicio_evento);
        $stmt->bindValue(":fim_evento", $this->fim_evento);
        $stmt->bindValue(":fk_id_morador", $this->fk_id_morador);
        $stmt->bindValue(":id_evento", $this->id_evento);
        $stmt->execute();
    }

    public function deleteEvent(){
        $stmt = $this->db->prepare("DELETE from eventos where id_evento = :id_evento");
        $stmt->bindValue(":id_evento", $this->id_evento);
        $stmt->execute();
    }

    public function getAllEventsRegisters(){
        $stmt = $this->db->prepare("SELECT * FROM eventos where id_evento = :id_evento");
        $stmt->bindValue(":id_evento", $this->id_evento);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllEventAndResidentData(){
        $stmt = $this->db->prepare("SELECT * FROM eventos INNER join moradores on eventos.id_evento = :id_evento and moradores.id_morador =:fk_id_morador");
        $stmt->bindValue(":id_evento", $this->id_evento);
        $stmt->bindValue(":fk_id_morador", $this->fk_id_morador);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Events extends Model{

    private $id_evento;
    private $nome;
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
        $stmt = $this->db->prepare("INSERT INTO eventos(nome, cpf, titulo_evento, inicio_evento, fim_evento, cor) values(:nome, :cpf, :titulo_evento, :inicio_evento, :fim_evento, :cor)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":titulo_evento", $this->titulo_evento);
        $stmt->bindValue(":inicio_evento", $this->inicio_evento);
        $stmt->bindValue(":fim_evento", $this->fim_evento);
        $stmt->bindValue(":cor", $this->cor);
        $stmt->execute();
    }
}


?>
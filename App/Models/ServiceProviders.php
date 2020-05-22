<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class ServiceProviders extends Model{

    private $id_prestador_servico;
    private $nome;
    private $cpf;
    private $telefone;
    private $apartamento;
    private $bloco;
    private $data_cadastro;

    public function __get($att){
        return $this->$att;
    }

    public function __set($att, $value){
        return $this->$att = $value;
    }

    public function registerServiceProvider(){
        $stmt = $this->db->prepare("INSERT INTO prestadores_servicos(nome, cpf, telefone, apartamento, bloco) values(:nome, :cpf, :telefone, :apartamento, :bloco)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":telefone", $this->telefone);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->execute();
    }

    public function updateServiceProvider(){
        $stmt = $this->db->prepare("UPDATE prestadores_servicos SET nome = :nome, cpf = :cpf, telefone = :telefone, apartamento = :apartamento, bloco = :bloco where id_prestador_servico = :id_prestador_servico");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":telefone", $this->telefone);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->bindValue(":id_prestador_servico", $this->id_prestador_servico);
        $stmt->execute();
    }

    public function deleteServiceProvider(){
        $stmt = $this->db->prepare("DELETE from prestadores_servicos where id_prestador_servico = :id_prestador_servico");
        $stmt->bindValue(":id_prestador_servico", $this->id_prestador_servico);
        $stmt->execute();
    }

    public function getAll(){
        $stmt = $this->db->prepare("SELECT * FROM prestadores_servicos");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllServiceProviders(){
        $stmt = $this->db->prepare("SELECT count(*) as total_prestadores_servicos FROM prestadores_servicos where data_cadastro = :data_cadastro");
        $stmt->bindValue(":data_cadastro", $this->data_cadastro);
        $stmt->execute();
        return $stmt->fetch();
    }
}


?>
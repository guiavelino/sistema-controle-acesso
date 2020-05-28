<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class ServiceProviders extends Model{

    private $id_prestador_servico;
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

    public function registerServiceProvider(){
        $stmt = $this->db->prepare("INSERT INTO prestadores_servicos(nome, cpf, apartamento, bloco) values(:nome, :cpf, :apartamento, :bloco)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->execute();
    }

    public function registerExit(){
        $stmt = $this->db->prepare("UPDATE prestadores_servicos SET data_saida = :data_saida where id_prestador_servico = :id_prestador_servico");
        $stmt->bindValue(":data_saida", $this->data_saida);
        $stmt->bindValue(":id_prestador_servico", $this->id_prestador_servico);
        $stmt->execute();
    }

    public function updateServiceProvider(){
        $stmt = $this->db->prepare("UPDATE prestadores_servicos SET nome = :nome, cpf = :cpf, apartamento = :apartamento, bloco = :bloco where id_prestador_servico = :id_prestador_servico");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
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

    public function getAllServiceProvidersByDay(){
        $stmt = $this->db->prepare("SELECT count(*) as prestadores_servicos_por_dia FROM prestadores_servicos where Date(data_entrada) = :data_atual");
        $stmt->bindValue(":data_atual", $this->data_atual);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllServiceProvidersByMonth(){
        $stmt = $this->db->prepare("SELECT count(*) as prestadores_servicos_por_mes FROM prestadores_servicos WHERE MONTH(data_entrada) = MONTH(:data_atual) AND YEAR(data_entrada) = YEAR(:data_atual)");
        $stmt->bindValue(":data_atual", $this->data_atual);
        $stmt->execute();
        return $stmt->fetch();
    }
}

?>
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

    public function getAllServiceProvidersByYear(){
        $stmt = $this->db->prepare("SELECT 
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 01 AND YEAR(data_entrada) = YEAR(:data_atual)) as janeiro,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 02 AND YEAR(data_entrada) = YEAR(:data_atual)) as fevereiro,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 03 AND YEAR(data_entrada) = YEAR(:data_atual)) as marco,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 04 AND YEAR(data_entrada) = YEAR(:data_atual)) as abril,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 05 AND YEAR(data_entrada) = YEAR(:data_atual)) as maio,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 06 AND YEAR(data_entrada) = YEAR(:data_atual)) as junho,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 07 AND YEAR(data_entrada) = YEAR(:data_atual)) as julho,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 08 AND YEAR(data_entrada) = YEAR(:data_atual)) as agosto,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 09 AND YEAR(data_entrada) = YEAR(:data_atual)) as setembro,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 10 AND YEAR(data_entrada) = YEAR(:data_atual)) as outubro,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 11 AND YEAR(data_entrada) = YEAR(:data_atual)) as novembro,
                    (select count(*) FROM prestadores_servicos WHERE MONTH(data_entrada) = 12 AND YEAR(data_entrada) = YEAR(:data_atual)) as dezembro
                    from prestadores_servicos
                ");
        $stmt->bindValue(":data_atual", $this->data_atual);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllNumberServiceProvidersPresents(){
        $stmt = $this->db->prepare("SELECT count(*) as prestadores_servicos_presentes FROM prestadores_servicos WHERE data_saida is null");
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllServiceProvidersPresents(){
        $stmt = $this->db->prepare("SELECT * FROM prestadores_servicos WHERE data_saida is null");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>
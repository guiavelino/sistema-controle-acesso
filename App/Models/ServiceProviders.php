<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class ServiceProviders extends Model{

    private $id_prestador_servico;
    private $fk_id_prestador_servico;
    private $nome;
    private $cpf;
    private $rg;
    private $cpf_rg;
    private $uf;
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
        $stmt = $this->db->prepare("INSERT INTO prestadores_servicos_cadastrados(nome, cpf, rg, uf) values(:nome, :cpf, :rg, :uf)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":rg", $this->rg);
        $stmt->bindValue(":uf", $this->uf);
        $stmt->execute();
    }

    public function registerEntry(){
        $stmt = $this->db->prepare("INSERT INTO prestadores_servicos(nome, cpf_rg, uf, apartamento, bloco, fk_id_prestador_servico) values(:nome, :cpf_rg, :uf, :apartamento, :bloco, :fk_id_prestador_servico)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf_rg", $this->cpf_rg);
        $stmt->bindValue(":uf", $this->uf);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->bindValue(":fk_id_prestador_servico", $this->fk_id_prestador_servico);
        if($stmt->execute()){
            return true;
        }
    }

    public function registerExit(){
        $stmt = $this->db->prepare("UPDATE prestadores_servicos SET data_saida = :data_saida where id_prestador_servico = :id_prestador_servico");
        $stmt->bindValue(":data_saida", $this->data_saida);
        $stmt->bindValue(":id_prestador_servico", $this->id_prestador_servico);
        $stmt->execute();
    }

    public function updateServiceProvider(){
        $stmt = $this->db->prepare("UPDATE prestadores_servicos_cadastrados inner join prestadores_servicos on(prestadores_servicos_cadastrados.id_prestador_servico = :id_prestador_servico AND prestadores_servicos.fk_id_prestador_servico = :id_prestador_servico) SET prestadores_servicos_cadastrados.nome = :nome, prestadores_servicos_cadastrados.cpf = :cpf, prestadores_servicos_cadastrados.rg = :rg, prestadores_servicos_cadastrados.uf = :uf, prestadores_servicos.nome = :nome, prestadores_servicos.cpf_rg = :cpf_rg, prestadores_servicos.uf = :uf");
        $stmt->bindValue(":id_prestador_servico", $this->id_prestador_servico);
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":rg", $this->rg);
        $stmt->bindValue(":uf", $this->uf);
        $stmt->bindValue(":cpf_rg", $this->cpf_rg);

        if($stmt->execute()){
            return true;
        }
    }

    public function updateServiceProviderEntry(){ 
        $stmt = $this->db->prepare("UPDATE prestadores_servicos SET apartamento = :apartamento, bloco = :bloco where id_prestador_servico = :id_prestador_servico");;
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->bindValue(":id_prestador_servico", $this->id_prestador_servico); 
        $stmt->execute();
    }

    public function deleteServiceProvider(){
        $stmt = $this->db->prepare("DELETE from prestadores_servicos_cadastrados where id_prestador_servico = :id_prestador_servico");
        $stmt->bindValue(":id_prestador_servico", $this->id_prestador_servico);
        $stmt->execute();
    }

    public function deleteServiceProviderEntry(){
        $stmt = $this->db->prepare("DELETE from prestadores_servicos where id_prestador_servico = :id_prestador_servico");
        $stmt->bindValue(":id_prestador_servico", $this->id_prestador_servico);
        $stmt->execute();
    }

    public function getAllServiceProvidersRegisters(){
        $stmt = $this->db->prepare("SELECT * FROM prestadores_servicos_cadastrados");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRegistersEntry(){ 
        $stmt = $this->db->prepare("SELECT * FROM prestadores_servicos ORDER BY data_entrada desc");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectDocumentByCpfRgAndUF(){
        $stmt = $this->db->prepare("SELECT * FROM prestadores_servicos_cadastrados where cpf = :cpf OR  rg = :rg AND uf = :uf");
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":rg", $this->rg);
        $stmt->bindValue(":uf", $this->uf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function getAllNumberServiceProvidersPresents(){ //Retorna o número de Prestadores de serviços que estão com a saída em aberto 
        $stmt = $this->db->prepare("SELECT count(*) as prestadores_servicos_presentes FROM prestadores_servicos WHERE data_saida is null");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getAllServiceProvidersPresents(){ //Retorna os Prestadores de serviços que estão com a saída em aberto para realizar uma exibição ao usuário
        $stmt = $this->db->prepare("SELECT * FROM prestadores_servicos WHERE data_saida is null");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllServiceProvidersPresentsForCondition(){ //Retorna os Prestadores de serviços que estão com a saída em aberto, o método é utilizado  para realizar um teste condicional, onde Prestadores de serviços que não tiveram sua saída registrada não poderão realizar o registro de entrada
        $stmt = $this->db->prepare("SELECT * FROM prestadores_servicos WHERE data_saida is null AND cpf_rg = :cpf_rg AND uf = :uf");
        $stmt->bindValue(":cpf_rg", $this->cpf_rg);
        $stmt->bindValue(":uf", $this->uf);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return true; // Esse Prestador de serviço está presente no condomínio, para realizar o registro de entrada primeiro é necessário registrar a saída
        }
    }
}

?>
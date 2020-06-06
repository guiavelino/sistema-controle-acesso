<?php

namespace App\Models;
use MF\Model\Model;
use PDO;

class Visitors extends Model{

    private $id_visitante;
    private $fk_id_visitante;
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

    public function registerVisitor(){
        $stmt = $this->db->prepare("INSERT INTO visitantes_cadastrados(nome, cpf, rg, uf) values(:nome, :cpf, :rg, :uf)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":rg", $this->rg);
        $stmt->bindValue(":uf", $this->uf);
        if($stmt->execute()){
            return true;
        }
    }

    public function registerEntry(){
        $stmt = $this->db->prepare("INSERT INTO visitantes(nome, cpf_rg, uf, apartamento, bloco, fk_id_visitante) values(:nome, :cpf_rg, :uf, :apartamento, :bloco, :fk_id_visitante)");
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf_rg", $this->cpf_rg);
        $stmt->bindValue(":uf", $this->uf);
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->bindValue(":fk_id_visitante", $this->fk_id_visitante);
        if($stmt->execute()){
            return true;
        }
    }

    public function registerExit(){
        $stmt = $this->db->prepare("UPDATE visitantes SET data_saida = :data_saida where id_visitante = :id_visitante");
        $stmt->bindValue(":data_saida", $this->data_saida);
        $stmt->bindValue(":id_visitante", $this->id_visitante);
        $stmt->execute();
    }

    public function updateVisitor(){
        $stmt = $this->db->prepare("UPDATE visitantes_cadastrados inner join visitantes on(visitantes_cadastrados.id_visitante = :id_visitante AND visitantes.fk_id_visitante = :id_visitante) SET visitantes_cadastrados.nome = :nome, visitantes_cadastrados.cpf = :cpf, visitantes_cadastrados.rg = :rg, visitantes_cadastrados.uf = :uf, visitantes.nome = :nome, visitantes.cpf_rg = :cpf_rg, visitantes.uf = :uf");
        $stmt->bindValue(":id_visitante", $this->id_visitante);
        $stmt->bindValue(":nome", $this->nome);
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":rg", $this->rg);
        $stmt->bindValue(":uf", $this->uf);
        $stmt->bindValue(":cpf_rg", $this->cpf_rg);

        if($stmt->execute()){
            return true;
        }
    }

    public function updateVisitorEntry(){ 
        $stmt = $this->db->prepare("UPDATE visitantes SET apartamento = :apartamento, bloco = :bloco where id_visitante = :id_visitante");;
        $stmt->bindValue(":apartamento", $this->apartamento);
        $stmt->bindValue(":bloco", $this->bloco);
        $stmt->bindValue(":id_visitante", $this->id_visitante); 
        $stmt->execute();
    }

    public function deleteVisitor(){
        $stmt = $this->db->prepare("DELETE from visitantes_cadastrados where id_visitante = :id_visitante");
        $stmt->bindValue(":id_visitante", $this->id_visitante);
        $stmt->execute();
    }

    public function deleteVisitorEntry(){
        $stmt = $this->db->prepare("DELETE from visitantes where id_visitante = :id_visitante");
        $stmt->bindValue(":id_visitante", $this->id_visitante);
        $stmt->execute();
    }

    public function getAllVisitorsRegisters(){ 
        $stmt = $this->db->prepare("SELECT * FROM visitantes_cadastrados ORDER BY id_visitante desc");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllRegistersEntry(){ 
        $stmt = $this->db->prepare("SELECT * FROM visitantes ORDER BY data_entrada desc");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectDocumentByCpfRgAndUF(){
        $stmt = $this->db->prepare("SELECT * FROM visitantes_cadastrados where cpf = :cpf OR  rg = :rg AND uf = :uf");
        $stmt->bindValue(":cpf", $this->cpf);
        $stmt->bindValue(":rg", $this->rg);
        $stmt->bindValue(":uf", $this->uf);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function getAllVisitorsByYear(){
        $stmt = $this->db->prepare("SELECT 
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 01 AND YEAR(data_entrada) = YEAR(:data_atual)) as janeiro,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 02 AND YEAR(data_entrada) = YEAR(:data_atual)) as fevereiro,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 03 AND YEAR(data_entrada) = YEAR(:data_atual)) as marco,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 04 AND YEAR(data_entrada) = YEAR(:data_atual)) as abril,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 05 AND YEAR(data_entrada) = YEAR(:data_atual)) as maio,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 06 AND YEAR(data_entrada) = YEAR(:data_atual)) as junho,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 07 AND YEAR(data_entrada) = YEAR(:data_atual)) as julho,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 08 AND YEAR(data_entrada) = YEAR(:data_atual)) as agosto,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 09 AND YEAR(data_entrada) = YEAR(:data_atual)) as setembro,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 10 AND YEAR(data_entrada) = YEAR(:data_atual)) as outubro,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 11 AND YEAR(data_entrada) = YEAR(:data_atual)) as novembro,
                    (select count(*) FROM visitantes WHERE MONTH(data_entrada) = 12 AND YEAR(data_entrada) = YEAR(:data_atual)) as dezembro
                    from visitantes
                ");
        $stmt->bindValue(":data_atual", $this->data_atual);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getAllNumberVisitorsPresents(){ //Retorna o número de visitantes que estão com a saída em aberto 
        $stmt = $this->db->prepare("SELECT count(*) as visitantes_presentes FROM visitantes WHERE data_saida is null");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllVisitorsPresents(){ //Retorna os visitantes que estão com a saída em aberto para realizar uma exibição ao usuário
        $stmt = $this->db->prepare("SELECT * FROM visitantes WHERE data_saida is null");
        $stmt->bindValue(":fk_id_visitante", $this->fk_id_visitante);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllVisitorsPresentsForCondition(){ //Retorna os visitantes que estão com a saída em aberto, o método é utilizado  para realizar um teste condicional, onde visitantes que não tiveram sua saída registrada não poderão realizar o registro de entrada
        $stmt = $this->db->prepare("SELECT * FROM visitantes WHERE data_saida is null AND cpf_rg = :cpf_rg AND uf = :uf");
        $stmt->bindValue(":cpf_rg", $this->cpf_rg);
        $stmt->bindValue(":uf", $this->uf);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            return true; // Esse visitante está presente no condomínio, para realizar o registro de entrada primeiro é necessário registrar a saída
        }
    }
}
?>
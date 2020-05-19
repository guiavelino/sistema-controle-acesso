<?php

    $conn = new \PDO(
        "mysql:host=localhost:3307;dbname=controle_de_acesso;charset=utf8",
        "root",
        "",
        array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") 
    );

    $stmt = $conn->prepare("SELECT * from eventos");
    $stmt->execute();
    $eventos = [];

    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $e){
        $eventos[] = ['id' => $e['id_evento'], 'title' => $e['titulo_evento'], 'color' => $e['cor'], 'start' => $e['inicio_evento'], 'end' => $e['fim_evento']];
    }

    echo json_encode($eventos);
?>
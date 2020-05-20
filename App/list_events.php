<?php

    require_once 'Connection.php';
    $conn =  $_SESSION['conexao'];
    $stmt = $conn->prepare("SELECT * from eventos");
    $stmt->execute();
    $eventos = [];
    
    foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $e){
        $eventos[] = ['id' => $e['id_evento'], 'title' => $e['titulo_evento'], 'color' => $e['cor'], 'start' => $e['inicio_evento'], 'end' => $e['fim_evento']];
    }
    echo json_encode($eventos);
?>
<?php

namespace App;

class Connection {

	public static function getDb() {
		try {

			$conn = new \PDO(
				"mysql:host=localhost:3307;dbname=controle_de_acesso;charset=utf8",
				"root",
				"",
				array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") 
			);

			return $conn;
			
		} catch (\PDOException $e) {
			echo "<p style='color:#ff0000'>Erro: ".$e->getCode().'<br> Detalhes do erro: '.$e->getMessage()."</p>";
		}
	}
}

?>
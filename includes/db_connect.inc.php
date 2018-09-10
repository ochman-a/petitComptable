<?php
    function db_connect() {
		$host = "localhost";
		$dbname = "petit_comptable";
		$user = "root";
		$password = "";
		//$db = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
		$db = new PDO(
					"mysql:host=$host;dbname=$dbname", 
					$user, 
					$password, 
					array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", PDO::MYSQL_ATTR_DIRECT_QUERY=>true));
		return $db;
    }
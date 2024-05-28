<?php
	require("constants.php");

	try{
		$pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password); 
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch(Exception $e){
		exit ('Ошибка подключения: <br><br>' . $e -> getMessage());
	}
		
?>
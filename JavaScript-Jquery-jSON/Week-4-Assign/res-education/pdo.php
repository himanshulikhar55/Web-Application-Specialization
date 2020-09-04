<?php
	ini_set('display_errors', 1);
	ini_set('display_startup', 1);
	try{
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=employees;','root','root');
	}
	catch(PDOException $e){
		die($e->message);
	}
?>
